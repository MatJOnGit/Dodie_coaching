class ProgressManager extends Fader {
    constructor() {
        super();
        
        this._weightRegex = /^(?!0*[.,]0*$|[.,]0*$|0*$)\d+[,.]?\d{0,3}$/;
        
        this._dateTypeSelect = document.getElementById('date-selector');
        this._formFields = document.getElementsByClassName('form-fields')[0];
        this._submitButton = document.getElementById('submit-btn');
        this._weightInput = document.getElementById('user-weight');
    }
    
    get dateTypeSelect() {
        return this._dateTypeSelect;
    }
    
    get formFields() {
        return this._formFields;
    }
    
    get selectedOptionValue() {
        return this.dateTypeSelect.options[this.dateTypeSelect.selectedIndex].value;
    }
    
    get submitButton() {
        return this._submitButton;
    }
    
    get weightInput() {
        return this._weightInput;
    }
    
    get weightRegex() {
        return this._weightRegex;
    }
    
    addDeleteReportListeners() {
        let deleteReportButtons = document.querySelectorAll('.progress-item button');
        
        for (let deleteReportButton of deleteReportButtons) {
            deleteReportButton.addEventListener('click', () => {
                this.displayDeleteReportConfirmation(deleteReportButton);
            })
        }
    }
    
    addSelectListener() {
        this.dateTypeSelect.addEventListener('change', () => {
            let reportDateExists = (!!document.getElementsByClassName('report-day')[0]);
            let selectedOptionIsOldWeight = (this.selectedOptionValue === 'old-weight');
            
            if (reportDateExists && !selectedOptionIsOldWeight) {
                this.hideDatetimeInputs();
            }
            else if (!reportDateExists && selectedOptionIsOldWeight) {
                this.showDatetimeInputs();
            }
        });
    }
    
    addSubmitButtonListener() {
        this.submitButton.addEventListener('click', (e) => {
            let isWeightValueValid = this.weightRegex.test(this.weightInput.value);

            if (!isWeightValueValid || this.selectedOptionValue === '') {
                e.preventDefault();
            }
        });
    }
    
    displayDeleteReportConfirmation(deleteReportClickedButton) {
        let selectedReport = deleteReportClickedButton.parentNode;
        
        let cancelReportDeletionButton = document.createElement('a');
        cancelReportDeletionButton.href = 'index.php?page=progress';
        cancelReportDeletionButton.classList = 'btn circle-btn purple-bkgd'
        cancelReportDeletionButton.textContent = 'Non';
        
        let reportDeletionMessage = document.createElement('div');
        reportDeletionMessage.classList = 'cancelation-alert';
        reportDeletionMessage.innerHTML = '<p>Etes-vous sûr de vouloir supprimer ce relevé ?</p>';
        
        let confirmReportDeletionButton = document.createElement('a');
        confirmReportDeletionButton.href = `index.php?action=delete-report&id=${selectedReport.id}`;
        confirmReportDeletionButton.classList = 'btn circle-btn red-bkgd';
        confirmReportDeletionButton.textContent = 'Oui';
        
        selectedReport.innerHTML = '';
        selectedReport.style.opacity = 0;
        
        selectedReport.appendChild(cancelReportDeletionButton);
        selectedReport.appendChild(reportDeletionMessage);
        selectedReport.appendChild(confirmReportDeletionButton);
        this.fadeInItem(selectedReport, 4000, 1);
    }
    
    hideDatetimeInputs() {
        let reportDate = document.getElementsByClassName('report-day')[0];
        let reportTime = document.getElementsByClassName('report-time')[0];
        
        this.formFields.classList.remove('full-length-fields');
        this.formFields.classList.add('sided-fields');
        
        this.formFields.removeChild(reportDate);
        this.formFields.removeChild(reportTime);
    }
    
    init() {
        this.addSelectListener();
        this.addDeleteReportListeners();
        this.addSubmitButtonListener();
    }
    
    showDatetimeInputs() {
        let reportDate = document.createElement('input');
        reportDate.type = 'date';
        reportDate.classList = 'report-day';
        reportDate.name = 'report-day';
        reportDate.max = new Date().toISOString().split('T')[0];
        reportDate.setAttribute('required', '');
        
        let reportTime = document.createElement('input');
        reportTime.type = 'time';
        reportTime.classList = 'report-time';
        reportTime.name = 'report-time';
        reportTime.setAttribute('required', '');
        
        this.formFields.classList.remove('sided-fields');
        this.formFields.classList.add('full-length-fields');
        
        this.formFields.appendChild(reportDate);
        this.formFields.appendChild(reportTime);
    }
}