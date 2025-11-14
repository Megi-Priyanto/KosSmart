@extends('layouts.admin')

@section('content')
<div class="container mt-4">
    <h2>Buat Tagihan Baru</h2>
    <form action="{{ route('admin.billing.store') }}" method="POST">
        @csrf
        <div class="mb-3">
            <label for="nama" class="form-label">Nama Penghuni</label>
            <input type="text" class="form-control" name="nama" required>
        </div>
        <div class="mb-3">
            <label for="jumlah" class="form-label">Jumlah Tagihan</label>
            <input type="number" class="form-control" name="jumlah" required>
        </div>
        <button type="submit" class="btn btn-primary">Simpan</button>
    </form>
</div>
@endsection
