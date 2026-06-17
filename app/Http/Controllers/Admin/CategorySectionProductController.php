<?php

namespace App\Http\Controllers\Admin;

use Exception;
use App\Models\CategorySection;
use App\Models\CategorySectionProduct;
use App\Http\Requests\PaginateRequest;
use App\Services\CategorySectionProductService;
use App\Http\Resources\CategorySectionProductResource;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Routing\Controllers\HasMiddleware;

class CategorySectionProductController extends AdminController implements HasMiddleware
{
    private CategorySectionProductService $service;

    public function __construct(CategorySectionProductService $service)
    {
        parent::__construct();
        $this->service = $service;
    }

    public static function middleware(): array
    {
        return [
            new Middleware('permission:settings', only: ['index', 'store', 'destroy']),
        ];
    }

    public function index(PaginateRequest $request, CategorySection $categorySection)
    {
        try {
            return CategorySectionProductResource::collection(
                $this->service->list($request, $categorySection)
            );
        } catch (Exception $e) {
            return response(['status' => false, 'message' => $e->getMessage()], 422);
        }
    }

    public function store(Request $request, CategorySection $categorySection)
    {
        try {
            $request->validate(['product_id' => 'required|integer|exists:products,id']);
            return new CategorySectionProductResource(
                $this->service->store($categorySection, $request->product_id)
            );
        } catch (Exception $e) {
            return response(['status' => false, 'message' => $e->getMessage()], 422);
        }
    }

    public function destroy(CategorySection $categorySection, CategorySectionProduct $categorySectionProduct)
    {
        try {
            $this->service->destroy($categorySection, $categorySectionProduct);
            return response('', 202);
        } catch (Exception $e) {
            return response(['status' => false, 'message' => $e->getMessage()], 422);
        }
    }
}
