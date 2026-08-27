{{-- platforms --}}
@if($company->platforms->isNotEmpty())
    <section class="mb-8">
        <div class="card bg-base-100 shadow-sm border border-base-300">
            <div class="card-body p-6 lg:p-8">
                <div class="flex items-start justify-between gap-4 pb-6 border-b border-base-300/60">
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-primary/15 to-primary/5 flex items-center justify-center ring-1 ring-primary/10">
                            <i class="fa-solid fa-share-nodes text-primary text-lg"></i>
                        </div>

                        <div>
                            <h2 class="card-title text-base-content text-lg">Digital Presence</h2>
                            <p class="text-sm text-base-content/60 mt-0.5">Company's presence across social and professional platforms.</p>
                        </div>
                    </div>
                    <div class="badge badge-ghost badge-sm gap-1.5 hidden sm:flex">
                        <i class="fa-solid fa-layer-group text-[10px]"></i>
                        {{ $company->platforms->count() }}
                    </div>
                </div>

                <div class="flex gap-3 flex-wrap justify-center">
                    @foreach($company->platforms as $platform)
                        <a href="{{ $platform->pivot->url }}" target="_blank" class="tooltip" data-tip="{{ $platform->name }}">
                            <i class="{{ $platform->icon }} text-3xl" style="color: {{ $platform->color }}"></i>
                        </a>
                    @endforeach
                </div>
            </div>
        </div>
    </section>
@endif