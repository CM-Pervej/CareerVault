<section class="space-y-8 mt-10">
    <div class="card border border-base-300 bg-base-100 shadow-sm">
        <div class="card-body">
            <h2 class="card-title text-base">Basic Information</h2>
    
            <div class="grid gap-4 md:grid-cols-2">
                <div class="form-control">
                    <label class="label">
                        <span class="label-text">Country Name <span class="text-error">*</span></span>
                    </label>
                    <input type="text" name="name" value="{{ old('name',$country->name ?? '') }}" class="input input-bordered w-full" required>
                </div>
    
                <div class="form-control">
                    <label class="label">
                        <span class="label-text">Slug</span>
                    </label>
                    <input type="text" name="slug" value="{{ old('slug',$country->slug ?? '') }}" class="input input-bordered w-full" placeholder="Auto-generated if empty">
                </div>
    
                <div class="form-control">
                    <label class="label">
                        <span class="label-text">ISO 2 <span class="text-error">*</span></span>
                    </label>
                    <input type="text" name="iso2" value="{{ old('iso2',$country->iso2 ?? '') }}" maxlength="2" class="input input-bordered w-full uppercase" placeholder="BD" required>
                </div>
    
                <div class="form-control">
                    <label class="label">
                        <span class="label-text">ISO 3 <span class="text-error">*</span></span>
                    </label>
                    <input type="text" name="iso3" value="{{ old('iso3',$country->iso3 ?? '') }}" maxlength="3" class="input input-bordered w-full uppercase" placeholder="BGD" required>
                </div>
    
                <div class="form-control">
                    <label class="label">
                        <span class="label-text">Phone Code</span>
                    </label>
                    <input type="text" name="phone_code" value="{{ old('phone_code',$country->phone_code ?? '') }}" class="input input-bordered w-full" placeholder="+880">
                </div>
    
                <div class="form-control">
                    <label class="label">
                        <span class="label-text">Capital</span>
                    </label>
                    <input type="text" name="capital" value="{{ old('capital',$country->capital ?? '') }}" class="input input-bordered w-full">
                </div>
            </div>
        </div>
    </div>
    
    <div class="card mt-4 border border-base-300 bg-base-100 shadow-sm">
        <div class="card-body">
            <h2 class="card-title text-base">Currency</h2>
    
            <div class="grid gap-4 md:grid-cols-2">
                <div class="form-control">
                    <label class="label">
                        <span class="label-text">Currency Code</span>
                    </label>
                    <input type="text" name="currency_code" value="{{ old('currency_code',$country->currency_code ?? '') }}" maxlength="3" class="input input-bordered w-full uppercase" placeholder="BDT">
                </div>
    
                <div class="form-control">
                    <label class="label">
                        <span class="label-text">Currency Name</span>
                    </label>
                    <input type="text" name="currency_name" value="{{ old('currency_name',$country->currency_name ?? '') }}" class="input input-bordered w-full" placeholder="Bangladeshi Taka">
                </div>
            </div>
        </div>
    </div>

    <div class="card mt-4 border border-base-300 bg-base-100 shadow-sm">
        <div class="card-body">
            <h2 class="card-title text-base">Geography & Display</h2>
    
            <div class="grid gap-4 md:grid-cols-2">
                <div class="form-control">
                    <label class="label">
                        <span class="label-text">Region</span>
                    </label>
                    <input type="text" name="region" value="{{ old('region',$country->region ?? '') }}" class="input input-bordered w-full">
                </div>
    
                <div class="form-control">
                    <label class="label">
                        <span class="label-text">Subregion</span>
                    </label>
                    <input type="text" name="subregion" value="{{ old('subregion',$country->subregion ?? '') }}" class="input input-bordered w-full">
                </div>
    
                <div class="form-control">
                    <label class="label">
                        <span class="label-text">Sort Order</span>
                    </label>
                    <input type="number" name="sort_order" value="{{ old('sort_order',$country->sort_order ?? 0) }}" min="0" class="input input-bordered w-full">
                </div>
    
                <div class="form-control justify-end">
                    <label class="label cursor-pointer justify-start gap-3">
                        <input type="checkbox" name="is_active" value="1" class="toggle toggle-success" @checked(old('is_active',$country->is_active ?? true))>
                        <span>
                            <span class="label-text font-medium">Active</span>
                            <span class="block text-xs text-base-content/50">Available for selection throughout the system.</span>
                        </span>
                    </label>
                </div>
            </div>
        </div>
    </div>
</section>

<div class="sticky bottom-0 -mx-6 mt-8 flex flex-wrap justify-end gap-3 border-t border-base-300 bg-base-100/95 px-6 py-4 backdrop-blur">
    <a href="{{ isset($country) ? route('admin.countries.show',$country) : route('admin.countries.index') }}" class="btn btn-ghost">Cancel</a>

    <button type="submit" class="btn btn-primary">
        <i class="fa-solid {{ isset($country) ? 'fa-floppy-disk' : 'fa-check' }}"></i>
        {{ isset($country) ? 'Update Country' : 'Create Country' }}
    </button>
</div>