class LoginHelper extends ConnectionHelper {
    constructor() {
        super();
        
        this._passwordInputElt = document.getElementById('user-password');
        
        this._inputElts = [
            this._emailInputElt,
            this._passwordInputElt
        ];
        
        this._isPasswordValid = false;
    }
    
    get isPasswordValid() {
        return this._isPasswordValid;
    }
    
    set isPasswordValid(boolean) {
        this._isPasswordValid = boolean;
    }
    
    addSubmitButtonListener() {
        this._form.addEventListener('submit', (e) => {
            if (!this.isEmailValid || !this.isPasswordValid) {
                e.preventDefault();
            }
        })
    }
    
    isBlurredInputValid(inputElt) {
        const inputType = inputElt.type;
        const inputValue = inputElt.value;
        let isBlurredInputValid;
        
        if (inputType === 'email' && !this.isInputEmpty(inputElt)) {
            this.isEmailValid = this.emailRegex.test(inputValue);
            isBlurredInputValid = this.isEmailValid;
        }
        else if (inputType === 'password' && !this.isInputEmpty(inputElt)) {
            this.isPasswordValid = this.passwordRegex.test(inputValue);
            isBlurredInputValid = this.isPasswordValid;
        }
        
        return isBlurredInputValid;
    }
}
