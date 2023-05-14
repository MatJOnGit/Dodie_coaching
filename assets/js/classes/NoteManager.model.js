class NoteManager extends ElementFader {
    constructor() {
        super();
        
        this._addNoteBtn = document.getElementById('add-note-btn');
        this._attendedMeetingsSpanElt = document.querySelectorAll('[data-attended-slots]')[0];
        this._editNoteBtns = document.querySelectorAll('.edit-note-btn');
        this._profilePanel = document.getElementsByClassName('profile-panel')[0];
        this._prevPageBtn = document.getElementsByClassName('prev-page-btn')[0];
        
        this._subscriberId = this._prevPageBtn.href.split('id=')[1];
        
        this._attendedMeetingsData;
        this._mappedAttendedMeetings = [];
        this._addNoteLink = 'index.php?action=reject-appliance&id=';
        this._timezone = 'Europe/Paris';
    }
    
    get addNoteBtn() {
        return this._addNoteBtn;
    }
    
    get attendedMeetingsSpanElt() {
        return this._attendedMeetingsSpanElt;
    }
    
    get attendedMeetingsData() {
        return this._attendedMeetingsData;
    }
    
    get editNoteBtns() {
        return this._editNoteBtns;
    }
    
    get mappedAttendedMeetings() {
        return this._mappedAttendedMeetings;
    }
    
    get profilePanel() {
        return this._profilePanel;
    }
    
    get subscriberId() {
        return this._subscriberId;
    }
    
    get timezone() {
        return this._timezone;
    }
    
    set mappedAttendedMeetings (jsonObject) {
        this._mappedAttendedMeetings = jsonObject;
    }
    
    addAddNoteBtnListener() {
        this.addNoteBtn.addEventListener('click', () => this.displayAddNoteElements());
    }
    
    addEditBtnsListeners() {
        this.editNoteBtns.forEach(editNoteBtn => {
            editNoteBtn.addEventListener('click', (e) => this.displayEditNoteElements(e.target));
        });
    }
    
    addSubmitFormBtnListener() {
        const noteForm = document.getElementsByClassName('admin-form')[0];
        const noteMessageTextArea = document.getElementById('note-message');
        
        noteForm.addEventListener('submit', (e) => {
            if (noteMessageTextArea.value.length === 0) {
                e.preventDefault();
            }
        });
    }
    
    buildAddnoteForm() {
        const addNoteForm = document.createElement('form');
        const addNoteTextarea = document.createElement('textarea');
        const attachedMeetingSelect = document.createElement('select');
        const defaultMeetingOption = document.createElement('option');
        const cancelNoteBtn = document.createElement('a');
        const saveNoteBtn = document.createElement('input');
        
        addNoteForm.classList.add('admin-form');
        addNoteForm.action = 'index.php?action=save-note&id=' + this.subscriberId;
        addNoteForm.method = 'post';
        addNoteForm.style.opacity = 0;
        
        addNoteTextarea.placeholder = 'Note à ajouter';
        addNoteTextarea.name = 'note-message';
        addNoteTextarea.id = 'note-message';
        addNoteTextarea.classList.add('note-textarea');
        addNoteTextarea.required = true;
        
        attachedMeetingSelect.name = 'attached-meeting-date';
        
        defaultMeetingOption.value = '';
        defaultMeetingOption.innerText = 'Associer à un rendez-vous (optionnelle)';
        
        cancelNoteBtn.href = 'index.php?page=subscriber-notes&id=' + this.subscriberId;
        cancelNoteBtn.textContent = 'Annuler';
        cancelNoteBtn.classList.add('btn', 'rounded-btn', 'tiny-btn', 'red-bkgd');
        
        saveNoteBtn.value = 'Enregistrer';
        saveNoteBtn.type = 'submit';
        saveNoteBtn.id = 'save-note-btn';
        saveNoteBtn.classList.add('btn', 'rounded-btn', 'tiny-btn', 'blue-bkgd');
        
        addNoteForm.appendChild(addNoteTextarea);
        attachedMeetingSelect.appendChild(defaultMeetingOption);
        addNoteForm.appendChild(attachedMeetingSelect);
        addNoteForm.appendChild(cancelNoteBtn);
        addNoteForm.appendChild(saveNoteBtn);
        
        this.mappedAttendedMeetings.forEach((attendedMeeting, index) => {
            let attendedMeetingsOption = document.createElement('option');
            attendedMeetingsOption.value = index;
            attendedMeetingsOption.innerText = attendedMeeting;
            
            attachedMeetingSelect.appendChild(attendedMeetingsOption);
        });
        
        this.profilePanel.appendChild(addNoteForm);
        this.fadeInItem(addNoteForm, 4000, 1);
    }
    
    /*********************************************************************************
    Implements elements that enable the admin to add a follow-up note to a subscriber.
    If subscriber has already attended to a meeting, those meeting dates will be an
    option, so the admin can associate the note to a specific attended meeting.
    *********************************************************************************/
    buildEditNoteForm(clickedListItem, noteData) {
        const clickedBtn = clickedListItem.getElementsByClassName('edit-note-btn')[0];
        
        const editNoteContainer = document.createElement('div');
        const editNoteForm = document.createElement('form');
        const editNoteTitle = document.createElement('h4');
        const editNoteTextarea = document.createElement('textarea');
        const editMeetingDateSelect = document.createElement('select');
        const editMeetingDefaultDateOption = document.createElement('option');
        const cancelEditionBtn = document.createElement('a');
        const confirmEditionBtn = document.createElement('input');
        const deleteNoteBtn = document.createElement('a');
        
        editNoteContainer.classList.add('edit-note-btn');
        editNoteContainer.style.opacity = 0;
        
        editNoteTextarea.value = noteData.content.slice(0, -1).slice(1);
        editNoteTextarea.name = 'note-message';
        editNoteTextarea.id = 'note-message';
        editNoteTextarea.classList.add('note-textarea');
        editNoteTextarea.required = true;
        
        editNoteTitle.classList.add('admin-panel-header', 'orange-bkgd');
        editNoteTitle.textContent = noteData.title;
        
        editNoteForm.classList.add('admin-form');
        editNoteForm.action = 'index.php?action=edit-note&id=' + this.subscriberId + '&note-id=' + noteData.id;
        editNoteForm.method = 'post';
        
        editMeetingDateSelect.name = 'attached-meeting-date';
        
        editMeetingDefaultDateOption.value = '';
        editMeetingDefaultDateOption.innerText = 'Associer à un rendez-vous (optionnelle)';
        
        cancelEditionBtn.href = 'index.php?page=subscriber-notes&id=' + this.subscriberId;
        cancelEditionBtn.textContent = 'Annuler';
        cancelEditionBtn.classList.add('btn', 'rounded-btn', 'tiny-btn', 'blue-bkgd');
        
        deleteNoteBtn.href = 'index.php?action=delete-note&id=' + this.subscriberId + '&note-id=' + noteData.id;
        deleteNoteBtn.textContent = 'Supprimer';
        deleteNoteBtn.classList.add('btn', 'rounded-btn', 'tiny-btn', 'red-bkgd');
        
        confirmEditionBtn.value = 'Enregistrer';
        confirmEditionBtn.type = 'submit';
        confirmEditionBtn.classList.add('btn', 'rounded-btn', 'large-btn', 'blue-bkgd', 'save-note-btn');
        
        editNoteForm.appendChild(editNoteTitle);
        editNoteForm.appendChild(editNoteTextarea);
        editMeetingDateSelect.appendChild(editMeetingDefaultDateOption);
        editNoteForm.appendChild(editMeetingDateSelect);
        
        if (this.attendedMeetingsData) {
            this.mappedAttendedMeetings.forEach((attendedMeeting, index) => {
                let attendedMeetingsOption = document.createElement('option');
                attendedMeetingsOption.value = index;
                attendedMeetingsOption.innerText = attendedMeeting;
                
                editMeetingDateSelect.appendChild(attendedMeetingsOption);
            });
        }
        
        editNoteForm.appendChild(cancelEditionBtn);
        editNoteForm.appendChild(deleteNoteBtn);
        editNoteForm.appendChild(confirmEditionBtn);
        editNoteContainer.appendChild(editNoteForm);
        
        clickedListItem.appendChild(editNoteContainer);
        clickedListItem.replaceChild(editNoteContainer, clickedBtn);
        this.fadeInItem(editNoteContainer, 4000, 1);
    }
    
    displayAddNoteElements() {
        this.removePreviousNotes();
        this.editPagetitle();
        this.buildAddnoteForm();
        this.addSubmitFormBtnListener();
    }
    
    displayEditNoteElements(clickedElt) {
        const clickedListItem = clickedElt.closest('li');
        const noteData = this.buildNoteData(clickedListItem);
        
        this.buildEditNoteForm(clickedListItem, noteData);
        this.removeAddNoteBtn();
    }
    
    editPagetitle() {
        const panelTitle = document.getElementsByTagName('h3')[0].innerHTML;
        
        const ptrn = "Notes de suivi de";
        const replacement = "Ajout de note pour";
        
        document.getElementsByTagName('h3')[0].innerText = panelTitle.replace(ptrn, replacement);
    }
    
    buildNoteData(clickedListItem) {
        const noteEntryElt = clickedListItem.getElementsByClassName('note-entry')[0];
        const titleElt = clickedListItem.getElementsByTagName('h4')[0];
        const clickedBtn = clickedListItem.getElementsByClassName('edit-note-btn')[0];
        const editNoteBtns = document.querySelectorAll('.edit-note-btn');
        const clickedBtnIndex = Array.from(editNoteBtns).indexOf(clickedBtn);
        
        let noteData = {
            'id' : clickedBtn.getAttribute('data-id'),
            'index' : clickedBtnIndex,
            'title' : titleElt.textContent,
            'content' : noteEntryElt.textContent
        };
        
        return noteData;
    }
    
    init() {
        if (this.attendedMeetingsSpanElt) {
            this.setAttendedMeetingsData();
            this.mapAttendedMeetings();
        }
        
        this.addAddNoteBtnListener();
        this.addEditBtnsListeners();
    }
    
    mapAttendedMeetings() {
        this.mappedAttendedMeetings = this.attendedMeetingsData.map(attendedMeetings => 'le ' + new Date(attendedMeetings['slot_date']).toLocaleDateString('fr') + ' à ' + new Date(attendedMeetings['slot_date']).toLocaleTimeString('fr').slice(0, 5).replace(':', 'h'));
    }
    
    removeAddNoteBtn() {
        if (document.getElementById('add-note-btn')) {
            this.profilePanel.removeChild(this.addNoteBtn);
        }
    }
    
    removePreviousNotes() {
        const notesList = document.getElementsByClassName('notes-list')[0];
        
        if (notesList) {
            this.profilePanel.removeChild(notesList);
        }
        
        this.profilePanel.removeChild(this.addNoteBtn);
    }
    
    setAttendedMeetingsData() {
        this._attendedMeetingsData = JSON.parse(this._attendedMeetingsSpanElt.getAttribute('data-attended-slots'));
    }
}