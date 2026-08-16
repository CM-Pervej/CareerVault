// resources/js/company/index.js

export function initCompanyIndex() {
    const search = document.getElementById('companySearch');
    const cards = document.querySelectorAll('.company-card');

    // Not the company index page
    if (!search && cards.length === 0) {
        return;
    }


    // --------------------------------------------------
    // Live Search
    // --------------------------------------------------

    if (search) {
        search.addEventListener('keyup', function () {
            const keyword = this.value.toLowerCase();

            cards.forEach(card => {
                const text = (
                    card.dataset.search ||
                    card.innerText
                ).toLowerCase();

                card.style.display = text.includes(keyword)
                    ? ''
                    : 'none';
            });
        });
    }


    // --------------------------------------------------
    // Delete Modal
    // --------------------------------------------------

    const deleteModal =
        document.getElementById('deleteModal');

    const deleteForm =
        document.getElementById('deleteForm');

    const deleteCompanyName =
        document.getElementById('deleteCompanyName');


    if (
        !deleteModal ||
        !deleteForm ||
        !deleteCompanyName
    ) {
        return;
    }


    document
        .querySelectorAll('.delete-company')
        .forEach(button => {

            button.addEventListener('click', function () {

                deleteCompanyName.textContent =
                    this.dataset.name;

                deleteForm.action =
                    this.dataset.url;

                deleteModal.showModal();
            });
        });
}