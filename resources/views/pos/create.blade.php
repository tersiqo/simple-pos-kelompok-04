@extends('layouts.app')
@section('title', 'Kasir')
@section('content')
    <h1 class="text-lg font-semibold mb-4">Transaksi Kasir</h1>
    <div class="grid grid-cols-3 gap-4">
        @foreach ($products as $product)
            <div class="border rounded-md p-3">
                <p class="font-medium">{{ $product->name }}</p>
                <p class="text-sm text-slate-500">Rp {{ number_format($product->price, 0, ',', '.') }}</p>
            </div>
            @endforeach
        </div>
@endsection