@extends('default')
@section('content')
    <div id="transparency_page">
        @include('components/head-stats')
        <div class="graph-container graph-section-common">
            <div class="label">Transparency</div>
            <img src="/img/transparency_graph.png"/>
        </div>
    </div>
@endsection