@extends('layouts.app')

@section('content')
<div class="container h-100">
    <user-list :data='{{ json_encode($users) }}'></admin-list>
</div>
@endsection
