<div class="flex items-center gap-2 overflow-x-auto no-scrollbar">
    <a href="#section-overview" data-nav="overview" class="cv-nav-link btn btn-xs btn-ghost border border-base-300 shrink-0">Overview</a>
    <a href="#section-contacts" data-nav="contacts" class="cv-nav-link btn btn-xs btn-ghost border border-base-300 shrink-0">Contacts</a>
    <a href="#section-locations" data-nav="locations" class="cv-nav-link btn btn-xs btn-ghost border border-base-300 shrink-0">Locations</a>
    @if($company->countries->isNotEmpty())
        <a href="#section-countries" data-nav="countries" class="cv-nav-link btn btn-xs btn-ghost border border-base-300 shrink-0">Countries</a>
    @endif
    @if($company->cities->isNotEmpty())
        <a href="#section-cities" data-nav="cities" class="cv-nav-link btn btn-xs btn-ghost border border-base-300 shrink-0">Cities</a>
    @endif
    <a href="#section-completeness" data-nav="completeness" class="cv-nav-link btn btn-xs btn-ghost border border-base-300 shrink-0">Completeness</a>
    @if($company->industries->isNotEmpty())
        <a href="#section-industries" data-nav="industries" class="cv-nav-link btn btn-xs btn-ghost border border-base-300 shrink-0">Industries</a>
    @endif
    @if($company->platforms->isNotEmpty())
        <a href="#section-social" data-nav="social" class="cv-nav-link btn btn-xs btn-ghost border border-base-300 shrink-0">Social Platforms</a>
    @endif
</div>