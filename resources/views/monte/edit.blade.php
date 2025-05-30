@extends('layouts.app')
  
@section('title', 'Edit saham')
  
@section('contents')
    <h1 class="mb-0">Edit saham</h1>
    <hr />
    <form action="{{ route('saham.update', $saham->id) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="row">
            <div class="col mb-3">
                <label class="form-label">Date</label>
                <input type="date" name="date" class="form-control" placeholder="Date" value="{{ $saham->date }}" >
            </div>
            <div class="col mb-3">
                <label class="form-label">Nama saham</label>
                <input type="text" name="nama_saham" class="form-control" placeholder="Nama saham" value="{{ $saham->nama_saham }}" >
            </div>
            <div class="col mb-3">
                <label class="form-label">Close</label>
                <input type="text" name="close" class="form-control" placeholder="Close" value="{{ $saham->close }}" >
            </div>
            <div class="col mb-3">
                <label class="form-label">High</label>
                <input type="text" name="high" class="form-control" placeholder="High" value="{{ 0 }}" >
            </div>
            <div class="col mb-3">
                <label class="form-label">Low</label>
                <input type="text" name="low" class="form-control" placeholder="Low" value="{{ 0 }}" >
            </div>
            <div class="col mb-3">
                <label class="form-label">Close</label>
                <input type="text" name="close" class="form-control" placeholder="Close" value="{{ 0 }}" >
            </div>
            <div class="col mb-3">
                <label class="form-label">Volume</label>
                <input type="text" name="volume" class="form-control" placeholder="Volume" value="{{ 0 }}" >
            </div>
        </div>
        <div class="row">
            <div class="d-grid">
                <button class="btn btn-warning">Update</button>
            </div>
        </div>
    </form>
@endsection