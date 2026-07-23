export function initCompanyForm() {

    const addButton = document.getElementById('addSocial');
    const container = document.getElementById('socialContainer');

    if (!addButton || !container) {
        return;
    }

    let index = container.querySelectorAll('.social-row').length;

    addButton.addEventListener('click', () => {

        container.insertAdjacentHTML('beforeend', `
            <div class="social-row grid md:grid-cols-2 gap-4 mb-4">

                <input
                    type="text"
                    name="social_links[${index}][platform]"
                    class="input input-bordered"
                    placeholder="Platform">

                <div class="flex gap-2">

                    <input
                        type="url"
                        name="social_links[${index}][url]"
                        class="input input-bordered w-full"
                        placeholder="https://">

                    <button
                        type="button"
                        class="btn btn-error removeSocial">

                        <i class="fa-solid fa-trash"></i>

                    </button>

                </div>

            </div>
        `);

        index++;

    });

    container.addEventListener('click', function (e) {

        const button = e.target.closest('.removeSocial');

        if (!button) {
            return;
        }

        button.closest('.social-row').remove();

    });

}

export function initDeleteModal() {

    const deleteModal = document.getElementById('deleteModal');

    if (!deleteModal) {
        return;
    }

    document.querySelectorAll('.open-delete-modal').forEach(button => {

        button.addEventListener('click', () => {

            document.getElementById('deleteCompanyName').textContent =
                button.dataset.name;

            document.getElementById('deleteForm').action =
                button.dataset.action;

            deleteModal.showModal();

        });

    });

}

// delete-confirmation modal + live search.

export function initCompaniesPage() {
    const deleteModal = document.getElementById('deleteModal');
    const deleteForm = document.getElementById('deleteForm');
    const deleteCompanyName = document.getElementById('deleteCompanyName');

    // Bail out quietly if we're not on the companies page
    if (!deleteModal || !deleteForm || !deleteCompanyName) {
        return;
    }

    // Wire up each delete trigger to populate and open the modal
    document.querySelectorAll('.open-delete-modal').forEach(function (btn) {
        btn.addEventListener('click', function () {
            deleteForm.setAttribute('action', btn.dataset.action);
            deleteCompanyName.textContent = btn.dataset.name;
            deleteModal.showModal();
        });
    });

    // Live search across name + industry
    const searchInput = document.getElementById('companySearch');
    const rows = Array.from(document.querySelectorAll('.company-row'));
    const noResultsRow = document.getElementById('noResultsRow');
    const resultCount = document.getElementById('resultCount');

    function runFilter() {
        const term = searchInput.value.trim().toLowerCase();
        let visible = 0;

        rows.forEach(function (row) {
            const matches = !term
                || row.dataset.name.includes(term)
                || row.dataset.industry.includes(term);

            row.classList.toggle('hidden', !matches);
            if (matches) visible++;
        });

        if (noResultsRow) {
            noResultsRow.classList.toggle('hidden', !(term && visible === 0));
        }

        if (resultCount) {
            resultCount.textContent = term
                ? visible + ' of ' + rows.length + ' companies'
                : '';
        }
    }

    if (searchInput) {
        searchInput.addEventListener('input', runFilter);
    }
}

// document.addEventListener('DOMContentLoaded', initCompaniesPage);
