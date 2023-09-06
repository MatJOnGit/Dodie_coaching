"use strict";

function _typeof(obj) { "@babel/helpers - typeof"; return _typeof = "function" == typeof Symbol && "symbol" == typeof Symbol.iterator ? function (obj) { return typeof obj; } : function (obj) { return obj && "function" == typeof Symbol && obj.constructor === Symbol && obj !== Symbol.prototype ? "symbol" : typeof obj; }, _typeof(obj); }
function _createForOfIteratorHelper(o, allowArrayLike) { var it = typeof Symbol !== "undefined" && o[Symbol.iterator] || o["@@iterator"]; if (!it) { if (Array.isArray(o) || (it = _unsupportedIterableToArray(o)) || allowArrayLike && o && typeof o.length === "number") { if (it) o = it; var i = 0; var F = function F() {}; return { s: F, n: function n() { if (i >= o.length) return { done: true }; return { done: false, value: o[i++] }; }, e: function e(_e2) { throw _e2; }, f: F }; } throw new TypeError("Invalid attempt to iterate non-iterable instance.\nIn order to be iterable, non-array objects must have a [Symbol.iterator]() method."); } var normalCompletion = true, didErr = false, err; return { s: function s() { it = it.call(o); }, n: function n() { var step = it.next(); normalCompletion = step.done; return step; }, e: function e(_e3) { didErr = true; err = _e3; }, f: function f() { try { if (!normalCompletion && it["return"] != null) it["return"](); } finally { if (didErr) throw err; } } }; }
function _slicedToArray(arr, i) { return _arrayWithHoles(arr) || _iterableToArrayLimit(arr, i) || _unsupportedIterableToArray(arr, i) || _nonIterableRest(); }
function _nonIterableRest() { throw new TypeError("Invalid attempt to destructure non-iterable instance.\nIn order to be iterable, non-array objects must have a [Symbol.iterator]() method."); }
function _unsupportedIterableToArray(o, minLen) { if (!o) return; if (typeof o === "string") return _arrayLikeToArray(o, minLen); var n = Object.prototype.toString.call(o).slice(8, -1); if (n === "Object" && o.constructor) n = o.constructor.name; if (n === "Map" || n === "Set") return Array.from(o); if (n === "Arguments" || /^(?:Ui|I)nt(?:8|16|32)(?:Clamped)?Array$/.test(n)) return _arrayLikeToArray(o, minLen); }
function _arrayLikeToArray(arr, len) { if (len == null || len > arr.length) len = arr.length; for (var i = 0, arr2 = new Array(len); i < len; i++) arr2[i] = arr[i]; return arr2; }
function _iterableToArrayLimit(arr, i) { var _i = null == arr ? null : "undefined" != typeof Symbol && arr[Symbol.iterator] || arr["@@iterator"]; if (null != _i) { var _s, _e, _x, _r, _arr = [], _n = !0, _d = !1; try { if (_x = (_i = _i.call(arr)).next, 0 === i) { if (Object(_i) !== _i) return; _n = !1; } else for (; !(_n = (_s = _x.call(_i)).done) && (_arr.push(_s.value), _arr.length !== i); _n = !0); } catch (err) { _d = !0, _e = err; } finally { try { if (!_n && null != _i["return"] && (_r = _i["return"](), Object(_r) !== _r)) return; } finally { if (_d) throw _e; } } return _arr; } }
function _arrayWithHoles(arr) { if (Array.isArray(arr)) return arr; }
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
var MeetingBooker = /*#__PURE__*/function (_ElementFader) {
  _inherits(MeetingBooker, _ElementFader);
  var _super = _createSuper(MeetingBooker);
  function MeetingBooker() {
    var _this;
    _classCallCheck(this, MeetingBooker);
    _this = _super.call(this);
    _this._meetingsDayList = document.getElementById('meetings-day-list');
    _this._prevDaysButton = document.getElementById('previous-days-btn');
    _this._nextDaysButton = document.getElementById('next-days-btn');
    _this._cancelAppointmentButton = document.getElementById('cancel-appointment-btn');
    _this._meetingDays = [];
    _this._pointerPosition = 0;
    _this._maxDisplayedDaysPerDevice = {
      mobile: 2,
      tablet: 4,
      desktop: 5
    };
    if (_this.meetingsDayList) {
      _this.convertMeetingsDataToArray(JSON.parse(_this.meetingsDayList.dataset.meetings));
      _this.setMaxDisplayedDays();
      _this.buildPlanning();
    }
    _this.addEventsListener();
    return _this;
  }
  _createClass(MeetingBooker, [{
    key: "cancelAppointmentButton",
    get: function get() {
      return this._cancelAppointmentButton;
    }
  }, {
    key: "prevDaysButton",
    get: function get() {
      return this._prevDaysButton;
    }
  }, {
    key: "nextDaysButton",
    get: function get() {
      return this._nextDaysButton;
    }
  }, {
    key: "meetingsDayList",
    get: function get() {
      return this._meetingsDayList;
    }
  }, {
    key: "maxDisplayedDaysPerDevice",
    get: function get() {
      return this._maxDisplayedDaysPerDevice;
    }
  }, {
    key: "meetingsData",
    get: function get() {
      return this._meetingsData;
    },
    set: function set(data) {
      this._meetingsData = data;
    }
  }, {
    key: "meetingDays",
    get: function get() {
      return this._meetingDays;
    }
  }, {
    key: "maxDisplayedDays",
    get: function get() {
      return this._maxDisplayedDays;
    },
    set: function set(maxDisplayedDays) {
      this._maxDisplayedDays = maxDisplayedDays;
    }
  }, {
    key: "displayedDays",
    get: function get() {
      return this._displayedDays;
    },
    set: function set(displayedDaysObject) {
      this._displayedDays = displayedDaysObject;
    }
  }, {
    key: "pointerPosition",
    get: function get() {
      return this._pointerPosition;
    },
    set: function set(newPosition) {
      this._pointerPosition = newPosition;
    }
  }, {
    key: "calendarLength",
    get: function get() {
      return this._calendarLength;
    },
    set: function set(length) {
      this._calendarLength = length;
    }
  }, {
    key: "addEventsListener",
    value: function addEventsListener() {
      var _this2 = this;
      var buttons = [{
        element: this.cancelAppointmentButton,
        handler: function handler() {
          return _this2.displayCancelMeetingConfirmation();
        }
      }, {
        element: this.prevDaysButton,
        handler: function handler(e) {
          return _this2.handlePrevButtonClick(e);
        }
      }, {
        element: this.nextDaysButton,
        handler: function handler(e) {
          return _this2.handleNextButtonClick(e);
        }
      }];
      var addClickListener = function addClickListener(button, handler) {
        button.addEventListener('click', handler);
      };
      buttons.forEach(function (_ref) {
        var element = _ref.element,
          handler = _ref.handler;
        if (element) {
          addClickListener(element, handler);
        }
      });
    }
  }, {
    key: "displayCancelMeetingConfirmation",
    value: function displayCancelMeetingConfirmation() {
      var cancelMeetingButtonContainer = document.getElementById('cancel-appointment-btn-container');
      var cancelMeetingCancelationButton = document.createElement('a');
      cancelMeetingCancelationButton.href = 'index.php?page=meetings-booking';
      cancelMeetingCancelationButton.classList = 'btn small-circle-btn purple-bkgd';
      cancelMeetingCancelationButton.setAttribute('aria-label', "Annuler l'annulation de rendez-vous");
      cancelMeetingCancelationButton.textContent = 'Non';
      var meetingConcelationMessage = document.createElement('div');
      meetingConcelationMessage.classList = 'cancelation-alert';
      meetingConcelationMessage.innerHTML = '<p>Etes-vous sûr de vouloir supprimer ce rendez-vous ?</p>';
      var confirmMeetingCancelationLink = document.createElement('a');
      confirmMeetingCancelationLink.href = 'index.php?action=cancel-appointment';
      confirmMeetingCancelationLink.classList = 'btn small-circle-btn red-bkgd';
      confirmMeetingCancelationLink.setAttribute('aria-label', "Confirmer l'annulation de rendez-vous");
      confirmMeetingCancelationLink.textContent = 'Oui';
      cancelMeetingButtonContainer.innerHTML = '';
      cancelMeetingButtonContainer.style.opacity = 0;
      cancelMeetingButtonContainer.appendChild(cancelMeetingCancelationButton);
      cancelMeetingButtonContainer.appendChild(meetingConcelationMessage);
      cancelMeetingButtonContainer.appendChild(confirmMeetingCancelationLink);
      this.fadeInItem(cancelMeetingButtonContainer, 4000, 1);
    }
  }, {
    key: "handleMeetingSlotClick",
    value: function handleMeetingSlotClick(e) {
      var nextMeetingDateInput = document.getElementById('user-next-meeting');
      var bookMeetingButton = document.getElementById('book-meeting-btn');
      var meetingSlotDate = e.target.closest('.daily-schedule').querySelector('h4').textContent;
      var meetingSlotTime = e.target.textContent;
      var slotFormatedDate = meetingSlotDate.substring(meetingSlotDate.indexOf(' ') + 1);
      nextMeetingDateInput.value = "le ".concat(slotFormatedDate, " \xE0 ").concat(meetingSlotTime);
      bookMeetingButton.removeAttribute('disabled');
      nextMeetingDateInput.removeAttribute('disabled');
    }
  }, {
    key: "convertMeetingsDataToArray",
    value: function convertMeetingsDataToArray(meetingsDataObj) {
      var _this3 = this;
      this.meetingsData = Object.entries(meetingsDataObj).map(function (_ref2) {
        var _ref3 = _slicedToArray(_ref2, 2),
          date = _ref3[0],
          meetingSlots = _ref3[1];
        return {
          date: _this3.convertIsoDateToFrenchDate(date),
          meetingSlots: meetingSlots
        };
      });
    }
  }, {
    key: "setMaxDisplayedDays",
    value: function setMaxDisplayedDays() {
      var windowScreenSize = window.innerWidth;
      if (windowScreenSize < 768) {
        this.maxDisplayedDays = this.maxDisplayedDaysPerDevice.mobile;
      } else if (windowScreenSize < 1024) {
        this.maxDisplayedDays = this.maxDisplayedDaysPerDevice.tablet;
      } else {
        this.maxDisplayedDays = this.maxDisplayedDaysPerDevice.desktop;
      }
    }
  }, {
    key: "buildPlanning",
    value: function buildPlanning() {
      this.displayedDays = this.meetingsData.slice(this.pointerPosition, this.pointerPosition + this.maxDisplayedDays);
      this.meetingsDayList.innerHTML = '';
      var _iterator = _createForOfIteratorHelper(this.displayedDays),
        _step;
      try {
        for (_iterator.s(); !(_step = _iterator.n()).done;) {
          var _step$value = _step.value,
            date = _step$value.date,
            meetingSlots = _step$value.meetingSlots;
          var dateTitle = document.createElement('h4');
          dateTitle.textContent = date;
          dateTitle.classList.add('meeting-day');
          var dailySchedule = document.createElement('li');
          dailySchedule.classList.add('daily-schedule');
          var dailySlots = document.createElement('ul');
          dailySlots.classList.add('daily-slots');
          var _iterator2 = _createForOfIteratorHelper(meetingSlots),
            _step2;
          try {
            for (_iterator2.s(); !(_step2 = _iterator2.n()).done;) {
              var slot = _step2.value;
              var meetingItem = document.createElement('li');
              meetingItem.classList.add('rounded');
              var meetingButton = document.createElement('button');
              meetingButton.classList.add('purple-bkgd', 'rounded', 'meeting-slot-btn');
              meetingButton.setAttribute('aria-label', 'Sélectionner le créneau horaire');
              meetingButton.textContent = this.convertTimeToFrenchTimeString(slot);
              meetingItem.appendChild(meetingButton);
              dailySlots.appendChild(meetingItem);
            }
          } catch (err) {
            _iterator2.e(err);
          } finally {
            _iterator2.f();
          }
          dailySchedule.appendChild(dateTitle);
          dailySchedule.appendChild(dailySlots);
          this.meetingsDayList.appendChild(dailySchedule);
        }
      } catch (err) {
        _iterator.e(err);
      } finally {
        _iterator.f();
      }
      this.addMeetingSlotsEventListener();
      this.manageNavButtonsDisplay();
    }
  }, {
    key: "addMeetingSlotsEventListener",
    value: function addMeetingSlotsEventListener() {
      var _this4 = this;
      if (!this.cancelAppointmentButton) {
        var meetingSlotButtons = this.meetingsDayList.querySelectorAll('.meeting-slot-btn');
        meetingSlotButtons.forEach(function (meetingSlotButton) {
          meetingSlotButton.addEventListener('click', function (e) {
            _this4.handleMeetingSlotClick(e);
          });
        });
      }
    }
  }, {
    key: "manageNavButtonsDisplay",
    value: function manageNavButtonsDisplay() {
      this.calendarLength = this.meetingsData.length;
      var firstDisplayedDate = this.displayedDays[0].date;
      var lastDisplayedDate = this.displayedDays[this.displayedDays.length - 1].date;
      var isFirstDayDisplayed = this.meetingsData[0].date === firstDisplayedDate;
      var isLastDayDisplayed = this.meetingsData[this.meetingsData.length - 1].date === lastDisplayedDate;
      this.prevDaysButton.classList.toggle('hidden', isFirstDayDisplayed);
      this.nextDaysButton.classList.toggle('hidden', isLastDayDisplayed);
    }
  }, {
    key: "handlePrevButtonClick",
    value: function handlePrevButtonClick(e) {
      e.preventDefault();
      this.pointerPosition = this.pointerPosition - this.maxDisplayedDays < 0 ? 0 : this.pointerPosition - this.maxDisplayedDays;
      this.buildPlanning();
      this.manageNavButtonsDisplay();
    }
  }, {
    key: "handleNextButtonClick",
    value: function handleNextButtonClick(e) {
      e.preventDefault();
      var pointerPositionMaxValue = this.calendarLength - this.maxDisplayedDays;
      this.pointerPosition = this.pointerPosition + this.maxDisplayedDays > pointerPositionMaxValue ? pointerPositionMaxValue : this.pointerPosition + this.maxDisplayedDays;
      this.buildPlanning();
      this.manageNavButtonsDisplay();
    }
  }, {
    key: "convertTimeToFrenchTimeString",
    value: function convertTimeToFrenchTimeString(timeString) {
      var _timeString$split = timeString.split(':'),
        _timeString$split2 = _slicedToArray(_timeString$split, 2),
        hours = _timeString$split2[0],
        minutes = _timeString$split2[1];
      return "".concat(hours, "h").concat(minutes);
    }
  }, {
    key: "convertIsoDateToFrenchDate",
    value: function convertIsoDateToFrenchDate(isoDate) {
      var months = ['janvier', 'février', 'mars', 'avril', 'mai', 'juin', 'juillet', 'août', 'septembre', 'octobre', 'novembre', 'décembre'];
      var daysOfWeek = ['Dimanche', 'Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi', 'Samedi'];
      var date = new Date(isoDate);
      var dayOfWeek = daysOfWeek[date.getDay()];
      var dayOfMonth = date.getDate();
      var month = months[date.getMonth()];
      return "".concat(dayOfWeek, " ").concat(dayOfMonth, " ").concat(month);
    }
  }]);
  return MeetingBooker;
}(ElementFader);
