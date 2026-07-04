<header class="bg-white border-b border-gray-100 px-4 lg:px-6 py-4 flex items-center justify-between sticky top-0 z-20">

    {{-- Groupe gauche : hamburger + titre --}}
    <div class="flex items-center gap-3">
        <button @click="sidebarOpen = !sidebarOpen"
            class="p-2 rounded-lg text-gray-500 hover:bg-gray-100 transition focus:outline-none">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M4 6h16M4 12h16M4 18h16" />
            </svg>
        </button>

        <div>
            <h1 class="text-lg font-semibold text-gray-800 truncate">
                @yield('page-title', 'Tableau de bord')
            </h1>
            @hasSection('page-subtitle')
            <p class="text-xs text-gray-400 hidden md:block">@yield('page-subtitle')</p>
            @endif
        </div>
    </div>

    {{-- Droite : notifications + profil --}}
    <div class="flex items-center gap-3">

        {{-- NOTIFICATIONS --}}
        <div x-data="{
            open: false,
            notifications: {{ Js::from(auth()->user()->notifications->take(10)) }},
            unreadCount: {{ auth()->user()->unreadNotifications->count() }},
            startX: null,
            moveX: null,
            async deleteNotification(id) {
                try {
                    const response = await fetch(`/notifications/${id}`, {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Accept': 'application/json',
                            'Content-Type': 'application/json'
                        }
                    });
                    const data = await response.json();
                    if (data.success) {
                        this.notifications = this.notifications.filter(n => n.id !== id);
                        this.unreadCount = this.notifications.filter(n => !n.read_at).length;
                    }
                } catch (error) {
                    console.error('Erreur suppression :', error);
                }
            },
            async deleteAll() {
                if (!confirm('Supprimer toutes les notifications ?')) return;
                try {
                    const response = await fetch('/notifications', {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Accept': 'application/json',
                            'Content-Type': 'application/json'
                        }
                    });
                    const data = await response.json();
                    if (data.success) {
                        this.notifications = [];
                        this.unreadCount = 0;
                    }
                } catch (error) {
                    console.error('Erreur réseau :', error);
                }
            }
        }" class="relative">

            {{-- Bouton cloche --}}
            <button @click="open = !open"
                class="relative p-2 rounded-lg text-gray-500 hover:bg-gray-100 transition focus:outline-none">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                </svg>
                <template x-if="unreadCount > 0">
                    <span class="absolute -top-1 -right-1 bg-red-500 text-white text-[10px] font-bold rounded-full
                                 w-5 h-5 flex items-center justify-center"
                        x-text="unreadCount > 9 ? '9+' : unreadCount"></span>
                </template>
            </button>

            {{-- Dropdown --}}
            <div x-show="open" @click.away="open = false"
                class="absolute right-0 mt-2 w-[60vw] max-w-[calc(100vw-2rem)] sm:w-96
                        bg-white rounded-2xl shadow-lg border border-gray-100 z-30 max-h-[80vh] overflow-y-auto"
                style="right: 0;">

                {{-- En-tête --}}
                <div class="p-4 border-b border-gray-100 flex items-center justify-between sticky top-0 bg-white rounded-t-2xl">
                    <h3 class="font-semibold text-gray-700">Notifications</h3>
                    <div class="flex items-center gap-2">
                        <template x-if="unreadCount > 0">
                            <form method="POST" action="{{ route('notifications.mark-all-read') }}" class="inline">
                                @csrf
                                <button type="submit" class="text-xs text-blue-600 hover:underline">Tout lire</button>
                            </form>
                        </template>
                        <button @click="deleteAll()" class="text-xs text-red-500 hover:underline">Tout supprimer</button>
                    </div>
                </div>

                {{-- Liste --}}
                <div class="divide-y divide-gray-50">
                    <template x-for="notification in notifications" :key="notification.id">
                        <div class="relative group"
                            @touchstart="startX = $event.touches[0].clientX"
                            @touchmove="moveX = $event.touches[0].clientX"
                            @touchend="
                                if (startX && moveX && (startX - moveX > 50)) {
                                    deleteNotification(notification.id);
                                }
                                startX = null; moveX = null;
                             ">
                            <div class="p-4 hover:bg-gray-50 transition flex items-start gap-3"
                                :class="notification.read_at ? 'opacity-60' : 'bg-blue-50/30'">
                                <div class="w-8 h-8 rounded-full bg-blue-100 text-blue-600
                                            flex items-center justify-center flex-shrink-0">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="text-sm font-medium text-gray-800 truncate" x-text="notification.data.title"></p>
                                    <p class="text-xs text-gray-500 mt-0.5 line-clamp-2" x-text="notification.data.message"></p>
                                    <div class="flex items-center gap-3 mt-2">
                                        <span class="text-xs text-gray-400" x-text="new Date(notification.created_at).toLocaleDateString('fr-FR')"></span>
                                        <template x-if="!notification.read_at">
                                            <a :href="'/notifications/mark-as-read/' + notification.id"
                                                class="text-xs text-blue-600 hover:underline">Marquer lue</a>
                                        </template>
                                    </div>
                                </div>
                                <button @click="deleteNotification(notification.id)"
                                    class="text-gray-400 hover:text-red-500 transition flex-shrink-0">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M6 18L18 6M6 6l12 12" />
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </template>
                    <template x-if="notifications.length === 0">
                        <div class="p-8 text-center text-gray-400 text-sm">
                            <svg class="w-10 h-10 mx-auto mb-2 text-gray-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                    d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                            </svg>
                            <p>Aucune notification</p>
                        </div>
                    </template>
                </div>

                {{-- Pied --}}
                <template x-if="notifications.length >= 10 && {{ auth()->user()->notifications->count() }} > 10">
                    <div class="p-3 border-t border-gray-100 text-center text-xs text-gray-400">
                        Affichage des 10 plus récentes
                    </div>
                </template>
            </div>
        </div>

        {{-- Profil utilisateur --}}
        <div class="flex items-center gap-2 pl-3 border-l border-gray-200">
            <a href="{{ route('profile.edit') }}" class="flex items-center gap-2 hover:opacity-80 transition">
                @if(auth()->user()->profile_photo)
                <div class="w-8 h-8 rounded-full overflow-hidden flex-shrink-0">
                    <img src="{{ Storage::url(auth()->user()->profile_photo) }}"
                        alt="Photo de profil"
                        class="w-full h-full object-cover">
                </div>
                @else
                <div class="w-8 h-8 bg-blue-600 rounded-full flex items-center justify-center">
                    <span class="text-xs font-bold text-white">
                        {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                    </span>
                </div>
                @endif
                <div class="hidden md:block">
                    <p class="text-sm font-medium text-gray-700">{{ auth()->user()->name }}</p>
                    <p class="text-xs text-gray-400">{{ ucfirst(auth()->user()->role_type) }}</p>
                </div>
            </a>
        </div>

    </div>

</header>