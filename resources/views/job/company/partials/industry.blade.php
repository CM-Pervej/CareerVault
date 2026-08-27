{{-- Industries --}}
@if($company->industries->isNotEmpty())
<div id="section-industries" class="card bg-base-100 shadow-sm border border-base-300 scroll-mt-20">
    <div class="card-body">
        <div class="flex items-center justify-between mb-4">
            <div class="flex items-center gap-2">
                <div class="w-8 h-8 rounded-lg bg-primary/10 text-primary flex items-center justify-center shrink-0">
                    <i class="fa-solid fa-industry text-sm"></i>
                </div>
                <h2 class="text-lg font-semibold">Industries</h2>
            </div>
            <span class="badge badge-ghost badge-sm font-medium">{{ $company->industries->count() }}</span>
        </div>

        <div class="flex flex-wrap gap-2">
            @foreach($company->industries as $industry)
            <span class="badge badge-secondary badge-outline">{{ $industry->name }}</span>
            @endforeach
        </div>
    </div>
</div>
@endif