"use strict";

function _typeof(obj) { "@babel/helpers - typeof"; return _typeof = "function" == typeof Symbol && "symbol" == typeof Symbol.iterator ? function (obj) { return typeof obj; } : function (obj) { return obj && "function" == typeof Symbol && obj.constructor === Symbol && obj !== Symbol.prototype ? "symbol" : typeof obj; }, _typeof(obj); }
function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }
function _defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, _toPropertyKey(descriptor.key), descriptor); } }
function _createClass(Constructor, protoProps, staticProps) { if (protoProps) _defineProperties(Constructor.prototype, protoProps); if (staticProps) _defineProperties(Constructor, staticProps); Object.defineProperty(Constructor, "prototype", { writable: false }); return Constructor; }
function _toPropertyKey(arg) { var key = _toPrimitive(arg, "string"); return _typeof(key) === "symbol" ? key : String(key); }
function _toPrimitive(input, hint) { if (_typeof(input) !== "object" || input === null) return input; var prim = input[Symbol.toPrimitive]; if (prim !== undefined) { var res = prim.call(input, hint || "default"); if (_typeof(res) !== "object") return res; throw new TypeError("@@toPrimitive must return a primitive value."); } return (hint === "string" ? String : Number)(input); }
var KitchenElementsBuilder = /*#__PURE__*/function () {
  function KitchenElementsBuilder() {
    _classCallCheck(this, KitchenElementsBuilder);
  }
  _createClass(KitchenElementsBuilder, null, [{
    key: "buildNodeFromTemplate",
    value: function buildNodeFromTemplate(template) {
      var range = document.createRange();
      range.selectNode(document.body);
      var templateFragment = range.createContextualFragment("".concat(template));
      return templateFragment;
    }
  }, {
    key: "buildPageTitle",
    value: function buildPageTitle(genericTitle) {
      var itemDesignation = arguments.length > 1 && arguments[1] !== undefined ? arguments[1] : '';
      var titleCompletion = itemDesignation ? "<br>".concat(itemDesignation) : '';
      var pageTitleTemplate = "\n            <h3>".concat(genericTitle).concat(titleCompletion, "</h3>\n        ");
      return this.buildNodeFromTemplate(pageTitleTemplate);
    }
  }, {
    key: "buildIngredientCard",
    value: function buildIngredientCard(ingredient) {
      var ingredientCardTemplate = "\n            <button id=".concat(ingredient.id, " class='food-card'>\n                <h4 class='food-name'>").concat(ingredient.name, " ").concat(ingredient.preparation || '', "</h4>\n                <p>").concat(ingredient.type, "</p>\n            </button>\n        ");
      return this.buildNodeFromTemplate(ingredientCardTemplate);
    }
  }, {
    key: "buildCreateItemButton",
    value: function buildCreateItemButton(itemType) {
      var createItemButtonTemplate = "\n            <button id='create-item-btn' class='btn rounded blue-bkgd'>\n                Nouvel ".concat(itemType, "\n            </button>\n        ");
      return this.buildNodeFromTemplate(createItemButtonTemplate);
    }
  }, {
    key: "buildSectionHeader",
    value: function buildSectionHeader(sectionType, sectionTitle) {
      var sectionHeaderTemplate = "\n            <h4 id='".concat(sectionType, "-params-title' class='admin-panel-header blue-bkgd'>\n                ").concat(sectionTitle, "\n            </h4>\n        ");
      return this.buildNodeFromTemplate(sectionHeaderTemplate);
    }
  }, {
    key: "buildInputBlock",
    value: function buildInputBlock(inputParams, itemType) {
      var inputBlockTemplate = "\n            <div id='".concat(inputParams.id, "-block' class='").concat(itemType, "-param").concat(inputParams.hidden && inputParams.hidden === true ? ' hidden' : '', "'>\n                <label for='").concat(itemType, "-").concat(inputParams.id, "'>").concat(inputParams.label, " :</label>\n                <input type='").concat(inputParams.type, "' id='").concat(inputParams.id, "' name='").concat(itemType, "-").concat(inputParams.id, "' value='").concat(inputParams.value === null ? '' : inputParams.value, "' ").concat(inputParams.required ? ' required' : '', ">\n            </div>\n        ");
      return this.buildNodeFromTemplate(inputBlockTemplate);
    }
  }, {
    key: "buildEditionValidationBlock",
    value: function buildEditionValidationBlock(itemType, message) {
      var editionvalidationBlockTemplate = "\n            <div id='form-btns-block'>\n                <div id='form-actions-block'>\n                    <button id='save-".concat(itemType, "-params-btn' class='btn tiny-btn rounded blue-bkgd'>Enregistrer</button>\n                    <button id='delete-").concat(itemType, "-btn' class='btn tiny-btn rounded red-bkgd'>Supprimer</button>\n                </div>\n                \n                <button id='new-search-btn' class='btn large-btn rounded blue-bkgd'>Nouvelle recherche</button>\n                \n                <div id='action-confirmation-block' class='hidden'>\n                    <button id='cancel-deletion-btn' class='btn small-circle-btn blue-bkgd'>Non</button>\n                    <div>\n                        <p>").concat(message, "</p>\n                    </div>\n                    <button id='confirm-deletion-btn' class='btn small-circle-btn red-bkgd'>Oui</button>\n                </div>\n            </div>\n        ");
      return this.buildNodeFromTemplate(editionvalidationBlockTemplate);
    }
  }, {
    key: "buildCreationFormValidationBlock",
    value: function buildCreationFormValidationBlock(itemType) {
      var creationFormValidationBlockTemplate = "\n            <div id='form-btns-block'>\n                <div id='form-actions-block'>\n                    <button id='save-".concat(itemType, "-params-btn' class='btn tiny-btn rounded blue-bkgd'>Enregistrer</button>\n                    <button id='reset-").concat(itemType, "-btn' class='btn tiny-btn rounded red-bkgd'>R\xE9initialiser</button>\n                </div>\n                \n                <button id='new-search-btn' class='btn large-btn rounded blue-bkgd'>Nouvelle recherche</button>\n            </div>\n        ");
      return this.buildNodeFromTemplate(creationFormValidationBlockTemplate);
    }
  }, {
    key: "buildSelectBlock",
    value: function buildSelectBlock(selectParams, itemType, defaultOption) {
      var optionsTemplate = selectParams.options.map(function (option) {
        return "<option value='".concat(option.value, "' ").concat(option.text === defaultOption ? 'selected' : '', ">").concat(option.text, "</option>");
      }).join('');
      var selectTemplate = "\n            <div class='".concat(itemType, "-param'>\n                <label for='").concat(selectParams.id, "-select'>").concat(selectParams.label, " :</label>\n                <select id='").concat(selectParams.id, "-select' name='").concat(selectParams.type, "-").concat(selectParams.id, "'>\n                    ").concat(optionsTemplate, "\n                </select>\n            </div>\n        ");
      return this.buildNodeFromTemplate(selectTemplate);
    }
  }, {
    key: "buildSuccessMessageBlock",
    value: function buildSuccessMessageBlock(successMessage) {
      var successMessageTemplate = "\n            <div class='success-alert fixed-alert'>\n                <p>".concat(successMessage, "</p>\n            </div>\n        ");
      return this.buildNodeFromTemplate(successMessageTemplate);
    }
  }, {
    key: "buildErrorMessageBlock",
    value: function buildErrorMessageBlock(errorMessage) {
      var errorMessageTemplate = "\n            <div class='error-alert fixed-alert'>\n                <p>".concat(errorMessage, "</p>\n            </div>\n        ");
      return this.buildNodeFromTemplate(errorMessageTemplate);
    }
  }, {
    key: "buildSearchHelper",
    value: function buildSearchHelper(message) {
      var searchHelperTemplate = "\n            <div class='entry-helper fixed-alert'>\n                <p>".concat(message, "</p>\n            </div>\n        ");
      return this.buildNodeFromTemplate(searchHelperTemplate);
    }
  }]);
  return KitchenElementsBuilder;
}();
