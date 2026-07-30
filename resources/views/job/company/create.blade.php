@extends('layouts.app')

@section('title', 'Create Company')

@section('content')

@include('job.company.partials.form', [
    'company' => null
])

@endsection