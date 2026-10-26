@extends('layouts.admin.app')

@section('content')

<div class="max-w-5xl mx-auto space-y-6">

<x-admin.reference.page-header
    title="Edit State / Division"
    description="Update administrative division information."
    icon="fa-solid fa-map"
    :back-route="route('admin.states.index')"
/>

    <div class="card bg-base-100 border border-base-300 shadow-sm">
        <div class="card-body">

            <form
                method="POST"
                action="{{ route('admin.states.update', $state) }}"
                class="space-y-6"
            >
                @csrf
                @method('PUT')

                @include('admin.reference.states._form')

                <div class="flex justify-end gap-3 pt-4 border-t border-base-300">
                    <a
                        href="{{ route('admin.states.index') }}"
                        class="btn btn-ghost"
                    >
                        Cancel
                    </a>

                    <button type="submit" class="btn btn-primary">
                        <i class="fa-solid fa-floppy-disk"></i>
                        Save Changes
                    </button>
                </div>
            </form>

        </div>
    </div>

</div>

@endsection