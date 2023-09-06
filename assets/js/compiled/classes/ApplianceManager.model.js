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
var ApplianceManager = /*#__PURE__*/function (_ElementFader) {
  _inherits(ApplianceManager, _ElementFader);
  var _super = _createSuper(ApplianceManager);
  function ApplianceManager() {
    var _this;
    _classCallCheck(this, ApplianceManager);
    _this = _super.call(this);
    _this._adminPanel = document.getElementsByClassName('admin-panel')[0];
    _this._applianceDecisionBox = document.getElementById('appliance-decision-box');
    _this._declineApplianceBtn = document.getElementById('decline-btn');
    _this._applianceId;
    return _this;
  }
  _createClass(ApplianceManager, [{
    key: "adminPanel",
    get: function get() {
      return this._adminPanel;
    }
  }, {
    key: "applianceDecisionBox",
    get: function get() {
      return this._applianceDecisionBox;
    }
  }, {
    key: "applianceId",
    get: function get() {
      return this._applianceId;
    },
    set: function set(id) {
      this._applianceId = id;
    }
  }, {
    key: "declineApplianceBtn",
    get: function get() {
      return this._declineApplianceBtn;
    }
  }, {
    key: "addDeclineApplianceButtonListener",
    value: function addDeclineApplianceButtonListener() {
      var _this2 = this;
      this.declineApplianceBtn.addEventListener('click', function () {
        _this2.setApplianceId();
        _this2.removeApplianceDecisionBox();
        _this2.buildRejectApplianceForm();
      });
    }
  }, {
    key: "buildRejectApplianceForm",
    value: function buildRejectApplianceForm() {
      var rejectApplianceForm = document.createElement('form');
      var rejectionMessage = document.createElement('textarea');
      var reloadBtn = document.createElement('a');
      var confirmRejectionBtn = document.createElement('input');
      rejectApplianceForm.classList.add('admin-form', 'appliance-form');
      rejectApplianceForm.action = "index.php?action=reject-appliance&id=".concat(this.applianceId);
      rejectApplianceForm.method = 'post';
      rejectApplianceForm.style.opacity = 0;
      rejectionMessage.placeholder = 'Votre message de refus de prise en charge';
      rejectionMessage.name = 'rejection-message';
      rejectionMessage.id = 'rejection-message';
      reloadBtn.innerText = 'Annuler';
      reloadBtn.href = "index.php?page=appliance-details&id=".concat(this.applianceId);
      reloadBtn.classList.add('btn', 'rounded', 'tiny-btn', 'blue-bkgd');
      confirmRejectionBtn.value = 'Confirmer';
      confirmRejectionBtn.type = 'submit';
      confirmRejectionBtn.classList.add('btn', 'rounded', 'tiny-btn', 'red-bkgd');
      rejectApplianceForm.appendChild(rejectionMessage);
      rejectApplianceForm.appendChild(reloadBtn);
      rejectApplianceForm.appendChild(confirmRejectionBtn);
      this.adminPanel.appendChild(rejectApplianceForm);
      this.fadeInItem(rejectApplianceForm, 4000, 1);
    }
  }, {
    key: "init",
    value: function init() {
      this.addDeclineApplianceButtonListener();
    }
  }, {
    key: "removeApplianceDecisionBox",
    value: function removeApplianceDecisionBox() {
      this.adminPanel.removeChild(this.applianceDecisionBox);
    }
  }, {
    key: "setApplianceId",
    value: function setApplianceId() {
      this.applianceId = this.declineApplianceBtn.getAttribute('data-id');
    }
  }]);
  return ApplianceManager;
}(ElementFader);
