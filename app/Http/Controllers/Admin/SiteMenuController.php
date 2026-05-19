<?php

namespace App\Http\Controllers\Admin;

use Exception;
use App\Models\SiteMenu;
use App\Services\SiteMenuService;
use App\Http\Requests\PaginateRequest;
use App\Http\Requests\SiteMenuRequest;
use App\Http\Resources\SiteMenuResource;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Routing\Controllers\HasMiddleware;

class SiteMenuController extends AdminController implements HasMiddleware
{
    private SiteMenuService $siteMenuService;

    public function __construct(SiteMenuService $siteMenuService)
    {
        parent::__construct();
        $this->siteMenuService = $siteMenuService;
    }

    public static function middleware(): array
    {
        return [
            new Middleware('permission:settings', only: ['index', 'show', 'store', 'update', 'destroy']),
        ];
    }

    public function index(PaginateRequest $request)
    {
        try {
            return SiteMenuResource::collection($this->siteMenuService->list($request));
        } catch (Exception $e) {
            return response(['status' => false, 'message' => $e->getMessage()], 422);
        }
    }

    public function store(SiteMenuRequest $request)
    {
        try {
            return new SiteMenuResource($this->siteMenuService->store($request));
        } catch (Exception $e) {
            return response(['status' => false, 'message' => $e->getMessage()], 422);
        }
    }

    public function show(SiteMenu $siteMenu)
    {
        try {
            return new SiteMenuResource($this->siteMenuService->show($siteMenu));
        } catch (Exception $e) {
            return response(['status' => false, 'message' => $e->getMessage()], 422);
        }
    }

    public function update(SiteMenuRequest $request, SiteMenu $siteMenu)
    {
        try {
            return new SiteMenuResource($this->siteMenuService->update($request, $siteMenu));
        } catch (Exception $e) {
            return response(['status' => false, 'message' => $e->getMessage()], 422);
        }
    }

    public function destroy(SiteMenu $siteMenu)
    {
        try {
            $this->siteMenuService->destroy($siteMenu);
            return response('', 202);
        } catch (Exception $e) {
            return response(['status' => false, 'message' => $e->getMessage()], 422);
        }
    }
}
