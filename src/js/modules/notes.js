class Notes {
    constructor(){
        this.noteArea = document.querySelector('#note__container');
        this.noteDeleteBtn = document.querySelectorAll('.note__delete');
        this.noteEditBtn = document.querySelectorAll('.note__edit');
        this.noteSaveBtn = document.querySelectorAll('.note__save');
        this.noteCreateBtn = document.querySelectorAll('.note__create');
        this.eventsActive = false;

        this.events();
    }
    events() {
        this.noteDeleteBtn.forEach(deleteBtn => {
            deleteBtn.addEventListener('click', (event) => this.delete(event));
        });
        this.noteEditBtn.forEach(editBtn => {
            editBtn.addEventListener('click', (event) => this.clickEdit(event));
        });
        this.noteSaveBtn.forEach(saveBtn => {
            saveBtn.addEventListener('click', (event) => this.saveContent(event));
        });
        this.noteCreateBtn.forEach(saveBtn => {
            saveBtn.addEventListener('click', (event) => this.createContent(event));
        });

        this.eventsActive = true;
    }
    async delete(event){
        //get ID of note
        const ID = event.target.closest('.note').dataset.id;
        
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
    async saveContent(event){
        //get note
        const note = event.target.closest('.note');
        const titleArea = note.querySelector('.note__title');
        const textArea = note.querySelector('.note__content');
        const spinner = note.querySelector('.note__spinner-container');

        //loading visual
        spinner.innerHTML = '<span class="loader"></span>';

        //data
        const fieldData = {
            title: titleArea.value,
            content: textArea.value
        };
        console.log(fieldData);

        // universal data are set in /inc/enqueue
        const response = await fetch(universalData.root_url+'/wp-json/wp/v2/ljm_note/'+note.dataset.id, {
            method: 'POST',
            body: JSON.stringify(fieldData),
            headers: {
                'Content-type': 'application/json',
                'X-WP-Nonce': universalData.nonce
            }
        }).then(response=>{
            // remove spinner
            spinner.innerHTML = '';
            console.log(response);
        });

        // set new default values
        textArea.defaultValue = textArea.value;
        titleArea.defaultValue = titleArea.value;
    }
    async createContent(event){
        //get note
        const note = event.target.closest('.note');
        const titleArea = note.querySelector('.note__title');
        const textArea = note.querySelector('.note__content');
        const spinner = note.querySelector('.note__spinner-container');

        //loading visual
        spinner.innerHTML = '<span class="loader"></span>';

        //data
        const fieldData = {
            title: titleArea.value,
            content: textArea.value,
        };
        console.log(fieldData);

        // universal data are set in /inc/enqueue
        // fetch post without id to make new posts
        const response = await fetch(universalData.root_url+'/wp-json/wp/v2/ljm_note/', {
            method: 'POST',
            body: JSON.stringify(fieldData),
            headers: {
                'Content-type': 'application/json',
                'X-WP-Nonce': universalData.nonce
            }
            // then response => response.json() fetches the response then you can grab the data
        }).then(response => response.json()).then(responseData=>{
            // remove spinner
            spinner.innerHTML = '';
            console.log(responseData);



            // add the new note visually
            this.noteArea.insertAdjacentHTML('afterbegin', `
                <li data-id="${responseData.id}" data-state="inactive" class="note">
                    <input readonly class="note__title" type="text" value="${responseData.title.raw}">
                    <textarea readonly class="note__content">${responseData.content.raw}</textarea>
                    <div class="note__spinner-container"></div>
                    <button class="note__btn note__edit">Edit</button>
                    <button class="note__btn note__save hidden">Save</button>
                    <button class="note__btn note__delete">Delete</button>
                </li>
            `);
            // re selecting and reasigning events
            let deleteBtn = document.querySelectorAll('.note__delete')[0];
            let editBtn = document.querySelectorAll('.note__edit')[0];
            let saveBtn = document.querySelectorAll('.note__save')[0];
            
            deleteBtn.addEventListener('click', (event) => this.delete(event));
            editBtn.addEventListener('click', (event) => this.clickEdit(event));
            saveBtn.addEventListener('click', (event) => this.saveContent(event));
        });
    }
    clickEdit(event){
        //get note
        const note = event.target.closest('.note');

        if(note.dataset.state == 'active'){
            this.closeEdit(note);
        }else{
            this.openEdit(note);
        }
    }
    openEdit(note){
        const editbtn = note.querySelector('.note__edit');
        const savebtn = note.querySelector('.note__save');
        const titleArea = note.querySelector('.note__title');
        const textArea = note.querySelector('.note__content');

        // toggle save and edit btn
        editbtn.innerHTML = 'Cancel';
        savebtn.classList.remove('hidden');

        // remove readonly
        titleArea.readOnly = false;
        textArea.readOnly = false;

        textArea.focus();

        //add class of active
        note.dataset.state = 'active';
    }
    closeEdit(note){
        const editbtn = note.querySelector('.note__edit');
        const savebtn = note.querySelector('.note__save');
        const titleArea = note.querySelector('.note__title');
        const textArea = note.querySelector('.note__content');

        // reset values
        textArea.value = textArea.defaultValue;
        titleArea.value = titleArea.defaultValue;

        // toggle save and edit btn
        editbtn.innerHTML = 'Edit';
        savebtn.classList.add('hidden');

        // add readonly
        titleArea.readOnly = true;
        textArea.readOnly = true;

        //removes class of active
        note.dataset.state = 'inactive';
    }
    
}


export default Notes