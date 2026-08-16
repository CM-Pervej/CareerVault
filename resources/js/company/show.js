// resources/js/company/show.js

import { initClipboard } from './clipboard.js';
import { initCompanyExport } from './export.js';
import { initCompanyShare } from './share.js';
import { initCompanyQR } from './qr.js';
// import { initCompanyFilters } from './filters.js';
import { initCompanyNavigation } from './navigation.js';


function getCompanyData() {

    const dataEl =
        document.getElementById(
            'cv-company-data'
        );

    if (!dataEl) return null;

    try {
        return JSON.parse(
            dataEl.textContent
        );
    } catch (error) {
        console.error('company.js: could not parse company data JSON', error);

        return null;
    }
}

export function initCompanyShow() {
    const root = document.getElementById('cv-company-root');

    // Not Company Show page
    if (!root) return;

    const data = getCompanyData();

    if (!data) {
        console.warn(
            'company.js: #cv-company-data payload missing or invalid; export actions will be disabled.'
        );
    }

    initClipboard();
    initCompanyExport(data);
    initCompanyShare(data);
    initCompanyQR();
    // initCompanyFilters();
    initCompanyNavigation();
    initCompanyKeyboard();
}

function initCompanyKeyboard() {
    document.addEventListener(
        'keydown',
        function (e) {
            if (e.key !== 'Escape') return;

            const modal = document.getElementById('cv-qr-modal');

            if (modal && modal.open) {
                modal.close();
            }
        }
    );
}