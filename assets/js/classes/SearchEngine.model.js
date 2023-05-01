class SearchEngine {    
    constructor() {
        this._apiKey = document.getElementById('search-bar').getAttribute('data-api-token');
        this._searchResults = document.getElementById('search-results');
        
        this._getRequestOptions = [{
            method: 'GET',
            headers: {
                'Content-Type': 'application/json',
                'Authorization': 'Bearer ' + this.apiKey
            }
        }];
        this._onlyNumbersRegex = /^\d+$/;
    }
    
    get getRequestOptions() {
        return this._getRequestOptions;
    }
    
    get searchResults() {
        return this._searchResults;
    }
    
    get apiKey() {
        return this._apiKey;
    }
    
    get onlyNumbersRegex() {
        return this._onlyNumbersRegex;
    }
    
    clearSearchResults() {
        this.searchResults.innerHTML = '';
    }
    
    checkInputValidity(inputValue = '') {
        if (!inputValue.trim()) {
            return false;
        }
        
        if (inputValue.length >= 45) {
            return false;
        }
        
        if (inputValue.length === 0) {
            return false;
        }
        
        if (this.onlyNumbersRegex.test(inputValue)) {
            return false;
        }
        
        return true;
    }
    
    async sendGetIngredientsRequest(endpoint) {
        try {
            let response = await fetch(endpoint, {
                method: this.getRequestOptions[0].method,
                headers: this.getRequestOptions[0].headers
            });
            
            return response.json();
        }
        
        catch (error) {
            console.error(error);
        }
    }
}