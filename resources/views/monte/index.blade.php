@extends('layouts.app')

@section('title', 'Monte Carlo')

@section('contents')
    <hr />
    <div class="container">
        <div class="row">
            @foreach ($nama_saham as $ns)
                <div class="col">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">{{ $ns }}</h5>
                            <a href="{{ route('monte.show.data', ['id' => $ns]) }}" class="btn btn-primary d-block"><i class="fas fa-external-link-alt"></i> Buka</a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
@endsection
