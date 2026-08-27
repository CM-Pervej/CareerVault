import { initDatabaseSearch, initDeleteModal } from "../modular/filter";

export function initIndustryIndex() {
    const search = document.getElementById('industrySearch');
    const container = document.getElementById('industryContainer');
    const pagination = document.getElementById('industryPagination');

    if (!search || !container || !pagination) {
        return;
    }

    initDatabaseSearch({
        input: search,
        container: container,
        pagination: pagination,
        total: 'industryTotal',
        loading: 'industrySearchLoading',
        shortcut: 'industrySearchShortcut',
    });

    initDeleteModal();
}