<?php

namespace App\Http\Controllers\Frontend;

use Exception;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Resources\PromotionSectionWithPromotionsResource;
use App\Services\PromotionSectionService;

class PromotionSectionController extends AdminController
{
    private PromotionSectionService $promotionSectionService;

    public function __construct(PromotionSectionService $promotionSectionService)
    {
        parent::__construct();
        $this->promotionSectionService = $promotionSectionService;
    }

    public function sections(): \Illuminate\Foundation\Application|\Illuminate\Http\Response|\Illuminate\Http\Resources\Json\AnonymousResourceCollection|\Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Routing\ResponseFactory
    {
        try {
            $sections = $this->promotionSectionService->activeSectionsWithPromotions();
            return PromotionSectionWithPromotionsResource::collection($sections);
        } catch (Exception $exception) {
            return response(['status' => false, 'message' => $exception->getMessage()], 422);
        }
    }
}
