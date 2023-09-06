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
var ProgramInitializer = /*#__PURE__*/function (_ElementFader) {
  _inherits(ProgramInitializer, _ElementFader);
  var _super = _createSuper(ProgramInitializer);
  function ProgramInitializer() {
    var _this;
    _classCallCheck(this, ProgramInitializer);
    _this = _super.call(this);
    _this._adminPanel = document.getElementsByClassName('admin-panel')[0];
    _this._mealsInputsBlock = document.getElementById('meal-inputs-block');
    _this._mealsSubmitBtn = document.getElementById('meals-submit-btn');
    _this._checkboxInputs = _this._mealsInputsBlock.getElementsByTagName('input');
    _this._pageTitleElt = _this._adminPanel.getElementsByTagName('h3')[0];
    return _this;
  }
  _createClass(ProgramInitializer, [{
    key: "adminPanel",
    get: function get() {
      return this._adminPanel;
    }
  }, {
    key: "checkboxInputs",
    get: function get() {
      return this._checkboxInputs;
    }
  }, {
    key: "mealsSubmitBtn",
    get: function get() {
      return this._mealsSubmitBtn;
    }
  }, {
    key: "pageTitleElt",
    get: function get() {
      return this._pageTitleElt;
    }
  }, {
    key: "addRemoveAlertListener",
    value: function addRemoveAlertListener() {
      var _this2 = this;
      var dismissAlertBtn = this.adminPanel.getElementsByClassName('input-helper-dismiss-btn')[0];
      var alertBox = document.getElementById('input-helper-container');
      dismissAlertBtn.addEventListener('click', function (e) {
        _this2.adminPanel.removeChild(alertBox);
      });
    }
  }, {
    key: "addSubmitButtonListener",
    value: function addSubmitButtonListener() {
      var _this3 = this;
      this.mealsSubmitBtn.addEventListener('click', function (e) {
        var isMealsFormValid = false;
        var isAlertDisplayed = document.getElementById('input-helper-container');
        Object.keys(_this3.checkboxInputs).forEach(function (element) {
          if (_this3.checkboxInputs[element].checked) {
            isMealsFormValid = true;
          }
        });
        if (!isMealsFormValid) {
          e.preventDefault();
          if (!isAlertDisplayed) {
            _this3.displayNoSelectedMealsAlert();
          }
        }
      });
    }
  }, {
    key: "displayNoSelectedMealsAlert",
    value: function displayNoSelectedMealsAlert() {
      var inputHelper = document.createElement('div');
      var helperMessage = document.createElement('p');
      var dismissAlertBtn = document.createElement('button');
      var crossIcon = document.createElement('i');
      inputHelper.id = 'input-helper-container';
      helperMessage.textContent = "Vous n'avez pas sélectionné de repas.";
      helperMessage.className = "meal-building-alert";
      dismissAlertBtn.className = 'input-helper-dismiss-btn';
      crossIcon.className = 'fa-solid fa-xmark';
      dismissAlertBtn.appendChild(crossIcon);
      inputHelper.appendChild(helperMessage);
      inputHelper.appendChild(dismissAlertBtn);
      this.adminPanel.insertBefore(inputHelper, this.pageTitleElt);
      this.fadeInItem(inputHelper, 2000, 1);
      this.addRemoveAlertListener();
    }
  }, {
    key: "editPageElts",
    value: function editPageElts() {
      var pageTitle = this.adminPanel.getElementsByTagName('h3')[0].textContent;
      this.adminPanel.classList.add('meal-setup-panel');
      this.pageTitleElt.textContent = 'Composition du ' + pageTitle.slice(0, 1).toLowerCase() + pageTitle.slice(1);
    }
  }, {
    key: "init",
    value: function init() {
      this.editPageElts();
      this.addSubmitButtonListener();
    }
  }]);
  return ProgramInitializer;
}(ElementFader);
