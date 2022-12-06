class ApplianceDetails extends UserPanels {
    constructor() {
        super();

        this._adminPanel = document.getElementsByClassName('admin-panel')[0];
        this._applianceDecisionBox = document.getElementById('appliance-decision-box');
        this._declineApplianceBtn = document.getElementById('decline-btn');

        this._applianceId;
    }

    get adminPanel() {
        return this._adminPanel;
    }

    get applianceDecisionBox() {
        return this._applianceDecisionBox;
    }

    get applianceId() {
        return this._applianceId;
    }

    get declineApplianceBtn() {
        return this._declineApplianceBtn;
    }

    set applianceId(id) {
        this._applianceId = id;
    }

    init() {
        this.addDeclineApplianceButtonListener();
    }

    addDeclineApplianceButtonListener() {
        this.declineApplianceBtn.addEventListener('click', () => {
            this.setApplianceId();
            this.removeApplianceDecisionBox();
            this.buildRejectApplianceForm();
        });
    }

    removeApplianceDecisionBox() {
        this.adminPanel.removeChild(this.applianceDecisionBox);
    }

    setApplianceId() {
        this.applianceId = this.declineApplianceBtn.getAttribute('data-id')
    }

    buildRejectApplianceForm() {
        const rejectApplianceForm = document.createElement('form');
        const rejectionMessage = document.createElement('textarea');
        const reloadBtn = document.createElement('a');
        const confirmRejectionBtn = document.createElement('input');

        rejectApplianceForm.classList.add('admin-form', 'appliance-form');
        rejectApplianceForm.action = `index.php?action=reject-appliance&id=${this.applianceId}`;
        rejectApplianceForm.method = 'post';
        rejectApplianceForm.style.opacity = 0;

        rejectionMessage.placeholder = 'Votre message de refus de prise en charge';
        rejectionMessage.name = 'rejection-message';
        rejectionMessage.id = 'rejection-message';

        reloadBtn.innerText = 'Annuler';
        reloadBtn.href = `index.php?page=appliance-details&id=${this.applianceId}`;
        reloadBtn.classList.add('btn', 'rounded-btn', 'tiny-btn');

        confirmRejectionBtn.value = 'Confirmer';
        confirmRejectionBtn.type = 'submit';
        confirmRejectionBtn.classList.add('btn', 'rounded-btn', 'tiny-btn', 'red-bkgd');

        rejectApplianceForm.appendChild(rejectionMessage);
        rejectApplianceForm.appendChild(reloadBtn);
        rejectApplianceForm.appendChild(confirmRejectionBtn);

        this.adminPanel.appendChild(rejectApplianceForm);
        rejectApplianceForm.onload = this.fadeInItem(rejectApplianceForm, 4000);
    }
}