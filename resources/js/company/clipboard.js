// resources/js/company/clipboard.js

import {
    copyText,
    showToast
} from './common.js';


export function initClipboard() {

    document.addEventListener('click', function (e) {

        const copyBtn =
            e.target.closest('.copy-btn');

        if (!copyBtn) return;


        const text =
            copyBtn.getAttribute('data-copy');

        if (!text) return;


        copyText(text)
            .then(function () {

                const icon =
                    copyBtn.querySelector('i');

                if (icon) {
                    icon.classList.remove('fa-copy');
                    icon.classList.add('fa-check');
                }

                copyBtn.classList.add('text-success');

                showToast(
                    'Copied to clipboard',
                    'success'
                );


                setTimeout(function () {

                    if (icon) {
                        icon.classList.remove('fa-check');
                        icon.classList.add('fa-copy');
                    }

                    copyBtn.classList.remove(
                        'text-success'
                    );

                }, 1500);

            })
            .catch(() => {
                showToast(
                    'Could not copy — please copy manually',
                    'error'
                );
            });
    });
}