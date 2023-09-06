"use strict";

function _typeof(obj) { "@babel/helpers - typeof"; return _typeof = "function" == typeof Symbol && "symbol" == typeof Symbol.iterator ? function (obj) { return typeof obj; } : function (obj) { return obj && "function" == typeof Symbol && obj.constructor === Symbol && obj !== Symbol.prototype ? "symbol" : typeof obj; }, _typeof(obj); }
function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }
function _defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, _toPropertyKey(descriptor.key), descriptor); } }
function _createClass(Constructor, protoProps, staticProps) { if (protoProps) _defineProperties(Constructor.prototype, protoProps); if (staticProps) _defineProperties(Constructor, staticProps); Object.defineProperty(Constructor, "prototype", { writable: false }); return Constructor; }
function _toPropertyKey(arg) { var key = _toPrimitive(arg, "string"); return _typeof(key) === "symbol" ? key : String(key); }
function _toPrimitive(input, hint) { if (_typeof(input) !== "object" || input === null) return input; var prim = input[Symbol.toPrimitive]; if (prim !== undefined) { var res = prim.call(input, hint || "default"); if (_typeof(res) !== "object") return res; throw new TypeError("@@toPrimitive must return a primitive value."); } return (hint === "string" ? String : Number)(input); }
var ElementFader = /*#__PURE__*/function () {
  function ElementFader() {
    _classCallCheck(this, ElementFader);
  }
  _createClass(ElementFader, [{
    key: "fadeInItem",
    value:
    /*********************************************************
    Adds a progressive fade in effect to the item in parameter
    *********************************************************/
    function fadeInItem(item, timer, maxOpacity) {
      var intervalId = setInterval(function () {
        var itemOpacity = Number(window.getComputedStyle(item).getPropertyValue('opacity'));
        if (itemOpacity < maxOpacity) {
          item.style.opacity = itemOpacity + .1;
        } else {
          clearInterval(intervalId);
        }
      }, timer / 100);
    }

    /**********************************************************
    Adds a progressive fade out effect to the item in parameter
    **********************************************************/
  }, {
    key: "fadeOutItem",
    value: function fadeOutItem(item, timer) {
      var intervalId = setInterval(function () {
        var itemOpacity = Number(window.getComputedStyle(item).getPropertyValue('opacity'));
        if (itemOpacity > 0) {
          item.style.opacity = itemOpacity - .1;
        } else {
          clearInterval(intervalId);
        }
      }, timer / 100);
    }
  }]);
  return ElementFader;
}();
