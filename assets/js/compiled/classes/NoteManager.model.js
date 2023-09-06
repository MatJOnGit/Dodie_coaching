"use strict";

function _typeof(obj) { "@babel/helpers - typeof"; return _typeof = "function" == typeof Symbol && "symbol" == typeof Symbol.iterator ? function (obj) { return typeof obj; } : function (obj) { return obj && "function" == typeof Symbol && obj.constructor === Symbol && obj !== Symbol.prototype ? "symbol" : typeof obj; }, _typeof(obj); }
function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }
function _defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, _toPropertyKey(descriptor.key), descriptor); } }
function _createClass(Constructor, protoProps, staticProps) { if (protoProps) _defineProperties(Constructor.prototype, protoProps); if (staticProps) _defineProperties(Constructor, staticProps); Object.defineProperty(Constructor, "prototype", { writable: false }); return Constructor; }
function _toPropertyKey(arg) { var key = _toPrimitive(arg, "string"); return _typeof(key) === "symbol" ? key : String(key); }
function _toPrimitive(input, hint) { if (_typeof(input) !== "object" || input === null) return input; var prim = input[Symbol.toPrimitive]; if (prim !== undefined) { var res = prim.call(input, hint || "default"); if (_typeof(res) !== "object") return res; throw new TypeError("@@toPrimitive must return a primitive value."); } return (hint === "string" ? String : Number)(input); }
function _inherits(subClass, superClass) { if (typeof superClass !== "function" && superClass !== null) { throw new TypeError("Super expression must either be null or a function"); } subClass.prototype = Object.create(superClass && superClass.prototype, { constructor: { value: subClass, writable: true, configurable: true } }); Object.defineProperty(subClass, "prototype", { writable: false }); if (superClass) _setPrototypeOf(subClass, superClass); }
function _setPrototypeOf(o, p) { _setPrototypeOf = Object.setPrototypeOf ? Object.setPrototypeOf.bind() : function _setPrototypeOf(o, p) { o.__proto__ = p; return o; }; return _setPrototypeOf(o, p); }
function _createSuper(Derived) { var hasNativeReflectConstruct = _isNativeReflectConstruct(); return function _createSuperInternal() { var Super = _getPrototypeOf(Derived), result; if (hasNativeReflectConstruct) { var NewTarget = _getPrototypeOf(this).constructor; result = Reflect.construct(Super, arguments, NewTarget); } else { result = Super.apply(this, arguments); } return _possibleConstructorReturn(this, result); }; }
function _possibleConstructorReturn(self, call) { if (call && (_typeof(call) === "object" || typeof call === "function")) { return call; } else if (call !== void 0) { throw new TypeError("Derived constructors may only return object or undefined"); } return _assertThisInitialized(self); }
function _assertThisInitialized(self) { if (self === void 0) { throw new ReferenceError("this hasn't been initialised - super() hasn't been called"); } return self; }
function _isNativeReflectConstruct() { if (typeof Reflect === "undefined" || !Reflect.construct) return false; if (Reflect.construct.sham) return false; if (typeof Proxy === "function") return true; try { Boolean.prototype.valueOf.call(Reflect.construct(Boolean, [], function () {})); return true; } catch (e) { return false; } }
function _getPrototypeOf(o) { _getPrototypeOf = Object.setPrototypeOf ? Object.getPrototypeOf.bind() : function _getPrototypeOf(o) { return o.__proto__ || Object.getPrototypeOf(o); }; return _getPrototypeOf(o); }
var NoteManager = /*#__PURE__*/function (_ElementFader) {
  _inherits(NoteManager, _ElementFader);
  var _super = _createSuper(NoteManager);
  function NoteManager() {
    var _this;
    _classCallCheck(this, NoteManager);
    _this = _super.call(this);
    _this._addNoteBtn = document.getElementById('add-note-btn');
    _this._attendedMeetingsSpanElt = document.querySelectorAll('[data-attended-slots]')[0];
    _this._editNoteBtns = document.querySelectorAll('.edit-note-btn');
    _this._profilePanel = document.getElementsByClassName('profile-panel')[0];
    _this._prevPageBtn = document.getElementsByClassName('prev-page-btn')[0];
    _this._subscriberId = _this._prevPageBtn.href.split('id=')[1];
    _this._attendedMeetingsData;
    _this._mappedAttendedMeetings = [];
    _this._addNoteLink = 'index.php?action=reject-appliance&id=';
    _this._timezone = 'Europe/Paris';
    return _this;
  }
  _createClass(NoteManager, [{
    key: "addNoteBtn",
    get: function get() {
      return this._addNoteBtn;
    }
  }, {
    key: "attendedMeetingsSpanElt",
    get: function get() {
      return this._attendedMeetingsSpanElt;
    }
  }, {
    key: "attendedMeetingsData",
    get: function get() {
      return this._attendedMeetingsData;
    }
  }, {
    key: "editNoteBtns",
    get: function get() {
      return this._editNoteBtns;
    }
  }, {
    key: "mappedAttendedMeetings",
    get: function get() {
      return this._mappedAttendedMeetings;
    },
    set: function set(jsonObject) {
      this._mappedAttendedMeetings = jsonObject;
    }
  }, {
    key: "profilePanel",
    get: function get() {
      return this._profilePanel;
    }
  }, {
    key: "subscriberId",
    get: function get() {
      return this._subscriberId;
    }
  }, {
    key: "timezone",
    get: function get() {
      return this._timezone;
    }
  }, {
    key: "addAddNoteBtnListener",
    value: function addAddNoteBtnListener() {
      var _this2 = this;
      this.addNoteBtn.addEventListener('click', function () {
        return _this2.displayAddNoteElements();
      });
    }
  }, {
    key: "addEditBtnsListeners",
    value: function addEditBtnsListeners() {
      var _this3 = this;
      this.editNoteBtns.forEach(function (editNoteBtn) {
        editNoteBtn.addEventListener('click', function (e) {
          return _this3.displayEditNoteElements(e.target);
        });
      });
    }
  }, {
    key: "addSubmitFormBtnListener",
    value: function addSubmitFormBtnListener() {
      var noteForm = document.getElementsByClassName('admin-form')[0];
      var noteMessageTextArea = document.getElementById('note-message');
      noteForm.addEventListener('submit', function (e) {
        if (noteMessageTextArea.value.length === 0) {
          e.preventDefault();
        }
      });
    }
  }, {
    key: "buildAddnoteForm",
    value: function buildAddnoteForm() {
      var addNoteForm = document.createElement('form');
      var addNoteTextarea = document.createElement('textarea');
      var attachedMeetingSelect = document.createElement('select');
      var defaultMeetingOption = document.createElement('option');
      var noteFormActionsBlock = document.createElement('div');
      var cancelNoteBtn = document.createElement('a');
      var saveNoteBtn = document.createElement('input');
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
      noteFormActionsBlock.classList.add('note-form-actions-block');
      cancelNoteBtn.href = 'index.php?page=subscriber-notes&id=' + this.subscriberId;
      cancelNoteBtn.textContent = 'Annuler';
      cancelNoteBtn.classList.add('btn', 'rounded', 'tiny-btn', 'red-bkgd');
      saveNoteBtn.value = 'Enregistrer';
      saveNoteBtn.type = 'submit';
      saveNoteBtn.id = 'save-note-btn';
      saveNoteBtn.classList.add('btn', 'rounded', 'tiny-btn', 'blue-bkgd');
      addNoteForm.appendChild(addNoteTextarea);
      attachedMeetingSelect.appendChild(defaultMeetingOption);
      addNoteForm.appendChild(attachedMeetingSelect);
      noteFormActionsBlock.appendChild(saveNoteBtn);
      noteFormActionsBlock.appendChild(cancelNoteBtn);
      addNoteForm.appendChild(noteFormActionsBlock);
      this.mappedAttendedMeetings.forEach(function (attendedMeeting, index) {
        var attendedMeetingsOption = document.createElement('option');
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
  }, {
    key: "buildEditNoteForm",
    value: function buildEditNoteForm(clickedListItem, noteData) {
      var clickedBtn = clickedListItem.getElementsByClassName('edit-note-btn')[0];
      var editNoteContainer = document.createElement('div');
      var editNoteForm = document.createElement('form');
      var editNoteTitle = document.createElement('h4');
      var editNoteTextarea = document.createElement('textarea');
      var editMeetingDateSelect = document.createElement('select');
      var editMeetingDefaultDateOption = document.createElement('option');
      var cancelEditionBtn = document.createElement('a');
      var confirmEditionBtn = document.createElement('input');
      var deleteNoteBtn = document.createElement('a');
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
      cancelEditionBtn.classList.add('btn', 'rounded', 'tiny-btn', 'blue-bkgd');
      deleteNoteBtn.href = 'index.php?action=delete-note&id=' + this.subscriberId + '&note-id=' + noteData.id;
      deleteNoteBtn.textContent = 'Supprimer';
      deleteNoteBtn.classList.add('btn', 'rounded', 'tiny-btn', 'red-bkgd');
      confirmEditionBtn.value = 'Enregistrer';
      confirmEditionBtn.type = 'submit';
      confirmEditionBtn.classList.add('btn', 'rounded', 'large-btn', 'blue-bkgd', 'save-note-btn');
      editNoteForm.appendChild(editNoteTitle);
      editNoteForm.appendChild(editNoteTextarea);
      editMeetingDateSelect.appendChild(editMeetingDefaultDateOption);
      editNoteForm.appendChild(editMeetingDateSelect);
      if (this.attendedMeetingsData) {
        this.mappedAttendedMeetings.forEach(function (attendedMeeting, index) {
          var attendedMeetingsOption = document.createElement('option');
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
  }, {
    key: "displayAddNoteElements",
    value: function displayAddNoteElements() {
      this.removePreviousNotes();
      this.editPagetitle();
      this.buildAddnoteForm();
      this.addSubmitFormBtnListener();
    }
  }, {
    key: "displayEditNoteElements",
    value: function displayEditNoteElements(clickedElt) {
      var clickedListItem = clickedElt.closest('li');
      var noteData = this.buildNoteData(clickedListItem);
      this.buildEditNoteForm(clickedListItem, noteData);
      this.removeAddNoteBtn();
    }
  }, {
    key: "editPagetitle",
    value: function editPagetitle() {
      var panelTitle = document.getElementsByTagName('h3')[0].innerHTML;
      var ptrn = "Notes de suivi de";
      var replacement = "Ajout de note pour";
      document.getElementsByTagName('h3')[0].innerText = panelTitle.replace(ptrn, replacement);
    }
  }, {
    key: "buildNoteData",
    value: function buildNoteData(clickedListItem) {
      var noteEntryElt = clickedListItem.getElementsByClassName('note-entry')[0];
      var titleElt = clickedListItem.getElementsByTagName('h4')[0];
      var clickedBtn = clickedListItem.getElementsByClassName('edit-note-btn')[0];
      var editNoteBtns = document.querySelectorAll('.edit-note-btn');
      var clickedBtnIndex = Array.from(editNoteBtns).indexOf(clickedBtn);
      var noteData = {
        'id': clickedBtn.getAttribute('data-id'),
        'index': clickedBtnIndex,
        'title': titleElt.textContent,
        'content': noteEntryElt.textContent
      };
      return noteData;
    }
  }, {
    key: "init",
    value: function init() {
      if (this.attendedMeetingsSpanElt) {
        this.setAttendedMeetingsData();
        this.mapAttendedMeetings();
      }
      this.addAddNoteBtnListener();
      this.addEditBtnsListeners();
    }
  }, {
    key: "mapAttendedMeetings",
    value: function mapAttendedMeetings() {
      this.mappedAttendedMeetings = this.attendedMeetingsData.map(function (attendedMeetings) {
        return 'le ' + new Date(attendedMeetings['slot_date']).toLocaleDateString('fr') + ' à ' + new Date(attendedMeetings['slot_date']).toLocaleTimeString('fr').slice(0, 5).replace(':', 'h');
      });
    }
  }, {
    key: "removeAddNoteBtn",
    value: function removeAddNoteBtn() {
      if (document.getElementById('add-note-btn')) {
        this.profilePanel.removeChild(this.addNoteBtn);
      }
    }
  }, {
    key: "removePreviousNotes",
    value: function removePreviousNotes() {
      var notesList = document.getElementsByClassName('notes-list')[0];
      if (notesList) {
        this.profilePanel.removeChild(notesList);
      }
      this.profilePanel.removeChild(this.addNoteBtn);
    }
  }, {
    key: "setAttendedMeetingsData",
    value: function setAttendedMeetingsData() {
      this._attendedMeetingsData = JSON.parse(this._attendedMeetingsSpanElt.getAttribute('data-attended-slots'));
    }
  }]);
  return NoteManager;
}(ElementFader);
