<?php

namespace App\Http\Controllers\Frontend;

use Exception;
use App\Http\Controllers\Controller;
use App\Services\TestimonialService;
use App\Services\TestimonialSettingService;
use App\Http\Resources\TestimonialResource;

class TestimonialController extends Controller
{
    private TestimonialService $testimonialService;
    private TestimonialSettingService $testimonialSettingService;

    public function __construct(
        TestimonialService $testimonialService,
        TestimonialSettingService $testimonialSettingService
    ) {
        $this->testimonialService        = $testimonialService;
        $this->testimonialSettingService = $testimonialSettingService;
    }

    public function index(): \Illuminate\Http\Response|\Illuminate\Http\Resources\Json\AnonymousResourceCollection|\Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Routing\ResponseFactory
    {
        try {
            return TestimonialResource::collection($this->testimonialService->activeList());
        } catch (Exception $exception) {
            return response(['status' => false, 'message' => $exception->getMessage()], 422);
        }
    }

    public function setting(): \Illuminate\Http\Response|\Illuminate\Http\JsonResponse|\Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Routing\ResponseFactory
    {
        try {
            return response()->json(['data' => $this->testimonialSettingService->list()]);
        } catch (Exception $exception) {
            return response(['status' => false, 'message' => $exception->getMessage()], 422);
        }
    }
}
