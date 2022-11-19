@extends('layouts.base')
@section('title', 'Todas las Ordenes')

@section('content')
<table class="table table-sm">
    <thead>
        <tr>
            <th>Order Number</th>
            <th>Nombre Cliente</th>
            <th>Correo</th>
            <th>Telefono</th>
            <th>Status</th>
            <th>Estado de Pago</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($orders as $order)
        <tr>
            <td><a href="{{route('order.view',$order)}}">{{$order->id}}</a></td>
            <td>{{$order->customer_name}}</td>
            <td>{{$order->customer_email}}</td>
            <td>{{$order->customer_phone}}</td>
            <td>{{$order->status}} </td>
            <td>{{$order->payment_status}} </td>
            <td>
            @if (!$order->payment_status)
                <a href="{{route('order.view',$order)}}">Pagar</a>
            @endif
            @if (in_array($order->payment_status, [env('PENDING'), env('REJECTED'), env('PENDING_VALIDATION'), env("PENDING_PROCESS")]))
                @include('layouts.validate')
            @endif
            </td>
        </tr>
        @endforeach
    </tbody>
</table>
@endsection