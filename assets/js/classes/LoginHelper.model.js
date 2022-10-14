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

    get inputElts() {
        return this._inputElts;
    }

    get isPasswordValid() {
        return this._isPasswordValid;
    }

    set isPasswordValid(boolean) {
        this._isPasswordValid = boolean;
    }

    addInputsListeners() {
        this.inputElts.forEach(inputElt => {
            inputElt.addEventListener('blur', () => {
                this.updateInputChecker(inputElt);
            });
        });
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

    addShowHelperButtonsListeners() {
        for (let showHelperBtn of this.showInputHelperBtns) {
            showHelperBtn.addEventListener('click', (e) => {
                e.preventDefault();
                const inputElt = this.getInfoButtonBindedValue(showHelperBtn);
                this.showInputHelper(inputElt.type, inputElt.value)
            })
        }
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

    addSubmitButtonListener() {
        this._form.addEventListener('submit', (e) => {
            if (!this.isEmailValid || !this.isPasswordValid) {
                e.preventDefault();
            }
        })
    }
}
