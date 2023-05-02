class IngredientsFinder extends SearchEngine {
    constructor() {
        super();
        
        this._apiBaseUri = 'http://localhost:8080/MealFusion/v1/ingredients?name=';
        this._inputElt = document.getElementById('ingredient-search-bar');
        this.inputElt.addEventListener('input', this.searchIngredients.bind(this));
        this._newIngredientBtn = KitchenElementsBuilder.buildCreateItemButton('ingrédient');
    }
    
    get inputElt() {
        return this._inputElt;
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
    async searchIngredients() {
        this.clearSearchResults();
        
        if (this.checkInputValidity(this.inputElt.value)) {
            const apiHandler = new APIHandler(this.apiKey);
            const endpoint = `${this.apiBaseUri}${this.inputElt.value}`;
            const responseData = await apiHandler.sendGetRequest(endpoint);
            this.manageGetIngredientsResponse(responseData);
        }
        
        else if (this.inputElt.value.length > 1) {
            const inputErrorBlock = KitchenElementsBuilder.buildErrorMessage('Entrée invalide');
            this.searchResults.appendChild(inputErrorBlock);
        }
        
        this.searchResults.appendChild(this.newIngredientBtn);
    }
    
    /**************************************************************************
    Verifies API response and calls the appropriate displays and eventListeners
    **************************************************************************/
    manageGetIngredientsResponse(response) {
        const data = response.data;
        
        if (response['status'] === 200 && data.length > 0) {
            for (const ingredient of data) {
                const ingredientCard = KitchenElementsBuilder.buildIngredientCard(ingredient);
                this.searchResults.appendChild(ingredientCard);
                
                ingredientCard.addEventListener('click', () => {
                    const ingredientEditor = new IngredientEditor(this.apiKey, ingredient.id);
                    ingredientEditor.showIngredientEditionForm();
                })
            }
        }
        
        else {
            const noResultBlock = KitchenElementsBuilder.buildErrorMessage('Aucun résultat trouvé');
            this.searchResults.appendChild(noResultBlock);
        }
    }
}