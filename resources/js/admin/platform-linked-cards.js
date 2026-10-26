
/**
 * CareerVault — reusable linked-card interactions
 * Supports search, filtering, sorting, expand/collapse, and keyboard navigation.
 */
(() => {
    'use strict';

    const ROOT_SELECTOR = '[data-linked-companies]';
    const INITIALIZED_KEY = 'cvLinkedCompaniesInitialized';

    const query = (root, selector) => root.querySelector(selector);
    const queryAll = (root, selector) => Array.from(root.querySelectorAll(selector));

    function initializeLinkedCompanies(root) {
        if (root.dataset[INITIALIZED_KEY] === 'true') return;

        const list = query(root, '[data-cv-list]');
        if (!list) return;

        root.dataset[INITIALIZED_KEY] = 'true';

        const rows = Array.from(list.children).filter((element) =>
            element.matches('[data-cv-row]')
        );

        const search = query(root, '[data-cv-search]');
        const clearButton = query(root, '[data-cv-clear]');
        const sortSelect = query(root, '[data-cv-sort]');
        const toggleAllButton = query(root, '[data-cv-toggle-all]');
        const chips = queryAll(root, '[data-cv-chip]');
        const count = query(root, '[data-cv-count]');
        const empty = query(root, '[data-cv-empty]');
        const emptyQuery = query(root, '[data-cv-empty-q]');
        const resetButton = query(root, '[data-cv-reset]');

        const state = {
            query: '',
            filter: 'all',
        };

        const visibleRows = () => rows.filter((row) => !row.hidden);
        const isOpen = (row) => row.dataset.open === 'true';
        const buttonFor = (row) => query(row, '[data-cv-btn]');
        const regionFor = (row) => query(row, '[role="region"]');

        function setOpen(row, open) {
            row.dataset.open = String(open);

            const button = buttonFor(row);
            const region = regionFor(row);

            if (button) {
                button.setAttribute('aria-expanded', String(open));
            }

            if (region) {
                region.toggleAttribute('inert', !open);
            }
        }

        function syncToggleAll() {
            if (!toggleAllButton) return;

            const visible = visibleRows();
            const allOpen =
                visible.length > 0 && visible.every(isOpen);

            const label = query(toggleAllButton, 'span');
            const icon = query(toggleAllButton, 'i');

            toggleAllButton.disabled = visible.length === 0;

            if (label) {
                label.textContent = allOpen ? 'Collapse all' : 'Expand all';
            }

            if (icon) {
                icon.className = `fa-solid ${
                    allOpen ? 'fa-angles-up' : 'fa-angles-down'
                }`;
                icon.setAttribute('aria-hidden', 'true');
            }
        }

        function applyFilters() {
            const normalizedQuery = state.query.trim().toLocaleLowerCase();

            rows.forEach((row) => {
                const rowState = row.dataset.state || 'none';
                const searchableText = (
                    row.dataset.search || ''
                ).toLocaleLowerCase();

                const matchesFilter =
                    state.filter === 'all' ||
                    (state.filter === 'valid' && rowState === 'valid') ||
                    (state.filter === 'attention' && rowState !== 'valid');

                const matchesQuery =
                    !normalizedQuery ||
                    searchableText.includes(normalizedQuery);

                row.hidden = !(matchesFilter && matchesQuery);
            });

            const visibleCount = visibleRows().length;
            const hasFilters =
                normalizedQuery !== '' || state.filter !== 'all';

            if (count) {
                count.textContent = hasFilters
                    ? `Showing ${visibleCount} of ${rows.length}`
                    : '';
            }

            list.hidden = visibleCount === 0;

            if (empty) {
                empty.classList.toggle('hidden', visibleCount > 0);
            }

            if (emptyQuery) {
                emptyQuery.textContent = normalizedQuery
                    ? `“${state.query.trim()}”`
                    : 'these filters';
            }

            if (clearButton) {
                clearButton.classList.toggle(
                    'hidden',
                    state.query.length === 0
                );
            }

            syncToggleAll();
        }

        function sortByName(a, b) {
            return (a.dataset.name || '').localeCompare(
                b.dataset.name || '',
                undefined,
                {
                    sensitivity: 'base',
                    numeric: true,
                }
            );
        }

        const sorters = {
            default: (a, b) =>
                Number(a.dataset.index || 0) -
                Number(b.dataset.index || 0),

            az: sortByName,

            za: (a, b) => sortByName(b, a),

            recent: (a, b) =>
                Number(b.dataset.linked || 0) -
                    Number(a.dataset.linked || 0) ||
                sortByName(a, b),

            attention: (a, b) =>
                Number(a.dataset.state === 'valid') -
                    Number(b.dataset.state === 'valid') ||
                sortByName(a, b),
        };

        function sortRows() {
            const sorter = sorters[sortSelect?.value] || sorters.default;

            rows.slice().sort(sorter).forEach((row) => {
                list.appendChild(row);
            });
        }

        function setFilter(filter) {
            state.filter = filter;

            chips.forEach((chip) => {
                chip.setAttribute(
                    'aria-pressed',
                    String(chip.dataset.cvChip === filter)
                );
            });

            applyFilters();
        }

        function resetFilters() {
            state.query = '';
            state.filter = 'all';

            if (search) search.value = '';

            chips.forEach((chip) => {
                chip.setAttribute(
                    'aria-pressed',
                    String(chip.dataset.cvChip === 'all')
                );
            });

            applyFilters();
            search?.focus();
        }

        rows.forEach((row) => {
            const button = buttonFor(row);

            if (!button) return;

            button.addEventListener('click', () => {
                setOpen(row, !isOpen(row));
                syncToggleAll();
            });
        });

        toggleAllButton?.addEventListener('click', () => {
            const visible = visibleRows();
            const shouldOpen =
                !(visible.length > 0 && visible.every(isOpen));

            visible.forEach((row) => {
                setOpen(row, shouldOpen);
            });

            syncToggleAll();
        });

        search?.addEventListener('input', () => {
            state.query = search.value;
            applyFilters();
        });

        search?.addEventListener('keydown', (event) => {
            if (event.key === 'Escape' && search.value) {
                event.stopPropagation();
                search.value = '';
                state.query = '';
                applyFilters();
            }
        });

        clearButton?.addEventListener('click', () => {
            if (search) search.value = '';

            state.query = '';
            applyFilters();
            search?.focus();
        });

        chips.forEach((chip) => {
            chip.addEventListener('click', () => {
                setFilter(chip.dataset.cvChip || 'all');
            });
        });

        sortSelect?.addEventListener('change', () => {
            sortRows();
            applyFilters();
        });

        resetButton?.addEventListener('click', resetFilters);

        list.addEventListener('keydown', (event) => {
            const buttons = visibleRows()
                .map(buttonFor)
                .filter(Boolean);

            const currentIndex = buttons.indexOf(document.activeElement);

            if (currentIndex < 0) return;

            let nextButton = null;

            if (event.key === 'ArrowDown') {
                nextButton = buttons[currentIndex + 1];
            }

            if (event.key === 'ArrowUp') {
                nextButton = buttons[currentIndex - 1];
            }

            if (event.key === 'Home') {
                nextButton = buttons[0];
            }

            if (event.key === 'End') {
                nextButton = buttons[buttons.length - 1];
            }

            if (nextButton) {
                event.preventDefault();
                nextButton.focus();
            }
        });

        applyFilters();
    }

    function initializeAll() {
        document
            .querySelectorAll(ROOT_SELECTOR)
            .forEach(initializeLinkedCompanies);
    }

    function focusSearchShortcut(event) {
        if (
            event.key !== '/' ||
            event.ctrlKey ||
            event.metaKey ||
            event.altKey
        ) {
            return;
        }

        const target = event.target;

        if (
            target instanceof Element &&
            (
                target.matches(
                    'input, textarea, select, [contenteditable="true"]'
                ) ||
                target.isContentEditable
            )
        ) {
            return;
        }

        const roots = Array.from(
            document.querySelectorAll(ROOT_SELECTOR)
        );

        const search = roots
            .map((root) => query(root, '[data-cv-search]'))
            .find(
                (input) =>
                    input &&
                    !input.disabled &&
                    input.getClientRects().length > 0
            );

        if (!search) return;

        event.preventDefault();
        search.focus();
    }

    if (document.readyState === 'loading') {
        document.addEventListener(
            'DOMContentLoaded',
            initializeAll,
            { once: true }
        );
    } else {
        initializeAll();
    }

    document.addEventListener('keydown', focusSearchShortcut);
})();