class IngredientCreator extends KitchenManager {
    constructor(apiKey) {
        super(apiKey);
        
        this._endpoint = 'http://localhost:8080/MealFusion/v1/ingredients';
        this._itemType = 'ingredient';
        
        this._apiHandler = new APIHandler(this.apiKey);
    }
    
    showIngredientCreationForm() {
        this.clearPanel();
        this.addCreationModeTitle();
        this.addCreationForm();
        this.addMeasureSelectListener();
        this.addFormButtonsListener();
    }
    
    get itemType() {
        return this._itemType;
    }
    
    get endpoint() {
        return this._endpoint;
    }
    
    get apiHandler() {
        return this._apiHandler;
    }
    
    get putRequestResponse() {
        return this._putRequestResponse;
    }
    
    set putRequestResponse(response) {
        this._putRequestResponse = response;
    }
    
    addCreationModeTitle() {
        const genericTitle = `Ajouter un ingrédient`;
        const createIngredientTitle = KitchenElementsBuilder.buildPageTitle(genericTitle);
        
        this.adminPanel.appendChild(createIngredientTitle);
    }
    
    addCreationForm() {
        this.ingredientDataForm = document.createElement('form');
        this.ingredientDataForm.id = 'ingredient-form';
        this.ingredientDataForm.action = 'javascript:void(0)';
        
        const generalParamsSection = this.buildGeneralParamsSection();
        const nutrientsParamsSection = this.buildNutrientsParamsSection();
        const creationFormValidationBlock = KitchenElementsBuilder.buildCreationFormValidationBlock('ingredient');
        
        this.ingredientDataForm.append(generalParamsSection, nutrientsParamsSection, creationFormValidationBlock);
        
        this.adminPanel.append(this.ingredientDataForm);
    }
    
    buildGeneralParamsSection() {
        const generalParamsSection = document.createElement('section');
        
        const generalParams = {
            name: {
                id: 'name',
                label: 'Nom',
                type: 'text',
                value: '',
                required: true
            },
            preparation: {
                id: 'preparation',
                label: 'Préparation',
                type: 'text',
                value: '',
                required: false
            },
            type: {
                id: 'type',
                label: 'Type',
                type: 'text',
                value: '',
                required: true
            },
            otherMeasure: {
                id: 'other-measure',
                label: 'Autre mesure',
                type: 'text',
                value: '',
                required: true,
                hidden: true
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
        
        const measureSelectionBlock = KitchenElementsBuilder.buildSelectBlock(generalParams.measure, this.itemType, 'grams');
        
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
                value: '',
                required: true
            },
            fat: {
                id: 'fat',
                label: 'Lipides (g)',
                type: 'number',
                value: '',
                required: true
            },
            proteins: {
                id: 'proteins',
                label: 'Protéines (g)',
                type: 'number',
                value: '',
                required: true
            },
            carbs: {
                id: 'carbs',
                label: 'Glucides (g)',
                type: 'number',
                value: '',
                required: true
            },
            sodium: {
                id: 'sodium',
                label: 'Sodium (mg)',
                type: 'number',
                value: '',
                required: true
            },
            sugar: {
                id: 'sugar',
                label: 'Sucre (g)',
                type: 'number',
                value: '',
                required: true
            },
            fibers: {
                id: 'fibers',
                label: 'Fibres (g)',
                type: 'number',
                value: '',
                required: true
            },
            note: {
                id: 'note',
                label: 'Note',
                type: 'text',
                value: '',
            },
        };
        
        const nutrientsSectionTitle = `Paramètres nutritionnels pour <br>100 grammes`;
        
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
    
    addMeasureSelectListener() {
        const measureSelect = document.getElementById('measure-select');
        const otherMeasureBlock = document.getElementById('other-measure-block');
        const otherMeasureInput = otherMeasureBlock.getElementsByTagName('input')[0];
        const nutrientsParamsTitle = document.getElementById('nutrients-params-title');
        
        const updateNutrientsParamsTitle = () => {
            if (measureSelect.value === 'grams') {
                nutrientsParamsTitle.innerHTML = `Paramètres nutritionnels pour<br>100 grammes`;
            }
            
            else {
                nutrientsParamsTitle.innerHTML = `Paramètres nutritionnels pour<br>1 unité`;
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
        const newSearchButton = document.getElementById('new-search-btn');
        const resetButton = document.getElementById('reset-ingredient-btn');
        
        const addClickListener = (button, handler) => {
            button.addEventListener('click', handler);
        }
        
        addClickListener(saveButton, (e) => this.handleSaveBtnClick(e));
        addClickListener(newSearchButton, (e) => this.handleNewSearchBtnClick(e));
        addClickListener(resetButton, (e) => this.handleResetFormBtnClick(e));
    }
    
    async handleSaveBtnClick(e) {
        e.preventDefault();
        if (this.verifyFormData()) {
            const body = this.buildIngredientBodyOption();
            this.putRequestResponse = await this.apiHandler.sendRequest(this.endpoint, 'PUT', body);
            this.managePutIngredientResponse(this.putRequestResponse);
        }
        
        else {
            this.scrollTop();
            const errorMessage = KitchenElementsBuilder.buildErrorMessageBlock(`Certaines valeurs du formulaire ne sont pas valides`);
            this.showTemporaryAlert(errorMessage);
        }
    }
    
    handleResetFormBtnClick(e) {
        e.preventDefault();
        const inputs = document.querySelectorAll('#ingredient-form input');
        inputs.forEach(input => input.value = '');
        
        const measureSelect = document.querySelector('#measure-select');
        measureSelect.value = 'grams';
    }
    
    managePutIngredientResponse(putRequestResponse) {
        if (putRequestResponse.status === 200) {
            this.scrollTop();
            const ingredientEditor = new IngredientEditor(this.apiKey, putRequestResponse.data.ingredientId);
            ingredientEditor.showIngredientEditionForm();
            const successMessageBlock = KitchenElementsBuilder.buildSuccessMessageBlock(`Ajout de l'ingrédient réussie`);
            this.showTemporaryAlert(successMessageBlock);
        }
        
        else {
            this.scrollTop();
            const errorMessageBlock = KitchenElementsBuilder.buildErrorMessageBlock(`Echec de l'ajout de l'ingrédient`);
            this.showTemporaryAlert(errorMessageBlock);
        }
    }
}