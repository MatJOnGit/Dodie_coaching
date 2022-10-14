class PwdRetrievingHelper extends ConnectionHelper {
    constructor() {
        super();

        this._inputElts = [
            this._emailInputElt
        ];
    }

    get inputElts() {
        return this._inputElts;
    }

    addInputsListeners() {
        this.inputElts.forEach(inputElt => {
            inputElt.addEventListener('blur', () => {
                this.updateInputChecker(inputElt);
            });
        });
    }

    isBlurredInputValid(inputElt) {
        const inputValue = inputElt.value;
        this.isEmailValid = this.emailRegex.test(inputValue);

        return this.isEmailValid;
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
            if (!this.isEmailValid) {
                e.preventDefault();
            }
        })
    }
}