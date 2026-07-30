// function initRepeatable(config) {

//     const container = document.querySelector(config.container);
//     const template = document.querySelector(config.template);
//     const addButton = document.querySelector(config.addButton);

//     if (!container || !template || !addButton) {
//         return;
//     }

//     function updateIndexes() {

//         const items = container.querySelectorAll(config.itemClass);

//         items.forEach((item, index) => {

//             config.fields.forEach(field => {

//                 const element = item.querySelector(field.selector);

//                 if (!element) return;

//                 element.name = `${config.prefix}[${index}][${field.name}]`;

//             });

//             const removeButton = item.querySelector(config.removeButton);

//             if (removeButton) {
//                 removeButton.disabled = items.length === 1;
//             }

//         });

//     }

//     addButton.addEventListener('click', () => {

//         const clone = template.content.cloneNode(true);

//         container.appendChild(clone);

//         updateIndexes();

//     });

//     container.addEventListener('click', (e) => {

//         const removeButton = e.target.closest(config.removeButton);

//         if (!removeButton) return;

//         const items = container.querySelectorAll(config.itemClass);

//         if (items.length === 1) return;

//         removeButton.closest(config.itemClass).remove();

//         updateIndexes();

//     });

//     updateIndexes();

// }

// export function initCompanyForm() {

//     initRepeatable({
//         container: '#emails-container',
//         template: '#email-template',
//         addButton: '#add-email',
//         itemClass: '.email-item',
//         removeButton: '.remove-email',
//         prefix: 'emails',
//         fields: [
//             {
//                 selector: '.email-type',
//                 name: 'email_type'
//             },
//             {
//                 selector: '.email-address',
//                 name: 'email'
//             }
//         ]
//     });

//     initRepeatable({
//         container: '#phones-container',
//         template: '#phone-template',
//         addButton: '#add-phone',
//         itemClass: '.phone-item',
//         removeButton: '.remove-phone',
//         prefix: 'phones',
//         fields: [
//             {
//                 selector: '.phone-type',
//                 name: 'phone_type'
//             },
//             {
//                 selector: '.phone-number',
//                 name: 'phone'
//             }
//         ]
//     });

//     initRepeatable({
//         container: '#addresses-container',
//         template: '#address-template',
//         addButton: '#add-address',
//         itemClass: '.address-item',
//         removeButton: '.remove-address',
//         prefix: 'address',
//         fields: [
//             {
//                 selector: '.address-type',
//                 name: 'address_type'
//             },
//             {
//                 selector: '.address-value',
//                 name: 'address'
//             }
//         ]
//     });

//     initRepeatable({
//         container: '#social-links-container',
//         template: '#social-link-template',
//         addButton: '#add-social-link',
//         itemClass: '.social-link-item',
//         removeButton: '.remove-social-link',
//         prefix: 'social_links',
//         fields: [
//             {
//                 selector: '.social-platform',
//                 name: 'platform'
//             },
//             {
//                 selector: '.social-url',
//                 name: 'url'
//             }
//         ]
//     });

// }

// export function initCompanyPage() {

//     const search = document.getElementById('companySearch');

//     const cards = document.querySelectorAll('.company-card');


//     // Stop if this is not the company index page
//     if (!search && cards.length === 0) {
//         return;
//     }


//     // Live search
//     if (search) {

//         search.addEventListener('keyup', function () {

//             const keyword = this.value.toLowerCase();


//             cards.forEach(card => {

//                 const text = card.innerText.toLowerCase();

//                 card.style.display = text.includes(keyword)
//                     ? ''
//                     : 'none';

//             });

//         });

//     }



//     // Delete modal
//     const deleteModal = document.getElementById('deleteModal');

//     const deleteForm = document.getElementById('deleteForm');

//     const deleteCompanyName = document.getElementById('deleteCompanyName');


//     if (!deleteModal || !deleteForm || !deleteCompanyName) {
//         return;
//     }


//     document.querySelectorAll('.delete-company')
//         .forEach(button => {

//             button.addEventListener('click', function () {

//                 deleteCompanyName.textContent = this.dataset.name;

//                 deleteForm.action = this.dataset.url;

//                 deleteModal.showModal();

//             });

//         });

// }
function initRepeatable(config) {
    const container = document.querySelector(config.container);
    const template = document.querySelector(config.template);
    const addButton = document.querySelector(config.addButton);
    if (!container || !template || !addButton) {
        return;
    }
    function updateIndexes() {
        const items = container.querySelectorAll(config.itemClass);
        items.forEach((item, index) => {
            config.fields.forEach(field => {
                const element = item.querySelector(field.selector);
                if (!element) return;
                element.name = `${config.prefix}[${index}][${field.name}]`;
            });
            const removeButton = item.querySelector(config.removeButton);
            if (removeButton) {
                removeButton.disabled = items.length === 1;
            }
        });
    }
    addButton.addEventListener('click', () => {
        const clone = template.content.cloneNode(true);
        container.appendChild(clone);
        updateIndexes();
    });
    container.addEventListener('click', (e) => {
        const removeButton = e.target.closest(config.removeButton);
        if (!removeButton) return;
        const items = container.querySelectorAll(config.itemClass);
        if (items.length === 1) return;
        removeButton.closest(config.itemClass).remove();
        updateIndexes();
    });
    updateIndexes();
}
export function initCompanyForm() {
    initRepeatable({
        container: '#emails-container',
        template: '#email-template',
        addButton: '#add-email',
        itemClass: '.email-item',
        removeButton: '.remove-email',
        prefix: 'emails',
        fields: [
            {
                selector: '.email-type',
                name: 'email_type'
            },
            {
                selector: '.email-address',
                name: 'email'
            }
        ]
    });
    initRepeatable({
        container: '#phones-container',
        template: '#phone-template',
        addButton: '#add-phone',
        itemClass: '.phone-item',
        removeButton: '.remove-phone',
        prefix: 'phones',
        fields: [
            {
                selector: '.phone-type',
                name: 'phone_type'
            },
            {
                selector: '.phone-number',
                name: 'phone'
            }
        ]
    });
    initRepeatable({
        container: '#addresses-container',
        template: '#address-template',
        addButton: '#add-address',
        itemClass: '.address-item',
        removeButton: '.remove-address',
        prefix: 'address',
        fields: [
            {
                selector: '.address-type',
                name: 'address_type'
            },
            {
                selector: '.address-value',
                name: 'address'
            }
        ]
    });
    initRepeatable({
        container: '#social-links-container',
        template: '#social-link-template',
        addButton: '#add-social-link',
        itemClass: '.social-link-item',
        removeButton: '.remove-social-link',
        prefix: 'social_links',
        fields: [
            {
                selector: '.social-platform',
                name: 'platform'
            },
            {
                selector: '.social-url',
                name: 'url'
            }
        ]
    });
}
export function initCompanyPage() {
    const search = document.getElementById('companySearch');
    const cards = document.querySelectorAll('.company-card');
    // Stop if this is not the company index page
    if (!search && cards.length === 0) {
        return;
    }
    // Live search
    if (search) {
        search.addEventListener('keyup', function () {
            const keyword = this.value.toLowerCase();
            cards.forEach(card => {
                // Use the full data-search text (name + all countries + all industries)
                // instead of innerText, since the row only visually shows one tag
                // plus a "+N" badge and hides the rest.
                const text = (card.dataset.search || card.innerText).toLowerCase();
                card.style.display = text.includes(keyword)
                    ? ''
                    : 'none';
            });
        });
    }
    // Delete modal
    const deleteModal = document.getElementById('deleteModal');
    const deleteForm = document.getElementById('deleteForm');
    const deleteCompanyName = document.getElementById('deleteCompanyName');
    if (!deleteModal || !deleteForm || !deleteCompanyName) {
        return;
    }
    document.querySelectorAll('.delete-company')
        .forEach(button => {
            button.addEventListener('click', function () {
                deleteCompanyName.textContent = this.dataset.name;
                deleteForm.action = this.dataset.url;
                deleteModal.showModal();
            });
        });
}