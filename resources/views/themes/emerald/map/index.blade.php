@extends(theme_layout())

@section('title', 'Peta Wilayah & Web GIS Fasilitas Desa - ' . \App\Models\Setting::get('village_name', 'Desa Sukamaju'))

@section('content')
<!-- Leaflet CSS -->
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />

<style>
    .facility-marker-icon {
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: transform 0.2s ease, filter 0.2s ease;
    }
    .facility-marker-icon:hover {
        transform: scale(1.18);
        filter: drop-shadow(0 4px 6px rgba(0,0,0,0.3));
        z-index: 1000 !important;
    }
    .leaflet-popup-content-wrapper {
        border-radius: 1rem;
        padding: 0.25rem;
        box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.15), 0 8px 10px -6px rgba(0, 0, 0, 0.1);
    }
</style>

@component('themes.emerald.partials.page-hero', [
    'breadcrumbs' => [
        ['label' => 'Beranda', 'url' => route('home'), 'icon' => '🏡'],
        ['label' => 'Peta Digital & Wilayah GIS'],
    ],
    'eyebrow' => 'Sistem Informasi Geografis (Web GIS) & Inventarisasi Aset (Permendagri 1/2016)',
    'eyebrowIcon' => '🗺️',
    'title' => 'Peta Digital & Fasilitas Desa',
    'description' => 'Eksplorasi batas administratif wilayah, pembagian zonasi dusun/RW, persebaran titik fasilitas publik, serta kepemilikan yuridis aset desa di ' . \App\Models\Setting::get('village_name', 'Desa Sukamaju') . '.',
])
@endcomponent

<!-- Filter & Map Container -->
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 space-y-6">
    <!-- Category & Asset Filter Bar -->
    <div class="space-y-3 bg-white p-4 rounded-3xl border border-slate-200 shadow-sm">
        <!-- Filter Kategori Fasilitas -->
        <div class="flex items-center space-x-2 overflow-x-auto pb-1 scrollbar-none" id="categoryFilters">
            <button 
                type="button" 
                onclick="filterCategory('all')" 
                id="btn-cat-all"
                class="filter-cat-btn active px-3.5 py-1.5 rounded-full text-xs font-bold transition whitespace-nowrap bg-emerald-600 text-white shadow-sm"
            >
                🌐 Semua Kategori ({{ $facilities->count() }})
            </button>
            @foreach($categories as $key => $cat)
                @php
                    $count = $facilities->where('category', $key)->count();
                @endphp
                @if($count > 0)
                    <button 
                        type="button" 
                        onclick="filterCategory('{{ $key }}')" 
                        id="btn-cat-{{ $key }}"
                        class="filter-cat-btn px-3.5 py-1.5 rounded-full text-xs font-bold transition whitespace-nowrap bg-slate-50 text-slate-700 hover:bg-slate-100 border border-slate-200"
                    >
                        <span>{{ $cat['icon'] }}</span>
                        <span class="ml-1">{{ $cat['label'] }} ({{ $count }})</span>
                    </button>
                @endif
            @endforeach
        </div>

        <!-- Filter Yuridis Aset Desa (Permendagri No. 1/2016) -->
        <div class="flex items-center space-x-2 overflow-x-auto pt-2 border-t border-slate-100 scrollbar-none" id="assetFilters">
            <span class="text-[11px] font-bold text-emerald-800 uppercase shrink-0 flex items-center gap-1">
                <span>🏛️</span>
                <span>Aset Desa:</span>
            </span>
            <button 
                type="button" 
                onclick="filterAsset('all')" 
                id="btn-asset-all"
                class="filter-asset-btn active px-3 py-1 rounded-full text-xs font-semibold transition whitespace-nowrap bg-emerald-700 text-white shadow-sm"
            >
                Semua Titik
            </button>
            <button 
                type="button" 
                onclick="filterAsset('only_assets')" 
                id="btn-asset-only_assets"
                class="filter-asset-btn px-3 py-1 rounded-full text-xs font-semibold transition whitespace-nowrap bg-slate-100 text-slate-700 hover:bg-slate-200 border border-slate-200"
            >
                🏛️ Hanya Aset Desa ({{ $facilities->where('is_village_asset', true)->count() }})
            </button>
            @foreach($kibMetas as $kCode => $kib)
                @php
                    $kCount = $facilities->where('kib_type', $kCode)->count();
                @endphp
                @if($kCount > 0)
                    <button 
                        type="button" 
                        onclick="filterAsset('{{ $kCode }}')" 
                        id="btn-asset-{{ $kCode }}"
                        class="filter-asset-btn px-3 py-1 rounded-full text-xs font-semibold transition whitespace-nowrap bg-slate-100 text-slate-700 hover:bg-slate-200 border border-slate-200"
                    >
                        <span>{{ $kib['icon'] }}</span>
                        <span class="ml-1">{{ $kib['code'] }}: {{ $kib['name'] }} ({{ $kCount }})</span>
                    </button>
                @endif
            @endforeach
        </div>
    </div>

    <!-- Map & Sidebar Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Leaflet Map Container -->
        <div class="lg:col-span-2 bg-white rounded-3xl border border-slate-200 overflow-hidden shadow-sm relative">
            <div id="villageMap" class="w-full h-[580px] z-10"></div>
            <!-- Legend Overlay -->
            <div class="absolute bottom-4 left-4 z-20 bg-white/95 backdrop-blur-md p-3.5 rounded-2xl border border-slate-200 shadow-lg text-xs space-y-2 hidden sm:block max-w-[220px]">
                <span class="font-bold text-slate-800 block text-[11px] uppercase tracking-wider">Legenda Batas</span>
                @foreach($boundaries as $boundary)
                    <div class="flex items-center space-x-2">
                        <span class="w-3.5 h-3.5 rounded shrink-0" style="background-color: {{ $boundary->color }}40; border: 2px solid {{ $boundary->color }};"></span>
                        <span class="text-slate-700 text-[11px] font-medium truncate">{{ $boundary->name }}</span>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Directory Fasilitas List -->
        <div class="bg-white rounded-3xl border border-slate-200 p-5 shadow-sm space-y-4 flex flex-col justify-between max-h-[580px]">
            <div>
                <h3 class="font-bold text-slate-800 text-sm flex items-center justify-between pb-3 border-b border-slate-100">
                    <span class="flex items-center space-x-1.5">
                        <span>📍</span>
                        <span>Daftar Fasilitas & Aset</span>
                    </span>
                    <span class="text-xs text-slate-400 font-normal" id="visibleCount">{{ $facilities->count() }} titik</span>
                </h3>
                <div class="overflow-y-auto space-y-2.5 mt-3 pr-1 max-h-[470px]" id="facilityList">
                    @forelse($facilities as $facility)
                        <div 
                            onclick="focusFacility({{ $facility->latitude }}, {{ $facility->longitude }}, '{{ addslashes($facility->name) }}')"
                            data-category="{{ $facility->category }}"
                            data-is-asset="{{ $facility->is_village_asset ? '1' : '0' }}"
                            data-kib="{{ $facility->kib_type ?? '' }}"
                            class="facility-item p-3 rounded-2xl border border-slate-100 hover:border-emerald-300 hover:bg-emerald-50/50 transition cursor-pointer group flex items-start space-x-3"
                        >
                            <div class="w-9 h-9 rounded-xl bg-slate-100 flex items-center justify-center text-lg shrink-0 group-hover:scale-105 transition">
                                {{ $facility->category_meta['icon'] ?? '📍' }}
                            </div>
                            <div class="min-w-0 flex-1">
                                <div class="flex items-center space-x-1.5 flex-wrap">
                                    <h4 class="text-xs font-bold text-slate-800 group-hover:text-emerald-700 transition truncate">
                                        {{ $facility->name }}
                                    </h4>
                                    @if($facility->is_village_asset)
                                        <span class="px-1.5 py-0.2 rounded text-[9px] font-bold bg-emerald-100 text-emerald-800">
                                            Aset Desa
                                        </span>
                                    @endif
                                </div>
                                <p class="text-[11px] text-slate-500 truncate mt-0.5">
                                    {{ $facility->address ?? ($facility->category_meta['label'] ?? ucfirst($facility->category)) }}
                                </p>
                                
                                @if($facility->is_village_asset && ($facility->kib_meta || $facility->ownership_meta))
                                    <div class="mt-1 flex items-center gap-1.5 flex-wrap text-[10px]">
                                        @if($facility->kib_meta)
                                            <span class="px-1.5 py-0.5 rounded font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                                {{ $facility->kib_meta['code'] }}
                                            </span>
                                        @endif
                                        @if($facility->ownership_meta)
                                            <span class="text-slate-500 truncate">
                                                {{ $facility->ownership_meta['short_label'] }}
                                            </span>
                                        @endif
                                    </div>
                                @endif

                                <div class="mt-1.5 flex items-center justify-between text-[10px]">
                                    <span class="inline-block font-semibold px-2 py-0.5 rounded-full {{ $facility->condition === 'baik' ? 'bg-emerald-50 text-emerald-700' : 'bg-amber-50 text-amber-700' }}">
                                        {{ $facility->condition_label ?? 'Kondisi Baik' }}
                                    </span>
                                    @if($facility->register_code)
                                        <span class="font-mono text-slate-400 text-[9px] truncate max-w-[120px]">
                                            {{ $facility->register_code }}
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @empty
                        @include('themes.emerald.partials.empty-state', [
                            'icon' => '📍',
                            'title' => 'Belum ada titik fasilitas yang tersimpan.',
                            'compact' => true,
                        ])
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Leaflet JS -->
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
<script>
    let map;
    let markers = [];
    let currentCategoryFilter = 'all';
    let currentAssetFilter = 'all';

    const facilitiesData = @json($facilities);
    const boundariesData = @json($boundaries);
    const categoriesMap = @json($categories);

    document.addEventListener('DOMContentLoaded', function () {
        // Inisialisasi Peta
        const defaultCenter = [-6.914744, 107.609810];
        map = L.map('villageMap').setView(defaultCenter, 14);

        // Tambahkan Tile OpenStreetMap
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors',
            maxZoom: 19
        }).addTo(map);

        // Gambar Poligon Batas Wilayah
        boundariesData.forEach(boundary => {
            if (boundary.coordinates && Array.isArray(boundary.coordinates) && boundary.coordinates.length > 0) {
                const polygon = L.polygon(boundary.coordinates, {
                    color: boundary.color || '#059669',
                    weight: 2,
                    opacity: 0.85,
                    fillColor: boundary.color || '#059669',
                    fillOpacity: 0.15
                }).addTo(map);

                polygon.bindPopup(`
                    <div class="p-2">
                        <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400 block">${(boundary.type || 'WILAYAH').toUpperCase()}</span>
                        <h4 class="font-bold text-slate-800 text-sm">${boundary.name}</h4>
                        ${boundary.area_hectares ? `<p class="text-xs text-slate-600 mt-1">Luas: <b>${boundary.area_hectares} Ha</b></p>` : ''}
                        ${boundary.description ? `<p class="text-xs text-slate-500 mt-1">${boundary.description}</p>` : ''}
                    </div>
                `);
            }
        });

        // Tambahkan Markers Fasilitas
        facilitiesData.forEach(item => {
            const meta = item.category_meta || categoriesMap[item.category] || {
                label: item.category,
                icon: '📍',
                color: '#059669'
            };
            const condLabel = item.condition_label || (item.condition === 'baik' ? 'Kondisi Baik' : 'Perlu Perbaikan');

            const iconHtml = `
                <div class="facility-marker-icon" style="background-color: white; border: 2.5px solid ${meta.color}; border-radius: 50%; width: 36px; height: 36px; font-size: 17px; box-shadow: 0 4px 10px rgba(0,0,0,0.25);">
                    ${meta.icon}
                </div>
            `;

            const customIcon = L.divIcon({
                html: iconHtml,
                className: '',
                iconSize: [36, 36],
                iconAnchor: [18, 18],
                popupAnchor: [0, -20]
            });

            const marker = L.marker([item.latitude, item.longitude], { icon: customIcon }).addTo(map);

            let assetSection = '';
            if (item.is_village_asset) {
                assetSection = `
                    <div class="mt-2 p-2 rounded-xl bg-emerald-50/90 border border-emerald-200 text-[10px] space-y-1">
                        <div class="flex items-center justify-between font-bold text-emerald-900">
                            <span>🏛️ ASET RESMI DESA</span>
                            <span>${item.kib_meta ? item.kib_meta.code : ''}</span>
                        </div>
                        ${item.kib_meta ? `<div class="font-semibold text-slate-700">${item.kib_meta.name}</div>` : ''}
                        ${item.ownership_meta ? `<div class="text-slate-600">${item.ownership_meta.icon} ${item.ownership_meta.label}</div>` : ''}
                        ${item.register_code ? `<div class="font-mono text-emerald-800">No. Reg: <b>${item.register_code}</b></div>` : ''}
                        ${item.surface_area ? `<div class="text-slate-500">Luas: <b>${Number(item.surface_area).toLocaleString('id-ID')} m²</b></div>` : ''}
                        ${item.formatted_asset_value ? `<div class="text-emerald-700 font-semibold">Nilai: ${item.formatted_asset_value}</div>` : ''}
                    </div>
                `;
            }

            const popupContent = `
                <div class="p-1 max-w-[260px]">
                    ${item.image_url ? `<img src="${item.image_url}" alt="${item.name}" class="w-full h-28 object-cover rounded-xl mb-2">` : ''}
                    <span class="text-[10px] font-bold uppercase tracking-wider block" style="color: ${meta.color};">
                        ${meta.icon} ${meta.label}
                    </span>
                    <h4 class="font-bold text-slate-800 text-xs mt-0.5 leading-snug">${item.name}</h4>
                    ${item.address ? `<p class="text-[11px] text-slate-500 mt-1">📍 ${item.address}</p>` : ''}
                    ${item.description ? `<p class="text-[11px] text-slate-600 mt-1 line-clamp-2">${item.description}</p>` : ''}
                    
                    ${assetSection}

                    <div class="mt-2.5 pt-1.5 border-t border-slate-100 flex items-center justify-between text-[10px]">
                        <span class="text-slate-400">Kondisi:</span>
                        <span class="font-bold ${item.condition === 'baik' ? 'text-emerald-600' : 'text-amber-600'}">${condLabel}</span>
                    </div>
                </div>
            `;

            marker.bindPopup(popupContent);
            marker.category = item.category;
            marker.isVillageAsset = !!item.is_village_asset;
            marker.kibType = item.kib_type;
            marker.facilityName = item.name;
            markers.push(marker);
        });

        // Fit bounds agar seluruh titik fasilitas & batas wilayah otomatis terlihat
        if (markers.length > 0) {
            const group = new L.featureGroup(markers);
            map.fitBounds(group.getBounds().pad(0.2));
        }

        // Pastikan ukuran Leaflet terkalkulasi dengan benar setelah load
        setTimeout(() => {
            map.invalidateSize();
        }, 300);
    });

    // Filter Kategori
    function filterCategory(category) {
        currentCategoryFilter = category;

        document.querySelectorAll('.filter-cat-btn').forEach(btn => {
            btn.className = 'filter-cat-btn px-3.5 py-1.5 rounded-full text-xs font-bold transition whitespace-nowrap bg-slate-50 text-slate-700 hover:bg-slate-100 border border-slate-200';
        });
        const activeBtn = document.getElementById('btn-cat-' + category);
        if (activeBtn) {
            activeBtn.className = 'filter-cat-btn active px-3.5 py-1.5 rounded-full text-xs font-bold transition whitespace-nowrap bg-emerald-600 text-white shadow-sm';
        }

        applyCombinedFilters();
    }

    // Filter Aset Desa
    function filterAsset(assetFilter) {
        currentAssetFilter = assetFilter;

        document.querySelectorAll('.filter-asset-btn').forEach(btn => {
            btn.className = 'filter-asset-btn px-3 py-1 rounded-full text-xs font-semibold transition whitespace-nowrap bg-slate-100 text-slate-700 hover:bg-slate-200 border border-slate-200';
        });
        const activeBtn = document.getElementById('btn-asset-' + assetFilter);
        if (activeBtn) {
            activeBtn.className = 'filter-asset-btn active px-3 py-1 rounded-full text-xs font-semibold transition whitespace-nowrap bg-emerald-700 text-white shadow-sm';
        }

        applyCombinedFilters();
    }

    function applyCombinedFilters() {
        let visibleCount = 0;
        const visibleMarkers = [];

        markers.forEach(marker => {
            const matchCategory = (currentCategoryFilter === 'all' || marker.category === currentCategoryFilter);
            let matchAsset = true;

            if (currentAssetFilter === 'only_assets') {
                matchAsset = marker.isVillageAsset === true;
            } else if (currentAssetFilter !== 'all') {
                matchAsset = (marker.kibType === currentAssetFilter);
            }

            if (matchCategory && matchAsset) {
                map.addLayer(marker);
                visibleMarkers.push(marker);
                visibleCount++;
            } else {
                map.removeLayer(marker);
            }
        });

        // Filter list di sidebar
        document.querySelectorAll('.facility-item').forEach(item => {
            const cat = item.getAttribute('data-category');
            const isAsset = item.getAttribute('data-is-asset') === '1';
            const kib = item.getAttribute('data-kib');

            const matchCat = (currentCategoryFilter === 'all' || cat === currentCategoryFilter);
            let matchAs = true;

            if (currentAssetFilter === 'only_assets') {
                matchAs = isAsset;
            } else if (currentAssetFilter !== 'all') {
                matchAs = (kib === currentAssetFilter);
            }

            if (matchCat && matchAs) {
                item.style.display = 'flex';
            } else {
                item.style.display = 'none';
            }
        });

        const countEl = document.getElementById('visibleCount');
        if (countEl) {
            countEl.innerText = visibleCount + ' titik';
        }

        if (visibleMarkers.length > 0) {
            const group = new L.featureGroup(visibleMarkers);
            map.fitBounds(group.getBounds().pad(0.25));
        }
    }

    // Fokus ke Fasilitas
    function focusFacility(lat, lng, name) {
        map.flyTo([lat, lng], 17, { duration: 1.2 });
        const targetMarker = markers.find(m => m.facilityName === name);
        if (targetMarker) {
            setTimeout(() => targetMarker.openPopup(), 1250);
        }
    }
</script>
@endsection
