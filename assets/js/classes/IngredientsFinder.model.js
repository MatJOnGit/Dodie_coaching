class IngredientsFinder extends SearchEngine {
    constructor() {
        super();
        
        this._apiBaseUri = 'http://localhost:8080/MealFusion/v1/ingredients?name=';
        this._input = document.getElementById('ingredient-search-bar');
        this._input.addEventListener('input', this.searchIngredient.bind(this));
        this._newIngredientBtn = KitchenElementsBuilder.buildCreateItemButton('ingrédient');
    }
    
    get inputValue() {
        return this._input.value;
    }
    
    get apiBaseUri() {
        return this._apiBaseUri;
    }

    get newIngredientBtn() {
        return this._newIngredientBtn;
    }
    
    /***************************************************************************
    Empties results, then tests input value before requesting the API or setting
    a new message. In both cases, add a button to create a new ingredient in db
    ***************************************************************************/
    async searchIngredient() {
        this.clearSearchResults();
        
        if (this.checkInputValidity(this.inputValue)) {
            const endpoint = this.apiBaseUri + this.inputValue;
            const response = await this.sendGetIngredientsRequest(endpoint)
            this.manageGetIngredientsResponse(response);
        }
        
        else if (this.inputValue.length > 1) {
            const inputErrorBlock = KitchenElementsBuilder.buildErrorMessage('Entrée invalide');
            this.searchResults.appendChild(inputErrorBlock);
        }
        
        this.searchResults.appendChild(this.newIngredientBtn);
    }
    
    /*******************************************************
    Verifies API response and calls the appropriate displays
    *******************************************************/
    manageGetIngredientsResponse(response) {
        const { data } = response;
        
        if (response['status'] === 200 && data.length > 0) {
            for (const ingredient of data) {
                const ingredientCard = KitchenElementsBuilder.buildIngredientCard(this.apiKey, ingredient);
                this.searchResults.appendChild(ingredientCard);
            }
        }
        
        else {
            const noResultBlock = KitchenElementsBuilder.buildErrorMessage('Aucun résultat trouvé');
            this.searchResults.appendChild(noResultBlock);
        }
    }
}