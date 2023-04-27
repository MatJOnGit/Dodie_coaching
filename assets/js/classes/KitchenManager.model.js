class KitchenManager {
    #onlyNumbersRegex = /^[0-9]+$/;

    constructor() {
        this._searchResults = document.getElementById('search-results');
        this._apiToken = document.getElementById('search-bar').getAttribute('data-api-token');
        this._getIngredientDataRequestOptions = [
            {
                method: 'GET',
                headers: {
                    'Content-Type': 'application/json',
                    'Authorization': 'Bearer ' + this.apiToken
                }
            }
        ];
    }
    
    get getIngredientDataRequestOptions() {
        return this._getIngredientDataRequestOptions;
    }

    get searchResults() {
        return this._searchResults;
    }

    get apiToken() {
        return this._apiToken;
    }

    get onlyNumbersRegex() {
        return this.#onlyNumbersRegex;
    }
    
    showInputError() {
        let errorElt = document.createElement('div');
        errorElt.innerText = 'Le champ ne peut pas contenir que des chiffres.';
        errorElt.style.color = 'red';
        this.searchResults.appendChild(errorElt);
    }
    
    clearSearchResults() {
        this.searchResults.innerHTML = '';
    }
    
    checkInputValidity(inputValue) {
        if (!inputValue.trim()) {
            return false;
        }
        
        if (inputValue.length >= 45) {
            return false;
        }
        
        if (this.onlyNumbersRegex.test(inputValue)) {
            return false;
        }
        
        return true;
    }
    
    async sendRequest(endpoint) {
        try {
            let response = await fetch(endpoint, {
                method: this.getIngredientDataRequestOptions[0].method,
                headers: this.getIngredientDataRequestOptions[0].headers
            });
            
            return response.json();
        }
        
        catch (error) {
            console.error(error);
        }
    }
}