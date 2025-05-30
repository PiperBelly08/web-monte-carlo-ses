@extends('layouts.app')
  
@section('title', 'Show Product')
  
@section('contents')
    <h1 class="mb-0">Detail Product</h1>
    <hr />
    <div class="row">
        <div class="col mb-3">
            <label class="form-label">Date</label>
            <input type="date" name="date" class="form-control" placeholder="date" value="{{ $saham->date }}" readonly>
        </div>
    </div>
    <div class="row">
        <div class="col mb-3">
            <label class="form-label">Nama Saham</label>
            <input type="text" name="nama_saham" class="form-control" placeholder="Nama Saham" value="{{ $saham->nama_saham }}" readonly>
        </div>
    </div>
    <div class="row">
        <div class="col mb-3">
            <label class="form-label">Open</label>
            <input type="text" name="open" class="form-control" placeholder="Open" value="{{ $saham->open }}" readonly>
        </div>
    </div>
    <div class="row">
        <div class="col mb-3">
            <label class="form-label">High</label>
            <input type="text" name="high" class="form-control" placeholder="High" value="{{ $saham->high }}" readonly>
        </div>
    </div>
    <div class="row">
        <div class="col mb-3">
            <label class="form-label">Low</label>
            <input type="text" name="low" class="form-control" placeholder="Low" value="{{ $saham->low }}" readonly>
        </div>
    </div>
    <div class="row">
        <div class="col mb-3">
            <label class="form-label">Close</label>
            <input type="text" name="close" class="form-control" placeholder="Close" value="{{ $saham->close }}" readonly>
        </div>
    </div>
    <div class="row">
        <div class="col mb-3">
            <label class="form-label">Volume</label>
            <input type="text" name="volume" class="form-control" placeholder="Volume" value="{{ $saham->volume }}" readonly>
        </div>
    </div>
    <div class="row">
        <div class="col mb-3">
            <label class="form-label">Created At</label>
            <input type="text" name="created_at" class="form-control" placeholder="Created At" value="{{ $saham->created_at }}" readonly>
        </div>
    </div>
    <div class="row">
        <div class="col mb-3">
            <label class="form-label">Updated At</label>
            <input type="text" name="updated_at" class="form-control" placeholder="Updated At" value="{{ $saham->updated_at }}" readonly>
        </div>
    </div>
@endsection