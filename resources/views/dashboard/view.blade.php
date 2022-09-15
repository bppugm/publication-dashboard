@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <dashboard-show :initial-dashboard='@json($dashboard)'></dashboard-show>
        </div>
    </div>
</div>
@endsection
