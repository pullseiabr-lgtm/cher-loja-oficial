<?php

namespace App\Services;

use Exception;
use App\Models\CategorySection;
use App\Models\CategorySectionCategory;
use Illuminate\Support\Facades\Log;
use App\Http\Requests\PaginateRequest;
use App\Http\Requests\CategorySectionCategoryRequest;
use App\Libraries\QueryExceptionLibrary;

class CategorySectionCategoryService
{
    protected array $categoryFilter = [
        'product_category_id',
    ];

    /**
     * @throws Exception
     */
    public function list(PaginateRequest $request, CategorySection $categorySection)
    {
        try {
            $requests    = $request->all();
            $method      = $request->get('paginate', 0) == 1 ? 'paginate' : 'get';
            $methodValue = $request->get('paginate', 0) == 1 ? $request->get('per_page', 10) : '*';
            $orderColumn = $request->get('order_column') ?? 'id';
            $orderType   = $request->get('order_type') ?? 'desc';

            return CategorySectionCategory::with('categorySection', 'productCategory')
                ->where(['category_section_id' => $categorySection->id])
                ->where(function ($query) use ($requests) {
                    foreach ($requests as $key => $request) {
                        if (in_array($key, $this->categoryFilter)) {
                            $query->where($key, 'like', '%' . $request . '%');
                        }
                    }
                })
                ->orderBy($orderColumn, $orderType)
                ->$method($methodValue);
        } catch (Exception $exception) {
            Log::info($exception->getMessage());
            throw new Exception(QueryExceptionLibrary::message($exception), 422);
        }
    }

    /**
     * @throws Exception
     */
    public function store(CategorySectionCategoryRequest $request, CategorySection $categorySection): CategorySectionCategory
    {
        try {
            $categorySectionCategory = CategorySectionCategory::create(
                $request->validated() + ['category_section_id' => $categorySection->id]
            );
            if ($request->image) {
                $categorySectionCategory->addMediaFromRequest('image')->toMediaCollection('category-section-category');
            }
            return $categorySectionCategory;
        } catch (Exception $exception) {
            Log::info($exception->getMessage());
            throw new Exception(QueryExceptionLibrary::message($exception), 422);
        }
    }

    /**
     * @throws Exception
     */
    public function update(CategorySectionCategoryRequest $request, CategorySection $categorySection, CategorySectionCategory $categorySectionCategory): CategorySectionCategory
    {
        try {
            if ($categorySection->id != $categorySectionCategory->category_section_id) {
                throw new Exception(trans('all.category_section_mismatch'), 422);
            }
            $categorySectionCategory->update($request->validated());
            if ($request->image) {
                $categorySectionCategory->clearMediaCollection('category-section-category');
                $categorySectionCategory->addMediaFromRequest('image')->toMediaCollection('category-section-category');
            }
            return $categorySectionCategory;
        } catch (Exception $exception) {
            Log::info($exception->getMessage());
            throw new Exception(QueryExceptionLibrary::message($exception), 422);
        }
    }

    public function destroy(CategorySection $categorySection, CategorySectionCategory $categorySectionCategory): void
    {
        try {
            if ($categorySection->id == $categorySectionCategory->category_section_id) {
                $categorySectionCategory->delete();
            } else {
                throw new Exception(trans('all.category_section_mismatch'), 422);
            }
        } catch (Exception $exception) {
            Log::info($exception->getMessage());
            throw new Exception(QueryExceptionLibrary::message($exception), 422);
        }
    }
}
