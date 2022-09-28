@extends('layouts.app')

@section('content')
<div class="container container-fluid">
    {{-- Header Title --}}
    <div class="d-flex justify-content-between my-30">
        <h5 class="text-primary card-title mb-0">
            <b>DATA</b>
        </h5>
        <nav class="breadcrumb m-0">
            <a class="breadcrumb-item active" aria-current="data" style="text-decoration: none"><b>Data List</b></a>
        </nav>
    </div>
    {{-- Card --}}
    <div class="card border-0 shadow-sm p-30">
        {{-- Tabs Data and Category --}}
        <ul class="nav nav-tabs" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-tabs nav-link active border-0" id="data-tab" data-bs-toggle="tab"
                    data-bs-target="#data" type="button" role="tab" aria-controls="data" aria-selected="true">
                    Data List
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <a href="/category" class="text-decoration-none">
                    <button class="nav-tabs nav-link disabled" id="category-tab" data-bs-toggle="tab"
                        data-bs-target="#category" type="button" role="tab" aria-controls="category"
                        aria-selected="true">
                        Category List
                    </button>
                </a>
            </li>
        </ul>
        {{-- Tab Content --}}
        <div class="tab-content">
            <div id="data" class="tab-pane fade show active" role="tabpanel" aria-labelledby="data-tab">
                <div class="d-flex my-3 justify-content-between">
                    {{-- Search --}}
                    <form class="d-flex justify-content-between w-100" method="GET"
                        action="{{ route('data.index') }}">
                        {{-- Filters --}}
                        <div class="flex-shrink-0 dropdown">
                            <button class="btn btn-outline-primary dropdown-toggle"
                            type="button" id="dropdownMenuButton1"
                            data-bs-toggle="dropdown">
                                <i class="mdi mdi-filter-variant"></i> Filter
                            </button>
                            {{-- form dropdownmenu filter category --}}
                            <div class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
                                    {{-- Category Filter --}}
                                    <p> Category Filter </p>
                                    {{-- selectpicker category --}}
                                    <select class="selectpicker" multiple data-live-search="true" name="category[]">
                                        @foreach ($categories as $category)
                                        <option value="{{ $category->name }}">{{ $category->name }}</option>
                                        @endforeach
                                    </select>

                                    {{-- Assigned User Filter --}}
                                    <p> Assigned User Filter </p>
                                    {{-- selectpicker assigned user --}}

                                    {{-- button filter --}}
                                    <button type="submit" class="btn btn-primary mt-3">Filter</button>
                            </div>
                        </div>
                        <div class="input-group mx-3 w-100">
                            <input type="text" name="search" placeholder="Search Data" class="form-control search-input"
                                value="{{ request('search') }}">

                            @if (request('search'))
                            <a href="{{ route('data.index') }}">
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
                    <!-- Add Data -->
                    <div class="flex-shrink-0">
                        <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                            data-bs-target="#data-add-modal">
                            <i class="mdi mdi-plus d-none d-sm-inline" style="margin-right: 10px"></i>
                            <span class="d-none d-sm-inline"> Add Data</span>
                            <span class="d-inline d-sm-none"> Add</span>
                        </button>
                        <data-add-modal></data-add-modal>
                    </div>
                </div>
                <data-list :data='{{ json_encode($data->items()) }}'>
                    <div class="align-self-center">
                        Showing {{ $data->firstItem() }} to {{ $data->lastItem() }} of {{ $data->total() }} results
                    </div>
                    {{ $data->OnEachSide(1)->links() }}
                </data-list>
            </div>
        </div>
    </div>
</div>
@endsection
