class MeetingBooker extends ElementFader {
    constructor() {
        super();
        
        this._meetingsDayList = document.getElementById('meetings-day-list');
        this._prevDaysButton = document.getElementById('previous-days-btn');
        this._nextDaysButton = document.getElementById('next-days-btn');
        this._cancelAppointmentButton = document.getElementById('cancel-appointment-btn');
        
        this._meetingDays = [];
        this._pointerPosition = 0;
        this._maxDisplayedDaysPerDevice = {
            mobile: 2,
            tablet: 4,
            desktop: 5
        };
        
        this.convertMeetingsDataToArray(JSON.parse(this.meetingsDayList.dataset.meetings));
        this.setMaxDisplayedDays();
        this.buildPlanning();
        this.addEventsListener();
    }
    
    get cancelAppointmentButton() {
        return this._cancelAppointmentButton;
    }
    
    get prevDaysButton() {
        return this._prevDaysButton;
    }
    
    get nextDaysButton() {
        return this._nextDaysButton;
    }
    
    get meetingsDayList() {
        return this._meetingsDayList;
    }
    
    get maxDisplayedDaysPerDevice() {
        return this._maxDisplayedDaysPerDevice;
    }
    
    get meetingsData() {
        return this._meetingsData;
    }
    
    get meetingDays() {
        return this._meetingDays;
    }
    
    get maxDisplayedDays() {
        return this._maxDisplayedDays;
    }
    
    get displayedDays() {
        return this._displayedDays;
    }
    
    get pointerPosition() {
        return this._pointerPosition;
    }
    
    get calendarLength() {
        return this._calendarLength;
    }
    
    set meetingsData(data) {
        this._meetingsData = data;
    }
    
    set maxDisplayedDays(maxDisplayedDays) {
        this._maxDisplayedDays = maxDisplayedDays;
    }
    
    set displayedDays(displayedDaysObject) {
        this._displayedDays = displayedDaysObject;
    }
    
    set pointerPosition(newPosition) {
        this._pointerPosition = newPosition;
    }
    
    set calendarLength(length) {
        this._calendarLength = length;
    }
    
    addEventsListener() {
        const addClickListener = (button, handler) => {
            button.addEventListener('click', handler);
        }
        
        if (this.cancelAppointmentButton) {
            addClickListener(this.cancelAppointmentButton, () => this.displayCancelMeetingConfirmation())
        }
        
        addClickListener(this.prevDaysButton, (e) => this.handlePrevButtonClick(e));
        addClickListener(this.nextDaysButton, (e) => this.handleNextButtonClick(e));
    }
    
    displayCancelMeetingConfirmation() {
        const cancelMeetingButtonContainer = document.getElementById('cancel-appointment-btn-container');
        
        const cancelMeetingCancelationButton = document.createElement('a');
        cancelMeetingCancelationButton.href = 'index.php?page=meetings-booking';
        cancelMeetingCancelationButton.classList = 'btn small-circle-btn purple-bkgd';
        cancelMeetingCancelationButton.textContent = 'Non';
        
        const meetingConcelationMessage = document.createElement('div');
        meetingConcelationMessage.classList = 'cancelation-alert';
        meetingConcelationMessage.innerHTML = '<p>Etes-vous sûr de vouloir supprimer ce rendez-vous ?</p>';
        
        const confirmMeetingCancelationLink = document.createElement('a');
        confirmMeetingCancelationLink.href = 'index.php?action=cancel-appointment';
        confirmMeetingCancelationLink.classList = 'btn small-circle-btn red-bkgd';
        confirmMeetingCancelationLink.textContent = 'Oui';
        
        cancelMeetingButtonContainer.innerHTML = '';
        cancelMeetingButtonContainer.style.opacity = 0;
        cancelMeetingButtonContainer.appendChild(cancelMeetingCancelationButton);
        cancelMeetingButtonContainer.appendChild(meetingConcelationMessage);
        cancelMeetingButtonContainer.appendChild(confirmMeetingCancelationLink);
        this.fadeInItem(cancelMeetingButtonContainer, 4000, 1);
    }
    
    handleMeetingSlotClick(e) {
        const nextMeetingDateInput = document.getElementById('user-next-meeting');
        const bookMeetingButton = document.getElementById('book-meeting-btn');
        
        const meetingSlotDate = e.target.closest('.daily-schedule').querySelector('h4').textContent;
        const meetingSlotTime = e.target.textContent;
        const slotFormatedDate = meetingSlotDate.substring(meetingSlotDate.indexOf(' ') + 1);
        
        nextMeetingDateInput.value = `le ${slotFormatedDate} à ${meetingSlotTime}`;
        bookMeetingButton.removeAttribute('disabled');
        nextMeetingDateInput.removeAttribute('disabled');
    }
    
    convertMeetingsDataToArray(meetingsDataObj) {
        this.meetingsData = Object.entries(meetingsDataObj).map(([date, meetingSlots]) => ({
            date: this.convertIsoDateToFrenchDate(date),
            meetingSlots,
        }));
    }
    
    setMaxDisplayedDays() {
        const windowScreenSize = window.innerWidth;
        
        if (windowScreenSize < 768) {
            this.maxDisplayedDays = this.maxDisplayedDaysPerDevice.mobile
        }
        else if (windowScreenSize < 1024) {
            this.maxDisplayedDays = this.maxDisplayedDaysPerDevice.tablet
        }
        else {
            this.maxDisplayedDays = this.maxDisplayedDaysPerDevice.desktop
        }
    }
    
    buildPlanning() {
        this.displayedDays = this.meetingsData.slice(this.pointerPosition, this.pointerPosition + this.maxDisplayedDays);
        this.meetingsDayList.innerHTML = '';
        
        for (const { date, meetingSlots } of this.displayedDays) {
            const dateTitle = document.createElement('h4');
            dateTitle.textContent = date;
            dateTitle.classList.add('meeting-day');
            const dailySchedule = document.createElement('li');
            dailySchedule.classList.add('daily-schedule');
            const dailySlots = document.createElement('ul');
            dailySlots.classList.add('daily-slots');
          
            for (const slot of meetingSlots) {
                const meetingItem = document.createElement('li');
                meetingItem.classList.add('rounded');
                const meetingButton = document.createElement('button');
                meetingButton.classList.add('purple-bkgd', 'meeting-slot-btn');
                meetingButton.textContent = this.convertTimeToFrenchTimeString(slot);
                meetingItem.appendChild(meetingButton);
                dailySlots.appendChild(meetingItem);
            }
          
            dailySchedule.appendChild(dateTitle);
            dailySchedule.appendChild(dailySlots);
            this.meetingsDayList.appendChild(dailySchedule);
        }
        
        this.addMeetingSlotsEventListener();
        this.manageNavButtonsDisplay();
    }
    
    addMeetingSlotsEventListener() {
        if (!this.cancelAppointmentButton) {
            const meetingSlotButtons = this.meetingsDayList.querySelectorAll('.meeting-slot-btn');
            meetingSlotButtons.forEach(meetingSlotButton => {
                meetingSlotButton.addEventListener('click', e => {
                    this.handleMeetingSlotClick(e);
                  });
            });
        }
    }
    
    manageNavButtonsDisplay() {
        this.calendarLength = this.meetingsData.length;
        const firstDisplayedDate = this.displayedDays[0].date;
        const lastDisplayedDate = this.displayedDays[this.displayedDays.length - 1].date;
        const isFirstDayDisplayed = this.meetingsData[0].date === firstDisplayedDate;
        const isLastDayDisplayed = this.meetingsData[this.meetingsData.length - 1].date === lastDisplayedDate;
        
        this.prevDaysButton.classList.toggle('hidden', isFirstDayDisplayed);
        this.nextDaysButton.classList.toggle('hidden', isLastDayDisplayed);
    }
    
    handlePrevButtonClick(e) {
        e.preventDefault();
        this.pointerPosition = (this.pointerPosition - this.maxDisplayedDays < 0) ? 0 : this.pointerPosition - this.maxDisplayedDays;
        
        this.buildPlanning();
        this.manageNavButtonsDisplay();
    }
    
    handleNextButtonClick(e) {
        e.preventDefault();
        const pointerPositionMaxValue = this.calendarLength - this.maxDisplayedDays;
        
        this.pointerPosition = (this.pointerPosition + this.maxDisplayedDays > pointerPositionMaxValue) ? pointerPositionMaxValue : this.pointerPosition + this.maxDisplayedDays;
        
        this.buildPlanning();
        this.manageNavButtonsDisplay();
    }
    
    convertTimeToFrenchTimeString(timeString) {
        const [hours, minutes] = timeString.split(':');
        
        return `${hours}h${minutes}`;
    }
    
    convertIsoDateToFrenchDate(isoDate) {
        const months = ['janvier', 'février', 'mars', 'avril', 'mai', 'juin', 'juillet', 'août', 'septembre', 'octobre', 'novembre', 'décembre'];
        const daysOfWeek = ['Dimanche', 'Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi', 'Samedi'];
        const date = new Date(isoDate);
        const dayOfWeek = daysOfWeek[date.getDay()];
        const dayOfMonth = date.getDate();
        const month = months[date.getMonth()];
        
        return `${dayOfWeek} ${dayOfMonth} ${month}`;
    }
}