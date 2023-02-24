<?php

namespace App\Models\ProductAttributes;

use App\Facades\Currency;
use App\Traits\AttributeFilterTrait;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

/**
 * @property int $id
 * @property string $slug
 * @property float $price
 */
class Price extends Model
{
    use AttributeFilterTrait;

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = [];

    /**
     * Return random id attribute
     */
    public function getIdAttribute(): string
    {
        return mt_rand();
    }

    /**
     * Return price value
     */
    public function getPriceAttribute(): int
    {
        return (int)Str::of($this->slug)->explode('-')->last();
    }

    /**
     * @return Builder
     */
    public static function applyFilter(Builder $builder, array $values)
    {
        /** @var \App\Models\Url $url */
        foreach ($values as $url) {
            /** @var self $self */
            $self = $url->filters;
            $operator = str_starts_with($self->slug, 'price-from-') ? '>' : '<';
            $builder->where('price', $operator, $self->price);
        }

        return $builder;
    }

    /**
     * Generate filter badge name
     */
    public function getBadgeName(): string
    {
        $prefix = str_starts_with($this->slug, 'price-from-') ? 'От ' : 'До ';

        return $prefix . Currency::convertAndFormat($this->price);
    }
}
