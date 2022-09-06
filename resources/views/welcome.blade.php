@extends('layouts.app')

<head>
    <!-- Styles -->
    <link href="{{ asset('css/uc.css') }}" rel="stylesheet">
    {{-- TITLE --}}
    <title>Under Construction</title>
</head>
@section('content')
    <section class="relative schome-hero__page">
        <img src="/images/gedung-pusat-night.jpeg" class="img-fluid img-blur" alt="...">
        <div class="schome-hero__logotitle text-center">
            <img src="/images/hero_logo.png" alt="">
            <h1>BADAN PENERBIT DAN PUBLIKASI</h1>
            <h1 class="mt-1">UNIVERSITAS GADJAH MADA</h1>
            <div class="flex ">
                <br />
                <h3 class="text-bg-light text-muted text-uppercase text-center">
                    503 | Site is Under Construction
                </h3>
            </div>
        </div>
    </section>
@endsection
