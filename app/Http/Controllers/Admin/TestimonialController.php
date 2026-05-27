<?php

namespace App\Http\Controllers\Admin;

use Exception;
use App\Models\Testimonial;
use App\Services\TestimonialService;
use App\Services\TestimonialSettingService;
use App\Http\Requests\PaginateRequest;
use App\Http\Requests\TestimonialRequest;
use App\Http\Resources\TestimonialResource;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Routing\Controllers\HasMiddleware;

class TestimonialController extends AdminController implements HasMiddleware
{
    private TestimonialService $testimonialService;
    private TestimonialSettingService $testimonialSettingService;

    public function __construct(
        TestimonialService $testimonialService,
        TestimonialSettingService $testimonialSettingService
    ) {
        parent::__construct();
        $this->testimonialService        = $testimonialService;
        $this->testimonialSettingService = $testimonialSettingService;
    }

    public static function middleware(): array
    {
        return [
            new Middleware('permission:settings', only: ['index', 'store', 'update', 'destroy', 'show', 'setting', 'updateSetting']),
        ];
    }

    public function index(PaginateRequest $request): \Illuminate\Http\Response|\Illuminate\Http\Resources\Json\AnonymousResourceCollection|\Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Routing\ResponseFactory
    {
        try {
            return TestimonialResource::collection($this->testimonialService->list($request));
        } catch (Exception $exception) {
            return response(['status' => false, 'message' => $exception->getMessage()], 422);
        }
    }

    public function store(TestimonialRequest $request): TestimonialResource|\Illuminate\Http\Response|\Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Routing\ResponseFactory
    {
        try {
            return new TestimonialResource($this->testimonialService->store($request));
        } catch (Exception $exception) {
            return response(['status' => false, 'message' => $exception->getMessage()], 422);
        }
    }

    public function show(Testimonial $testimonial): TestimonialResource|\Illuminate\Http\Response|\Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Routing\ResponseFactory
    {
        try {
            return new TestimonialResource($this->testimonialService->show($testimonial));
        } catch (Exception $exception) {
            return response(['status' => false, 'message' => $exception->getMessage()], 422);
        }
    }

    public function update(TestimonialRequest $request, Testimonial $testimonial): TestimonialResource|\Illuminate\Http\Response|\Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Routing\ResponseFactory
    {
        try {
            return new TestimonialResource($this->testimonialService->update($request, $testimonial));
        } catch (Exception $exception) {
            return response(['status' => false, 'message' => $exception->getMessage()], 422);
        }
    }

    public function destroy(Testimonial $testimonial): \Illuminate\Http\Response|\Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Routing\ResponseFactory
    {
        try {
            $this->testimonialService->destroy($testimonial);
            return response('', 202);
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

    public function updateSetting(Request $request): \Illuminate\Http\Response|\Illuminate\Http\JsonResponse|\Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Routing\ResponseFactory
    {
        try {
            return response()->json(['data' => $this->testimonialSettingService->update($request)]);
        } catch (Exception $exception) {
            return response(['status' => false, 'message' => $exception->getMessage()], 422);
        }
    }
}
