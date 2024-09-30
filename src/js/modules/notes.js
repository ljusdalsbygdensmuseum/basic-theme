class notes {
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
    delete(){
        console.log('deleting');
    }
    openEdit(){
        console.log('edit clicked');
    }
}


export default notes