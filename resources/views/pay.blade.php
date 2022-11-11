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
        <h5>El numero de su orden es {{$order->id}}</h5>
    </div>
    <div>
        @if ($order->payment_status)
            <h4>El estado de su pago Es: {{$order->payment_status}}</h4>
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

@if (in_array($order->payment_status, [env('PENDIENTE'), env("RECHAZADO")]))
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
    </form>
@endif

@if ($order->payment_status == env('ESPERANDO'))
    <form action="{{route('order.execute_action',$order)}}" method="post">
        @csrf
        @method('put')
        <h5 style="color:tomato">Estamos Procesando su orden puede esperar o proceder a cancelarla</h5>
        <div>
            <select name="opcion">
                <option value="cancelar">Cancelar</option>
                <option value="void">Void</option>
            </select>
        </div>
        <br>
        <button type="submit">Aceptar</button>
    </form>
@endif

@if ($order->payment_status == env('PROCESANDO'))
    <form action="{{route('order.execute_action',$order)}}" method="post">
        @csrf
        @method('put')
     
        {{-- aqui validamos que se actualice con 3 minutos --}}
        <button type="submit">Aceptar</button>
    </form>
@endif

@endsection