class KitchenManager {
    #onlyNumbersRegex = /^[0-9]+$/;

    constructor() {
        this._searchResults = document.getElementById('search-results');
        this._apiToken = document.getElementById('search-bar').getAttribute('data-api-token');
        this._requestOptions = [
            {
                method: 'GET',
                headers: {
                    'Content-Type': 'application/json',
                    'Authorization': 'Bearer ' + this._apiToken
                }
            }
        ];
    }
    
    get requestOptions() {
        return this._requestOptions;
    }
    
    showInputError() {
        let alert = document.createElement('div');
        alert.innerText = 'Le champ ne peut pas contenir que des chiffres.';
        alert.style.color = 'red';
        this._searchResults.appendChild(alert);
    }
    
    clearSearchResults() {
        this._searchResults.innerHTML = '';
    }
    
    checkInputValidity(inputValue) {
        if (!inputValue.trim()) {
            return false;
        }
        
        if (inputValue.length >= 45) {
            return false;
        }
        
        if (this.#onlyNumbersRegex.test(inputValue)) {
            return false;
        }
        
        return true;
    }
    
    async sendRequest(endpoint) {
        try {
            let response = await fetch(endpoint, {
                method: this.requestOptions[0].method,
                headers: this.requestOptions[0].headers
            });
            
            return response.json();
        } catch (error) {
            console.error(error);
        }
    }
}