<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Peminjaman</title>
</head>
<body>
    <h1>Edit Data Peminjaman</h1>

    @if(session('error'))
        <p style="color: red;"><strong>{{ session('error') }}</strong></p>
    @endif

    <form action="{{ route('peminjaman.update', $peminjaman['id']) }}" method="POST">
        @csrf
        @method('PUT')
        
        <label>Kode Peminjaman:</label><br>
        <input type="text" name="kode_peminjaman" value="{{ old('kode_peminjaman', $peminjaman['kode_peminjaman']) }}" required>
        @error('kode_peminjaman')
            <span style="color: red;">{{ $message }}</span>
        @enderror
        <br><br>

        <label>Nama Peminjam:</label><br>
        <input type="text" name="nama_peminjam" value="{{ old('nama_peminjam', $peminjaman['nama_peminjam']) }}" required>
        @error('nama_peminjam')
            <span style="color: red;">{{ $message }}</span>
        @enderror
        <br><br>

        <label>Nomor Identitas:</label><br>
        <input type="text" name="nomor_identitas" value="{{ old('nomor_identitas', $peminjaman['nomor_identitas']) }}" required>
        @error('nomor_identitas')
            <span style="color: red;">{{ $message }}</span>
        @enderror
        <br><br>

        <label>Jumlah Alat:</label><br>
        <input type="number" name="jumlah_alat" value="{{ old('jumlah_alat', $peminjaman['jumlah_alat']) }}" min="1" max="10" required>
        @error('jumlah_alat')
            <span style="color: red;">{{ $message }}</span>
        @enderror
        <br><br>

        <label>Alat:</label><br>
        <select name="alat_id" required>
            <option value="">-- Pilih Alat --</option>
            @foreach($alats as $alat)
                <option value="{{ $alat['id'] }}" {{ old('alat_id', $peminjaman['alat_id']) == $alat['id'] ? 'selected' : '' }}>
                    {{ $alat['nama_alat'] }}
                </option>
            @endforeach
        </select>
        @error('alat_id')
            <span style="color: red;">{{ $message }}</span>
        @enderror
        <br><br>

        <button type="submit">Update Peminjaman</button>
        <a href="{{ route('peminjaman.index') }}">Batal</a>
    </form>
</body>
</html>
