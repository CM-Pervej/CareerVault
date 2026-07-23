@extends('layouts.app')

@section('title', 'Company Career Sites')

@section('content')

<h1>Company Career Sites</h1>

<a href="{{ route('careers.create') }}">Add Company</a>

<table border="1" cellpadding="10">
    <tr>
        <th>Name</th>
        <th>Industry</th>
        <th>Country</th>
        <th>Career Page</th>
        <th>Action</th>
    </tr>

    @foreach ($careers as $career)
        <tr>
            <td>{{ $career->name }}</td>
            <td>{{ $career->industry }}</td>
            <td>{{ $career->country }}</td>
            <td>
                <a href="{{ $career->career }}" target="_blank">
                    Visit
                </a>
            </td>
            <td>
                <a href="{{ route('careers.edit', $career) }}">Edit</a>

                <form action="{{ route('careers.destroy', $career) }}"
                      method="POST"
                      style="display:inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit">Delete</button>
                </form>
            </td>
        </tr>
    @endforeach
</table>

{{ $careers->links() }}

@endsection