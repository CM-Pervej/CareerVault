{{-- Email Directory --}}
<div class="card bg-base-100 shadow-sm border border-base-300">
    <div class="card-body">
        <div class="flex items-center justify-between mb-3 gap-2">
            <div class="flex items-center gap-2 min-w-0">
                <div class="w-8 h-8 rounded-lg bg-primary/10 text-primary flex items-center justify-center shrink-0">
                    <i class="fa-solid fa-envelope text-sm"></i>
                </div>
                <h2 class="text-lg font-semibold truncate">Email Directory</h2>
            </div>
            <div class="flex items-center gap-1.5 shrink-0">
                @if(!empty($company->emails))
                    <span class="badge badge-ghost badge-sm font-medium">{{ count($company->emails) }}</span>
                    <button type="button" class="btn btn-ghost btn-xs no-print" data-action="copy-all-emails" title="Copy all emails">
                        <i class="fa-solid fa-copy text-xs"></i> <span class="hidden sm:inline">Copy all</span>
                    </button>
                @endif
            </div>
        </div>

        @if(!empty($company->emails))
            <div class="space-y-1.5" id="cv-email-rows">
                @foreach($company->emails as $email)
                @php $isDuplicate = $duplicateEmails->contains(strtolower(trim($email['email']))); @endphp
                <div class="cv-filter-row flex items-center justify-between gap-2 rounded-lg px-2.5 py-2 hover:bg-base-200/60 transition-colors {{ $isDuplicate ? 'ring-1 ring-warning/50 bg-warning/5' : '' }}"
                        data-search="{{ strtolower($email['email'].' '.$email['email_type']) }}">
                    <div class="min-w-0 flex items-center gap-2">
                        <span class="badge {{ $colorFor($email['email_type']) }} badge-outline badge-sm capitalize shrink-0">{{ $email['email_type'] }}</span>
                        <a href="mailto:{{ $email['email'] }}" class="link link-primary no-underline hover:underline font-mono text-sm truncate">
                            {{ $email['email'] }}
                        </a>
                        @if($isDuplicate)
                            <span class="tooltip" data-tip="Appears more than once">
                                <i class="fa-solid fa-triangle-exclamation text-warning text-xs"></i>
                            </span>
                        @endif
                    </div>
                    <button type="button" class="copy-btn btn btn-ghost btn-xs btn-circle shrink-0 no-print" data-copy="{{ $email['email'] }}" title="Copy email" aria-label="Copy email address">
                        <i class="fa-solid fa-copy text-xs"></i>
                    </button>
                </div>
                @endforeach
            </div>
        @else
            <div class="flex flex-col items-center justify-center text-center py-8 text-base-content/40">
                <i class="fa-solid fa-envelope-open text-2xl mb-2"></i>
                <p class="text-sm">No emails on file</p>
            </div>
        @endif
    </div>
</div>

{{-- Phone Directory --}}
<div class="card bg-base-100 shadow-sm border border-base-300 -mt-5 sm:mt-0">
    <div class="card-body">
        <div class="flex items-center justify-between mb-3 gap-2">
            <div class="flex items-center gap-2 min-w-0">
                <div class="w-8 h-8 rounded-lg bg-primary/10 text-primary flex items-center justify-center shrink-0">
                    <i class="fa-solid fa-phone text-sm"></i>
                </div>
                <h2 class="text-lg font-semibold truncate">Phone Directory</h2>
            </div>
            <div class="flex items-center gap-1.5 shrink-0">
                @if(!empty($company->phones))
                    <span class="badge badge-ghost badge-sm font-medium">{{ count($company->phones) }}</span>
                    <button type="button" class="btn btn-ghost btn-xs no-print" data-action="copy-all-phones" title="Copy all phone numbers">
                        <i class="fa-solid fa-copy text-xs"></i> <span class="hidden sm:inline">Copy all</span>
                    </button>
                @endif
            </div>
        </div>

        @if(!empty($company->phones))
            <div class="space-y-1.5" id="cv-phone-rows">
                @foreach($company->phones as $phone)
                @php $isDuplicate = $duplicatePhones->contains(preg_replace('/\D+/', '', $phone['phone'])); @endphp
                <div class="cv-filter-row flex items-center justify-between gap-2 rounded-lg px-2.5 py-2 hover:bg-base-200/60 transition-colors {{ $isDuplicate ? 'ring-1 ring-warning/50 bg-warning/5' : '' }}"
                        data-search="{{ strtolower($phone['phone'].' '.$phone['phone_type']) }}">
                    <div class="min-w-0 flex items-center gap-2">
                        <span class="badge {{ $colorFor($phone['phone_type']) }} badge-outline badge-sm capitalize shrink-0">{{ $phone['phone_type'] }}</span>
                        <a href="tel:{{ $phone['phone'] }}" class="link link-primary no-underline hover:underline font-mono text-sm truncate">
                            {{ $phone['phone'] }}
                        </a>
                        @if($isDuplicate)
                            <span class="tooltip" data-tip="Appears more than once">
                                <i class="fa-solid fa-triangle-exclamation text-warning text-xs"></i>
                            </span>
                        @endif
                    </div>
                    <button type="button" class="copy-btn btn btn-ghost btn-xs btn-circle shrink-0 no-print" data-copy="{{ $phone['phone'] }}" title="Copy phone" aria-label="Copy phone number">
                        <i class="fa-solid fa-copy text-xs"></i>
                    </button>
                </div>
                @endforeach
            </div>
        @else
            <div class="flex flex-col items-center justify-center text-center py-8 text-base-content/40">
                <i class="fa-solid fa-phone-slash text-2xl mb-2"></i>
                <p class="text-sm">No phone numbers on file</p>
            </div>
        @endif
    </div>
</div>