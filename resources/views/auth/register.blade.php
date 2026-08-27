@extends('layouts.app')

@section('title', 'CareerVault | Register')

@section('content')
<section class="h-full bg-base-200 flex items-center justify-center sm:p-6">
    <div class="w-full max-w-6xl bg-base-100 sm:rounded-3xl sm:shadow-2xl overflow-hidden grid lg:grid-cols-2">
        <!-- Left Side -->
        <div class="hidden lg:flex flex-col justify-center bg-gradient-to-br from-primary to-secondary text-white p-16">
            <h1 class="text-5xl font-bold mb-6"> CareerVault </h1>
            <p class="text-lg opacity-90 leading-8">Create your free account and start your journey toward your dream career. Manage applications, build your profile, and connect with employers.</p>

            <div class="mt-12 space-y-5">
                <div class="flex items-center gap-3">
                    <div class="badge badge-success badge-lg"></div>
                    Unlimited Job Applications
                </div>
                <div class="flex items-center gap-3">
                    <div class="badge badge-success badge-lg"></div>
                    Resume Builder
                </div>
                <div class="flex items-center gap-3">
                    <div class="badge badge-success badge-lg"></div>
                    Recruiter Connections
                </div>
            </div>
        </div>

        <!-- Right Side -->
        <div class="p-10 lg:p-16">
            <div class="max-w-md mx-auto">
                <div class="text-center mb-8">
                    <h2 class="text-4xl font-bold"> Create Account </h2>
                    <p class="text-base-content/60 mt-2"> Join CareerVault and get started today. </p>
                </div>

                <form action="{{ route('register') }}" method="POST">
                    @csrf
                    <!-- Name -->
                    <div class="form-control mb-5">
                        <label class="label"> <span class="label-text font-semibold"> Full Name </span> </label>
                        <input type="text" name="name" value="{{ old('name') }}" placeholder="John Doe" class="input input-bordered w-full @error('name') input-error @enderror">

                        @error('name')
                            <span class="text-error text-sm mt-1">
                                {{ $message }}
                            </span>
                        @enderror
                    </div>

                    <!-- Email -->
                    <div class="form-control mb-5">
                        <label class="label"> <span class="label-text font-semibold"> Email </span> </label>
                        <input type="email" name="email" value="{{ old('email') }}" placeholder="john@example.com" class="input input-bordered w-full @error('email') input-error @enderror">

                        @error('email')
                            <span class="text-error text-sm mt-1">
                                {{ $message }}
                            </span>
                        @enderror
                    </div>

                    <!-- Password -->
                    <div class="form-control mb-5">
                        <label class="label"> <span class="label-text font-semibold"> Password </span> </label>
                        <div class="relative">
                            <input id="registerPassword" type="password" name="password" placeholder="••••••••" class="w-full border rounded-lg px-4 py-2 pr-12" >
                            <button type="button" data-password-toggle="#registerPassword" class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-500 hover:text-blue-600" >
                                <i class="fa-solid fa-eye"></i>
                            </button>
                        </div>

                        @error('password')
                            <span class="text-error text-sm mt-1">
                                {{ $message }}
                            </span>
                        @enderror
                    </div>

                    <!-- Confirm Password -->
                    <div class="form-control mb-8">
                        <label class="label"> <span class="label-text font-semibold"> Confirm Password </span> </label>
                        <div class="relative">
                            <input id="confirmPassword" type="password" name="password_confirmation" placeholder="••••••••" class="w-full border rounded-lg px-4 py-2 pr-12">
                            <button type="button" data-password-toggle="#confirmPassword" class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-500 hover:text-blue-600">
                                <i class="fa-solid fa-eye"></i>
                            </button>
                        </div>
                    </div>

                    <button class="btn btn-primary w-full"> Create Account </button>
                </form>

                <div class="divider my-8"> OR </div>
                <button class="btn btn-outline w-full"> Continue with Google </button>

                <p class="text-center mt-8">
                    Already have an account? <a href="{{ route('login') }}" class="link link-primary font-semibold"> Login </a>
                </p>
            </div>
        </div>
    </div>
</section>
@endsection