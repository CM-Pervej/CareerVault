// resources/js/company/qr.js

import {
    showToast
} from './common.js';


export function initCompanyQR() {

    document.addEventListener('click', function (e) {

        // Show QR
        if (
            e.target.closest(
                '[data-action="show-qr"]'
            )
        ) {

            const modal =
                document.getElementById(
                    'cv-qr-modal'
                );

            const img =
                document.getElementById(
                    'cv-qr-image'
                );

            const urlLabel =
                document.getElementById(
                    'cv-qr-url'
                );


            if (!modal || !img) return;


            const url =
                window.location.href;


            img.src =
                'https://api.qrserver.com/v1/create-qr-code/' +
                '?size=220x220&data=' +
                encodeURIComponent(url);


            if (urlLabel) {
                urlLabel.textContent = url;
            }


            if (
                typeof modal.showModal === 'function'
            ) {
                modal.showModal();
            } else {
                modal.setAttribute(
                    'open',
                    'true'
                );
            }


            return;
        }


        // Download QR
        if (
            e.target.closest(
                '[data-action="download-qr"]'
            )
        ) {

            const img =
                document.getElementById(
                    'cv-qr-image'
                );


            if (!img || !img.src) return;


            fetch(img.src)
                .then(response =>
                    response.blob()
                )
                .then(blob => {

                    const url =
                        URL.createObjectURL(blob);

                    const a =
                        document.createElement('a');


                    a.href = url;

                    a.download =
                        'company-qr-code.png';


                    document.body.appendChild(a);

                    a.click();

                    document.body.removeChild(a);


                    setTimeout(() => {
                        URL.revokeObjectURL(url);
                    }, 1000);


                    showToast(
                        'QR code downloaded',
                        'success'
                    );
                })
                .catch(() => {

                    showToast(
                        'Could not download QR code',
                        'error'
                    );
                });
        }
    });
}