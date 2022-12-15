class MeetingsManagementHelper extends UserPanels {
    constructor() {
        super();

        this._addMeetingBtn = document.getElementById('add-meeting-btn');
        this._incomingMeetingsTab = document.getElementById('meeting-slots');
        this._meetingPanel = document.getElementsByClassName('admin-panel')[0];

        this._meetingsSafetyMargin = 60;
        this._parsedIncomingMeetings;
    }

    get addMeetingBtn() {
        return this._addMeetingBtn;
    }

    get incomingMeetingsTab() {
        return this._incomingMeetingsTab;
    }

    get meetingSafetyMargin() {
        return this._meetingsSafetyMargin;
    }

    get meetingPanel() {
        return this._meetingPanel;
    }

    get parsedIncomingMeetings() {
        return this._parsedIncomingMeetings;
    }

    set parsedIncomingMeetings(jsonObject) {
        this._parsedIncomingMeetings = jsonObject;
    }

    addAddMeetingButtonListener() {
        this.addMeetingBtn.addEventListener('click', () => {
            this.displayAddMeetingElements();
            this.triggerMeetingAdditionButtons();
            this.addMeetingDayListener();
            this.addMeetingSlotSubmitTest();
        })
    }

    addMeetingDayListener() {
        const meetingDayInput = document.getElementById('meeting-day');

        meetingDayInput.addEventListener('change', (e) => {
            let dayEntry = new Date(e.target.value);
            let formatedDayEntry = dayEntry.toLocaleDateString('fr');
            let enteredDateIndex = -1;

            this.parsedIncomingMeetings.forEach((incomingMeetingData, index) => {
                if (incomingMeetingData[0] === formatedDayEntry) {
                    enteredDateIndex = index;
                }
            });
            
            if (enteredDateIndex >= 0) {
                this.removePreviousDates();
                this.displayDailyMeetingList(enteredDateIndex);
            }
            
            else {
                this.removePreviousDates();
            }
        });
    }

    addMeetingsButtonsListeners() {
        const editMeetingBtns = document.querySelectorAll('.edit-meeting');
        editMeetingBtns.forEach(editMeetingBtn => {
            editMeetingBtn.addEventListener('click', (e) => {
                this.triggerEditMeetingBtns(e.target);
            })
        });
    }

    addMeetingSlotSubmitTest() {
        const meetingForm = document.getElementsByClassName('meeting-form')[0];

        meetingForm.addEventListener('submit', (e) => {
            let dateInput = document.getElementById('meeting-day');
            let timeInput = document.getElementById('meeting-time');
            let dayValue = new Date(dateInput.value);
            let formatedDayValue = dayValue.toLocaleDateString('fr');
            let timeValue = timeInput.value;
            let isMeetingTimeAllowed = true;

            this.parsedIncomingMeetings.forEach((incomingMeetingData, index) => {
                if (incomingMeetingData[0] === formatedDayValue) {
                    incomingMeetingData[1].forEach(meetingItem => {
                        if (!this.verifyMeetingTime(meetingItem['starting_time'], timeValue)) {
                            isMeetingTimeAllowed = false;
                        }
                    })
                }
            });

            if (!isMeetingTimeAllowed) {
                e.preventDefault();
                this.displayNotAllowedMeetingAlert();
                this.fadeawayAlert();
            }
        })
    }

    buildAddMeetingForm() {
        const addMeetingForm = document.createElement('form');
        const dateContainer = document.createElement('div');
        const meetingDayLabel = document.createElement('label');
        const meetingDayInput = document.createElement('input');
        const meetingTimeLabel = document.createElement('label');
        const meetingTimeInput = document.createElement('input');
        const cancelBtn = document.createElement('a');
        const saveMeetingBtn = document.createElement('input');

        addMeetingForm.classList.add('meeting-form');
        addMeetingForm.action = 'index.php?action=save-meeting';
        addMeetingForm.method = 'post';
        addMeetingForm.style.opacity = 0;

        dateContainer.classList.add('date-container');

        meetingDayLabel.setAttribute('for', 'meeting-day');
        meetingDayLabel.textContent = 'Le';

        meetingDayInput.type = 'date';
        meetingDayInput.id = 'meeting-day';
        meetingDayInput.name = 'meeting-day';
        meetingDayInput.min = new Date().toISOString().split('T')[0];
        meetingDayInput.required = true;

        meetingTimeLabel.setAttribute('for', 'meeting-time');
        meetingTimeLabel.textContent = 'à';

        meetingTimeInput.type = 'time';
        meetingTimeInput.id = 'meeting-time';
        meetingTimeInput.name = 'meeting-time';
        meetingTimeInput.required = true;

        cancelBtn.classList.add('btn', 'rounded-btn', 'large-btn', 'red-bkgd');
        cancelBtn.id = 'cancel-btn';
        cancelBtn.href = 'index.php?page=meetings-management';
        cancelBtn.textContent = "Annuler";

        saveMeetingBtn.value = "Enregistrer";
        saveMeetingBtn.id = 'submit-meeting-btn';
        saveMeetingBtn.type = 'submit';
        saveMeetingBtn.classList.add('btn', 'rounded-btn', 'tiny-btn');

        dateContainer.appendChild(meetingDayLabel);
        dateContainer.appendChild(meetingDayInput);
        dateContainer.appendChild(meetingTimeLabel);
        dateContainer.appendChild(meetingTimeInput);
        addMeetingForm.appendChild(dateContainer);
        addMeetingForm.appendChild(cancelBtn);
        addMeetingForm.appendChild(saveMeetingBtn);
        this.meetingPanel.appendChild(addMeetingForm);
        this.fadeInItem(addMeetingForm, 4000, 1);
    }

    buildMeetingsList() {
        this.parsedIncomingMeetings.forEach(incomingMeetingData => {
            const meetingDateListItem = document.createElement('li');
            const meetingDateTitle = document.createElement('h4');
            const dailyMeetingsBtnsList = document.createElement('ul');

            meetingDateListItem.classList.add('daily-data');
            meetingDateTitle.textContent = incomingMeetingData[0];
            meetingDateTitle.classList.add('section-header', 'orange-bkgd');
            dailyMeetingsBtnsList.classList.add('daily-meetings-list')

            meetingDateListItem.appendChild(meetingDateTitle);
            meetingDateListItem.appendChild(dailyMeetingsBtnsList);

            incomingMeetingData[1].forEach(incomingMeetingData => {
                let bookedMeeting = incomingMeetingData['name'] ? true : false;

                let meetingItem = document.createElement('li');
                let meetingBtn = document.createElement('button');
                let cancelEditionBtn = document.createElement('a');
                let deleteMeetingBtn = document.createElement('a');

                meetingItem.classList.add('meeting-item');
                meetingBtn.id = incomingMeetingData['slot_id'];
                meetingBtn.classList.add('btn', 'large-btn', 'edit-meeting');
                meetingBtn.textContent = incomingMeetingData['starting_time'] + ' : ';
                meetingBtn.textContent += bookedMeeting ? incomingMeetingData['name'] : 'disponible';
                cancelEditionBtn.classList.add('btn', 'rounded-btn', 'hidden');
                cancelEditionBtn.textContent = 'Annuler';
                cancelEditionBtn.href = 'index.php?page=meetings-management';
                deleteMeetingBtn.classList.add('btn', 'rounded-btn', 'hidden', 'red-bkgd');
                deleteMeetingBtn.textContent = bookedMeeting ? 'Supprimer le rendez-vous' : 'Supprimer le créneau';
                deleteMeetingBtn.href = 'index.php?action=delete-meeting&id=' + incomingMeetingData['slot_id'];

                meetingItem.appendChild(meetingBtn);
                meetingItem.appendChild(cancelEditionBtn);
                meetingItem.appendChild(deleteMeetingBtn);
                dailyMeetingsBtnsList.appendChild(meetingItem);
            })

            this.incomingMeetingsTab.appendChild(meetingDateListItem);
        });
    }

    buildParsedMeetingsData() {
        let incomingMeetingsData = this.incomingMeetingsTab.attributes['data-meeting-slots'].textContent;
        this.parsedIncomingMeetings = Object.entries(JSON.parse(incomingMeetingsData));
    }

    displayAddMeetingElements() {
        this.removeMeetingsTab();
        this.editPageTitle();
        this.buildAddMeetingForm();
    }

    displayDailyMeetingList(enteredDateIndex) {
        const dailyMeetingsTitle = document.createElement('h3');
        const dailyMeetingsList = document.createElement('ul');
        const dailyMeetings = this.parsedIncomingMeetings[enteredDateIndex][1];

        dailyMeetingsTitle.id = 'daily-meetings-title';
        dailyMeetingsTitle.textContent = 'Rendez-vous déjà enregistrés ce jour';
        dailyMeetingsList.id = 'daily-meetings-list';

        dailyMeetings.forEach(meeting => {
            let dailyMeetingItem = document.createElement('li');
            dailyMeetingItem.textContent = meeting['starting_time'];
            
            if (meeting['name']) {
                dailyMeetingItem.textContent += ` (réservé par ${meeting['name']})`
            }
            dailyMeetingsList.appendChild(dailyMeetingItem);

        })

        this.meetingPanel.appendChild(dailyMeetingsTitle);
        this.meetingPanel.appendChild(dailyMeetingsList);
    }

    displayNotAllowedMeetingAlert() {
        const alertBox = document.createElement('div');
        const alertMessage = document.createElement('p');

        alertBox.classList.add('alert-box');
        alertMessage.textContent = "Vous ne pouvez pas créer ce créneau horaire à cause d'un conflit avec un autre rendez-vous ce jour-ci";

        alertBox.appendChild(alertMessage);
        this.meetingPanel.appendChild(alertBox);
    }

    editPageTitle() {
        const panelTitle = this.meetingPanel.getElementsByTagName('h3')[0];
        const ptrn = "Vos prochains"
        const addMeetingTitle = "Ajouter un créneau de";
        panelTitle.textContent = panelTitle.textContent.replace(ptrn, addMeetingTitle);
    }

    fadeawayAlert() {
        let alertBox = document.getElementsByClassName('alert-box')[0]

        setTimeout(() => {
            this.fadeOutItem(alertBox, 4000);
        }, 10000);

        setTimeout(() => {
            this.meetingPanel.removeChild(alertBox);
        }, 14000);
    }

    init() {
        if (this.incomingMeetingsTab) {
            this.buildParsedMeetingsData();
            this.buildMeetingsList();
            this.addMeetingsButtonsListeners();
            this.addAddMeetingButtonListener();
        }
    }

    removeMeetingsTab() {
        const meetingSlotsList = document.getElementById('meeting-slots');
        this.meetingPanel.removeChild(meetingSlotsList);
        this.meetingPanel.removeChild(this.addMeetingBtn)
    }

    removePreviousDates() {
        const dailyMeetingTitle = document.getElementById('daily-meetings-title');
        const dailyMeetingsList = document.getElementById('daily-meetings-list');

        if (dailyMeetingTitle) {
            this.meetingPanel.removeChild(dailyMeetingTitle)
        }
        if (dailyMeetingsList) {
            this.meetingPanel.removeChild(dailyMeetingsList)
        }
    }

    triggerEditMeetingBtns(clickedElt) {
        let clickedEltLinks = clickedElt.closest('li').querySelectorAll('a');

        if (clickedElt.classList.contains('selected')) {
            clickedElt.classList.remove('selected');
            clickedEltLinks.forEach(linkItem => {
                linkItem.classList.replace('tiny-btn', 'hidden');
            });
        }

        else {
            clickedElt.classList.add('selected');
            clickedEltLinks.forEach(linkItem => {
                linkItem.classList.remove('hidden');
                linkItem.style.opacity = 0;
                linkItem.classList.add('tiny-btn');
                this.fadeInItem(linkItem, 4000, 1);
            });
        }
    }

    triggerMeetingAdditionButtons() {
        const dateContainer = document.getElementsByClassName('date-container')[0];
        const dateInputElts = dateContainer.querySelectorAll('input');
        const dayInput = document.getElementById('meeting-day');
        const timeInput = document.getElementById('meeting-time');
        const cancelBtn = document.getElementById('cancel-btn');
        const saveMeetingBtn = document.getElementById('submit-meeting-btn');

        dateInputElts.forEach(dateInput => {
            dateInput.addEventListener('change', () => {
                if ((dayInput.value != '') && (timeInput.value != '')) {
                    if (cancelBtn.classList.contains('large-btn')) {
                        cancelBtn.classList.replace('large-btn', 'tiny-btn');
                    }
                    saveMeetingBtn.style.display = 'flex';
                }

                else {
                    if (cancelBtn.classList.contains('tiny-btn')) {
                        cancelBtn.classList.replace('tiny-btn', 'large-btn');
                    }
                    saveMeetingBtn.style.display = 'none';
                }
            })
        });
    }

    verifyMeetingTime(savedMeetingTime, submittedMeetingTime) {
        savedMeetingTime = savedMeetingTime.replace('h',':');
        let isMeetingTimeAllowed;

        let savedMeetingTimeHours = +savedMeetingTime.split(':')[0];
        let savedMeetingTimeMinutes = +savedMeetingTime.split(':')[1];
        let savedMeetingTimestamp = savedMeetingTimeHours * 60 + savedMeetingTimeMinutes;

        let submittedMeetingTimeHours = +submittedMeetingTime.split(':')[0];
        let submittedMeetingTimeMinutes = +submittedMeetingTime.split(':')[1];
        let submittedMeetingTimestamp = submittedMeetingTimeHours * 60 + submittedMeetingTimeMinutes;

        let safetyTimestampMin = savedMeetingTimestamp - this.meetingSafetyMargin;
        let safetyTimestampMax = savedMeetingTimestamp + this.meetingSafetyMargin;
        isMeetingTimeAllowed = true;

        if (submittedMeetingTimestamp > safetyTimestampMin && submittedMeetingTimestamp < safetyTimestampMax) {
            isMeetingTimeAllowed = false;
        }

        return isMeetingTimeAllowed;
    }
}