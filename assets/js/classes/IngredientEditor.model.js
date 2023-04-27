class IngredientEditor extends KitchenEditor {
    #mealFusionBaseUri = 'http://localhost:8080/MealFusion/v1/ingredients?id=';
    
    constructor(apiToken, getRequestOptions, ingredientId) {
        super(apiToken, ingredientId);
        
        this._getRequestOptions = getRequestOptions;
    }
    
    get mealFusionBaseUri() {
        return this.#mealFusionBaseUri;
    }
    
    get getRequestOptions() {
        return this._getRequestOptions;
    }
    
    get deleteRequestOptions() {
        return this._deleteRequestOptions;
    }
    
    get endpoint() {
        return this._endpoint;
    }

    get response() {
        return this._response;
    }

    get ingredientData() {
        return this._response['data'];
    }

    get responseStatus() {
        return this._response['status'];
    }
    
    set endpoint(endpoint) {
        this._endpoint = endpoint;
    }

    set response(response) {
        this._response = response;
    }
    
    async showIngredientEditionForm() {
        this.clearPanel();
        this.endpoint = this.mealFusionBaseUri + this.itemId;
        this.response = await this.sendIngredientDataRequest(this.endpoint, 'GET');
        
        if (this.verifyApiResponse()) {
            this.addEditionModeTitle();
            this.buildEditionForm();
            this.addMeasureSelectListener();
            this.addFormButtonsListener()
            console.log(this.ingredientData);
        }
        
        else {
            this.showError()
        }
    }

    async editIngredientData() {
        const body = this.buildBodyOption();
        this.response = await this.sendIngredientDataRequest(this.endpoint, 'PUT', body);
        
        if (this.verifyApiResponse()) {
            
        }
        
        else {
            this.showError()
        }
    }
    
    addMeasureSelectListener() {
        const measureSelect = document.getElementById('measure-select');
        const nutrientsParamsTitle = document.getElementById('nutrients-params-title'); 
        measureSelect.addEventListener('change', () => {
            if (measureSelect.value !== 'grams') {
                nutrientsParamsTitle.innerHTML = `Paramètres nutritionnels pour<br>1 ingrédient`
            }
            
            else {
                nutrientsParamsTitle.innerHTML = `Paramètres nutritionnels pour<br>100 grammes`;
            }
        });
    }

    buildBodyOption() {
        const inputs = Array.from(this.ingredientDataForm.querySelectorAll("#ingredient-form input"));
        const body = {};
        
        // Gets the true value of each input.
        inputs.forEach(input => {
            body[input.id] = /^\d+$/.test(input.value) ? parseInt(input.value) : input.value;
        });

        // Replaces empty values of preparation and note keys with a NULL value
        ["preparation", "note"].forEach(key => {
            if (body[key] === '') {
                body[key] = null;
            }
        });
        
        // Adds a default value if "other" option is not selected
        if (!body.hasOwnProperty('measure')) {
            const select = this.ingredientDataForm.querySelector("#measure-select");
            const selectedOption = select.options[select.selectedIndex];
            const selectedValue = selectedOption.value;
            
            switch (selectedValue) {
                case 'grams':
                    body['measure'] = 'grammes'
                    break;
                case 'no-unit':
                    body['measure'] = null
                    break;
                default :
                    break;
            }
        }

        return JSON.stringify(body);
    }
    
    addFormButtonsListener() {
        const saveBtn = document.getElementById('save-ingredient-params-btn');
        const deleteBtn = document.getElementById('delete-ingredient-btn');
        const newSearchBtn = document.getElementById('new-search-btn');
        const formActionsBlock = document.getElementById('form-actions-block');
        const actionConfirmationBlock = document.getElementById('action-confirmation-block');
        const cancelDeletionBtn = document.getElementById('cancel-deletion-btn');
        const confirmDeletionBtn = document.getElementById('confirm-deletion-btn');
        
        saveBtn.addEventListener('click', () => {
            if (this.verifyFormData()) {
                console.log("les données ont l'air valides");
                this.editIngredientData();
            }
            
            else {
                console.log("Les données n'ont pas l'air valides");
            }
        })
        
        deleteBtn.addEventListener('click', () => {
            formActionsBlock.style.display = 'none';
            newSearchBtn.style.display = 'none';
            actionConfirmationBlock.style.display = 'flex';
        })
        
        newSearchBtn.addEventListener('click', () => {
            location.reload();
        })
        
        cancelDeletionBtn.addEventListener('click', () => {
            formActionsBlock.style.display = 'flex';
            newSearchBtn.style.display = 'flex';
            actionConfirmationBlock.style.display = 'none';
        })
        
        confirmDeletionBtn.addEventListener('click', () => {
            // this.sendIngredientDeletionRequest(this.endpoint, 'DELETE');
        })
    }
    
    async sendIngredientDataRequest(endpoint, method, body = '') {
        try {
            console.log(endpoint);
            const options = {
                method: method,
                headers: this._getRequestOptions[0].headers,
            }
            
            if (body) {
                console.log(body);
                options.body = body;
            }
            
            console.log(options);
            
            const response = await fetch(endpoint, options);
            return response.json();
        }
        
        catch (error) {
            console.error(error);
        }
    }
    
    verifyApiResponse() {
        return (this.responseStatus === 200);
    }
    
    addEditionModeTitle() {
        const ingredientTitle = document.createElement('h3');
        ingredientTitle.innerHTML = `Editer un ingrédient :<br>${this.ingredientData.name}${this.ingredientData.preparation ? ' ' + this.ingredientData.preparation : ''}`;
        this.adminPanel.appendChild(ingredientTitle);
    }
    
    buildEditionForm() {
        this.ingredientDataForm = document.createElement('form');
        this.ingredientDataForm.id = 'ingredient-form';
        this.ingredientDataForm.action = 'javascript:void(0)';
        
        const generalParamsSection = this.buildGeneralParamsSection();
        const nutrientsParamsSection = this.buildNutrientsParamsSection();
        const formValidationBlock = this.buildFormValidationBlock();

        this.ingredientDataForm.appendChild(generalParamsSection);
        this.ingredientDataForm.appendChild(nutrientsParamsSection);
        this.ingredientDataForm.appendChild(formValidationBlock);
        this.adminPanel.appendChild(this.ingredientDataForm);
    }
    
    buildGeneralParamsSection() {
        const generalParamsSection = document.createElement('section');
        const generalParamsTitle = document.createElement('h4');
        generalParamsTitle.textContent = 'Paramètres généraux';
        generalParamsTitle.classList.add('ingredient-params-header');
        
        const ingredientNameBlock = document.createElement('div');
        ingredientNameBlock.classList.add('ingredient-param');
        const ingredientNameLabel = document.createElement('label')
        ingredientNameLabel.textContent = "Nom de l'ingrédient :";
        const ingredientNameInput = document.createElement('input');
        ingredientNameInput.type = 'text';
        ingredientNameInput.id = 'name';
        ingredientNameInput.name = 'ingredient-name';
        ingredientNameInput.value = this.ingredientData.name;
        ingredientNameBlock.appendChild(ingredientNameLabel)
        ingredientNameBlock.appendChild(ingredientNameInput);
        
        const preparationBlock = document.createElement('div');
        preparationBlock.classList.add('ingredient-param');
        const preparationLabel = document.createElement('label');
        preparationLabel.textContent = 'Préparation :';
        const preparationInput = document.createElement('input');
        preparationInput.type = 'text';
        preparationInput.id = 'preparation';
        preparationInput.name = 'ingredient-preparation';
        preparationInput.value = this.ingredientData.preparation === null ? 'aucune' : this.ingredientData.preparation;
        preparationBlock.appendChild(preparationLabel)
        preparationBlock.appendChild(preparationInput);
        
        const ingredientTypeBlock = document.createElement('div');
        ingredientTypeBlock.classList.add('ingredient-param');
        const ingredientTypeLabel = document.createElement('label');
        ingredientTypeLabel.textContent = "Type :";
        const ingredientTypeInput = document.createElement('input');
        ingredientTypeInput.type = 'text';
        ingredientTypeInput.id = 'type';
        ingredientTypeInput.name = 'ingredient-type';
        ingredientTypeInput.value = this.ingredientData.type;
        ingredientTypeBlock.appendChild(ingredientTypeLabel);
        ingredientTypeBlock.appendChild(ingredientTypeInput);
        
        // ajouter un affichage conditionnel du select selon la valeur de this.ingredientData.measure
        const measureBlock = document.createElement('div');
        measureBlock.classList.add('ingredient-param');
        const measureLabel = document.createElement('label');
        measureLabel.textContent = 'Mesure :';
        const measureSelect = document.createElement('select');
        measureSelect.id = 'measure-select';
        measureSelect.name = 'ingredient-measure';
        const gramOption = document.createElement('option');
        gramOption.value = 'grams';
        gramOption.text = 'Gramme';
        measureSelect.appendChild(gramOption);
        const noUnitOption = document.createElement('option');
        noUnitOption.value = 'no-unit';
        noUnitOption.text = 'Aucune unité';
        measureSelect.appendChild(noUnitOption);
        const otherOption = document.createElement('option');
        otherOption.value = 'others';
        otherOption.text = 'Autres';
        measureSelect.appendChild(otherOption);
        
        switch (this.ingredientData.measure) {
            case 'grammes':
                gramOption.selected = true;
                break;
            case null:
                noUnitOption.selected = true;
                break;
            default:
                otherOption.selected = true;
                const measureInput = document.createElement('input');
                measureInput.type = 'text';
                measureInput.id = 'measure-other';
                measureInput.name = 'ingredient-measure-other';
                measureInput.value = this.ingredientData.measure;
                measureBlock.appendChild(measureInput);
        }
        
        measureSelect.addEventListener('change', () => {
            if (measureSelect.value === 'others') {
                const otherMeasureBlock = document.createElement('div');
                otherMeasureBlock.id = 'other-measure-block';
                otherMeasureBlock.classList = 'ingredient-param';
                const otherMeasureLabel = document.createElement('label');
                otherMeasureLabel.textContent = 'Nom de la mesure';
                const otherMeasureInput = document.createElement('input');
                otherMeasureInput.type = 'text';
                otherMeasureInput.id = 'measure';
                otherMeasureInput.name = 'ingredient-other-measure';
                otherMeasureInput.value = this.ingredientData.measure !== null && this.ingredientData.measure !== 'gram' && this.ingredientData.measure !== 'unit' ? this.ingredientData.measure : '';
                otherMeasureBlock.appendChild(otherMeasureLabel)
                otherMeasureBlock.appendChild(otherMeasureInput);
                generalParamsSection.appendChild(otherMeasureBlock);
            }
            
            else {
                const otherMeasureInput = document.getElementById('other-measure-block');
                if (otherMeasureInput !== null) {
                    generalParamsSection.removeChild(otherMeasureInput);
                }
            }
        });
        
        measureBlock.appendChild(measureLabel);
        measureBlock.appendChild(measureSelect);
        
        generalParamsSection.appendChild(generalParamsTitle);
        generalParamsSection.appendChild(ingredientNameBlock);
        generalParamsSection.appendChild(preparationBlock);
        generalParamsSection.appendChild(ingredientTypeBlock);
        generalParamsSection.appendChild(measureBlock);
        
        return generalParamsSection;
    }
    
    buildNutrientsParamsSection() {
        const nutrientsParamsSection = document.createElement('section');
        const nutrientsParamsTitle = document.createElement('h4');
        nutrientsParamsTitle.id = 'nutrients-params-title';
        nutrientsParamsTitle.innerHTML = `Paramètres nutritionnels pour<br>${this.ingredientData.measure === 'grammes' ? '100 grammes' : `1 ${this.ingredientData.name}`}`;
        nutrientsParamsTitle.classList.add('ingredient-params-header');
        nutrientsParamsSection.appendChild(nutrientsParamsTitle);
        
        const caloriesBlock = document.createElement('div');
        caloriesBlock.classList.add('ingredient-param');
        const caloriesLabel = document.createElement('label');
        caloriesLabel.textContent = 'Calories :';
        const caloriesInput = document.createElement('input');
        caloriesInput.type = 'number';
        caloriesInput.id = 'calories';
        caloriesInput.name = 'calories';
        caloriesInput.value = this.ingredientData.calories;
        caloriesBlock.appendChild(caloriesLabel);
        caloriesBlock.appendChild(caloriesInput);
        nutrientsParamsSection.appendChild(caloriesBlock);
        
        const lipidsBlock = document.createElement('div');
        lipidsBlock.classList.add('ingredient-param');
        const lipidsLabel = document.createElement('label');
        lipidsLabel.textContent = 'Lipides :';
        const lipidsInput = document.createElement('input');
        lipidsInput.type = 'number';
        lipidsInput.id = 'fat';
        lipidsInput.name = 'fat';
        lipidsInput.value = this.ingredientData.fat;
        lipidsBlock.appendChild(lipidsLabel);
        lipidsBlock.appendChild(lipidsInput);
        nutrientsParamsSection.appendChild(lipidsBlock);
        
        const proteinsBlock = document.createElement('div');
        proteinsBlock.classList.add('ingredient-param');
        const proteinsLabel = document.createElement('label');
        proteinsLabel.textContent = 'Protéines :';
        const proteinsInput = document.createElement('input');
        proteinsInput.type = 'number';
        proteinsInput.id = 'proteins';
        proteinsInput.name = 'proteins';
        proteinsInput.value = this.ingredientData.proteins;
        proteinsBlock.appendChild(proteinsLabel);
        proteinsBlock.appendChild(proteinsInput);
        nutrientsParamsSection.appendChild(proteinsBlock);
        
        const carbsBlock = document.createElement('div');
        carbsBlock.classList.add('ingredient-param');
        const carbsLabel = document.createElement('label');
        carbsLabel.textContent = 'Glucides :';
        const carbsInput = document.createElement('input');
        carbsInput.type = 'number';
        carbsInput.id = 'carbs';
        carbsInput.name = 'carbs';
        carbsInput.value = this.ingredientData.carbs;
        carbsBlock.appendChild(carbsLabel);
        carbsBlock.appendChild(carbsInput);
        nutrientsParamsSection.appendChild(carbsBlock);
        
        const sodiumBlock = document.createElement('div');
        sodiumBlock.classList.add('ingredient-param');
        const sodiumLabel = document.createElement('label');
        sodiumLabel.textContent = 'Sodium :';
        const sodiumInput = document.createElement('input');
        sodiumInput.type = 'number';
        sodiumInput.id = 'sodium';
        sodiumInput.name = 'sodium';
        sodiumInput.value = this.ingredientData.sodium;
        sodiumBlock.appendChild(sodiumLabel);
        sodiumBlock.appendChild(sodiumInput);
        nutrientsParamsSection.appendChild(sodiumBlock);
        
        const fibersBlock = document.createElement('div');
        fibersBlock.classList.add('ingredient-param');
        const fibersLabel = document.createElement('label');
        fibersLabel.textContent = 'Sucre :';
        const fibersInput = document.createElement('input');
        fibersInput.type = 'number';
        fibersInput.id = 'sugar';
        fibersInput.name = 'sucre';
        fibersInput.value = this.ingredientData.fibers;
        fibersBlock.appendChild(fibersLabel);
        fibersBlock.appendChild(fibersInput);
        nutrientsParamsSection.appendChild(fibersBlock);
        
        const sugarBlock = document.createElement('div');
        sugarBlock.classList.add('ingredient-param');
        const sugarLabel = document.createElement('label');
        sugarLabel.textContent = 'Sucre :';
        const sugarInput = document.createElement('input');
        sugarInput.type = 'number';
        sugarInput.id = 'fibers';
        sugarInput.name = 'fibre';
        sugarInput.value = this.ingredientData.sugar;
        sugarBlock.appendChild(sugarLabel);
        sugarBlock.appendChild(sugarInput);
        nutrientsParamsSection.appendChild(sugarBlock);
        
        const sourceBlock = document.createElement('div');
        sourceBlock.classList.add('ingredient-param');
        const sourceLabel = document.createElement('label');
        sourceLabel.textContent = 'Note :';
        const sourceInput = document.createElement('input');
        sourceInput.type = 'text';
        sourceInput.id = 'note';
        sourceInput.name = 'source';
        sourceInput.value = this.ingredientData.data_source;
        sourceBlock.appendChild(sourceLabel);
        sourceBlock.appendChild(sourceInput);
        nutrientsParamsSection.appendChild(sourceBlock);
        
        return nutrientsParamsSection;
    }
    
    buildFormValidationBlock() {
        const formValidationBlock = document.createElement('div');
        formValidationBlock.id = 'form-btns-block';
        
        const actionButtonsBlock = document.createElement('div');
        actionButtonsBlock.id = 'form-actions-block';
        const saveFormDataButton = document.createElement('button');
        saveFormDataButton.id = 'save-ingredient-params-btn';
        saveFormDataButton.classList = 'btn tiny-btn rounded-btn blue-bkgd';
        saveFormDataButton.textContent = 'Enregistrer';
        const deleteIngredientButton = document.createElement('button');
        deleteIngredientButton.id = 'delete-ingredient-btn';
        deleteIngredientButton.classList = 'btn tiny-btn rounded-btn red-bkgd';
        deleteIngredientButton.textContent = 'Supprimer';
        actionButtonsBlock.appendChild(saveFormDataButton);
        actionButtonsBlock.appendChild(deleteIngredientButton);
        
        const ingredientSearchButton = document.createElement('button');
        ingredientSearchButton.classList = 'btn large-btn rounded-btn blue-bkgd';
        ingredientSearchButton.id = 'new-search-btn';
        ingredientSearchButton.textContent = 'Nouvelle recherche';
        
        const actionConfirmationBlock = document.createElement('div');
        actionConfirmationBlock.id = 'action-confirmation-block';
        actionConfirmationBlock.style.display = 'none';
        const cancelIngredienDeletionBtn = document.createElement('button');
        cancelIngredienDeletionBtn.id = 'cancel-deletion-btn';
        cancelIngredienDeletionBtn.classList = 'btn circle-btn blue-bkgd';
        cancelIngredienDeletionBtn.textContent = 'Non';
        const ingredientDeletionMessage = document.createElement('div');
        ingredientDeletionMessage.innerHTML = '<p>Etes-vous sûr de vouloir supprimer cet ingrédient ?</p>';
        const confirmIngredientDeletionBtn = document.createElement('button');
        confirmIngredientDeletionBtn.id = 'confirm-deletion-btn';
        confirmIngredientDeletionBtn.classList = 'btn circle-btn red-bkgd';
        confirmIngredientDeletionBtn.textContent = 'Oui';
        actionConfirmationBlock.appendChild(cancelIngredienDeletionBtn);
        actionConfirmationBlock.appendChild(ingredientDeletionMessage);
        actionConfirmationBlock.appendChild(confirmIngredientDeletionBtn);
        
        formValidationBlock.appendChild(actionButtonsBlock);
        formValidationBlock.appendChild(ingredientSearchButton);
        formValidationBlock.appendChild(actionConfirmationBlock);
        
        return formValidationBlock
    }
    
    clearPanel() {
        this.adminPanel.innerHTML = '';
    }
    
    showError() {
        const errorElt = document.createElement('div');
        errorElt.textContent = 'Ressource indisponible';
        
        const backButton = document.createElement('button');
        backButton.textContent = 'Retour';
        backButton.addEventListener('click', () => {
            location.reload();
        });
        
        this.adminPanel.appendChild(errorElt);
        this.adminPanel.appendChild(backButton);
    }
}