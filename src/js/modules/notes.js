class Notes {
    constructor(){
        this.noteTitleArea = document.querySelectorAll('.note__title');
        this.noteTextArea = document.querySelectorAll('.note__content');
        this.noteDeleteBtn = document.querySelectorAll('.note__delete');
        this.noteEditBtn = document.querySelectorAll('.note__edit');

        this.events();
    }
    events() {
        this.noteDeleteBtn.forEach(deleteBtn => {
            deleteBtn.addEventListener('click', (event) => this.delete(event));
        });
        this.noteEditBtn.forEach(editBtn => {
            editBtn.addEventListener('click', () => this.openEdit());
        });
    }
    async delete(event){
        //get ID of note
        const ID = event.target.closest('.note').id;

        
        // universal data are set in /inc/enqueue
        const response = await fetch(universalData.root_url+'/wp-json/wp/v2/ljm_note/'+ID, {
            method: 'DELETE',
            headers: {
                //'Content-type': 'application/json',
                'X-WP-Nonce': universalData.nonce
            }
        }).then(response => {
            
            if(response.status >= 400){
                console.log('Failed to prosess the request, Status: '+ response.status);
                console.log(response)
                return;
            }
            console.log('Completed request, Status: '+response.status)

            //remove old note
            const noteHeight = event.target.closest('.note').offsetHeight;
            const noteMargin = parseInt(window.getComputedStyle(event.target.closest('.note')).marginBottom);

            //change opacity of note
            event.target.closest('.note').style.transition = 'all .2s ease-in-out';
            event.target.closest('.note').style.opacity = 0;

            //change position of note
            setTimeout(()=>{
                event.target.closest('.note').style.marginTop = '-'+(noteHeight+noteMargin)+'px';
            }, 200);

            setTimeout(()=>{
                event.target.closest('.note').remove();
            }, 2000);
        });

    }
    openEdit(){
        console.log('edit clicked');
    }
}


export default Notes