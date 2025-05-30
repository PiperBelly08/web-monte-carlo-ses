@extends('layouts.app')
  
@section('title', 'Create Product')
  
@section('contents')
    <h1 class="mb-0">Add saham</h1>
    <hr />
    <form action="{{ route('saham.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="row mb-3">
            <div class="col">
                <input type="date" name="date" class="form-control" placeholder="Date">
            </div>
        </div>
        <div class="row mb-3">
            <div class="col">
                <input type="text" name="nama_saham" class="form-control" placeholder="Nama Saham">
            </div>
        </div>
        <div class="row mb-3">
            <div class="col">
                <input type="text" name="open" class="form-control" placeholder="Open">
            </div>
        </div>
        <div class="row mb-3">
            <div class="col">
                <input type="text" name="high" class="form-control" placeholder="High">
            </div>
        </div>
        <div class="row mb-3">
            <div class="col">
                <input type="text" name="low" class="form-control" placeholder="Low">
            </div>
        </div>
        <div class="row mb-3">
            <div class="col">
                <input type="text" name="close" class="form-control" placeholder="Close">
            </div>
        </div>
        <div class="row mb-3">
            <div class="col">
                <input type="text" name="volume" class="form-control" placeholder="Volume">
            </div>
        </div>
        <div class="row">
            <div class="d-grid">
                <button type="submit" class="btn btn-primary">Submit</button>
            </div>
        </div>
    </form>
@endsection