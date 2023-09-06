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
var ConnectionHelper = /*#__PURE__*/function (_ElementFader) {
  _inherits(ConnectionHelper, _ElementFader);
  var _super = _createSuper(ConnectionHelper);
  function ConnectionHelper() {
    var _this;
    _classCallCheck(this, ConnectionHelper);
    _this = _super.call(this);
    _this._emailRegex = /^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/;
    _this._passwordRegex = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{10,50}$/;
    _this._usernameRegex = /^[a-zA-Zàâçéèêñ '.-]+$/;
    _this._capitalLetterRegex = /[A-Z]/;
    _this._domainNameRegex = /@\w+([\.-]?\w+)*(\.\w{2,3})+$/;
    _this._numberRegex = /\d/;
    _this._smallCapRegex = /[a-z]/;
    _this._specialCharRegex = /[@$!%*?&]/;
    _this._emailInputElt = document.getElementById('user-email');
    _this._form = document.getElementsByTagName('form')[0];
    _this._showInputHelperBtns = document.getElementsByClassName('show-input-helper-btn');
    _this._isEmailValid = false;
    _this._inputAlerts = {
      'email': {
        'at-symbol': "Votre email doit contenir une arobase",
        'domain': "Le domaine de votre email n'est pas valide",
        'unknown': "Votre adresse mail n'est pas valide",
        'valid': "Votre adresse mail est valide"
      },
      'password': {
        'capital-letter': "Votre mot de passe doit contenir au moins une lettre majuscule",
        'long': "Votre mot de passe est trop long (max. 50 caractères)",
        'number': "Votre mot de passe doit contenir au moins un chiffre",
        'small-cap': "Votre mot de passe doit contenir au moins une lettre minuscule",
        'short': "Votre mot de passe est trop court (mini. 10 caractères)",
        'special-char': "Votre mot de passe doit contenir au moins un caractère spécial (@,$,!,%,*,?,&)",
        'unknown': "Votre mot de passe n'est pas valide",
        'valid': "Votre mot de passe est valide"
      }
    };
    return _this;
  }
  _createClass(ConnectionHelper, [{
    key: "capitalLetterRegex",
    get: function get() {
      return this._capitalLetterRegex;
    }
  }, {
    key: "domainNameRegex",
    get: function get() {
      return this._domainNameRegex;
    }
  }, {
    key: "emailRegex",
    get: function get() {
      return this._emailRegex;
    }
  }, {
    key: "form",
    get: function get() {
      return this._form;
    }
  }, {
    key: "inputAlerts",
    get: function get() {
      return this._inputAlerts;
    }
  }, {
    key: "inputElts",
    get: function get() {
      return this._inputElts;
    }
  }, {
    key: "isEmailValid",
    get: function get() {
      return this._isEmailValid;
    },
    set: function set(_boolean) {
      this._isEmailValid = _boolean;
    }
  }, {
    key: "numberRegex",
    get: function get() {
      return this._numberRegex;
    }
  }, {
    key: "passwordRegex",
    get: function get() {
      return this._passwordRegex;
    }
  }, {
    key: "showInputHelperBtns",
    get: function get() {
      return this._showInputHelperBtns;
    }
  }, {
    key: "smallCapRegex",
    get: function get() {
      return this._smallCapRegex;
    }
  }, {
    key: "specialCharRegex",
    get: function get() {
      return this._specialCharRegex;
    }
  }, {
    key: "addHelperDismissButtonListener",
    value: function addHelperDismissButtonListener(inputHelper) {
      var _this2 = this;
      var inputHelperDismissBtn = inputHelper.getElementsByTagName('button')[0];
      var inputHelperMessageType = inputHelper.getElementsByTagName('p')[0].className;
      var inputHelperType = inputHelperMessageType.split('-')[0];
      inputHelperDismissBtn.addEventListener('click', function () {
        _this2.removePreviousInputHelper(inputHelperType);
      });
    }
  }, {
    key: "addInputsListeners",
    value: function addInputsListeners() {
      var _this3 = this;
      this.inputElts.forEach(function (inputElt) {
        inputElt.addEventListener('blur', function () {
          _this3.updateInputChecker(inputElt);
        });
      });
    }
  }, {
    key: "addShowHelperButtonsListeners",
    value: function addShowHelperButtonsListeners() {
      var _this4 = this;
      var _iterator = _createForOfIteratorHelper(this.showInputHelperBtns),
        _step;
      try {
        var _loop = function _loop() {
          var showHelperBtn = _step.value;
          showHelperBtn.addEventListener('click', function (e) {
            e.preventDefault();
            var inputElt = _this4.getInfoButtonBoundValue(showHelperBtn);
            _this4.showInputHelper(inputElt.type, inputElt.value);
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
      ;
    }
  }, {
    key: "buildHelper",
    value: function buildHelper(inputType, inputValue) {
      var inputHelper = document.createElement('div');
      var helperMessage = document.createElement('p');
      var textualHelpDismissBtn = document.createElement('button');
      var crossIcon = document.createElement('i');
      inputHelper.id = 'input-helper-container';
      helperMessage.textContent = this.getAlert(inputType, inputValue);
      helperMessage.className = "".concat(inputType, "-message");
      textualHelpDismissBtn.className = 'input-helper-dismiss-btn';
      crossIcon.className = 'fa-solid fa-xmark';
      textualHelpDismissBtn.appendChild(crossIcon);
      inputHelper.appendChild(helperMessage);
      inputHelper.appendChild(textualHelpDismissBtn);
      return inputHelper;
    }
  }, {
    key: "getAlert",
    value: function getAlert(inputType, inputValue) {
      var alert = '';
      if (inputType === 'password') {
        alert = this.getPasswordAlert(inputType, inputValue);
      } else if (inputType === 'email') {
        alert = this.getEmailAlert(inputType, inputValue);
      }
      return alert;
    }
  }, {
    key: "getEmailAlert",
    value: function getEmailAlert(inputType, inputValue) {
      var emailAlert = '';
      if (this.emailRegex.test(inputValue)) {
        emailAlert = this.inputAlerts[inputType]['valid'];
      } else if (!inputValue.includes('@')) {
        emailAlert = this.inputAlerts[inputType]['at-symbol'];
      } else if (!this.domainNameRegex.test(inputValue)) {
        emailAlert = this.inputAlerts[inputType]['domain'];
      } else {
        emailAlert = this.inputAlerts[inputType]['unknown'];
      }
      return emailAlert;
    }
  }, {
    key: "getInfoButtonBoundValue",
    value: function getInfoButtonBoundValue(showHelperBtn) {
      return showHelperBtn.parentElement.getElementsByTagName('input')[0];
    }
  }, {
    key: "getPasswordAlert",
    value: function getPasswordAlert(inputType, inputValue) {
      var passwordAlert = '';
      if (this.passwordRegex.test(inputValue)) {
        passwordAlert = this.inputAlerts[inputType]['valid'];
      } else if (inputValue.length < 10) {
        passwordAlert = this.inputAlerts[inputType]['short'];
      } else if (inputValue.length > 50) {
        passwordAlert = this.inputAlerts[inputType]['long'];
      } else if (!this.numberRegex.test(inputValue)) {
        passwordAlert = this.inputAlerts[inputType]['number'];
      } else if (!this.smallCapRegex.test(inputValue)) {
        passwordAlert = this.inputAlerts[inputType]['small-cap'];
      } else if (!this.capitalLetterRegex.test(inputValue)) {
        passwordAlert = this.inputAlerts[inputType]['capital-letter'];
      } else if (!this.specialCharRegex.test(inputValue)) {
        passwordAlert = this.inputAlerts[inputType]['special-char'];
      } else {
        passwordAlert = this.inputAlerts[inputType]['unknown'];
      }
      return passwordAlert;
    }
  }, {
    key: "init",
    value: function init() {
      this.addInputsListeners();
      this.addShowHelperButtonsListeners();
      this.addSubmitButtonListener();
    }
  }, {
    key: "isInputEmpty",
    value: function isInputEmpty(inputElt) {
      return inputElt.value === '';
    }
  }, {
    key: "isInputHelperExisting",
    value: function isInputHelperExisting() {
      var inputHelper = document.getElementById('input-helper-container');
      var isInputHelperExisting;
      if (!inputHelper) {
        isInputHelperExisting = false;
      } else {
        isInputHelperExisting = true;
      }
      return isInputHelperExisting;
    }
  }, {
    key: "removePreviousInputHelper",
    value: function removePreviousInputHelper() {
      var connectionPanel = document.getElementsByClassName('connection-panel')[0];
      var previousInputHelper = document.getElementById('input-helper-container');
      if (previousInputHelper) {
        connectionPanel.removeChild(previousInputHelper);
      }
    }
  }, {
    key: "showInputHelper",
    value: function showInputHelper(inputType, inputValue) {
      var connectionPanel = document.getElementsByClassName('connection-panel')[0];
      var inputHelper = this.buildHelper(inputType, inputValue);
      if (this.isInputHelperExisting(inputType)) {
        this.removePreviousInputHelper(inputType);
      }
      connectionPanel.insertAdjacentElement('afterbegin', inputHelper);
      this.fadeInItem(inputHelper, 2000, 1);
      this.addHelperDismissButtonListener(inputHelper);
    }
  }, {
    key: "updateInputChecker",
    value: function updateInputChecker(inputElt) {
      var inputContainerElt = inputElt.parentElement;
      var inputCheckerElt = inputContainerElt.getElementsByClassName('input-helper')[0];
      var isInputEmpty = this.isInputEmpty(inputElt);
      var isInputValid = this.isBlurredInputValid(inputElt);
      if (isInputValid) {
        inputCheckerElt.innerHTML = '<i class="fa-solid fa-check correct"></i>';
        this.removePreviousInputHelper(inputElt.type);
      } else if (isInputEmpty) {
        inputCheckerElt.innerHTML = '';
      } else {
        inputCheckerElt.innerHTML = '<i class="fa-solid fa-xmark wrong"></i>';
      }
    }
  }]);
  return ConnectionHelper;
}(ElementFader);
