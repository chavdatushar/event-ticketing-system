@extends('layouts.app')
@section('title','Login')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                Dashboard, Welcome {{ auth()->user()->name }}
            </div>
            <div class="card-body">
            </div>
        </div>
    </div>
</div>
@endsection
