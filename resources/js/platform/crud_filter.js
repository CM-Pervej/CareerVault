export function initPlatformFilters(){
    const searchInput=document.getElementById('platformSearch');
    const platformContainer=document.getElementById('platformContainer');
    const platformPagination=document.getElementById('platformPagination');
    const loadingIndicator=document.getElementById('platformSearchLoading');
    const searchShortcut=document.getElementById('platformSearchShortcut');

    if(!searchInput||!platformContainer) return;

    let debounceTimer=null;
    let activeController=null;
    const platformsUrl = searchInput.dataset.url|| window.location.pathname;

    function setLoading(loading){
        if(loading){
            loadingIndicator?.classList.remove('hidden');
            searchShortcut?.classList.add('hidden');
            searchInput.classList.add('opacity-70');
        }else{
            loadingIndicator?.classList.add('hidden');

            if(document.activeElement!==searchInput){
                searchShortcut?.classList.remove('hidden');
            }

            searchInput.classList.remove('opacity-70');
        }
    }

    async function fetchPlatforms(url = null){
        const search = searchInput.value.trim();

        let requestUrl;

        try{
            requestUrl = new URL(url || platformsUrl, window.location.origin);
        }catch(error){
            console.error('Invalid platform URL:',url,error);
            return;
        }

        if(search){
            requestUrl.searchParams.set('search',search);
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
            const response = await fetch(
                requestUrl.toString(),
                {
                    method:'GET',
                    headers:{
                        'X-Requested-With':'XMLHttpRequest',
                        'Accept':'application/json',
                    },
                    signal:activeController.signal,
                }
            );

            const contentType = response.headers.get('content-type')||'';

            if(!response.ok){
                const text=await response.text();
                console.error('Platform filter request failed:', response.status, text);
                throw new Error(`Unable to load platforms (${response.status}).`);
            }

            if(!contentType.includes('application/json')){
                const text=await response.text();
                console.error('Expected JSON but received:', text);
                throw new Error('The server returned an unexpected response.');
            }

            const data=await response.json();

            if(!data.html){
                throw new Error('Invalid platform response received.');
            }

            const parser=new DOMParser();
            const responseDocument = parser.parseFromString(data.html, 'text/html');
            const newPlatformContainer = responseDocument.querySelector('#platformContainer');

            if(!newPlatformContainer){
                throw new Error('Platform results could not be found.');
            }

            platformContainer.innerHTML = newPlatformContainer.innerHTML;

            const newPagination = responseDocument.querySelector('#platformPagination');
            const currentPagination = document.getElementById('platformPagination');

            if(newPagination&&currentPagination){
                currentPagination.innerHTML = newPagination.innerHTML;
            }

            window.history.replaceState(
                {}, '', requestUrl.pathname + requestUrl.search
            );
        }catch(error){
            if(error.name==='AbortError') return;

            console.error('Platform filter error:', error);

            platformContainer.innerHTML=`
                <div class="flex flex-col items-center justify-center py-14 text-center">
                    <i class="fa-solid fa-triangle-exclamation text-2xl text-error opacity-70 mb-3"></i>
                    <p class="font-semibold">Unable to load platforms</p>
                    <p class="text-sm text-base-content/50 mt-1">Please try again.</p>
                </div>
            `;
        }finally{
            setLoading(false);

            if(activeController){
                activeController = null;
            }
        }
    }

    searchInput.addEventListener('input',()=>{
        clearTimeout(debounceTimer);

        debounceTimer=setTimeout(()=>{
            fetchPlatforms();
        },300);
    });

    document.addEventListener('click',event=>{
        const link = event.target.closest('#platformPagination a');

        if(!link) return;

        event.preventDefault();
        fetchPlatforms(link.href);
    });

    document.addEventListener('keydown',event=>{
        if(event.key !== '/' || event.ctrlKey || event.metaKey || event.altKey) return;

        const target=event.target;

        if(target instanceof HTMLInputElement || target instanceof HTMLTextAreaElement || target instanceof HTMLSelectElement || target.isContentEditable) return;

        event.preventDefault();
        searchInput.focus();
    });

    searchInput.addEventListener('focus',()=>{
        searchShortcut?.classList.add('hidden');
    });

    searchInput.addEventListener('blur',()=>{
        if(!searchInput.value.trim()){
            searchShortcut?.classList.remove('hidden');
        }
    });
}

// crud 
export function initPlatformCrud(){
    const form=document.getElementById('platformForm');
    const modal=document.getElementById('platformModal');
    const deleteModal=document.getElementById('deleteModal');
    const platformContainer=document.getElementById('platformContainer');

    if(!form||!modal||!deleteModal||!platformContainer) return;

    const platformId=document.getElementById('platformId');
    const platformName=document.getElementById('platformName');
    const platformIcon=document.getElementById('platformIcon');
    const platformColor=document.getElementById('platformColor');
    const platformColorValue = document.getElementById('platformColorValue');
    const platformBaseUrl = document.getElementById('platformBaseUrl');
    const modalTitle=document.getElementById('modalTitle');
    const savePlatformText = document.getElementById('savePlatformText');
    const addButton = document.getElementById('addPlatformButton');
    const closeButton = document.getElementById('closePlatformModal');
    const cancelButton = document.getElementById('cancelPlatformButton');
    const saveButton = document.getElementById('savePlatformButton');
    const deleteForm = document.getElementById('deleteForm');
    const deletePlatformName = document.getElementById('deletePlatformName');
    const cancelDeleteButton = document.getElementById('cancelDeleteButton');

    // Add
    addButton?.addEventListener('click',()=>{
        resetForm();
        modalTitle.textContent='Add Platform';
        savePlatformText.textContent='Save Platform';
        form.action='/platforms';
        modal.showModal();
    });

    // Close
    closeButton?.addEventListener('click',()=>{
        modal.close();
    });

    cancelButton?.addEventListener('click',()=>{
        modal.close();
    });

    // Color Picker
    platformColor?.addEventListener('input',()=>{
        platformColorValue.value = platformColor.value.toUpperCase();
    });

    platformColorValue?.addEventListener('input',()=>{
        const value = platformColorValue.value.trim();

        if(/^#[0-9A-Fa-f]{6}$/.test(value)){
            platformColor.value=value;
        }
    });

    // Edit / Delete
    platformContainer.addEventListener('click',event=>{
        const editButton = event.target.closest('[data-edit-platform]');
        const deleteButton = event.target.closest('[data-delete-platform]');

        // Edit
        if(editButton){
            const id = editButton.dataset.id;

            platformId.value = id;
            platformName.value = editButton.dataset.name||'';
            platformIcon.value = editButton.dataset.icon||'';

            const color = editButton.dataset.color||'';

            platformColorValue.value=color;
            platformColor.value = /^#[0-9A-Fa-f]{6}$/.test(color)?color:'#000000';
            platformBaseUrl.value = editButton.dataset.baseUrl||'';
            form.action = `/platforms/${id}`;
            modalTitle.textContent = 'Edit Platform';
            savePlatformText.textContent = 'Update Platform';
            modal.showModal();

            return;
        }

        // Delete
        if(deleteButton){
            const id = deleteButton.dataset.id;
            const name = deleteButton.dataset.name || 'this platform';

            deletePlatformName.textContent = name;
            deleteForm.action = `/platforms/${id}`;
            deleteModal.showModal();
        }
    });

    // Store / Update
    form.addEventListener('submit',async event=>{
        event.preventDefault();
        const color = platformColorValue.value.trim();

        // Client-side color validation
        if(color && !/^#[0-9A-Fa-f]{6}$/.test(color)){
            alert('Brand color must be a valid 6-digit hexadecimal color, e.g. #0A66C2.');
            platformColorValue.focus();
            return;
        }

        const id = platformId.value;
        const formData = new FormData(form);

        if(id){
            formData.append('_method','PUT');
        }

        saveButton.disabled=true;

        try{
            const response=await fetch(
                form.action,
                {
                    method:'POST',

                    headers:{
                        'X-CSRF-TOKEN':getCsrfToken(),
                        'X-Requested-With':'XMLHttpRequest',
                        'Accept':'application/json',
                    },

                    body:formData,
                }
            );

            const contentType = response.headers.get('content-type')||'';

            if(!contentType.includes('application/json')){
                const text = await response.text();
                console.error('Unexpected server response:', text);
                throw new Error('The server returned an unexpected response.');
            }

            const data = await response.json();

            if(!response.ok){
                if(response.status === 422 && data.errors){
                    const messages = Object.values(data.errors).flat().join('\n');
                    throw new Error(messages);
                }

                throw new Error(data.message || 'Unable to save platform.');
            }

            modal.close();
            window.location.reload();

        }catch(error){
            console.error(error);
            alert(error.message);
        }finally{
            saveButton.disabled=false;
        }
    });

    // Cancel Delete
    cancelDeleteButton?.addEventListener('click',()=>{
        deleteModal.close();
    });

    // Delete
    deleteForm.addEventListener('submit',async event=>{
        event.preventDefault();

        const formData = new FormData(deleteForm);
        const button = document.getElementById('confirmDeleteButton');

        button.disabled=true;

        try{
            const response = await fetch(
                deleteForm.action,
                {
                    method:'POST',

                    headers:{
                        'X-CSRF-TOKEN':getCsrfToken(),
                        'X-Requested-With':'XMLHttpRequest',
                        'Accept':'application/json',
                    },

                    body:formData,
                }
            );

            const contentType = response.headers.get('content-type')||'';

            if(!contentType.includes('application/json')){
                const text = await response.text();
                console.error('Unexpected delete response:', text);
                throw new Error('The server returned an unexpected response.');
            }

            const data = await response.json();

            if(!response.ok){
                if(response.status === 422 && data.errors){
                    const messages = Object.values(data.errors).flat().join('\n');
                    throw new Error(messages);
                }

                throw new Error(data.message||'Unable to delete platform.');
            }

            deleteModal.close();
            window.location.reload();

        }catch(error){
            console.error(error);
            alert(error.message);
        }finally{
            button.disabled=false;
        }
    });

    // Reset Form
    function resetForm(){
        form.reset();
        platformId.value='';
        platformColor.value='#000000';
        platformColorValue.value='';
        form.action='/platforms';
    }

    // CSRF
    function getCsrfToken(){
        return document.querySelector('meta[name="csrf-token"]')?.content||'';
    }
}