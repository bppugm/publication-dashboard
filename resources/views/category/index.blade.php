@extends('layouts.app')

@section('content')
<div class="container h-100">
    <category-list :data='{{ json_encode($categories) }}'></category-list>
</div>
@endsection
