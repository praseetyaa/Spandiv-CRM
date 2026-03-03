@extends('layouts.app')
@section('title', 'Edit User')
@section('content')
    @include('users.create', ['user' => $user])
@endsection