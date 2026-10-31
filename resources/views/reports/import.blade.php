@extends('layouts.app')
@section('title', 'Import Produk')
@section('content')
<h1 class="text-lg font-semibold mb-4">Import Produk dari CSV</h1>
@if (session('success'))
    <div class="bg-green-50 text-green-700 p-3 rounded-md mb-4">
        {{ session('success') }}
    </div>
@endif
<form method="POST" action="{{ route('import.store') }}" enctype="multipart/form-data" class="max-w-md">
    @csrf
    <label class="block mb-3">
        <span class="text-sm font-medium">File CSV</span>
        <input type="file" name="csv" accept=".csv" class="mt-1 block w-full text-sm">
        @error('csv')
            <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
        @enderror
    </label>
    <p class="text-xs text-slate-500 mb-3">Column yang dibutuhkan: <code>name</code>, <code>category_id</code>, <code>price</code>.</p>
    <button type="submit" class="bg-slate-900 text-white px-4 py-2 rounded-md text-sm">Import</button>
</form>

@if ($importErrors->isNotEmpty())
    <div class="mt-6 bg-red-50 border border-red-200 rounded-md p-4">
        <h2 class="text-sm font-semibold text-red-800 mb-2">
            Baris yang Gagal Di-import ({{ $importErrors->count() }} baris)
        </h2>
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left border-collapse">
                <thead>
                    <tr class="border-b border-red-200 text-red-900">
                        <th class="py-2 pr-4 font-medium w-28">Nomor Row</th>
                        <th class="py-2 font-medium">Pesan Error</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-red-100 text-red-700">
                    @foreach ($importErrors as $err)
                        <tr>
                            <td class="py-2 pr-4 font-mono">Baris {{ $err->row_number }}</td>
                            <td class="py-2">{{ $err->error_message }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endif

<div class="mt-6 border-t pt-4">
    <h1 class="text-lg font-semibold mb-3">
        Import Produk Menggunakan Laravel Excel
    </h1>

    <form action="{{ route('reports.import.excel') }}"
          method="POST"
          enctype="multipart/form-data">

        @csrf
        <label class="block mb-3">
            <span class="text-sm font-medium">File CSV</span>
            <input type="file" name="csv" accept=".csv" required class="mt-1 block w-full text-sm">
            @error('csv')
                <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
            @enderror
        </label>

        <button type="submit" class="bg-green-600 text-white px-4 py-2 rounded-md text-sm">
            Import dengan Laravel Excel
        </button>
    </form>
</div>
@endsection
