@extends('layouts.app')


@section('content')
<div class="my-5 col-12 text-center">
    <h1 class="text-danger h3">Счёт № {{ $online_payment->payment_num }}</h1>
    <p class="text-muted">к заказу № {{ $online_payment->order_id }} от {{ $online_payment->order->created_at->format('d.m.Y') }}</p>
    <p class="h4">Сумма {{ $online_payment->amount }} BYN</p>
</div>
<div class="my-3 col-12">
    <h2 class="h3 mb-3 text-center font-weight-normal">Как оплатить?</h3>
        <div class="row">
            <div class="col-12 col-md-6 mb-4">
                <h3 class="h4">1-й способ</h3>
                <p>По QR-коду в Вашем мобильном банкинге.</p>
            </div>
            <div class="col-12 col-md-6">
                <h3 class="h4">2-й способ</h3>
                <p>Оплата в ЕРИП по коду услуги.</p>
                <p>Код услуги <b>1746902</b></p>
                <p class="mb-4">Далее введите номер договора (соответствует номеру счета) и сумму, если не указана.</p>

                <h3 class="h4">3-й способ</h3>
                <p>Оплата в ЕРИП - выбор среди списка услуг</p>
                <ul class="pl-3">
                    <li>Пункт “Система “Расчет” (ЕРИП)</li>
                    <li>Интернет-магазины/сервисы</li>
                    <li>A-Z Латинские домены</li>
                    <li>«B»</li>
                    <li>Barocco.by (в инфокиосках РУП «Белпочта»может быть указано «Оплата товара ООО БароккоСтайл»).</li>
                </ul>
                <p>Далее введите номер договора (соответствует номеру счета) и сумму, если не указана.</p>
            </div>
        </div>

</div>

@endsection