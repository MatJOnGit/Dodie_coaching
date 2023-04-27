class KitchenEditor extends ElementFader {
    #onlyNumbersRegex = /^\d+$/;

    constructor(apiToken, itemId) {
        super();

        this._apiToken = apiToken;
        this._itemId = itemId;
        
        this._adminPanel = document.getElementsByClassName('admin-panel')[0];
    }
    
    get apiToken() {
        return this._apiToken;
    }

    get onlyNumbersRegex() {
        return this.#onlyNumbersRegex;
    }
    
    set apiToken(key) {
        this._apiToken = key;
    }
    
    get itemId() {
        return this._itemId;
    }
    
    set itemId(id) {
        this._itemId = id;
    }
    
    get adminPanel() {
        return this._adminPanel;
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
}