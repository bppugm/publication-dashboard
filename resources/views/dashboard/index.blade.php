@extends('layouts.app')

@section('content')
    <div class="container h-100">
        <div>
            {{-- Header title --}}
            <div class="d-flex justify-content-between my-30">
                <h5 class="text-primary card-title mb-0">
                    <b>DASHBOARD</b>
                </h5>
                <div class="text-end">
                    <nav class="breadcrumb m-0">
                        <a class="breadcrumb-item active" aria-current="dashboard" style="text-decoration: none"><b>Dashboard List</b></a>
                    </nav>
                </div>
            </div>

            {{-- Placeholder for displayed dashboard --}}
            <div class="card border-0 shadow-sm p-30">
                <div id="displayed-dashboard" role="displayed-dashboard" aria-labelledby="displayed-dashboard">
                    <div class="card-header text-primary bg-transparent p-0 pb-1">
                        <h5><b>Displayed Dashboard List</b></h5>
                    </div>
                    <div class="card-body text-primary bg-transparent">
                        <h5>Coming soon ...</h5>
                    </div>
                </div>
            </div>

            {{-- Dashboard list --}}
            <div class="card border-0 shadow-sm p-30" style="margin-top: 2rem">
                <div id="dashboards" role="dashboard-list" aria-labelledby="dashboard-list">
                    <div class="card-header text-primary bg-transparent p-0 pb-1">
                        <h5><b>All Dashboards List</b></h5>
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
                                data-bs-target="#dashboard-add-modal">
                                <i class="mdi mdi-plus" style="margin-right: 10px"></i>
                                <span class="d-none d-sm-inline"> Add Dashboard</span>
                                <span class="d-inline d-sm-none"> Add</span>
                            </button>
                        </div>
                    </div>

                    <div class="mb-3">
                        <p class="text-justify">
                            Click the checkbox on The “Display” column to display dashboard on the public page. You can only
                            display <b>maximum 6</b> dashboards.
                        </p>
                    </div>

                    <!-- Table -->
                    <dashboard-list :data='{{ json_encode($dashboards->items()) }}'>
                        <div class="align-self-center">
                            Showing {{ $dashboards->firstItem() }} to {{ $dashboards->lastItem() }} out of
                            {{ $dashboards->total() }} results
                        </div>
                            {{ $dashboards->OnEachSide(1)->links() }}
                    </dashboard-list>
                </div>
            </div>
            <dashboard-add-modal></dashboard-add-modal>
        </div>
    </div>
@endsection
