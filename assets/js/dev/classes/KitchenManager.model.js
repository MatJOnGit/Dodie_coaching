class KitchenManager {
    constructor(apiKey) {
        this._adminPanel = document.getElementsByClassName('admin-panel')[0];
        this._apiKey = apiKey;
        this._numbersRegex = /^[0-9]+([.,][0-9]+)?$/;
    }
    
    get adminPanel() {
        return this._adminPanel;
    }
    
    get apiKey() {
        return this._apiKey;
    }
    
    get kitchenDataForm() {
        return this._kitchenDataForm;
    }
    
    set kitchenDataForm(dataForm) {
        this._kitchenDataForm = dataForm;
    }
    
    get numbersRegex() {
        return this._numbersRegex;
    }
    
    clearPanel() {
        this.adminPanel.innerHTML = '';
    }
    
    scrollTop() {
        window.scrollTo({
            top: 0,
            behavior: 'smooth'
        });
    }
    
    verifyFormData() {
        const inputValidationResults = Array.from(document.querySelectorAll('#ingredient-form input'))
        .map(input => ({
            value: input.value.trim(),
            expectedType: input.type,
        }))
        .every(({ expectedType, value }) => this.validateType(value, expectedType));
        
        return inputValidationResults;
    }
    
    validateType(value, type) {
        switch (type) {
            case 'number':
                return !isNaN(value.replace(',', '.'));
            case 'text':
                return isNaN(value.replace(',', '.')) || value === '';
            default:
                return false;
        }
    }
    
    handleNewSearchBtnClick(e) {
        e.preventDefault();
        location.reload();
    }
    
    /*********************************************************************************
    Builds and returns a body object with sanitized data, computed ingredient measures
    based on select selected value, and number inputs values turned into float values
    *********************************************************************************/
    buildIngredientBodyOption() {
        let bodyObj = this.getSanitizedBody();
        bodyObj = this.getComputedMeasure(bodyObj);
        bodyObj = this.parseFloatValues(bodyObj);
        
        return bodyObj;
    }
    
    getSanitizedBody(body) {
        body = {};
        const inputs = Array.from(document.querySelectorAll('.ingredient-param:not(.hidden) input'));
        inputs.forEach(input => {
            const valueTextNode = document.createTextNode(input.value);
            const textNodeContainer  = document.createElement('div');
            textNodeContainer.appendChild(valueTextNode);
            body[input.id] = textNodeContainer.innerHTML;
        });
        
        return body;
    }
    
    getComputedMeasure(body) {
        const measureSelect = document.querySelector('#measure-select');
        const selectedOption = measureSelect.options[measureSelect.selectedIndex];
        const selectedValue = selectedOption.value;
        
        body.preparation = body.preparation || null;
        body.note = body.note || null;
        
        switch (selectedValue) {
            case 'grams':
                body.measure = selectedOption.textContent;
                break;
            case 'no-unit':
                body.measure = null;
                break;
            default:
                const otherMeasureInput = document.querySelector('#other-measure');
                body.measure = otherMeasureInput.value;
                break;
        }
        delete body['other-measure'];
        
        return body;
    }
    
    parseFloatValues(body) {
        return Object.fromEntries(
            Object.entries(body).map(([bodyKey, inputValue]) => {
                if (this.numbersRegex.test(inputValue)) {
                    return [bodyKey, parseFloat(inputValue)];
                }
                
                else {
                    return [bodyKey, inputValue];
                }
            })
        );
    }
    
    showTemporaryAlert(alertBlockString) {
        const removeBlock = () => {
            this.adminPanel.removeChild(alertBlock);
        };
        
        this.adminPanel.appendChild(alertBlockString);
        const alertBlock = document.getElementsByClassName('fixed-alert')[0];
        setTimeout(removeBlock, 4000);
    }
}