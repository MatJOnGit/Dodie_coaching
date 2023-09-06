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
var DynamicMenuDisplayer = /*#__PURE__*/function (_ElementFader) {
  _inherits(DynamicMenuDisplayer, _ElementFader);
  var _super = _createSuper(DynamicMenuDisplayer);
  function DynamicMenuDisplayer() {
    var _this;
    _classCallCheck(this, DynamicMenuDisplayer);
    _this = _super.call(this);
    _this._menuTriggerBtn = document.getElementById('dynamic-menu-button');
    _this._bodyElt = document.getElementsByTagName('body')[0];
    _this._dynamicMenuWidthPerDevice = {
      'mobile': 225,
      'tablet': 500
    };
    return _this;
  }
  _createClass(DynamicMenuDisplayer, [{
    key: "dynamicMenuWidth",
    get: function get() {
      return this._dynamicMenuWidth;
    },
    set: function set(width) {
      this._dynamicMenuWidth = width;
    }
  }, {
    key: "dynamicMenuWidthPerDevice",
    get: function get() {
      return this._dynamicMenuWidthPerDevice;
    }
  }, {
    key: "bodyElt",
    get: function get() {
      return this._bodyElt;
    }
  }, {
    key: "closeDynamicMenuLayer",
    get: function get() {
      return this._closeDynamicMenuLayer;
    },
    set: function set(item) {
      this._closeDynamicMenuLayer = item;
    }
  }, {
    key: "dynamicMenu",
    get: function get() {
      return this._dynamicMenu;
    },
    set: function set(item) {
      this._dynamicMenu = item;
    }
  }, {
    key: "dynamicMenuContainer",
    get: function get() {
      return this._dynamicMenuContainer;
    },
    set: function set(item) {
      this._dynamicMenuContainer = item;
    }
  }, {
    key: "menuTriggerBtn",
    get: function get() {
      return this._menuTriggerBtn;
    }
  }, {
    key: "setDynamicMenuWidth",
    value: function setDynamicMenuWidth() {
      var windowScreenSize = window.innerWidth;
      if (windowScreenSize < 768) {
        this.dynamicMenuWidth = this.dynamicMenuWidthPerDevice.mobile;
      } else {
        this.dynamicMenuWidth = this.dynamicMenuWidthPerDevice.tablet;
      }
    }
  }, {
    key: "addCloseMenuEltsListener",
    value: function addCloseMenuEltsListener() {
      var _this2 = this;
      var closeMenuBtn = document.getElementById('close-menu-button');
      var blurryLayer = document.getElementsByClassName('blurry-layer')[0];
      var clickableElts = [closeMenuBtn, blurryLayer];
      clickableElts.forEach(function (clickedElt) {
        clickedElt.addEventListener('click', function () {
          _this2.fadeOutItem(_this2.dynamicMenuContainer, 1000);
          setTimeout(function () {
            _this2.bodyElt.removeChild(_this2.dynamicMenuContainer);
          }, 100);
        });
      });
    }
  }, {
    key: "addDefaultMenu",
    value: function addDefaultMenu() {
      var linkListElt = document.createElement('ul');
      var loginListItem = document.createElement('li');
      var contactListItem = document.createElement('li');
      var loginLink = document.createElement('a');
      var contactLink = document.createElement('a');
      var loginSpan = document.createElement('span');
      var contactSpan = document.createElement('span');
      loginLink.textContent = 'Connexion';
      loginLink.setAttribute('aria-label', 'Accéder au formulaire de connexion');
      loginLink.href = 'index.php?page=login';
      contactLink.textContent = 'Contact';
      contactLink.setAttribute('aria-label', 'Prendre contact par email');
      contactLink.href = 'mailto:contact@dodie-coaching.com';
      loginSpan.classList.add('menu-splitter');
      contactSpan.classList.add('menu-splitter');
      loginListItem.appendChild(loginLink);
      contactListItem.appendChild(contactLink);
      linkListElt.appendChild(loginListItem);
      linkListElt.appendChild(loginSpan);
      linkListElt.appendChild(contactListItem);
      linkListElt.appendChild(contactSpan);
      this.dynamicMenu.appendChild(linkListElt);
    }
  }, {
    key: "addLoggedUserMenu",
    value: function addLoggedUserMenu() {
      var linkListElt = document.createElement('ul');
      var dashboardListItem = document.createElement('li');
      var contactListItem = document.createElement('li');
      var logoutListItem = document.createElement('li');
      var dashboardLink = document.createElement('a');
      var contactLink = document.createElement('a');
      var logoutLink = document.createElement('a');
      var dashboardSpan = document.createElement('span');
      var contactSpan = document.createElement('span');
      var logoutSpan = document.createElement('span');
      dashboardListItem.classList.add('dynamic-menu-item');
      contactListItem.classList.add('dynamic-menu-item');
      logoutListItem.classList.add('dynamic-menu-item');
      dashboardLink.textContent = 'Tableau de bord';
      dashboardLink.setAttribute('aria-label', 'Accéder au tableau de bord');
      dashboardLink.href = 'index.php?page=dashboard';
      contactLink.textContent = 'Contact';
      contactLink.setAttribute('aria-label', 'Prendre contact par email');
      contactLink.href = 'index.php';
      logoutLink.textContent = 'Déconnexion';
      logoutLink.setAttribute('aria-label', 'Déconnecter ma session');
      logoutLink.href = 'index.php?action=logout';
      dashboardSpan.classList.add('menu-splitter');
      contactSpan.classList.add('menu-splitter');
      logoutSpan.classList.add('menu-splitter');
      dashboardListItem.appendChild(dashboardLink);
      contactListItem.appendChild(contactLink);
      logoutListItem.appendChild(logoutLink);
      linkListElt.appendChild(dashboardListItem);
      linkListElt.appendChild(dashboardSpan);
      linkListElt.appendChild(contactListItem);
      linkListElt.appendChild(contactSpan);
      linkListElt.appendChild(logoutListItem);
      linkListElt.appendChild(logoutSpan);
      this.dynamicMenu.appendChild(linkListElt);
    }
  }, {
    key: "addMenuTriggerBtnListener",
    value: function addMenuTriggerBtnListener() {
      var _this3 = this;
      this.menuTriggerBtn.addEventListener('click', function () {
        _this3.setDynamicMenuWidth();
        _this3.setDynamicLayer();
        _this3.setCloseMenuLayer();
        _this3.triggerCorrectMenuButtons();
        _this3.addCloseMenuEltsListener();
      });
    }
  }, {
    key: "setCloseMenuLayer",
    value: function setCloseMenuLayer() {
      var closeMenuBtn = document.createElement('button');
      var closeMenuIcon = document.createElement('i');
      closeMenuBtn.setAttribute('aria-label', 'Fermer le menu dynamique');
      closeMenuIcon.classList.add('fa-solid', 'fa-xmark');
      closeMenuIcon.id = 'close-menu-button';
      closeMenuBtn.appendChild(closeMenuIcon);
      this.closeDynamicMenuLayer.appendChild(closeMenuBtn);
    }
  }, {
    key: "setDynamicLayer",
    value: function setDynamicLayer() {
      var animationDuration = 200;
      var dynamicMenuContainer = document.createElement('div');
      var blurryLayer = document.createElement('div');
      var dynamicMenuLayer = document.createElement('div');
      var closeDynamicMenuLayer = document.createElement('div');
      var dynamicMenu = document.createElement('div');
      dynamicMenuContainer.id = 'dynamic-menu-container';
      blurryLayer.classList.add('blurry-layer');
      blurryLayer.style.opacity = 0;
      dynamicMenuLayer.classList.add('dynamic-menu-layer', 'blue-bkgd');
      closeDynamicMenuLayer.classList.add('close-menu-layer');
      dynamicMenu.classList.add('dynamic-menu');
      this.dynamicMenu = dynamicMenuLayer.appendChild(dynamicMenu);
      dynamicMenuContainer.appendChild(blurryLayer);
      dynamicMenuContainer.appendChild(dynamicMenuLayer);
      this.dynamicMenuContainer = this.bodyElt.appendChild(dynamicMenuContainer);
      this.fadeInItem(blurryLayer, 3000, 0.8);
      this.slideInMenu(dynamicMenuLayer, animationDuration);
      this.closeDynamicMenuLayer = dynamicMenuLayer.appendChild(closeDynamicMenuLayer);
    }
  }, {
    key: "slideInMenu",
    value: function slideInMenu(item, duration) {
      var _this4 = this;
      var stepCount = Math.round(duration / 16);
      var increment = this.dynamicMenuWidth / stepCount;
      var step = 0;
      var intervalId = setInterval(function () {
        if (step >= stepCount) {
          clearInterval(intervalId);
        } else {
          var itemWidth = item.getBoundingClientRect().width;
          var newWidth = Math.min(itemWidth + increment, _this4.dynamicMenuWidth);
          item.style.width = newWidth + 'px';
          step++;
        }
      }, 16);
    }
  }, {
    key: "triggerCorrectMenuButtons",
    value: function triggerCorrectMenuButtons() {
      document.getElementById('logged') ? this.addLoggedUserMenu() : this.addDefaultMenu();
    }
  }]);
  return DynamicMenuDisplayer;
}(ElementFader);
