<?php

namespace App\Services;

use Exception;
use App\Models\PromotionSection;
use App\Models\PromotionSectionPromotion;
use Illuminate\Support\Facades\Log;
use App\Http\Requests\PaginateRequest;
use App\Http\Requests\PromotionSectionPromotionRequest;
use App\Libraries\QueryExceptionLibrary;

class PromotionSectionPromotionService
{
    /**
     * @throws Exception
     */
    public function list(PaginateRequest $request, PromotionSection $promotionSection)
    {
        try {
            $method      = $request->get('paginate', 0) == 1 ? 'paginate' : 'get';
            $methodValue = $request->get('paginate', 0) == 1 ? $request->get('per_page', 10) : '*';

            return PromotionSectionPromotion::with('promotion')
                ->where('promotion_section_id', $promotionSection->id)
                ->orderBy('order')
                ->$method($methodValue);
        } catch (Exception $exception) {
            Log::info($exception->getMessage());
            throw new Exception(QueryExceptionLibrary::message($exception), 422);
        }
    }

    /**
     * @throws Exception
     */
    public function store(PromotionSectionPromotionRequest $request, PromotionSection $promotionSection): PromotionSectionPromotion
    {
        try {
            $already = PromotionSectionPromotion::where('promotion_section_id', $promotionSection->id)
                ->where('promotion_id', $request->promotion_id)
                ->exists();

            if ($already) {
                throw new Exception('Essa promoção já foi adicionada a esta seção.', 422);
            }

            $maxOrder = PromotionSectionPromotion::where('promotion_section_id', $promotionSection->id)->max('order') ?? 0;

            return PromotionSectionPromotion::create([
                'promotion_section_id' => $promotionSection->id,
                'promotion_id'         => $request->promotion_id,
                'order'                => $maxOrder + 1,
            ]);
        } catch (Exception $exception) {
            Log::info($exception->getMessage());
            throw new Exception($exception->getMessage(), $exception->getCode() ?: 422);
        }
    }

    /**
     * @throws Exception
     */
    public function destroy(PromotionSection $promotionSection, PromotionSectionPromotion $promotionSectionPromotion): void
    {
        try {
            if ($promotionSectionPromotion->promotion_section_id !== $promotionSection->id) {
                throw new Exception('Registro não pertence a esta seção.', 403);
            }
            $promotionSectionPromotion->delete();
        } catch (Exception $exception) {
            Log::info($exception->getMessage());
            throw new Exception($exception->getMessage(), $exception->getCode() ?: 422);
        }
    }
}
