"use strict";

function _typeof(obj) { "@babel/helpers - typeof"; return _typeof = "function" == typeof Symbol && "symbol" == typeof Symbol.iterator ? function (obj) { return typeof obj; } : function (obj) { return obj && "function" == typeof Symbol && obj.constructor === Symbol && obj !== Symbol.prototype ? "symbol" : typeof obj; }, _typeof(obj); }
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
var KitchenManager = /*#__PURE__*/function () {
  function KitchenManager(apiKey) {
    _classCallCheck(this, KitchenManager);
    this._adminPanel = document.getElementsByClassName('admin-panel')[0];
    this._apiKey = apiKey;
    this._numbersRegex = /^[0-9]+([.,][0-9]+)?$/;
  }
  _createClass(KitchenManager, [{
    key: "adminPanel",
    get: function get() {
      return this._adminPanel;
    }
  }, {
    key: "apiKey",
    get: function get() {
      return this._apiKey;
    }
  }, {
    key: "kitchenDataForm",
    get: function get() {
      return this._kitchenDataForm;
    },
    set: function set(dataForm) {
      this._kitchenDataForm = dataForm;
    }
  }, {
    key: "numbersRegex",
    get: function get() {
      return this._numbersRegex;
    }
  }, {
    key: "clearPanel",
    value: function clearPanel() {
      this.adminPanel.innerHTML = '';
    }
  }, {
    key: "scrollTop",
    value: function scrollTop() {
      window.scrollTo({
        top: 0,
        behavior: 'smooth'
      });
    }
  }, {
    key: "verifyFormData",
    value: function verifyFormData() {
      var _this = this;
      var inputValidationResults = Array.from(document.querySelectorAll('#ingredient-form input')).map(function (input) {
        return {
          value: input.value.trim(),
          expectedType: input.type
        };
      }).every(function (_ref) {
        var expectedType = _ref.expectedType,
          value = _ref.value;
        return _this.validateType(value, expectedType);
      });
      return inputValidationResults;
    }
  }, {
    key: "validateType",
    value: function validateType(value, type) {
      switch (type) {
        case 'number':
          return !isNaN(value.replace(',', '.'));
        case 'text':
          return isNaN(value.replace(',', '.')) || value === '';
        default:
          return false;
      }
    }
  }, {
    key: "handleNewSearchBtnClick",
    value: function handleNewSearchBtnClick(e) {
      e.preventDefault();
      location.reload();
    }

    /*********************************************************************************
    Builds and returns a body object with sanitized data, computed ingredient measures
    based on select selected value, and number inputs values turned into float values
    *********************************************************************************/
  }, {
    key: "buildIngredientBodyOption",
    value: function buildIngredientBodyOption() {
      var bodyObj = this.getSanitizedBody();
      bodyObj = this.getComputedMeasure(bodyObj);
      bodyObj = this.parseFloatValues(bodyObj);
      return bodyObj;
    }
  }, {
    key: "getSanitizedBody",
    value: function getSanitizedBody(body) {
      body = {};
      var inputs = Array.from(document.querySelectorAll('.ingredient-param:not(.hidden) input'));
      inputs.forEach(function (input) {
        var valueTextNode = document.createTextNode(input.value);
        var textNodeContainer = document.createElement('div');
        textNodeContainer.appendChild(valueTextNode);
        body[input.id] = textNodeContainer.innerHTML;
      });
      return body;
    }
  }, {
    key: "getComputedMeasure",
    value: function getComputedMeasure(body) {
      var measureSelect = document.querySelector('#measure-select');
      var selectedOption = measureSelect.options[measureSelect.selectedIndex];
      var selectedValue = selectedOption.value;
      body.preparation = body.preparation || null;
      body.note = body.note || null;
      switch (selectedValue) {
        case 'grams':
          body.measure = selectedOption.textContent;
          break;
        case 'no-unit':
          body.measure = null;
          break;
        default:
          var otherMeasureInput = document.querySelector('#other-measure');
          body.measure = otherMeasureInput.value;
          break;
      }
      delete body['other-measure'];
      return body;
    }
  }, {
    key: "parseFloatValues",
    value: function parseFloatValues(body) {
      var _this2 = this;
      return Object.fromEntries(Object.entries(body).map(function (_ref2) {
        var _ref3 = _slicedToArray(_ref2, 2),
          bodyKey = _ref3[0],
          inputValue = _ref3[1];
        if (_this2.numbersRegex.test(inputValue)) {
          return [bodyKey, parseFloat(inputValue)];
        } else {
          return [bodyKey, inputValue];
        }
      }));
    }
  }, {
    key: "showTemporaryAlert",
    value: function showTemporaryAlert(alertBlockString) {
      var _this3 = this;
      var removeBlock = function removeBlock() {
        _this3.adminPanel.removeChild(alertBlock);
      };
      this.adminPanel.appendChild(alertBlockString);
      var alertBlock = document.getElementsByClassName('fixed-alert')[0];
      setTimeout(removeBlock, 4000);
    }
  }]);
  return KitchenManager;
}();
