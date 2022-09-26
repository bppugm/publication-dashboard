@extends('layouts.app')

@section('content')
    <div class="container h-100">
        <div>
            {{-- Header title --}}
            <div class="d-flex justify-content-between my-3">
                <h5 class="text-primary card-title mb-0">
                    <b>DASHBOARD</b>
                </h5>
                <div class="text-dark"><b>Dashboard List</b></div>
            </div>

            {{-- Placeholder for displayed dashboard --}}
            <div class="card border-0 shadow-sm p-3">
                <div id="displayed-dashboard" role="displayed-dashboard" aria-labelledby="displayed-dashboard">
                    <div class="card-header text-primary bg-transparent">
                        <h5><b>Displayed Dashboard List</b></h5>
                    </div>
                    <div class="card-body text-primary bg-transparent">
                        <h5>Coming soon ...</h5>
                    </div>
                </div>
            </div>

            {{-- Dashboard list --}}
            <div class="card border-0 shadow-sm p-3 mt-3">
                <div id="dashboards" role="dashboard-list" aria-labelledby="dashboard-list">
                    <div class="card-header text-primary bg-transparent">
                        <h5><b>All Dashboard List</b></h5>
                    </div>
                    <!-- Heading Table -->
                    <div class="d-flex my-3 justify-content-between">
                        <!-- Search bar -->
                        <form class="d-flex justify-content-between w-100" method="GET" action="{{ route('dashboard.index') }}">
                            <div class="input-group me-3">
                                <input type="text" name="search" placeholder="Search Dashboard"
                                    class="form-control search-input" value="{{ request('search') }}">

                                @if (request('search'))
                                    <a href="{{ route('dashboard.index') }}">
                                        <span class="input-group-btn">
                                            <button type="button" class="btn btn-search-append" title="Refresh Page">
                                                <i class="mdi mdi-close"></i>
                                            </button>
                                        </span>
                                    </a>
                                @endif

                                <span class="input-group-btn">
                                    <button type="submit" class="btn btn-input-append btn-outline-primary">
                                        Search
                                    </button>
                                </span>
                            </div>
                        </form>
                        <!-- Add Dashboard Button -->
                        <div class="flex-shrink-0">
                            <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                                data-bs-target="#dashboard-form-modal">
                                <i class="mdi mdi-plus"></i> Add Dashboard
                            </button>
                        </div>
                    </div>
                    <!-- Table -->
                    <dashboard-list :data='{{ json_encode($dashboards->items()) }}'>
                        {{ $dashboards->links() }}
                    </dashboard-list>
                </div>
            </div>
            <dashboard-form-modal></dashboard-form-modal>
        </div>
    </div>
@endsection
