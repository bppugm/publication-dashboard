@extends('layouts.app')

@section('content')
<div class="container container-fluid">
    {{-- HEADER --}}
    <div class="d-flex justify-content-between my-30">
        <h5 class="text-primary card-title mb-0">
            <b>Data</b>
        </h5>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('data.index') }}">Data List</a></li>
                <li class="breadcrumb-item active" aria-current="page">Data Detail</li>
            </ol>
        </nav>
    </div>
    {{-- END HEADER --}}

    <div class="card border-0 shadow-sm p-30 mb-5">

        <data-show-info class="mb-3" :initial-data='@json($data)'></data-show-info>

        {{-- ACTIVITY LOGS TABLE --}}
        <h5 class="text-primary">Activity Logs</h5>
        <div class="table-responsive">
            <table class="table table-bordered">
                <thead class="table-light">
                    <tr>
                        <th scope="col">Timestamp</th>
                        <th scope="col">Causer</th>
                        <th scope="col">Before</th>
                        <th scope="col">After</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($activities as $activity)
                    <tr>
                        <td>
                            <date-formatter iso-date="{{ $activity->created_at->toIsoString() }}"></date-formatter>
                        </td>
                        <td>{{ $activity->causer->name }}</td>
                        <td>
                            @foreach ($activity->properties['old'] as $key => $value)
                            <div>
                                {{ $key }}: {{ $value }}
                            </div>
                            @endforeach
                        </td>
                        <td>
                            @foreach ($activity->properties['attributes'] as $key => $value)
                            <div>
                                {{ $key }}: {{ $value }}
                            </div>
                            @endforeach
                        </td>
                    </tr>
                    @empty
                    <div>
                        <td colspan="4" class="text-center">
                            No activities found
                        </td>
                    </div>
                    @endforelse
                </tbody>
            </table>
        </div>
        {{-- END TABLE --}}
        <div>
            <div class="align-self-center">
                Showing {{ $activities->firstItem() }} to {{ $activities->lastItem() }} of {{ $activities->total() }} results
            </div>
            {{ $activities->OnEachSide(1)->links() }}
        </div>
    </div>
</div>
@endsection
