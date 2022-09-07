@extends('layouts.app')

<head>
    {{-- TITLE --}}
    <title>Under Construction</title>
    <style>
        .logotitle {
            position: absolute;
            top: 60%;
            left: 50%;
        }
        .logotitle>img {
            -o-object-fit: cover;
            object-fit: cover;
        }
        .logotitle h1 {
            color: #fff;
            letter-spacing: 3px;
            font-family: "Gama Serif", Georgia, "Times New Roman", Times, serif;
        }
        .img-blur {
            filter: blur(2px);
            -webkit-filter: blur(2px);
        }
    </style>
</head>

@section('content')
    <div class="row overflow-hidden" style="max-height:80vh">
        <img src="/images/gedung-pusat-night.jpeg" class="img-blur" alt="...">
        <div class="logotitle translate-middle text-center">
            <img src="/images/hero_logo.png" class="position-relative">
            <h1 class="d-none d-sm-block mt-2">BADAN PENERBIT DAN PUBLIKASI</h1>
            <h1>UNIVERSITAS GADJAH MADA</h1>
            <div class="flex">
                <h3 class="text-bg-light text-muted text-uppercase text-center">
                    503 | Site is Under Construction
                </h3>
            </div>
        </div>
    </div>
@endsection
