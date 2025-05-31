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
    <h2>Saham {{ $id }}</h2>
    <table>
        <thead class="table-primary text-nowrap">
            <tr>
                <th rowspan="2">No</th>
                <th rowspan="2">Date</th>
                <th rowspan="2">Saham</th>
                <th rowspan="2">Frekuensi</th>
                <th rowspan="2">Probabilitas</th>
                <th rowspan="2">Kumulatif</th>
                <th colspan="2">Interval</th>
                <th rowspan="2">Bilangan acak</th>
            </tr>
            <tr>
                <th>Awal</th>
                <th>Akhir</th>
            </tr>
        </thead>
        <tbody>
            @if($saham->count() > 0)
                @foreach($saham as $s)
                    <tr>
                        <td class="align-middle">{{ $loop->iteration }}</td>
                        <td class="align-middle">{{ $s->date }}</td>
                        <td class="align-middle">{{ $s->nama_saham }}</td>
                        <td class="align-middle">{{ $s->close }}</td>
                        <td class="align-middle">{{ $s->probabilities }}</td>
                        <td class="align-middle">{{ $s->cumulative }}</td>
                        <td class="align-middle">{{ $s->interval_start }}</td>
                        <td class="align-middle">{{ $s->interval_end }}</td>
                        <td class="align-middle">{{ rand(1, 10000) }}</td>
                    </tr>
                @endforeach
            @endif
        </tbody>
    </table>
    <footer class="text-center text-sm text-muted">
        <p>
            Processed using Monte Carlo Method with
            <a href="https://en.wikipedia.org/wiki/Pseudorandom_number_generator" target="_blank">Pseudorandom Number Generator</a>
            and
            <a href="https://en.wikipedia.org/wiki/Discrete_uniform_distribution" target="_blank">Discrete Uniform Distribution</a>
        </p>
        <small>Generated {{ date('d-m-Y H:i:s', time()) }} by <a href="https://github.com/PiperBelly08/web-monte-carlo-ses" target="_blank">Monte Carlo</a></small>
    </footer>
</body>
</html>
