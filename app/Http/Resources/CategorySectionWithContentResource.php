<?php

namespace App\Http\Resources;

use App\Enums\Ask;
use App\Libraries\AppLibrary;
use Illuminate\Http\Resources\Json\JsonResource;

class CategorySectionWithContentResource extends JsonResource
{
    public function toArray($request): array
    {
        $type = $this->type ?? 'categories';

        return [
            'id'             => $this->id,
            'name'           => $this->name,
            'slug'           => $this->slug,
            'type'           => $type,
            'title_tag'      => $this->title_tag ?? 'h2',
            'title_position' => $this->title_position ?? 'left',
            'item_template'  => $this->item_template ?? 'card',
            'item_image_size' => $this->item_image_size,
            'row_layout'     => $this->row_layout ?? 'carousel',
            'categories'     => $type === 'categories' ? ProductCategoryResource::collection($this->productCategories) : [],
            'products'       => $type === 'products'   ? $this->serializeProducts() : [],
            'promotions'     => $type === 'banner'     ? $this->serializePromotions() : [],
        ];
    }

    private function serializeProducts(): array
    {
        return $this->categorySectionProducts
            ->pluck('product')
            ->filter()
            ->map(function ($product) {
                $price = count($product->variations) > 0 ? $product->variation_price : $product->selling_price;
                return [
                    'id'                => $product->id,
                    'name'              => $product->name,
                    'slug'              => $product->slug,
                    'currency_price'    => AppLibrary::currencyAmountFormat($price),
                    'cover'             => $product->cover,
                    'cover_position'    => $product->cover_position ?? 'center',
                    'cover_zoom'        => (float)($product->cover_zoom ?? 1.0),
                    'flash_sale'        => $product->add_to_flash_sale == Ask::YES,
                    'is_offer'          => AppLibrary::isBetweenDate($product->offer_start_date, $product->offer_end_date),
                    'discounted_price'  => AppLibrary::currencyAmountFormat($price - (($price / 100) * $product->discount)),
                    'rating_star'       => $product->rating_star,
                    'rating_star_count' => (int) $product->rating_star_count,
                    'wishlist'          => false,
                ];
            })
            ->values()
            ->toArray();
    }

    private function serializePromotions(): array
    {
        return $this->categorySectionPromotions
            ->pluck('promotion')
            ->filter()
            ->map(fn($p) => [
                'id'        => $p->id,
                'name'      => $p->name,
                'slug'      => $p->slug,
                'cover'     => $p->cover,
                'link_type' => $p->link_type,
                'link_url'  => $p->link_url,
            ])
            ->values()
            ->toArray();
    }
}
