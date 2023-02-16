class MealNutrientsDisplayer {
    constructor() {
        this._nutrientDefaultValue = 0;
        this._nutrientsList = ['calories', 'carbs', 'fat', 'proteins', 'sodium', 'potassium', 'fibers', 'sugar'];
        
        this._mealParsedData;
        this._tableItem;

        this._nutrientKey;

        this._nutrientsPerMeal = {};
    }

    get nutrientKey() {
        return this._nutrientKey;
    }

    get nutrientDefaultValue() {
        return this._nutrientDefaultValue;
    }

    get nutrientsList() {
        return this._nutrientsList;
    }

    get nutrientsPerMeal() {
        return this._nutrientsPerMeal;
    }

    get tableItem() {
        return this._tableItem;
    }
    
    get mealParsedData() {
        return this._mealParsedData;
    }

    set nutrientKey(nutrientKey) {
        this._nutrientKey = nutrientKey;
    }
    
    set mealParsedData(dailyMealsData) {
        this._mealParsedData = JSON.parse(dailyMealsData);
    }
    
    set tableItem(tableElt) {
        this._tableItem = tableElt;
    }
    
    set nutrientsPerMealValue(nutrientValue) {
        this._nutrientsPerMeal[this.nutrientKey] = nutrientValue;
    }

    init() {
        this.buildMealArray();
        this.buildNutrientsTable(this.nutrientsPerMeal, this.tableItem);
    }

    buildNutrientsTable(nutrientsTab, dataTabElt) {
        const tableBodyElt = document.createElement('tbody');
        
        const firstNutrientsRow = document.createElement('tr');
        const secondNutrientsRow = document.createElement('tr');
        const thirdNutrientsRow = document.createElement('tr');
        const fourthNutrientsRow = document.createElement('tr');
        
        const caloriesTdElt = document.createElement('td');
        
        caloriesTdElt.innerText = nutrientsTab['calories'].toFixed(0) + ' calories';
        caloriesTdElt.setAttribute('colspan', 2);
        const proteinsTdElt = document.createElement('td');
        proteinsTdElt.innerText = nutrientsTab['proteins'].toFixed(0) + 'g protéines';
        const carbsTdElt = document.createElement('td');
        carbsTdElt.innerText = nutrientsTab['carbs'].toFixed(0) + 'g glucides';
        const fatTdElt = document.createElement('td');
        fatTdElt.innerText = nutrientsTab['fat'].toFixed(0) + 'g lipides';
        
        const sugarTdElt = document.createElement('td');
        sugarTdElt.innerText = nutrientsTab['sugar'].toFixed(0) + 'g sucre';
        const fibersTdElt = document.createElement('td');
        fibersTdElt.innerText = nutrientsTab['fibers'].toFixed(0) + 'g fibres';
        const sodiumTdElt = document.createElement('td');
        sodiumTdElt.innerText = nutrientsTab['sodium'].toFixed(0) + 'mg sodium';
        
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

    buildMealArray() {
        this.gatherMealData();
        this.buildMealNutrientsData(this.mealParsedData);
    }

    gatherMealData() {
        this.tableItem = document.getElementsByClassName('meal-nutrients-table')[0];
        this.mealParsedData = this.tableItem.getAttribute('data-daily-program')
    }

    buildMealNutrientsData(mealData) {
        this.initNutrientsPerMealEntry();

        Object.keys(mealData).forEach(ingredientKey => {
            const ingredientData = mealData[ingredientKey];
            
            this.buildIngredientsNutrientsData(ingredientData);
        });
    }

    initNutrientsPerMealEntry() {
        this.nutrientsList.forEach((nutrient) => {
            this.nutrientsPerMeal[nutrient] = this.nutrientDefaultValue;
        })
    }

    buildIngredientsNutrientsData(ingredientData) {
        Object.keys(this.nutrientsList).forEach(nutrientKey => {
            this.nutrientKey = this.nutrientsList[nutrientKey];
            const rationNutrientValue = this.getNutrientValue(ingredientData);
            
            this.updateNutrientsPerMealData(rationNutrientValue);
        });
    }

    getNutrientValue(ingredientData) {
        let isNutrientValueSet = ingredientData[this.nutrientKey] !== '-1';
        let isNutrientInGrams = ingredientData['measure'] === 'grammes';
        let isBaseValueNotNull = ingredientData['measure_base_value'] !== '0';
        
        return isNutrientValueSet ? isNutrientInGrams ? isBaseValueNotNull ? ingredientData[this.nutrientKey] / ingredientData['measure_base_value'] * ingredientData['quantity'] : 'Missing data' : ingredientData[this.nutrientKey] * ingredientData['quantity'] : 'Missing data';
    }

    updateNutrientsPerMealData(rationNutrientValue) {
        this.nutrientsPerMealValue = this.computeNewNutrientPerMealValue(rationNutrientValue);
    }

    computeNewNutrientPerMealValue(rationNutrientValue) {
        let oldNutrientValue = this.nutrientsPerMeal[this.nutrientKey];

        return typeof oldNutrientValue === 'number' && typeof rationNutrientValue === 'number' ? oldNutrientValue + rationNutrientValue : 'Missing data';
    }
}