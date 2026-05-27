<?php

namespace App\Services;

use Exception;
use App\Models\Testimonial;
use Illuminate\Support\Facades\Log;
use App\Http\Requests\PaginateRequest;
use App\Libraries\QueryExceptionLibrary;
use App\Http\Requests\TestimonialRequest;

class TestimonialService
{
    protected array $testimonialFilter = [
        'name',
        'status',
    ];

    /**
     * @throws Exception
     */
    public function list(PaginateRequest $request)
    {
        try {
            $requests    = $request->all();
            $method      = $request->get('paginate', 0) == 1 ? 'paginate' : 'get';
            $methodValue = $request->get('paginate', 0) == 1 ? $request->get('per_page', 10) : '*';
            $orderColumn = $request->get('order_column') ?? 'sort_order';
            $orderType   = $request->get('order_type') ?? 'asc';

            return Testimonial::with('media')->where(function ($query) use ($requests) {
                foreach ($requests as $key => $value) {
                    if (in_array($key, $this->testimonialFilter)) {
                        $query->where($key, 'like', '%' . $value . '%');
                    }
                }
            })->orderBy($orderColumn, $orderType)->$method($methodValue);
        } catch (Exception $exception) {
            Log::info($exception->getMessage());
            throw new Exception(QueryExceptionLibrary::message($exception), 422);
        }
    }

    /**
     * @throws Exception
     */
    public function activeList()
    {
        try {
            return Testimonial::with('media')->active()->orderBy('sort_order', 'asc')->orderBy('id', 'asc')->get();
        } catch (Exception $exception) {
            Log::info($exception->getMessage());
            throw new Exception(QueryExceptionLibrary::message($exception), 422);
        }
    }

    /**
     * @throws Exception
     */
    public function store(TestimonialRequest $request): Testimonial
    {
        try {
            $testimonial = Testimonial::create([
                'name'       => $request->name,
                'content'    => $request->content,
                'rating'     => $request->rating,
                'status'     => $request->status,
                'sort_order' => $request->sort_order ?? 0,
            ]);
            if ($request->image) {
                $testimonial->addMediaFromRequest('image')->toMediaCollection('testimonial');
            }
            return $testimonial;
        } catch (Exception $exception) {
            Log::info($exception->getMessage());
            throw new Exception(QueryExceptionLibrary::message($exception), 422);
        }
    }

    /**
     * @throws Exception
     */
    public function update(TestimonialRequest $request, Testimonial $testimonial): Testimonial
    {
        try {
            $testimonial->update([
                'name'       => $request->name,
                'content'    => $request->content,
                'rating'     => $request->rating,
                'status'     => $request->status,
                'sort_order' => $request->sort_order ?? $testimonial->sort_order,
            ]);
            if ($request->image) {
                $testimonial->clearMediaCollection('testimonial');
                $testimonial->addMediaFromRequest('image')->toMediaCollection('testimonial');
            }
            return $testimonial;
        } catch (Exception $exception) {
            Log::info($exception->getMessage());
            throw new Exception(QueryExceptionLibrary::message($exception), 422);
        }
    }

    /**
     * @throws Exception
     */
    public function destroy(Testimonial $testimonial): void
    {
        try {
            $testimonial->clearMediaCollection('testimonial');
            $testimonial->delete();
        } catch (Exception $exception) {
            Log::info($exception->getMessage());
            throw new Exception(QueryExceptionLibrary::message($exception), 422);
        }
    }

    /**
     * @throws Exception
     */
    public function show(Testimonial $testimonial): Testimonial
    {
        try {
            return $testimonial;
        } catch (Exception $exception) {
            Log::info($exception->getMessage());
            throw new Exception(QueryExceptionLibrary::message($exception), 422);
        }
    }
}
