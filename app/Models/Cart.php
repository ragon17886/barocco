<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;

class Cart extends Model
{
    use HasFactory;
    /**
     * Инициализация корзины
     *
     * @return $this
     */
    public function setCart()
    {
        $cartId = Auth::user() ? Auth::user()->cart_token : Cookie::get('cart_token');
        return Cart::findOrNew($cartId);
    }
    /**
     * Содержимое корзины
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function items()
    {
        return $this->hasMany(CartData::class);
    }
    /**
     * Количество товаров в корзине
     *
     * @return int
     */
    public function itemsCount()
    {
        $counter = 0;
        foreach ($this->items as $item) {
            $counter += $item->count;
        }
        return $counter;
    }
    /**
     * Добавить товар в корзину
     *
     * @param integer $productId
     * @param integer $sizeId
     * @param integer $colorId
     * @return void
     */
    public function addItem(int $productId, int $sizeId, int $colorId)
    {
        $this->createIfNotExists();

        $item = $this->items
            ->where('product_id', $productId)
            ->where('size_id', $sizeId)
            ->where('color_id', $colorId)
            ->first();

        if (isset($item)) {
            $item->increment('count');
        } else {
            $this->items()->create([
                'product_id' => $productId,
                'count' => 1,
                'size_id' => $sizeId,
                'color_id' => $colorId
            ]);
        }
    }
    /**
     * Создать корзину, если она еще не создана
     *
     * @return void
     */
    public function createIfNotExists()
    {
        if (!$this->exists) {
            $this->save();
            if (Auth::check()) {
                $user = Auth::user();
                $user->cart_token = $this->id;
                $user->save();
            } else {
                Cookie::queue(cookie('cart_token', $this->id, 60 * 24 * 30, '/'));
            }
        }
    }
    /**
     * Получить содержимое корзины
     *
     * @return void
     */
    public function getData()
    {
        $this->load('items');
        $this->items->load('products');
        return $this;
    }
}
