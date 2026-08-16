// resources/js/company/navigation.js

export function initCompanyNavigation() {

    const nav =
        document.getElementById(
            'cv-quick-nav'
        );

    const scrollTopBtn =
        document.getElementById(
            'cv-scroll-top'
        );

    const navLinks =
        document.querySelectorAll(
            '.cv-nav-link'
        );


    const sections =
        Array.from(navLinks)
            .map(link =>
                document.getElementById(
                    'section-' +
                    link.getAttribute('data-nav')
                )
            )
            .filter(Boolean);


    function setActive(id) {

        navLinks.forEach(link => {

            const isActive =
                link.getAttribute('data-nav') === id;


            link.classList.toggle(
                'btn-primary',
                isActive
            );


            link.classList.toggle(
                'btn-ghost',
                !isActive
            );
        });
    }


    if (
        'IntersectionObserver' in window &&
        sections.length
    ) {

        const observer =
            new IntersectionObserver(
                entries => {

                    entries.forEach(entry => {

                        if (entry.isIntersecting) {

                            setActive(
                                entry.target.id
                                    .replace(
                                        'section-',
                                        ''
                                    )
                            );
                        }
                    });

                },
                {
                    rootMargin:
                        '-96px 0px -70% 0px',

                    threshold: 0
                }
            );


        sections.forEach(section => {
            observer.observe(section);
        });
    }


    window.addEventListener(
        'scroll',
        function () {

            if (nav) {
                nav.classList.toggle(
                    'cv-scrolled',
                    window.scrollY > 8
                );
            }


            if (scrollTopBtn) {

                const visible =
                    window.scrollY > 400;


                scrollTopBtn.classList.toggle(
                    'opacity-0',
                    !visible
                );


                scrollTopBtn.classList.toggle(
                    'pointer-events-none',
                    !visible
                );
            }

        },
        {
            passive: true
        }
    );
}