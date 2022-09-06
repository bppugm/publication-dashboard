@extends('layouts.app')

<head>
    <!-- Styles -->
    <link href="{{ asset('css/uc.css') }}" rel="stylesheet">
    {{-- TITLE --}}
    <title>Under Construction</title>
</head>

@section('content')
    <section class="relative schome-hero--video">
        <div>
            <img src="https://ugm.ac.id/video/2021Shuttle..jpg" alt="">
            <div class="schome-hero__logotitle text-center">
                <img src="https://ugm.ac.id/images/new/hero_logo.png" alt="">
                <h1>UNIVERSITAS GADJAH MADA</h1>
                <h1 class="mt-1 m-auto">BADAN PENERBIT DAN PUBLIKASI</h1>
                <div class="flex text-muted text-uppercase text-center">
                    <br/>
                    <h4>
                        503 | Site is Under Construction
                    </h4>
                </div>
            </div>
        </div>
    </section>
@endsection
