<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistem Peminjaman Alat</title>
</head>
<body>
    <h1>Sistem Peminjaman Alat Laboratorium</h1>

    @if(session('success'))
        <p style="color: green;"><strong>{{ session('success') }}</strong></p>
    @endif

    @if(session('error'))
        <p style="color: red;"><strong>{{ session('error') }}</strong></p>
    @endif

    <hr>

    <h2>Form Tambah Peminjaman</h2>
    <form action="{{ route('peminjaman.store') }}" method="POST">
        @csrf
        
        <label>Kode Peminjaman:</label><br>
        <input type="text" name="kode_peminjaman" value="{{ old('kode_peminjaman') }}" required>
        @error('kode_peminjaman')
            <span style="color: red;">{{ $message }}</span>
        @enderror
        <br><br>

        <label>Nama Peminjam:</label><br>
        <input type="text" name="nama_peminjam" value="{{ old('nama_peminjam') }}" required>
        @error('nama_peminjam')
            <span style="color: red;">{{ $message }}</span>
        @enderror
        <br><br>

        <label>Nomor Identitas:</label><br>
        <input type="text" name="nomor_identitas" value="{{ old('nomor_identitas') }}" required>
        @error('nomor_identitas')
            <span style="color: red;">{{ $message }}</span>
        @enderror
        <br><br>

        <label>Jumlah Alat:</label><br>
        <input type="number" name="jumlah_alat" value="{{ old('jumlah_alat') }}" min="1" max="10" required>
        @error('jumlah_alat')
            <span style="color: red;">{{ $message }}</span>
        @enderror
        <br><br>

        <label>Alat:</label><br>
        <select name="alat_id" required>
            <option value="">-- Pilih Alat --</option>
            @foreach($alats as $alat)
                <option value="{{ $alat['id'] }}" {{ old('alat_id') == $alat['id'] ? 'selected' : '' }}>
                    {{ $alat['nama_alat'] }}
                </option>
            @endforeach
        </select>
        @error('alat_id')
            <span style="color: red;">{{ $message }}</span>
        @enderror
        <br><br>

        <button type="submit">Simpan Peminjaman</button>
    </form>

    <hr>

    <h2>Pencarian dan Filter</h2>
    <form action="{{ route('peminjaman.index') }}" method="GET">
        <label>Cari (Kode/Nama/Nomor Identitas):</label>
        <input type="text" name="search" value="{{ request('search') }}">
        
        <label>Filter Alat:</label>
        <select name="alat_id">
            <option value="">-- Semua Alat --</option>
            @foreach($alats as $alat)
                <option value="{{ $alat['id'] }}" {{ request('alat_id') == $alat['id'] ? 'selected' : '' }}>
                    {{ $alat['nama_alat'] }}
                </option>
            @endforeach
        </select>
        
        <button type="submit">Cari</button>
        <a href="{{ route('peminjaman.index') }}">Reset</a>
    </form>

    <hr>

    <h2>Daftar Peminjaman Alat</h2>
    <table border="1" cellpadding="10" cellspacing="0">
        <thead>
            <tr>
                <th>No</th>
                <th>Kode Peminjaman</th>
                <th>Nama Peminjam</th>
                <th>Nomor Identitas</th>
                <th>Alat</th>
                <th>Jumlah</th>
                <th>Status Jumlah</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @if(count($peminjamans) > 0)
                @foreach($peminjamans as $index => $peminjaman)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $peminjaman['kode_peminjaman'] }}</td>
                        <td>{{ $peminjaman['nama_peminjam'] }}</td>
                        <td>{{ $peminjaman['nomor_identitas'] }}</td>
                        <td>{{ $peminjaman['nama_alat'] }}</td>
                        <td>{{ $peminjaman['jumlah_alat'] }}</td>
                        <td>
                            @if($peminjaman['jumlah_alat'] >= 3)
                                <strong>{{ statusJumlah($peminjaman['jumlah_alat']) }}</strong>
                            @else
                                {{ statusJumlah($peminjaman['jumlah_alat']) }}
                            @endif
                        </td>
                        <td>
                            <a href="{{ route('peminjaman.edit', $peminjaman['id']) }}">Edit</a>
                            |
                            <form action="{{ route('peminjaman.destroy', $peminjaman['id']) }}" method="POST" style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" onclick="return confirm('Yakin hapus data ini?')">Hapus</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            @else
                <tr>
                    <td colspan="8">Tidak ada data peminjaman</td>
                </tr>
            @endif
        </tbody>
    </table>
</body>
</html>
