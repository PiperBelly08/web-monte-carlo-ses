@extends('layouts.app')

@section('title', 'Saham '.$id)

@section('contents')
    <a href="{{ route('monte.index') }}" class="btn btn-sm btn-outline-secondary">Kembali</a>
    <hr />
    @if(Session::has('success'))
        <div class="alert alert-success" role="alert">
            {{ Session::get('success') }}
        </div>
    @endif
    <div style="d-flex align-items-center justify-content-between; gap: 10px;">
        <form action="{{ route('monte.export.pdf') }}" method="POST" style="d-flex align-items-center justify-content-between; gap: 10px;" target="_blank">
            @csrf
            <input type="hidden" name="nama_saham_selected_export" value="{{ $id }}">
            <button class="btn btn-outline-danger" type="submit"><i class="fas fa-file-pdf mr-2"></i>Export PDF</button>
        </form>
    </div>
    <hr />
    <div class="table-responsive">
        <table class="table table-hover" id="example">
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
    </div>
    <script>
        let table = new DataTable('#example');
    </script>
@endsection
