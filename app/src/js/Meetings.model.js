class Meetings {
    constructor() {
        this.meetingDateInput = document.getElementById('user-next-meeting');
        this.meetingFormSubmitButton = document.getElementById('appointment-form-submit-button');
        this.appointmentTab = document.getElementById('appointment-tab');
        this.scheduleNavElts = document.getElementsByClassName('schedule-days-nav')
        this.previousDaysSchedule = this.scheduleNavElts[0];
        this.nextDaysSchedule = this.scheduleNavElts[1];
        this.maxDisplayedDays = 2;
        this.parsedMeetingSlots;
        this.meetingsListIndex;
    }

    getMeetingsListIndex() {
        return this.meetingsListIndex;
    }

    setMeetingsListIndex(index) {
        this.meetingsListIndex = index;
    }

    getParseMeetingsData() {
        return this.parsedMeetingSlots;
    }

    getAppointmentTab() {
        return this.appointmentTab;
    }

    setParsedMeetingsData() {
        let meetingSlotData = document.getElementById('appointment-tab').attributes['data-meeting-slots'].textContent;
        this.parsedMeetingSlots = Object.entries(JSON.parse(meetingSlotData));
    }

    addMeetingSlotButtonEventListeners() {
        let meetingSlotButtons = document.querySelectorAll('.daily-schedule li button');
        meetingSlotButtons.forEach(meetingSlotButton => {
            meetingSlotButton.addEventListener('click', event => {
                event.preventDefault();
                let meetingSlotDate = meetingSlotButton.parentElement.parentElement.parentElement.getElementsByTagName('h4')[0].textContent;
                let slotTime = meetingSlotButton.textContent;
                let slotFormatedDate = meetingSlotDate.substring(meetingSlotDate.indexOf(' ') + 1);
                this.meetingDateInput.value = `${slotFormatedDate} à ${slotTime}`;
                this.meetingFormSubmitButton.removeAttribute('disabled');
                this.meetingDateInput.removeAttribute('disabled');
            })
        })
    }

    getFilteredMeetingArray() {
        let meetingsArray = [];

        meetingsArray[0] = {
            'date' : this.parsedMeetingSlots[this.getMeetingsListIndex()][0],
            'slots' : this.parsedMeetingSlots[this.getMeetingsListIndex()][1]
        };

        if (this.parsedMeetingSlots.length >= 2) {
            meetingsArray[1] = {
                'date' : this.parsedMeetingSlots[this.getMeetingsListIndex()+1][0],
                'slots' : this.parsedMeetingSlots[this.getMeetingsListIndex()+1][1]
            };
        }

        return meetingsArray;
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

    displayTabNavButtons(filteredMeetingsArray) {
        if (this.parsedMeetingSlots.length <= this.maxDisplayedDays) {
            this.previousDaysSchedule.innerHTML = '';
            this.nextDaysSchedule.innerHTML = '';
        }
        else {
            if (this.meetingsListIndex === 0 ) {
                this.previousDaysSchedule.innerHTML = '';
            }
            else {
                this.previousDaysSchedule.innerHTML = "<button class='btn previous-days-btn purple-to-blue-bkgd'><i class='fa-solid fa-angle-left'></i></button>";
            }

            if (this.meetingsListIndex >= this.parsedMeetingSlots.length -2) {
                this.nextDaysSchedule.innerHTML = '';
            }
            else {
                this.nextDaysSchedule.innerHTML = "<button class='btn next-days-btn purple-to-blue-bkgd'><i class='fa-solid fa-angle-right'></i></button>";
            }
        }
    }

    verifyIndex(index) {
        if ((index >= 0) && (index <= this.getParseMeetingsData().length-2)) {
            this.setMeetingsListIndex(index);
        }
        else if (index < 0) {
            this.setMeetingsListIndex(0);
        }
        else {
            this.setMeetingsListIndex(this.getParseMeetingsData().length-2);
        }
    }

    emptyMeetingTag() {
        this.appointmentTab.innerHTML = '';
    }

    addTabNavButtonsEventListeners() {
        for (let scheduleNavElt of this.scheduleNavElts) {
            if (scheduleNavElt.hasChildNodes('button')) {
                if (scheduleNavElt.classList.contains('previous-days-nav')) {
                    scheduleNavElt.addEventListener('click', event => {
                        this.setMeetingsCalendar(this.meetingsListIndex-2);
                    })
                }
                else if (scheduleNavElt.classList.contains('next-days-nav')) {
                    scheduleNavElt.addEventListener('click', event => {
                        this.setMeetingsCalendar(this.meetingsListIndex+2);
                    })
                }
                
            }
        }
    }

    setMeetingsCalendar(index) {
        this.verifyIndex(index);
        let filteredMeetingsArray = this.getFilteredMeetingArray(this.getMeetingsListIndex());
        this.emptyMeetingTag();
        this.buildMeetingsTab(filteredMeetingsArray);
        this.displayTabNavButtons(filteredMeetingsArray);
        this.addTabNavButtonsEventListeners();
        this.addMeetingSlotButtonEventListeners();
    }

    init() {
        this.setParsedMeetingsData()
        this.setMeetingsCalendar(0);
    }
}