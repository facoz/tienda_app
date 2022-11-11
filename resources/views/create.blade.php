@extends('layouts.base')
@section('title', 'Crear Orden')

@section('content')
<form method="POST" action="{{route('order.save')}}">
    @csrf
    <label>Customer Name
        <input type="text" name="customer_name" value="{{old('name')}}">
    </label>
    @error('customer_name')
        <br>
        <small>
            {{$message}}
        </small>
        <br>
    @enderror
    <br>
    <br>
    <label>Customer Email
        <input type="text" name="customer_email"  value="{{old('email')}}">
    </label>
    @error('customer_email')
        <br>
        <small>
            {{$message}}
        </small>
        <br>
    @enderror
    <br>
    <br>
    <label>Customer Phone
        <input type="text" name="customer_phone"  value="{{old('phone')}}">
    </label>
    @error('customer_phone')
        <br>
        <small>
            {{$message}}
        </small>
        <br>
    @enderror
    <br>
    <br>
    <button type="submit">Crear pedido</button>
</form>
@endsection