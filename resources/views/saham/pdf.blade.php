<!DOCTYPE html>
<html>
<head>
    <title>Daftar Produk</title>
    <style>
        body { font-family: sans-serif; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #333; padding: 8px; }
        th { background-color: #eee; }
    </style>
</head>
<body>
    <h2>Daftar Produk</h2>
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Date</th>
                <th>Nama Saham</th>
                <th>Open</th>
                <th>High</th>
                <th>Low</th>
                <th>Close</th>
                <th>Volume</th>
            </tr>
        </thead>
        <tbody>
            @foreach($saham as $sh)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $sh->date }}</td>
                    <td>{{ $sh->nama_saham }}</td>
                    <td>{{ $sh->open }}</td>
                    <td>{{ $sh->high }}</td>
                    <td>{{ $sh->low }}</td>
                    <td>{{ $sh->close }}</td>
                    <td>{{ $sh->volume }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>