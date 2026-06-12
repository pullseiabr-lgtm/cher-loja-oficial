<?php

namespace App\Http\Controllers\Admin;

use Exception;
use App\Models\PromotionSection;
use App\Models\PromotionSectionPromotion;
use App\Http\Requests\PaginateRequest;
use App\Services\PromotionSectionPromotionService;
use App\Http\Requests\PromotionSectionPromotionRequest;
use App\Http\Resources\PromotionSectionPromotionResource;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Routing\Controllers\HasMiddleware;

class PromotionSectionPromotionController extends AdminController implements HasMiddleware
{
    private PromotionSectionPromotionService $promotionSectionPromotionService;

    public function __construct(PromotionSectionPromotionService $promotionSectionPromotionService)
    {
        parent::__construct();
        $this->promotionSectionPromotionService = $promotionSectionPromotionService;
    }

    public static function middleware(): array
    {
        return [
            new Middleware('permission:promotions', only: ['index', 'store', 'destroy']),
        ];
    }

    public function index(PaginateRequest $request, PromotionSection $promotionSection): \Illuminate\Http\Response|\Illuminate\Http\Resources\Json\AnonymousResourceCollection|\Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Routing\ResponseFactory
    {
        try {
            return PromotionSectionPromotionResource::collection(
                $this->promotionSectionPromotionService->list($request, $promotionSection)
            );
        } catch (Exception $exception) {
            return response(['status' => false, 'message' => $exception->getMessage()], 422);
        }
    }

    public function store(PromotionSectionPromotionRequest $request, PromotionSection $promotionSection): \Illuminate\Http\Response|PromotionSectionPromotionResource|\Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Routing\ResponseFactory
    {
        try {
            return new PromotionSectionPromotionResource(
                $this->promotionSectionPromotionService->store($request, $promotionSection)
            );
        } catch (Exception $exception) {
            return response(['status' => false, 'message' => $exception->getMessage()], 422);
        }
    }

    public function destroy(PromotionSection $promotionSection, PromotionSectionPromotion $promotionSectionPromotion): \Illuminate\Http\Response|\Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Routing\ResponseFactory
    {
        try {
            $this->promotionSectionPromotionService->destroy($promotionSection, $promotionSectionPromotion);
            return response('', 202);
        } catch (Exception $exception) {
            return response(['status' => false, 'message' => $exception->getMessage()], 422);
        }
    }
}
