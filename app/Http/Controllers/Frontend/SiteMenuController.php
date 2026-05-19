<?php

namespace App\Http\Controllers\Frontend;

use Exception;
use App\Http\Controllers\Controller;
use App\Services\SiteMenuService;
use App\Services\SiteMenuItemService;
use App\Http\Resources\SiteMenuItemResource;
use Illuminate\Http\Request;

class SiteMenuController extends Controller
{
    private SiteMenuService $siteMenuService;
    private SiteMenuItemService $siteMenuItemService;

    public function __construct(SiteMenuService $siteMenuService, SiteMenuItemService $siteMenuItemService)
    {
        $this->siteMenuService     = $siteMenuService;
        $this->siteMenuItemService = $siteMenuItemService;
    }

    public function byLocation(string $location)
    {
        try {
            $menu = $this->siteMenuService->getByLocation($location);
            if (!$menu) {
                return response(['status' => false, 'data' => []], 200);
            }
            $items = $this->siteMenuItemService->list($menu);
            return SiteMenuItemResource::collection($items);
        } catch (Exception $e) {
            return response(['status' => false, 'message' => $e->getMessage()], 422);
        }
    }
}
