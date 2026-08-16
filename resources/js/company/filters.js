export function initCompanyFilters() {
    const searchInput = document.getElementById('companySearch');
    const companyContainer = document.getElementById('companyContainer');
    const companyPagination = document.getElementById('companyPagination');
    const companyTotal = document.getElementById('companyTotal');
    const loadingIndicator = document.getElementById('companySearchLoading');
    const searchShortcut = document.getElementById('companySearchShortcut');

    // This module should only run on the Companies page.
    if (!searchInput || !companyContainer || !companyPagination) return;

    let debounceTimer = null;
    let activeController = null;

    const companiesUrl = searchInput.dataset.url || window.location.pathname;

    // Show/hide search loading state.
    function setLoading(loading) {
        if (loading) {
            loadingIndicator?.classList.remove('hidden');
            searchShortcut?.classList.add('hidden');
            searchInput.classList.add('opacity-70');
        } else {
            loadingIndicator?.classList.add('hidden');

            if (document.activeElement !== searchInput) {
                searchShortcut?.classList.remove('hidden');
            }

            searchInput.classList.remove('opacity-70');
        }
    }

    // Fetch filtered companies from the database.
    async function fetchCompanies(url = null) {
        const search = searchInput.value.trim();

        const requestUrl = new URL(
            url || companiesUrl,
            window.location.origin
        );

        // Add/remove search parameter.
        if (search) {
            requestUrl.searchParams.set('search', search);
        } else {
            requestUrl.searchParams.delete('search');
        }

        // A new search always starts from page 1.
        if (!url) {
            requestUrl.searchParams.delete('page');
        }

        //  Cancel previous request
        if (activeController) {
            activeController.abort();
        }

        activeController = new AbortController();
        setLoading(true);

        try {
            const response = await fetch(requestUrl.toString(), {
                method: 'GET',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json',
                },

                signal: activeController.signal,
            });

            if (!response.ok) {
                throw new Error(`Request failed with status ${response.status}`);
            }

            const data = await response.json();

            if (!data.html) {
                throw new Error('Invalid response received from the server.');
            }

            // Convert returned Blade HTML into a document, so we can extract the required sections.
            const parser = new DOMParser();
            const responseDocument = parser.parseFromString(data.html,'text/html');
            const newCompanyContainer = responseDocument.querySelector('#companyContainer');
            const newCompanyPagination = responseDocument.querySelector('#companyPagination');
            const newCompanyTotal = responseDocument.querySelector('#companyTotal');

            if (!newCompanyContainer) {
                throw new Error('Company results could not be found.');
            }

            // Replace table rows.
            companyContainer.innerHTML = newCompanyContainer.innerHTML;

            // Replace pagination.
            if (newCompanyPagination) {
                companyPagination.innerHTML = newCompanyPagination.innerHTML;
            }

            // Update total company count.
            if (newCompanyTotal && companyTotal) {
                companyTotal.textContent = newCompanyTotal.textContent.trim();
            }

            // Update browser URL without page reload.
            window.history.replaceState({}, '', requestUrl.toString());
        } catch (error) {

            // AbortError is expected when another request, replaces the current request.
            if (error.name === 'AbortError') return;

            console.error('Company filter error:', error);

            const columnCount = document.querySelectorAll('#companyContainer' + ' ~ *').length;

            companyContainer.innerHTML = `
                <tr>
                    <td colspan="6" class="text-center py-14">
                        <i class="fa-solid fa-triangle-exclamation text-2xl text-error opacity-70 mb-3 block"></i>
                        <p class="cv-title text-lg">Unable to load companies</p>
                        <p class="text-sm opacity-50">Please try again.</p>
                    </td>
                </tr>
            `;

            console.error('Column count:', columnCount);

        } finally {
            setLoading(false);

            if (activeController) {
                activeController = null;
            }
        }
    }

    // Live database search. Wait 300ms after the user stops typing before sending the AJAX request.
    searchInput.addEventListener('input', () => {
        clearTimeout(debounceTimer);

        debounceTimer = setTimeout(() => {
            fetchCompanies();
        }, 300);
    });

    // AJAX pagination
    companyPagination.addEventListener('click', (event) => {
        const link = event.target.closest('a');

        if (!link) return;

        event.preventDefault();

        fetchCompanies(link.href);
    });

    /**
     * "/" keyboard shortcut.
     *
     * Press "/" anywhere on the page to focus the
     * company search input.
     */
    document.addEventListener('keydown', (event) => {
        if (
            event.key !== '/' ||
            event.ctrlKey ||
            event.metaKey ||
            event.altKey
        ) {
            return;
        }

        const target = event.target;

        if (
            target instanceof HTMLInputElement ||
            target instanceof HTMLTextAreaElement ||
            target instanceof HTMLSelectElement ||
            target.isContentEditable
        ) {
            return;
        }

        event.preventDefault();

        searchInput.focus();
    });

    // Hide keyboard shortcut while search input is focused.
    searchInput.addEventListener('focus', () => {
        searchShortcut?.classList.add('hidden');
    });

    // Show keyboard shortcut when search input loses focus and is empty.
    searchInput.addEventListener('blur', () => {
        if (!searchInput.value.trim()) {
            searchShortcut?.classList.remove('hidden');
        }
    });

    // delete 
    document.addEventListener('click', event => {
        const deleteButton = event.target.closest('.delete-company');

        if (!deleteButton) return;

        const deleteModal = document.getElementById('deleteModal');
        const deleteForm = document.getElementById('deleteForm');
        const deleteCompanyName = document.getElementById('deleteCompanyName');

        if (!deleteModal || !deleteForm || !deleteCompanyName) return;

        const url = deleteButton.dataset.url;
        const name = deleteButton.dataset.name;

        if (!url) return;

        deleteForm.action = url;
        deleteCompanyName.textContent = name || 'this company';

        deleteModal.showModal();
    });
}