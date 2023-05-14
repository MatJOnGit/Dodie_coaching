class RecipesManager extends KitchenManager {
    #mealFusionBaseUri = 'http://localhost:8080/MealFusion/v1/recipes?name=';

    constructor() {
        super();
        
        this._input = document.getElementById('recipe-name');
        this._input.addEventListener('input', this.searchRecipe.bind(this));
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
    
    async searchRecipe() {
        this.clearSearchResults();
        
        let inputValue = this.inputValue;
        
        if (!this.checkInputValidity(inputValue)) {
            this.showInputError();
            return;
        }
        
        else {
            let endpoint = this.#mealFusionBaseUri + inputValue;
            let response = await this.sendRequest(endpoint)
            
            this.manageApiResponse(response);
        }
    }
    
    manageApiResponse(response) {
        if (response['status'] === 200 && response['data'].length > 0) {
            Object(response['data']).forEach(recipe => {
                this.addRecipeCard(recipe);
            });
        }
        
        else {
            let noResults = document.createElement('div');
            noResults.innerText = 'Aucun résultat trouvé';
            this._searchResults.appendChild(noResults);
        }
        
        this.addCreateRecipeButton();
    }
    
    addRecipeCard(recipe) {
        let recipeCard = document.createElement('div');
        recipeCard.classList.add('food-card');
        
        let recipeName = document.createElement('h4');
        recipeName.classList.add('food-name');
        recipeName.innerText = recipe.recipe_name;
        
        let ingredientList = document.createElement('ul');
        ingredientList.classList.add('ingredient-list');
        
        recipe.ingredient_details.forEach(ingredient => {
            let ingredientListItem = document.createElement('li');
            let ingredientName = ingredient.ingredient_name;
            let ingredientQuantity = ingredient.quantity;
            let ingredientMeasure = ingredient.measure;
            
            ingredientListItem.innerText = `${ingredientName} (${ingredientQuantity}`;
            
            if (ingredientMeasure) {
                ingredientListItem.innerText += ` ${ingredientMeasure}`;
            }
            
            ingredientListItem.innerText += ')';
            
            ingredientList.appendChild(ingredientListItem);
        });
        
        recipeCard.appendChild(recipeName);
        recipeCard.appendChild(ingredientList);
        
        this._searchResults.appendChild(recipeCard);
    }
    
    addCreateRecipeButton() {
        let createRecipeBtn = document.createElement('a');
        createRecipeBtn.classList.add('btn', 'rounded-btn', 'large-btn', 'blue-bkgd');
        createRecipeBtn.id = 'create-food-item-btn';
        createRecipeBtn.innerText = 'Nouvel ingrédient';
        
        this._searchResults.appendChild(createRecipeBtn);
    }
}