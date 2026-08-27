<div>
    <div class="card bg-base-100 shadow border border-base-300 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="table table-zebra">
                <thead>
                    <tr class="cv-eyebrow border-b border-base-300">
                        <th class="w-10">#</th>
                        <th>Platform</th>
                        <th>Slug</th>
                        <th>Icon</th>
                        <th>Color</th>
                        <th>Base URL</th>
                        <th>Job URL</th>
                        <th>Job Type</th>
                        <th class="text-center">Actions</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-slate-100">
                    @forelse($platforms as $platform)

                        <tr class="cv-row company-card border-b border-base-300">

                            {{-- Number --}}
                            <td class="cv-mono text-xs opacity-40">
                                {{ str_pad($loop->iteration + (($platforms->currentPage() - 1) * $platforms->perPage()), 2, '0', STR_PAD_LEFT) }}
                            </td>

                            {{-- Platform --}}
                            <td>
                                <div class="flex items-center gap-3">

                                    <div
                                        class="w-9 h-9 rounded-lg flex items-center justify-center shrink-0"
                                        style="{{ $platform->color ? 'background-color:'.$platform->color.'20;color:'.$platform->color : '' }}"
                                    >
                                        @if($platform->icon)
                                            <i class="{{ $platform->icon }} text-lg"></i>
                                        @else
                                            <i class="fa-solid fa-globe text-base-content/40"></i>
                                        @endif
                                    </div>

                                    <span class="font-medium">
                                        {{ $platform->name }}
                                    </span>

                                </div>
                            </td>

                            {{-- Slug --}}
                            <td>
                                <code class="inline-flex items-center rounded-md bg-slate-100 text-slate-500 text-xs font-mono px-2 py-1">
                                    {{ $platform->slug }}
                                </code>
                            </td>

                            {{-- Icon --}}
                            <td>
                                @if($platform->icon)

                                    <div class="flex items-center gap-2">
                                        <i class="{{ $platform->icon }}"></i>

                                        <code class="text-xs">
                                            {{ $platform->icon }}
                                        </code>
                                    </div>

                                @else
                                    <span class="text-base-content/40">—</span>
                                @endif
                            </td>

                            {{-- Color --}}
                            <td>
                                @if($platform->color)

                                    <div class="flex items-center gap-2">

                                        <span
                                            class="w-6 h-6 rounded border border-base-300 shrink-0"
                                            style="background-color:{{ $platform->color }}"
                                            title="{{ $platform->color }}"
                                        ></span>

                                        <code class="text-xs">
                                            {{ $platform->color }}
                                        </code>

                                    </div>

                                @else
                                    <span class="text-base-content/40">—</span>
                                @endif
                            </td>

                            {{-- Base URL --}}
                            <td>
                                @if($platform->base_url)

                                    @php
                                        $host = parse_url($platform->base_url, PHP_URL_HOST);
                                    @endphp

                                    <a
                                        href="{{ $platform->base_url }}"
                                        target="_blank"
                                        rel="noopener noreferrer"
                                        class="link link-primary text-sm"
                                    >
                                        {{ $host ?: $platform->base_url }}
                                    </a>

                                @else
                                    <span class="text-base-content/40">—</span>
                                @endif
                            </td>

                            {{-- Job URL --}}
                            <td>
                                @if($platform->job_url)

                                    @php
                                        $jobHost = parse_url($platform->job_url, PHP_URL_HOST);
                                    @endphp

                                    <a
                                        href="{{ $platform->job_url }}"
                                        target="_blank"
                                        rel="noopener noreferrer"
                                        class="link link-primary text-sm"
                                    >
                                        <span class="inline-flex items-center gap-1.5">
                                            <i class="fa-solid fa-briefcase text-xs"></i>
                                            {{ $jobHost ?: $platform->job_url }}
                                        </span>
                                    </a>

                                @else
                                    <span class="text-base-content/40">—</span>
                                @endif
                            </td>

                            {{-- Job Type --}}
                            <td>
                                @if($platform->job_type)

                                    <span class="badge badge-ghost text-xs capitalize">
                                        {{ str_replace('_', ' ', $platform->job_type) }}
                                    </span>

                                @else
                                    <span class="text-base-content/40">—</span>
                                @endif
                            </td>

                            {{-- Actions --}}
                            <td>
                                <div class="flex justify-end gap-1">

                                    {{-- Edit --}}
                                    <button
                                        type="button"
                                        class="inline-flex items-center gap-1.5 rounded-lg border border-slate-200 text-slate-600 hover:bg-slate-100 text-xs font-medium px-3 py-1.5 transition hover:text-indigo-600"
                                        data-edit-platform
                                        data-id="{{ $platform->id }}"
                                        data-name="{{ $platform->name }}"
                                        data-icon="{{ $platform->icon ?? '' }}"
                                        data-color="{{ $platform->color ?? '' }}"
                                        data-base-url="{{ $platform->base_url ?? '' }}"
                                        data-job-url="{{ $platform->job_url ?? '' }}"
                                        data-job-type="{{ $platform->job_type ?? '' }}"
                                        title="Edit"
                                    >
                                        <i class="fa-solid fa-pen"></i>
                                        Edit
                                    </button>

                                    {{-- Delete --}}
                                    <button
                                        type="button"
                                        class="inline-flex items-center gap-1.5 rounded-lg border border-rose-100 bg-rose-50 text-rose-600 hover:bg-rose-100 text-xs font-medium px-3 py-1.5 transition"
                                        data-delete-platform
                                        data-id="{{ $platform->id }}"
                                        data-name="{{ $platform->name }}"
                                        title="Delete"
                                    >
                                        <i class="fa-solid fa-trash"></i>
                                        Delete
                                    </button>

                                </div>
                            </td>

                        </tr>

                    @empty

                        <tr>
                            <td colspan="9">

                                <div class="flex flex-col items-center justify-center py-12 text-center">

                                    <i class="fa-solid fa-layer-group text-4xl text-base-content/20 mb-3"></i>

                                    <h3 class="font-semibold">
                                        No platforms found
                                    </h3>

                                    <p class="text-sm text-base-content/50 mt-1">
                                        No platforms match your search.
                                    </p>

                                </div>

                            </td>
                        </tr>

                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- Pagination --}}
    @if($platforms->hasPages())

        <div id="platformPagination" class="mt-5 border-t border-base-200">
            {{ $platforms->links() }}
        </div>

    @else

        <div id="platformPagination"></div>

    @endif
</div>