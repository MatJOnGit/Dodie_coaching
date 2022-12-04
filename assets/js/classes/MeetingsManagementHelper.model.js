class MeetingsManagementHelper extends UserPanels {
    constructor() {
        super();

        this._incomingMeetingsTab = document.getElementById('meeting-slots');

        this._parsedMeetingsSlots;
    }

    get incomingMeetingsTab() {
        return this._incomingMeetingsTab;
    }

    get parsedMeetingsSlots() {
        return this._parsedMeetingsSlots;
    }

    set parsedMeetingsSlots (jsonObject) {
        this._parsedMeetingsSlots = jsonObject;
    }

    init() {
        if (this.incomingMeetingsTab) {
            this.buildParsedMeetingsData();
            this.buildMeetingsList();
            this.addMeetingsButtonsListeners();
        }
    }

    buildParsedMeetingsData() {
        let incomingMeetingsData = document.getElementById('meeting-slots').attributes['data-meeting-slots'].textContent;
        this.parsedIncomingMeetings = Object.entries(JSON.parse(incomingMeetingsData));
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

    addMeetingsButtonsListeners() {
        let editMeetingBtns = document.querySelectorAll('.edit-meeting');
        editMeetingBtns.forEach(editMeetingBtn => {
            editMeetingBtn.addEventListener('click', (e) => {
                this.triggerEditMeetingBtns(e.target);
            })
        });
    }

    triggerEditMeetingBtns(clickedElt) {
        let clickedEltLinks = clickedElt.closest('li').querySelectorAll('a');

        if (clickedElt.classList.contains('selected')) {
            clickedElt.classList.remove('selected');
            clickedEltLinks.forEach(linkItem => {
                linkItem.classList.remove('tiny-btn');
                linkItem.classList.add('hidden');
            });
        }
        else {
            clickedElt.classList.add('selected');
            clickedEltLinks.forEach(linkItem => {
                linkItem.classList.remove('hidden');
                linkItem.style.opacity = 0;
                linkItem.classList.add('tiny-btn');
                linkItem.onload = this.fadeInItem(linkItem, 4000);
            });
        }
    }
}