export function initCrudPage(entityName) {
    const deleteModal = document.getElementById('deleteModal');
    const deleteForm = document.getElementById('deleteForm');
    const deleteItemName = document.getElementById('deleteItemName');

    if (!deleteModal || !deleteForm || !deleteItemName) return;

    // Delete modal
    document.addEventListener('click', function (event) {
        const deleteButton = event.target.closest('.open-delete-modal');

        if (!deleteButton) return;

        const modal = document.getElementById('deleteModal');
        const deleteForm = document.getElementById('deleteForm');
        const deleteItemName = document.getElementById('deleteItemName');

        if (!modal || !deleteForm) return;

        deleteForm.action = deleteButton.dataset.action;

        if (deleteItemName) {
            deleteItemName.textContent = deleteButton.dataset.name;
        }

        modal.showModal();
    });

    // Live Search
    const searchInput = document.getElementById('searchInput');
    const rows = [...document.querySelectorAll('.crud-row')];
    const noResultsRow = document.getElementById('noResultsRow');
    const resultCount = document.getElementById('resultCount');

    if (!searchInput) return;

    searchInput.addEventListener('input', () => {
        const term = searchInput.value.trim().toLowerCase();
        let visible = 0;

        rows.forEach((row) => {
            const match = !term || row.dataset.name.includes(term);

            row.classList.toggle('hidden', !match);

            if (match) {
                visible++;
            }
        });

        if (noResultsRow) {
            noResultsRow.classList.toggle('hidden', !(term && visible === 0));
        }

        if (resultCount) {
            resultCount.textContent = term
                ? `${visible} of ${rows.length} ${entityName}`
                : '';
        }
    });
}

// Search through DB
export function initDatabaseSearch(config) {

    const {
        input,
        container,
        loading = null,
        url,
        delay = 300
    } = config;

    const searchInput = document.getElementById(input);
    const tableContainer = document.getElementById(container);
    const loadingElement = loading
        ? document.getElementById(loading)
        : null;

    if (!searchInput || !tableContainer) return;

    let timer = null;
    let controller = null;

    function load(url) {

        if (controller) {
            controller.abort();
        }

        controller = new AbortController();
        loadingElement?.classList.remove('hidden');

        fetch(url, {
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'text/html'
            },
            signal: controller.signal
        })
            .then(response => {

                if (!response.ok) {
                    throw new Error('Failed to load data.');
                }

                return response.text();
            })
            .then(html => {
                tableContainer.innerHTML = html;

                window.history.replaceState(
                    {},
                    '',
                    url
                );
            })
            .catch(error => {
                if (error.name !== 'AbortError') {
                    console.error(error);
                }
            })
            .finally(() => {
                loadingElement?.classList.add('hidden');
            });
    }

    // Live Search with AJAX
    searchInput.addEventListener('input', function () {
        clearTimeout(timer);

        timer = setTimeout(() => {
            const urlObject = new URL(url, window.location.origin);
            const search = this.value.trim();

            urlObject.searchParams.delete('page');

            if (search) {
                urlObject.searchParams.set('search', search);
            } else {
                urlObject.searchParams.delete('search');
            }

            load(urlObject.toString());
        }, delay);
    });

    // Pagination
    tableContainer.addEventListener('click', function (event) {
        const link = event.target.closest('a');

        if (!link) return;

        if (link.closest('nav')) {
            event.preventDefault();
            load(link.href);
        }
    });
}