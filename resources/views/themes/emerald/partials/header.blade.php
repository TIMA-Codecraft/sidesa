<!-- Top Info Bar (Emerald Civic Header) -->
<div class="bg-emerald-900 text-emerald-100 text-xs py-2 px-4 sm:px-6 lg:px-8">
    <div class="max-w-7xl mx-auto flex flex-wrap items-center justify-between gap-2">
        <div class="flex items-center space-x-4">
            <span class="inline-flex items-center gap-1.5 font-medium">
                <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
                Jam Pelayanan: {{ \App\Models\Setting::get('office_hours', 'Senin - Jumat 08.00 - 15.00 ' . timezone_label()) }}
            </span>
            <span class="hidden md:inline text-emerald-400/40">|</span>
            <span class="hidden md:inline text-emerald-200">
                📞 Hotline: {{ \App\Models\Setting::get('village_phone', '0812-3456-7890') }}
            </span>
        </div>
        <div class="flex items-center space-x-3 text-[11px]">
            <a href="{{ route('public.ppid.index') }}" class="hover:text-white transition font-medium">PPID Desa</a>
            <span class="text-emerald-400/40">&bull;</span>
            <a href="{{ route('budgets.index') }}" class="hover:text-white transition font-medium">Transparansi APBDes</a>
            <span class="text-emerald-400/40">&bull;</span>
            <a href="{{ route('map.index') }}" class="hover:text-white transition font-medium">Peta GIS</a>
        </div>
    </div>
</div>

<!-- Floating Island Navigation Bar -->
<header class="sticky top-2 z-50 px-4 sm:px-6 lg:px-8 mt-2" x-data="{ openMobile: false }">
    <div class="max-w-7xl mx-auto bg-white/95 backdrop-blur-md rounded-2xl border border-emerald-100/80 shadow-lg shadow-emerald-950/5 px-4 sm:px-6 py-3 transition-all duration-300">
        <div class="flex items-center justify-between">
            <!-- Brand & Village Logo -->
            <a href="{{ route('home') }}" class="flex items-center gap-3 group">
                @if($logo = village_logo())
                    <img src="{{ $logo }}" alt="Logo {{ \App\Models\Setting::get('village_name', 'Desa') }}" class="w-11 h-11 object-contain rounded-2xl p-1 bg-white border border-emerald-100 shadow-md shadow-emerald-600/10 group-hover:scale-105 transition-transform duration-200">
                @else
                    <div class="w-11 h-11 rounded-2xl bg-gradient-to-tr from-emerald-600 via-emerald-500 to-teal-400 text-white flex items-center justify-center text-xl shadow-md shadow-emerald-600/20 group-hover:scale-105 transition-transform duration-200">
                        🌿
                    </div>
                @endif
                <div>
                    <span class="block font-black text-lg text-slate-800 tracking-tight leading-tight group-hover:text-emerald-700 transition">
                        {{ \App\Models\Setting::get('village_name', 'Desa Sukamaju') }}
                    </span>
                    <span class="block text-[11px] font-semibold text-emerald-600">
                        {{ \App\Models\Setting::get('subdistrict_name', 'Kecamatan') }} &bull; Mandiri & Hijau
                    </span>
                </div>
            </a>

            <!-- Desktop Nav Menu (Dynamic from Database) -->
            <nav class="hidden lg:flex items-center space-x-1 xl:space-x-2">
                @php
                    $headerMenus = public_header_menus();
                @endphp

                @forelse($headerMenus as $navMenu)
                    @if($navMenu->children->isNotEmpty())
                        <div class="relative" x-data="{ open: false }" @click.outside="open = false" @close.stop="open = false">
                            <button @click="open = !open" type="button" class="inline-flex items-center gap-1.5 px-3 py-2 rounded-xl text-xs font-semibold transition {{ $navMenu->isCurrent() ? 'bg-emerald-50 text-emerald-700' : 'text-slate-600 hover:text-emerald-700 hover:bg-emerald-50/60' }}">
                                <span>{{ $navMenu->name }}</span>
                                <svg class="w-3 h-3 text-slate-400 transition-transform duration-200" :class="{ 'rotate-180': open }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                </svg>
                            </button>
                            <div 
                                x-show="open" 
                                x-transition:enter="transition ease-out duration-150"
                                x-transition:enter-start="opacity-0 translate-y-1"
                                x-transition:enter-end="opacity-100 translate-y-0"
                                x-transition:leave="transition ease-in duration-100"
                                x-transition:leave-start="opacity-100 translate-y-0"
                                x-transition:leave-end="opacity-0 translate-y-1"
                                class="absolute left-0 mt-2 w-56 rounded-2xl bg-white/95 backdrop-blur-md shadow-xl border border-emerald-100 p-2 z-50"
                                style="display: none;"
                            >
                                @foreach($navMenu->children as $child)
                                    <a href="{{ $child->resolved_url }}" target="{{ $child->target }}" class="block px-3 py-2 rounded-xl text-xs font-medium text-slate-700 hover:bg-emerald-50 hover:text-emerald-700 transition">
                                        {{ $child->name }}
                                    </a>
                                @endforeach
                            </div>
                        </div>
                    @else
                        <a href="{{ $navMenu->resolved_url }}" target="{{ $navMenu->target }}" class="px-3 py-2 rounded-xl text-xs font-semibold transition {{ $navMenu->isCurrent() ? 'bg-emerald-50 text-emerald-700' : 'text-slate-600 hover:text-emerald-700 hover:bg-emerald-50/60' }}">
                            {{ $navMenu->name }}
                        </a>
                    @endif
                @empty
                    <a href="{{ route('home') }}" class="px-3 py-2 rounded-xl text-xs font-semibold text-emerald-700 bg-emerald-50">Beranda</a>
                    <a href="{{ route('articles.index') }}" class="px-3 py-2 rounded-xl text-xs font-semibold text-slate-600 hover:text-emerald-700 hover:bg-emerald-50/60">Kabar Desa</a>
                    <a href="{{ route('budgets.index') }}" class="px-3 py-2 rounded-xl text-xs font-semibold text-slate-600 hover:text-emerald-700 hover:bg-emerald-50/60">APBDes</a>
                    <a href="{{ route('map.index') }}" class="px-3 py-2 rounded-xl text-xs font-semibold text-slate-600 hover:text-emerald-700 hover:bg-emerald-50/60">Peta GIS</a>
                    <a href="{{ route('public.ppid.index') }}" class="px-3 py-2 rounded-xl text-xs font-semibold text-slate-600 hover:text-emerald-700 hover:bg-emerald-50/60">PPID Desa</a>
                @endforelse
            </nav>

            <!-- Actions CTA -->
            <div class="flex items-center gap-2">
                @auth
                    @if(auth()->user()->hasRole(['superadmin', 'kades', 'perangkat']))
                        <a href="{{ route('admin.dashboard') }}" class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl text-xs font-bold bg-gradient-to-r from-emerald-600 to-teal-600 text-white shadow-md shadow-emerald-600/20 hover:from-emerald-700 hover:to-teal-700 transition">
                            Panel Admin &rarr;
                        </a>
                    @elseif(auth()->user()->hasRole('rt'))
                        <a href="{{ route('admin.letter-requests.index') }}" class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl text-xs font-bold bg-amber-500 text-slate-950 shadow-sm hover:bg-amber-400 transition">
                            Panel RT &rarr;
                        </a>
                    @else
                        <a href="{{ route('citizen.letters.index') }}" class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl text-xs font-bold bg-gradient-to-r from-emerald-600 to-teal-600 text-white shadow-md shadow-emerald-600/20 hover:from-emerald-700 hover:to-teal-700 transition">
                            ✉️ Layanan Warga
                        </a>
                    @endif

                    <form action="{{ route('logout') }}" method="POST" class="inline">
                        @csrf
                        <button type="submit" class="p-2 text-slate-400 hover:text-rose-600 transition text-xs font-bold" title="Keluar">
                            Logout
                        </button>
                    </form>
                @else
                    <a href="{{ route('login') }}" class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl text-xs font-bold bg-gradient-to-r from-emerald-600 to-teal-600 text-white shadow-md shadow-emerald-600/20 hover:from-emerald-700 hover:to-teal-700 transition">
                        Masuk Warga
                    </a>
                @endauth

                <!-- Mobile Hamburger Toggle -->
                <button @click="openMobile = !openMobile" class="lg:hidden p-2 rounded-xl text-slate-600 hover:bg-emerald-50 hover:text-emerald-700 transition">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16m-7 6h7" />
                    </svg>
                </button>
            </div>
        </div>

        <!-- Mobile Drawer -->
        <div x-show="openMobile" x-collapse class="lg:hidden mt-3 pt-3 border-t border-emerald-100 space-y-1">
            @forelse($headerMenus ?? [] as $m)
                <a href="{{ $m->resolved_url }}" class="block px-3 py-2 rounded-xl text-xs font-semibold text-slate-700 hover:bg-emerald-50 hover:text-emerald-700">
                    {{ $m->name }}
                </a>
                @foreach($m->children as $sub)
                    <a href="{{ $sub->resolved_url }}" class="block pl-6 pr-3 py-1.5 text-xs text-slate-500 hover:text-emerald-700">
                        &bull; {{ $sub->name }}
                    </a>
                @endforeach
            @empty
                <a href="{{ route('home') }}" class="block px-3 py-2 rounded-xl text-xs font-semibold text-emerald-700">Beranda</a>
                <a href="{{ route('articles.index') }}" class="block px-3 py-2 rounded-xl text-xs font-semibold text-slate-700">Kabar Desa</a>
                <a href="{{ route('budgets.index') }}" class="block px-3 py-2 rounded-xl text-xs font-semibold text-slate-700">APBDes</a>
            @endforelse
        </div>
    </div>
</header>
