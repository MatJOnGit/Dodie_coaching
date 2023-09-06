"use strict";

function _typeof(obj) { "@babel/helpers - typeof"; return _typeof = "function" == typeof Symbol && "symbol" == typeof Symbol.iterator ? function (obj) { return typeof obj; } : function (obj) { return obj && "function" == typeof Symbol && obj.constructor === Symbol && obj !== Symbol.prototype ? "symbol" : typeof obj; }, _typeof(obj); }
function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }
function _defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, _toPropertyKey(descriptor.key), descriptor); } }
function _createClass(Constructor, protoProps, staticProps) { if (protoProps) _defineProperties(Constructor.prototype, protoProps); if (staticProps) _defineProperties(Constructor, staticProps); Object.defineProperty(Constructor, "prototype", { writable: false }); return Constructor; }
function _toPropertyKey(arg) { var key = _toPrimitive(arg, "string"); return _typeof(key) === "symbol" ? key : String(key); }
function _toPrimitive(input, hint) { if (_typeof(input) !== "object" || input === null) return input; var prim = input[Symbol.toPrimitive]; if (prim !== undefined) { var res = prim.call(input, hint || "default"); if (_typeof(res) !== "object") return res; throw new TypeError("@@toPrimitive must return a primitive value."); } return (hint === "string" ? String : Number)(input); }
var MealNutrientsDisplayer = /*#__PURE__*/function () {
  function MealNutrientsDisplayer() {
    _classCallCheck(this, MealNutrientsDisplayer);
    this._nutrientDefaultValue = 0;
    this._nutrientsList = ['calories', 'carbs', 'fat', 'proteins', 'sodium', 'potassium', 'fibers', 'sugar'];
    this._mealParsedData;
    this._tableItem;
    this._nutrientKey;
    this._nutrientsPerMeal = {};
  }
  _createClass(MealNutrientsDisplayer, [{
    key: "nutrientKey",
    get: function get() {
      return this._nutrientKey;
    },
    set: function set(nutrientKey) {
      this._nutrientKey = nutrientKey;
    }
  }, {
    key: "nutrientDefaultValue",
    get: function get() {
      return this._nutrientDefaultValue;
    }
  }, {
    key: "nutrientsList",
    get: function get() {
      return this._nutrientsList;
    }
  }, {
    key: "nutrientsPerMeal",
    get: function get() {
      return this._nutrientsPerMeal;
    }
  }, {
    key: "tableItem",
    get: function get() {
      return this._tableItem;
    },
    set: function set(tableElt) {
      this._tableItem = tableElt;
    }
  }, {
    key: "mealParsedData",
    get: function get() {
      return this._mealParsedData;
    },
    set: function set(dailyMealsData) {
      this._mealParsedData = JSON.parse(dailyMealsData);
    }
  }, {
    key: "nutrientsPerMealValue",
    set: function set(nutrientValue) {
      this._nutrientsPerMeal[this.nutrientKey] = nutrientValue;
    }
  }, {
    key: "init",
    value: function init() {
      this.buildMealArray();
      this.buildNutrientsTable(this.nutrientsPerMeal, this.tableItem);
    }
  }, {
    key: "buildNutrientsTable",
    value: function buildNutrientsTable(nutrientsTab, dataTabElt) {
      var tableBodyElt = document.createElement('tbody');
      var firstNutrientsRow = document.createElement('tr');
      var secondNutrientsRow = document.createElement('tr');
      var thirdNutrientsRow = document.createElement('tr');
      var fourthNutrientsRow = document.createElement('tr');
      var caloriesTdElt = document.createElement('td');
      caloriesTdElt.innerText = nutrientsTab['calories'].toFixed(0) + ' calories';
      caloriesTdElt.setAttribute('colspan', 2);
      var proteinsTdElt = document.createElement('td');
      proteinsTdElt.innerText = nutrientsTab['proteins'].toFixed(0) + ' g protéines';
      var carbsTdElt = document.createElement('td');
      carbsTdElt.innerText = nutrientsTab['carbs'].toFixed(0) + ' g glucides';
      var fatTdElt = document.createElement('td');
      fatTdElt.innerText = nutrientsTab['fat'].toFixed(0) + ' g lipides';
      var sugarTdElt = document.createElement('td');
      sugarTdElt.innerText = nutrientsTab['sugar'].toFixed(0) + ' g sucre';
      var fibersTdElt = document.createElement('td');
      fibersTdElt.innerText = nutrientsTab['fibers'].toFixed(0) + ' g fibres';
      var sodiumTdElt = document.createElement('td');
      sodiumTdElt.innerText = nutrientsTab['sodium'].toFixed(0) + ' mg sodium';
      firstNutrientsRow.appendChild(caloriesTdElt);
      secondNutrientsRow.appendChild(proteinsTdElt);
      secondNutrientsRow.appendChild(sugarTdElt);
      thirdNutrientsRow.appendChild(carbsTdElt);
      thirdNutrientsRow.appendChild(fibersTdElt);
      fourthNutrientsRow.appendChild(fatTdElt);
      fourthNutrientsRow.appendChild(sodiumTdElt);
      tableBodyElt.appendChild(firstNutrientsRow);
      tableBodyElt.appendChild(secondNutrientsRow);
      tableBodyElt.appendChild(thirdNutrientsRow);
      tableBodyElt.appendChild(fourthNutrientsRow);
      dataTabElt.appendChild(tableBodyElt);
    }
  }, {
    key: "buildMealArray",
    value: function buildMealArray() {
      this.gatherMealData();
      this.buildMealNutrientsData(this.mealParsedData);
    }
  }, {
    key: "gatherMealData",
    value: function gatherMealData() {
      this.tableItem = document.getElementsByClassName('meal-nutrients-table')[0];
      this.mealParsedData = this.tableItem.getAttribute('data-daily-program');
    }
  }, {
    key: "buildMealNutrientsData",
    value: function buildMealNutrientsData(mealData) {
      var _this = this;
      this.initNutrientsPerMealEntry();
      Object.keys(mealData).forEach(function (ingredientKey) {
        var ingredientData = mealData[ingredientKey];
        _this.buildIngredientsNutrientsData(ingredientData);
      });
    }
  }, {
    key: "initNutrientsPerMealEntry",
    value: function initNutrientsPerMealEntry() {
      var _this2 = this;
      this.nutrientsList.forEach(function (nutrient) {
        _this2.nutrientsPerMeal[nutrient] = _this2.nutrientDefaultValue;
      });
    }
  }, {
    key: "buildIngredientsNutrientsData",
    value: function buildIngredientsNutrientsData(ingredientData) {
      var _this3 = this;
      Object.keys(this.nutrientsList).forEach(function (nutrientKey) {
        _this3.nutrientKey = _this3.nutrientsList[nutrientKey];
        var rationNutrientValue = _this3.getNutrientValue(ingredientData);
        _this3.updateNutrientsPerMealData(rationNutrientValue);
      });
    }
  }, {
    key: "getNutrientValue",
    value: function getNutrientValue(ingredientData) {
      var isNutrientValueSet = ingredientData[this.nutrientKey] !== '-1';
      var isNutrientInGrams = ingredientData['measure'] === 'grammes';
      return isNutrientValueSet ? isNutrientInGrams ? ingredientData[this.nutrientKey] / 100 * ingredientData['quantity'] : ingredientData[this.nutrientKey] * ingredientData['quantity'] : 'Missing data';
    }
  }, {
    key: "updateNutrientsPerMealData",
    value: function updateNutrientsPerMealData(rationNutrientValue) {
      this.nutrientsPerMealValue = this.computeNewNutrientPerMealValue(rationNutrientValue);
    }
  }, {
    key: "computeNewNutrientPerMealValue",
    value: function computeNewNutrientPerMealValue(rationNutrientValue) {
      var oldNutrientValue = this.nutrientsPerMeal[this.nutrientKey];
      return typeof oldNutrientValue === 'number' && typeof rationNutrientValue === 'number' ? oldNutrientValue + rationNutrientValue : 'Missing data';
    }
  }]);
  return MealNutrientsDisplayer;
}();
