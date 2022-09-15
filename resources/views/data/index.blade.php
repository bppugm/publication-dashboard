@extends('layouts.app')

@section('content')
<div class="container h-100">
    <data-list :data='{{ json_encode($data) }}'></data-list>
</div>
@endsection
