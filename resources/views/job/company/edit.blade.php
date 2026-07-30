@extends('layouts.app')

@section('title', 'Edit Company')

@section('content')

@include('job.company.partials.form', [
    'company' => $company
])

@endsection