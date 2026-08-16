@extends('layouts.app')

@section('title', $company->name)

@section('content')

@include('job.company.partials.form', [
    'company' => $company
])

@endsection