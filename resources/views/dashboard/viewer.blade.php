@extends('layouts.app')

@section('content')
    <div class="container-fluid container">
        <div class="row">
            <div class="col-md-12">
                <div class="card justify-content-between my-3 shadow-sm">
                    @if ($url)
                        <div class="card-body border-warning d-flex justify-content-between" style="border-bottom: 4px solid">
                            <div class="text-primary">
                                <a href="{{ $url }}" class="text-decoration-none">
                                    <i class="mdi mdi-arrow-left"> BACK </i>
                                </a>
                            </div>
                            <div class="text-primary">
                                <h4 class="m-0">{{ Ucwords($dashboard->name) }}</h4>
                            </div>
                        </div>
                    @else
                        {{-- card body with line yellow on the bottom of the card --}}
                        <div class="card-body border-warning d-flex" style="border-bottom: 4px solid">
                            <div class="text-primary col">
                                <h4 class="m-0">{{ Ucwords($dashboard->name) }}</h4>
                            </div>
                            <div class="justify-content-end">
                                <i class="mdi mdi-calendar pt-1 text-center"> {{ date('l, d F Y') }} </i>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <dashboard-show :can-edit='false' :initial-dashboard='@json($dashboard)'></dashboard-show>
            </div>
        </div>
    </div>
@endsection
