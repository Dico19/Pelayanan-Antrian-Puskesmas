<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Antrian</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 11px; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid black; padding: 4px; }
        th { background-color: #eee; }
        h3 { text-align: center; }
    </style>
</head>
<body>

<h3>Laporan Antrian Puskesmas</h3>

<table>
    <thead>
        <tr>
            <th>No Antrian</th>
            <th>Nama</th>
            <th>Alamat</th>
            <th>Jenis Kelamin</th>
            <th>No HP</th>
            <th>No KTP</th>
            <th>Tgl Lahir</th>
            <th>Pekerjaan</th>
            <th>Poli</th>
            <th>Tanggal Antrian</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($antrians as $row)
        <tr>
            <td>{{ $row->no_antrian }}</td>
            <td>{{ $row->nama }}</td>
            <td>{{ $row->alamat }}</td>
            <td>{{ $row->jenis_kelamin }}</td>
            <td>{{ $row->no_hp }}</td>
            <td>{{ $row->no_ktp }}</td>
            <td>{{ $row->tgl_lahir }}</td>
            <td>{{ $row->pekerjaan }}</td>
            <td>{{ $row->poli }}</td>
            <td>{{ $row->tanggal_antrian }}</td>
        </tr>
        @endforeach
    </tbody>
</table>

</body>
</html>
