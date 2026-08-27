export function initDatabaseSearch({
    input,
    container,
    pagination,
    total = null,
    loading = null,
    shortcut = null,
    debounce = 300,
}){
    const searchInput = resolveElement(input);
    const resultContainer = resolveElement(container);
    const paginationContainer = resolveElement(pagination);
    const totalElement = resolveElement(total);
    const loadingElement = resolveElement(loading);
    const shortcutElement = resolveElement(shortcut);
    if(!searchInput || !resultContainer || !paginationContainer){
        return;
    }
    if(searchInput.dataset.databaseSearchInitialized === 'true'){
        return;
    }
    searchInput.dataset.databaseSearchInitialized = 'true';
    let debounceTimer = null;
    let activeController = null;
    const dataUrl = searchInput.dataset.url || window.location.pathname;
    function setLoading(state){
        loadingElement?.classList.toggle('hidden',!state);
        shortcutElement?.classList.toggle('hidden',state || document.activeElement === searchInput);
        searchInput.classList.toggle('opacity-70',state);
    }
    async function fetchResults(url = null){
        const keyword = searchInput.value.trim();
        const requestUrl = new URL(url || dataUrl,window.location.origin);
        if(keyword){
            requestUrl.searchParams.set('search',keyword);
        }else{
            requestUrl.searchParams.delete('search');
        }
        if(!url){
            requestUrl.searchParams.delete('page');
        }
        if(activeController){
            activeController.abort();
        }
        activeController = new AbortController();
        setLoading(true);
        try{
            const response = await fetch(requestUrl.toString(),{
                method:'GET',
                headers:{
                    'X-Requested-With':'XMLHttpRequest',
                    'Accept':'application/json',
                },
                signal:activeController.signal,
            });
            if(!response.ok){
                throw new Error(`Request failed with status ${response.status}`);
            }
            const data = await response.json();
            if(!data.html){
                throw new Error('Invalid response received from server.');
            }
            const parser = new DOMParser();
            const responseDocument = parser.parseFromString(data.html,'text/html');
            const newContainer = responseDocument.querySelector(`#${resultContainer.id}`);
            const newPagination = responseDocument.querySelector(`#${paginationContainer.id}`);
            const newTotal = totalElement
                ? responseDocument.querySelector(`#${totalElement.id}`)
                : null;
            if(!newContainer){
                throw new Error('Search results could not be found.');
            }
            resultContainer.innerHTML = newContainer.innerHTML;
            if(newPagination){
                paginationContainer.innerHTML = newPagination.innerHTML;
                paginationContainer.dataset.total = newPagination.dataset.total || '0';
                paginationContainer.dataset.perPage = newPagination.dataset.perPage || '50';
            }
            if(newTotal && totalElement){
                totalElement.textContent = newTotal.textContent.trim();
            }
            window.history.replaceState({},'',requestUrl.toString());
            document.dispatchEvent(new CustomEvent('database-search-updated',{
                detail:{
                    input:searchInput,
                    container:resultContainer,
                    pagination:paginationContainer,
                    total:totalElement,
                },
            }));
        }catch(error){
            if(error.name === 'AbortError'){
                return;
            }
            console.error('Database search error:',error);
            resultContainer.innerHTML = `
                <tr>
                    <td colspan="100%" class="text-center py-14">
                        <i class="fa-solid fa-triangle-exclamation text-2xl text-error opacity-70 mb-3 block"></i>
                        <p class="cv-title text-lg">Unable to load results</p>
                        <p class="text-sm opacity-50">Please try again.</p>
                    </td>
                </tr>
            `;
        }finally{
            setLoading(false);
            activeController = null;
        }
    }
    searchInput.addEventListener('input',()=>{
        clearTimeout(debounceTimer);
        debounceTimer = setTimeout(()=>{
            fetchResults();
        },debounce);
    });
    paginationContainer.addEventListener('click',event=>{
        const link = event.target.closest('a');
        if(!link){
            return;
        }
        event.preventDefault();
        fetchResults(link.href);
    });
    if(shortcutElement){
        searchInput.addEventListener('focus',()=>{
            shortcutElement.classList.add('hidden');
        });
        searchInput.addEventListener('blur',()=>{
            if(!searchInput.value.trim()){
                shortcutElement.classList.remove('hidden');
            }
        });
        document.addEventListener('keydown',event=>{
            if(
                event.key !== '/' ||
                event.ctrlKey ||
                event.metaKey ||
                event.altKey
            ){
                return;
            }
            const target = event.target;
            if(
                target instanceof HTMLInputElement ||
                target instanceof HTMLTextAreaElement ||
                target instanceof HTMLSelectElement ||
                target.isContentEditable
            ){
                return;
            }
            event.preventDefault();
            searchInput.focus();
        });
    }
}
function resolveElement(element){
    if(!element){
        return null;
    }
    if(typeof element === 'string'){
        return document.getElementById(element);
    }
    return element;
}
export function initDeleteModal(){
    const deleteModal = document.getElementById('deleteModal');
    const deleteForm = document.getElementById('deleteForm');
    const deleteItemName = document.getElementById('deleteItemName');
    const deleteModalTitle = document.getElementById('deleteModalTitle');
    if(!deleteModal || !deleteForm || !deleteItemName){
        return;
    }
    if(deleteModal.dataset.initialized === 'true'){
        return;
    }
    deleteModal.dataset.initialized = 'true';
    document.addEventListener('click',event=>{
        const deleteButton = event.target.closest('.delete-item');
        if(!deleteButton){
            return;
        }
        const url = deleteButton.dataset.url;
        if(!url){
            return;
        }
        deleteItemName.textContent = deleteButton.dataset.name || 'this item';
        deleteForm.action = url;
        if(deleteModalTitle){
            deleteModalTitle.textContent = deleteButton.dataset.title || 'Delete';
        }
        deleteModal.showModal();
    });
}
export function initDatabaseSearches(){
    document.querySelectorAll('[data-database-search]').forEach(input=>{
        if(input.dataset.databaseSearchInitialized === 'true'){
            return;
        }
        const prefix = input.id.replace(/Search$/,'');
        if(!prefix){
            return;
        }
        const name = prefix.charAt(0).toLowerCase() + prefix.slice(1);
        initDatabaseSearch({
            input:input,
            container:`${name}Container`,
            pagination:`${name}Pagination`,
            total:`${name}Total`,
            loading:`${name}SearchLoading`,
            shortcut:`${name}SearchShortcut`,
        });
    });
}
export function initGeneral(){
    initDatabaseSearches();
    initDeleteModal();
}