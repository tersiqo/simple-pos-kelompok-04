<nav class="bg-slate-900 text-white px-4 py-3 flex items-center gap-6">
    <span class="font-semibold">Simple POS</span>

    <a
        href="{{ route('pos.create') }}"
        class="px-3 py-1 rounded-md {{ request()->routeIs('pos.create') ? 'bg-slate-700 font-semibold' : 'hover:bg-slate-800' }}"
    >
        Kasir
    </a>

    <a
        href="{{ route('transactions.index') }}"
        class="px-3 py-1 rounded-md {{ request()->routeIs('transactions.*') ? 'bg-slate-700 font-semibold' : 'hover:bg-slate-800' }}"
    >
        Transaksi
    </a>
</nav>