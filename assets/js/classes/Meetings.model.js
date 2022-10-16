class Meetings extends UserPanels {
    constructor() {
        super();

        this._meetingDateInput = document.getElementById('user-next-meeting');
        this._submitButton = document.getElementById('submit-button');

        this._appointmentTab = document.getElementById('appointment-tab');
        this._scheduleNavElts = document.getElementsByClassName('schedule-days-nav')

        this._maxDisplayedDays = 2;
        this._listIndex;
        this._parsedMeetingsSlots;

        this._nextDaysSchedule = this.scheduleNavElts[1];
        this._previousDaysSchedule = this.scheduleNavElts[0];

        this._displayTabNextElements = this.displayTabNextElements.bind(this)
        this._displayTabPreviousElements = this.displayTabPreviousElements.bind(this)
    }

    get appointmentTab() {
        return this._appointmentTab;
    }

    get cancelMeetingButton() {
        return document.getElementById('cancel-appointment');
    }
    
    get cancelMeetingButtonContainer() {
        return document.getElementById('cancel-btn-container');
    }

    get meetingDateInput() {
        return this._meetingDateInput;
    }

    get submitButton() {
        return this._submitButton;
    }

    get scheduleNavElts() {
        return this._scheduleNavElts;
    }

    get maxDisplayedDays() {
        return this._maxDisplayedDays;
    }

    get listIndex() {
        return this._listIndex;
    }

    get parsedMeetingsSlots() {
        return this._parsedMeetingsSlots;
    }

    get nextDaysSchedule() {
        return this._nextDaysSchedule;
    }

    get previousDaysSchedule() {
        return this._previousDaysSchedule;
    }
    
    set listIndex(index) {
        this._listIndex = index;
    }

    set parsedMeetingsSlots (jsonObject) {
        this._parsedMeetingsSlots = jsonObject;
    }

    addCancelMeetingButtonEventListener() {
        this.cancelMeetingButton.addEventListener('click', () => {
            this.displayCancelMeetingConfirmation();
        })
    }

    addMeetingSlotButtonsEventListeners() {
        let meetingSlotButtons = document.querySelectorAll('.daily-schedule li button');
        meetingSlotButtons.forEach(meetingSlotButton => {
            meetingSlotButton.addEventListener('click', () => {
                let meetingSlotDate = meetingSlotButton.parentElement.parentElement.parentElement.getElementsByTagName('h4')[0].textContent;
                let slotTime = meetingSlotButton.textContent;
                let slotFormatedDate = meetingSlotDate.substring(meetingSlotDate.indexOf(' ') + 1);
                this.meetingDateInput.value = `le ${slotFormatedDate} à ${slotTime}`;
                this.submitButton.removeAttribute('disabled');
                this.meetingDateInput.removeAttribute('disabled');
            })
        })
    }

    buildFilteredMeetingArray() {
        let meetingsArray = [];

        meetingsArray[0] = {
            'date' : this.parsedMeetingsSlots[this.listIndex][0],
            'slots' : this.parsedMeetingsSlots[this.listIndex][1]
        };

        if (this.parsedMeetingsSlots.length >= 2) {
            meetingsArray[1] = {
                'date' : this.parsedMeetingsSlots[this.listIndex+1][0],
                'slots' : this.parsedMeetingsSlots[this.listIndex+1][1]
            };
        }

        return meetingsArray;
    }

    buildMeetingsCalendar(index) {
        this.setListIndex(index);
        let filteredMeetingsArray = this.buildFilteredMeetingArray();
        this.emptyMeetingTag();
        this.buildMeetingsTab(filteredMeetingsArray);
        this.displayMeetingsTabNavButton(filteredMeetingsArray);
        if (this.submitButton) {
            this.addMeetingSlotButtonsEventListeners();
        }
    }

    buildMeetingsTab(meetingsArrays) {
        meetingsArrays.forEach(meetingsArray => {
            let dailyMeetingsListElt = document.createElement('li');
            dailyMeetingsListElt.classList.add('daily-schedule');

            let listTitleElt = document.createElement('h4');
            listTitleElt.classList.add('meeting-day');
            listTitleElt.textContent = this.convertDateToFrenchDateString(meetingsArray.date);

            let meetingSlotsList = document.createElement('ul');

            meetingsArray.slots.forEach(meetingSlot => {
                let meetingSlotsListElt = document.createElement('li');
                meetingSlotsListElt.classList.add('btn', 'rounded-btn', 'purple-to-blue-bkgd')

                let meetingSlotButton = document.createElement('button');
                meetingSlotButton.textContent = this.convertTimeToFrenchTimeString(meetingSlot);
    
                meetingSlotsListElt.appendChild(meetingSlotButton);
                meetingSlotsList.appendChild(meetingSlotsListElt);
            })

            meetingSlotsList.classList.add('daily-slots');

            dailyMeetingsListElt.appendChild(listTitleElt);
            dailyMeetingsListElt.appendChild(meetingSlotsList);

            this.appointmentTab.appendChild(dailyMeetingsListElt);
        })
    }

    buildParsedMeetingsData() {
        let meetingsSlotData = document.getElementById('appointment-tab').attributes['data-meeting-slots'].textContent;
        this.parsedMeetingsSlots = Object.entries(JSON.parse(meetingsSlotData))
    }

    convertDateToFrenchDateString(dateItem) {
        let weekday = ['Dimanche', 'Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi', 'Samedi'];
        let months = ['janvier', 'février', 'mars', 'avril', 'mai', 'juin', 'juillet', 'août', 'septembre', 'octobre', 'novembre', 'décembre'];
        dateItem = new Date(dateItem);
        let day = weekday[dateItem.getDay()];
        let month = months[dateItem.getMonth()];
        let convertedDate = `${day} ${dateItem.getDate()} ${month}`;

        return convertedDate;
    }

    convertTimeToFrenchTimeString(timeItem) {
        let hours = timeItem.split(':')[0];
        let minutes = timeItem.split(':')[1];
        let convertedTime = `${hours}h${minutes}`;

        return convertedTime;
    }

    displayCancelMeetingConfirmation() {
        let cancelMeetingCancelationButton = document.createElement('a');
        cancelMeetingCancelationButton.href = 'index.php?page=meetings';
        cancelMeetingCancelationButton.classList = 'btn user-panel-rounded-btn purple-to-blue-bkgd'
        cancelMeetingCancelationButton.textContent = 'Non';

        let meetingConcelationMessage = document.createElement('div');
        meetingConcelationMessage.classList = 'cancelation-alert';
        meetingConcelationMessage.innerHTML = '<p>Etes-vous sûr de vouloir supprimer ce rendez-vous ?</p>';

        let confirmMeetingCancelationLink = document.createElement('a');
        confirmMeetingCancelationLink.href = 'index.php?action=cancel-appointment';
        confirmMeetingCancelationLink.classList = 'btn user-panel-rounded-btn red-bkgd';
        confirmMeetingCancelationLink.textContent = 'Oui';

        this.cancelMeetingButtonContainer.innerHTML = '';
        this.cancelMeetingButtonContainer.style.opacity = 0;
        this.cancelMeetingButtonContainer.appendChild(cancelMeetingCancelationButton);
        this.cancelMeetingButtonContainer.appendChild(meetingConcelationMessage);
        this.cancelMeetingButtonContainer.appendChild(confirmMeetingCancelationLink);
        this.cancelMeetingButtonContainer.onload = this.fadeInItem(this.cancelMeetingButtonContainer, 4000);
    }

    displayTabNextElements() {
        this.listIndex = this.listIndex +2;
        this.buildMeetingsCalendar(this.listIndex);
    }

    displayTabPreviousElements() {
        this.listIndex = this.listIndex -2;
        this.buildMeetingsCalendar(this.listIndex);
    }

    displayMeetingsTabNavButton (filteredMeetingsArray) {
        if (this.parsedMeetingsSlots.length <= this.maxDisplayedDays) {
            this.previousDaysSchedule.innerHTML = '';
            this.nextDaysSchedule.innerHTML = '';
        }
        else {
            if (this.listIndex === 0 ) {
                this.previousDaysSchedule.innerHTML = '';
            }
            else {
                this.previousDaysSchedule.innerHTML = "<button class='btn previous-days-btn purple-to-blue-bkgd'><i class='fa-solid fa-angle-left'></i></button>";
                let previousDaysScheduleBtn = this.previousDaysSchedule.getElementsByTagName('button')[0];
                previousDaysScheduleBtn.addEventListener('click', this._displayTabPreviousElements);
            }

            if (this.listIndex >= this.parsedMeetingsSlots.length -2) {
                this.nextDaysSchedule.innerHTML = '';
            }
            else {
                this.nextDaysSchedule.innerHTML = "<button class='btn next-days-btn purple-to-blue-bkgd'><i class='fa-solid fa-angle-right'></i></button>";
                let nextDaysScheduleBtn = this.nextDaysSchedule.getElementsByTagName('button')[0]

                nextDaysScheduleBtn.addEventListener('click', this._displayTabNextElements);
            }
        }
    }

    emptyMeetingTag() {
        this.appointmentTab.innerHTML = '';
    }

    init() {
        if (this.appointmentTab) {
            this.buildParsedMeetingsData();
            this.buildMeetingsCalendar(0);
        }

        if (this.cancelMeetingButton) {
            this.addCancelMeetingButtonEventListener();
        }
    }

    setListIndex(index) {
        if ((index >= 0) && (index <= this.parsedMeetingsSlots.length-2)) {
            this.listIndex = index;
        }
        else if (index < 2) {
            this.listIndex = 0;
        }
        else {
            this.listIndex = this.parsedMeetingsSlots.length - 2;
        }
    }
}