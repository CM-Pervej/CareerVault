{{-- Hero --}}
<div class="h-28 sm:h-32 md:h-40 w-full bg-gradient-to-br from-primary via-primary/85 to-secondary relative overflow-hidden sm:rounded-t-2xl">
    <div class="absolute inset-0 opacity-[0.15]" style="background-image: radial-gradient(circle at 1px 1px, white 1.5px, transparent 1.5px); background-size: 26px 26px;"></div>
    <div class="absolute -right-10 -top-10 w-56 h-56 rounded-full bg-white/10 blur-2xl"></div>
    {{-- <p class="no-print absolute top-3 left-3 text-base-100/90 uppercase backdrop-blur">
        <i class="fa-solid fa-building mr-1"></i> 
        <a href="{{ route('dashboard') }}">CareerVault</a> /
        <a href="{{ route('companies.index') }}" aria-label="Back to companies">Company</a> /
    </p> --}}
    <div class="flex justify-between items-center p-2 sm:p-4">
        {{-- Breadcrumb --}}
        <div class="breadcrumbs text-sm text-indigo-200 no-print hidden sm:block backdrop-blur uppercase">
            <ul>
                <li><a href="{{ route('dashboard') }}"><i class="fa-solid fa-house mr-1"></i> CareerVault</a></li>
                <li><a href="{{ route('companies.index') }}">Companies</a></li>
                <li class="font-semibold">{{ $company->name }}</li>
            </ul>
        </div>
        <div>
            <a href="{{ route('companies.index') }}" aria-label="Back to companies" class="no-print btn btn-sm btn-circle bg-base-100/90 hover:bg-base-100 border-none shadow-sm backdrop-blur">
                <i class="fa-solid fa-arrow-left"></i>
            </a>
        </div>

    </div>

</div>

{{-- Row 1: identity --}}
<div class="bg-base-100 px-4 sm:px-6 md:px-8 pt-0 pb-4">
    <div class="flex flex-col sm:flex-row sm:items-end gap-4 -mt-10 sm:-mt-12">
        <div class="relative w-20 h-20 sm:w-24 sm:h-24 md:w-28 md:h-28 shrink-0">
            <svg class="absolute inset-0 w-full h-full -rotate-90" viewBox="0 0 100 100">
                <circle cx="50" cy="50" r="{{ $ringRadius }}" fill="none" stroke="currentColor" stroke-width="5" class="text-base-300"/>
                <circle cx="50" cy="50" r="{{ $ringRadius }}" fill="none" stroke="currentColor" stroke-width="5" stroke-linecap="round" class="text-primary transition-all duration-700" stroke-dasharray="{{ $ringCircumference }}" stroke-dashoffset="{{ $ringOffset }}"/>
            </svg>

            <div class="absolute inset-2 rounded-full bg-gradient-to-br from-primary to-secondary text-primary-content flex items-center justify-center text-2xl sm:text-3xl md:text-4xl font-bold shadow-lg ring-4 ring-base-100">
                {{ strtoupper(substr($company->name, 0, 1)) }}
            </div>

            <div class="tooltip absolute -bottom-1 -right-1" data-tip="{{ $completeness }}% profile complete">
                <div class="w-6 h-6 sm:w-7 sm:h-7 rounded-full {{ $completeness === 100 ? 'bg-success text-success-content' : 'bg-base-100 text-primary border border-base-300' }} flex items-center justify-center text-[10px] font-bold shadow">
                    @if($completeness === 100)
                        <i class="fa-solid fa-check"></i>
                    @else
                        {{ $completeness }}
                    @endif
                </div>
            </div>
        </div>

        <div class="min-w-0 flex-1">
            <div class="text-[11px] font-semibold uppercase tracking-wider text-primary/70 mb-0.5">Company Profile</div>
            <h1 class="text-xl sm:text-2xl md:text-3xl font-bold leading-tight truncate" title="{{ $company->name }}"> {{ $company->name }} </h1>
        </div>
    </div>

    <div class="flex flex-wrap items-center gap-1.5 mt-2 sm:ml-28 md:ml-32">
        @if($company->industries->count())
            {{-- mobile --}}
            <div class="sm:hidden flex flex-wrap gap-1">
                @foreach($company->industries->take(2) as $industry)
                    <span class="badge badge-secondary badge-outline badge-sm"> {{ $industry->name }} </span>
                @endforeach
                
                @if($company->industries->count() > 2)
                    <a href="#section-industries" title="See all industries" class="badge badge-ghost badge-sm cv-tag"> +{{ $company->industries->count() - 2 }} more </a>
                @endif
            </div>

            {{-- tablet --}}
            <div class="hidden sm:flex md:hidden flex-wrap gap-1">
                @foreach($company->industries->take(3) as $industry)
                    <span class="badge badge-secondary badge-outline badge-sm"> {{ $industry->name }} </span>
                @endforeach
                
                @if($company->industries->count() > 3)
                    <a href="#section-industries" title="See all industries" class="badge badge-ghost badge-sm cv-tag"> +{{ $company->industries->count() - 3 }} more </a>
                @endif
            </div>

            {{-- laptop --}}
            <div class="hidden md:flex flex-wrap gap-1">
                @foreach($company->industries->take(5) as $industry)
                    <span class="badge badge-secondary badge-outline badge-sm"> {{ $industry->name }} </span>
                @endforeach
                
                @if($company->industries->count() > 5)
                    <a href="#section-industries" title="See all industries" class="badge badge-ghost badge-sm cv-tag"> +{{ $company->industries->count() - 5 }} more </a>
                @endif
            </div>
        @else
            <span class="text-xs text-base-content/40 italic">No industries tagged yet</span>
        @endif
    </div>
</div>

{{-- Row 2: action bar --}}
<div class="no-print px-4 sm:px-6 md:px-8 pb-5 pt-4 border-t border-base-300 flex flex-wrap items-center gap-2 bg-base-100 rounded-b-2xl">
    @if($primaryPhone)
        <a href="tel:{{ $primaryPhone['phone'] }}" class="btn btn-sm btn-outline" title="Call {{ $primaryPhone['phone'] }}">
            <i class="fa-solid fa-phone"></i>
            <span class="hidden lg:inline">Call</span>
        </a>
    @endif

    @if($primaryEmail)
        <a href="mailto:{{ $primaryEmail['email'] }}" class="btn btn-sm btn-outline" title="Email {{ $primaryEmail['email'] }}">
            <i class="fa-solid fa-envelope"></i>
            <span class="hidden lg:inline">Email</span>
        </a>
    @endif

    @if($company->website)
        <a href="{{ $company->website }}" target="_blank" rel="noopener" class="btn btn-sm btn-outline" title="Visit website">
            <i class="fa-solid fa-globe"></i>
            <span class="hidden lg:inline">Visit</span>
        </a>
    @endif

    <button type="button" class="btn btn-sm btn-outline" data-action="share" title="Share this profile">
        <i class="fa-solid fa-share-nodes"></i>
        <span class="hidden lg:inline">Share</span>
    </button>

    {{-- Export --}}
    <details class="dropdown dropdown-end relative">
        <summary class="btn btn-sm btn-outline list-none">
            <i class="fa-solid fa-download"></i>
            <span class="hidden lg:inline">Export</span>
        </summary>

        <ul class="menu menu-sm dropdown-content z-[100] mt-2 w-56 rounded-box bg-base-100 border border-base-300 shadow-xl">
            <li>
                <button type="button" data-action="download-vcard">
                    <i class="fa-solid fa-address-card w-4"></i> Save as vCard (.vcf)
                </button>
            </li>

            <li>
                <button type="button" data-action="download-json">
                    <i class="fa-solid fa-file-code w-4"></i> Export as JSON
                </button>
            </li>

            <li>
                <button type="button" data-action="download-csv">
                    <i class="fa-solid fa-file-csv w-4"></i> Export contacts as CSV
                </button>
            </li>

            <li>
                <button type="button" data-action="copy-summary">
                    <i class="fa-solid fa-copy w-4"></i> Copy summary
                </button>
            </li>

            <li>
                <button type="button" data-action="show-qr">
                    <i class="fa-solid fa-qrcode w-4"></i> Show QR code
                </button>
            </li>

            <li>
                <button type="button" data-action="print">
                    <i class="fa-solid fa-print w-4"></i> Print profile
                </button>
            </li>
        </ul>
    </details>

    @auth
        <a href="{{ route('companies.edit', $company) }}" class="btn btn-sm btn-primary sm:ml-auto" title="Edit company">
            <i class="fa-solid fa-pen"></i>
            <span class="hidden lg:inline">Edit</span>
        </a>
    @endauth
</div>