<div class="card bg-base-100 shadow-xl border border-base-300/60 rounded-none sm:rounded-2xl">
    <div class="card-body">
        {{-- Header --}}
        <div class="flex items-start justify-between gap-4 pb-6 border-b border-base-300/60">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-primary/15 to-primary/5 flex items-center justify-center ring-1 ring-primary/10">
                    <i class="fa-solid fa-building text-primary text-lg"></i>
                </div>

                <div>
                    <h2 class="card-title text-base-content text-lg"> Company Information </h2>
                    <p class="text-sm text-base-content/60 mt-0.5"> Basic information about the company. </p>
                </div>
            </div>

            <div class="badge badge-ghost badge-sm gap-1.5 hidden sm:flex"> 
                <i class="fa-solid fa-circle-info text-[10px]"></i> Step 1 of 6 
            </div>
        </div>

        {{-- Fields --}}
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            {{-- Company Name --}}
            <div class="lg:col-span-2 form-control">
                <label for="name" class="label pb-1.5">
                    <span class="label-text font-medium text-base-content/80">
                        Company Name <span class="text-error">*</span>
                    </span>
                </label>

                <label class="input input-bordered flex items-center gap-2 w-full focus-within:input-primary @error('name') input-error @enderror">
                    <i class="fa-solid fa-building text-base-content/30 text-xs"></i>
                    <input id="name" type="text" name="name" value="{{ old('name', $company?->name) }}" placeholder="Google LLC" class="grow" required>
                </label>

                @error('name')
                    <label class="label pt-1.5">
                        <span class="label-text-alt text-error flex items-center gap-1"> <i class="fa-solid fa-circle-exclamation text-[11px]"></i> {{ $message }} </span>
                    </label>
                @enderror

            </div>

            {{-- Website --}}
            <div class="form-control">
                <label for="website" class="label pb-1.5">
                    <span class="label-text font-medium text-base-content/80"> Website </span>
                </label>

                <label class="input input-bordered flex items-center gap-2 w-full focus-within:input-primary @error('website') input-error @enderror">
                    <i class="fa-solid fa-globe text-base-content/30 text-xs"></i>
                    <input id="website" type="url" name="website" value="{{ old('website', $company?->website) }}" placeholder="https://company.com" class="grow">
                </label>

                @error('website')
                    <label class="label pt-1.5">
                        <span class="label-text-alt text-error flex items-center gap-1"> <i class="fa-solid fa-circle-exclamation text-[11px]"></i> {{ $message }} </span>
                    </label>
                @enderror
            </div>

            {{-- Career Page --}}
            <div class="form-control">
                <label for="career_page" class="label pb-1.5">
                    <span class="label-text font-medium text-base-content/80"> Career Page </span>
                </label>

                <label class="input input-bordered flex items-center gap-2 w-full focus-within:input-primary @error('career_page') input-error @enderror">
                    <i class="fa-solid fa-briefcase text-base-content/30 text-xs"></i>
                    <input id="career_page" type="url" name="career_page" value="{{ old('career_page', $company?->career_page) }}" placeholder="https://company.com/careers" class="grow">
                </label>

                @error('career_page')
                    <label class="label pt-1.5">
                        <span class="label-text-alt text-error flex items-center gap-1"> <i class="fa-solid fa-circle-exclamation text-[11px]"></i> {{ $message }} </span>
                    </label>
                @enderror
            </div>
        </div>
    </div>
</div>