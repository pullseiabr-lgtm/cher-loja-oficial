<?php

namespace App\Services;

use Exception;
use Illuminate\Support\Str;
use App\Models\CategorySection;
use Illuminate\Support\Facades\Log;
use App\Http\Requests\PaginateRequest;
use App\Http\Requests\CategorySectionRequest;
use App\Libraries\QueryExceptionLibrary;

class CategorySectionService
{
    protected array $sectionFilter = [
        'name',
        'slug',
        'status',
    ];

    protected array $exceptFilter = [
        'excepts'
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
            $orderColumn = $request->get('order_column') ?? 'id';
            $orderType   = $request->get('order_type') ?? 'desc';

            return CategorySection::where(function ($query) use ($requests) {
                foreach ($requests as $key => $request) {
                    if (in_array($key, $this->sectionFilter)) {
                        $query->where($key, 'like', '%' . $request . '%');
                    }

                    if (in_array($key, $this->exceptFilter)) {
                        $explodes = explode('|', $request);
                        if (is_array($explodes)) {
                            foreach ($explodes as $explode) {
                                $query->where('id', '!=', $explode);
                            }
                        }
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
    public function store(CategorySectionRequest $request): CategorySection
    {
        try {
            return CategorySection::create($request->validated() + ['slug' => Str::slug($request->name)]);
        } catch (Exception $exception) {
            Log::info($exception->getMessage());
            throw new Exception(QueryExceptionLibrary::message($exception), 422);
        }
    }

    /**
     * @throws Exception
     */
    public function update(CategorySectionRequest $request, CategorySection $categorySection): CategorySection
    {
        try {
            $categorySection->update($request->validated() + ['slug' => Str::slug($request->name)]);
            return $categorySection;
        } catch (Exception $exception) {
            Log::info($exception->getMessage());
            throw new Exception(QueryExceptionLibrary::message($exception), 422);
        }
    }

    /**
     * @throws Exception
     */
    public function destroy(CategorySection $categorySection): void
    {
        try {
            $categorySection->categorySectionCategories()->delete();
            $categorySection->delete();
        } catch (Exception $exception) {
            Log::info($exception->getMessage());
            throw new Exception(QueryExceptionLibrary::message($exception), 422);
        }
    }

    /**
     * @throws Exception
     */
    public function show(CategorySection $categorySection): CategorySection
    {
        try {
            return $categorySection;
        } catch (Exception $exception) {
            Log::info($exception->getMessage());
            throw new Exception(QueryExceptionLibrary::message($exception), 422);
        }
    }

    /**
     * Returns active sections each with their active categories (for frontend homepage).
     *
     * @throws Exception
     */
    public function activeSectionsWithCategories()
    {
        try {
            return CategorySection::active()
                ->with(['productCategories' => function ($query) {
                    $query->active()->with('media');
                }])
                ->orderBy('id', 'asc')
                ->get()
                ->filter(fn($section) => $section->productCategories->isNotEmpty())
                ->values();
        } catch (Exception $exception) {
            Log::info($exception->getMessage());
            throw new Exception(QueryExceptionLibrary::message($exception), 422);
        }
    }

    /**
     * Returns ALL active sections with type-appropriate content (unified frontend endpoint).
     *
     * @throws Exception
     */
    public function activeSectionsWithContent()
    {
        try {
            return CategorySection::active()
                ->with([
                    'productCategories' => function ($q) {
                        $q->active()->with('media');
                    },
                    'categorySectionProducts.product.variations',
                    'categorySectionPromotions.promotion',
                ])
                ->orderBy('id', 'asc')
                ->get();
        } catch (Exception $exception) {
            Log::info($exception->getMessage());
            throw new Exception(QueryExceptionLibrary::message($exception), 422);
        }
    }
}
