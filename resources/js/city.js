/**
 * city.js
 * ---------------------------------------------------------------------------
 * Powers the Cities page (country picker + city browser).
 * Import this into resources/js/app.js so it ships with the main bundle:
 *
 *     // resources/js/app.js
 *     import './city.js';
 *
 * Everything below is scoped and guards itself against missing elements,
 * so it's safe to include on pages that don't have this markup.
 * ---------------------------------------------------------------------------
 */

document.addEventListener('DOMContentLoaded', function () {
    const countryTable = document.getElementById('countryTable');
    if (!countryTable) return; // Not on the cities page, bail out.

    const countryListItems = document.getElementById('countryListItems');
    const countryGroups = document.querySelectorAll('.country-group');
    const citySearchInput = document.querySelector('.city-search-input');
    const selectedCountryLabel = document.getElementById('selectedCountryLabel');
    const selectedCountryHint = document.getElementById('selectedCountryHint');
    const selectedCountryFlag = document.getElementById('selectedCountryFlag');
    const selectCountryMessage = document.getElementById('selectCountryMessage');
    const cityResultCount = document.getElementById('cityResultCount');

    const countryPanel = document.getElementById('countryPanel');
    const cityPanel = document.getElementById('cityPanel');
    const backToCountriesBtn = document.getElementById('backToCountries');
    const countryIdSelect = document.getElementById('countryIdSelect');
    const countrySearchInput = document.getElementById('searchInput');
    const countryResultCount = document.getElementById('resultCount');
    const countryNoResults = document.getElementById('countryNoResults');
    const clearCountrySearch = document.getElementById('clearCountrySearch');
    const clearCitySearch = document.getElementById('clearCitySearch');
    const sortButtons = document.querySelectorAll('.sort-btn');
    const toast = document.getElementById('cvToast');

    const LAST_COUNTRY_KEY = 'cv_last_country_id';
    let selectedCountryId = null;

    // -------------------------------------------------------------------
    // Helpers
    // -------------------------------------------------------------------

    function debounce(fn, delay) {
        let timer;
        return function (...args) {
            clearTimeout(timer);
            timer = setTimeout(() => fn.apply(this, args), delay);
        };
    }

    /** Store the original display text once so highlighting can be reset. */
    function cacheOriginalText(el) {
        if (el && el.dataset.original === undefined) {
            el.dataset.original = el.textContent.trim();
        }
    }

    /** Wrap the matched substring of an element's text in <mark>, or reset it. */
    function highlightMatch(el, term) {
        if (!el) return;
        cacheOriginalText(el);
        const original = el.dataset.original;

        if (!term) {
            el.textContent = original;
            return;
        }

        const idx = original.toLowerCase().indexOf(term);
        if (idx === -1) {
            el.textContent = original;
            return;
        }

        const before = original.slice(0, idx);
        const match = original.slice(idx, idx + term.length);
        const after = original.slice(idx + term.length);

        el.textContent = '';
        el.append(document.createTextNode(before));

        const mark = document.createElement('mark');
        mark.className = 'bg-amber-200/80 text-slate-900 rounded-sm px-0.5';
        mark.textContent = match;
        el.appendChild(mark);

        el.append(document.createTextNode(after));
    }

    function showToast(message) {
        if (!toast) return;
        toast.textContent = message;
        toast.classList.remove('hidden');
        requestAnimationFrame(() => toast.classList.add('opacity-100'));

        clearTimeout(showToast._t);
        showToast._t = setTimeout(() => {
            toast.classList.add('hidden');
        }, 1800);
    }

    // -------------------------------------------------------------------
    // Country selection
    // -------------------------------------------------------------------

    function selectCountry(row) {
        if (!row) return;
        selectedCountryId = row.dataset.countryId;
        localStorage.setItem(LAST_COUNTRY_KEY, selectedCountryId);

        // Hide all city groups, show the matching one
        countryGroups.forEach((group) => group.classList.add('hidden'));

        const selectedGroup = document.querySelector(
            '.country-group[data-country-id="' + selectedCountryId + '"]'
        );

        if (selectedGroup) {
            selectedGroup.classList.remove('hidden');
        }

        if (selectCountryMessage) {
            selectCountryMessage.classList.add('hidden');
        }

        // Country name (bug fix: was previously matching the row's index
        // number, since that span also carried the `font-medium` class)
        const countryName = row.querySelector('.country-name')?.textContent.trim() || '';

        if (selectedCountryLabel) {
            selectedCountryLabel.textContent = countryName;
        }

        // Mirror the flag next to the city panel title
        if (selectedCountryFlag) {
            const flagImg = row.querySelector('img');
            selectedCountryFlag.innerHTML = flagImg
                ? `<img src="${flagImg.src}" alt="${countryName} flag" class="w-full h-full object-cover">`
                : '';
            selectedCountryFlag.classList.toggle('hidden', !flagImg);
        }

        if (selectedCountryHint) {
            const cityCount = selectedGroup?.querySelectorAll('.city-row').length || 0;
            selectedCountryHint.textContent = `${cityCount} ${cityCount === 1 ? 'city' : 'cities'}`;
        }

        // Enable + reset city search
        if (citySearchInput) {
            citySearchInput.disabled = false;
            citySearchInput.value = '';
        }
        if (clearCitySearch) {
            clearCitySearch.classList.add('hidden');
        }

        // Reset city rows for the newly selected group
        if (selectedGroup) {
            selectedGroup.querySelectorAll('.city-row').forEach((cityRow) => {
                cityRow.classList.remove('hidden');
                const nameEl = cityRow.querySelector('.city-name');
                if (nameEl) highlightMatch(nameEl, '');
            });

            const noResults = selectedGroup.querySelector('.city-no-results');
            if (noResults) noResults.classList.add('hidden');
        }

        if (cityResultCount) {
            cityResultCount.textContent = '';
        }

        // Highlight the selected row, clear the rest
        document.querySelectorAll('.country-card').forEach((countryRow) => {
            countryRow.classList.remove('bg-indigo-50', 'border-indigo-200');
            countryRow.removeAttribute('aria-current');
        });
        row.classList.add('bg-indigo-50');
        row.setAttribute('aria-current', 'true');

        // Sync the "Add City" form with whatever country is being browsed
        if (countryIdSelect) {
            countryIdSelect.value = selectedCountryId;
        }
    }

    function bindCountryRow(row) {
        row.addEventListener('click', function () {
            selectCountry(this);

            // Mobile drill-in: swap to the city panel, offer a way back
            if (window.innerWidth < 1024) {
                if (countryPanel) countryPanel.classList.add('hidden');
                if (cityPanel) {
                    cityPanel.classList.remove('hidden');
                    cityPanel.scrollIntoView({ behavior: 'smooth', block: 'start' });
                }
                if (backToCountriesBtn) backToCountriesBtn.classList.remove('hidden');
            }
        });

        // Keyboard accessibility: Enter / Space activates the row
        row.addEventListener('keydown', function (e) {
            if (e.key === 'Enter' || e.key === ' ') {
                e.preventDefault();
                this.click();
            }
        });
    }

    document.querySelectorAll('.country-card').forEach(bindCountryRow);

    // -------------------------------------------------------------------
    // Back to countries (mobile drill-in)
    // -------------------------------------------------------------------

    if (backToCountriesBtn) {
        backToCountriesBtn.addEventListener('click', function () {
            if (countryPanel) countryPanel.classList.remove('hidden');
            if (cityPanel) cityPanel.classList.add('hidden');
            countryPanel?.scrollIntoView({ behavior: 'smooth', block: 'start' });
        });
    }

    // Recover from a mid drill-in state when the viewport grows past `lg`
    window.addEventListener('resize', debounce(function () {
        if (window.innerWidth >= 1024) {
            countryPanel?.classList.remove('hidden');
            cityPanel?.classList.remove('hidden');
        }
    }, 150));

    // -------------------------------------------------------------------
    // Country search (with highlighting + debounce)
    // -------------------------------------------------------------------

    if (countrySearchInput) {
        const runCountrySearch = debounce(function () {
            const term = this.value.trim().toLowerCase();
            let visible = 0;

            document.querySelectorAll('.country-card').forEach((row) => {
                const countryName = row.dataset.name || '';
                const match = !term || countryName.includes(term);
                row.classList.toggle('hidden', !match);

                const nameEl = row.querySelector('.country-name');
                if (nameEl) highlightMatch(nameEl, match ? term : '');

                if (match) visible++;
            });

            if (countryResultCount) {
                countryResultCount.textContent = term
                    ? `${visible} of ${document.querySelectorAll('.country-card').length} countries`
                    : '';
            }

            if (countryNoResults) {
                countryNoResults.classList.toggle('hidden', !(term && visible === 0));
            }

            if (clearCountrySearch) {
                clearCountrySearch.classList.toggle('hidden', !term);
            }
        }, 120);

        countrySearchInput.addEventListener('input', runCountrySearch);
    }

    // -------------------------------------------------------------------
    // City search (with highlighting + debounce)
    // -------------------------------------------------------------------

    if (citySearchInput) {
        const runCitySearch = debounce(function () {
            if (!selectedCountryId) return;

            const term = this.value.trim().toLowerCase();
            const selectedGroup = document.querySelector(
                '.country-group[data-country-id="' + selectedCountryId + '"]'
            );
            if (!selectedGroup) return;

            const cityRows = selectedGroup.querySelectorAll('.city-row');
            const noResults = selectedGroup.querySelector('.city-no-results');
            let visible = 0;

            cityRows.forEach((row) => {
                const cityName = row.dataset.name || '';
                const match = !term || cityName.includes(term);
                row.classList.toggle('hidden', !match);

                const nameEl = row.querySelector('.city-name');
                if (nameEl) highlightMatch(nameEl, match ? term : '');

                if (match) visible++;
            });

            if (cityResultCount) {
                cityResultCount.textContent = term
                    ? `${visible} of ${cityRows.length} cities`
                    : `${cityRows.length} cities`;
            }

            if (noResults) {
                noResults.classList.toggle('hidden', !(term && visible === 0));
            }

            if (clearCitySearch) {
                clearCitySearch.classList.toggle('hidden', !term);
            }
        }, 120);

        citySearchInput.addEventListener('input', runCitySearch);
    }

    // -------------------------------------------------------------------
    // Clear-search buttons
    // -------------------------------------------------------------------

    if (clearCountrySearch) {
        clearCountrySearch.addEventListener('click', function () {
            countrySearchInput.value = '';
            countrySearchInput.dispatchEvent(new Event('input'));
            countrySearchInput.focus();
        });
    }

    if (clearCitySearch) {
        clearCitySearch.addEventListener('click', function () {
            if (citySearchInput.disabled) return;
            citySearchInput.value = '';
            citySearchInput.dispatchEvent(new Event('input'));
            citySearchInput.focus();
        });
    }

    // -------------------------------------------------------------------
    // Sort toggle (A–Z / Most cities)
    // -------------------------------------------------------------------

    if (sortButtons.length && countryListItems) {
        sortButtons.forEach((btn) => {
            btn.addEventListener('click', function () {
                sortButtons.forEach((b) => {
                    b.classList.remove('active-sort-btn', 'bg-white/20', 'text-white');
                    b.classList.add('text-indigo-100/70');
                });
                this.classList.add('active-sort-btn', 'bg-white/20', 'text-white');
                this.classList.remove('text-indigo-100/70');

                const mode = this.dataset.sort;
                const rows = Array.from(countryListItems.querySelectorAll('.country-card'));

                rows.sort((a, b) => {
                    if (mode === 'count') {
                        return Number(b.dataset.cityCount) - Number(a.dataset.cityCount);
                    }
                    return a.dataset.name.localeCompare(b.dataset.name);
                });

                rows.forEach((row) => countryListItems.appendChild(row));
            });
        });
    }

    // -------------------------------------------------------------------
    // Copy slug to clipboard
    // -------------------------------------------------------------------

    document.querySelectorAll('.copy-slug-btn').forEach((btn) => {
        btn.addEventListener('click', function () {
            const slug = this.dataset.slug || '';
            if (!slug) return;

            navigator.clipboard?.writeText(slug).then(() => {
                showToast(`Copied "${slug}"`);
            }).catch(() => {
                showToast('Could not copy slug');
            });
        });
    });

    // -------------------------------------------------------------------
    // Escape key: clear focused search, or step back on mobile
    // -------------------------------------------------------------------

    document.addEventListener('keydown', function (e) {
        if (e.key !== 'Escape') return;

        if (document.activeElement === citySearchInput && citySearchInput.value) {
            clearCitySearch?.click();
            return;
        }

        if (document.activeElement === countrySearchInput && countrySearchInput.value) {
            clearCountrySearch?.click();
            return;
        }

        if (window.innerWidth < 1024 && cityPanel && !cityPanel.classList.contains('hidden')) {
            backToCountriesBtn?.click();
        }
    });

    // -------------------------------------------------------------------
    // Restore the last-viewed country on desktop (nice for repeat visits)
    // -------------------------------------------------------------------

    const lastCountryId = localStorage.getItem(LAST_COUNTRY_KEY);
    if (lastCountryId && window.innerWidth >= 1024) {
        const savedRow = document.querySelector(
            '.country-card[data-country-id="' + lastCountryId + '"]'
        );
        if (savedRow) selectCountry(savedRow);
    }
});