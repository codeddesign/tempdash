@extends('default-errors')
@section('content')
    <div style="margin-top: 100px; text-align: center;" class="container">
        <h1>Access Denied</h1>
        <p>{{ $exception->getMessage() }}</p>
    </div>
@endsection