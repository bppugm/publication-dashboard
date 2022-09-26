@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row py-5">
        <div class="col-md-12">
            <div class="d-flex justify-content-between">
                <h5 class="text-primary card-title mb-0">
                    <b>DASHBOARD</b>
                </h5>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard.index') }}">Dashboard List</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Dashboard Detail</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <dashboard-show :initial-dashboard='@json($dashboard)'></dashboard-show>
        </div>
    </div>
</div>
@endsection
