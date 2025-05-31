@extends('layouts.app')

@section('title', 'Saham')

@section('contents')
    <div style="d-flex align-items-center justify-content-between; gap: 10px;">
        <div class="btn-group btn-group-sm" role="group" aria-label="Actions">
            <a href="{{ route('saham.create') }}" class="btn btn-primary"><i class="fas fa-plus mr-2"></i>Add Product</a>
            <a href="{{ route('saham.export.pdf') }}" class="btn btn-outline-danger" target="_blank"><i class="fas fa-file-pdf mr-2"></i>Export PDF</a>
            <form action="{{ route('saham.clear') }}" method="POST" enctype="multipart/form-data" onsubmit="return confirm('Hapus Semua Data?')" class="m-0 p-0">
                @csrf
                <button type="submit" class="btn btn-sm btn-outline-danger"><i class="fas fa-trash mr-2"></i>Clear</button>
            </form>
        </div>
    </div>
    <div style="d-flex align-items-center justify-content-between; gap: 10px;">
        {{-- Form Upload --}}
        <form action="{{ route('saham.import') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="input-group my-3">
                <div class="input-group-prepend">
                    <button class="btn btn-outline-primary" type="submit"><i class="fas fa-file-excel mr-2"></i>Import</button>
                </div>
                <div class="custom-file">
                    <input type="file" name="file" id="file" class="custom-file-input" accept=".xlsx,.xls,.csv" required>
                    <label for="file" class="custom-file-label" id="file">Pilih File Excel atau CSV</label>
                </div>
            </div>
        </form>
    </div>
    <hr />
    <select class="custom-select custom-select-md mb-3" id="nama_saham">
        <option selected value="-">-- Pilih Saham --</option>
        @foreach($nama_saham as $ns)
            <option value="{{ $ns }}" @selected(old('nama_saham') == $ns)>{{ $ns }}</option>
        @endforeach
    </select>
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

        const selectOption = document.getElementById('nama_saham');

        selectOption.addEventListener('change', () => {
            fetch("{{ route('api.saham') }}", {
                method: 'POST', // Specify the HTTP method
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json',
                },
                body: JSON.stringify({
                    nama_saham: document.getElementById('nama_saham').value,
                }),
            })
                .then((response) => {
                    if (!response.ok) {
                    throw new Error(`HTTP error! Status: ${response.status}`);
                    }
                    return response.json(); // Parse the JSON response
                })
                .then((result) => {
                    // You can update the table or UI with the result here
                    table.clear(); // Clear the existing table data
                    result.forEach((row, index) => {
                        row.no = index + 1; // Add incremental number
                    });
                    table.rows.add(result.map(item => [
                        item.no,
                        item.date,
                        item.nama_saham,
                        item.open,
                        item.high,
                        item.low,
                        item.close,
                        item.volume,
                        `<div class="btn-group btn-group-sm" role="group" aria-label="Actions">
                            <a href="/saham/${item.id}" type="button" class="btn btn-secondary">Detail</a>
                            <a href="/saham/${item.id}/edit" type="button" class="btn btn-warning">Edit</a>
                            <form action="/saham/${item.id}" method="POST" type="button" class="p-0 m-0" onsubmit="return confirm('Delete?')">
                                <input type="hidden" name="_method" value="DELETE">
                                <input type="hidden" name="_token" value="${document.querySelector('meta[name="csrf-token"]').getAttribute('content')}">
                                <button class="btn btn-sm btn-danger m-0">Delete</button>
                            </form>
                        </div>`,
                    ]));

                    table.draw(); // Redraw the table to reflect the changes
                })
                .catch((error) => {
                    console.error('Error:', error); // Handle errors
                });
        });
    </script>
@endsection
