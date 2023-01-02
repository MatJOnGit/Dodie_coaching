class ProgramHelper extends UserPanels {
    constructor() {
        super();

        this._nutrientDefaultValue = 0;
        this._nutrientsList = ['calories', 'carbs', 'fat', 'proteins', 'sodium', 'potassium', 'fibers', 'sugar'];

        this._programMealsItems;
        this._dailyMealsParsedData;

        this._dayKey;
        this._mealKey;
        this._nutrientKey;

        this._nutrientsPerDay = {};
        this._nutrientsPerMeal = {};
        this._nutrientsPerMealIndex;
    }

    get dailyMealsParsedData() {
        return this._dailyMealsParsedData;
    }

    get dayKey() {
        return this._dayKey;
    }

    get mealKey() {
        return this._mealKey;
    }

    get nutrientDefaultValue() {
        return this._nutrientDefaultValue;
    }

    get nutrientKey() {
        return this._nutrientKey;
    }

    get nutrientsList() {
        return this._nutrientsList;
    }

    get nutrientsPerDay() {
        return this._nutrientsPerDay;
    }

    get nutrientsPerMeal() {
        return this._nutrientsPerMeal;
    }

    get nutrientsPerMealIndex() {
        return this._nutrientsPerMealIndex;
    }

    get programMealsItems() {
        return this._programMealsItems;
    }

    set dailyMealsParsedData(dailyMealsData) {
        this._dailyMealsParsedData = JSON.parse(dailyMealsData);
    }

    set dayKey(dayKey) {
        this._dayKey = dayKey;
    }

    set mealKey(mealKey) {
        this._mealKey = mealKey;
    }

    set nutrientKey(nutrientKey) {
        this._nutrientKey = nutrientKey;
    }

    set nutrientPerDayValue(nutrientValue) {
        this._nutrientsPerDay[this.dayKey][this.nutrientKey] = nutrientValue;
    }

    set nutrientPerMealValue(nutrientValue) {
        this._nutrientsPerMeal[this.nutrientsPerMealIndex][this.nutrientKey] = nutrientValue;
    }

    set nutrientsPerMealIndex(index) {
        this._nutrientsPerMealIndex = index;
    }

    set programMealsItems(weeklyMealsElts) {
        this._programMealsItems = weeklyMealsElts;
    }

    buildDailyNutrientsData() {
        this.initNutrientsPerDayEntry()
        Object.keys(this.dailyMealsParsedData).forEach(mealKey => {
            this.mealKey = mealKey;
            let mealData = this.dailyMealsParsedData[this.mealKey];

            this.buildMealNutrientsData(mealData);
        });
    }

    buildIngredientsNutrientsData(ingredientData) {
        Object.keys(this.nutrientsList).forEach(nutrientKey => {
            this.nutrientKey = this.nutrientsList[nutrientKey];
            const rationNutrientValue = this.getNutrientValue(ingredientData);

            this.updateNutrientsPerMealData(rationNutrientValue);
            this.updateNutrientsPerDayData(rationNutrientValue);
        });
    }

    buildMealNutrientsData(mealData) {
        this.initNutrientsPerMealEntry();

        Object.keys(mealData).forEach(ingredientKey => {
            this.ingredientKey = ingredientKey;
            const ingredientData = mealData[ingredientKey];

            this.buildIngredientsNutrientsData(ingredientData);
        })
    }

    buildProgramArrays() {
        Object.keys(this.programMealsItems).forEach(dayKey => {
            this.dayKey = dayKey;
            this.gatherProgramMealsData();
            this.buildDailyNutrientsData();
        });
    }

    computeNewNutrientPerDayValue(rationNutrientValue) {
        let oldNutrientValue = this.nutrientsPerDay[this.dayKey][this.nutrientKey];

        return typeof oldNutrientValue === 'number' && typeof rationNutrientValue === 'number' ? oldNutrientValue + rationNutrientValue : 'Missing data';

    }
    
    computeNewNutrientPerMealValue(rationNutrientValue) {
        let oldNutrientValue = this.nutrientsPerMeal[this.nutrientsPerMealIndex][this.nutrientKey];

        return typeof oldNutrientValue === 'number' && typeof rationNutrientValue === 'number' ? oldNutrientValue + rationNutrientValue : 'Missing data';
    }

    displayProgramData() {
        console.log(this.nutrientsPerMeal);
        console.log(this.nutrientsPerDay);
    }

    gatherProgramMealsData() {
        let mealElt = this.programMealsItems[this.dayKey];
        this.dailyMealsParsedData = mealElt.getAttribute('data-meals');
    }

    getNutrientValue(ingredientData) {
        let isNutrientValueSet = ingredientData[this.nutrientKey] !== '-1';
        let isNutrientInGrams = ingredientData['measure'] === 'grammes';
        let isBaseValueNotNull = ingredientData['measure_base_value'] !== '0';

        return isNutrientValueSet ? isNutrientInGrams ? isBaseValueNotNull ? ingredientData[this.nutrientKey] / ingredientData['measure_base_value'] * ingredientData['quantity'] :'Missing data' : ingredientData[this.nutrientKey] * ingredientData['quantity'] : 'Missing data';
    }

    init() {
        this.programMealsItems = document.getElementsByClassName('program-meals-list');
        
        if (this.programMealsItems) {
            this.buildProgramArrays();
        }
    }

    initNutrientsPerDayEntry() {
        this.nutrientsPerDay[this.dayKey] = {};
        
        this.nutrientsList.forEach((nutrient) => {
            this._nutrientsPerDay[this.dayKey][nutrient] = this.nutrientDefaultValue;
        });
    }

    initNutrientsPerMealEntry() {
        this.nutrientsPerMealIndex = (parseInt(this.dayKey) * this.dailyMealsParsedData.length + parseInt(this.mealKey));
        this.nutrientsPerMeal[this.nutrientsPerMealIndex] = {};
        
        this.nutrientsList.forEach((nutrient) => {            
            this._nutrientsPerMeal[this.nutrientsPerMealIndex][nutrient] = this.nutrientDefaultValue;
        });
    }

    updateNutrientsPerDayData(rationNutrientValue) {
        let newNutrientPerDayValue = this.computeNewNutrientPerDayValue(rationNutrientValue);

        this.nutrientPerDayValue = newNutrientPerDayValue;
    }

    updateNutrientsPerMealData(rationNutrientValue) {
        let newNutientPerMealValue = this.computeNewNutrientPerMealValue(rationNutrientValue);

        this.nutrientPerMealValue = newNutientPerMealValue;
    }
}