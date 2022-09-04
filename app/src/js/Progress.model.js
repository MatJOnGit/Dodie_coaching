class Progress {
    constructor() {
        this._dateTypeSelect = document.getElementById('date-selector');
        this._formFields = document.getElementsByClassName('form-fields')[0];
    }

    get dateTypeSelect() {
        return this._dateTypeSelect;
    }

    get formFields() {
        return this._formFields;
    }

    get dateTypeSelectedOption() {
        return this.dateTypeSelect.options[this.dateTypeSelect.selectedIndex];
    }

    showDatetimeInputs() {
        let reportDate = document.createElement('input');
        reportDate.type = 'date';
        reportDate.classList = 'report-date';
        reportDate.name = 'report-date';
        reportDate.max = new Date().toLocaleDateString();
        reportDate.setAttribute('required', '');

        let reportTime = document.createElement('input');
        reportTime.type = 'time';
        reportTime.classList = 'report-time';
        reportTime.name = 'report-time';
        reportDate.setAttribute('required', '');

        
        this.formFields.classList.remove('sided-fields');
        this.formFields.classList.add('full-length-fields');

        this.formFields.appendChild(reportDate);
        this.formFields.appendChild(reportTime);

    }

    hideDatetimeInputs() {
        let reportDate = document.getElementsByClassName('report-date')[0];
        let reportTime = document.getElementsByClassName('report-time')[0];

        this.formFields.classList.remove('full-length-fields');
        this.formFields.classList.add('sided-fields');

        this.formFields.removeChild(reportDate);
        this.formFields.removeChild(reportTime);
    }

    addSelectEventListener() {
        this.dateTypeSelect.addEventListener('change', () => {
            let reportDateExists = (!!document.getElementsByClassName('report-date')[0]);
            let selectedOptionIsOldWeight = this.dateTypeSelectedOption.value === 'old-weight';

            if (reportDateExists && !selectedOptionIsOldWeight) {
                this.hideDatetimeInputs();
            }
            else if (!reportDateExists && selectedOptionIsOldWeight) {
                this.showDatetimeInputs();
            }
        });
    }

    init() {
        this.addSelectEventListener();
    }
}