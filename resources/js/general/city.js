import { initDatabaseSearch, initDeleteModal } from "../modular/filter";

export function initCityIndex() {
    initDeleteModal();
    
    const search = document.getElementById('citySearch');
    const container = document.getElementById('cityContainer');
    const pagination = document.getElementById('cityPagination');

    if (!search || !container || !pagination) {
        return;
    }

    initDatabaseSearch({
        input: search,
        container: container,
        pagination: pagination,
        total: 'cityTotal',
        loading: 'citySearchLoading',
        shortcut: 'citySearchShortcut',
    });
}