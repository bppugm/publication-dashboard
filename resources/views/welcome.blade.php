@extends('layouts.app')

<head>
    {{-- TITLE --}}
    <title>Under Construction</title>
</head>

@section('content')
<div class="container">
    <div class="d-flex justify-content-center align-items-center" style="min-height: 70vh">
        <div class="text-center py-auto">
            <img src="/images/uc.png" class="img-fluid">
            <div class="text-primary py-4" style="font-family: Helvetica">
                <h2 class="text-bold"> Site is Under Construction</h2>
                <p class="text-small"> Please check back in sometimes</p>
            </div>
        </div>
    </div>
</div>
@endsection
