<?php

namespace App\Http\Controllers\Admin;

use Exception;
use App\Models\CategorySection;
use App\Http\Requests\PaginateRequest;
use App\Services\CategorySectionService;
use App\Http\Requests\CategorySectionRequest;
use App\Http\Resources\CategorySectionResource;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Routing\Controllers\HasMiddleware;

class CategorySectionController extends AdminController implements HasMiddleware
{
    private CategorySectionService $categorySectionService;

    public function __construct(CategorySectionService $categorySectionService)
    {
        parent::__construct();
        $this->categorySectionService = $categorySectionService;
    }

    public static function middleware(): array
    {
        return [
            new Middleware('permission:settings', only: ['index', 'show', 'store', 'update', 'destroy']),
        ];
    }

    public function index(PaginateRequest $request): \Illuminate\Http\Response|\Illuminate\Http\Resources\Json\AnonymousResourceCollection|\Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Routing\ResponseFactory
    {
        try {
            return CategorySectionResource::collection($this->categorySectionService->list($request));
        } catch (Exception $exception) {
            return response(['status' => false, 'message' => $exception->getMessage()], 422);
        }
    }

    public function store(CategorySectionRequest $request): \Illuminate\Http\Response|CategorySectionResource|\Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Routing\ResponseFactory
    {
        try {
            return new CategorySectionResource($this->categorySectionService->store($request));
        } catch (Exception $exception) {
            return response(['status' => false, 'message' => $exception->getMessage()], 422);
        }
    }

    public function show(CategorySection $categorySection): \Illuminate\Http\Response|CategorySectionResource|\Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Routing\ResponseFactory
    {
        try {
            return new CategorySectionResource($this->categorySectionService->show($categorySection));
        } catch (Exception $exception) {
            return response(['status' => false, 'message' => $exception->getMessage()], 422);
        }
    }

    public function update(CategorySectionRequest $request, CategorySection $categorySection): \Illuminate\Http\Response|CategorySectionResource|\Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Routing\ResponseFactory
    {
        try {
            return new CategorySectionResource($this->categorySectionService->update($request, $categorySection));
        } catch (Exception $exception) {
            return response(['status' => false, 'message' => $exception->getMessage()], 422);
        }
    }

    public function destroy(CategorySection $categorySection): \Illuminate\Http\Response|\Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Routing\ResponseFactory
    {
        try {
            $this->categorySectionService->destroy($categorySection);
            return response('', 202);
        } catch (Exception $exception) {
            return response(['status' => false, 'message' => $exception->getMessage()], 422);
        }
    }
}
