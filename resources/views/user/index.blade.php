@extends('layouts.app')

@section('content')
    <div class="container h-100">
        <div class="d-flex justify-content-between my-30">
            <h5 class="text-primary card-title mb-0">
                <b>USER</b>
            </h5>
            <nav class="breadcrumb m-0">
                <a class="breadcrumb-item active" aria-current="category" style="text-decoration: none"><b>User List</b></a>
            </nav>
        </div>
        <div class="card border-0 shadow-sm p-30">
            <div id="users" role="users-list" aria-labelledby="User-list">
                <div class="card-header text-primary bg-transparent p-0 pb-1">
                    <h5>User List</h5>
                </div>
                <!-- Heading Table -->
                <div class="d-flex my-3 justify-content-between">
                    <!-- Search bar -->
                    <form class="d-flex justify-content-between w-100" method="GET" action="{{ route('user.index') }}">
                        <div class="input-group me-3">
                            <input type="text" name="search" placeholder="Search name or email"
                                class="form-control search-input" value="{{ request('search') }}">

                            @if (request('search'))
                            <a href="{{ route('user.index') }}">
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
                    <!-- Add User -->
                    <div class="flex-shrink-0">
                        <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                            data-bs-target="#user-add-modal">
                            <i class="mdi mdi-plus d-none d-sm-inline" style="margin-right: 10px"></i>
                            <span class="d-none d-sm-inline"> Add User</span>
                            <span class="d-inline d-sm-none"> Add</span>
                        </button>
                    </div>
                </div>
                <!-- Table -->
                <user-list :data='{{ json_encode($users->items()) }}'>
                    <div class="align-self-center">
                        Showing {{ $users->firstItem() }} to {{ $users->lastItem() }} of {{ $users->total() }} results
                    </div>
                    {{ $users->OnEachSide(1)->links() }}
                </user-list>
            </div>
        </div>
        <user-add-modal></user-add-modal>
    </div>
@endsection
