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
            deleteBtn.addEventListener('click', () => this.delete());
        });
        this.noteEditBtn.forEach(editBtn => {
            editBtn.addEventListener('click', () => this.openEdit());
        });
    }
    async delete(){

        console.log(universalData.nonce);
        const response = await fetch(universalData.root_url+'/wp-json/wp/v2/ljm_note/104', {
            method: 'DELETE',
            headers: {
                //'Content-type': 'application/json',
                'X-WP-Nonce': universalData.nonce
            }
        }).then(res => console.log(res));

    }
    openEdit(){
        console.log('edit clicked');
    }
}


export default Notes