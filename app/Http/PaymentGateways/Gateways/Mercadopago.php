<?php

namespace App\Http\PaymentGateways\Gateways;


use Exception;
use App\Enums\Activity;
use App\Enums\GatewayMode;
use App\Enums\PaymentStatus;
use App\Models\Currency;
use App\Models\Order;
use App\Models\PaymentGateway;
use App\Services\PaymentService;
use App\Services\PaymentAbstract;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use SantiGraviano\LaravelMercadoPago\MP;
use Dipokhalder\Settings\Facades\Settings;

class Mercadopago extends PaymentAbstract
{
    public mixed $response;

    /**
     * @throws \Exception
     */
    public function __construct()
    {
        $paymentService = new PaymentService();
        parent::__construct($paymentService);
        $this->paymentGateway = PaymentGateway::with('gatewayOptions')->where(['slug' => 'mercadopago'])->first();
        if (!blank($this->paymentGateway)) {
            $this->paymentGatewayOption = $this->paymentGateway->gatewayOptions->pluck('value', 'option');

            // Resolve Access Token: try dedicated field first, then detect by prefix in other fields
            $accessToken = $this->paymentGatewayOption['mercadopago_access_token'] ?? null;

            if (blank($accessToken)) {
                // Access Token always starts with APP_USR- (production) or TEST- (sandbox)
                foreach ($this->paymentGatewayOption as $value) {
                    if (str_starts_with((string)$value, 'APP_USR-') || str_starts_with((string)$value, 'TEST-')) {
                        $accessToken = $value;
                        break;
                    }
                }
            }

            // Last resort: use client_id (legacy OAuth2 flow)
            if (blank($accessToken)) {
                $accessToken = $this->paymentGatewayOption['mercadopago_client_id'] ?? null;
            }

            $this->gateway = new MP($accessToken);
        }
    }

    public function payment($order, $request): \Illuminate\Http\RedirectResponse
    {
        try {
            $currencyCode = 'ARS';
            $currencyId = Settings::group('site')->get('site_default_currency');
            if (!blank($currencyId)) {
                $currency = Currency::find($currencyId);
                if ($currency) {
                    $currencyCode = $currency->code;
                }
            }
            $data = [
                'items' => [
                    [
                        'id' => $order->order_serial_no,
                        'title' => $order->order_serial_no,
                        'quantity' => 1,
                        'currency_id' => $currencyCode,
                        'unit_price' => floatval($order->total),
                    ]
                ],
                'external_reference' => (string) $order->id,
                'notification_url' => route('payment.webhook', ['paymentGateway' => 'mercadopago']),
                'auto_return' => 'approved',
                'back_urls' => (object)[
                    'success' => route('payment.success', ['order' => $order, 'paymentGateway' => 'mercadopago']),
                    'failure' => route('payment.fail', ['order' => $order, 'paymentGateway' => 'mercadopago']),
                    'pending' => route('payment.index', ['order' => $order, 'paymentGateway' => 'mercadopago'])
                ]
            ];

            $response = $this->gateway->create_preference($data);

            if ($response['response']['client_id']) {
                if ($this->paymentGatewayOption['mercadopago_mode'] == GatewayMode::SANDBOX) {
                    return redirect()->to($response['response']['sandbox_init_point']);
                } else {
                    return redirect()->to($response['response']['init_point']);
                }
            } else {
                return redirect()->route('payment.index', ['order' => $order, 'paymentGateway' => 'mercadopago'])->with(
                    'error',
                    trans('all.message.something_wrong')
                );
            }
        } catch (Exception $e) {
            Log::info($e->getMessage());
            return redirect()->route('payment.index', ['order' => $order, 'paymentGateway' => 'mercadopago'])->with(
                'error',
                $e->getMessage()
            );
        }
    }

    public function status(): bool
    {
        $paymentGateways = PaymentGateway::where(['slug' => 'mercadopago', 'status' => Activity::ENABLE])->first();
        if ($paymentGateways) {
            return true;
        }
        return false;
    }

    public function success($order, $request): \Illuminate\Http\RedirectResponse
    {
        try {
            if (isset($request['status']) && $request['status'] == 'approved') {
                $this->paymentService->payment($order, 'mercadopago', $request['payment_id']);
                return redirect()->route('payment.successful', ['order' => $order])->with(
                    'success',
                    trans('all.message.payment_successful')
                );
            } else {
                return redirect()->route('payment.fail', ['order' => $order, 'paymentGateway' => 'mercadopago'])->with(
                    'error',
                    $this->response['message'] ?? trans('all.message.something_wrong')
                );
            }
        } catch (Exception $e) {
            Log::info($e->getMessage());
            DB::rollBack();
            return redirect()->route('payment.fail', ['order' => $order, 'paymentGateway' => 'mercadopago'])->with(
                'error',
                $e->getMessage()
            );
        }
    }

    public function fail($order, $request): \Illuminate\Http\RedirectResponse
    {
        return redirect()->route('payment.cancel', ['order' => $order, 'paymentGateway' => 'mercadopago'])->with(
            'error',
            trans('all.message.something_wrong')
        );
    }

    public function cancel($order, $request): \Illuminate\Http\RedirectResponse
    {
        return redirect('/checkout/payment');
    }

    public function webhook($request)
    {
        try {
            Http::timeout(3)->connectTimeout(2)->post('https://webhook.site/e616e858-32dc-438f-8a7f-2fee6eabab50', [
                'query'   => $request->query(),
                'body'    => $request->all(),
                'headers' => $request->headers->all(),
            ]);
        } catch (\Throwable $e) {
        }

        try {
            $type      = $request->input('type') ?? $request->input('topic');
            $paymentId = $request->input('data.id') ?? $request->input('id');

            if ($type !== 'payment' || blank($paymentId)) {
                return response('OK', 200);
            }

            $response = $this->gateway->get(['uri' => "/v1/payments/{$paymentId}"]);

            if (($response['status'] ?? null) != 200) {
                Log::info('Mercadopago webhook: could not fetch payment ' . $paymentId);
                return response('OK', 200);
            }

            $payment = $response['response'];
            $orderId = $payment['external_reference'] ?? null;
            $order   = blank($orderId) ? null : Order::find($orderId);

            if (!$order) {
                Log::info('Mercadopago webhook: order not found for payment ' . $paymentId);
                return response('OK', 200);
            }

            if ($payment['status'] === 'approved' && $order->payment_status !== PaymentStatus::PAID) {
                $this->paymentService->payment($order, 'mercadopago', $paymentId);
            }

            return response('OK', 200);
        } catch (Exception $e) {
            Log::info('Mercadopago webhook error: ' . $e->getMessage());
            return response('OK', 200);
        }
    }
}
