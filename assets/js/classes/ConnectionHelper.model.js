class ConnectionHelper extends UserPanels{
    constructor() {
        super();

        this._emailRegex = /^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/;

        // valid password must have between 10 and 50 characters, with at least one small cap character, one capital letter, one number and one special character
        this._passwordRegex = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{10,50}$/;

        this._usernameRegex = /^[a-zA-Zàâçéèêñ '.-]+$/;

        this._containsDomainNameRegex = /@\w+([\.-]?\w+)*(\.\w{2,3})+$/;
        this._containsSmallCapRegex = /[a-z]/;
        this._containsCapitalLetterRegex = /[A-Z]/;
        this._containsNumberRegex = /\d/;
        this._containsSpecialCharRegex = /[@$!%*?&]/;

        this._showInputHelperBtns = document.getElementsByClassName('show-input-helper-btn');

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
}