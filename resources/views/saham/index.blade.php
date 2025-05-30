@extends('layouts.app')
  
@section('title', 'Saham')
  
@section('contents')
    <div style="d-flex align-items-center justify-content-between; gap: 10px;">
        {{-- <h1 class="mb-0">Saham</h1> --}}
        <a href="{{ route('saham.create') }}" class="btn btn-primary">Add Product</a>
        <a href="{{ route('saham.export.pdf') }}" class="btn btn-outline-danger" target="_blank">Export PDF</a>
       <form action="{{ route('saham.clear') }}" method="POST" enctype="multipart/form-data" onsubmit="return confirm('Hapus Semua Data?')" class="d-inline">
            @csrf
            <button type="submit" class="btn btn-outline-danger">Clear</button>
        </form>
    </div>
    <div style="d-flex align-items-center justify-content-between; gap: 10px;">
        {{-- Form Upload --}}
        <form action="{{ route('saham.import') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="mb-3">
                <label for="file" class="form-label">Pilih File Excel atau CSV</label>
                <input type="file" name="file" id="file" class="form-control" accept=".xlsx,.xls,.csv" required>
            </div>
            <button type="submit" class="btn btn-success">Import</button>
            <a href="{{ route('saham.index') }}" class="btn btn-secondary">Kembali</a>
        </form>
    </div>
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
                    <th>Open</th>
                    <th>High</th>
                    <th>Low</th>
                    <th>Close</th>
                    <th>Volume</th>
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
                            <td class="align-middle">{{ $rs->open }}</td>
                            <td class="align-middle">{{ $rs->high }}</td>
                            <td class="align-middle">{{ $rs->low }}</td> 
                            <td class="align-middle">{{ $rs->close}}</td> 
                            <td class="align-middle">{{ $rs->volume }}</td>
                            <td class="align-middle">
                                <div class="btn-group" role="group" aria-label="Basic example">
                                    <a href="{{ route('saham.show', $rs->id) }}" type="button" class="btn btn-secondary">Detail</a>
                                    <a href="{{ route('saham.edit', $rs->id)}}" type="button" class="btn btn-warning">Edit</a>
                                    <form action="{{ route('saham.destroy', $rs->id) }}" method="POST" type="button" class="btn btn-danger p-0" onsubmit="return confirm('Delete?')">
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