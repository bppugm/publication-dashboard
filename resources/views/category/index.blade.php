@extends('layouts.app')

@section('content')
    <div class="container h-100">
        <div class="d-flex justify-content-between mb-3">
            <h5 class="text-primary card-title mb-0">
                <b>DATA</b>
            </h5>
            <div class="text-dark"><b>Category List</b></div>
        </div>
        <div class="card border-0 shadow-sm p-3">
            <ul class="nav nav-tabs" role="tablist">
                <li class="nav-item" role="presentation">
                    <a href="/data" class="text-decoration-none">
                        <button class="nav-link disabled"
                        id="category-tab"
                        data-bs-toggle="tab"
                        data-bs-target="#category"
                        type="button"
                        role="tab"
                        aria-controls="category"
                        aria-selected="true">
                            Data List
                        </button>
                    </a>
                </li>
                <li class="nav-item" role="presentation">
                    <a href="/category" class="text-decoration-none">
                        <button class="nav-link nav-tabs active border-0"
                        id="category-tab"
                        data-bs-toggle="tab"
                        data-bs-target="#category"
                        type="button" role="tab"
                        aria-controls="category"
                        aria-selected="true">
                            Category
                        </button>
                    </a>
                </li>
            </ul>
            <div class="tab-content">
                <div id="category" class="tab-pane fade show active" role="tabpanel" aria-labelledby="category-tab">
                    <!-- Heading Table -->
                    <div class="d-flex my-3 justify-content-between">
                        <!-- Search bar -->
                        <div class="w-50 w-md-75">
                            <form method="GET" action="{{ route('category.index') }}">
                                <div class="input-group">
                                    <input type="text" name="search" placeholder="Search name or email"
                                        class="form-control" value="{{ request('search') }}">

                                    @if (request('search'))
                                        <a href="{{ route('category.index') }}">
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
                        <!-- Add Category -->
                        <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                            data-bs-target="#category-form-modal">
                            <i class="mdi mdi-plus"></i> Add Category
                        </button>
                    </div>
                    <!-- Table -->
                    <category-list :data='{{ json_encode($categories->items()) }}'>
                        {{ $categories->links() }}
                    </category-list>
                </div>
            </div>
        </div>
        <category-form-modal></category-form-modal>
    </div>
@endsection
