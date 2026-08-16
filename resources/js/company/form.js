// resources/js/company/form.js

import { initRepeatable } from './common.js';

export function initCompanyForm() {

    // Emails
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


    // Phones
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


    // Addresses
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


    // Social links
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