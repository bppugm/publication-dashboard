@extends('layouts.app')

@section('content')
    <div class="container h-100">
        <div>
            <div class="d-flex justify-content-between mb-3">
                <h5 class="text-primary card-title mb-0">
                    <b>USER</b>
                </h5>
                <div class="text-dark"><b>User List</b></div>
            </div>
            <div class="card border-0 shadow-sm p-3">
                <div id="users" role="users-list" aria-labelledby="User-list">
                    <div class="card-header text-primary bg-transparent">
                        <h5>User List</h5>
                    </div>
                    <!-- Heading Table -->
                    <div class="d-flex my-3 justify-content-between">
                        <!-- Search bar -->
                        <div class="w-50 w-md-75">
                            <form method="GET" action="{{ route('user.index') }}">
                                <div class="input-group">
                                    <input type="text" name="search" placeholder="Search name or email"
                                        class="form-control" value="{{ request('search') }}">

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
                        </div>
                        <!-- Add User -->
                        <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                            data-bs-target="#user-form-modal">
                            <i class="mdi mdi-plus"></i> Add User
                        </button>
                    </div>
                    <!-- Table -->
                    <user-list :data='{{ json_encode($users->items()) }}'>
                        {{ $users->links() }}
                    </user-list>
                </div>
            </div>
            <user-form-modal></user-form-modal>
        </div>
    </div>
@endsection
