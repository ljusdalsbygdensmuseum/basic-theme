class Like {
    constructor(){
        this.likebtn = document.querySelector('.heart-container');
        
        this.events()
    }
    events(){
        this.likebtn.addEventListener('click', () => this.click_dispatcher());
    }
    // Methods
    click_dispatcher(){
        if (!this.likebtn.dataset.user_liked) {
            this.add_like();
        }else{
            this.remove_like();
        }
    }
    add_like(){
        this.likebtn.dataset.user_liked = 1;
    }
    remove_like(){
        this.likebtn.dataset.user_liked = '';
    }
}

export default Like;