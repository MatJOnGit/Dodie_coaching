class IngredientEditor extends KitchenEditor {    
    constructor(apiKey, ingredientId) {
        super(apiKey, ingredientId);
        
        this._apiBaseUri = 'http://localhost:8080/MealFusion/v1/ingredients?id=';
        this._itemType = 'ingredient';
        this._getRequestOptions = [
            {
                method: 'GET',
                headers: {
                    'Content-Type': 'application/json',
                    'Authorization': 'Bearer ' + this.apiKey
                }
            }
        ];
    }

    get itemType() {
        return this._itemType;
    }
    
    get apiBaseUri() {
        return this._apiBaseUri;
    }
    
    get getRequestOptions() {
        return this._getRequestOptions;
    }
    
    get onlyNumbersRegex() {
        return this._onlyNumbersRegex;
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
    
    set response(response) {
        this._response = response;
    }
    
    async sendIngredientDataRequest(endpoint, method, body = '') {
        try {
            const options = {
                method: method,
                headers: this.getRequestOptions[0].headers,
            }
            
            if (body) {
                options.body = body;
            }
            
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
    
    async showIngredientEditionForm() {
        this.clearPanel();
        const endpoint = this.apiBaseUri + this.itemId;
        this.response = await this.sendIngredientDataRequest(endpoint, 'GET');
        
        if (this.verifyApiResponse()) {
            this.addEditionModeTitle();
            this.buildEditionForm();
            this.addMeasureSelectListener();
            this.addFormButtonsListener();
        }
        
        else {
            this.showError()
        }
    }
    
    addEditionModeTitle() {
        const ingredientTitle = document.createElement('h3');
        ingredientTitle.innerHTML = `
            Editer un ingrédient :
            ${this.ingredientData.name} ${this.ingredientData.preparation ? ' ' + this.ingredientData.preparation : ''}
        `;
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
        
        const generalParams = {
            name: {
                id: 'name',
                label: 'Nom',
                type: 'text',
                value: this.ingredientData.name,
                required: true
            },
            preparation: {
                id: 'preparation',
                label: 'Préparation',
                type: 'text',
                value: this.ingredientData.preparation === null ? 'aucune' : this.ingredientData.preparation,
                required: false
            },
            type: {
                id: 'type',
                label: 'Type',
                type: 'text',
                value: this.ingredientData.type,
                required: true
            },
            otherMeasure: {
                id: 'other-measure',
                label: 'Autre mesure',
                type: 'text',
                value: this.ingredientData.measure,
                required: true,
                hidden: this.ingredientData.measure === 'grammes' || this.ingredientData.measure === null
            },
            measure: {
                id: 'measure',
                label: 'Mesure',
                type: 'select',
                options: [
                    { value: 'grams', text: 'gramme' },
                    { value: 'no-unit', text: 'aucune unité' },
                    { value: 'others', text: 'autres' }
                ]
            }
        };
        
        const sectionHeader = `<h4 class='ingredient-params-header'>Paramètres généraux</h4>`;
        const nameBlock = KitchenElementsBuilder.buildInputBlock(generalParams.name, this.itemType);
        const preparationBlock = KitchenElementsBuilder.buildInputBlock(generalParams.preparation, this.itemType);
        const typeBlock = KitchenElementsBuilder.buildInputBlock(generalParams.type, this.itemType);
        const otherMeasureBlock = KitchenElementsBuilder.buildInputBlock(generalParams.otherMeasure, this.itemType);
        
        const defaultOption = this.getMeasureDefaultOption();
        const measureSelectionBlock = KitchenElementsBuilder.buildSelectBlock(generalParams.measure, this.itemType, defaultOption);
        
        generalParamsSection.innerHTML = `
            ${sectionHeader}
            ${nameBlock}
            ${preparationBlock}
            ${typeBlock}
            ${measureSelectionBlock}
            ${otherMeasureBlock}
        `;
        
        return generalParamsSection;
    }
    
    buildNutrientsParamsSection() {
        const nutrientsParamsSection = document.createElement('section');
        
        const nutrientsParams = {
            calories: {
                id: 'calories',
                label: 'Calories',
                type: 'number',
                value: this.ingredientData.calories,
                required: true
            },
            fat: {
                id: 'fat',
                label: 'Lipides',
                type: 'number',
                value: this.ingredientData.fat,
                required: true
            },
            proteins: {
                id: 'proteins',
                label: 'Protéines',
                type: 'number',
                value: this.ingredientData.proteins,
                required: true
            },
            carbs: {
                id: 'carbs',
                label: 'Glucides',
                type: 'number',
                value: this.ingredientData.carbs,
                required: true
            },
            sodium: {
                id: 'sodium',
                label: 'Sodium',
                type: 'number',
                value: this.ingredientData.sodium,
                required: true
            },
            sugar: {
                id: 'sugar',
                label: 'Sucre',
                type: 'number',
                value: this.ingredientData.sugar,
                required: true
            },
            fibers: {
                id: 'fibers',
                label: 'Fibres',
                type: 'number',
                value: this.ingredientData.fibers,
                required: true
            },
            note: {
                id: 'note',
                label: 'Note',
                type: 'text',
                value: this.ingredientData.data_source,
                required: true
            },
        };
        
        const sectionHeader = `
            <h4 id='nutrients-params-title' class='ingredient-params-header'>
                Paramètres nutritionnels pour<br>
                ${this.ingredientData.measure === 'grammes' ? '100 grammes' : `1 ${this.ingredientData.name}`}
            </h4>
        `;
        
        const caloriesBlock = KitchenElementsBuilder.buildInputBlock(nutrientsParams.calories, this.itemType);
        const fatBlock = KitchenElementsBuilder.buildInputBlock(nutrientsParams.fat, this.itemType);
        const proteinsBlock = KitchenElementsBuilder.buildInputBlock(nutrientsParams.proteins, this.itemType);
        const carbsBlock = KitchenElementsBuilder.buildInputBlock(nutrientsParams.carbs, this.itemType);
        const sodiumBlock = KitchenElementsBuilder.buildInputBlock(nutrientsParams.sodium, this.itemType);
        const fibersBlock = KitchenElementsBuilder.buildInputBlock(nutrientsParams.sugar, this.itemType);
        const sugarBlock = KitchenElementsBuilder.buildInputBlock(nutrientsParams.fibers, this.itemType);
        const noteBlock = KitchenElementsBuilder.buildInputBlock(nutrientsParams.note, this.itemType);
        
        nutrientsParamsSection.innerHTML = `
            ${sectionHeader}
            ${caloriesBlock}
            ${fatBlock}
            ${proteinsBlock}
            ${carbsBlock}
            ${sodiumBlock}
            ${fibersBlock}
            ${sugarBlock}
            ${noteBlock}
        `;
        
        return nutrientsParamsSection;
    }
    
    buildFormValidationBlock() {
        const formValidationBlock = document.createElement('div');
        
        formValidationBlock.innerHTML = `
            <div id='form-btns-block'>
                <div id='form-actions-block'>
                    <button id='save-ingredient-params-btn' class='btn tiny-btn rounded-btn blue-bkgd'>Enregistrer</button>
                    <button id='delete-ingredient-btn' class='btn tiny-btn rounded-btn red-bkgd'>Supprimer</button>
                </div>
                
                <button id='new-search-btn' class='btn large-btn rounded-btn blue-bkgd'>Nouvelle recherche</button>
                
                <div id='action-confirmation-block' class='hidden'>
                    <button id='cancel-deletion-btn' class='btn circle-btn blue-bkgd'>Non</button>
                    <div>
                        <p>Etes-vous sûr de vouloir supprimer cet ingrédient ?</p>
                    </div>
                    <button id='confirm-deletion-btn' class='btn circle-btn red-bkgd'>Oui</button>
                </div>
            </div>
        `;
        
        return formValidationBlock.firstElementChild;
    }
    
    getMeasureDefaultOption() {
        const DEFAULT_OPTIONS = {
            'grammes': 'grammes',
            null: 'aucune unité',
            default: 'autres'
        };
        
        const measure = this.ingredientData.measure;
        return DEFAULT_OPTIONS[measure] || DEFAULT_OPTIONS.default;
    }
    
    addMeasureSelectListener() {
        const measureSelect = document.getElementById('measure-select');
        const otherMeasureBlock = document.getElementById('other-measure-block');
        const nutrientsParamsTitle = document.getElementById('nutrients-params-title');
        
        const updateNutrientsParamsTItle = () => {
            switch (measureSelect.value) {
                case 'grams':
                    nutrientsParamsTitle.innerHTML = `Paramètres nutritionnels pour<br>100 grammes de ${this.ingredientData.name}`;
                    break;
                default :
                    nutrientsParamsTitle.innerHTML = `Paramètres nutritionnels pour<br>1 ${this.ingredientData.name}`;
                    break;
            }
        }
        
        const toggleOtherMeasureBlock = () => {
            switch (measureSelect.value) {
                case 'others':
                    otherMeasureBlock.classList.remove('hidden');
                    break;
                default:
                    otherMeasureBlock.classList.add('hidden');
            }
        }
        
        measureSelect.addEventListener('change', () => {
            toggleOtherMeasureBlock()
            updateNutrientsParamsTItle();
        });
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
    
    addFormButtonsListener() {
        const saveButton = document.getElementById('save-ingredient-params-btn');
        const deleteButton = document.getElementById('delete-ingredient-btn');
        console.log(deleteButton);
        const newSearchButton = document.getElementById('new-search-btn');
        const formActionsBlock = document.getElementById('form-actions-block');
        const actionConfirmationBlock = document.getElementById('action-confirmation-block');
        const cancelDeletionButton = document.getElementById('cancel-deletion-btn');
        const confirmDeletionButton = document.getElementById('confirm-deletion-btn');
        
        const addClickListener = (button, handler) => {
            button.addEventListener('click', handler);
        }
        
        addClickListener(saveButton, () => this.handleSaveBtnClick());
        addClickListener(deleteButton, () => this.handleDeleteBtnClick(formActionsBlock, newSearchButton, actionConfirmationBlock));
        addClickListener(newSearchButton, () => this.handleNewSearchBtnClick());
        addClickListener(cancelDeletionButton, () => this.handleCancelDeletionBtnClick(formActionsBlock, newSearchButton, actionConfirmationBlock));
        addClickListener(confirmDeletionButton, () => this.handleConfirmDeletionBtnClick());
    }

    handleSaveBtnClick() {
        if (this.verifyFormData()) {
            console.log("les données ont l'air valides");
            this.editIngredientData();
        }
        
        else {
            console.log("Les données n'ont pas l'air valides");
        }
    }
      
    handleDeleteBtnClick(formActionsBlock, newSearchBtn, actionConfirmationBlock) {
        formActionsBlock.classList.add('hidden');
        newSearchBtn.classList.add('hidden');
        actionConfirmationBlock.classList.remove('hidden');
    }
    
    handleNewSearchBtnClick() {
        location.reload();
    }
    
    handleCancelDeletionBtnClick(formActionsBlock, newSearchBtn, actionConfirmationBlock) {
        formActionsBlock.classList.remove('hidden');
        newSearchBtn.classList.remove('hidden');
        actionConfirmationBlock.classList.add('hidden');
    }
    
    handleConfirmDeletionBtnClick() {
        // this.sendIngredientDeletionRequest(this.endpoint, 'DELETE');
    }
    
    verifyFormData() {
        const inputValidationResults = Array.from(document.querySelectorAll("#ingredient-form input"))
        .map(input => ({
            value: input.value,
            expectedType: input.type,
        }))
        .every(({ expectedType, value }) => this.validateType(value, expectedType));
        
        return inputValidationResults;
    }
    
    validateType(value, type) {
        console.log(value);
        console.log(type);
        switch (type) {
            case 'number':
                return !isNaN(value);
            case 'text':
                return isNaN(value) || value === '';
            default:
                return false;
        }
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

    verifyFormData() {
        const inputValidationResults = Array.from(document.querySelectorAll("#ingredient-form input"))
        .map(input => ({
            value: input.value,
            expectedType: input.type,
        }))
        .every(({ expectedType, value }) => this.validateType(value, expectedType));
        
        return inputValidationResults;
    }
    
    validateType(value, type) {
        console.log(value);
        console.log(type);
        switch (type) {
            case 'number':
                return !isNaN(value);
            case 'text':
                return isNaN(value) || value === '';
            default:
                return false;
        }
    }
    
    buildBodyOption() {
        const inputs = Array.from(this.ingredientDataForm.querySelectorAll("#ingredient-form input"));
        const body = {};
        
        // Gets the true value of each input.
        inputs.forEach(input => {
            body[input.id] = this.onlyNumbersRegex.test(input.value) ? parseInt(input.value) : input.value;
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
}