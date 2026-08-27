import { initDatabaseSearch, initDeleteModal } from "../modular/filter";

export function initCompanyIndex() {
    const search = document.getElementById('companySearch');
    const container = document.getElementById('companyContainer');
    const pagination = document.getElementById('companyPagination');

    if (!search || !container || !pagination) {
        return;
    }

    initDatabaseSearch({
        input: search,
        container: container,
        pagination: pagination,
        total: 'companyTotal',
        loading: 'companySearchLoading',
        shortcut: 'companySearchShortcut',
    });

    initDeleteModal();
}