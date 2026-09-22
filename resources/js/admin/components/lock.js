const CvSectionState={
    prefix:'cv-section:',
    init(){
        document.querySelectorAll('details[data-section]').forEach(section=>{
            this.bind(section);
        });
    },
    bind(section){
        const key=this.prefix+section.dataset.section;
        const savedState=localStorage.getItem(key);

        section.open=savedState!=='closed';

        section.addEventListener('toggle',()=>{
            localStorage.setItem(key,section.open?'open':'closed');
        });
    },
    reset(sectionName){
        localStorage.removeItem(this.prefix+sectionName);

        const section=document.querySelector(`details[data-section="${sectionName}"]`);

        if(section){
            section.open=true;
        }
    },
    resetAll(){
        document.querySelectorAll('details[data-section]').forEach(section=>{
            localStorage.removeItem(this.prefix+section.dataset.section);
            section.open=true;
        });
    }
};

document.addEventListener('DOMContentLoaded',()=>{
    CvSectionState.init();
});