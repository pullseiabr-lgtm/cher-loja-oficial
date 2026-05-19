<?php

namespace App\Http\Controllers\Frontend;

use Exception;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Resources\ProductCategoryResource;
use App\Services\CategorySectionService;

class CategorySectionController extends AdminController
{
    private CategorySectionService $categorySectionService;

    public function __construct(CategorySectionService $categorySectionService)
    {
        parent::__construct();
        $this->categorySectionService = $categorySectionService;
    }

    /**
     * Returns a flat list of active product categories from all active category sections.
     */
    public function categories(): \Illuminate\Foundation\Application|\Illuminate\Http\Response|\Illuminate\Http\Resources\Json\AnonymousResourceCollection|\Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Routing\ResponseFactory
    {
        try {
            $categories = $this->categorySectionService->activeSectionCategories();
            return ProductCategoryResource::collection($categories);
        } catch (Exception $exception) {
            return response(['status' => false, 'message' => $exception->getMessage()], 422);
        }
    }
}
