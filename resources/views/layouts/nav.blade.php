<nav class="bg-indigo-600 text-white shadow-md">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between h-16">
            <!-- Logo / Brand -->
            <div class="flex items-center space-x-3">
                <a href="{{ route('dashboard') }}" class="text-xl font-bold tracking-wider hover:text-indigo-200 transition">
                    Simple POS
                </a>
            </div>

            <!-- Menu Navigasi -->
            <div class="hidden md:flex items-center space-x-4">
                <a href="{{ route('dashboard') }}" class="px-3 py-2 rounded-md text-sm font-medium hover:bg-indigo-500 transition">
                    Dashboard
                </a>
                <a href="{{ route('products.index') }}" class="px-3 py-2 rounded-md text-sm font-medium hover:bg-indigo-500 transition">
                    Produk
                </a>
                <a href="{{ route('transactions.index') }}" class="px-3 py-2 rounded-md text-sm font-medium hover:bg-indigo-500 transition">
                    Transaksi
                </a>
            </div>

            <!-- Info Kelompok -->
            <div class="flex items-center space-x-3">
                <span class="text-sm font-light">Kelompok 04</span>
            </div>
        </div>
    </div>
</nav>