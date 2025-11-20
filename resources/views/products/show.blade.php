<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Product Details') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="mb-6">
                        <h3 class="text-2xl font-bold mb-2">{{ $product->name }}</h3>
                        <p class="text-gray-600">{{ $product->description }}</p>
                    </div>

                    <div class="grid grid-cols-2 gap-4 mb-6">
                        <div>
                            <p class="text-sm text-gray-600">Price</p>
                            <p class="text-xl font-bold text-green-600">${{ number_format($product->price, 2) }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">Stock</p>
                            <p class="text-xl font-bold">{{ $product->stock }} units</p>
                        </div>
                    </div>

                    <div class="mb-4">
                        <p class="text-sm text-gray-600">Created: {{ $product->created_at->format('M d, Y') }}</p>
                        <p class="text-sm text-gray-600">Updated: {{ $product->updated_at->diffForHumans() }}</p>
                    </div>

                    <div class="flex items-center gap-4">
                        <a href="{{ route('products.index') }}"
                            class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded">
                            Back to Products
                        </a>

                        @can('edit products')
                            <a href="{{ route('products.edit', $product) }}"
                                class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                Edit
                            </a>
                        @endcan

                        @can('delete products')
                            <form action="{{ route('products.destroy', $product) }}" method="POST" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" onclick="return confirm('Are you sure?')"
                                    class="bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-4 rounded">
                                    Delete
                                </button>
                            </form>
                        @endcan
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
