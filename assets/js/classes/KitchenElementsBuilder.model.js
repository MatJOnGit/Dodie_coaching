class KitchenElementsBuilder {
    static buildNodeFromTemplate(template) {
        const range = document.createRange();
        range.selectNode(document.body);
        const templateFragment = range.createContextualFragment(`${template}`);
        
        return templateFragment;
    }
    
    static buildPageTitle(genericTitle, itemDesignation = '') {
        const titleCompletion = itemDesignation ? `<br>${itemDesignation}` : '';
        const pageTitleTemplate = `
            <h3>${genericTitle}${titleCompletion}</h3>
        `;
        
        return this.buildNodeFromTemplate(pageTitleTemplate);
    }
    
    static buildIngredientCard(ingredient) {
        const ingredientCardTemplate = `
            <button id=${ingredient.id} class='food-card'>
                <h4 class='food-name'>${ingredient.name} ${ingredient.preparation || ''}</h4>
                <p>${ingredient.type}</p>
            </button>
        `;
        
        return this.buildNodeFromTemplate(ingredientCardTemplate);
    }
    
    static buildCreateItemButton(itemType) {
        const createItemButtonTemplate = `
            <button id='create-item-btn' class='btn large-btn admin-link blue-bkgd'>
                Nouvel ${itemType}
            </button>
        `;
        
        return this.buildNodeFromTemplate(createItemButtonTemplate);
    }
    
    static buildSectionHeader(sectionType, sectionTitle) {
        const sectionHeaderTemplate = `
            <h4 id='${sectionType}-params-title' class='ingredient-params-header'>
                ${sectionTitle}
            </h4>
        `;
        
        return this.buildNodeFromTemplate(sectionHeaderTemplate);
    }
    
    static buildInputBlock(inputParams, itemType) {
        const inputBlockTemplate = `
            <div id='${inputParams.id}-block' class='${itemType}-param ${inputParams.hidden && inputParams.hidden === true ? 'hidden' : ''}'>
                <label for='${itemType}-${inputParams.id}'>${inputParams.label} :</label>
                <input type='${inputParams.type}' id='${inputParams.id}' name='${itemType}-${inputParams.id}' value='${inputParams.value === null ? '' : inputParams.value}' ${inputParams.required ? ' required' : ''}>
            </div>
        `;
        
        return this.buildNodeFromTemplate(inputBlockTemplate);
    }
    
    static buildEditionValidationBlock(itemType, message) {
        const editionvalidationBlockTemplate = `
            <div id='form-btns-block'>
                <div id='form-actions-block'>
                    <button id='save-${itemType}-params-btn' class='btn tiny-btn rounded-btn blue-bkgd'>Enregistrer</button>
                    <button id='delete-${itemType}-btn' class='btn tiny-btn rounded-btn red-bkgd'>Supprimer</button>
                </div>
                
                <button id='new-search-btn' class='btn large-btn rounded-btn blue-bkgd'>Nouvelle recherche</button>
                
                <div id='action-confirmation-block' class='hidden'>
                    <button id='cancel-deletion-btn' class='btn circle-btn blue-bkgd'>Non</button>
                    <div>
                        <p>${message}</p>
                    </div>
                    <button id='confirm-deletion-btn' class='btn circle-btn red-bkgd'>Oui</button>
                </div>
            </div>
        `;
        
        return this.buildNodeFromTemplate(editionvalidationBlockTemplate);
    }
    
    static buildCreationFormValidationBlock(itemType) {
        const creationFormValidationBlockTemplate = `
            <div id='form-btns-block'>
                <div id='form-actions-block'>
                    <button id='save-${itemType}-params-btn' class='btn tiny-btn rounded-btn blue-bkgd'>Enregistrer</button>
                    <button id='reset-${itemType}-btn' class='btn tiny-btn rounded-btn red-bkgd'>Réinitialiser</button>
                </div>
                
                <button id='new-search-btn' class='btn large-btn rounded-btn blue-bkgd'>Nouvelle recherche</button>
            </div>
        `;
        
        return this.buildNodeFromTemplate(creationFormValidationBlockTemplate);
    }
    
    static buildSelectBlock(selectParams, itemType, defaultOption) {
        const optionsTemplate = selectParams.options.map(option => {
            return `<option value='${option.value}' ${option.text === defaultOption ? 'selected' : ''}>${option.text}</option>`;
        }).join('');
        
        const selectTemplate = `
            <div class='${itemType}-param'>
                <label for='${selectParams.id}-select'>${selectParams.label} :</label>
                <select id='${selectParams.id}-select' name='${selectParams.type}-${selectParams.id}'>
                    ${optionsTemplate}
                </select>
            </div>
        `;
      
        return this.buildNodeFromTemplate(selectTemplate);
    }
    
    static buildSuccessMessageBlock(successMessage) {
        const successMessageTemplate = `
            <div class='success-alert fixed-alert'>
                <p>${successMessage}</p>
            </div>
        `;
        
        return this.buildNodeFromTemplate(successMessageTemplate);
    }
    
    static buildErrorMessageBlock(errorMessage) {
        const errorMessageTemplate = `
            <div class='error-alert fixed-alert'>
                <p>${errorMessage}</p>
            </div>
        `;
        
        return this.buildNodeFromTemplate(errorMessageTemplate);
      }
}