class liveSearch {
    constructor(){
        // remove link if js is active
        document.querySelector('#search_btn_container').innerHTML = '<button id="search-open">search</button>';

        this.printSearchHtml();
        this.openBtn = document.querySelector('#search-open');
        this.searchBox = document.querySelector('#live-search');
        this.closeBtn = document.querySelector('#close-search');
        this.resultBox = document.querySelector('#live-search__results');
        this.overlay = document.querySelector('.search-overlay');

        this.typingTimer;
        this.searchValue;
        this.spinnerVisible = false;
        
        this.searchResults;

        this.events();
    }
    // Events
    events(){
        this.closeBtn.addEventListener('click', () => this.closeOverlay());

        this.openBtn.addEventListener('click', () => this.openOverlay());

        document.addEventListener('keyup', (event)=> this.keyPressDispatcher(event));

        this.searchBox.addEventListener('keyup', (event) => this.typingLogic(event))
    }
    // Methods
    openOverlay(){
        this.overlay.classList.remove('hidden');
        document.body.classList.add('body_no_scroll');
        this.searchBox.focus();
    }
    closeOverlay(){
        this.overlay.classList.add('hidden');
        document.body.classList.add('body_no_scroll');
    }
    keyPressDispatcher(event){
        // Checks if anny textfields are active so that it does not fire when writing in a box
        let isActive = false;
        document.querySelectorAll('input, textarea').forEach(value =>{
            if (value == document.activeElement) {
                isActive = true;
                return;
            }
        });
        if (isActive) {
            return;
        }

        // if press s
        if(event.keyCode == 83){
            this.openOverlay();
        }
        // if press esc
        if (event.keyCode == 27) {
            this.closeOverlay();
        }
    }
    typingLogic(){
        // check the value so that it isnt the same as last kepress, ex press arrow keys  
        if (this.searchValue == this.searchBox.value) {
            return;
        }

        // if box is empty
        if (!this.searchBox.value) {
            clearTimeout(this.typingTimer);
            this.resultBox.innerHTML = this.printSearchResultHtml();
            this.spinnerVisible = false;
            
            return;
        }

        clearTimeout(this.typingTimer);//---------------------------------resets timer so that there wont be a request for every keystroke

        // if spinner is visible otherwise clear results and make visible
        if (!this.spinnerVisible) {
            this.resultBox.innerHTML = this.printSearchResultHtml();
            this.resultBox.querySelector(":scope > #live-search__results__spinner").innerHTML = '<span class="loader"></span>';
            this.spinnerVisible = true;
        }
    
        // calling getSearchResult() after a set time
        // this is to make shure that we don't send a bagilion requests to the server
        this.typingTimer = setTimeout(() => this.getSearchResults(), 800);

        // sets up the value to check in the first if statment of the function
        this.searchValue = this.searchBox.value;
    }

    async getSearchResults(){
        // results array
        this.searchResults = [];

        // requests the data
        // universal data are set in /inc/enque
        // the special rest url is set in /inc/re-route-search
        const postResponse = await fetch( universalData.root_url+'/wp-json/ljm/v1/search?value='+this.searchBox.value);

        const post = await postResponse.json();

        // check if there are search results
        let hasResults = false;
        Object.entries(post).forEach(result=>{
            if (result[1].length > 1) {
                hasResults = true;
            }
        });


        // if there are no results print nothing found
        if (!hasResults) {
            this.resultBox.innerHTML = this.printSearchResultHtml();
            this.spinnerVisible = false;
            this.resultBox.querySelector(":scope > #live-search__results__general_info").innerHTML = 'Nothing found';
            return;
        }

        // prints data in global variable
        this.searchResults = post;

        // displays the results
        this.displaySearchResults();
    }
    displaySearchResults(){
        //grabs global data
        const data = this.searchResults;

        // clears results and stops spinner
        this.resultBox.innerHTML = this.printSearchResultHtml();
        this.spinnerVisible = false;

        // foreach search result
        Object.entries(data).forEach(postType=> {
            if(postType[1].length <1){
                return;
            }
            let items = '';
            postType[1].forEach(item=>{
                items+= `<hr><li class="live-search__result-item"><a href="${item.url}">${item.title}</a></li>`;

            });
            let title = postType[1][0].post_type;
            if (postType[0] == 'general_info') {
                title = 'General results';
            }
            this.resultBox.querySelector(':scope > #live-search__results__'+postType[0]).innerHTML = '<h2>'+title+'</h2><ul>'+items+'</ul>';
        });
    }
    printSearchHtml(){
        document.body.insertAdjacentHTML( 'beforeend', `
            <div class="search-overlay hidden">
                <div class="search-container">
                    <input type="text" name="live-search" id="live-search" placeholder="Sök..." autocomplete="off">
                    <div id="live-search__results">
                        ${this.printSearchResultHtml()}
                    </div>
                </div>
                <button id="close-search">X</button>
            </div>
        `);
    }
    printSearchResultHtml(){
        return `
            <div id="live-search__results__spinner"></div>
            <div id="live-search__results__general_info" class="live-search__result_container"></div>
            <div id="live-search__results__programs" class="live-search__result_container"></div>
            <div id="live-search__results__events" class="live-search__result_container"></div>
            <div id="live-search__results__professors" class="live-search__result_container"></div>
            <div id="live-search__results__campuses" class="live-search__result_container"></div>
        `
    }
}

export default liveSearch