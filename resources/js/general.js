export function initCrudPage(entityName) {
    const deleteModal = document.getElementById('deleteModal');
    const deleteForm = document.getElementById('deleteForm');
    const deleteItemName = document.getElementById('deleteItemName');

    if (!deleteModal || !deleteForm || !deleteItemName) {
        return;
    }

    // Delete modal
    document.querySelectorAll('.open-delete-modal').forEach((btn) => {
        btn.addEventListener('click', () => {
            deleteForm.action = btn.dataset.action;
            deleteItemName.textContent = btn.dataset.name;
            deleteModal.showModal();
        });
    });

    // Search
    const searchInput = document.getElementById('searchInput');
    const rows = [...document.querySelectorAll('.crud-row')];
    const noResultsRow = document.getElementById('noResultsRow');
    const resultCount = document.getElementById('resultCount');

    if (!searchInput) {
        return;
    }

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