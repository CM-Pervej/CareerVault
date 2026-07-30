@php
    $emails = old(
        'emails',
        $company?->emails ?? [
            ['email_type' => '', 'email' => '',],
        ]
    );

    $emailTypes = ['HR', 'Career', 'Support', 'Office', 'Sales', 'General', 'Other',];
@endphp

<div class="card bg-base-100 shadow-xl border border-base-300/60 rounded-2xl">
    <div class="card-body p-6 lg:p-8">
        {{-- Header --}}
        <div class="flex items-start justify-between gap-4 pb-6 border-b border-base-300/60">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-primary/15 to-primary/5 flex items-center justify-center ring-1 ring-primary/10">
                    <i class="fa-solid fa-envelope text-primary text-lg"></i>
                </div>

                <div>
                    <h2 class="card-title text-base-content text-lg">Email Addresses</h2>
                    <p class="text-sm text-base-content/60 mt-0.5">Add one or more email addresses for this company.</p>
                </div>
            </div>

            <button type="button" id="add-email" class="btn btn-primary btn-sm rounded-lg shadow-sm"> 
                <i class="fa-solid fa-plus text-xs"></i> Add Email 
            </button>
        </div>

        {{-- Items --}}
        <div id="emails-container" class="flex flex-col gap-3">
            @foreach($emails as $index => $email)
                <div class="email-item border border-base-300/70 rounded-xl bg-base-100 hover:border-base-300 hover:shadow-sm transition-all">
                    <div class="flex flex-col lg:flex-row lg:items-end gap-4 p-4">
                        {{-- Order badge --}}
                        <div class="hidden lg:flex items-center justify-center w-7 h-7 rounded-full bg-base-200 text-base-content/50 text-xs font-semibold shrink-0 mb-2.5">
                            {{ $index + 1 }}
                        </div>

                        {{-- Type --}}
                        <div class="form-control w-full lg:w-44 shrink-0">
                            <label class="label pb-1.5"> 
                                <span class="label-text text-xs font-medium text-base-content/70">Type</span> 
                            </label>

                            <select name="emails[{{ $index }}][email_type]" class="select select-bordered select-sm w-full email-type focus:select-primary">
                                <option value="">Select type</option>

                                @foreach($emailTypes as $type)
                                    <option value="{{ $type }}" @selected(($email['email_type'] ?? '') === $type)> {{ $type }} </option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Email --}}
                        <div class="form-control w-full flex-1">
                            <label class="label pb-1.5"> 
                                <span class="label-text text-xs font-medium text-base-content/70">Email Address</span> 
                            </label>

                            <label class="input input-bordered input-sm flex items-center gap-2 w-full focus-within:input-primary">
                                <i class="fa-solid fa-at text-base-content/30 text-xs"></i>
                                <input type="email" name="emails[{{ $index }}][email]" value="{{ $email['email'] ?? '' }}" placeholder="hr@example.com" class="grow email-address">
                            </label>
                        </div>

                        {{-- Remove --}}
                        <div class="flex justify-end lg:justify-center lg:mb-1 shrink-0">
                            <div class="tooltip tooltip-left" data-tip="At least one email is required">
                                <button type="button" class="btn btn-ghost btn-sm btn-square text-base-content/40 hover:bg-error/10 hover:text-error remove-email disabled:opacity-30" @disabled($loop->count == 1)> 
                                    <i class="fa-solid fa-trash text-xs"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="flex justify-between items-center">
            {{-- Footer count --}}
            <div class="flex items-center gap-2 mt-4 text-xs text-base-content/40">
                <i class="fa-solid fa-circle-info text-[10px]"></i>
                <span id="emails-count-label">{{ count($emails) }} {{ Str::plural('email', count($emails)) }} added</span>
            </div>
            <div class="badge badge-ghost badge-sm gap-1.5 hidden sm:flex"> 
                <i class="fa-solid fa-circle-info text-[10px]"></i> Step 3 of 6 
            </div>
        </div>
    </div>
</div>

{{-- Template --}}
<template id="email-template">
    <div class="email-item border border-base-300/70 rounded-xl bg-base-100 hover:border-base-300 hover:shadow-sm transition-all">
        <div class="flex flex-col lg:flex-row lg:items-end gap-4 p-4">
            <div class="hidden lg:flex items-center justify-center w-7 h-7 rounded-full bg-base-200 text-base-content/50 text-xs font-semibold shrink-0 mb-2.5 email-order-badge">
                #
            </div>

            <div class="form-control w-full lg:w-44 shrink-0">
                <label class="label pb-1.5">
                    <span class="label-text text-xs font-medium text-base-content/70">Type</span>
                </label>

                <select class="select select-bordered select-sm w-full email-type focus:select-primary">
                    <option value="">Select type</option>

                    @foreach($emailTypes as $type)
                        <option value="{{ $type }}"> {{ $type }} </option>
                    @endforeach
                </select>
            </div>

            <div class="form-control w-full flex-1">
                <label class="label pb-1.5">
                    <span class="label-text text-xs font-medium text-base-content/70">Email Address</span>
                </label>

                <label class="input input-bordered input-sm flex items-center gap-2 w-full focus-within:input-primary">
                    <i class="fa-solid fa-at text-base-content/30 text-xs"></i>
                    <input type="email" class="grow email-address" placeholder="hr@example.com">
                </label>
            </div>

            <div class="flex justify-end lg:justify-center lg:mb-1 shrink-0">
                <div class="tooltip tooltip-left" data-tip="At least one email is required">
                    <button type="button" class="btn btn-ghost btn-sm btn-square text-base-content/40 hover:bg-error/10 hover:text-error remove-email">
                        <i class="fa-solid fa-trash text-xs"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>