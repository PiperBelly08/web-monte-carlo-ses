@extends('layouts.app')

@section('title', 'Saham')

@section('contents')
    <div style="d-flex align-items-center justify-content-between; gap: 10px;">
        <div class="btn-group btn-group-sm" role="group" aria-label="Actions">
            <a href="{{ route('saham.create') }}" class="btn btn-primary">Add Product</a>
            <a href="{{ route('saham.export.pdf') }}" class="btn btn-outline-danger" target="_blank">Export PDF</a>
            <form action="{{ route('saham.clear') }}" method="POST" enctype="multipart/form-data" onsubmit="return confirm('Hapus Semua Data?')" class="m-0 p-0">
                @csrf
                <button type="submit" class="btn btn-sm btn-outline-danger">Clear</button>
            </form>
        </div>
    </div>
    <div style="d-flex align-items-center justify-content-between; gap: 10px;">
        {{-- Form Upload --}}
        <form action="{{ route('saham.import') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="input-group my-3">
                <div class="input-group-prepend">
                    <button class="btn btn-outline-primary" type="submit">Import</button>
                </div>
                <div class="custom-file">
                    <input type="file" name="file" id="file" class="custom-file-input" accept=".xlsx,.xls,.csv" required>
                    <label for="file" class="custom-file-label" id="file">Pilih File Excel atau CSV</label>
                </div>
            </div>
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
                                <div class="btn-group btn-group-sm" role="group" aria-label="Actions">
                                    <a href="{{ route('saham.show', $rs->id) }}" type="button" class="btn btn-secondary">Detail</a>
                                    <a href="{{ route('saham.edit', $rs->id)}}" type="button" class="btn btn-warning">Edit</a>
                                    <form action="{{ route('saham.destroy', $rs->id) }}" method="POST" type="button" class="p-0 m-0" onsubmit="return confirm('Delete?')">
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
