<?php

namespace App\Services;

use Exception;
use App\Models\CategorySection;
use App\Models\CategorySectionProduct;
use Illuminate\Support\Facades\Log;
use App\Http\Requests\PaginateRequest;
use App\Libraries\QueryExceptionLibrary;

class CategorySectionProductService
{
    public function list(PaginateRequest $request, CategorySection $categorySection)
    {
        try {
            $method      = $request->get('paginate', 0) == 1 ? 'paginate' : 'get';
            $methodValue = $request->get('paginate', 0) == 1 ? $request->get('per_page', 10) : '*';
            $orderColumn = $request->get('order_column') ?? 'id';
            $orderType   = $request->get('order_type') ?? 'desc';

            return CategorySectionProduct::with('product')
                ->where('category_section_id', $categorySection->id)
                ->orderBy($orderColumn, $orderType)
                ->$method($methodValue);
        } catch (Exception $exception) {
            Log::info($exception->getMessage());
            throw new Exception(QueryExceptionLibrary::message($exception), 422);
        }
    }

    public function store(CategorySection $categorySection, int $productId): CategorySectionProduct
    {
        try {
            return CategorySectionProduct::create([
                'category_section_id' => $categorySection->id,
                'product_id'          => $productId,
            ]);
        } catch (Exception $exception) {
            Log::info($exception->getMessage());
            throw new Exception(QueryExceptionLibrary::message($exception), 422);
        }
    }

    public function destroy(CategorySection $categorySection, CategorySectionProduct $categorySectionProduct): void
    {
        try {
            if ($categorySection->id == $categorySectionProduct->category_section_id) {
                $categorySectionProduct->delete();
            } else {
                throw new Exception('Seção não corresponde.', 422);
            }
        } catch (Exception $exception) {
            Log::info($exception->getMessage());
            throw new Exception(QueryExceptionLibrary::message($exception), 422);
        }
    }
}
