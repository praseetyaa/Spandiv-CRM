@extends('layouts.app')
@section('title', 'Edit Perusahaan')
@section('content')
    @include('companies.create', ['company' => $company])
@endsection