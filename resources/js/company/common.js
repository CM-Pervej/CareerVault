// resources/js/company/common.js

export function initRepeatable(config) {
    const container = document.querySelector(config.container);
    const template = document.querySelector(config.template);
    const addButton = document.querySelector(config.addButton);

    if (!container || !template || !addButton) return;

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

    container.addEventListener('click', e => {
        const removeButton = e.target.closest(config.removeButton);

        if (!removeButton) return;

        const items = container.querySelectorAll(config.itemClass);

        if (items.length === 1) return;

        removeButton.closest(config.itemClass).remove();

        updateIndexes();
    });

    updateIndexes();
}


let toastTimer = null;

export function showToast(message, variant = 'success') {
    const toast = document.getElementById('cv-toast');
    const alertBox = document.getElementById('cv-toast-alert');
    const icon = document.getElementById('cv-toast-icon');
    const text = document.getElementById('cv-toast-message');

    if (!toast || !alertBox || !text) return;

    alertBox.classList.remove(
        'alert-success',
        'alert-error',
        'alert-info'
    );

    alertBox.classList.add(
        variant === 'error'
            ? 'alert-error'
            : variant === 'info'
                ? 'alert-info'
                : 'alert-success'
    );

    if (icon) {
        icon.className = 'fa-solid ' + (
            variant === 'error'
                ? 'fa-circle-exclamation'
                : variant === 'info'
                    ? 'fa-circle-info'
                    : 'fa-circle-check'
        );
    }

    text.textContent = message;

    toast.classList.remove('hidden');

    clearTimeout(toastTimer);

    toastTimer = setTimeout(() => {
        toast.classList.add('hidden');
    }, 2200);
}


export function copyText(text) {
    if (navigator.clipboard && window.isSecureContext) {
        return navigator.clipboard.writeText(text);
    }

    return new Promise((resolve, reject) => {
        const textarea = document.createElement('textarea');

        textarea.value = text;
        textarea.setAttribute('readonly', '');
        textarea.style.position = 'fixed';
        textarea.style.left = '-9999px';

        document.body.appendChild(textarea);

        textarea.focus();
        textarea.select();

        try {
            document.execCommand('copy');
            resolve();
        } catch (error) {
            reject(error);
        } finally {
            document.body.removeChild(textarea);
        }
    });
}


export function copyAndNotify(text, successMessage) {
    if (!text) {
        showToast('Nothing to copy', 'info');
        return;
    }

    copyText(text)
        .then(() => showToast(successMessage, 'success'))
        .catch(() => {
            showToast(
                'Could not copy — please copy manually',
                'error'
            );
        });
}


export function safeFilename(name) {
    return (
        (name || 'company')
            .toString()
            .trim()
            .replace(/[^a-z0-9]+/gi, '_')
            .replace(/^_+|_+$/g, '') ||
        'company'
    );
}


export function triggerDownload(content, filename, mime) {
    try {
        const blob = new Blob([content], {
            type: mime
        });

        const url = URL.createObjectURL(blob);

        const a = document.createElement('a');

        a.href = url;
        a.download = filename;

        document.body.appendChild(a);

        a.click();

        document.body.removeChild(a);

        setTimeout(() => {
            URL.revokeObjectURL(url);
        }, 1000);

        return true;
    } catch (error) {
        console.error('company.js: download failed', error);

        return false;
    }
}


export function csvField(value) {
    const str =
        value === null || value === undefined
            ? ''
            : String(value);

    if (/[",\n]/.test(str)) {
        return '"' + str.replace(/"/g, '""') + '"';
    }

    return str;
}