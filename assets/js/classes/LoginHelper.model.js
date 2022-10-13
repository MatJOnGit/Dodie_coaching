class LoginHelper extends ConnectionHelper {
    constructor() {
        super();

        this._emailInputElt = document.getElementById('user-email');
        this._passwordInputElt = document.getElementById('user-password');
        this._inputElts = [
            this._emailInputElt,
            this._passwordInputElt
        ];

        this._showInputHelperBtns = document.getElementsByClassName('show-input-helper-btn');

        this._form = document.getElementsByTagName('form')[0];

        this._isEmailValid = false;
        this._isPasswordValid = false;
    }

    get inputElts() {
        return this._inputElts;
    }

    get showInputHelperBtns() {
        return this._showInputHelperBtns;
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
        this.inputElts.forEach(inputElt => {
            inputElt.addEventListener('blur', () => {
                this.updateInputChecker(inputElt);
            });
        });
    }

    updateInputChecker(inputElt) {
        const inputContainerElt = inputElt.parentElement;
        const inputCheckerElt = inputContainerElt.getElementsByClassName('input-helper')[0];

        const isInputValid = this.isBlurredInputValid(inputElt);
        const isInputEmpty = this.isInputEmpty(inputElt);

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
            if (!this.isEmailValid || !this.isPasswordValid) {
                e.preventDefault();
            }
        })
    }
}
