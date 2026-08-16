// resources/js/company/export.js

import {
    copyAndNotify,
    triggerDownload,
    safeFilename,
    csvField,
    showToast
} from './common.js';


function buildVCard(data) {

    const lines = [
        'BEGIN:VCARD',
        'VERSION:3.0',
        'FN:' + data.name,
        'ORG:' + data.name,
        'KIND:org'
    ];


    if (data.website) {
        lines.push(
            'URL;TYPE=website:' + data.website
        );
    }


    if (data.career_page) {
        lines.push(
            'URL;TYPE=careers:' + data.career_page
        );
    }


    (data.emails || []).forEach(e => {

        if (!e || !e.email) return;

        lines.push(
            'EMAIL;TYPE=' +
            (e.email_type || 'work').toUpperCase() +
            ':' +
            e.email
        );
    });


    (data.phones || []).forEach(p => {

        if (!p || !p.phone) return;

        lines.push(
            'TEL;TYPE=' +
            (p.phone_type || 'work').toUpperCase() +
            ':' +
            p.phone
        );
    });


    (data.address || []).forEach(a => {

        if (!a || !a.address) return;

        const flat =
            String(a.address)
                .replace(/\r?\n/g, ' ');

        lines.push(
            'ADR;TYPE=' +
            (a.address_type || 'work').toUpperCase() +
            ':;;' +
            flat +
            ';;;;'
        );
    });


    const noteParts = [];


    if ((data.industries || []).length) {
        noteParts.push(
            'Industries: ' +
            data.industries.join(', ')
        );
    }


    if ((data.countries || []).length) {
        noteParts.push(
            'Countries: ' +
            data.countries.join(', ')
        );
    }


    if ((data.cities || []).length) {
        noteParts.push(
            'Cities: ' +
            data.cities.join(', ')
        );
    }


    if (noteParts.length) {
        lines.push(
            'NOTE:' +
            noteParts.join(' | ')
        );
    }


    lines.push(
        'REV:' +
        new Date().toISOString()
    );

    lines.push('END:VCARD');


    return lines.join('\r\n');
}


function downloadVCard(data) {

    if (!data) {
        return showToast(
            'Export data unavailable',
            'error'
        );
    }


    const ok = triggerDownload(
        buildVCard(data),
        safeFilename(data.name) + '.vcf',
        'text/vcard'
    );


    showToast(
        ok
            ? 'vCard downloaded'
            : 'Could not generate vCard',

        ok
            ? 'success'
            : 'error'
    );
}


function downloadJSON(data) {

    if (!data) {
        return showToast(
            'Export data unavailable',
            'error'
        );
    }


    const ok = triggerDownload(
        JSON.stringify(data, null, 2),
        safeFilename(data.name) + '.json',
        'application/json'
    );


    showToast(
        ok
            ? 'JSON downloaded'
            : 'Could not generate JSON',

        ok
            ? 'success'
            : 'error'
    );
}


function downloadCSV(data) {

    if (!data) {
        return showToast(
            'Export data unavailable',
            'error'
        );
    }


    const rows = [
        ['Type', 'Category', 'Value']
    ];


    (data.emails || []).forEach(e => {
        rows.push([
            'Email',
            e.email_type || '',
            e.email || ''
        ]);
    });


    (data.phones || []).forEach(p => {
        rows.push([
            'Phone',
            p.phone_type || '',
            p.phone || ''
        ]);
    });


    (data.address || []).forEach(a => {
        rows.push([
            'Address',
            a.address_type || '',
            (a.address || '')
                .replace(/\r?\n/g, ' ')
        ]);
    });


    if (data.website) {
        rows.push([
            'Website',
            '',
            data.website
        ]);
    }


    if (data.career_page) {
        rows.push([
            'Career page',
            '',
            data.career_page
        ]);
    }


    if (rows.length === 1) {
        return showToast(
            'No contact data to export',
            'info'
        );
    }


    const csv = rows
        .map(row =>
            row.map(csvField).join(',')
        )
        .join('\r\n');


    const ok = triggerDownload(
        csv,
        safeFilename(data.name) + '_contacts.csv',
        'text/csv'
    );


    showToast(
        ok
            ? 'CSV downloaded'
            : 'Could not generate CSV',

        ok
            ? 'success'
            : 'error'
    );
}


function buildSummaryText(data) {

    const parts = [
        data.name
    ];


    if (data.website) {
        parts.push(
            'Website: ' + data.website
        );
    }


    if (data.career_page) {
        parts.push(
            'Careers: ' + data.career_page
        );
    }


    (data.emails || []).forEach(e => {
        parts.push(
            'Email (' +
            (e.email_type || 'general') +
            '): ' +
            e.email
        );
    });


    (data.phones || []).forEach(p => {
        parts.push(
            'Phone (' +
            (p.phone_type || 'general') +
            '): ' +
            p.phone
        );
    });


    (data.address || []).forEach(a => {
        parts.push(
            'Address (' +
            (a.address_type || 'general') +
            '): ' +
            a.address
        );
    });


    if ((data.industries || []).length) {
        parts.push(
            'Industries: ' +
            data.industries.join(', ')
        );
    }


    if ((data.countries || []).length) {
        parts.push(
            'Countries: ' +
            data.countries.join(', ')
        );
    }


    if ((data.cities || []).length) {
        parts.push(
            'Cities: ' +
            data.cities.join(', ')
        );
    }


    parts.push(window.location.href);


    return parts.join('\n');
}


export function initCompanyExport(data) {

    document.addEventListener('click', function (e) {

        const actionEl =
            e.target.closest('[data-action]');

        if (!actionEl) return;


        switch (
            actionEl.getAttribute('data-action')
        ) {

            case 'download-vcard':
                downloadVCard(data);
                break;


            case 'download-json':
                downloadJSON(data);
                break;


            case 'download-csv':
                downloadCSV(data);
                break;


            case 'copy-summary':

                if (!data) {
                    showToast(
                        'Export data unavailable',
                        'error'
                    );

                    break;
                }

                copyAndNotify(
                    buildSummaryText(data),
                    'Summary copied to clipboard'
                );

                break;


            case 'copy-all-emails':

                if (!data) {
                    showToast(
                        'Export data unavailable',
                        'error'
                    );

                    break;
                }

                copyAndNotify(
                    (data.emails || [])
                        .map(e => e.email)
                        .join('\n'),

                    'All emails copied'
                );

                break;


            case 'copy-all-phones':

                if (!data) {
                    showToast(
                        'Export data unavailable',
                        'error'
                    );

                    break;
                }

                copyAndNotify(
                    (data.phones || [])
                        .map(p => p.phone)
                        .join('\n'),

                    'All phone numbers copied'
                );

                break;


            case 'copy-all-addresses':

                if (!data) {
                    showToast(
                        'Export data unavailable',
                        'error'
                    );

                    break;
                }

                copyAndNotify(
                    (data.address || [])
                        .map(a => a.address)
                        .join('\n\n'),

                    'All addresses copied'
                );

                break;


            case 'print':
                window.print();
                break;


            case 'scroll-top':
                window.scrollTo({
                    top: 0,
                    behavior: 'smooth'
                });
                break;


            default:
                return;
        }


        // Close export dropdown
        const openDropdown =
            actionEl.closest(
                'details.dropdown[open]'
            );

        if (openDropdown) {
            openDropdown.removeAttribute('open');
        }
    });
}