<?php

namespace App\Http\Controllers\Admin;

use Exception;
use App\Models\Country;
use App\Services\CountryService;
use App\Http\Requests\CountryRequest;
use App\Http\Requests\PaginateRequest;
use App\Http\Resources\CountryResource;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Routing\Controllers\HasMiddleware;

class CountryController extends AdminController implements HasMiddleware
{

    private CountryService $countryService;

    public function __construct(CountryService $country)
    {
        parent::__construct();
        $this->countryService = $country;
    }

    public static function middleware(): array
    {
        return [
            new Middleware('permission:settings|administrators|customers|employees', only: ['index']),
            new Middleware('permission:settings', only: ['store', 'update', 'destroy', 'bulkDestroy', 'bulkStatusUpdate']),
        ];
    }

    public function index(PaginateRequest $request)  {
        try {
            return CountryResource::collection($this->countryService->list($request));
        } catch (Exception $exception) {
            return response(['status' => false, 'message' => $exception->getMessage()], 422);
        }
    }

    public function store(CountryRequest $request) 
    {
        try {
            return new CountryResource($this->countryService->store($request));
        } catch (Exception $exception) {
            return response(['status' => false, 'message' => $exception->getMessage()], 422);
        }
    }

    public function update(CountryRequest $request, Country $country) {
        try {
            return new CountryResource($this->countryService->update($request, $country));
        } catch (Exception $exception) {
            return response(['status' => false, 'message' => $exception->getMessage()], 422);
        }
    }

    public function destroy(Country $country) {
        try {
            $this->countryService->destroy($country);
            return response('', 202);
        } catch (Exception $exception) {
            return response(['status' => false, 'message' => $exception->getMessage()], 422);
        }
    }

    public function bulkDestroy(Request $request)
    {
        try {
            $ids = $request->validate(['ids' => 'required|array', 'ids.*' => 'integer'])['ids'];
            $this->countryService->bulkDestroy($ids);
            return response('', 202);
        } catch (Exception $exception) {
            return response(['status' => false, 'message' => $exception->getMessage()], 422);
        }
    }

    public function bulkStatusUpdate(Request $request)
    {
        try {
            $data = $request->validate([
                'ids'    => 'required|array',
                'ids.*'  => 'integer',
                'status' => 'required|integer',
            ]);
            $this->countryService->bulkStatusUpdate($data['ids'], $data['status']);
            return response('', 202);
        } catch (Exception $exception) {
            return response(['status' => false, 'message' => $exception->getMessage()], 422);
        }
    }
}
