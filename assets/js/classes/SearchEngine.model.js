class SearchEngine {    
    constructor() {
        this._apiKey = document.getElementById('search-bar').getAttribute('data-api-token');
        this._searchResults = document.getElementById('search-results');
        
        this._options = [{
            method: 'GET',
            headers: {
                'Content-Type': 'application/json',
                'Authorization': 'Bearer ' + this.apiKey
            }
        }];

        this._onlyNumbersRegex = /^\d+$/;
    }
    
    get options() {
        return this._options;
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
}