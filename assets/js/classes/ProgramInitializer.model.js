class ProgramInitializer extends ElementFader {
    constructor() {
        super();
        
        this._adminPanel = document.getElementsByClassName('admin-panel')[0];
        this._mealsInputsBlock = document.getElementById('meal-inputs-block');
        this._mealsSubmitBtn = document.getElementById('meals-submit-btn');
        
        this._checkboxInputs = this._mealsInputsBlock.getElementsByTagName('input');
        this._pageTitleElt = this._adminPanel.getElementsByTagName('h3')[0];
    }
    
    get adminPanel() {
        return this._adminPanel;
    }
    
    get checkboxInputs() {
        return this._checkboxInputs;
    }
    
    get mealsSubmitBtn() {
        return this._mealsSubmitBtn;
    }
    
    get pageTitleElt() {
        return this._pageTitleElt;
    }
    
    addRemoveAlertListener() {
        const dismissAlertBtn = this.adminPanel.getElementsByClassName('input-helper-dismiss-btn')[0];
        const alertBox = document.getElementById('input-helper-container');
        
        dismissAlertBtn.addEventListener('click', (e) => {
            this.adminPanel.removeChild(alertBox);
        });
    }
    
    addSubmitButtonListener() {
        this.mealsSubmitBtn.addEventListener('click', (e) => {
            let isMealsFormValid = false;
            let isAlertDisplayed = document.getElementById('input-helper-container');
            
            Object.keys(this.checkboxInputs).forEach(element => {
                if (this.checkboxInputs[element].checked) {
                    isMealsFormValid = true;
                }
            });
            
            if (!isMealsFormValid) {
                e.preventDefault();
                
                if (!isAlertDisplayed) {
                    this.displayNoSelectedMealsAlert();
                }
            }
        });
    }
    
    displayNoSelectedMealsAlert() {
        const inputHelper = document.createElement('div');
        const helperMessage = document.createElement('p');
        const dismissAlertBtn = document.createElement('button');
        const crossIcon = document.createElement('i');
        
        inputHelper.id = 'input-helper-container';
        helperMessage.textContent = "Vous n'avez pas sélectionné de repas.";
        helperMessage.className = `meal-building-alert`;
        dismissAlertBtn.className = 'input-helper-dismiss-btn';
        crossIcon.className = 'fa-solid fa-xmark';
        
        dismissAlertBtn.appendChild(crossIcon);        
        inputHelper.appendChild(helperMessage);
        inputHelper.appendChild(dismissAlertBtn);
        this.adminPanel.insertBefore(inputHelper, this.pageTitleElt);
        
        this.fadeInItem(inputHelper, 2000, 1);
        this.addRemoveAlertListener();
    }
    
    editPageElts() {
        let pageTitle = this.adminPanel.getElementsByTagName('h3')[0].textContent;
        
        this.adminPanel.classList.add('meal-setup-panel');
        this.pageTitleElt.textContent = 'Composition du ' + pageTitle.slice(0, 1).toLowerCase() + pageTitle.slice(1);
    }
    
    init() { 
        this.editPageElts();
        this.addSubmitButtonListener();
    }
}