<section class="sm:rounded-lg border border-base-300 bg-base-100 shadow-sm px-6 py-5">
    <div class="border-b border-base-200">
        <h2 class="text-xl font-bold pl-3 border-l-4" style="border-left-color: {{ $platform->color }}">About {{ $platform->name }} </h2>
    </div>

    <div class="pt-2 pb-4">
        @if($platform->description)
            <div class="whitespace-pre-line text-sm leading-7 text-base-content/70">{{ $platform->description }}</div>
        @else
            <p class="text-base-content/45">No detailed description has been added.</p>
        @endif
    </div>

    @php
        $rows=[
            ['Name',$platform->name],
            ['Official Name',$platform->official_name?:'—'],
            ['Status',$platform->is_active?'Active':'No'],
            ['Job Type',$platform->job_type],
            ['Business Model',$platform->business_model],
            ['Account',$platform->account_required?'Required':'Not Required'],
            ['Bangladesh Focused',$platform->is_bangladesh_focused?'Yes':'Global'],
            ['Founded', $platform->founded_year ? ($platform->founded_month ? ' '.\Carbon\Carbon::create()->month($platform->founded_month)->format('F') : '').', '.$platform->founded_year : 'No'],
        ];
    @endphp

    <div class="divide-y divide-base-200">
        @foreach($rows as [$label,$value])
            <div class="row-hover flex items-center justify-between gap-4 py-2">
                <span class="text-sm text-base-content/50">{{ $label }}</span>

                @if($label === 'Bangladesh Focused')
                    @if($platform->is_bangladesh_focused)
                        <span class="px-2.5 py-1 rounded-md text-sm font-semibold text-white" style="background-color: {{ $platform->color }}">
                            {{ $value }}
                        </span>
                    @else
                        <span class="font-semibold" style="color: {{ $platform->color }}">
                            {{ $value }}
                        </span>
                    @endif
                @else
                    <span class="font-semibold text-right break-all">{{ $value }}</span>
                @endif
            </div>
        @endforeach
    </div>
</section>

{{-- Links --}}
<article id="section-links" class="scroll-mt-4 sm:rounded-lg border border-base-300/70 bg-base-100 p-5 shadow-sm sm:p-6">
    <div class="border-b border-base-200">
        <h2 class="text-xl font-bold pl-3 border-l-4" style="border-left-color: {{ $platform->color }}">Platform links</h2>
    </div>
    <div class="mt-3 divide-y divide-base-300/70">
        @if($platform->base_url)
            <div class="flex items-center gap-3 py-3">
                <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-lg bg-base-200 text-base-content/50">
                    <i class="fa-solid fa-globe text-xs"></i>
                </div>
                <div class="min-w-0 flex-1">
                    <p class="text-xs text-base-content/45">Official website</p>
                    <a href="{{ $platform->base_url }}" target="_blank" rel="noopener noreferrer" class="block truncate text-sm font-semibold hover:underline" style="color:{{ $platform->color }};">
                        {{ $platform->base_url }}
                    </a>
                </div>
                <button type="button" data-copy="{{ $platform->base_url }}" class="btn btn-ghost btn-sm btn-square rounded-lg" title="Copy website URL">
                    <i class="fa-regular fa-copy text-xs"></i>
                </button>
            </div>
        @endif

        @if($platform->job_url)
            <div class="flex items-center gap-3 py-3">
                <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-lg bg-base-200 text-base-content/50">
                    <i class="fa-solid fa-magnifying-glass text-xs"></i>
                </div>
                <div class="min-w-0 flex-1">
                    <p class="text-xs text-base-content/45">Job portal</p>
                    <a href="{{ $platform->job_url }}" target="_blank" rel="noopener noreferrer" class="block truncate text-sm font-semibold hover:underline" style="color:{{ $platform->color }};">
                        {{ $platform->job_url }}
                    </a>
                </div>
                <button type="button" data-copy="{{ $platform->job_url }}" class="btn btn-ghost btn-sm btn-square rounded-lg" title="Copy job URL">
                    <i class="fa-regular fa-copy text-xs"></i>
                </button>
            </div>
        @endif

        @if(!$platform->base_url && !$platform->job_url)
            <p class="py-4 text-sm text-base-content/45">No platform links have been added.</p>
        @endif
    </div>
</article>

{{-- Timeline --}}
<article id="section-timeline" class="scroll-mt-4 sm:rounded-lg border border-base-300/70 bg-base-100 p-5 shadow-sm sm:p-6">
    <div class="border-b border-base-200">
        <h2 class="text-xl font-bold pl-3 border-l-4" style="border-left-color: {{ $platform->color }}">Platform timeline</h2>
    </div>
    <div class="relative mt-5 space-y-6 pl-1">
        <div class="pointer-events-none absolute bottom-3 left-[15px] top-3 w-px bg-base-300"></div>

        @php
            $foundedLabel='Not on record';
            if($platform->founded_year){
                $monthName=$platform->founded_month?date('F',mktime(0,0,0,$platform->founded_month,1)):'';
                $foundedLabel=trim(($monthName?$monthName.' ':'').$platform->founded_year);
            }

            $marketAge=null;
            if($platform->founded_year){
                $marketAge=max(0,now()->year-$platform->founded_year);
            }

            $verifiedAt=$platform->last_verified_at? \Illuminate\Support\Carbon::parse($platform->last_verified_at):null;
            $verificationLabel='Not yet verified';
            $verificationTone='neutral';

            if($verifiedAt){
                $daysSinceVerification=$verifiedAt->diffInDays(now());

                if($daysSinceVerification<=30){
                    $verificationLabel='Verified recently';
                    $verificationTone='success';
                }elseif($daysSinceVerification<=90){
                    $verificationLabel='Verified '.$daysSinceVerification.' days ago';
                    $verificationTone='warning';
                }else{
                    $verificationLabel='Verification is due';
                    $verificationTone='danger';
                }
            }

            $verificationBadgeClass=match($verificationTone){
                'success'=>'badge-success',
                'warning'=>'badge-warning',
                'danger'=>'badge-error',
                default=>'badge-ghost',
            };
        @endphp

        <div class="relative flex gap-4">
            <div class="relative z-10 flex h-8 w-8 shrink-0 items-center justify-center rounded-full border-2 border-base-100" style="background:{{ $platform->color }}; color:{{ $platform->color }};">
                <i class="fa-solid fa-flag text-[11px]"></i>
            </div>
            <div>
                <p class="text-sm font-semibold">Platform established</p>
                <p class="mt-0.5 text-xs text-base-content/50">
                    {{ $foundedLabel }}
                    @if($marketAge!==null)
                        · {{ $marketAge }}{{ $marketAge===1?' year':' years' }} in operation
                    @endif
                </p>
            </div>
        </div>

        <div class="relative flex gap-4">
            <div class="relative z-10 flex h-8 w-8 shrink-0 items-center justify-center rounded-full border-2 border-base-100 bg-success/10 text-success">
                <i class="fa-solid fa-shield-check text-[11px]"></i>
            </div>
            <div>
                <p class="text-sm font-semibold">Verification status</p>
                <p class="mt-0.5 text-xs text-base-content/50">
                    {{ $verificationLabel }}
                    @if($verifiedAt)
                        · {{ $verifiedAt->format('d M, Y . h:i A') }}
                    @endif
                </p>
            </div>
        </div>
    </div>
</article>

{{-- Metadata --}}
<article id="section-metadata" class="scroll-mt-4 sm:rounded-lg border border-base-300/70 bg-base-100 p-5 shadow-sm sm:p-6">
    <div class="border-b border-base-200">
        <h2 class="text-xl font-bold pl-3 border-l-4" style="border-left-color: {{ $platform->color }}">Record Information</h2>
    </div>

    <dl class="mt-3 grid grid-cols-1 gap-x-6 divide-y divide-base-300/70 sm:divide-y-0">
        <div class="flex items-center justify-between gap-4 py-2.5 sm:border-b sm:border-base-300/70">
            <dt class="text-sm text-base-content/55">Slug</dt>
            <dd class="cv-mono truncate text-xs font-medium text-base-content/70">{{ $platform->slug }}</dd>
        </div>
        <div class="flex items-center justify-between gap-4 py-2.5 sm:border-b sm:border-base-300/70">
            <dt class="text-sm text-base-content/55">Sort order</dt>
            <dd class="text-sm font-semibold">{{ $platform->sort_order }}</dd>
        </div>
        @if($platform->created_at)
            <div class="flex items-center justify-between gap-4 py-2.5">
                <dt class="text-sm text-base-content/55">Added to CareerVault</dt>
                <dd class="text-sm font-semibold">{{ $platform->created_at->format('d M, Y - h:i A') }}</dd>
            </div>
        @endif
        @if($platform->updated_at)
            <div class="flex items-center justify-between gap-4 py-2.5">
                <dt class="text-sm text-base-content/55">Last updated</dt>
                <dd class="text-sm font-semibold">{{ $platform->updated_at->format('d M, Y - h:i A') }}</dd>
            </div>
        @endif
    </dl>
</article>