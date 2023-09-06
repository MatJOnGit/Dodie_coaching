"use strict";

function _typeof(obj) { "@babel/helpers - typeof"; return _typeof = "function" == typeof Symbol && "symbol" == typeof Symbol.iterator ? function (obj) { return typeof obj; } : function (obj) { return obj && "function" == typeof Symbol && obj.constructor === Symbol && obj !== Symbol.prototype ? "symbol" : typeof obj; }, _typeof(obj); }
function _createForOfIteratorHelper(o, allowArrayLike) { var it = typeof Symbol !== "undefined" && o[Symbol.iterator] || o["@@iterator"]; if (!it) { if (Array.isArray(o) || (it = _unsupportedIterableToArray(o)) || allowArrayLike && o && typeof o.length === "number") { if (it) o = it; var i = 0; var F = function F() {}; return { s: F, n: function n() { if (i >= o.length) return { done: true }; return { done: false, value: o[i++] }; }, e: function e(_e) { throw _e; }, f: F }; } throw new TypeError("Invalid attempt to iterate non-iterable instance.\nIn order to be iterable, non-array objects must have a [Symbol.iterator]() method."); } var normalCompletion = true, didErr = false, err; return { s: function s() { it = it.call(o); }, n: function n() { var step = it.next(); normalCompletion = step.done; return step; }, e: function e(_e2) { didErr = true; err = _e2; }, f: function f() { try { if (!normalCompletion && it["return"] != null) it["return"](); } finally { if (didErr) throw err; } } }; }
function _unsupportedIterableToArray(o, minLen) { if (!o) return; if (typeof o === "string") return _arrayLikeToArray(o, minLen); var n = Object.prototype.toString.call(o).slice(8, -1); if (n === "Object" && o.constructor) n = o.constructor.name; if (n === "Map" || n === "Set") return Array.from(o); if (n === "Arguments" || /^(?:Ui|I)nt(?:8|16|32)(?:Clamped)?Array$/.test(n)) return _arrayLikeToArray(o, minLen); }
function _arrayLikeToArray(arr, len) { if (len == null || len > arr.length) len = arr.length; for (var i = 0, arr2 = new Array(len); i < len; i++) arr2[i] = arr[i]; return arr2; }
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
var ProgressManager = /*#__PURE__*/function (_ElementFader) {
  _inherits(ProgressManager, _ElementFader);
  var _super = _createSuper(ProgressManager);
  function ProgressManager() {
    var _this;
    _classCallCheck(this, ProgressManager);
    _this = _super.call(this);
    _this._weightRegex = /^(?!0*[.,]0*$|[.,]0*$|0*$)\d+[,.]?\d{0,3}$/;
    _this._dateTypeSelect = document.getElementById('date-selector');
    _this._progressFormInputs = document.getElementsByClassName('progress-form-inputs')[0];
    _this._submitButton = document.getElementById('submit-btn');
    _this._weightInput = document.getElementById('user-weight');
    return _this;
  }
  _createClass(ProgressManager, [{
    key: "dateTypeSelect",
    get: function get() {
      return this._dateTypeSelect;
    }
  }, {
    key: "progressFormInputs",
    get: function get() {
      return this._progressFormInputs;
    }
  }, {
    key: "selectedOptionValue",
    get: function get() {
      return this.dateTypeSelect.options[this.dateTypeSelect.selectedIndex].value;
    }
  }, {
    key: "submitButton",
    get: function get() {
      return this._submitButton;
    }
  }, {
    key: "weightInput",
    get: function get() {
      return this._weightInput;
    }
  }, {
    key: "weightRegex",
    get: function get() {
      return this._weightRegex;
    }
  }, {
    key: "addDeleteReportListeners",
    value: function addDeleteReportListeners() {
      var _this2 = this;
      var deleteReportButtons = document.querySelectorAll('.progress-item button');
      var _iterator = _createForOfIteratorHelper(deleteReportButtons),
        _step;
      try {
        var _loop = function _loop() {
          var deleteReportButton = _step.value;
          deleteReportButton.addEventListener('click', function () {
            _this2.displayDeleteReportConfirmation(deleteReportButton);
          });
        };
        for (_iterator.s(); !(_step = _iterator.n()).done;) {
          _loop();
        }
      } catch (err) {
        _iterator.e(err);
      } finally {
        _iterator.f();
      }
    }
  }, {
    key: "addSelectListener",
    value: function addSelectListener() {
      var _this3 = this;
      this.dateTypeSelect.addEventListener('change', function () {
        var reportDateExists = !!document.getElementsByClassName('report-day')[0];
        var selectedOptionIsOldWeight = _this3.selectedOptionValue === 'old-weight';
        if (reportDateExists && !selectedOptionIsOldWeight) {
          _this3.hideDatetimeInputs();
        } else if (!reportDateExists && selectedOptionIsOldWeight) {
          _this3.showDatetimeInputs();
        }
      });
    }
  }, {
    key: "addSubmitButtonListener",
    value: function addSubmitButtonListener() {
      var _this4 = this;
      this.submitButton.addEventListener('click', function (e) {
        var isWeightValueValid = _this4.weightRegex.test(_this4.weightInput.value);
        if (!isWeightValueValid || _this4.selectedOptionValue === '') {
          e.preventDefault();
        }
      });
    }
  }, {
    key: "displayDeleteReportConfirmation",
    value: function displayDeleteReportConfirmation(deleteReportClickedButton) {
      var selectedReport = deleteReportClickedButton.parentNode;
      var cancelReportDeletionButton = document.createElement('a');
      cancelReportDeletionButton.href = 'index.php?page=progress';
      cancelReportDeletionButton.classList = 'btn small-circle-btn purple-bkgd';
      cancelReportDeletionButton.textContent = 'Non';
      var reportDeletionMessage = document.createElement('div');
      reportDeletionMessage.classList = 'cancelation-alert';
      reportDeletionMessage.innerHTML = '<p>Etes-vous sûr de vouloir supprimer ce relevé ?</p>';
      var confirmReportDeletionButton = document.createElement('a');
      confirmReportDeletionButton.href = "index.php?action=delete-report&id=".concat(selectedReport.id);
      confirmReportDeletionButton.classList = 'btn small-circle-btn red-bkgd';
      confirmReportDeletionButton.textContent = 'Oui';
      selectedReport.innerHTML = '';
      selectedReport.style.opacity = 0;
      selectedReport.appendChild(cancelReportDeletionButton);
      selectedReport.appendChild(reportDeletionMessage);
      selectedReport.appendChild(confirmReportDeletionButton);
      this.fadeInItem(selectedReport, 4000, 1);
    }
  }, {
    key: "hideDatetimeInputs",
    value: function hideDatetimeInputs() {
      var reportDate = document.getElementsByClassName('report-day')[0];
      var reportTime = document.getElementsByClassName('report-time')[0];
      this.progressFormInputs.removeChild(reportDate);
      this.progressFormInputs.removeChild(reportTime);
    }
  }, {
    key: "addProgressFormEvents",
    value: function addProgressFormEvents() {
      this.addSelectListener();
      this.addDeleteReportListeners();
      this.addSubmitButtonListener();
    }
  }, {
    key: "showDatetimeInputs",
    value: function showDatetimeInputs() {
      var reportDate = document.createElement('input');
      reportDate.type = 'date';
      reportDate.classList = 'report-day';
      reportDate.name = 'report-day';
      reportDate.max = new Date().toISOString().split('T')[0];
      reportDate.setAttribute('required', '');
      var reportTime = document.createElement('input');
      reportTime.type = 'time';
      reportTime.classList = 'report-time';
      reportTime.name = 'report-time';
      reportTime.setAttribute('required', '');
      this.progressFormInputs.appendChild(reportDate);
      this.progressFormInputs.appendChild(reportTime);
    }
  }]);
  return ProgressManager;
}(ElementFader);
