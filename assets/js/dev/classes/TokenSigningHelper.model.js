class TokenSigningHelper {
    constructor() {
        this._tokenRegex = /^[A-Z0-9]{6}$/;

        this._form = document.getElementsByTagName('form')[0];
        this._tokenField = document.getElementById('user-token');
    }
    
    get form() {
        return this._form;
    }
    
    get tokenField() {
        return this._tokenField;
    }
    
    get tokenRegex() {
        return this._tokenRegex;
    }
    
    init() {
        this.form.addEventListener('submit', (e) => {
            if (!this.tokenRegex.test(this.tokenField.value.toUpperCase())) {
                e.preventDefault();
            }
        });
    }
}