<?php

namespace App\Http\Controllers\Admin;

use Exception;
use App\Models\CategorySection;
use App\Models\CategorySectionPromotion;
use App\Http\Requests\PaginateRequest;
use App\Services\CategorySectionPromotionService;
use App\Http\Resources\CategorySectionPromotionResource;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Routing\Controllers\HasMiddleware;

class CategorySectionPromotionController extends AdminController implements HasMiddleware
{
    private CategorySectionPromotionService $service;

    public function __construct(CategorySectionPromotionService $service)
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
            return CategorySectionPromotionResource::collection(
                $this->service->list($request, $categorySection)
            );
        } catch (Exception $e) {
            return response(['status' => false, 'message' => $e->getMessage()], 422);
        }
    }

    public function store(Request $request, CategorySection $categorySection)
    {
        try {
            $request->validate(['promotion_id' => 'required|integer|exists:promotions,id']);
            return new CategorySectionPromotionResource(
                $this->service->store($categorySection, $request->promotion_id)
            );
        } catch (Exception $e) {
            return response(['status' => false, 'message' => $e->getMessage()], 422);
        }
    }

    public function destroy(CategorySection $categorySection, CategorySectionPromotion $categorySectionPromotion)
    {
        try {
            $this->service->destroy($categorySection, $categorySectionPromotion);
            return response('', 202);
        } catch (Exception $e) {
            return response(['status' => false, 'message' => $e->getMessage()], 422);
        }
    }
}
