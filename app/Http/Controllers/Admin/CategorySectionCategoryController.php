<?php

namespace App\Http\Controllers\Admin;

use Exception;
use App\Models\CategorySection;
use App\Models\CategorySectionCategory;
use App\Http\Requests\PaginateRequest;
use App\Services\CategorySectionCategoryService;
use App\Http\Requests\CategorySectionCategoryRequest;
use App\Http\Resources\CategorySectionCategoryResource;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Routing\Controllers\HasMiddleware;

class CategorySectionCategoryController extends AdminController implements HasMiddleware
{
    private CategorySectionCategoryService $categorySectionCategoryService;

    public function __construct(CategorySectionCategoryService $categorySectionCategoryService)
    {
        parent::__construct();
        $this->categorySectionCategoryService = $categorySectionCategoryService;
    }

    public static function middleware(): array
    {
        return [
            new Middleware('permission:settings', only: ['index', 'store', 'update', 'destroy']),
        ];
    }

    public function index(PaginateRequest $request, CategorySection $categorySection): \Illuminate\Http\Response|\Illuminate\Http\Resources\Json\AnonymousResourceCollection|\Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Routing\ResponseFactory
    {
        try {
            return CategorySectionCategoryResource::collection(
                $this->categorySectionCategoryService->list($request, $categorySection)
            );
        } catch (Exception $exception) {
            return response(['status' => false, 'message' => $exception->getMessage()], 422);
        }
    }

    public function store(CategorySectionCategoryRequest $request, CategorySection $categorySection): \Illuminate\Http\Response|CategorySectionCategoryResource|\Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Routing\ResponseFactory
    {
        try {
            return new CategorySectionCategoryResource(
                $this->categorySectionCategoryService->store($request, $categorySection)
            );
        } catch (Exception $exception) {
            return response(['status' => false, 'message' => $exception->getMessage()], 422);
        }
    }

    public function update(CategorySectionCategoryRequest $request, CategorySection $categorySection, CategorySectionCategory $categorySectionCategory): \Illuminate\Http\Response|CategorySectionCategoryResource|\Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Routing\ResponseFactory
    {
        try {
            return new CategorySectionCategoryResource(
                $this->categorySectionCategoryService->update($request, $categorySection, $categorySectionCategory)
            );
        } catch (Exception $exception) {
            return response(['status' => false, 'message' => $exception->getMessage()], 422);
        }
    }

    public function destroy(CategorySection $categorySection, CategorySectionCategory $categorySectionCategory): \Illuminate\Http\Response|\Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Routing\ResponseFactory
    {
        try {
            $this->categorySectionCategoryService->destroy($categorySection, $categorySectionCategory);
            return response('', 202);
        } catch (Exception $exception) {
            return response(['status' => false, 'message' => $exception->getMessage()], 422);
        }
    }
}
