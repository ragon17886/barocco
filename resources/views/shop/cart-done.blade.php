@extends('layouts.app')

@section('title', 'Корзина')

@section('breadcrumbs', Breadcrumbs::render('cart'))

@section('content')

<div class="col-12 col-md-4 col-xl-5 my-5 text-center text-muted">
    <p>
        Ваш заказ принят!
    </p>    
    <h3 class="text-danger">Заказ {{ $orderInfo['orderNum'] }}</h3>
    <p>
        от {{ date('j') }} октября 2020 на сумму {{ $orderInfo['totalPrice'] }} BYN
    </p>
    <div class="row px-5 py-4 text-left" style="background: #FBFBFD">

        <div class="col-6 py-2 font-weight-bold">
            Статус заказа
        </div>
        <div class="col-6 py-2">
            Ожидает подтверждения менеджером
        </div>

        <div class="col-6 py-2 font-weight-bold">
            Способ получения 
        </div>
        <div class="col-6 py-2">
            Курьерская доставка <br>
            {{ $orderInfo['address'] }}
        </div>

        <div class="col-6 py-2 font-weight-bold">
            Способ оплаты
        </div>
        <div class="col-6 py-2">
            Рассрочка, при получении
        </div>

    </div>

    <p class="font-weight-light font-size-12 text-center mt-2">
        По указанному номеру с Вами свяжется менеджер для <br>
        подтверждения условий доставки
    </p>    
</div>

<div class="col-12 my-5 text-center">
    <h3 class="font-weight-light">
        Специально для вас / Недавно просмотренные
    </h3>
    <div class="row justify-content-center align-items-end">
        @foreach ($recomended as $product)
            <div class="col-12 col-md-auto js-product-item product-item mb-3 px-3 text-left">
                <a href="{{ $product->category->getUrl() . '/' . $product->slug }}">
                    <p>
                        <img src="/images/products/{{ $product->images->first()['img'] }}" alt="{{ $product->title }}"
                                    class="img-fluid" style="max-width: 180px">
                    </p>
                </a>
                <b>{{ $product->getFullName() }}</b> <br>
                <span class="text-mutted">{{ $product->category->title }}</span> <br>
                @if ($product->product_price < $product->product_old_price)
                    <s>{{ round($product->product_old_price, 2) }} руб.</s>
                    <font color="#D22020">{{ round($product->product_price, 2) }} руб.</font><br>
                @else
                    {{ round($product->product_price, 2) }} руб.<br>
                @endif
                
            </div> 
        @endforeach
    </div>
    <div class="col-12 my-5 text-center">
        <a href="{{ route('shop') }}" class="btn btn-dark px-4">
            Продолжить покупки
        </a>
    </div>
</div>

@endsection