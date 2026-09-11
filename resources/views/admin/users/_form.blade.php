<div class="space-y-6">

    {{-- Account Information --}}
    <div class="rounded-2xl border border-base-300 bg-base-100">

        <div class="border-b border-base-300 px-5 py-4">
            <div class="cv-admin-label text-primary">
                Account
            </div>

            <h2 class="mt-1 text-lg font-bold">
                Account information
            </h2>

            <p class="mt-1 text-sm text-base-content/50">
                Basic information used to identify and access this account.
            </p>
        </div>

        <div class="grid gap-5 p-5 md:grid-cols-2">

            {{-- Name --}}
            <div class="form-control md:col-span-2">

                <label class="label px-0">
                    <span class="label-text font-semibold">
                        Full Name
                    </span>
                </label>

                <input
                    type="text"
                    name="name"
                    value="{{ old('name',$user->name ?? '') }}"
                    placeholder="John Doe"
                    class="input input-bordered w-full @error('name') input-error @enderror"
                    required
                >

                @error('name')
                    <span class="mt-1 text-sm text-error">
                        {{ $message }}
                    </span>
                @enderror

            </div>

            {{-- Email --}}
            <div class="form-control">

                <label class="label px-0">
                    <span class="label-text font-semibold">
                        Email Address
                    </span>
                </label>

                <input
                    type="email"
                    name="email"
                    value="{{ old('email',$user->email ?? '') }}"
                    placeholder="john@example.com"
                    class="input input-bordered w-full @error('email') input-error @enderror"
                    required
                >

                @error('email')
                    <span class="mt-1 text-sm text-error">
                        {{ $message }}
                    </span>
                @enderror

            </div>

            {{-- Status --}}
            <div class="form-control">

                <label class="label px-0">
                    <span class="label-text font-semibold">
                        Account Status
                    </span>
                </label>

                <select
                    name="status"
                    class="select select-bordered w-full @error('status') select-error @enderror"
                    required
                >
                    <option value="active" @selected(old('status',$user->status ?? 'active')==='active')>
                        Active
                    </option>

                    <option value="inactive" @selected(old('status',$user->status ?? '')==='inactive')>
                        Inactive
                    </option>
                </select>

                @error('status')
                    <span class="mt-1 text-sm text-error">
                        {{ $message }}
                    </span>
                @enderror

            </div>

        </div>
    </div>

    {{-- Access & Security --}}
    <div class="rounded-2xl border border-base-300 bg-base-100">

        <div class="border-b border-base-300 px-5 py-4">
            <div class="cv-admin-label text-warning">
                Security
            </div>

            <h2 class="mt-1 text-lg font-bold">
                Access & permissions
            </h2>

            <p class="mt-1 text-sm text-base-content/50">
                Control the account's administrative access.
            </p>
        </div>

        <div class="grid gap-5 p-5 md:grid-cols-2">

            {{-- Role --}}
            <div class="form-control">

                <label class="label px-0">
                    <span class="label-text font-semibold">
                        Role
                    </span>
                </label>

                @if(auth()->user()->isSuperAdmin())

                    <select
                        name="role"
                        class="select select-bordered w-full @error('role') select-error @enderror"
                        required
                    >
                        <option value="user" @selected(old('role',$user->role ?? 'user')==='user')>
                            User
                        </option>

                        <option value="admin" @selected(old('role',$user->role ?? '')==='admin')>
                            Admin
                        </option>

                        <option value="super_admin" @selected(old('role',$user->role ?? '')==='super_admin')>
                            Super Admin
                        </option>
                    </select>

                @else

                    <input
                        type="hidden"
                        name="role"
                        value="user"
                    >

                    <select
                        class="select select-bordered w-full"
                        disabled
                    >
                        <option selected>
                            User
                        </option>
                    </select>

                @endif

                @error('role')
                    <span class="mt-1 text-sm text-error">
                        {{ $message }}
                    </span>
                @enderror

                @if(!auth()->user()->isSuperAdmin())
                    <span class="mt-1 text-xs text-base-content/40">
                        Only a super administrator can assign administrative roles.
                    </span>
                @endif

            </div>

            {{-- Password --}}
            <div class="form-control">

                <label class="label px-0">
                    <span class="label-text font-semibold">
                        Password

                        @isset($user)
                            <span class="font-normal text-base-content/40">
                                (optional)
                            </span>
                        @endisset
                    </span>
                </label>

                <div class="relative">

                    <input
                        id="userPassword"
                        type="password"
                        name="password"
                        placeholder="{{ isset($user) ? 'Leave blank to keep current' : 'Create a password' }}"
                        class="input input-bordered w-full pr-12 @error('password') input-error @enderror"
                        {{ isset($user) ? '' : 'required' }}
                    >

                    <button
                        type="button"
                        onclick="toggleUserPassword('userPassword',this)"
                        class="absolute right-3 top-1/2 -translate-y-1/2 text-base-content/40 hover:text-primary"
                        tabindex="-1"
                    >
                        <i class="fa-solid fa-eye"></i>
                    </button>

                </div>

                @error('password')
                    <span class="mt-1 text-sm text-error">
                        {{ $message }}
                    </span>
                @enderror

                <span class="mt-1 text-xs text-base-content/40">
                    Minimum 8 characters with uppercase, lowercase, number and symbol.
                </span>

            </div>

            {{-- Confirm Password --}}
            <div class="form-control">

                <label class="label px-0">
                    <span class="label-text font-semibold">
                        Confirm Password
                    </span>
                </label>

                <div class="relative">

                    <input
                        id="userPasswordConfirmation"
                        type="password"
                        name="password_confirmation"
                        placeholder="Repeat the password"
                        class="input input-bordered w-full pr-12"
                        {{ isset($user) ? '' : 'required' }}
                    >

                    <button
                        type="button"
                        onclick="toggleUserPassword('userPasswordConfirmation',this)"
                        class="absolute right-3 top-1/2 -translate-y-1/2 text-base-content/40 hover:text-primary"
                        tabindex="-1"
                    >
                        <i class="fa-solid fa-eye"></i>
                    </button>

                </div>

                @error('password_confirmation')
                    <span class="mt-1 text-sm text-error">
                        {{ $message }}
                    </span>
                @enderror

            </div>

        </div>
    </div>

    {{-- Existing account information --}}
    @isset($user)

        <div class="rounded-2xl border border-base-300 bg-base-200/50">

            <div class="grid gap-4 p-5 sm:grid-cols-3">

                <div>
                    <div class="cv-admin-label opacity-40">
                        User ID
                    </div>

                    <div class="cv-admin-mono mt-1 text-sm">
                        #{{ $user->id }}
                    </div>
                </div>

                <div>
                    <div class="cv-admin-label opacity-40">
                        Created
                    </div>

                    <div class="mt-1 text-sm">
                        {{ $user->created_at->format('d M Y, h:i A') }}
                    </div>
                </div>

                <div>
                    <div class="cv-admin-label opacity-40">
                        Last Login
                    </div>

                    <div class="mt-1 text-sm">
                        {{ $user->last_login_at?->format('d M Y, h:i A') ?? 'Never' }}
                    </div>
                </div>

            </div>

        </div>

    @endisset

</div>

@push('scripts')
<script>
function toggleUserPassword(id,button){
    const input=document.getElementById(id);
    const icon=button.querySelector('i');

    if(input.type==='password'){
        input.type='text';
        icon.classList.remove('fa-eye');
        icon.classList.add('fa-eye-slash');
    }else{
        input.type='password';
        icon.classList.remove('fa-eye-slash');
        icon.classList.add('fa-eye');
    }
}
</script>
@endpush