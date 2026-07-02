<div>

    {{-- Barre de recherche --}}
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5 mb-6">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-3">

            {{-- Recherche texte --}}
            <div class="md:col-span-2 relative">
                <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                    <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                </div>
                <input type="text"
                       wire:model.live.debounce.300ms="search"
                       placeholder="Titre, auteur, ISBN..."
                       class="w-full pl-10 pr-4 py-2.5 border border-gray-200 rounded-xl text-sm
                              focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>

            {{-- Catégorie --}}
            <div>
                <select wire:model.live="category"
                        class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm
                               focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="">Toutes catégories</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat->id }}">{{ $cat->nom }}</option>
                    @endforeach
                </select>
            </div>

            {{-- Disponibilité --}}
            <div>
                <select wire:model.live="disponible"
                        class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm
                               focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="">Tous les livres</option>
                    <option value="1">Disponibles uniquement</option>
                    <option value="0">Indisponibles</option>
                </select>
            </div>

        </div>

        {{-- Langue --}}
        <div class="flex gap-2 mt-3 flex-wrap">
            <button wire:click="$set('langue', '')"
                    class="text-xs px-3 py-1.5 rounded-lg transition
                           {{ $langue === '' ? 'bg-blue-600 text-white' : 'bg-gray-100 text-gray-600 hover:bg-gray-200' }}">
                Toutes langues
            </button>
            @foreach($langues as $l)
                <button wire:click="$set('langue', '{{ $l }}')"
                        class="text-xs px-3 py-1.5 rounded-lg transition
                               {{ $langue === $l ? 'bg-blue-600 text-white' : 'bg-gray-100 text-gray-600 hover:bg-gray-200' }}">
                    {{ $l }}
                </button>
            @endforeach
        </div>

    </div>

    {{-- Résultats --}}
    <div class="flex items-center justify-between mb-4">
        <p class="text-sm text-gray-500">
            <span class="font-medium text-gray-700">{{ $books->total() }}</span> livre(s) trouvé(s)
        </p>
        @if($search || $category || $langue || $disponible)
            <button wire:click="$set('search', ''); $set('category', ''); $set('langue', ''); $set('disponible', '')"
                    class="text-xs text-blue-600 hover:underline">
                Réinitialiser les filtres
            </button>
        @endif
    </div>

    {{-- Indicateur de chargement --}}
    <div wire:loading class="text-center py-4">
        <div class="inline-flex items-center gap-2 text-sm text-gray-500">
            <svg class="animate-spin w-4 h-4" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor"
                      d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
            </svg>
            Recherche en cours...
        </div>
    </div>

    {{-- Grille de livres --}}
    @if($books->isEmpty())
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm text-center py-16">
            <svg class="w-12 h-12 mx-auto mb-3 text-gray-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                      d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13"/>
            </svg>
            <p class="text-gray-400 text-sm">Aucun livre trouvé</p>
        </div>
    @else
        <div wire:loading.remove class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
            @foreach($books as $book)
                <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden
                            hover:shadow-md transition group">

                    {{-- Couverture --}}
                    <div class="h-48 bg-gray-50 flex items-center justify-center overflow-hidden">
                        @if($book->couverture)
                            <img src="{{ Storage::url($book->couverture) }}"
                                 alt="{{ $book->titre }}"
                                 class="h-full w-full object-cover group-hover:scale-105 transition duration-300">
                        @else
                            <div class="text-center p-4">
                                <svg class="w-10 h-10 mx-auto text-gray-200 mb-2" fill="none"
                                     stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                          d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13"/>
                                </svg>
                                <p class="text-xs text-gray-300">Pas de couverture</p>
                            </div>
                        @endif
                    </div>

                    {{-- Infos --}}
                    <div class="p-4">
                        <h3 class="text-sm font-semibold text-gray-800 leading-tight line-clamp-2 mb-1">
                            {{ $book->titre }}
                        </h3>
                        <p class="text-xs text-gray-500 mb-2">
                            {{ $book->author->prenom ?? '' }} {{ $book->author->nom ?? '—' }}
                        </p>

                        <div class="flex items-center justify-between">
                            <span class="text-xs px-2 py-0.5 bg-blue-50 text-blue-600 rounded-lg">
                                {{ $book->category->nom ?? '—' }}
                            </span>
                            @if($book->isAvailable())
                                <span class="text-xs text-green-600 font-medium">
                                    {{ $book->quantite_disponible }} dispo.
                                </span>
                            @else
                                <span class="text-xs text-red-500 font-medium">Épuisé</span>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        {{-- Pagination --}}
        <div class="mt-6">
            {{ $books->links() }}
        </div>
    @endif

</div>