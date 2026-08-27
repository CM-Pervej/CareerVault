import { initDatabaseSearch, initDeleteModal } from "../modular/filter";

export function initPlatformIndex() {
    const search = document.getElementById('platformSearch');
    const container = document.getElementById('platformContainer');
    const pagination = document.getElementById('platformPagination');

    if (!search || !container || !pagination) {
        return;
    }

    initDatabaseSearch({
        input: search,
        container: container,
        pagination: pagination,
        total: 'platformTotal',
        loading: 'platformSearchLoading',
        shortcut: 'platformSearchShortcut',
    });

    initDeleteModal();
}