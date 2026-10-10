@props(['produk'])

<a href="{{ route('products.show', $produk->slug) }}" wire:navigate class="group block bg-white border border-gray-200 rounded-2xl overflow-hidden shadow-sm hover:shadow-lg hover:border-pink-200 transition">
    <div class="aspect-[1/1] bg-gray-100 overflow-hidden">
        <img src="{{ $produk->foto ? asset('storage/'.$produk->foto) : 'https://placehold.co/400x400?text=Produk' }}"
             alt="{{ $produk->nama }}"
             class="w-full h-full object-cover group-hover:scale-105 transition duration-300">
    </div>
    <div class="p-3">
        <h3 class="font-medium text-sm text-gray-800 line-clamp-1">{{ $produk->nama }}</h3>
        <p class="mt-1 text-pink-600 font-bold text-sm">Rp {{ number_format($produk->harga,  0, ',', '.') }}</p>
        <span class="inline-block mt-1 text-xs {{ $produk->stok > 0 ? 'text-green-600' : 'text-red-500' }}">
            {{ $produk->stok > 0 ? 'Stok: '.$produk->stok : 'Stok Habis' }}
        </span>
    </div>
</a>