class IngredientsManager extends KitchenManager {
    #mealFusionBaseUri = 'http://localhost:8080/MealFusion/v1/ingredients?name=';
    
    constructor() {
        super();
        
        this._input = document.getElementById('ingredient-name');
        this._input.addEventListener('input', this.searchIngredient.bind(this));
    }
    
    get inputValue() {
        return this._input.value;
    }
    
    get mealFusionBaseUri() {
        return this.#mealFusionBaseUri;
    }
    
    set inputValue(value) {
        this._input.value = value.replace(/\s+/g, '_');
    }
    
    async searchIngredient() {
        this.clearSearchResults();
        
        let inputValue = this.inputValue;
        
        if (!this.checkInputValidity(inputValue)) {
            this.showInputError();
            return;
        }
        
        let endpoint = this.#mealFusionBaseUri + inputValue;
        let response = await this.sendRequest(endpoint)
        
        this.manageApiResponse(response);
    }
    
    manageApiResponse(response) {
        if (response['status'] === 200 && response['data'].length > 0) {
            Object(response['data']).forEach(ingredient => {
                this.buildIngredientCard(ingredient);
            });
        }
        
        else {
            let noResults = document.createElement('div');
            noResults.innerText = 'Aucun résultat trouvé';
            this.searchResults.appendChild(noResults);
        }
        
        this.addCreateIngredientButton();
    }
    
    buildIngredientCard(ingredient) {
        let ingredientCard = document.createElement('button');
        ingredientCard.classList.add('food-card');
        ingredientCard.id = ingredient.id;
        ingredientCard.addEventListener('click', () => {
            let ingredientEditor = new IngredientEditor(this.apiToken, this._getIngredientDataRequestOptions, ingredient.id);
            ingredientEditor.showIngredientEditionForm();
        })
        
        let ingredientName = document.createElement('h4');
        ingredientName.classList.add('food-name');
        ingredientName.innerText = ingredient.name;
        
        if (ingredient.preparation !== null) {
            ingredientName.innerText += ' ' + ingredient.preparation
        }
        
        let ingredientType = document.createElement('p');
        ingredientType.innerText = '(' + ingredient.type + ')';
        
        ingredientCard.appendChild(ingredientName);
        ingredientCard.appendChild(ingredientType);
        this._searchResults.appendChild(ingredientCard);
    }
    
    addCreateIngredientButton() {
        let createIngredientBtn = document.createElement('a');
        createIngredientBtn.classList.add('btn', 'large-btn', 'admin-link', 'blue-bkgd');
        createIngredientBtn.id = 'create-food-item-btn';
        createIngredientBtn.innerText = 'Nouvel ingrédient';
        
        this._searchResults.appendChild(createIngredientBtn);
    }
}