<?php

namespace App\Http\Controllers\Admin;

use Exception;
use App\Models\PromotionSection;
use App\Http\Requests\PaginateRequest;
use App\Services\PromotionSectionService;
use App\Http\Requests\PromotionSectionRequest;
use App\Http\Resources\PromotionSectionResource;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Routing\Controllers\HasMiddleware;

class PromotionSectionController extends AdminController implements HasMiddleware
{
    private PromotionSectionService $promotionSectionService;

    public function __construct(PromotionSectionService $promotionSectionService)
    {
        parent::__construct();
        $this->promotionSectionService = $promotionSectionService;
    }

    public static function middleware(): array
    {
        return [
            new Middleware('permission:promotions', only: ['index', 'show', 'store', 'update', 'destroy']),
        ];
    }

    public function index(PaginateRequest $request): \Illuminate\Http\Response|\Illuminate\Http\Resources\Json\AnonymousResourceCollection|\Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Routing\ResponseFactory
    {
        try {
            return PromotionSectionResource::collection($this->promotionSectionService->list($request));
        } catch (Exception $exception) {
            return response(['status' => false, 'message' => $exception->getMessage()], 422);
        }
    }

    public function store(PromotionSectionRequest $request): \Illuminate\Http\Response|PromotionSectionResource|\Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Routing\ResponseFactory
    {
        try {
            return new PromotionSectionResource($this->promotionSectionService->store($request));
        } catch (Exception $exception) {
            return response(['status' => false, 'message' => $exception->getMessage()], 422);
        }
    }

    public function show(PromotionSection $promotionSection): \Illuminate\Http\Response|PromotionSectionResource|\Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Routing\ResponseFactory
    {
        try {
            return new PromotionSectionResource($this->promotionSectionService->show($promotionSection));
        } catch (Exception $exception) {
            return response(['status' => false, 'message' => $exception->getMessage()], 422);
        }
    }

    public function update(PromotionSectionRequest $request, PromotionSection $promotionSection): \Illuminate\Http\Response|PromotionSectionResource|\Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Routing\ResponseFactory
    {
        try {
            return new PromotionSectionResource($this->promotionSectionService->update($request, $promotionSection));
        } catch (Exception $exception) {
            return response(['status' => false, 'message' => $exception->getMessage()], 422);
        }
    }

    public function destroy(PromotionSection $promotionSection): \Illuminate\Http\Response|\Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Routing\ResponseFactory
    {
        try {
            $this->promotionSectionService->destroy($promotionSection);
            return response('', 202);
        } catch (Exception $exception) {
            return response(['status' => false, 'message' => $exception->getMessage()], 422);
        }
    }
}
