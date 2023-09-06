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
var ProgramDisplayer = /*#__PURE__*/function (_ElementFader) {
  _inherits(ProgramDisplayer, _ElementFader);
  var _super = _createSuper(ProgramDisplayer);
  function ProgramDisplayer() {
    var _this;
    _classCallCheck(this, ProgramDisplayer);
    _this = _super.call(this);
    _this._nutrientDefaultValue = 0;
    _this._nutrientsList = ['calories', 'carbs', 'fat', 'proteins', 'sodium', 'fibers', 'sugar'];
    _this._dayDataTabs = document.getElementsByClassName('day-data-tab');
    _this._mealDataTabs = document.getElementsByClassName('meal-data-tab');
    _this._dailyMealsParsedData;
    _this._dailyProgramItems;
    _this._dayKey;
    _this._mealKey;
    _this._nutrientKey;
    _this._nutrientsPerDay = {};
    _this._nutrientsPerMeal = {};
    _this._nutrientsPerMealIndex;
    return _this;
  }
  _createClass(ProgramDisplayer, [{
    key: "dailyMealsParsedData",
    get: function get() {
      return this._dailyMealsParsedData;
    },
    set: function set(dailyMealsData) {
      this._dailyMealsParsedData = JSON.parse(dailyMealsData);
    }
  }, {
    key: "dailyProgramItems",
    get: function get() {
      return this._dailyProgramItems;
    },
    set: function set(weeklyMealsElts) {
      this._dailyProgramItems = weeklyMealsElts;
    }
  }, {
    key: "dayDataTabs",
    get: function get() {
      return this._dayDataTabs;
    }
  }, {
    key: "dayKey",
    get: function get() {
      return this._dayKey;
    },
    set: function set(dayKey) {
      this._dayKey = dayKey;
    }
  }, {
    key: "mealDataTabs",
    get: function get() {
      return this._mealDataTabs;
    }
  }, {
    key: "mealKey",
    get: function get() {
      return this._mealKey;
    },
    set: function set(mealKey) {
      this._mealKey = mealKey;
    }
  }, {
    key: "nutrientDefaultValue",
    get: function get() {
      return this._nutrientDefaultValue;
    }
  }, {
    key: "nutrientKey",
    get: function get() {
      return this._nutrientKey;
    },
    set: function set(nutrientKey) {
      this._nutrientKey = nutrientKey;
    }
  }, {
    key: "nutrientsList",
    get: function get() {
      return this._nutrientsList;
    }
  }, {
    key: "nutrientsPerDay",
    get: function get() {
      return this._nutrientsPerDay;
    }
  }, {
    key: "nutrientsPerMeal",
    get: function get() {
      return this._nutrientsPerMeal;
    }
  }, {
    key: "nutrientsPerMealIndex",
    get: function get() {
      return this._nutrientsPerMealIndex;
    },
    set: function set(index) {
      this._nutrientsPerMealIndex = index;
    }
  }, {
    key: "nutrientsPerDayValue",
    set: function set(nutrientValue) {
      this._nutrientsPerDay[this.dayKey][this.nutrientKey] = nutrientValue;
    }
  }, {
    key: "nutrientsPerMealValue",
    set: function set(nutrientValue) {
      this._nutrientsPerMeal[this.nutrientsPerMealIndex][this.nutrientKey] = nutrientValue;
    }
  }, {
    key: "addNutrientsTabToggleListeners",
    value: function addNutrientsTabToggleListeners() {
      var _this2 = this;
      var dayNutrientsElts = document.getElementsByClassName('day-nutrients');
      var wrapBtns = document.getElementsByClassName('wrap-btn');
      Object.keys(dayNutrientsElts).forEach(function (dayNutrientsIndex) {
        dayNutrientsElts[dayNutrientsIndex].addEventListener('click', function (e) {
          var dayNutrients = e.target.closest('.day-nutrients');
          var dailyProgramList = dayNutrients.parentNode.getElementsByClassName('hidden')[0];
          dayNutrients.classList.add('hidden');
          dailyProgramList.style.opacity = 0;
          dailyProgramList.classList.remove('hidden');
          _this2.fadeInItem(dailyProgramList, 4000, 1);
        });
      });
      Object.keys(wrapBtns).forEach(function (dailyProgramListIndex) {
        wrapBtns[dailyProgramListIndex].addEventListener('click', function (e) {
          var dailyProgramList = e.target.closest('.daily-program-list');
          var dayNutrients = dailyProgramList.parentNode.getElementsByClassName('hidden')[0];
          dailyProgramList.classList.add('hidden');
          dayNutrients.classList.remove('hidden');
        });
      });
    }
  }, {
    key: "buildDailyNutrientsData",
    value: function buildDailyNutrientsData() {
      var _this3 = this;
      //
      this.initNutrientsPerDayEntry();
      Object.keys(this.dailyMealsParsedData).forEach(function (mealKey) {
        // console.log('Nouveau repas : ' + mealKey);
        _this3.mealKey = mealKey;
        var mealData = _this3.dailyMealsParsedData[_this3.mealKey];
        _this3.buildMealNutrientsData(mealData);
      });
    }
  }, {
    key: "buildIngredientsNutrientsData",
    value: function buildIngredientsNutrientsData(ingredientData) {
      var _this4 = this;
      Object.keys(this.nutrientsList).forEach(function (nutrientKey) {
        _this4.nutrientKey = _this4.nutrientsList[nutrientKey];
        var rationNutrientValue = _this4.getNutrientValue(ingredientData);

        // console.log('Nouveau nutriment (' + this.nutrientsList[nutrientKey] + ') : ' + rationNutrientValue);

        _this4.updateNutrientsPerMealData(rationNutrientValue);
        _this4.updateNutrientsPerDayData(rationNutrientValue);
      });
    }
  }, {
    key: "buildMealNutrientsData",
    value: function buildMealNutrientsData(mealData) {
      var _this5 = this;
      this.initNutrientsPerMealEntry();
      Object.keys(mealData).forEach(function (ingredientKey) {
        // console.log('-- Nouvel ingrédient : ' + mealData[ingredientKey]['ingr_name'] + ' --');
        // console.log(mealData[ingredientKey]);
        var ingredientData = mealData[ingredientKey];
        _this5.buildIngredientsNutrientsData(ingredientData);
      });
    }
  }, {
    key: "buildNutrientsTable",
    value: function buildNutrientsTable(nutrientsTab, dataTabElt, dataTabKey) {
      var tableBodyElt = document.createElement('tbody');
      var firstNutrientsRow = document.createElement('tr');
      var secondNutrientsRow = document.createElement('tr');
      var thirdNutrientsRow = document.createElement('tr');
      var fourthNutrientsRow = document.createElement('tr');
      var caloriesTdElt = document.createElement('td');
      caloriesTdElt.innerText = nutrientsTab.hasOwnProperty(dataTabKey) ? nutrientsTab[dataTabKey]['calories'].toFixed(0) + ' calories' : '0 calories';
      caloriesTdElt.setAttribute('colspan', 2);
      var proteinsTdElt = document.createElement('td');
      proteinsTdElt.innerText = nutrientsTab.hasOwnProperty(dataTabKey) ? nutrientsTab[dataTabKey]['proteins'].toFixed(0) + ' g protéines' : '0g protéines';
      var carbsTdElt = document.createElement('td');
      carbsTdElt.innerText = nutrientsTab.hasOwnProperty(dataTabKey) ? nutrientsTab[dataTabKey]['carbs'].toFixed(0) + ' g glucides' : '0g glucides';
      var fatTdElt = document.createElement('td');
      fatTdElt.innerText = nutrientsTab.hasOwnProperty(dataTabKey) ? nutrientsTab[dataTabKey]['fat'].toFixed(0) + ' g lipides' : '0g lipides';
      var sugarTdElt = document.createElement('td');
      sugarTdElt.innerText = nutrientsTab.hasOwnProperty(dataTabKey) ? nutrientsTab[dataTabKey]['sugar'].toFixed(0) + ' g sucre' : '0g sucre';
      var fibersTdElt = document.createElement('td');
      fibersTdElt.innerText = nutrientsTab.hasOwnProperty(dataTabKey) ? nutrientsTab[dataTabKey]['fibers'].toFixed(0) + ' g fibres' : '0g fibres';
      var sodiumTdElt = document.createElement('td');
      sodiumTdElt.innerText = nutrientsTab.hasOwnProperty(dataTabKey) ? nutrientsTab[dataTabKey]['sodium'].toFixed(0) + ' mg sodium' : '0mg sodium';
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
    key: "buildProgramArrays",
    value: function buildProgramArrays() {
      var _this6 = this;
      this.dailyProgramItems = document.getElementsByClassName('daily-program-list');
      Object.keys(this.dailyProgramItems).forEach(function (dayKey) {
        // console.log('Nouveau jour : ' + dayKey);
        _this6.dayKey = dayKey;
        _this6.gatherProgramMealsData();
        _this6.buildDailyNutrientsData();
      });
    }
  }, {
    key: "computeNewNutrientPerDayValue",
    value: function computeNewNutrientPerDayValue(rationNutrientValue) {
      var oldNutrientValue = this.nutrientsPerDay[this.dayKey][this.nutrientKey];
      return typeof oldNutrientValue === 'number' && typeof rationNutrientValue === 'number' ? oldNutrientValue + rationNutrientValue : 'Missing data';
    }
  }, {
    key: "computeNewNutrientPerMealValue",
    value: function computeNewNutrientPerMealValue(rationNutrientValue) {
      var oldNutrientValue = this.nutrientsPerMeal[this.nutrientsPerMealIndex][this.nutrientKey];
      return typeof oldNutrientValue === 'number' && typeof rationNutrientValue === 'number' ? oldNutrientValue + rationNutrientValue : 'Missing data';
    }
  }, {
    key: "displayDailyDataTab",
    value: function displayDailyDataTab() {
      var _this7 = this;
      Object.keys(this.dayDataTabs).forEach(function (dayDataTabKey) {
        var dayDataTabElt = _this7.dayDataTabs[dayDataTabKey];
        _this7.buildNutrientsTable(_this7.nutrientsPerDay, dayDataTabElt, dayDataTabKey);
      });
    }
  }, {
    key: "displayMealsDataTab",
    value: function displayMealsDataTab() {
      var _this8 = this;
      Object.keys(this.mealDataTabs).forEach(function (mealDataTabKey) {
        var mealDataTabElt = _this8.mealDataTabs[mealDataTabKey];
        _this8.buildNutrientsTable(_this8.nutrientsPerMeal, mealDataTabElt, mealDataTabKey);
      });
    }
  }, {
    key: "displayProgramData",
    value: function displayProgramData() {
      this.displayMealsDataTab();
      this.displayDailyDataTab();
      this.hideMealTables();
      this.addNutrientsTabToggleListeners();
    }
  }, {
    key: "gatherProgramMealsData",
    value: function gatherProgramMealsData() {
      var mealElt = this.dailyProgramItems[this.dayKey];
      this.dailyMealsParsedData = mealElt.getAttribute('data-daily-program');
    }
  }, {
    key: "getNutrientValue",
    value: function getNutrientValue(ingredientData) {
      // console.log(this.nutrientKey);
      var isNutrientValueSet = ingredientData[this.nutrientKey] !== '-1';
      var isNutrientInGrams = ingredientData['measure'] === 'grammes';
      return isNutrientValueSet ? isNutrientInGrams ? ingredientData[this.nutrientKey] / 100 * ingredientData['quantity'] : ingredientData[this.nutrientKey] * ingredientData['quantity'] : 'Missing data';
    }
  }, {
    key: "hideMealTables",
    value: function hideMealTables() {
      var dailyProgramLists = document.getElementsByClassName('daily-program-list');
      Object.keys(dailyProgramLists).forEach(function (mealItemKey) {
        dailyProgramLists[mealItemKey].classList.add('hidden');
      });
    }
  }, {
    key: "init",
    value: function init() {
      this.buildProgramArrays();
      this.displayProgramData();
    }
  }, {
    key: "initNutrientsPerDayEntry",
    value: function initNutrientsPerDayEntry() {
      var _this9 = this;
      this.nutrientsPerDay[this.dayKey] = {};
      this.nutrientsList.forEach(function (nutrient) {
        _this9._nutrientsPerDay[_this9.dayKey][nutrient] = _this9.nutrientDefaultValue;
      });
    }
  }, {
    key: "initNutrientsPerMealEntry",
    value: function initNutrientsPerMealEntry() {
      var _this10 = this;
      this.nutrientsPerMealIndex = parseInt(this.dayKey) * Object.keys(this.dailyMealsParsedData).length + parseInt(this.mealKey) - 1;
      this.nutrientsPerMeal[this.nutrientsPerMealIndex] = {};
      this.nutrientsList.forEach(function (nutrient) {
        _this10._nutrientsPerMeal[_this10.nutrientsPerMealIndex][nutrient] = _this10.nutrientDefaultValue;
      });
    }
  }, {
    key: "updateNutrientsPerDayData",
    value: function updateNutrientsPerDayData(rationNutrientValue) {
      var newNutrientsPerDayValue = this.computeNewNutrientPerDayValue(rationNutrientValue);
      this.nutrientsPerDayValue = newNutrientsPerDayValue;
    }
  }, {
    key: "updateNutrientsPerMealData",
    value: function updateNutrientsPerMealData(rationNutrientValue) {
      this.nutrientsPerMealValue = this.computeNewNutrientPerMealValue(rationNutrientValue);
    }
  }]);
  return ProgramDisplayer;
}(ElementFader);
