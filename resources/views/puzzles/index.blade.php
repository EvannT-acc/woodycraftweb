<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-2xl text-white">Puzzles</h2>
    </x-slot>

    <div class="min-h-screen bg-gray-950 py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <form method="GET" class="mb-6 grid grid-cols-1 md:grid-cols-4 gap-3">
                <input type="text" name="q" value="{{ request('q') }}"
                       placeholder="Recherche"
                       class="col-span-2 bg-gray-900 text-gray-100 border-gray-700 rounded-xl">
                <select name="categorie_id" class="bg-gray-900 text-gray-100 border-gray-700 rounded-xl">
                    <option value="">Toutes catégories</option>
                    @foreach($categories as $c)
                        <option value="{{ $c->id }}" @selected(request('categorie_id')==$c->id)>
                            {{ $c->name }}
                        </option>
                    @endforeach
                </select>
                <button class="rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white px-4">Filtrer</button>
            </form>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($puzzles as $p)
                    <a href="{{ route('puzzles.show', $p) }}"
                       class="group rounded-2xl overflow-hidden border border-gray-800 bg-gray-900 hover:bg-gray-850 transition">
                        <div class="aspect-[16/10] overflow-hidden">
                            <img src="{{ $p->imageUrl() }}" alt="Image {{ $p->title }}"
                                 class="w-full h-full object-cover group-hover:scale-105 transition">
                        </div>
                        <div class="p-4">
                            <h3 class="text-white font-semibold">{{ $p->title }}</h3>
                            <p class="text-gray-300 text-sm line-clamp-2">{{ $p->description }}</p>
                            <div class="mt-3 flex items-center justify-between text-sm">
                                <span class="text-gray-400">Diff: {{ $p->difficulty }}/5</span>
                                <span class="text-indigo-300 font-semibold">{{ number_format($p->price, 2, ',', ' ') }} €</span>
                            </div>
                            <p class="mt-1 text-xs text-gray-500">{{ $p->categorie?->name }}</p>
                        </div>
                    </a>
                @endforeach
            </div>

            <div class="mt-6">
                {{ $puzzles->links() }}
            </div>
        </div>
    </div>
</x-app-layout>
