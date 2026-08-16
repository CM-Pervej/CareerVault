// document.addEventListener('DOMContentLoaded', () => {
//     const searchInput = document.getElementById('companySearch');
//     const companyContainer = document.getElementById('companyContainer');
//     const companyPagination = document.getElementById('companyPagination');
//     const companyTotal = document.getElementById('companyTotal');
//     const loadingIndicator = document.getElementById('companySearchLoading');
//     const searchShortcut = document.getElementById('companySearchShortcut');

//     if (
//         !searchInput ||
//         !companyContainer ||
//         !companyPagination
//     ) {
//         return;
//     }

//     let debounceTimer = null;
//     let activeController = null;

//     /**
//      * Get the companies index URL.
//      */
//     const companiesUrl = searchInput.dataset.url || window.location.pathname;

//     /**
//      * Toggle loading state.
//      */
//     const setLoading = (loading) => {
//         if (loading) {
//             loadingIndicator?.classList.remove('hidden');
//             searchShortcut?.classList.add('hidden');
//             searchInput.classList.add('opacity-70');
//         } else {
//             loadingIndicator?.classList.add('hidden');

//             if (document.activeElement !== searchInput) {
//                 searchShortcut?.classList.remove('hidden');
//             }

//             searchInput.classList.remove('opacity-70');
//         }
//     };

//     /**
//      * Fetch companies from the database.
//      */
//     const fetchCompanies = async (url = null) => {
//         const search = searchInput.value.trim();

//         const requestUrl = new URL(
//             url || companiesUrl,
//             window.location.origin
//         );

//         /*
//          * Always keep the current search value in the URL.
//          */
//         if (search) {
//             requestUrl.searchParams.set('search', search);
//         } else {
//             requestUrl.searchParams.delete('search');
//         }

//         /*
//          * When starting a new search, always go back to page 1.
//          */
//         if (!url) {
//             requestUrl.searchParams.delete('page');
//         }

//         /*
//          * Cancel the previous request.
//          */
//         if (activeController) {
//             activeController.abort();
//         }

//         activeController = new AbortController();

//         setLoading(true);

//         try {
//             const response = await fetch(requestUrl.toString(), {
//                 method: 'GET',

//                 headers: {
//                     'X-Requested-With': 'XMLHttpRequest',
//                     'Accept': 'application/json',
//                 },

//                 signal: activeController.signal,
//             });

//             if (!response.ok) {
//                 throw new Error(
//                     `Request failed with status ${response.status}`
//                 );
//             }

//             const data = await response.json();

//             /*
//              * Parse the returned Blade HTML.
//              */
//             const parser = new DOMParser();

//             const documentHtml = parser.parseFromString(
//                 data.html,
//                 'text/html'
//             );

//             const newCompanyContainer =
//                 documentHtml.querySelector('#companyContainer');

//             const newCompanyPagination =
//                 documentHtml.querySelector('#companyPagination');

//             const newCompanyTotal =
//                 documentHtml.querySelector('#companyTotal');

//             /*
//              * Make sure the expected elements exist.
//              */
//             if (!newCompanyContainer) {
//                 throw new Error(
//                     'Company results could not be loaded.'
//                 );
//             }

//             /*
//              * Replace only the table body.
//              */
//             companyContainer.innerHTML =
//                 newCompanyContainer.innerHTML;

//             /*
//              * Replace pagination.
//              */
//             if (newCompanyPagination) {
//                 companyPagination.innerHTML =
//                     newCompanyPagination.innerHTML;
//             }

//             /*
//              * Update total count.
//              */
//             if (newCompanyTotal && companyTotal) {
//                 companyTotal.textContent =
//                     newCompanyTotal.textContent.trim();
//             }

//             /*
//              * Update browser URL without reloading the page.
//              */
//             const cleanUrl = new URL(
//                 requestUrl.toString()
//             );

//             if (!cleanUrl.searchParams.get('search')) {
//                 cleanUrl.searchParams.delete('search');
//             }

//             window.history.replaceState(
//                 {},
//                 '',
//                 cleanUrl.toString()
//             );

//         } catch (error) {

//             /*
//              * Ignore AbortError because it simply means
//              * another search request replaced this one.
//              */
//             if (error.name === 'AbortError') {
//                 return;
//             }

//             console.error(
//                 'Company search error:',
//                 error
//             );

//             companyContainer.innerHTML = `
//                 <tr>
//                     <td
//                         colspan="${document.querySelector('#companyContainer')?.closest('table')?.querySelectorAll('thead th').length || 6}"
//                         class="text-center py-14"
//                     >
//                         <i class="fa-solid fa-triangle-exclamation text-2xl text-error opacity-70 mb-3 block"></i>

//                         <p class="cv-title text-lg">
//                             Unable to load companies
//                         </p>

//                         <p class="text-sm opacity-50">
//                             Please try again.
//                         </p>
//                     </td>
//                 </tr>
//             `;

//         } finally {
//             setLoading(false);
//             activeController = null;
//         }
//     };

//     /**
//      * Live database search.
//      *
//      * Debounce prevents a request for every single
//      * keystroke.
//      */
//     searchInput.addEventListener('input', () => {
//         clearTimeout(debounceTimer);

//         debounceTimer = setTimeout(() => {
//             fetchCompanies();
//         }, 300);
//     });

//     /**
//      * AJAX pagination.
//      *
//      * Laravel generates normal pagination links.
//      * We intercept them and load the requested page
//      * through AJAX instead.
//      */
//     companyPagination.addEventListener('click', (event) => {
//         const link = event.target.closest('a');

//         if (!link) {
//             return;
//         }

//         event.preventDefault();

//         fetchCompanies(link.href);
//     });

//     /**
//      * "/" keyboard shortcut.
//      *
//      * Focus the company search box when the user presses "/".
//      */
//     document.addEventListener('keydown', (event) => {
//         if (
//             event.key !== '/' ||
//             event.ctrlKey ||
//             event.metaKey ||
//             event.altKey
//         ) {
//             return;
//         }

//         const target = event.target;

//         if (
//             target instanceof HTMLInputElement ||
//             target instanceof HTMLTextAreaElement ||
//             target instanceof HTMLSelectElement ||
//             target.isContentEditable
//         ) {
//             return;
//         }

//         event.preventDefault();

//         searchInput.focus();
//     });

//     /**
//      * Hide/show the "/" shortcut based on focus.
//      */
//     searchInput.addEventListener('focus', () => {
//         searchShortcut?.classList.add('hidden');
//     });

//     searchInput.addEventListener('blur', () => {
//         if (!searchInput.value.trim()) {
//             searchShortcut?.classList.remove('hidden');
//         }
//     });
// });