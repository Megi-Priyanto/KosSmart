@extends('layouts.user')

@section('title', 'Booking Berhasil')

@section('content')

<div class="max-w-3xl mx-auto py-12">
    
    <!-- Success Icon -->
    <div class="text-center mb-8">
        <div class="inline-flex items-center justify-center w-24 h-24 bg-green-100 rounded-full mb-6">
            <svg class="w-12 h-12 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
        </div>
        
        <h1 class="text-3xl font-bold text-gray-800 mb-2">🎉 Booking Berhasil!</h1>
        <p class="text-gray-600">Terima kasih telah melakukan booking di KosSmart</p>
    </div>
    
    <!-- Info Card -->
    <div class="bg-white rounded-xl shadow-lg border border-gray-200 p-8 mb-6">
        <div class="space-y-4">
            <div class="flex items-start bg-yellow-50 border-l-4 border-yellow-400 p-4 rounded-lg">
                <svg class="w-6 h-6 text-green-500 mr-3 mt-1 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <div>
                    <p class="font-semibold text-gray-800">Booking Anda sedang dalam proses verifikasi</p>
                    <p class="text-sm text-gray-600 mt-1">Tim kami akan menghubungi Anda dalam waktu 1x24 jam untuk konfirmasi</p>
                </div>
            </div>
            
            <div class="flex items-start bg-yellow-50 border-l-4 border-yellow-400 p-4 rounded-lg">
                <svg class="w-6 h-6 text-purple-500 mr-3 mt-1 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                </svg>
                <div>
                    <p class="font-semibold text-gray-800">Cek email Anda</p>
                    <p class="text-sm text-gray-600 mt-1">Kami telah mengirimkan konfirmasi booking ke {{ Auth::user()->email }}</p>
                </div>
            </div>
            
            <div class="flex items-start bg-yellow-50 border-l-4 border-yellow-400 p-4 rounded-lg">
                <svg class="w-6 h-6 text-purple-500 mr-3 mt-1 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                </svg>
                <div>
                    <p class="font-semibold text-gray-800">Status booking</p>
                    <p class="text-sm text-gray-600 mt-1">Anda dapat melihat status booking Anda di dashboard</p>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Next Steps -->
    <div class="bg-gradient-to-br from-purple-50 to-indigo-50 rounded-xl border border-purple-200 p-6 mb-6">
        <h3 class="text-lg font-bold text-gray-800 mb-4">Langkah Selanjutnya</h3>
        <ol class="space-y-3">
            <li class="flex items-start">
                <span class="flex items-center justify-center w-6 h-6 bg-purple-600 text-white rounded-full text-sm font-bold mr-3 flex-shrink-0">1</span>
                <p class="text-gray-700">Tunggu konfirmasi dari admin melalui telepon atau WhatsApp</p>
            </li>
            <li class="flex items-start">
                <span class="flex items-center justify-center w-6 h-6 bg-purple-600 text-white rounded-full text-sm font-bold mr-3 flex-shrink-0">2</span>
                <p class="text-gray-700">Lakukan pembayaran sisa jika booking disetujui</p>
            </li>
            <li class="flex items-start">
                <span class="flex items-center justify-center w-6 h-6 bg-purple-600 text-white rounded-full text-sm font-bold mr-3 flex-shrink-0">3</span>
                <p class="text-gray-700">Serahkan dokumen identitas (KTP/SIM/Paspor)</p>
            </li>
            <li class="flex items-start">
                <span class="flex items-center justify-center w-6 h-6 bg-purple-600 text-white rounded-full text-sm font-bold mr-3 flex-shrink-0">4</span>
                <p class="text-gray-700">Terima kunci kamar dan mulai menempati</p>
            </li>
        </ol>
    </div>
    
    <!-- Actions -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <a href="{{ route('user.dashboard') }}" 
           class="flex items-center justify-center space-x-2 bg-purple-600 text-white py-3 rounded-lg hover:bg-purple-700 font-semibold transition-colors">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
            </svg>
            <span>Ke Dashboard</span>
        </a>
        
        <a href="{{ route('user.rooms.index') }}" 
           class="flex items-center justify-center space-x-2 border-2 border-gray-300 text-gray-700 py-3 rounded-lg hover:bg-gray-50 font-semibold transition-colors">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
            </svg>
            <span>Lihat Kamar Lain</span>
        </a>
    </div>
    
    <!-- Contact Info -->
    <div class="mt-8 text-center">
        <p class="text-gray-600 mb-2">Ada pertanyaan?</p>
        <a href="https://wa.me/6281234567890" 
           target="_blank"
           class="inline-flex items-center space-x-2 text-green-600 hover:text-green-700 font-medium">
            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z"/>
            </svg>
            <span>Hubungi Kami via WhatsApp</span>
        </a>
    </div>
    
</div>

@endsection