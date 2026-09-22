<div class="rounded-3xl border border-base-300 bg-base-100 p-6 shadow-sm">

    {{-- Header --}}
    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">

        <div>
            <h2 class="text-xl font-black">
                Platform Pages
            </h2>

            <p class="mt-1 text-sm text-base-content/50">
                Official pages and resources associated with this platform.
            </p>
        </div>

        <a
            href="{{ route('admin.platform-pages.index', [
                'platform' => $platform->slug
            ]) }}"
            class="btn btn-primary btn-sm gap-2"
        >
            <i class="fa-solid fa-sliders"></i>
            Manage Pages
        </a>

    </div>

    @php
        $activePages = $platform->platformPages
            ->where('is_active', true);
    @endphp

    @if($activePages->isNotEmpty())

        <div class="mt-5 grid gap-3 sm:grid-cols-2">

            @foreach($activePages as $page)

                <a
                    href="{{ $page->url }}"
                    target="_blank"
                    rel="noopener noreferrer"
                    class="group rounded-2xl border border-base-300 bg-base-100 p-4 transition hover:-translate-y-0.5 hover:border-primary/30 hover:shadow-md"
                >

                    <div class="flex items-start justify-between gap-4">

                        <div class="min-w-0">

                            <div class="flex items-center gap-2">

                                <h3 class="truncate font-bold group-hover:text-primary">
                                    {{ $page->name }}
                                </h3>

                                <i class="fa-solid fa-arrow-up-right-from-square text-xs text-base-content/30 group-hover:text-primary"></i>

                            </div>

                            @if($page->page_type)
                                <span class="mt-2 inline-flex rounded-full bg-base-200 px-2.5 py-1 text-[11px] font-semibold">
                                    {{ $page->page_type }}
                                </span>
                            @endif

                            @if($page->short_desc)
                                <p class="mt-2 line-clamp-2 text-xs leading-5 text-base-content/60">
                                    {{ $page->short_desc }}
                                </p>
                            @endif

                        </div>

                        <i class="fa-solid fa-chevron-right shrink-0 pt-1 text-sm text-base-content/20 transition group-hover:translate-x-1 group-hover:text-primary"></i>

                    </div>

                </a>

            @endforeach

        </div>

    @else

        <div class="mt-5 rounded-2xl border border-dashed border-base-300 p-8 text-center">

            <div class="mx-auto grid size-12 place-items-center rounded-xl bg-base-200">
                <i class="fa-solid fa-file-lines text-base-content/40"></i>
            </div>

            <h3 class="mt-3 font-bold">
                No platform pages
            </h3>

            <p class="mt-1 text-sm text-base-content/60">
                No official pages have been added for this platform yet.
            </p>

            <a
                href="{{ route('admin.platform-pages.create', [
                    'platform' => $platform->slug
                ]) }}"
                class="btn btn-primary btn-sm mt-4 gap-2"
            >
                <i class="fa-solid fa-plus"></i>
                Add First Page
            </a>

        </div>

    @endif

</div>