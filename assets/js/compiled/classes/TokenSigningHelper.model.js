"use strict";

function _typeof(obj) { "@babel/helpers - typeof"; return _typeof = "function" == typeof Symbol && "symbol" == typeof Symbol.iterator ? function (obj) { return typeof obj; } : function (obj) { return obj && "function" == typeof Symbol && obj.constructor === Symbol && obj !== Symbol.prototype ? "symbol" : typeof obj; }, _typeof(obj); }
function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }
function _defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, _toPropertyKey(descriptor.key), descriptor); } }
function _createClass(Constructor, protoProps, staticProps) { if (protoProps) _defineProperties(Constructor.prototype, protoProps); if (staticProps) _defineProperties(Constructor, staticProps); Object.defineProperty(Constructor, "prototype", { writable: false }); return Constructor; }
function _toPropertyKey(arg) { var key = _toPrimitive(arg, "string"); return _typeof(key) === "symbol" ? key : String(key); }
function _toPrimitive(input, hint) { if (_typeof(input) !== "object" || input === null) return input; var prim = input[Symbol.toPrimitive]; if (prim !== undefined) { var res = prim.call(input, hint || "default"); if (_typeof(res) !== "object") return res; throw new TypeError("@@toPrimitive must return a primitive value."); } return (hint === "string" ? String : Number)(input); }
var TokenSigningHelper = /*#__PURE__*/function () {
  function TokenSigningHelper() {
    _classCallCheck(this, TokenSigningHelper);
    this._tokenRegex = /^[A-Z0-9]{6}$/;
    this._form = document.getElementsByTagName('form')[0];
    this._tokenField = document.getElementById('user-token');
  }
  _createClass(TokenSigningHelper, [{
    key: "form",
    get: function get() {
      return this._form;
    }
  }, {
    key: "tokenField",
    get: function get() {
      return this._tokenField;
    }
  }, {
    key: "tokenRegex",
    get: function get() {
      return this._tokenRegex;
    }
  }, {
    key: "init",
    value: function init() {
      var _this = this;
      this.form.addEventListener('submit', function (e) {
        if (!_this.tokenRegex.test(_this.tokenField.value.toUpperCase())) {
          e.preventDefault();
        }
      });
    }
  }]);
  return TokenSigningHelper;
}();
