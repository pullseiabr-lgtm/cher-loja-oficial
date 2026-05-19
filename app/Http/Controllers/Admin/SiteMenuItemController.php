<?php

namespace App\Http\Controllers\Admin;

use Exception;
use App\Models\SiteMenu;
use App\Models\SiteMenuItem;
use App\Services\SiteMenuItemService;
use App\Http\Requests\SiteMenuItemRequest;
use App\Http\Resources\SiteMenuItemResource;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Routing\Controllers\HasMiddleware;

class SiteMenuItemController extends AdminController implements HasMiddleware
{
    private SiteMenuItemService $siteMenuItemService;

    public function __construct(SiteMenuItemService $siteMenuItemService)
    {
        parent::__construct();
        $this->siteMenuItemService = $siteMenuItemService;
    }

    public static function middleware(): array
    {
        return [
            new Middleware('permission:settings'),
        ];
    }

    public function index(SiteMenu $siteMenu)
    {
        try {
            return SiteMenuItemResource::collection(
                $this->siteMenuItemService->list($siteMenu)
            );
        } catch (Exception $e) {
            return response(['status' => false, 'message' => $e->getMessage()], 422);
        }
    }

    public function store(SiteMenuItemRequest $request, SiteMenu $siteMenu)
    {
        try {
            return new SiteMenuItemResource(
                $this->siteMenuItemService->store($request, $siteMenu)
            );
        } catch (Exception $e) {
            return response(['status' => false, 'message' => $e->getMessage()], 422);
        }
    }

    public function update(SiteMenuItemRequest $request, SiteMenu $siteMenu, SiteMenuItem $siteMenuItem)
    {
        try {
            return new SiteMenuItemResource(
                $this->siteMenuItemService->update($request, $siteMenuItem)
            );
        } catch (Exception $e) {
            return response(['status' => false, 'message' => $e->getMessage()], 422);
        }
    }

    public function destroy(SiteMenu $siteMenu, SiteMenuItem $siteMenuItem)
    {
        try {
            $this->siteMenuItemService->destroy($siteMenuItem);
            return response('', 202);
        } catch (Exception $e) {
            return response(['status' => false, 'message' => $e->getMessage()], 422);
        }
    }

    public function moveUp(SiteMenu $siteMenu, SiteMenuItem $siteMenuItem)
    {
        try {
            $this->siteMenuItemService->moveUp($siteMenuItem);
            return response(['status' => true], 200);
        } catch (Exception $e) {
            return response(['status' => false, 'message' => $e->getMessage()], 422);
        }
    }

    public function moveDown(SiteMenu $siteMenu, SiteMenuItem $siteMenuItem)
    {
        try {
            $this->siteMenuItemService->moveDown($siteMenuItem);
            return response(['status' => true], 200);
        } catch (Exception $e) {
            return response(['status' => false, 'message' => $e->getMessage()], 422);
        }
    }
}
