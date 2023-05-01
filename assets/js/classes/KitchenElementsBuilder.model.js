class KitchenElementsBuilder {
    static buildIngredientCard(apiKey, ingredient) {
        const ingredientCard = document.createElement('button');
        ingredientCard.classList.add('food-card');
        ingredientCard.id = ingredient.id;
        
        const ingredientEditor = new IngredientEditor(apiKey, ingredient.id);
        ingredientCard.addEventListener('click', () => {
            ingredientEditor.showIngredientEditionForm();
        })
        
        const ingredientName = document.createElement('h4');
        ingredientName.classList = 'food-name';
        ingredientName.innerText = `${ingredient.name} ${ingredient.preparation || ''}`;
        
        const ingredientType = document.createElement('p');
        ingredientType.innerText = `(${ingredient.type})`;
        
        ingredientCard.appendChild(ingredientName);
        ingredientCard.appendChild(ingredientType);
        
        return ingredientCard;
    }
    
    static buildCreateItemButton(itemType) {
        const createItemBtn = document.createElement('a');
        createItemBtn.classList.add('btn', 'large-btn', 'admin-link', 'blue-bkgd');
        createItemBtn.id = 'create-item-btn';
        createItemBtn.textContent = `Nouvel ${itemType}`;
        
        return createItemBtn;
    }
    
    static buildErrorMessage(errorMessage) {
        const errorMessageBlock = document.createElement('div');
        const errorMessageText = document.createElement('p');
        errorMessageText.classList.add('search-bar-alert');
        errorMessageText.textContent = `${errorMessage}`;
        errorMessageBlock.appendChild(errorMessageText);
        
        return errorMessageBlock;
    }
    
    static buildInputBlock(inputParams, itemType) {
        const inputBlock = `
            <div id='${inputParams.id}-block' class='${itemType}-param ${inputParams.hidden && inputParams.hidden === true ? 'hidden' : ''}'>
                <label>${inputParams.label} :</label>
                <input type='${inputParams.type}' id='${inputParams.id}' name='${inputParams.type}-${inputParams.id}' value='${inputParams.value}' ${inputParams.required ? ' required' : ''}>
            </div>
        `;
        
        const inputBlockContainer = document.createElement('div');
        inputBlockContainer.innerHTML = inputBlock.trim();
        
        return inputBlockContainer.firstChild.outerHTML;
    }
    
    static buildSelectBlock(selectParams, itemType, defaultOption) {
        const optionsBlocks = selectParams.options.map(option => {
            return `<option value="${option.value}" ${option.text === defaultOption ? 'selected' : ''}>${option.text}</option>`;
        }).join('');
      
        const selectBlock = `
            <div class="${itemType}-param">
                <label for="${selectParams.id}-select">${selectParams.label} :</label>
                <select id="${selectParams.id}-select" name="${selectParams.type}-${selectParams.id}">
                    ${optionsBlocks}
                </select>
            </div>
        `;
      
        const selectBlockContainer = document.createElement('div');
        selectBlockContainer.innerHTML = selectBlock.trim();
      
        return selectBlockContainer.firstChild.outerHTML;
    }
}