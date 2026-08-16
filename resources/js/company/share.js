// resources/js/company/share.js

import {
    copyAndNotify
} from './common.js';


export function initCompanyShare(data) {

    document.addEventListener('click', function (e) {

        const shareBtn =
            e.target.closest(
                '[data-action="share"]'
            );

        if (!shareBtn) return;


        const url = window.location.href;

        const title =
            data
                ? data.name
                : document.title;


        if (navigator.share) {

            navigator.share({
                title: title,
                url: url
            })
            .catch(error => {

                if (
                    error &&
                    error.name !== 'AbortError'
                ) {
                    copyAndNotify(
                        url,
                        'Profile link copied'
                    );
                }
            });

            return;
        }


        copyAndNotify(
            url,
            'Profile link copied'
        );
    });
}