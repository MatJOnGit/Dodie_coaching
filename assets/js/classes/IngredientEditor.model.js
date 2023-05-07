class IngredientEditor extends KitchenManager {    
    constructor(apiKey, ingredientId) {
        super(apiKey);
        
        this._endpoint = `http://localhost:8080/MealFusion/v1/ingredients?id=${ingredientId}`;
        this._itemType = 'ingredient';
        this._itemId = ingredientId;
        
        this._apiHandler = new APIHandler(this.apiKey);
    }
    
    get itemType() {
        return this._itemType;
    }
    
    get itemId() {
        return this._itemId;
    }
    
    set itemId(itemId) {
        this._itemId = itemId;
    }
    
    get endpoint() {
        return this._endpoint;
    }
    
    get apiHandler() {
        return this._apiHandler;
    }
    
    get getRequestResponse() {
        return this._getRequestResponse;
    }
    
    get ingredientData() {
        return this._getRequestResponse['data'];
    }
    
    get getRequestStatus() {
        return this._getRequestResponse['status'];
    }
    
    get postRequestResponse() {
        return this._postRequestResponse;
    }
    
    set getRequestResponse(response) {
        this._getRequestResponse = response;
    }
    
    set postRequestResponse(response) {
        this._postRequestResponse = response;
    }
    
    async showIngredientEditionForm() {
        this.clearPanel();
        this.getRequestResponse = await this.apiHandler.sendRequest(this.endpoint, 'GET');
        
        if (this.verifyApiResponse()) {
            this.addEditionModeTitle();
            this.addEditionForm();
            this.addMeasureSelectListener();
            this.addFormButtonsListener();
        }
        
        else {
            this.showError('Ressource indisponible')
        }
    }
    
    verifyApiResponse() {
        return (this.getRequestStatus === 200);
    }
    
    addEditionModeTitle() {
        const ingredientItem = `${this.ingredientData.name} ${this.ingredientData.preparation ? ' ' + this.ingredientData.preparation : ''}`;
        const genericTitle = `Editer un ingrédient :`;
        const editIngredientTitle = KitchenElementsBuilder.buildPageTitle(genericTitle, ingredientItem);
        
        this.adminPanel.appendChild(editIngredientTitle);
    }
    
    addEditionForm() {
        this.kitchenDataForm = document.createElement('form');
        this.kitchenDataForm.id = 'ingredient-form';
        this.kitchenDataForm.action = 'javascript:void(0)';
        
        const confirmDeletionMessage = 'Etes-vous sûr de vouloir supprimer cet ingrédient ?';
        
        const generalParamsSection = this.buildGeneralParamsSection();
        const nutrientsParamsSection = this.buildNutrientsParamsSection();
        const formValidationBlock = KitchenElementsBuilder.buildEditionValidationBlock('ingredient', confirmDeletionMessage);
        
        this.kitchenDataForm.append(generalParamsSection, nutrientsParamsSection, formValidationBlock);
        
        this.adminPanel.append(this.kitchenDataForm);
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
        
        const generalSectionTitle = `Paramètres généraux`;
        
        const generalSectionHeader = KitchenElementsBuilder.buildSectionHeader('ingredient', generalSectionTitle);
        const nameBlock = KitchenElementsBuilder.buildInputBlock(generalParams.name, this.itemType);
        const preparationBlock = KitchenElementsBuilder.buildInputBlock(generalParams.preparation, this.itemType);
        const typeBlock = KitchenElementsBuilder.buildInputBlock(generalParams.type, this.itemType);
        const otherMeasureBlock = KitchenElementsBuilder.buildInputBlock(generalParams.otherMeasure, this.itemType);
        
        const defaultOption = this.getMeasureDefaultOption();
        const measureSelectionBlock = KitchenElementsBuilder.buildSelectBlock(generalParams.measure, this.itemType, defaultOption);
        
        generalParamsSection.append(generalSectionHeader, nameBlock, preparationBlock, typeBlock, measureSelectionBlock, otherMeasureBlock);
        
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
                label: 'Lipides (g)',
                type: 'number',
                value: this.ingredientData.fat,
                required: true
            },
            proteins: {
                id: 'proteins',
                label: 'Protéines (g)',
                type: 'number',
                value: this.ingredientData.proteins,
                required: true
            },
            carbs: {
                id: 'carbs',
                label: 'Glucides (g)',
                type: 'number',
                value: this.ingredientData.carbs,
                required: true
            },
            sodium: {
                id: 'sodium',
                label: 'Sodium (mg)',
                type: 'number',
                value: this.ingredientData.sodium,
                required: true
            },
            sugar: {
                id: 'sugar',
                label: 'Sucre (g)',
                type: 'number',
                value: this.ingredientData.sugar,
                required: true
            },
            fibers: {
                id: 'fibers',
                label: 'Fibres (g)',
                type: 'number',
                value: this.ingredientData.fibers,
                required: true
            },
            note: {
                id: 'note',
                label: 'Note',
                type: 'text',
                value: this.ingredientData.data_source
            },
        };
        
        const nutrientsSectionTitle = `Paramètres nutritionnels pour<br>
        ${this.ingredientData.measure === 'grammes' ? '100 grammes' : `1 ${this.ingredientData.name}`}`;
        
        const nutrientsSectionHeader = KitchenElementsBuilder.buildSectionHeader('nutrients', nutrientsSectionTitle);
        const caloriesBlock = KitchenElementsBuilder.buildInputBlock(nutrientsParams.calories, this.itemType);
        const fatBlock = KitchenElementsBuilder.buildInputBlock(nutrientsParams.fat, this.itemType);
        const proteinsBlock = KitchenElementsBuilder.buildInputBlock(nutrientsParams.proteins, this.itemType);
        const carbsBlock = KitchenElementsBuilder.buildInputBlock(nutrientsParams.carbs, this.itemType);
        const sodiumBlock = KitchenElementsBuilder.buildInputBlock(nutrientsParams.sodium, this.itemType);
        const fibersBlock = KitchenElementsBuilder.buildInputBlock(nutrientsParams.sugar, this.itemType);
        const sugarBlock = KitchenElementsBuilder.buildInputBlock(nutrientsParams.fibers, this.itemType);
        const noteBlock = KitchenElementsBuilder.buildInputBlock(nutrientsParams.note, this.itemType);
        
        nutrientsParamsSection.append(nutrientsSectionHeader, caloriesBlock, fatBlock, proteinsBlock, carbsBlock, sodiumBlock, fibersBlock, sugarBlock, noteBlock);
        
        return nutrientsParamsSection;
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
        const otherMeasureInput = otherMeasureBlock.getElementsByTagName('input')[0];
        const nutrientsParamsTitle = document.getElementById('nutrients-params-title');
        
        const updateNutrientsParamsTitle = () => {
            if (measureSelect.value === 'grams') {
                nutrientsParamsTitle.innerHTML = `Paramètres nutritionnels pour<br>100 grammes de ${this.ingredientData.name}`;
            }
            
            else {
                nutrientsParamsTitle.innerHTML = `Paramètres nutritionnels pour<br>1 ${this.ingredientData.name}`;
            }
        }
        
        const toggleOtherMeasureBlock = () => {
            if (measureSelect.value === 'others') {
                otherMeasureBlock.classList.remove('hidden');
                otherMeasureInput.required = true;
            }
            
            else {
                otherMeasureBlock.classList.add('hidden');
                otherMeasureInput.required = false;
            }
        }
        
        measureSelect.addEventListener('change', () => {
            toggleOtherMeasureBlock();
            updateNutrientsParamsTitle();
        });
    }
    
    addFormButtonsListener() {
        const saveButton = document.getElementById('save-ingredient-params-btn');
        const deleteButton = document.getElementById('delete-ingredient-btn');
        const newSearchButton = document.getElementById('new-search-btn');
        const formActionsBlock = document.getElementById('form-actions-block');
        const actionConfirmationBlock = document.getElementById('action-confirmation-block');
        const cancelDeletionButton = document.getElementById('cancel-deletion-btn');
        const confirmDeletionButton = document.getElementById('confirm-deletion-btn');
        
        const addClickListener = (button, handler) => {
            button.addEventListener('click', handler);
        }
        
        addClickListener(saveButton, (e) => this.handleSaveBtnClick(e));
        addClickListener(deleteButton, (e) => this.handleDeleteBtnClick(e, formActionsBlock, newSearchButton, actionConfirmationBlock));
        addClickListener(newSearchButton, (e) => this.handleNewSearchBtnClick(e));
        addClickListener(cancelDeletionButton, (e) => this.handleCancelDeletionBtnClick(e, formActionsBlock, newSearchButton, actionConfirmationBlock));
        addClickListener(confirmDeletionButton, (e) => this.handleConfirmDeletionBtnClick(e));
    }
    
    async handleSaveBtnClick(e) {
        e.preventDefault();
        if (this.verifyFormData()) {
            const body = this.buildIngredientBodyOption();
            console.log(body);
            this.postRequestResponse = await this.apiHandler.sendRequest(this.endpoint, 'POST', body);
            this.managePostIngredientResponse(this.postRequestResponse);
        }
        
        else {
            this.scrollTop();
            const errorMessage = KitchenElementsBuilder.buildErrorMessageBlock(`Certaines valeurs du formulaire ne sont pas valides`);
            this.showTemporaryAlert(errorMessage);
        }
    }
    
    handleDeleteBtnClick(e, formActionsBlock, newSearchBtn, actionConfirmationBlock) {
        e.preventDefault();
        formActionsBlock.classList.add('hidden');
        newSearchBtn.classList.add('hidden');
        actionConfirmationBlock.classList.remove('hidden');
    }
    
    handleCancelDeletionBtnClick(e, formActionsBlock, newSearchBtn, actionConfirmationBlock) {
        e.preventDefault();
        formActionsBlock.classList.remove('hidden');
        newSearchBtn.classList.remove('hidden');
        actionConfirmationBlock.classList.add('hidden');
    }
    
    async handleConfirmDeletionBtnClick(e) {
        e.preventDefault();
        this.deleteRequestResponse = await this.apiHandler.sendRequest(this.endpoint, 'DELETE');
        this.manageDeleteIngredientResponse(this.deleteRequestResponse);
    }
    
    manageDeleteIngredientResponse(deleteRequestResponse) {
        if (deleteRequestResponse.status === 200) {
            sessionStorage.setItem('IsLastIngredientDeleted', 'true');
            location.reload();
        }
        
        else {
            this.scrollTop();
            const errorMessage = KitchenElementsBuilder.buildErrorMessageBlock(`Echec de la suppression de l'ingrédient`);
            this.showTemporaryAlert(errorMessage);
        }
    }
    
    managePostIngredientResponse(postRequestResponse) {
        if (postRequestResponse.status === 200) {
            this.scrollTop();
            const successMessageBlock = KitchenElementsBuilder.buildSuccessMessageBlock(`Mise à jour de l'ingrédient réussie`);
            this.showTemporaryAlert(successMessageBlock);
        }
        
        else {
            this.scrollTop();
            const errorMessage = KitchenElementsBuilder.buildErrorMessageBlock(`Echec de la mise à jour de l'ingrédient`);
            this.showTemporaryAlert(errorMessage);
        }
    }
    
    showError(error) {
        const errorElt = document.createElement('div');
        errorElt.textContent = error;
        
        const backButton = document.createElement('button');
        backButton.textContent = 'Retour';
        backButton.addEventListener('click', () => {
            location.reload();
        });
        
        this.adminPanel.appendChild(errorElt);
        this.adminPanel.appendChild(backButton);
    }
}