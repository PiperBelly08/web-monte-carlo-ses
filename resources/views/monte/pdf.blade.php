<!DOCTYPE html>
<html>
<head>
    <title>Daftar Saham</title>
    <style>
        body { font-family: sans-serif; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #333; padding: 8px; }
        th { background-color: #eee; }
    </style>
</head>
<body>
    <h2>Daftar Saham</h2>
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Date</th>
                <th>Saham</th>
                <th>Close</th>
                <th>Probabilitas</th>
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
                    <td>{{ $sh->close }}</td>
                    <td>{{ number_format($sh->close / $closingSum, 4); }}</td>
                    <td>{{ 0 }}</td>
                    <td>{{ 0 }}</td>
                    <td>{{ 0 }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>