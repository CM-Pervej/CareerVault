@extends('layouts.app')

@section('title', 'CareerVault | Login')

@section('content')
<div class="h-full bg-base-200 flex items-center justify-center sm:p-6">
    <div class="w-full max-w-6xl bg-base-100 sm:rounded-3xl sm:shadow-2xl overflow-hidden grid lg:grid-cols-2">
        <!-- Left Side -->
        <div class="hidden lg:flex flex-col justify-center bg-gradient-to-br from-primary to-secondary text-white p-16">
            <h1 class="text-5xl font-bold mb-6">CareerVault</h1>
            <p class="text-lg opacity-90 leading-8">Find your dream job, manage applications, connect with recruiters and grow your career with one modern platform.</p>

            <div class="mt-12 space-y-5">
                <div class="flex items-center gap-3">
                    <div class="badge badge-success badge-lg"></div> AI Powered Resume
                </div>
                <div class="flex items-center gap-3">
                    <div class="badge badge-success badge-lg"></div> Job Tracking
                </div>
                <div class="flex items-center gap-3">
                    <div class="badge badge-success badge-lg"></div> Company Dashboard
                </div>
            </div>
        </div>

        <!-- Right Side -->
        <div class="p-10 lg:p-16">
            <div class="max-w-md mx-auto">
                <div class="text-center mb-8">
                    <h2 class="text-4xl font-bold"> Welcome Back </h2>
                    <p class="text-base-content/60 mt-2"> Login to continue to your dashboard. </p>
                </div>

                @if(session('success'))
                    <div class="alert alert-success mb-5"> {{ session('success') }} </div>
                @endif

                <form action="{{ route('login') }}" method="POST">
                    @csrf
                    <div class="form-control mb-5">
                        <label class="label"> <span class="label-text font-semibold"> Email </span> </label>

                        {{-- <input type="email" name="email" value="{{ old('email') }}" placeholder="example@email.com" class="input input-bordered w-full @error('email') input-error @enderror"> --}}
                        <input type="email" name="email" value="pervejbd2029@gmail.com" placeholder="example@email.com" class="input input-bordered w-full @error('email') input-error @enderror">

                        @error('email')
                            <span class="text-error text-sm mt-1"> {{ $message }} </span>
                        @enderror
                    </div>

                    <div class="form-control mb-3">
                        <label class="label"> <span class="label-text font-semibold"> Password </span> </label>

                        <div class="relative">
                            {{-- <input id="loginPassword" type="password" placeholder="••••••••" name="password" class="w-full border rounded-lg px-4 py-2 pr-12 @error('password') border-red-500 @enderror" > --}}
                            <input id="loginPassword" type="password" value="CMp-190126" name="password" class="w-full border rounded-lg px-4 py-2 pr-12 @error('password') border-red-500 @enderror" >

                            <button type="button" data-password-toggle="#loginPassword" class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-500 hover:text-blue-600" >
                                <i class="fa-solid fa-eye"></i>
                            </button>
                        </div>

                        @error('password')
                            <span class="text-error text-sm mt-1"> {{ $message }} </span>
                        @enderror
                    </div>

                    <div class="flex justify-between items-center mb-8">
                        <label class="label cursor-pointer gap-2">
                            <input type="checkbox" name="remember" class="checkbox checkbox-primary">
                            <span class="label-text"> Remember Me </span>
                        </label>
                        <a href="#" class="link link-primary"> Forgot Password? </a>
                    </div>

                    <button class="btn btn-primary w-full"> Login </button>
                </form>

                <div class="divider my-8"> OR </div>
                <button class="btn btn-outline w-full mb-3"> Continue with Google </button>
                <p class="text-center mt-8">
                    Don't have an account? <a href="{{ route('register') }}" class="link link-primary font-semibold"> Register </a>
                </p>
            </div>
        </div>
    </div>
</div>
@endsection