class LoginHelper extends ConnectionHelper {
    constructor() {
        super();

        this._emailInputElt = document.getElementById('user-email');
        this._passwordInputElt = document.getElementById('user-password');
        this._inputElts = [this._emailInputElt, this._passwordInputElt];

        this._infoBtnElts = document.getElementsByClassName('input-info');

        this._form = document.getElementsByTagName('form')[0];

        this._isFormValid = false;
        this._isEmailValid = false;
        this._isPasswordValid = false;
    }

    get inputElts() {
        return this._inputElts;
    }

    get infoBtnElts() {
        return this._infoBtnElts;
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

    addInfoButtonsListeners() {
        for (let infoBtnElt of this.infoBtnElts) {
            infoBtnElt.addEventListener('click', (e) => {
                e.preventDefault();
                const inputElt = this.getInfoButtonBindedValue(infoBtnElt);
                this.showTextualHelper(inputElt.type, inputElt.value)
            })
        }
    }

    getInfoButtonBindedValue(infoBtnElt) {
        return infoBtnElt.parentElement.getElementsByTagName('input')[0];
    }

    showTextualHelper(inputType, inputValue) {
        const inputHelper = this.buildInputHelper(inputType, inputValue);
        const connectionPanel = document.getElementsByClassName('connection-panel')[0];

        if (this.isInputTextualHelpExisting(inputType)) {
            this.removePreviousHelper(inputType);
        }

        connectionPanel.insertAdjacentElement('afterbegin', inputHelper);
        this.fadeInItem(inputHelper, 2000);
        this.addHelperDismissButtonListener(inputHelper);
    }

    isInputTextualHelpExisting(inputType) {
        const inputHelper = document.getElementById('input-textual-help');
        let isInputHelperExisting;

        if (!inputHelper) {
            isInputHelperExisting = false;
        }

        else {
            isInputHelperExisting = true;
        }

        return isInputHelperExisting;
    }

    removePreviousHelper(inputType) {
        const connectionPanel = document.getElementsByClassName('connection-panel')[0];
        const previousHelper = document.getElementById('input-textual-help');
        connectionPanel.removeChild(previousHelper);
    }

    buildInputHelper(inputType, inputValue) {
        const inputHelper = document.createElement('div');
        const helperMessage = document.createElement('p');
        const textualHelpDismissBtn = document.createElement('button');
        const crossIcon = document.createElement('i')

        inputHelper.id = 'input-textual-help';
        helperMessage.textContent = this.getAlert(inputType, inputValue);
        helperMessage.className = `${inputType}-message`;
        textualHelpDismissBtn.className = 'textual-help-dismiss-btn';
        crossIcon.className = 'fa-solid fa-xmark';

        textualHelpDismissBtn.appendChild(crossIcon);        
        inputHelper.appendChild(helperMessage);
        inputHelper.appendChild(textualHelpDismissBtn);

        return inputHelper;
    }

    addHelperDismissButtonListener(inputHelper) {
        console.log(inputHelper)
        const helperDismissBtn = inputHelper.getElementsByTagName('button')[0];
        const helperTypeMessage = inputHelper.getElementsByTagName('p')[0].className;
        const helperType = helperTypeMessage.split('-')[0];

        helperDismissBtn.addEventListener('click', () => {
            this.removePreviousHelper(helperType);
        })
    }

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
