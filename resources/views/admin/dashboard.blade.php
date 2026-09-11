@extends('layouts.admin.app')

@section('title','Admin Dashboard | CareerVault')
@section('page_title','Dashboard')

@section('content')
    <div class="mx-auto max-w-7xl">
        <div class="mb-6">
            <div class="cv-admin-label mb-2 opacity-40">Overview</div>
            <h2 class="cv-admin-title text-3xl sm:text-4xl">Good to see you, {{ auth()->user()->name }}.</h2>
            <p class="mt-2 max-w-2xl text-sm opacity-60">
                Manage CareerVault's platforms, companies, reference data, users, and system information from one place.
            </p>
        </div>

        <div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
            <div class="rounded-2xl border border-base-300 bg-base-100 p-5 shadow-sm">
                <div class="mb-4 flex h-10 w-10 items-center justify-center rounded-xl bg-base-200">
                    <i class="fa-solid fa-layer-group text-sm"></i>
                </div>
                <div class="cv-admin-label opacity-40">Platforms</div>
                <div class="cv-admin-title mt-1 text-3xl">—</div>
                <div class="mt-1 text-xs opacity-50">Platform directory</div>
            </div>

            <div class="rounded-2xl border border-base-300 bg-base-100 p-5 shadow-sm">
                <div class="mb-4 flex h-10 w-10 items-center justify-center rounded-xl bg-base-200">
                    <i class="fa-solid fa-building text-sm"></i>
                </div>
                <div class="cv-admin-label opacity-40">Companies</div>
                <div class="cv-admin-title mt-1 text-3xl">—</div>
                <div class="mt-1 text-xs opacity-50">Company directory</div>
            </div>

            <div class="rounded-2xl border border-base-300 bg-base-100 p-5 shadow-sm">
                <div class="mb-4 flex h-10 w-10 items-center justify-center rounded-xl bg-base-200">
                    <i class="fa-solid fa-users text-sm"></i>
                </div>
                <div class="cv-admin-label opacity-40">Users</div>
                <div class="cv-admin-title mt-1 text-3xl">—</div>
                <div class="mt-1 text-xs opacity-50">Registered accounts</div>
            </div>

            <div class="rounded-2xl border border-base-300 bg-base-100 p-5 shadow-sm">
                <div class="mb-4 flex h-10 w-10 items-center justify-center rounded-xl bg-base-200">
                    <i class="fa-solid fa-clock-rotate-left text-sm"></i>
                </div>
                <div class="cv-admin-label opacity-40">Activity</div>
                <div class="cv-admin-title mt-1 text-3xl">—</div>
                <div class="mt-1 text-xs opacity-50">Recent system activity</div>
            </div>
        </div>

        <div class="mt-6 grid gap-6 lg:grid-cols-3">
            <div class="rounded-2xl border border-base-300 bg-base-100 p-6 shadow-sm lg:col-span-2">
                <div class="flex items-start justify-between gap-4">
                    <div>
                        <div class="cv-admin-label opacity-40">Administration</div>
                        <h3 class="cv-admin-title mt-1 text-xl">Quick management</h3>
                    </div>
                    <i class="fa-solid fa-bolt opacity-30"></i>
                </div>

                <div class="mt-5 grid gap-3 sm:grid-cols-2">
                    <a href="#" class="group rounded-xl border border-base-300 p-4 transition hover:border-primary/40 hover:bg-base-200">
                        <div class="flex items-center justify-between">
                            <span class="font-semibold">Manage Platforms</span>
                            <i class="fa-solid fa-arrow-right text-xs opacity-30 transition group-hover:translate-x-1"></i>
                        </div>
                        <p class="mt-1 text-xs opacity-50">Maintain job platforms and their information.</p>
                    </a>

                    <a href="#" class="group rounded-xl border border-base-300 p-4 transition hover:border-primary/40 hover:bg-base-200">
                        <div class="flex items-center justify-between">
                            <span class="font-semibold">Manage Companies</span>
                            <i class="fa-solid fa-arrow-right text-xs opacity-30 transition group-hover:translate-x-1"></i>
                        </div>
                        <p class="mt-1 text-xs opacity-50">Maintain company and career information.</p>
                    </a>

                    <a href="#" class="group rounded-xl border border-base-300 p-4 transition hover:border-primary/40 hover:bg-base-200">
                        <div class="flex items-center justify-between">
                            <span class="font-semibold">Platform Connections</span>
                            <i class="fa-solid fa-arrow-right text-xs opacity-30 transition group-hover:translate-x-1"></i>
                        </div>
                        <p class="mt-1 text-xs opacity-50">Manage official accounts across platforms.</p>
                    </a>

                    <a href="#" class="group rounded-xl border border-base-300 p-4 transition hover:border-primary/40 hover:bg-base-200">
                        <div class="flex items-center justify-between">
                            <span class="font-semibold">Manage Users</span>
                            <i class="fa-solid fa-arrow-right text-xs opacity-30 transition group-hover:translate-x-1"></i>
                        </div>
                        <p class="mt-1 text-xs opacity-50">Review users and account status.</p>
                    </a>
                </div>
            </div>

            <div class="rounded-2xl border border-base-300 bg-base-100 p-6 shadow-sm">
                <div class="cv-admin-label opacity-40">System</div>
                <h3 class="cv-admin-title mt-1 text-xl">Administrator</h3>

                <div class="mt-5 space-y-4">
                    <div>
                        <div class="text-xs opacity-45">Name</div>
                        <div class="mt-1 text-sm font-semibold">{{ auth()->user()->name }}</div>
                    </div>

                    <div>
                        <div class="text-xs opacity-45">Email</div>
                        <div class="mt-1 break-all text-sm font-semibold">{{ auth()->user()->email }}</div>
                    </div>

                    <div>
                        <div class="text-xs opacity-45">Role</div>
                        <div class="mt-1">
                            <span class="badge badge-neutral badge-sm">
                                {{ str_replace('_',' ',auth()->user()->role) }}
                            </span>
                        </div>
                    </div>

                    <div>
                        <div class="text-xs opacity-45">Status</div>
                        <div class="mt-1">
                            <span class="badge badge-success badge-sm gap-1">
                                <span class="h-1.5 w-1.5 rounded-full bg-current"></span>
                                {{ ucfirst(auth()->user()->status) }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection