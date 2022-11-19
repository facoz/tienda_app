@extends('layouts.base')
@section('title', 'Detalle de la orden')

@section('content')

<div>
    <div>
        <h1>Bienvenido Sr(a) {{$order->customer_name}}</h1>
    </div>
    <div>
        <h3>El estado de su orden es {{$order->status}}</h3>
    </div>
    <div>
        <h4>El numero de su orden es {{$order->id}}</h4>
    </div>
    <div>
        @if ($order->payment_status)
            <h6>El estado de su pago Es: {{$order->payment_status}}</h6>
        @endif
    </div>
</div>

@if ($order->payment_status == '' || !$order->url)
    <form method="post" action="{{route('order.process',$order)}}">
        @csrf
        @method('put')
        <button type="submit">Solicitar Url Pago</button>
    </form>
@endif

@if (in_array($order->payment_status, [env('PENDING'), env("REJECTED")]))
    <form action="{{route('order.validate',$order)}}" method="post">
        @csrf
        <select name="tipo_tarjeta">
            <option value="visa">Visa</option>
            <option value="master">Master Card</option>
            <option value="dinners">Dinners</option>
        </select>
        <input type="text" name="numero_tarjeta" placeholder="Ingrese El Numero de Tarjeta">
        <a target="_blank" href="{{$order->url}}">Ir a Pagar</a>
        <button type="submit">Validar Pago</button>
        @error('numero_tarjeta')
            <br>
            <small style="color:red">*{{$message}}</small>
            <br>
        @enderror
    </form>
@endif

@if ($order->payment_status == env('PENDING_VALIDATION'))
    <form action="{{route('order.execute_action',$order)}}" method="post">
        @csrf
        <h5 style="color:tomato">Estamos Procesando su orden puede esperar o proceder a cancelarla</h5>
        <div>
            <select name="action">
                <option value="refund">Refund</option>
                <option value="void">Void</option>
                <option value="reverse">Reverse</option>
            </select>
            <button type="submit">Aceptar</button>
        </div>
    </form>
@endif

@if (in_array($order->payment_status, [env('PENDING_PROCESS')]))
    <form action="{{route('order.re_validate',$order)}}" method="post">
        @csrf
        <button type="submit">Consultar estado de pago</button>
    </form>
@endif

@endsection