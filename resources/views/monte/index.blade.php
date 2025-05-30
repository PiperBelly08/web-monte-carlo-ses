@extends('layouts.app')

@section('title', 'Monte Carlo')

@section('contents')
    <div style="d-flex align-items-center justify-content-between; gap: 10px;">
        {{-- <h1 class="mb-0">Saham</h1> --}}
        <form action="{{ route('monte.export.pdf') }}" method="POST" style="d-flex align-items-center justify-content-between; gap: 10px;" target="_blank">
            @csrf
            <input type="hidden" name="nama_saham_selected_export" value="{{ old('nama_saham_selected', request('nama_saham_selected')) }}">
            <button class="btn btn-outline-danger" type="submit">Export PDF</button>
        </form>
    </div>
    <form action="{{ route('monte.index') }}" method="GET" style="d-flex align-items-center justify-content-between; gap: 10px;">
        @csrf
        @php
            $selected = old('nama_saham_selected', request('nama_saham_selected'));
        @endphp
        <select class="custom-select" aria-label="Nama Saham" name="nama_saham_selected">
            @foreach ($nama_saham as $ns)
                <option value="{{ $ns }}" @selected($ns == $selected)>{{ $ns }}</option>
            @endforeach
        </select>
        <button type="submit" class="btn btn-outline-info">Pilih</button>
    </form>
    <hr />
    @if(Session::has('success'))
        <div class="alert alert-success" role="alert">
            {{ Session::get('success') }}
        </div>
    @endif
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
                    <th rowspan="2">Action</th>
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
                            <td class="align-middle">
                                <div class="btn-group btn-group-sm" role="group" aria-label="Actions">
                                    <a href="{{ route('monte.show', $s->id) }}" type="button" class="btn btn-secondary">Detail</a>
                                    <a href="{{ route('monte.edit', $s->id)}}" type="button" class="btn btn-warning">Edit</a>
                                    <form action="{{ route('monte.destroy', $s->id) }}" method="POST" type="button" onsubmit="return confirm('Delete?')" class="p-0 m-0">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-sm btn-danger m-0">Delete</button>
                                    </form>
                                </div>
                            </td>
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
