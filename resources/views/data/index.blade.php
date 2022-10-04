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
                    <form class="d-flex justify-content-between w-100" method="GET" action="{{ route('data.index') }}">
                        {{-- Filters --}}
                        <div class="dropdown">
                            <button class="btn btn-outline-primary dropdown-toggle" type="button"
                                data-bs-auto-close="false" aria-expanded="false" id="dropdownMenuButton1"
                                data-bs-toggle="dropdown">
                                <i class="mdi mdi-filter-variant"></i> Filter
                            </button>
                            <div class="dropdown-menu p-4" style="width: 300px">
                                <div class="mb-3">
                                    <label for="" class="form-label fw-bold">Filter Category</label>
                                    <data-category-selector
                                    value='@json(request()->categories)' :init-selected='@json($categories)'>
                                        <template v-slot="prop">
                                            <input
                                            v-for="item in prop.selected"
                                            type="hidden"
                                            name="categories[]"
                                            :value="item.name">
                                        </template>
                                    </data-category-selector>
                                    <span class="text-muted text-small">You can select multiple category</span>
                                </div>
                                <div class="mb-3">
                                    <label for="" class="form-label fw-bold">Filter Assigned User</label>
                                    <data-user-selector value="{{request()->user}}" :init-selected='@json($user)'>
                                        <template v-slot="{selected}">
                                            <input v-if="selected"
                                            type="hidden"
                                            name="user"
                                            :value="selected.id">
                                        </template>
                                    </data-user-selector>
                                </div>
                                <div class="d-flex w-100 gap-2">
                                    <a href="{{ route('data.index') }}" class="btn btn-outline-danger w-50">Reset</a>
                                    <button type="submit" class="btn btn-primary w-50">Apply</button>
                                </div>
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
                        <data-add-modal> </data-add-modal>
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
