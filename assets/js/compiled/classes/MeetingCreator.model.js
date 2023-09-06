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
var MeetingCreator = /*#__PURE__*/function (_ElementFader) {
  _inherits(MeetingCreator, _ElementFader);
  var _super = _createSuper(MeetingCreator);
  function MeetingCreator() {
    var _this;
    _classCallCheck(this, MeetingCreator);
    _this = _super.call(this);
    _this._addMeetingBtn = document.getElementById('add-meeting-btn');
    _this._incomingMeetingsTab = document.getElementById('meeting-slots');
    _this._meetingPanel = document.getElementsByClassName('admin-panel')[0];
    _this._meetingsSafetyMargin = 60;
    _this._parsedIncomingMeetings;
    return _this;
  }
  _createClass(MeetingCreator, [{
    key: "addMeetingBtn",
    get: function get() {
      return this._addMeetingBtn;
    }
  }, {
    key: "incomingMeetingsTab",
    get: function get() {
      return this._incomingMeetingsTab;
    }
  }, {
    key: "meetingPanel",
    get: function get() {
      return this._meetingPanel;
    }
  }, {
    key: "meetingSafetyMargin",
    get: function get() {
      return this._meetingsSafetyMargin;
    }
  }, {
    key: "parsedIncomingMeetings",
    get: function get() {
      return this._parsedIncomingMeetings;
    },
    set: function set(jsonObject) {
      this._parsedIncomingMeetings = jsonObject;
    }
  }, {
    key: "addAddMeetingButtonListener",
    value: function addAddMeetingButtonListener() {
      var _this2 = this;
      this.addMeetingBtn.addEventListener('click', function () {
        _this2.displayAddMeetingElements();
        _this2.triggerMeetingAdditionButtons();
        if (_this2.incomingMeetingsTab) {
          _this2.addMeetingDayListener();
        }
        _this2.addMeetingSlotSubmitTest();
      });
    }
  }, {
    key: "addMeetingButtonsListeners",
    value: function addMeetingButtonsListeners() {
      var _this3 = this;
      var editMeetingBtns = document.querySelectorAll('.edit-meeting');
      editMeetingBtns.forEach(function (editMeetingBtn) {
        editMeetingBtn.addEventListener('click', function (e) {
          _this3.triggerEditMeetingBtns(e.target);
        });
      });
    }
  }, {
    key: "addMeetingDayListener",
    value: function addMeetingDayListener() {
      var _this4 = this;
      var meetingDayInput = document.getElementById('meeting-day');
      meetingDayInput.addEventListener('change', function (e) {
        var dayEntry = new Date(e.target.value);
        var formatedDayEntry = dayEntry.toLocaleDateString('fr');
        var enteredDateIndex = -1;
        _this4.parsedIncomingMeetings.forEach(function (incomingMeetingData, index) {
          if (incomingMeetingData[0] === formatedDayEntry) {
            enteredDateIndex = index;
          }
        });
        if (enteredDateIndex >= 0) {
          _this4.removePreviousDates();
          _this4.displayDailyMeetingList(enteredDateIndex);
        } else {
          _this4.removePreviousDates();
        }
      });
    }
  }, {
    key: "addMeetingSlotSubmitTest",
    value: function addMeetingSlotSubmitTest() {
      var _this5 = this;
      var meetingForm = document.getElementsByClassName('meeting-form')[0];
      meetingForm.addEventListener('submit', function (e) {
        var dateInput = document.getElementById('meeting-day');
        var timeInput = document.getElementById('meeting-time');
        var dayValue = new Date(dateInput.value);
        var formatedDayValue = dayValue.toLocaleDateString('fr');
        var timeValue = timeInput.value;
        var isMeetingTimeAllowed = true;
        _this5.parsedIncomingMeetings.forEach(function (incomingMeetingData, index) {
          if (incomingMeetingData[0] === formatedDayValue) {
            incomingMeetingData[1].forEach(function (meetingItem) {
              if (!_this5.verifyMeetingTime(meetingItem['starting_time'], timeValue)) {
                isMeetingTimeAllowed = false;
              }
            });
          }
        });
        if (!isMeetingTimeAllowed) {
          e.preventDefault();
          _this5.displayNotAllowedMeetingAlert();
          _this5.fadeawayAlert();
        }
      });
    }
  }, {
    key: "buildAddMeetingForm",
    value: function buildAddMeetingForm() {
      var addMeetingForm = document.createElement('form');
      var dateContainer = document.createElement('div');
      var meetingDayLabel = document.createElement('label');
      var meetingDayInput = document.createElement('input');
      var meetingTimeLabel = document.createElement('label');
      var meetingTimeInput = document.createElement('input');
      var cancelBtn = document.createElement('a');
      var saveMeetingBtn = document.createElement('input');
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
      cancelBtn.classList.add('btn', 'rounded', 'large-btn', 'red-bkgd');
      cancelBtn.id = 'cancel-btn';
      cancelBtn.href = 'index.php?page=meetings-management';
      cancelBtn.textContent = 'Annuler';
      saveMeetingBtn.value = 'Enregistrer';
      saveMeetingBtn.id = 'submit-meeting-btn';
      saveMeetingBtn.type = 'submit';
      saveMeetingBtn.classList.add('btn', 'rounded', 'tiny-btn', 'blue-bkgd');
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
  }, {
    key: "buildMeetingsList",
    value: function buildMeetingsList() {
      var _this6 = this;
      this.parsedIncomingMeetings.forEach(function (incomingMeetingData) {
        var meetingDateListItem = document.createElement('li');
        var meetingDateTitle = document.createElement('h4');
        var dailyMeetingsBtnsList = document.createElement('ul');
        meetingDateListItem.classList.add('daily-data');
        meetingDateTitle.textContent = incomingMeetingData[0];
        meetingDateTitle.classList.add('admin-panel-header', 'orange-bkgd');
        dailyMeetingsBtnsList.classList.add('daily-meetings-list');
        meetingDateListItem.appendChild(meetingDateTitle);
        meetingDateListItem.appendChild(dailyMeetingsBtnsList);
        incomingMeetingData[1].forEach(function (incomingMeetingData) {
          var bookedMeeting = incomingMeetingData['name'] ? true : false;
          var meetingItem = document.createElement('li');
          var meetingBtn = document.createElement('button');
          var cancelEditionBtn = document.createElement('a');
          var deleteMeetingBtn = document.createElement('a');
          meetingItem.classList.add('meeting-item');
          meetingBtn.id = incomingMeetingData['slot_id'];
          meetingBtn.classList.add('btn', 'large-btn', 'edit-meeting', 'blue-bkgd');
          meetingBtn.textContent = incomingMeetingData['starting_time'] + ' : ';
          meetingBtn.textContent += bookedMeeting ? incomingMeetingData['name'] : 'disponible';
          cancelEditionBtn.classList.add('btn', 'rounded', 'blue-bkgd', 'hidden');
          cancelEditionBtn.textContent = 'Annuler';
          cancelEditionBtn.href = 'index.php?page=meetings-management';
          deleteMeetingBtn.classList.add('btn', 'rounded', 'red-bkgd', 'hidden');
          deleteMeetingBtn.textContent = bookedMeeting ? 'Supprimer le rendez-vous' : 'Supprimer le créneau';
          deleteMeetingBtn.href = 'index.php?action=delete-meeting&id=' + incomingMeetingData['slot_id'];
          meetingItem.appendChild(meetingBtn);
          meetingItem.appendChild(cancelEditionBtn);
          meetingItem.appendChild(deleteMeetingBtn);
          dailyMeetingsBtnsList.appendChild(meetingItem);
        });
        _this6.incomingMeetingsTab.appendChild(meetingDateListItem);
      });
    }
  }, {
    key: "buildParsedMeetingsData",
    value: function buildParsedMeetingsData() {
      var incomingMeetingsData = this.incomingMeetingsTab.attributes['data-meeting-slots'].textContent;
      this.parsedIncomingMeetings = Object.entries(JSON.parse(incomingMeetingsData));
    }
  }, {
    key: "displayAddMeetingElements",
    value: function displayAddMeetingElements() {
      if (this.incomingMeetingsTab) {
        this.removeMeetingsTab();
      }
      this.editPageTitle();
      this.buildAddMeetingForm();
    }
  }, {
    key: "displayDailyMeetingList",
    value: function displayDailyMeetingList(enteredDateIndex) {
      var dailyMeetingsTitle = document.createElement('h3');
      var dailyMeetingsList = document.createElement('ul');
      var dailyMeetings = this.parsedIncomingMeetings[enteredDateIndex][1];
      dailyMeetingsTitle.id = 'daily-meetings-title';
      dailyMeetingsTitle.textContent = 'Rendez-vous déjà enregistrés ce jour';
      dailyMeetingsList.id = 'daily-meetings-list';
      dailyMeetings.forEach(function (meeting) {
        var dailyMeetingItem = document.createElement('li');
        dailyMeetingItem.textContent = meeting['starting_time'];
        if (meeting['name']) {
          dailyMeetingItem.textContent += " (r\xE9serv\xE9 par ".concat(meeting['name'], ")");
        }
        dailyMeetingsList.appendChild(dailyMeetingItem);
      });
      this.meetingPanel.appendChild(dailyMeetingsTitle);
      this.meetingPanel.appendChild(dailyMeetingsList);
    }
  }, {
    key: "displayNotAllowedMeetingAlert",
    value: function displayNotAllowedMeetingAlert() {
      var alertBox = document.createElement('div');
      var alertMessage = document.createElement('p');
      alertBox.classList.add('alert-box');
      alertMessage.textContent = "Vous ne pouvez pas créer ce créneau horaire à cause d'un conflit avec un autre rendez-vous ce jour-ci";
      alertBox.appendChild(alertMessage);
      this.meetingPanel.appendChild(alertBox);
    }
  }, {
    key: "editPageTitle",
    value: function editPageTitle() {
      var panelTitle = this.meetingPanel.getElementsByTagName('h3')[0];
      var ptrn = "Vos prochains";
      var addMeetingTitle = "Ajouter un créneau de";
      panelTitle.textContent = panelTitle.textContent.replace(ptrn, addMeetingTitle);
    }
  }, {
    key: "fadeawayAlert",
    value: function fadeawayAlert() {
      var _this7 = this;
      var alertBox = document.getElementsByClassName('alert-box')[0];
      setTimeout(function () {
        _this7.fadeOutItem(alertBox, 4000);
      }, 10000);
      setTimeout(function () {
        _this7.meetingPanel.removeChild(alertBox);
      }, 14000);
    }
  }, {
    key: "init",
    value: function init() {
      if (this.incomingMeetingsTab) {
        this.buildParsedMeetingsData();
        this.buildMeetingsList();
        this.addMeetingButtonsListeners();
      }
      this.addAddMeetingButtonListener();
    }
  }, {
    key: "removeMeetingsTab",
    value: function removeMeetingsTab() {
      var meetingSlotsList = document.getElementById('meeting-slots');
      this.meetingPanel.removeChild(meetingSlotsList);
      this.meetingPanel.removeChild(this.addMeetingBtn);
    }
  }, {
    key: "removePreviousDates",
    value: function removePreviousDates() {
      var dailyMeetingTitle = document.getElementById('daily-meetings-title');
      var dailyMeetingsList = document.getElementById('daily-meetings-list');
      if (dailyMeetingTitle) {
        this.meetingPanel.removeChild(dailyMeetingTitle);
      }
      if (dailyMeetingsList) {
        this.meetingPanel.removeChild(dailyMeetingsList);
      }
    }
  }, {
    key: "triggerEditMeetingBtns",
    value: function triggerEditMeetingBtns(clickedElt) {
      var _this8 = this;
      var clickedEltLinks = clickedElt.closest('li').querySelectorAll('a');
      if (clickedElt.classList.contains('selected')) {
        clickedElt.classList.remove('selected');
        clickedEltLinks.forEach(function (linkItem) {
          linkItem.classList.replace('tiny-btn', 'hidden');
        });
      } else {
        clickedElt.classList.add('selected');
        clickedEltLinks.forEach(function (linkItem) {
          linkItem.classList.remove('hidden');
          linkItem.style.opacity = 0;
          linkItem.classList.add('tiny-btn');
          _this8.fadeInItem(linkItem, 4000, 1);
        });
      }
    }
  }, {
    key: "triggerMeetingAdditionButtons",
    value: function triggerMeetingAdditionButtons() {
      var dateContainer = document.getElementsByClassName('date-container')[0];
      var dateInputElts = dateContainer.querySelectorAll('input');
      var dayInput = document.getElementById('meeting-day');
      var timeInput = document.getElementById('meeting-time');
      var cancelBtn = document.getElementById('cancel-btn');
      var saveMeetingBtn = document.getElementById('submit-meeting-btn');
      dateInputElts.forEach(function (dateInput) {
        dateInput.addEventListener('change', function () {
          if (dayInput.value != '' && timeInput.value != '') {
            if (cancelBtn.classList.contains('large-btn')) {
              cancelBtn.classList.replace('large-btn', 'tiny-btn');
            }
            saveMeetingBtn.style.display = 'flex';
          } else {
            if (cancelBtn.classList.contains('tiny-btn')) {
              cancelBtn.classList.replace('tiny-btn', 'large-btn');
            }
            saveMeetingBtn.style.display = 'none';
          }
        });
      });
    }
  }, {
    key: "verifyMeetingTime",
    value: function verifyMeetingTime(savedMeetingTime, submittedMeetingTime) {
      savedMeetingTime = savedMeetingTime.replace('h', ':');
      var isMeetingTimeAllowed;
      var savedMeetingTimeHours = +savedMeetingTime.split(':')[0];
      var savedMeetingTimeMinutes = +savedMeetingTime.split(':')[1];
      var savedMeetingTimestamp = savedMeetingTimeHours * 60 + savedMeetingTimeMinutes;
      var submittedMeetingTimeHours = +submittedMeetingTime.split(':')[0];
      var submittedMeetingTimeMinutes = +submittedMeetingTime.split(':')[1];
      var submittedMeetingTimestamp = submittedMeetingTimeHours * 60 + submittedMeetingTimeMinutes;
      var safetyTimestampMin = savedMeetingTimestamp - this.meetingSafetyMargin;
      var safetyTimestampMax = savedMeetingTimestamp + this.meetingSafetyMargin;
      isMeetingTimeAllowed = true;
      if (submittedMeetingTimestamp > safetyTimestampMin && submittedMeetingTimestamp < safetyTimestampMax) {
        isMeetingTimeAllowed = false;
      }
      return isMeetingTimeAllowed;
    }
  }]);
  return MeetingCreator;
}(ElementFader);
