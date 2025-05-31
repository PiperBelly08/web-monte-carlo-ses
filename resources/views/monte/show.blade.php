@extends('layouts.app')

@section('title', 'Saham '.$id)

@section('contents')
    <a href="{{ route('monte.index') }}" class="btn btn-sm btn-outline-secondary"><i class="fas fa-arrow-left"></i> Kembali</a>
    <hr />
    @if(Session::has('success'))
        <div class="alert alert-success" role="alert">
            {{ Session::get('success') }}
        </div>
    @endif
    <div class="d-flex align-items-center justify-content-between">
        <form action="{{ route('monte.export.pdf') }}" method="POST" style="d-flex align-items-center justify-content-between; gap: 10px;" target="_blank">
            @csrf
            <input type="hidden" name="nama_saham_selected_export" value="{{ $id }}">
            <button class="btn btn-outline-danger" type="submit"><i class="fas fa-file-pdf mr-2"></i>Export PDF</button>
        </form>

        <!-- Button trigger modal -->
        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#randomNumbersModal">
            Bilangan Acak
        </button>

        {{-- Display MAPE value if available --}}
        @if (isset($mape_value))
            <p style="margin-left: auto; margin-right: 0;">MAPE Value: <strong>{{ $mape_value }}%</strong></p>
        @endif
    </div>
    <hr />

    <!-- Modal -->
    <div class="modal fade" id="randomNumbersModal" tabindex="-1" aria-labelledby="randomNumbersModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="randomNumbersModalLabel">Mixed Congruent Method</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    {{-- Form for MCM parameters --}}
                    <form id="mcmForm" action="" method="POST">
                        @csrf
                        <div class="form-group">
                            <label for="mcmSeed">Seed (X0):</label>
                            <input type="number" class="form-control" id="mcmSeed" name="seed" value="0" required>
                        </div>
                        <div class="form-group">
                            <label for="mcmCount">Jumlah Bilangan:</label>
                            <input type="number" class="form-control" id="mcmCount" name="count" value="0" required>
                        </div>
                        <div class="form-group">
                            <label for="mcmMultiplier">Multiplier (a):</label>
                            <input type="number" class="form-control" id="mcmMultiplier" name="multiplier" value="0" required>
                        </div>
                        <div class="form-group">
                            <label for="mcmIncrement">Increment (c):</label>
                            <input type="number" class="form-control" id="mcmIncrement" name="increment" value="0" required>
                        </div>
                        <div class="form-group">
                            <label for="mcmModulus">Modulus (m):</label>
                            <input type="number" class="form-control" id="mcmModulus" name="modulus" value="0" required>
                        </div>
                        <button type="submit" class="btn btn-primary btn-block">
                            <i class="fas fa-calculator mr-2"></i> Generate
                        </button>
                    </form>

                    {{-- Display generated random numbers here if returned via AJAX, or after form submission --}}
                    {{-- @if (isset($random_numbers) && $random_numbers->isNotEmpty())
                        <h6 class="mt-3">Generated Numbers:</h6>
                        <ul class="list-group">
                            @foreach ($random_numbers as $randomNumber)
                                <li class="list-group-item">{{ number_format($randomNumber, 8) }}</li>
                            @endforeach
                        </ul>
                    @elseif (isset($random_numbers) && $random_numbers->isEmpty())
                        <p class="mt-3 text-muted">No random numbers generated yet or with current parameters.</p>
                    @endif --}}
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Kembali</button>
                    {{-- The "Save changes" button is usually for editing, not just generating.
                    The "Generate" button in the form handles submission. --}}
                </div>
            </div>
        </div>
    </div>

    <div class="table-responsive my-4">
        <table class="table table-hover my-2" id="example">
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
                    <th rowspan="2">MAPE</th>
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
                            <td class="align-middle">-</td>
                            <td>60%</td>
                        </tr>
                    @endforeach
                @endif
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="9">Total MAPE</td>
                    <td>20%</td>
                </tr>
            </tfoot>
        </table>
    </div>
    <script>
        let table = new DataTable('#example');
    </script>
@endsection
