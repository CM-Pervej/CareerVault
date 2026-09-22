<dialog id="admin-action-modal" class="modal">
    <div class="modal-box max-w-md rounded-2xl p-0">
        <div class="border-b border-base-300 px-6 py-5">
            <div class="flex items-start gap-4">
                <div id="admin-action-modal-icon-wrapper" class="flex h-12 w-12 shrink-0 items-center justify-center rounded-xl">
                    <i id="admin-action-modal-icon" class="text-lg"></i>
                </div>

                <div class="min-w-0">
                    <h3 id="admin-action-modal-title" class="text-lg font-black"></h3>
                    <p id="admin-action-modal-description" class="mt-1 text-sm leading-6 text-base-content/60"></p>
                </div>
            </div>
        </div>

        <div class="flex justify-end gap-2 px-6 py-4">
            <button type="button" class="btn btn-ghost btn-sm" onclick="document.getElementById('admin-action-modal').close()">
                Cancel
            </button>

            <form id="admin-action-modal-form" method="POST">
                @csrf

                <input type="hidden" name="_method" id="admin-action-modal-method">

                <button id="admin-action-modal-confirm" type="submit" class="btn btn-sm gap-2">
                    <i id="admin-action-modal-confirm-icon"></i>
                    <span id="admin-action-modal-confirm-text"></span>
                </button>
            </form>
        </div>
    </div>

    <form method="dialog" class="modal-backdrop">
        <button>close</button>
    </form>
</dialog>

<script>
document.addEventListener('DOMContentLoaded',function(){
    const modal=document.getElementById('admin-action-modal');
    const form=document.getElementById('admin-action-modal-form');
    const method=document.getElementById('admin-action-modal-method');
    const title=document.getElementById('admin-action-modal-title');
    const description=document.getElementById('admin-action-modal-description');
    const iconWrapper=document.getElementById('admin-action-modal-icon-wrapper');
    const icon=document.getElementById('admin-action-modal-icon');
    const confirmButton=document.getElementById('admin-action-modal-confirm');
    const confirmIcon=document.getElementById('admin-action-modal-confirm-icon');
    const confirmText=document.getElementById('admin-action-modal-confirm-text');

    document.querySelectorAll('[data-admin-action]').forEach(function(trigger){
        trigger.addEventListener('click',function(){
            form.action=this.dataset.actionUrl;
            method.value=this.dataset.actionMethod||'POST';

            title.textContent=this.dataset.actionTitle||'Confirm Action';
            description.innerHTML=this.dataset.actionDescription||'Are you sure you want to continue?';

            icon.className=this.dataset.actionIcon||'fa-solid fa-circle-question';
            confirmIcon.className=this.dataset.actionConfirmIcon||this.dataset.actionIcon||'fa-solid fa-check';
            confirmText.textContent=this.dataset.actionConfirmText||'Confirm';

            const type=this.dataset.actionType||'primary';

            iconWrapper.className='flex h-12 w-12 shrink-0 items-center justify-center rounded-xl';

            if(type==='danger'){
                iconWrapper.classList.add('bg-error/10','text-error');
                confirmButton.className='btn btn-error btn-sm gap-2';
            }else if(type==='success'){
                iconWrapper.classList.add('bg-success/10','text-success');
                confirmButton.className='btn btn-success btn-sm gap-2';
            }else if(type==='warning'){
                iconWrapper.classList.add('bg-warning/10','text-warning');
                confirmButton.className='btn btn-warning btn-sm gap-2';
            }else{
                iconWrapper.classList.add('bg-primary/10','text-primary');
                confirmButton.className='btn btn-primary btn-sm gap-2';
            }

            modal.showModal();
        });
    });
});
</script>