<?php

namespace App\Services;

use Exception;
use App\Models\SiteMenu;
use Illuminate\Http\Request;
use App\Http\Requests\PaginateRequest;

class SiteMenuService
{
    public function list(PaginateRequest $request)
    {
        $filterArr = ['name', 'location', 'status'];
        $q = SiteMenu::query();
        foreach ($filterArr as $field) {
            if ($request->filled($field)) {
                $q->where($field, $request->$field);
            }
        }
        if ($request->filled('order_column') && $request->filled('order_type')) {
            $q->orderBy($request->order_column, $request->order_type);
        } else {
            $q->orderBy('id', 'asc');
        }
        if ($request->filled('paginate') && $request->paginate == 0) {
            return $q->get();
        }
        return $q->paginate($request->per_page ?? 15);
    }

    public function store(Request $request): SiteMenu
    {
        return SiteMenu::create($request->only(['name', 'location', 'status']));
    }

    public function show(SiteMenu $siteMenu): SiteMenu
    {
        return $siteMenu;
    }

    public function update(Request $request, SiteMenu $siteMenu): SiteMenu
    {
        $siteMenu->update($request->only(['name', 'location', 'status']));
        return $siteMenu;
    }

    public function destroy(SiteMenu $siteMenu): void
    {
        $siteMenu->delete();
    }

    public function getByLocation(string $location): ?SiteMenu
    {
        return SiteMenu::where('location', $location)
                       ->where('status', 1)
                       ->first();
    }
}
