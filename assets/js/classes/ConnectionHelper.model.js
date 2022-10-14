class ConnectionHelper extends UserPanels{
    constructor() {
        super();

        this._emailRegex = /^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/;
        this._passwordRegex = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{10,50}$/;
        this._usernameRegex = /^[a-zA-Zàâçéèêñ '.-]+$/;

        this._containsDomainNameRegex = /@\w+([\.-]?\w+)*(\.\w{2,3})+$/;
        this._containsSmallCapRegex = /[a-z]/;
        this._containsCapitalLetterRegex = /[A-Z]/;
        this._containsNumberRegex = /\d/;
        this._containsSpecialCharRegex = /[@$!%*?&]/;

        this._emailInputElt = document.getElementById('user-email');
        this._form = document.getElementsByTagName('form')[0];

        this._showInputHelperBtns = document.getElementsByClassName('show-input-helper-btn');

        this._isEmailValid = false;

        this._inputAlerts = {
            'email' : {
                'valid' : "Votre adresse mail est valide",
                'at-symbol' : "Votre email doit contenir une arobase",
                'domain' : "Le domaine de votre email n'est pas valide",
                'unknown' : "Votre adresse mail n'est pas valide"
                
            },
            'password' : {
                'valid' : "Votre mot de passe est valide",
                'short' : "Votre mot de passe est trop court (mini. 10 caractères)",
                'long' : "Votre mot de passe est trop long (max. 50 caractères)",
                'number' : "Votre mot de passe doit contenir au moins un chiffre",
                'small-cap' : "Votre mot de passe doit contenir au moins une lettre minuscule",
                'capital-letter' : "Votre mot de passe doit contenir au moins une lettre majuscule",
                'special-char' : "Votre mot de passe doit contenir au moins un caractère spécial (@,$,!,%,*,?,&)",
                'unknown' : "Votre mot de passe n'est pas valide"
            }
        };
    }

    get isEmailValid() {
        return this._isEmailValid;
    }

    set isEmailValid(boolean) {
        this._isEmailValid = boolean;
    }

    get containsDomainNameRegex() {
        return this._containsDomainNameRegex;
    }

    get containsNumberRegex() {
        return this._containsNumberRegex;
    }

    get containsSmallCapRegex() {
        return this._containsSmallCapRegex;
    }

    get containsCapitalLetterRegex() {
        return this._containsCapitalLetterRegex;
    }

    get containsSpecialCharRegex() {
        return this._containsSpecialCharRegex;
    }

    get emailRegex() {
        return this._emailRegex;
    }

    get inputAlerts() {
        return this._inputAlerts;
    }

    get passwordRegex() {
        return this._passwordRegex;
    }

    get showInputHelperBtns() {
        return this._showInputHelperBtns;
    }

    get form() {
        return this._form;
    }

    getAlert (inputType, inputValue) {
        let alert = '';

        if (inputType === 'password') {
            alert = this.getPasswordAlert(inputType, inputValue);
        }
        else if (inputType === 'email') {
            alert = this.getEmailAlert(inputType, inputValue);
        }

        return alert;
    }

    getEmailAlert(inputType, inputValue) {
        let emailAlert = '';

        if (this.emailRegex.test(inputValue)) {
            emailAlert = this.inputAlerts[inputType]['valid'];
        }

        else if (!inputValue.includes('@')) {
            emailAlert = this.inputAlerts[inputType]['at-symbol'];
        }
        else if (!this.containsDomainNameRegex.test(inputValue)) {
            emailAlert = this.inputAlerts[inputType]['domain'];
        }
        else {
            emailAlert = this.inputAlerts[inputType]['unknown'];
        }

        return emailAlert;
    }

    getPasswordAlert(inputType, inputValue) {
        let passwordAlert = '';

        if (this.passwordRegex.test(inputValue)) {
            passwordAlert = this.inputAlerts[inputType]['valid'];
        }

        else if (inputValue.length < 10) {
            passwordAlert = this.inputAlerts[inputType]['short'];
        }

        else if (inputValue.length > 50) {
            passwordAlert = this.inputAlerts[inputType]['long'];
        }

        else if (!this.containsNumberRegex.test(inputValue)) {
            passwordAlert = this.inputAlerts[inputType]['number'];
        }

        else if (!this.containsSmallCapRegex.test(inputValue)) {
            passwordAlert = this.inputAlerts[inputType]['small-cap'];
        }

        else if (!this.containsCapitalLetterRegex.test(inputValue)) {
            passwordAlert = this.inputAlerts[inputType]['capital-letter']
        }

        else if (!this.containsSpecialCharRegex.test(inputValue)) {
            passwordAlert = this.inputAlerts[inputType]['special-char']
        }

        else {
            passwordAlert = this.inputAlerts[inputType]['unknown'];
        }

        return passwordAlert;
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

    getInfoButtonBindedValue(showHelperBtn) {
        return showHelperBtn.parentElement.getElementsByTagName('input')[0];
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

    removePreviousInputHelper(inputType) {
        const connectionPanel = document.getElementsByClassName('connection-panel')[0];
        const previousInputHelper = document.getElementById('input-helper-container');

        if (previousInputHelper) {
            connectionPanel.removeChild(previousInputHelper);
        }
    }

    isInputEmpty(inputElt) {
        return inputElt.value === '';
    }

    addHelperDismissButtonListener(inputHelper) {
        const inputHelperDismissBtn = inputHelper.getElementsByTagName('button')[0];
        const inputHelperMessageType = inputHelper.getElementsByTagName('p')[0].className;
        const inputHelperType = inputHelperMessageType.split('-')[0];

        inputHelperDismissBtn.addEventListener('click', () => {
            this.removePreviousInputHelper(inputHelperType);
        })
    }
}