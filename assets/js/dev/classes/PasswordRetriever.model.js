class PasswordRetriever extends ConnectionHelper {
    constructor() {
        super();
        
        this._inputElts = [
            this._emailInputElt
        ];
    }
    
    addSubmitButtonListener() {
        this._form.addEventListener('submit', (e) => {
            if (!this.isEmailValid) {
                e.preventDefault();
            }
        })
    }
    
    isBlurredInputValid(inputElt) {
        const inputValue = inputElt.value;
        this.isEmailValid = this.emailRegex.test(inputValue);
        
        return this.isEmailValid;
    }
}