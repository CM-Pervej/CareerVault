<section class="grid grid-cols-2 gap-4 xl:grid-cols-4">
    <div class="rounded-2xl border border-base-300 bg-base-100 p-5 shadow-sm"><p class="text-xs text-base-content/45 uppercase">Companies</p><p class="mt-2 text-3xl font-black">{{ $platform->companies->count() }}</p></div>
    <div class="rounded-2xl border border-base-300 bg-base-100 p-5 shadow-sm"><p class="text-xs text-base-content/45 uppercase">Presence</p><p class="mt-2 text-3xl font-black">{{ $platform->connectedPlatforms->count() }}</p></div>
    <div class="rounded-2xl border border-base-300 bg-base-100 p-5 shadow-sm"><p class="text-xs text-base-content/45 uppercase">Pages</p><p class="mt-2 text-3xl font-black">{{ $platform->platformPages->count() }}</p></div>
    <div class="rounded-2xl border border-base-300 bg-base-100 p-5 shadow-sm"><p class="text-xs text-base-content/45 uppercase">Platform ID</p><p class="mt-2 text-3xl font-black">#{{ $platform->id }}</p></div>
</section>