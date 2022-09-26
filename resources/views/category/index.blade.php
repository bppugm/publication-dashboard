@extends('layouts.app')

@section('content')
    <div class="container h-100">
        {{-- Header title --}}
        <div class="d-flex justify-content-between my-30">
            <h5 class="text-primary card-title mb-0">
                <b>CATEGORY</b>
            </h5>
            <nav class="breadcrumb m-0">
                <a class="breadcrumb-item active" aria-current="category" style="text-decoration: none"><b>Category List</b></a>
            </nav>
        </div>
        <div class="card border-0 shadow-sm p-30">
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
                            Category List
                        </button>
                    </a>
                </li>
            </ul>
            <div class="tab-content">
                <div id="category" class="tab-pane fade show active" role="tabpanel" aria-labelledby="category-tab">
                    <!-- Heading Table -->
                    <div class="d-flex my-3 justify-content-between">
                        <!-- Search bar -->
                        <form method="GET" class="d-flex justify-content-between w-100" action="{{ route('category.index') }}">
                            <div class="input-group me-3 w-100">
                                <input type="text" name="search" placeholder="Search Category"
                                    class="form-control search-input" value="{{ request('search') }}">

                                @if (request('search'))
                                    <a href="{{ route('category.index') }}">
                                        <span class="input-group-btn">
                                            <button type="button" class="btn btn-search-append" title="Reset Search">
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
                        <!-- Add Category -->
                        <div class="flex-shrink-0">
                            <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                                data-bs-target="#category-add-modal">
                                <i class="mdi mdi-plus d-none d-sm-inline" style="margin-right: 10px"></i>
                                <span class="d-none d-sm-inline"> Add Category</span>
                                <span class="d-inline d-sm-none"> Add</span>
                            </button>
                        </div>
                        <category-add-modal></category-add-modal>
                    </div>
                    <!-- Table -->
                    <category-list :data='{{ json_encode($categories->items()) }}'>
                        <div class="align-self-center">
                            Showing {{ $categories->firstItem() }} to {{ $categories->lastItem() }} of {{ $categories->total() }} results
                        </div>
                        {{ $categories->OnEachSide(0)->links() }}
                    </category-list>
                </div>
            </div>
        </div>
    </div>
@endsection
