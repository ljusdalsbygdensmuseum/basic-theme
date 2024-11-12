class Like {
    constructor(){
        this.likebtn = document.querySelector('.heart-container');
        this.likeCount = document.querySelector('.heart-count');
        
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
    async add_like(){
        try {
            const fieldData = {
                prof_id: this.likebtn.dataset.prof_id,
            }
            
            // request to add like
            const request = await fetch(universalData.root_url+ '/wp-json/ljm/v1/manage_like', {
                method: 'POST',
                body: JSON.stringify(fieldData),
                headers: {
                    'Content-type': 'application/json',
                    'X-WP-Nonce': universalData.nonce
                }
            });
            if (!request.ok) {
                throw new Error(`Response status: ${request.status}`);
            }
            const response = await request.json();

            //visually changes the heart of like btn
            this.likebtn.dataset.user_liked = 1;
            let count = parseInt(this.likeCount.innerHTML) +1;
            this.likeCount.innerHTML = count+' likes';

            // adds like id to data
            this.likebtn.dataset.like_id = response;

        } catch (error) {
            console.error(error.message);
        }
    }
    async remove_like(){
        try {
            const fieldData = {
                like_id: this.likebtn.dataset.like_id
            }
            // request to add like
            const request = await fetch(universalData.root_url+ '/wp-json/ljm/v1/manage_like', {
                method: 'DELETE',
                body: JSON.stringify(fieldData),
                headers: {
                    'Content-type': 'application/json',
                    'X-WP-Nonce': universalData.nonce
                }
            });
            if (!request.ok) {
                throw new Error(`Response status: ${request.status}`);
            }
            const json = await request.json();
            console.log(json)

            //visually changes the heart of like btn
            this.likebtn.dataset.user_liked = '';
            let count = parseInt(this.likeCount.innerHTML) -1;
            this.likeCount.innerHTML = count+' likes';

            // adds like id to data
            this.likebtn.dataset.like_id = '';

        } catch (error) {
            console.error(error.message);
        }
    }
}

export default Like;