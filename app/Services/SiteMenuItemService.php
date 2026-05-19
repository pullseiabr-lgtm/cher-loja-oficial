<?php

namespace App\Services;

use App\Models\SiteMenu;
use App\Models\SiteMenuItem;
use Illuminate\Http\Request;

class SiteMenuItemService
{
    public function list(SiteMenu $siteMenu)
    {
        return $siteMenu->allItems()->with(['children', 'category', 'page'])->get();
    }

    public function store(Request $request, SiteMenu $siteMenu): SiteMenuItem
    {
        $maxOrder = $siteMenu->allItems()
                             ->where('parent_id', $request->parent_id ?? null)
                             ->max('sort_order') ?? 0;

        return SiteMenuItem::create([
            'site_menu_id' => $siteMenu->id,
            'parent_id'    => $request->parent_id ?: null,
            'title'        => $request->title,
            'type'         => $request->type ?? 'custom',
            'reference_id' => $request->reference_id ?: null,
            'url'          => $request->url ?: null,
            'target'       => $request->target ?? '_self',
            'sort_order'   => $maxOrder + 1,
            'status'       => $request->status ?? 1,
        ]);
    }

    public function update(Request $request, SiteMenuItem $item): SiteMenuItem
    {
        $item->update([
            'title'        => $request->title,
            'type'         => $request->type ?? 'custom',
            'parent_id'    => $request->parent_id ?: null,
            'reference_id' => $request->reference_id ?: null,
            'url'          => $request->url ?: null,
            'target'       => $request->target ?? '_self',
            'status'       => $request->status ?? 1,
        ]);
        return $item;
    }

    public function destroy(SiteMenuItem $item): void
    {
        $item->delete();
    }

    public function reorder(array $orderedIds): void
    {
        foreach ($orderedIds as $index => $id) {
            SiteMenuItem::where('id', $id)->update(['sort_order' => $index + 1]);
        }
    }

    public function moveUp(SiteMenuItem $item): void
    {
        $prev = SiteMenuItem::where('site_menu_id', $item->site_menu_id)
                            ->where('parent_id', $item->parent_id)
                            ->where('sort_order', '<', $item->sort_order)
                            ->orderBy('sort_order', 'desc')
                            ->first();
        if ($prev) {
            $tmp = $prev->sort_order;
            $prev->update(['sort_order' => $item->sort_order]);
            $item->update(['sort_order' => $tmp]);
        }
    }

    public function moveDown(SiteMenuItem $item): void
    {
        $next = SiteMenuItem::where('site_menu_id', $item->site_menu_id)
                            ->where('parent_id', $item->parent_id)
                            ->where('sort_order', '>', $item->sort_order)
                            ->orderBy('sort_order', 'asc')
                            ->first();
        if ($next) {
            $tmp = $next->sort_order;
            $next->update(['sort_order' => $item->sort_order]);
            $item->update(['sort_order' => $tmp]);
        }
    }
}
