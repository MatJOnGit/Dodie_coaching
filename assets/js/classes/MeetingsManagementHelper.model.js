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
                let meetingItem = document.createElement('li');
                meetingItem.classList.add('meeting-item');
                let meetingBtn = document.createElement('button');
                meetingBtn.textContent = incomingMeetingData['starting_time'] + ' : ';
                meetingBtn.id = incomingMeetingData['slot_id'];
                meetingBtn.classList.add('btn', 'large-btn', 'meeting-btn');
                
                if (incomingMeetingData['name']) {
                    meetingBtn.textContent += incomingMeetingData['name'];
                }
                else {
                    meetingBtn.textContent += 'disponible';
                }
                meetingItem.appendChild(meetingBtn);
                dailyMeetingsBtnsList.appendChild(meetingItem);
            })

            this.incomingMeetingsTab.appendChild(meetingDateListItem);
            
        });
    }

    addMeetingsButtonsListeners() {

    }
}