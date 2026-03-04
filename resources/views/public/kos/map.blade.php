@extends('layouts.public')

@section('title', 'Peta Lokasi Kos - KosSmart')

@push('head-scripts')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />
<style>
    /* Ensure the map fits between navbar and footer */
    #map {
        height: calc(100vh - 72px);
        width: 100%;
        z-index: 10;
    }

    .custom-popup .leaflet-popup-content-wrapper {
        border-radius: 12px;
        padding: 0;
        overflow: hidden;
    }

    .custom-popup .leaflet-popup-content {
        margin: 0;
        width: 250px !important;
    }

    .custom-popup .leaflet-popup-close-button {
        color: white !important;
        background: rgba(0, 0, 0, 0.5) !important;
        border-radius: 50%;
        text-shadow: 0 1px 3px rgba(0, 0, 0, 0.8);
        top: 8px !important;
        right: 8px !important;
        width: 24px !important;
        height: 24px !important;
        display: flex;
        align-items: center;
        justify-content: center;
        text-align: center;
    }

    .custom-popup .leaflet-popup-close-button:hover {
        background: rgba(0, 0, 0, 0.8) !important;
    }
</style>
@endpush

@section('content')
<div class="relative w-full bg-slate-100">
    <div class="absolute top-4 left-4 z-[20] bg-white text-slate-800 px-5 py-4 rounded-xl shadow-xl border-l-4 border-amber-500 font-semibold max-w-sm pointer-events-auto">
        Temukan <span class="text-amber-600">{{ count($tempatKosList) }} Kos</span> di Peta
        <p class="text-xs font-normal text-slate-500 mt-1">Pilih penanda di peta untuk melihat harga dan detail kos lebih lanjut.</p>
    </div>

    <div id="map"></div>
</div>
@endsection

@push('scripts')
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Default Center (Bandung)
        let center = [-6.914744, 107.609810];

        const kosData = @json($tempatKosList);

        if (kosData.length > 0) {
            // Gunakan lokasi kos pertama sebagai titik tengah jika ada
            // (Atau bisa cari rata-rata latitude & longitude jika ingin posisi tengah persebaran)
            center = [kosData[0].latitude, kosData[0].longitude];
        }

        const map = L.map('map', {
            zoomControl: false // Kita pindah zoom control ke bawah kanan
        }).setView(center, 13);

        L.control.zoom({
            position: 'bottomright'
        }).addTo(map);

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a>'
        }).addTo(map);

        kosData.forEach(kos => {
            if (kos.latitude && kos.longitude) {
                // Formatting Harga Termurah
                let hargaTeks = 'Harga tidak tersedia';
                if (kos.rooms && kos.rooms.length > 0) {
                    const termurah = kos.rooms[0].price;
                    hargaTeks = new Intl.NumberFormat('id-ID', {
                        style: 'currency',
                        currency: 'IDR',
                        minimumFractionDigits: 0
                    }).format(termurah) + ' <span class="text-xs font-normal text-slate-400">/ bln</span>';
                }

                // Ambil Foto (Bisa dari logo, atau dari kos_infos)
                let fotoUrl = kos.logo ? '/storage/' + kos.logo : 'https://placehold.co/400x300?text=KosSmart';
                if (kos.kos_infos && kos.kos_infos.length > 0) {
                    const images = kos.kos_infos[0].images;
                    if (images && images.length > 0) {
                        fotoUrl = '/storage/' + images[0];
                    }
                }

                // URL Detail Kos
                const detailUrl = '/kos/' + kos.id + '/kamar';

                // Setup Pin/Marker Bulat dengan Gambar di dalamnya
                const markerIcon = L.divIcon({
                    className: 'custom-div-icon',
                    html: `<div class="w-10 h-10 rounded-full border-[3px] border-amber-500 bg-white shadow-lg overflow-hidden flex items-center justify-center transform hover:scale-110 transition-transform">
                                <img src="${fotoUrl}" onerror="this.src='https://placehold.co/100x100?text=Kos'" class="w-full h-full object-cover"/>
                           </div>`,
                    iconSize: [40, 40],
                    iconAnchor: [20, 20],
                    popupAnchor: [0, -20]
                });

                const popupHtml = `
                    <div class="relative group">
                        <img src="${fotoUrl}" class="w-full h-36 object-cover" onerror="this.src='https://placehold.co/400x300?text=KosSmart'">
                        <div class="absolute bottom-0 left-0 right-0 bg-gradient-to-t from-slate-900/90 to-transparent p-4 pt-10">
                            <h3 class="text-white font-bold text-base truncate leading-tight">${kos.nama_kos}</h3>
                            <p class="text-white/80 text-xs truncate mt-0.5" title="${kos.alamat}">${kos.alamat}</p>
                        </div>
                    </div>
                    <div class="p-4 bg-white">
                        <div class="text-lg font-bold text-green-600 mb-3">${hargaTeks}</div>
                        <a href="${detailUrl}" class="block w-full text-center bg-slate-900 hover:bg-slate-800 text-white font-semibold py-2.5 rounded-lg transition-colors text-sm shadow-md">
                            Lihat Detail Kos
                        </a>
                    </div>
                `;

                L.marker([kos.latitude, kos.longitude], {
                        icon: markerIcon
                    })
                    .addTo(map)
                    .bindPopup(popupHtml, {
                        className: 'custom-popup',
                        minWidth: 250
                    });
            }
        });
    });
</script>
@endpush