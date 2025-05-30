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
                    <th>No</th>
                    <th>Date</th>
                    <th>Saham</th>
                    <th>Frekuensi</th>
                    <th>Probabilitas</th>
                    <th>Kumulatif</th>
                    <th>Interval</th>
                    <th>Bilangan acak</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @if($saham->count() > 0)
                    @foreach($saham as $rs)
                        <tr>
                            <td class="align-middle">{{ $loop->iteration }}</td>
                            <td class="align-middle">{{ $rs->date }}</td>
                            <td class="align-middle">{{ $rs->nama_saham }}</td>
                            <td class="align-middle">{{ $rs->close }}</td>
                            <td class="align-middle">{{ number_format($porsisaham[$loop->iteration - 1]->porsi, 4) }}</td>
                            <td class="align-middle">{{ number_format($porsisaham[$loop->iteration - 1]->kumulatif, 4) }}</td> 
                            <td class="align-middle">{{ 0}}</td> 
                            <td class="align-middle">{{ 0 }}</td>
                            <td class="align-middle">
                                <div class="btn-group" role="group" aria-label="Basic example">
                                    <a href="{{ route('monte.show', $rs->id) }}" type="button" class="btn btn-secondary">Detail</a>
                                    <a href="{{ route('monte.edit', $rs->id)}}" type="button" class="btn btn-warning">Edit</a>
                                    <form action="{{ route('monte.destroy', $rs->id) }}" method="POST" type="button" class="btn btn-danger p-0" onsubmit="return confirm('Delete?')">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-danger m-0">Delete</button>
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