import { initDatabaseSearch, initDeleteModal } from "../modular/filter";

export function initCountryIndex() {
    const search = document.getElementById('countrySearch');
    const container = document.getElementById('countryContainer');
    const pagination = document.getElementById('countryPagination');

    if (!search || !container || !pagination) {
        return;
    }

    initDatabaseSearch({
        input: search,
        container: container,
        pagination: pagination,
        total: 'countryTotal',
        loading: 'countrySearchLoading',
        shortcut: 'countrySearchShortcut',
    });

    initDeleteModal();
}