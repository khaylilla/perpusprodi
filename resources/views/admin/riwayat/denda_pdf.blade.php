<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Data Denda</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 12px; }
        h2 { text-align: center; margin-bottom: 20px; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #000; padding: 6px; text-align: center; }
        th { background-color: #f2f2f2; }
        tbody tr:nth-child(even) { background-color: #f9f9f9; }
    </style>
</head>
<body>

    <h2>Data Denda Perpustakaan</h2>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Nama Anggota</th>
                <th>NPM</th>
                <th>Judul Buku</th>
                <th>Nomor Buku</th>
                <th>Tanggal Pinjam</th>
                <th>Tanggal Kembali</th>
                <th>Hari Terlambat</th>
                <th>Total Denda (Rp)</th>
            </tr>
        </thead>
        <tbody>
    @forelse($denda as $index => $d)
    <tr>
        <td>{{ $index + 1 }}</td>
        <td>{{ $d->nama }}</td>
        <td>{{ $d->npm }}</td>
        <td>{{ $d->judul_buku }}</td>
        <td>{{ $d->nomor_buku }}</td>
        <td>{{ \Carbon\Carbon::parse($d->tanggal_pinjam)->format('d M Y') }}</td>
        <td>{{ \Carbon\Carbon::parse($d->tanggal_kembali)->format('d M Y') }}</td>
        <td>{{ $d->hari_terlambat }}</td>
        <td>{{ number_format($d->total_denda, 0, ',', '.') }}</td>
    </tr>
    @empty
    <tr>
        <td colspan="9">Tidak ada data</td>
    </tr>
    @endforelse
</tbody>

    </table>

</body>
</html>
