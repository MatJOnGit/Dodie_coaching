class RegisteringHelper extends ConnectionHelper {
    constructor() {
        super();
        
        this._passwordInputElt = document.getElementById('user-password');

        this._confirmationPasswordInputElt = document.getElementById('user-confirmation-password')
        this._inputElts = [
            this._emailInputElt,
            this._passwordInputElt,
            this._confirmationPasswordInputElt
        ];

        this._isPasswordValid = false;
        this._isConfirmationPasswordValid = false;
        this._arePasswordIdentical = false;
    }

    get inputElts() {
        return this._inputElts;
    }

    get isPasswordValid() {
        return this._isPasswordValid;
    }

    get arePasswordIdentical() {
        return this._arePasswordIdentical;
    }

    set isPasswordValid(boolean) {
        this._isPasswordValid = boolean;
    }

    set arePasswordIdentical(boolean) {
        this._arePasswordIdentical = boolean;
    }

    addInputsListeners() {
        this.inputElts.forEach(inputElt => {
            inputElt.addEventListener('blur', () => {
                this.updateInputChecker(inputElt);
            });
        });
    }

    isInputHelperExisting(inputType) {
        const inputHelper = document.getElementById('input-helper-container');
        let isInputHelperExisting;

        if (!inputHelper) {
            isInputHelperExisting = false;
        }

        else {
            isInputHelperExisting = true;
        }

        return isInputHelperExisting;
    }

    isBlurredInputValid(inputElt, isInputEmpty) {
        const inputType = inputElt.type;
        const inputValue = inputElt.value;
        const inputName = inputElt.name;
        const passwordValue = document.getElementById('user-password').value;
        const confirmationPasswordValue = document.getElementById('user-confirmation-password').value;
        let isBlurredInputValid;

        if (inputType === 'email' && !isInputEmpty) {
            this.isEmailValid = this.emailRegex.test(inputValue);
            isBlurredInputValid = this.isEmailValid;
        }

        else if (inputType === 'password' && !isInputEmpty) {
            if (inputName === 'user-password') {
                this.isPasswordValid = this.passwordRegex.test(inputValue);
                isBlurredInputValid = this.isPasswordValid;
            }
            else if (inputName === 'user-confirmation-password') {
                this.isConfirmationPasswordValid = this.passwordRegex.test(inputValue);
                isBlurredInputValid = this.isConfirmationPasswordValid;
            }

            this.arePasswordIdentical = passwordValue === confirmationPasswordValue
        }

        return isBlurredInputValid;
    }

    addShowHelperButtonsListeners() {
        for (let showHelperBtn of this.showInputHelperBtns) {
            showHelperBtn.addEventListener('click', (e) => {
                e.preventDefault();
                const inputElt = this.getInfoButtonBindedValue(showHelperBtn);
                this.showInputHelper(inputElt.type, inputElt.value)
            })
        }
    }

    addSubmitButtonListener() {
        this._form.addEventListener('submit', (e) => {
            if (!this.isEmailValid || !this.isPasswordValid || !this.isConfirmationPasswordValid || !this.arePasswordIdentical) {
                e.preventDefault();
            }
        })
    }
}