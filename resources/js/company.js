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

// show company
/**
 * Company Profile page behaviour.
 *
 * Import this from resources/js/app.js:
 *   import './company.js';
 *
 * The module is fully self-guarding: on pages that don't contain
 * #cv-company-root it does nothing, so it's safe to bundle globally.
 */

(function () {
    'use strict';

    /** Root element + JSON payload are both optional; bail out cleanly. */
    function getCompanyData() {
        const dataEl = document.getElementById('cv-company-data');
        if (!dataEl) return null;
        try {
            return JSON.parse(dataEl.textContent);
        } catch (err) {
            console.error('company.js: could not parse company data JSON', err);
            return null;
        }
    }

    function init() {
        const root = document.getElementById('cv-company-root');
        if (!root) return; // Not on the company profile page.

        const data = getCompanyData();
        if (!data) {
            console.warn('company.js: #cv-company-data payload missing or invalid; export actions will be disabled.');
        }

        initToast();
        initClipboard(data);
        initExports(data);
        initShare(data);
        initQrModal(data);
        initFilters();
        initScrollSpyAndTopButton();
        initPrint();
    }

    // ------------------------------------------------------------------
    // Toast
    // ------------------------------------------------------------------
    let toastTimer = null;

    function showToast(message, variant) {
        const toast = document.getElementById('cv-toast');
        const alertBox = document.getElementById('cv-toast-alert');
        const icon = document.getElementById('cv-toast-icon');
        const text = document.getElementById('cv-toast-message');
        if (!toast || !alertBox || !text) return;

        variant = variant || 'success';
        alertBox.classList.remove('alert-success', 'alert-error', 'alert-info');
        alertBox.classList.add(variant === 'error' ? 'alert-error' : variant === 'info' ? 'alert-info' : 'alert-success');

        if (icon) {
            icon.className = 'fa-solid ' + (
                variant === 'error' ? 'fa-circle-exclamation'
                    : variant === 'info' ? 'fa-circle-info'
                        : 'fa-circle-check'
            );
        }

        text.textContent = message;
        toast.classList.remove('hidden');
        clearTimeout(toastTimer);
        toastTimer = setTimeout(() => toast.classList.add('hidden'), 2200);
    }

    function initToast() {
        // Nothing to wire up eagerly; showToast is used by every feature below.
    }

    // ------------------------------------------------------------------
    // Clipboard (with fallback for non-secure contexts / older browsers)
    // ------------------------------------------------------------------
    function copyText(text) {
        if (navigator.clipboard && window.isSecureContext) {
            return navigator.clipboard.writeText(text);
        }
        return new Promise((resolve, reject) => {
            const ta = document.createElement('textarea');
            ta.value = text;
            ta.setAttribute('readonly', '');
            ta.style.position = 'fixed';
            ta.style.left = '-9999px';
            document.body.appendChild(ta);
            ta.focus();
            ta.select();
            try {
                document.execCommand('copy');
                resolve();
            } catch (err) {
                reject(err);
            } finally {
                document.body.removeChild(ta);
            }
        });
    }

    function copyAndNotify(text, successMessage) {
        if (!text) {
            showToast('Nothing to copy', 'info');
            return;
        }
        copyText(text)
            .then(() => showToast(successMessage, 'success'))
            .catch(() => showToast('Could not copy — please copy manually', 'error'));
    }

    function initClipboard() {
        // Individual copy-icon buttons (website, career page, each email/phone/address row).
        document.addEventListener('click', function (e) {
            const copyBtn = e.target.closest('.copy-btn');
            if (!copyBtn) return;

            const text = copyBtn.getAttribute('data-copy');
            if (!text) return;

            copyText(text).then(function () {
                const icon = copyBtn.querySelector('i');
                if (icon) {
                    icon.classList.remove('fa-copy');
                    icon.classList.add('fa-check');
                }
                copyBtn.classList.add('text-success');
                showToast('Copied to clipboard', 'success');
                setTimeout(function () {
                    if (icon) {
                        icon.classList.remove('fa-check');
                        icon.classList.add('fa-copy');
                    }
                    copyBtn.classList.remove('text-success');
                }, 1500);
            }).catch(() => showToast('Could not copy — please copy manually', 'error'));
        });
    }

    // ------------------------------------------------------------------
    // File export helpers
    // ------------------------------------------------------------------
    function triggerDownload(content, filename, mime) {
        try {
            const blob = new Blob([content], { type: mime });
            const url = URL.createObjectURL(blob);
            const a = document.createElement('a');
            a.href = url;
            a.download = filename;
            document.body.appendChild(a);
            a.click();
            document.body.removeChild(a);
            // Give the browser a tick to start the download before revoking.
            setTimeout(() => URL.revokeObjectURL(url), 1000);
            return true;
        } catch (err) {
            console.error('company.js: download failed', err);
            return false;
        }
    }

    function safeFilename(name) {
        return (name || 'company').toString().trim().replace(/[^a-z0-9]+/gi, '_').replace(/^_+|_+$/g, '') || 'company';
    }

    /** Escapes a value for a single CSV field (RFC 4180-ish). */
    function csvField(value) {
        const str = value === null || value === undefined ? '' : String(value);
        if (/[",\n]/.test(str)) {
            return '"' + str.replace(/"/g, '""') + '"';
        }
        return str;
    }

    function buildVCard(data) {
        const lines = ['BEGIN:VCARD', 'VERSION:3.0', 'FN:' + data.name, 'ORG:' + data.name, 'KIND:org'];

        if (data.website) lines.push('URL;TYPE=website:' + data.website);
        if (data.career_page) lines.push('URL;TYPE=careers:' + data.career_page);

        (data.emails || []).forEach(e => {
            if (!e || !e.email) return;
            lines.push('EMAIL;TYPE=' + (e.email_type || 'work').toUpperCase() + ':' + e.email);
        });

        (data.phones || []).forEach(p => {
            if (!p || !p.phone) return;
            lines.push('TEL;TYPE=' + (p.phone_type || 'work').toUpperCase() + ':' + p.phone);
        });

        (data.address || []).forEach(a => {
            if (!a || !a.address) return;
            const flat = String(a.address).replace(/\r?\n/g, ' ');
            lines.push('ADR;TYPE=' + (a.address_type || 'work').toUpperCase() + ':;;' + flat + ';;;;');
        });

        const noteParts = [];
        if ((data.industries || []).length) noteParts.push('Industries: ' + data.industries.join(', '));
        if ((data.countries || []).length) noteParts.push('Countries: ' + data.countries.join(', '));
        if ((data.cities || []).length) noteParts.push('Cities: ' + data.cities.join(', '));
        if (noteParts.length) lines.push('NOTE:' + noteParts.join(' | '));

        lines.push('REV:' + new Date().toISOString());
        lines.push('END:VCARD');
        return lines.join('\r\n');
    }

    function downloadVCard(data) {
        if (!data) return showToast('Export data unavailable', 'error');
        const ok = triggerDownload(buildVCard(data), safeFilename(data.name) + '.vcf', 'text/vcard');
        showToast(ok ? 'vCard downloaded' : 'Could not generate vCard', ok ? 'success' : 'error');
    }

    function downloadJSON(data) {
        if (!data) return showToast('Export data unavailable', 'error');
        const ok = triggerDownload(JSON.stringify(data, null, 2), safeFilename(data.name) + '.json', 'application/json');
        showToast(ok ? 'JSON downloaded' : 'Could not generate JSON', ok ? 'success' : 'error');
    }

    function downloadCSV(data) {
        if (!data) return showToast('Export data unavailable', 'error');

        const rows = [['Type', 'Category', 'Value']];
        (data.emails || []).forEach(e => rows.push(['Email', e.email_type || '', e.email || '']));
        (data.phones || []).forEach(p => rows.push(['Phone', p.phone_type || '', p.phone || '']));
        (data.address || []).forEach(a => rows.push(['Address', a.address_type || '', (a.address || '').replace(/\r?\n/g, ' ')]));
        if (data.website) rows.push(['Website', '', data.website]);
        if (data.career_page) rows.push(['Career page', '', data.career_page]);

        if (rows.length === 1) {
            return showToast('No contact data to export', 'info');
        }

        const csv = rows.map(row => row.map(csvField).join(',')).join('\r\n');
        const ok = triggerDownload(csv, safeFilename(data.name) + '_contacts.csv', 'text/csv');
        showToast(ok ? 'CSV downloaded' : 'Could not generate CSV', ok ? 'success' : 'error');
    }

    function buildSummaryText(data) {
        const parts = [data.name];
        if (data.website) parts.push('Website: ' + data.website);
        if (data.career_page) parts.push('Careers: ' + data.career_page);
        (data.emails || []).forEach(e => parts.push('Email (' + (e.email_type || 'general') + '): ' + e.email));
        (data.phones || []).forEach(p => parts.push('Phone (' + (p.phone_type || 'general') + '): ' + p.phone));
        (data.address || []).forEach(a => parts.push('Address (' + (a.address_type || 'general') + '): ' + a.address));
        if ((data.industries || []).length) parts.push('Industries: ' + data.industries.join(', '));
        if ((data.countries || []).length) parts.push('Countries: ' + data.countries.join(', '));
        if ((data.cities || []).length) parts.push('Cities: ' + data.cities.join(', '));
        parts.push(window.location.href);
        return parts.join('\n');
    }

    function initExports(data) {
        document.addEventListener('click', function (e) {
            const actionEl = e.target.closest('[data-action]');
            if (!actionEl) return;

            switch (actionEl.getAttribute('data-action')) {
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
                    if (!data) { showToast('Export data unavailable', 'error'); break; }
                    copyAndNotify(buildSummaryText(data), 'Summary copied to clipboard');
                    break;
                case 'copy-all-emails':
                    if (!data) { showToast('Export data unavailable', 'error'); break; }
                    copyAndNotify((data.emails || []).map(e => e.email).join('\n'), 'All emails copied');
                    break;
                case 'copy-all-phones':
                    if (!data) { showToast('Export data unavailable', 'error'); break; }
                    copyAndNotify((data.phones || []).map(p => p.phone).join('\n'), 'All phone numbers copied');
                    break;
                case 'copy-all-addresses':
                    if (!data) { showToast('Export data unavailable', 'error'); break; }
                    copyAndNotify((data.address || []).map(a => a.address).join('\n\n'), 'All addresses copied');
                    break;
                case 'print':
                    window.print();
                    break;
                case 'scroll-top':
                    window.scrollTo({ top: 0, behavior: 'smooth' });
                    break;
                default:
                    return; // Not handled here (share / QR handled in their own modules).
            }

            // Close the export dropdown after a selection is made.
            const openDropdown = actionEl.closest('details.dropdown[open]');
            if (openDropdown) openDropdown.removeAttribute('open');
        });
    }

    // ------------------------------------------------------------------
    // Share (native share sheet on mobile, clipboard fallback elsewhere)
    // ------------------------------------------------------------------
    function initShare(data) {
        document.addEventListener('click', function (e) {
            const shareBtn = e.target.closest('[data-action="share"]');
            if (!shareBtn) return;

            const url = window.location.href;
            const title = data ? data.name : document.title;

            if (navigator.share) {
                navigator.share({ title: title, url: url }).catch((err) => {
                    // AbortError just means the user cancelled the share sheet.
                    if (err && err.name !== 'AbortError') {
                        copyAndNotify(url, 'Profile link copied');
                    }
                });
                return;
            }

            copyAndNotify(url, 'Profile link copied');
        });
    }

    // ------------------------------------------------------------------
    // QR code modal
    // ------------------------------------------------------------------
    function initQrModal() {
        document.addEventListener('click', function (e) {
            if (e.target.closest('[data-action="show-qr"]')) {
                const modal = document.getElementById('cv-qr-modal');
                const img = document.getElementById('cv-qr-image');
                const urlLabel = document.getElementById('cv-qr-url');
                if (!modal || !img) return;

                const url = window.location.href;
                img.src = 'https://api.qrserver.com/v1/create-qr-code/?size=220x220&data=' + encodeURIComponent(url);
                if (urlLabel) urlLabel.textContent = url;

                if (typeof modal.showModal === 'function') {
                    modal.showModal();
                } else {
                    modal.setAttribute('open', 'true'); // Fallback for very old browsers.
                }
                return;
            }

            if (e.target.closest('[data-action="download-qr"]')) {
                const img = document.getElementById('cv-qr-image');
                if (!img || !img.src) return;

                fetch(img.src)
                    .then(res => res.blob())
                    .then(blob => {
                        const url = URL.createObjectURL(blob);
                        const a = document.createElement('a');
                        a.href = url;
                        a.download = 'company-qr-code.png';
                        document.body.appendChild(a);
                        a.click();
                        document.body.removeChild(a);
                        setTimeout(() => URL.revokeObjectURL(url), 1000);
                        showToast('QR code downloaded', 'success');
                    })
                    .catch(() => showToast('Could not download QR code', 'error'));
            }
        });
    }

    // ------------------------------------------------------------------
    // Live filter for the email / phone directories
    // ------------------------------------------------------------------
    function initFilters() {
        let debounceTimer = null;

        document.addEventListener('input', function (e) {
            if (!e.target.classList.contains('cv-filter-input')) return;

            const input = e.target;
            clearTimeout(debounceTimer);
            debounceTimer = setTimeout(() => {
                const container = document.getElementById(input.getAttribute('data-target'));
                if (!container) return;
                const query = input.value.trim().toLowerCase();
                container.querySelectorAll('.cv-filter-row').forEach(function (row) {
                    const haystack = row.getAttribute('data-search') || '';
                    row.style.display = haystack.includes(query) ? '' : 'none';
                });
            }, 100);
        });
    }

    // ------------------------------------------------------------------
    // Scroll-spy for the sticky quick nav + scroll-to-top visibility
    // ------------------------------------------------------------------
    function initScrollSpyAndTopButton() {
        const nav = document.getElementById('cv-quick-nav');
        const scrollTopBtn = document.getElementById('cv-scroll-top');
        const navLinks = document.querySelectorAll('.cv-nav-link');
        const sections = Array.from(navLinks)
            .map(link => document.getElementById('section-' + link.getAttribute('data-nav')))
            .filter(Boolean);

        function setActive(id) {
            navLinks.forEach(function (link) {
                const isActive = link.getAttribute('data-nav') === id;
                link.classList.toggle('btn-primary', isActive);
                link.classList.toggle('btn-ghost', !isActive);
            });
        }

        if ('IntersectionObserver' in window && sections.length) {
            const observer = new IntersectionObserver(function (entries) {
                entries.forEach(function (entry) {
                    if (entry.isIntersecting) {
                        setActive(entry.target.id.replace('section-', ''));
                    }
                });
            }, { rootMargin: '-96px 0px -70% 0px', threshold: 0 });

            sections.forEach(section => observer.observe(section));
        }

        window.addEventListener('scroll', function () {
            if (nav) nav.classList.toggle('cv-scrolled', window.scrollY > 8);
            if (scrollTopBtn) {
                const visible = window.scrollY > 400;
                scrollTopBtn.classList.toggle('opacity-0', !visible);
                scrollTopBtn.classList.toggle('pointer-events-none', !visible);
            }
        }, { passive: true });
    }

    // ------------------------------------------------------------------
    // Print + misc keyboard affordances
    // ------------------------------------------------------------------
    function initPrint() {
        // Escape closes the QR modal even on browsers where <dialog> Esc
        // handling is inconsistent; also closes any open export dropdown.
        document.addEventListener('keydown', function (e) {
            if (e.key !== 'Escape') return;
            const modal = document.getElementById('cv-qr-modal');
            if (modal && modal.open) modal.close();
        });
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', init);
    } else {
        init();
    }
})();