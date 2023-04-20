class IngredientManager {
    #mealFusionBaseUri = 'http://localhost:8080/MealFusion/v1/ingredients?name=';
    #onlyNumbersRegex = /^[0-9]+$/;
    
    constructor() {
        this._input = document.getElementById('ingredient-name');
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
        
        this._input.addEventListener('input', this.searchIngredient.bind(this));
    }
    
    get apiToken() {
        return this._apiToken;
    }
    
    get inputValue() {
        return this._input.value;
    }
    
    get mealFusionBaseUri() {
        return this.#mealFusionBaseUri;
    }
    
    get requestOptions() {
        return this._requestOptions;
    }
    
    set inputValue(value) {
        this._input.value = value.replace(/\s+/g, '_');
    }
    
    clearSearchResults() {
        this._searchResults.innerHTML = '';
    }
    
    checkInputValidity() {
        let inputValue = this.inputValue;
        
        if (this.#onlyNumbersRegex.test(inputValue) || inputValue.length >= 45) {
            return false;
        }
        
        return true;
    }
    
    searchIngredient(event) {
        this.clearSearchResults();
        
        if (!this.checkInputValidity()) {
            this.showInputError();
            return;
        }
        
        this.sendRequest();
    }
    
    sendRequest() {
        let inputValue = this.inputValue;
        
        if (inputValue) {
            let endpoint = this.mealFusionBaseUri + inputValue;
            
            fetch(endpoint, {
                method: this.requestOptions[0].method,
                headers: this.requestOptions[0].headers
            })
            .then(response => response.json())
            .then(response => {
                this.manageApiResponse(response);
            })
            .catch(error => {
                console.error(error);
            });
        }
    }
    
    showInputError() {
        let alert = document.createElement('div');
        alert.innerText = 'Le champ ne peut pas contenir que des chiffres.';
        alert.style.color = 'red';
        this._searchResults.appendChild(alert);
    }
    
    manageApiResponse(response) {
        if (response['status'] === 200 && response['data'].length > 0) {
            Object(response['data']).forEach(ingredient => {
                this.addIngredientCard(ingredient);
            });
        }
        
        else {
            let noResults = document.createElement('div');
            noResults.innerText = 'Aucun résultat trouvé';
            this._searchResults.appendChild(noResults);
        }
        
        this.addCreateIngredientButton();
    }
    
    addIngredientCard(ingredient) {
        let searchCard = document.createElement('div');
        searchCard.classList.add('ingredient-card');
        
        let ingredientName = document.createElement('h4');
        ingredientName.classList.add('ingredient-name');
        ingredientName.innerText = ingredient.name;
        if (ingredient.preparation !== null) {
            ingredientName.innerText += ' ' + ingredient.preparation
        }
        
        let ingredientType = document.createElement('p');
        ingredientType.innerText = '(' + ingredient.type + ')';
        
        searchCard.appendChild(ingredientName);
        searchCard.appendChild(ingredientType);
        this._searchResults.appendChild(searchCard);
    }
    
    addCreateIngredientButton() {
        let createIngredientBtn = document.createElement('a');
        createIngredientBtn.classList.add('btn', 'large-btn', 'admin-link', 'blue-bkgd');
        createIngredientBtn.id = 'create-ingredient-btn';
        createIngredientBtn.innerText = 'Nouvel ingrédient';
        
        this._searchResults.appendChild(createIngredientBtn);
    }
}