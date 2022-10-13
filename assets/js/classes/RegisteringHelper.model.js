class RegisteringHelper extends ConnectionHelper {
    constructor() {
        super();

        this._emailInputElt = document.getElementById('user-email');
        this._passwordInputElt = document.getElementById('user-password');
        this._confirmationPasswordInputElt = document.getElementById('user-confirmation-password')
        this._inputElts = [
            this._emailInputElt,
            this._passwordInputElt,
            this._confirmationPasswordInputElt
        ];

        this._form = document.getElementsByTagName('form')[0];

        this._isEmailValid = false;
        this._isPasswordValid = false;
        this._isConfirmationPasswordValid = false;
        this._arePasswordIdentical = false;
    }

    get inputElts() {
        return this._inputElts;
    }

    get arePasswordIdentical() {
        return this._arePasswordIdentical;
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

    getInfoButtonBindedValue(showHelperBtn) {
        return showHelperBtn.parentElement.getElementsByTagName('input')[0];
    }

    showInputHelper(inputType, inputValue) {
        const inputHelper = this.buildHelper(inputType, inputValue);
        const connectionPanel = document.getElementsByClassName('connection-panel')[0];

        if (this.isInputHelperExisting(inputType)) {
            this.removePreviousInputHelper(inputType);
        }

        connectionPanel.insertAdjacentElement('afterbegin', inputHelper);
        this.fadeInItem(inputHelper, 2000);
        this.addHelperDismissButtonListener(inputHelper);
    }

    buildHelper(inputType, inputValue) {
        const inputHelper = document.createElement('div');
        const helperMessage = document.createElement('p');
        const textualHelpDismissBtn = document.createElement('button');
        const crossIcon = document.createElement('i')

        inputHelper.id = 'input-helper-container';
        helperMessage.textContent = this.getAlert(inputType, inputValue);
        helperMessage.className = `${inputType}-message`;
        textualHelpDismissBtn.className = 'input-helper-dismiss-btn';
        crossIcon.className = 'fa-solid fa-xmark';

        textualHelpDismissBtn.appendChild(crossIcon);        
        inputHelper.appendChild(helperMessage);
        inputHelper.appendChild(textualHelpDismissBtn);

        return inputHelper;
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

    updateInputChecker(inputElt) {
        const inputContainerElt = inputElt.parentElement;
        const inputCheckerElt = inputContainerElt.getElementsByClassName('input-helper')[0];

        const isInputEmpty = this.isInputEmpty(inputElt);
        const isInputValid = this.isBlurredInputValid(inputElt, isInputEmpty);

        if (isInputValid) {
            inputCheckerElt.innerHTML = '<i class="fa-solid fa-check correct"></i>';
            this.removePreviousInputHelper(inputElt.type)
        }

        else if (isInputEmpty) {
            inputCheckerElt.innerHTML = '';
        }
        
        else {
            inputCheckerElt.innerHTML = '<i class="fa-solid fa-xmark wrong"></i>';
        }
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

    addHelperDismissButtonListener(inputHelper) {
        const inputHelperDismissBtn = inputHelper.getElementsByTagName('button')[0];
        const inputHelperMessageType = inputHelper.getElementsByTagName('p')[0].className;
        const inputHelperType = inputHelperMessageType.split('-')[0];

        inputHelperDismissBtn.addEventListener('click', () => {
            this.removePreviousInputHelper(inputHelperType);
        })
    }

    addSubmitButtonListener() {
        this._form.addEventListener('submit', (e) => {
            if (!this.isEmailValid || !this.isPasswordValid || !this.isConfirmationPasswordValid || !this.arePasswordIdentical) {
                e.preventDefault();
            }
        })
    }
}