class ProgramHelper extends UserPanels {
    constructor() {
        super();

        this._nutrientDefaultValue = 0;
        this._nutrientPerTableRow = 2;
        this._nutrientsList = ['calories', 'carbs', 'fat', 'proteins', 'sodium', 'potassium', 'fibers', 'sugar'];

        this._mealDataTabs = document.getElementsByClassName('meal-data-tab');
        this._dayDataTabs = document.getElementsByClassName('day-data-tab');

        this._dailyProgramItems;
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

    get dayDataTabs() {
        return this._dayDataTabs;
    }

    get dayKey() {
        return this._dayKey;
    }

    get mealKey() {
        return this._mealKey;
    }

    get mealDataTabs() {
        return this._mealDataTabs;
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

    get nutrientsPerTableRow() {
        return this._nutrientPerTableRow;
    }

    get dailyProgramItems() {
        return this._dailyProgramItems;
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

    set dailyProgramItems(weeklyMealsElts) {
        this._dailyProgramItems = weeklyMealsElts;
    }

    addNutrientsTabToggleListeners() {
        const dayNutrientsElts = document.getElementsByClassName('day-nutrients');
        const wrapBtns = document.getElementsByClassName('wrap-btn');

        Object.keys(dayNutrientsElts).forEach(dayNutrientsIndex => {
            dayNutrientsElts[dayNutrientsIndex].addEventListener('click', (e) => {
                let dayNutrients = e.target.closest('.day-nutrients');
                let dailyProgramList = dayNutrients.parentNode.getElementsByClassName('hidden')[0];

                dayNutrients.classList.add('hidden');
                dailyProgramList.classList.remove('hidden');
            })
        });

        Object.keys(wrapBtns).forEach(dailyProgramListIndex => {
            wrapBtns[dailyProgramListIndex].addEventListener('click', (e) => {
                let dailyProgramList = e.target.closest('.daily-program-list');
                let dayNutrients = dailyProgramList.parentNode.getElementsByClassName('hidden')[0];

                dailyProgramList.classList.add('hidden');
                dayNutrients.classList.remove('hidden');
            })
        })
    }

    manageDisplayedTable() {
        let dailyProgramLists = document.getElementsByClassName('daily-program-list');

        Object.keys(dailyProgramLists).forEach(mealItemKey => {
            dailyProgramLists[mealItemKey].classList.add('hidden');
        });
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

    buildNutrientsTable(nutrientsTab, dataTabElt, dataTabKey) {
        const tableBodyElt = document.createElement('tbody');
        
        const firstNutrientsRow = document.createElement('tr');
        const secondNutrientsRow = document.createElement('tr');
        const thirdNutrientsRow = document.createElement('tr');
        const fourthNutrientsRow = document.createElement('tr');

        const caloriesTdElt = document.createElement('td');
        caloriesTdElt.innerText = nutrientsTab[dataTabKey]['calories'].toFixed(0) + ' calories';
        caloriesTdElt.setAttribute('colspan', 2);
        const proteinsTdElt = document.createElement('td');
        proteinsTdElt.innerText = nutrientsTab[dataTabKey]['proteins'].toFixed(0) + 'g protéines';
        const carbsTdElt = document.createElement('td');
        carbsTdElt.innerText = nutrientsTab[dataTabKey]['carbs'].toFixed(0) + 'g glucides';
        const fatTdElt = document.createElement('td');
        fatTdElt.innerText = nutrientsTab[dataTabKey]['fat'].toFixed(0) + 'g lipides';
        
        const sugarTdElt = document.createElement('td');
        sugarTdElt.innerText = nutrientsTab[dataTabKey]['sugar'].toFixed(0) + 'g sucre';
        const fibersTdElt = document.createElement('td');
        fibersTdElt.innerText = nutrientsTab[dataTabKey]['fibers'].toFixed(0) + 'g fibres';
        const sodiumTdElt = document.createElement('td');
        sodiumTdElt.innerText = nutrientsTab[dataTabKey]['sodium'].toFixed(0) + 'mg sodium';

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

    buildProgramArrays() {
        Object.keys(this.dailyProgramItems).forEach(dayKey => {
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
        this.displayMealsDataTab();
        this.displayDailyDataTab();
        this.manageDisplayedTable();
        this.addNutrientsTabToggleListeners();
    }

    displayDailyDataTab() {
        Object.keys(this.dayDataTabs).forEach(dayDataTabKey => {
            let dayDataTabElt = this.dayDataTabs[dayDataTabKey];
            this.buildNutrientsTable(this.nutrientsPerDay, dayDataTabElt, dayDataTabKey);
        })
    }

    displayMealsDataTab() {
        Object.keys(this.mealDataTabs).forEach(mealDataTabKey => {
            let mealDataTabElt = this.mealDataTabs[mealDataTabKey];
            this.buildNutrientsTable(this.nutrientsPerMeal, mealDataTabElt, mealDataTabKey);
        });
    }

    gatherProgramMealsData() {
        let mealElt = this.dailyProgramItems[this.dayKey];
        this.dailyMealsParsedData = mealElt.getAttribute('data-daily-program');
    }

    getNutrientValue(ingredientData) {
        let isNutrientValueSet = ingredientData[this.nutrientKey] !== '-1';
        let isNutrientInGrams = ingredientData['measure'] === 'grammes';
        let isBaseValueNotNull = ingredientData['measure_base_value'] !== '0';

        return isNutrientValueSet ? isNutrientInGrams ? isBaseValueNotNull ? ingredientData[this.nutrientKey] / ingredientData['measure_base_value'] * ingredientData['quantity'] :'Missing data' : ingredientData[this.nutrientKey] * ingredientData['quantity'] : 'Missing data';
    }

    init() {
        this.dailyProgramItems = document.getElementsByClassName('daily-program-list');
        
        if (this.dailyProgramItems) {
            this.buildProgramArrays();
            this.displayProgramData();
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