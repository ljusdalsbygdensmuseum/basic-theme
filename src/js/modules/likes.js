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
    async add_like(){
        try {
            const fieldData = {
                prof_id: 12,
                user_id: 34
            }
            console.log(fieldData);
            // request to add like
            const request = await fetch(universalData.root_url+ '/wp-json/ljm/v1/manage_like', {
                method: 'POST',
                body: JSON.stringify(fieldData),
                headers: {
                    'Content-type': 'application/json'
                }
            });
            if (!request.ok) {
                throw new Error(`Response status: ${request.status}`);
            }
            const json = await request.json();
            console.log(json)

            //visually changes the heart of like btn
            this.likebtn.dataset.user_liked = 1;

        } catch (error) {
            console.error(error.message);
        }
    }
    async remove_like(){
        try {
            // request to add like
            const request = await fetch(universalData.root_url+ '/wp-json/ljm/v1/manage_like', {
                method: 'DELETE',
            });
            if (!request.ok) {
                throw new Error(`Response status: ${request.status}`);
            }
            const json = await request.json();
            console.log(json)

            //visually changes the heart of like btn
            this.likebtn.dataset.user_liked = '';

        } catch (error) {
            console.error(error.message);
        }
    }
}

export default Like;