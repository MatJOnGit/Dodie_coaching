class LoginHelper extends ConnectionHelper {
    constructor() {
        super();

        this._emailInputElt = document.getElementById('user-email');
        this._passwordInputElt = document.getElementById('user-password');
        this._inputElts = [this._emailInputElt, this._passwordInputElt];

        this._form = document.getElementsByTagName('form')[0];

        this._isFormValid = false;
        this._isEmailValid = false;
        this._isPasswordValid = false;
    }

    get inputElts() {
        return this._inputElts;
    }

    get form() {
        return this._form;
    }

    get isEmailValid() {
        return this._isEmailValid;
    }

    get isPasswordValid() {
        return this._isPasswordValid;
    }

    set isEmailValid(boolean) {
        this._isEmailValid = boolean;
    }

    set isPasswordValid(boolean) {
        this._isPasswordValid = boolean;
    }

    addInputsListeners() {
        this.inputElts.forEach(input => {
            input.addEventListener('blur', () => {
                this.testBlurredInput(input);
            });
        });
    }

    addSubmitButtonListener() {
        this._form.addEventListener('submit', (event) => {
            if (!this.isEmailValid || !this.isPasswordValid) {
                event.preventDefault();
                if (!this.isEmailValid) {
                    this.sendEmailAlert()
                }
                else if (!this.isPasswordValid) {
                    this.sendPasswordAlert()
                }
            }
        })
    }

    sendAlert(inputType, inputValue) {
        const alertBox = this.buildAlertBox(inputType, inputValue);
    }

    buildAlertBox(inputType, inputValue) {
        const alertMessage = document.createElement('p');
        const alertBox = document.createElement('div');

        // this.getAlertMessage(inputType, inputValue)

        console.log(this.getAlertMessage(inputType, inputValue));

        // alertMessage.textContent = this.getAlertMessage(inputType, inputValue);
    }
    
    testBlurredInput(inputElt) {
        const inputType = inputElt.type;
        const inputValue = inputElt.value;

        if (inputType === 'email' && inputValue !== '') {
            this.isEmailValid = this.emailRegex.test(inputValue);

            if (!this.isEmailValid) {
                this.sendAlert(inputType, inputValue);
            }
        }

        else if (inputType === 'password' && inputValue !== '') {
            this.isPasswordValid = this.passwordRegex.test(inputValue);

            if (!this.isPasswordValid) {
                this.sendAlert(inputType, inputValue);
            }
        }
    }
}
