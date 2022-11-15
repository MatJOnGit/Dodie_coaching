class ApplicationDetails {
    constructor() {
        this._adminPanel = document.getElementsByClassName('admin-panel')[0];
        this._applicationDecisionBox = document.getElementById('application-decision-box');
        this._declineApplicationBtn = document.getElementById('decline-btn');

        this._rejectApplicationLink = 'index.php?action=rejectApplication&id=';

        this._applicationId;
    }

    get adminPanel() {
        return this._adminPanel;
    }

    get applicationDecisionBox() {
        return this._applicationDecisionBox;
    }

    get applicationId() {
        return this._applicationId;
    }

    get declineApplicationBtn() {
        return this._declineApplicationBtn;
    }

    get rejectApplicationLink() {
        return this._rejectApplicationLink;
    }

    set applicationId(id) {
        this._applicationId = id;
    }

    init() {
        this.addDeclineApplicationButtonListener();
    }

    addDeclineApplicationButtonListener() {
        this.declineApplicationBtn.addEventListener('click', () => {
            this.getApplicationId();
            this.removeApplicationDecisionBox();
            this.buildRejectApplicationForm();
        });
    }

    removeApplicationDecisionBox() {
        this.adminPanel.removeChild(this.applicationDecisionBox);
    }

    getApplicationId() {
        this.applicationId = this.declineApplicationBtn.getAttribute('data-id')
    }

    buildRejectApplicationForm() {
        const rejectApplicationForm = document.createElement('form');
        const rejectionMessage = document.createElement('textarea');
        const reloadBtn = document.createElement('a');
        const confirmRejectionBtn = document.createElement('input');

        rejectApplicationForm.id = 'reject-application-form';
        rejectApplicationForm.action = `${this.rejectApplicationLink}${this.applicationId}`;
        rejectApplicationForm.method = 'post';

        rejectionMessage.placeholder = 'Votre message de refus de prise en charge';

        reloadBtn.innerText = 'Annuler';
        reloadBtn.href = `index.php?page=application-details&id=${this.applicationId}`;
        reloadBtn.classList.add('btn', 'rounded-btn', 'tiny-btn');

        confirmRejectionBtn.value = 'Confirmer';
        confirmRejectionBtn.type = 'submit';
        confirmRejectionBtn.classList.add('btn', 'rounded-btn', 'tiny-btn', 'red-bkgd');

        rejectApplicationForm.appendChild(rejectionMessage);
        rejectApplicationForm.appendChild(reloadBtn);
        rejectApplicationForm.appendChild(confirmRejectionBtn);

        this.adminPanel.appendChild(rejectApplicationForm);
    }
}