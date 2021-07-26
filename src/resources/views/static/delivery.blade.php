@extends('layouts.app')

@section('title', 'Доставка')

@section('breadcrumbs', Breadcrumbs::render('static-delivery'))

@section('content')
<div class="col-3 d-none d-lg-block">
    @include('includes.static-pages-menu')
</div>
<div class="col-12 col-lg-9 static-page">
    <p><span class="title">1. Доставка с примеркой</span></p>
    <p>
        Курьер доставит Вам заказанный товар на дом и подождет 15 мин. пока Вы убедитесь, что изделие Вам подходит. Вы можете выбрать модель, которая подошла Вам больше и рассчитаться только за нее.
    </p>
    <p>
        <strong>Стоимость  доставки</strong> - БЕСПЛАТНО *<br>
        * при выкупе после примерки любой единицы.<br>
        Без выкупа изделий - оплачивается согласно тарифа курьерской службы от 12 руб. до 15 руб. в зависимости от веса посылки (менеджер Вам сообщит при оформлении заказа)
    </p>
    <p>
        <strong>Сроки доставки</strong>
        Минск - <b>1-2 дня</b><br>
        Гомель, Гродно, Витебск и Могилев  <b>2-3 дня</b><br>
        Остальная территория РБ -  <b>до 4 дней.</b>
    </p>
    <p class="mt-5"><span class="title">2. Курьерская доставка</span></p>
    <p>
        <strong>Стоимость  доставки</strong> - БЕСПЛАТНО
    </p>
    <p>
        <strong>Сроки доставки</strong>
        Минск - <b>1-2 дня</b><br>
        Гомель, Гродно, Витебск и Могилев  <b>2-3 дня</b><br>
        Остальная территория РБ -  <b>до 4 дней.</b>
    </p>
    <p class="mt-5"><span class="title">3. EMS: ускоренная почта</span></p>
    <p>
        <strong>Стоимость  доставки</strong> - от 10 до 20 руб. в зависимости от веса посылки (рассчитывается менеджером после заказа)
    </p>
    <p>
        <strong>Сроки доставки</strong> - 2 дня
    </p>
    <p class="mt-5"><span class="title">4. Самовывоз в Бресте</span></p>
    <p>
        Смотрите адреса магазинов <a href="#" class="text-decoration-underline">здесь</a>
    </p>
</div>
@endsection