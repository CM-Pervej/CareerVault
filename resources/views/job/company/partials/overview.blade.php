{{-- Overview --}}
<div class="card-body">
    <div class="flex items-center gap-2 mb-5">
        <div class="w-8 h-8 rounded-lg bg-primary/10 text-primary flex items-center justify-center shrink-0">
            <i class="fa-solid fa-building text-sm"></i>
        </div>
        <h2 class="text-lg font-semibold">Overview</h2>
    </div>

    <dl class="grid grid-cols-1 sm:grid-cols-2 gap-x-8 gap-y-5">
        {{-- Website --}}
        <div class="flex items-start gap-3">
            <div class="mt-0.5 shrink-0 w-9 h-9 rounded-lg bg-primary/10 text-primary flex items-center justify-center">
                <i class="fa-solid fa-globe"></i>
            </div>
            <div class="min-w-0 flex-1">
                <dt class="text-xs font-medium uppercase tracking-wide text-base-content/50">Website</dt>
                <dd class="mt-0.5 text-sm">
                    @if($company->website)
                        <div class="flex items-center gap-1.5 group">
                            <a href="{{ $company->website }}" target="_blank" rel="noopener" title="{{ $company->website }}" class="link link-primary no-underline hover:underline font-mono truncate">
                                {{ $websiteHost }}
                            </a>
                            <i class="fa-solid fa-arrow-up-right-from-square text-[10px] text-base-content/30 group-hover:text-primary transition-colors"></i>
                            <button type="button" class="copy-btn btn btn-ghost btn-xs btn-circle no-print" data-copy="{{ $company->website }}" title="Copy website" aria-label="Copy website URL">
                                <i class="fa-solid fa-copy text-xs"></i>
                            </button>
                        </div>
                    @else
                        <span class="text-base-content/30">Not provided</span>
                    @endif
                </dd>
            </div>
        </div>

        {{-- Career Page --}}
        <div class="flex items-start gap-3">
            <div class="mt-0.5 shrink-0 w-9 h-9 rounded-lg bg-primary/10 text-primary flex items-center justify-center">
                <i class="fa-solid fa-briefcase"></i>
            </div>
            <div class="min-w-0 flex-1">
                <dt class="text-xs font-medium uppercase tracking-wide text-base-content/50">Career Page</dt>
                <dd class="mt-0.5 text-sm">
                    @if($company->career_page)
                        <div class="flex items-center gap-1.5 group">
                            <a href="{{ $company->career_page }}" target="_blank" rel="noopener" title="{{ $company->career_page }}" class="link link-primary no-underline hover:underline font-mono truncate">
                                {{ $careerHost }}
                            </a>
                            <i class="fa-solid fa-arrow-up-right-from-square text-[10px] text-base-content/30 group-hover:text-primary transition-colors"></i>
                            <button type="button" class="copy-btn btn btn-ghost btn-xs btn-circle no-print" data-copy="{{ $company->career_page }}" title="Copy career page" aria-label="Copy career page URL">
                                <i class="fa-solid fa-copy text-xs"></i>
                            </button>
                        </div>
                    @else
                        <span class="text-base-content/30">Not provided</span>
                    @endif
                </dd>
            </div>
        </div>

        {{-- Company Name --}}
        <div class="flex items-start gap-3">
            <div class="mt-0.5 shrink-0 w-9 h-9 rounded-lg bg-primary/10 text-primary flex items-center justify-center">
                <i class="fa-solid fa-signature"></i>
            </div>
            <div class="min-w-0">
                <dt class="text-xs font-medium uppercase tracking-wide text-base-content/50">Company Name</dt>
                <dd class="mt-0.5 text-sm font-medium" title="{{ $company->name }}"> {{ $company->name }} </dd>
            </div>
        </div>

        {{-- Record created --}}
        <div class="flex items-start gap-3">
            <div class="mt-0.5 shrink-0 w-9 h-9 rounded-lg bg-primary/10 text-primary flex items-center justify-center">
                <i class="fa-solid fa-calendar-days"></i>
            </div>
            <div class="min-w-0">
                <dt class="text-xs font-medium uppercase tracking-wide text-base-content/50">Record Created</dt>
                <dd class="mt-0.5 text-sm font-medium" title="Last updated {{ $company->updated_at->format('d M Y, h:i A') }}">
                    {{ $company->created_at->format('d M Y, h:i A') }}
                </dd>
            </div>
        </div>
    </dl>
</div>