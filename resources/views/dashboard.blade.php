@extends('layouts.app')

@section('title', 'Dashboard')

@section('contents')
    <div class="container">
      <div class="row">
          {{-- @foreach ($saham as $s) --}}
              <div class="col">
                  <div class="card">
                      <div class="card-body">
                          <h5 class="card-title">Total Data</h5>
                          <h1 class="card-text">{{ $total }}</h1>
                      </div>
                  </div>
              </div>
              <div class="col">
                  <div class="card">
                      <div class="card-body">
                          <h5 class="card-title">Total Saham</h5>
                          <h1 class="card-text">{{ $total_saham }}</h1>
                      </div>
                  </div>
              </div>
              <div class="col">
                  <div class="card">
                      <div class="card-body">
                          <h5 class="card-title">Total Closing</h5>
                          <h1 class="card-text">{{ $total_close }}</h1>
                      </div>
                  </div>
              </div>
          {{-- @endforeach --}}
          <hr class="col-12" />
          {{-- <div class="col-12">
              <h2>Saham</h2>
          </div> --}}

      </div>
    </div>
@endsection
