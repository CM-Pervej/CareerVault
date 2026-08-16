<div>

    {{-- Table --}}
    <div class="card bg-base-100 shadow border border-base-300 overflow-hidden">

        <div class="overflow-x-auto">

            <table class="table">

                <thead>
                    <tr class="cv-eyebrow border-b border-base-300">
                        <th class="w-10">#</th>
                        <th>Industry</th>
                        <th>Slug</th>
                        <th class="text-center">Action</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-slate-100">

                    @forelse($industries as $item)

                        <tr class="cv-row company-card border-b border-base-300">

                            {{-- Number --}}
                            <td class="cv-mono text-xs opacity-40">
                                {{
                                    str_pad(
                                        $loop->iteration
                                        + (($industries->currentPage() - 1) * $industries->perPage()),
                                        2,
                                        '0',
                                        STR_PAD_LEFT
                                    )
                                }}
                            </td>


                            {{-- Industry --}}
                            <td class="px-4 py-3">

                                <div class="flex items-center gap-3">

                                    <div class="cv-avatar bg-primary text-primary-content">
                                        {{ strtoupper(substr($item->name, 0, 1)) }}
                                    </div>

                                    <span class="font-medium text-slate-800">
                                        {{ $item->name }}
                                    </span>

                                </div>

                            </td>


                            {{-- Slug --}}
                            <td class="px-4 py-3">

                                <span class="inline-flex items-center rounded-md bg-slate-100 text-slate-500 text-xs font-mono px-2 py-1">
                                    {{ $item->slug }}
                                </span>

                            </td>


                            {{-- Actions --}}
                            <td class="px-4 py-3">

                                <div class="flex gap-2 justify-center">

                                    {{-- Edit --}}
                                    <a
                                        href="{{ route('industries.edit', $item) }}"
                                        class="inline-flex items-center gap-1.5 rounded-lg border border-slate-200 text-slate-600 hover:bg-slate-100 text-xs font-medium px-3 py-1.5 transition hover:text-indigo-600"
                                    >
                                        <i class="fa-solid fa-pen text-[11px]"></i>
                                        Edit
                                    </a>


                                    {{-- Delete --}}
                                    <button
                                        type="button"
                                        class="open-delete-modal inline-flex items-center gap-1.5 rounded-lg border border-rose-100 bg-rose-50 text-rose-600 hover:bg-rose-100 text-xs font-medium px-3 py-1.5 transition"
                                        data-action="{{ route('industries.destroy', $item) }}"
                                        data-name="{{ $item->name }}"
                                    >
                                        <i class="fa-solid fa-trash text-[11px]"></i>
                                        Delete
                                    </button>

                                </div>

                            </td>

                        </tr>

                    @empty

                        {{-- No Results --}}
                        <tr>

                            <td
                                colspan="4"
                                class="text-center py-12"
                            >

                                <div class="flex flex-col items-center gap-2 text-slate-400">

                                    @if(request('search'))

                                        <i class="fa-solid fa-magnifying-glass text-2xl"></i>

                                        <p class="text-sm">
                                            No industries match
                                            "{{ request('search') }}".
                                        </p>

                                    @else

                                        <i class="fa-solid fa-inbox text-2xl"></i>

                                        <p class="text-sm">
                                            No industries found.
                                        </p>

                                    @endif

                                </div>

                            </td>

                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

    </div>


    {{-- Pagination --}}
    @if($industries->hasPages())

        <div class="mt-5">
            {{ $industries->links() }}
        </div>

    @endif

</div>