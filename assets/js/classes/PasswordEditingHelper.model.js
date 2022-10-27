class PasswordEditingHelper extends ConnectionHelper {
    constructor() {
        super();
        
        this._passwordInputElt = document.getElementById('user-password');
        this._confirmationPasswordInputElt = document.getElementById('user-confirmation-password')

        this._inputElts = [
            this._passwordInputElt,
            this._confirmationPasswordInputElt
        ];

        this._arePasswordIdentical = false;
        this._isConfirmationPasswordValid = false;
        this._isPasswordValid = false;
    }

    get arePasswordIdentical() {
        return this._arePasswordIdentical;
    }

    get isConfirmationPasswordValid() {
        return this._isConfirmationPasswordValid;
    }

    get isPasswordValid() {
        return this._isPasswordValid;
    }

    set arePasswordIdentical(boolean) {
        this._arePasswordIdentical = boolean;
    }

    set isConfirmationPasswordValid(boolean) {
        this._isConfirmationPasswordValid = boolean;
    }

    set isPasswordValid(boolean) {
        this._isPasswordValid = boolean;
    }

    addSubmitButtonListener() {
        this._form.addEventListener('submit', (e) => {
            if (!this.isPasswordValid || !this.isConfirmationPasswordValid || !this.arePasswordIdentical) {
                e.preventDefault();
            }
        })
    }

    isBlurredInputValid(inputElt, isInputEmpty) {
        const inputType = inputElt.type;
        const inputValue = inputElt.value;
        const inputName = inputElt.name;
        const passwordValue = document.getElementById('user-password').value;
        const confirmationPasswordValue = document.getElementById('user-confirmation-password').value;
        let isBlurredInputValid;

        if (inputName === 'user-password') {
            this.isPasswordValid = this.passwordRegex.test(inputValue);
            isBlurredInputValid = this.isPasswordValid;
        }
        else if (inputName === 'user-confirmation-password') {
            this.isConfirmationPasswordValid = this.passwordRegex.test(inputValue);
            isBlurredInputValid = this.isConfirmationPasswordValid;
        }

        this.arePasswordIdentical = passwordValue === confirmationPasswordValue
        
        return isBlurredInputValid;
    }
}