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
        const connectionPanel = document.getElementsByClassName('connection-panel')[0];

        if (this.isAlertBoxExisting(inputType)) {
            this.removeOutdatedAlert(inputType);
        }

        connectionPanel.insertAdjacentElement('afterbegin', alertBox);
        this.fadeInItem(alertBox, 2000);
    }

    isAlertBoxExisting(inputType) {
        const alertBox = document.getElementById(`${inputType}-alert`);
        let isAlertBoxExisting;

        if (!alertBox) {
            isAlertBoxExisting = false;
        }

        else {
            isAlertBoxExisting = true;
        }

        return isAlertBoxExisting;
    }

    removeOutdatedAlert(inputType) {
        const connectionPanel = document.getElementsByClassName('connection-panel')[0];
        const outdatedAlertBox = document.getElementById(`${inputType}-alert`);
        connectionPanel.removeChild(outdatedAlertBox);
    }

    buildAlertBox(inputType, inputValue) {
        const alertBox = document.createElement('div');
        const alertMessage = document.createElement('p');
        const dismissAlertBtn = document.createElement('button');
        const crossAlertIcon = document.createElement('i')

        alertBox.id = `${inputType}-alert`;
        alertMessage.textContent = this.getAlert(inputType, inputValue);
        dismissAlertBtn.className = `dismiss-${inputType}-alert-btn`;
        crossAlertIcon.className = 'fa-solid fa-xmark';

        dismissAlertBtn.appendChild(crossAlertIcon);        
        alertBox.appendChild(alertMessage);
        alertBox.appendChild(dismissAlertBtn);

        return alertBox;
    }
    
    testBlurredInput(inputElt) {
        const inputType = inputElt.type;
        const inputValue = inputElt.value;

        if (inputType === 'email' && inputValue !== '') {
            this.isEmailValid = this.emailRegex.test(inputValue);

            if (!this.isEmailValid) {
                this.sendAlert(inputType, inputValue);
            }
            else if (this.isAlertBoxExisting(inputType)) {
                this.removeOutdatedAlert(inputType);
            }
        }

        else if (inputType === 'password' && inputValue !== '') {
            this.isPasswordValid = this.passwordRegex.test(inputValue);

            if (!this.isPasswordValid) {
                this.sendAlert(inputType, inputValue);
            }
            else if (this.isAlertBoxExisting(inputType)) {
                this.removeOutdatedAlert(inputType);
            }
        }
    }
}
