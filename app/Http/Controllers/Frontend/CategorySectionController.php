<?php

namespace App\Http\Controllers\Frontend;

use Exception;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Resources\CategorySectionWithCategoriesResource;
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
     * Returns active sections each with their active categories grouped.
     */
    public function categories(): \Illuminate\Foundation\Application|\Illuminate\Http\Response|\Illuminate\Http\Resources\Json\AnonymousResourceCollection|\Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Routing\ResponseFactory
    {
        try {
            $sections = $this->categorySectionService->activeSectionsWithCategories();
            return CategorySectionWithCategoriesResource::collection($sections);
        } catch (Exception $exception) {
            return response(['status' => false, 'message' => $exception->getMessage()], 422);
        }
    }
}
