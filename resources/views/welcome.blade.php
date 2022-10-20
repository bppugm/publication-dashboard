@extends('layouts.app')

@section('content')
<div class="container-fluid container">
    <dashboard-carousel :dashboards='@json($dashboards)'></dashboard-carousel>
</div>
@endsection