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

    addInputsEventListeners() {
        this.inputElts.forEach(inputElt => {
            inputElt.addEventListener('blur', () => {
                this.updateInputHelper(inputElt);
            });
        });
    }

    updateInputHelper(inputElt) {
        const inputEltContainer = document.getElementsByClassName(`${inputElt.type}-input-container`)[0];
        const inputEltHelper = inputEltContainer.getElementsByClassName('input-helper')[0];

        const isInputValid = this.isBlurredInputValid(inputElt);
        const isInputEmpty = this.isInputEmpty(inputElt);

        if (isInputValid) {
            inputEltHelper.innerHTML = '<i class="fa-solid fa-check correct"></i>';
        }

        else if (isInputEmpty) {
            inputEltHelper.innerHTML = '';
        }
        
        else {
            inputEltHelper.innerHTML = '<i class="fa-solid fa-xmark wrong"></i>';
        }
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

    isInputEmpty(inputElt) {
        return inputElt.value === '';
    }

    // sendAlert(inputType, inputValue) {
    //     const alertBox = this.buildAlertBox(inputType, inputValue);
    //     const connectionPanel = document.getElementsByClassName('connection-panel')[0];

    //     if (this.isAlertBoxExisting(inputType)) {
    //         this.removeOutdatedAlert(inputType);
    //     }

    //     connectionPanel.insertAdjacentElement('afterbegin', alertBox);
    //     this.fadeInItem(alertBox, 2000);
    // }

    // isAlertBoxExisting(inputType) {
    //     const alertBox = document.getElementById(`input-alert`);
    //     let isAlertBoxExisting;

    //     if (!alertBox) {
    //         isAlertBoxExisting = false;
    //     }

    //     else {
    //         isAlertBoxExisting = true;
    //     }

    //     return isAlertBoxExisting;
    // }

    // removeOutdatedAlert(inputType) {
    //     const connectionPanel = document.getElementsByClassName('connection-panel')[0];
    //     const outdatedAlertBox = document.getElementById(`input-alert`);
    //     connectionPanel.removeChild(outdatedAlertBox);
    // }

    // buildAlertBox(inputType, inputValue) {
    //     const alertBox = document.createElement('div');
    //     const alertMessage = document.createElement('p');
    //     const dismissAlertBtn = document.createElement('button');
    //     const crossAlertIcon = document.createElement('i')

    //     alertBox.id = 'input-alert';
    //     alertMessage.textContent = this.getAlert(inputType, inputValue);
    //     dismissAlertBtn.className = `dismiss-alert-btn`;
    //     crossAlertIcon.className = 'fa-solid fa-xmark';

    //     dismissAlertBtn.appendChild(crossAlertIcon);        
    //     alertBox.appendChild(alertMessage);
    //     alertBox.appendChild(dismissAlertBtn);

    //     return alertBox;
    // }

    // addSubmitButtonListener() {
    //     this._form.addEventListener('submit', (event) => {
    //         if (!this.isEmailValid || !this.isPasswordValid) {
    //             event.preventDefault();
    //             if (!this.isEmailValid) {
    //                 this.sendEmailAlert()
    //             }
    //             else if (!this.isPasswordValid) {
    //                 this.sendPasswordAlert()
    //             }
    //         }
    //     })
    // }
    

}
