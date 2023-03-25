class ProgramDisplayer extends ElementFader {
    constructor() {
        super();
        
        this._nutrientDefaultValue = 0;
        this._nutrientsList = ['calories', 'carbs', 'fat', 'proteins', 'sodium', 'fibers', 'sugar'];
        
        this._dayDataTabs = document.getElementsByClassName('day-data-tab');
        this._mealDataTabs = document.getElementsByClassName('meal-data-tab');
        
        this._dailyMealsParsedData;
        this._dailyProgramItems;
        
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
    
    get dailyProgramItems() {
        return this._dailyProgramItems;
    }
    
    get dayDataTabs() {
        return this._dayDataTabs;
    }
    
    get dayKey() {
        return this._dayKey;
    }
    
    get mealDataTabs() {
        return this._mealDataTabs;
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
    
    set dailyMealsParsedData(dailyMealsData) {
        this._dailyMealsParsedData = JSON.parse(dailyMealsData);
    }
    
    set dailyProgramItems(weeklyMealsElts) {
        this._dailyProgramItems = weeklyMealsElts;
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
    
    set nutrientsPerDayValue(nutrientValue) {
        this._nutrientsPerDay[this.dayKey][this.nutrientKey] = nutrientValue;
    }
    
    set nutrientsPerMealValue(nutrientValue) {
        this._nutrientsPerMeal[this.nutrientsPerMealIndex][this.nutrientKey] = nutrientValue;
    }
    
    set nutrientsPerMealIndex(index) {
        this._nutrientsPerMealIndex = index;
    }
    
    addNutrientsTabToggleListeners() {
        const dayNutrientsElts = document.getElementsByClassName('day-nutrients');
        const wrapBtns = document.getElementsByClassName('wrap-btn');
        
        Object.keys(dayNutrientsElts).forEach(dayNutrientsIndex => {
            dayNutrientsElts[dayNutrientsIndex].addEventListener('click', (e) => {
                let dayNutrients = e.target.closest('.day-nutrients');
                let dailyProgramList = dayNutrients.parentNode.getElementsByClassName('hidden')[0];
                
                dayNutrients.classList.add('hidden');
                dailyProgramList.style.opacity = 0;
                dailyProgramList.classList.remove('hidden');
                this.fadeInItem(dailyProgramList, 4000, 1);
            })
        });
        
        Object.keys(wrapBtns).forEach(dailyProgramListIndex => {
            wrapBtns[dailyProgramListIndex].addEventListener('click', (e) => {
                let dailyProgramList = e.target.closest('.daily-program-list');
                let dayNutrients = dailyProgramList.parentNode.getElementsByClassName('hidden')[0];
                
                dailyProgramList.classList.add('hidden');
                dayNutrients.classList.remove('hidden');
            })
        });
    }
    
    buildDailyNutrientsData() {
        this.initNutrientsPerDayEntry();
        
        Object.keys(this.dailyMealsParsedData).forEach(mealKey => {
            console.log('Nouveau repas : ' + mealKey);
            this.mealKey = mealKey;
            
            let mealData = this.dailyMealsParsedData[this.mealKey];
            this.buildMealNutrientsData(mealData);
        });
    }
    
    buildIngredientsNutrientsData(ingredientData) {
        Object.keys(this.nutrientsList).forEach(nutrientKey => {
            this.nutrientKey = this.nutrientsList[nutrientKey];
            const rationNutrientValue = this.getNutrientValue(ingredientData);

            console.log('Nouveau nutriment (' + this.nutrientsList[nutrientKey] + ') : ' + rationNutrientValue);
            
            this.updateNutrientsPerMealData(rationNutrientValue);
            this.updateNutrientsPerDayData(rationNutrientValue);
        });
    }
    
    buildMealNutrientsData(mealData) {
        this.initNutrientsPerMealEntry();
        
        Object.keys(mealData).forEach(ingredientKey => {
            console.log('-- Nouvel ingrédient : ' + mealData[ingredientKey]['name'] + ' --');
            console.log(mealData[ingredientKey]);
            const ingredientData = mealData[ingredientKey];
            
            this.buildIngredientsNutrientsData(ingredientData);
        });
    }
    
    buildNutrientsTable(nutrientsTab, dataTabElt, dataTabKey) {
        const tableBodyElt = document.createElement('tbody');
        
        const firstNutrientsRow = document.createElement('tr');
        const secondNutrientsRow = document.createElement('tr');
        const thirdNutrientsRow = document.createElement('tr');
        const fourthNutrientsRow = document.createElement('tr');
        
        const caloriesTdElt = document.createElement('td');
        
        caloriesTdElt.innerText = nutrientsTab.hasOwnProperty(dataTabKey) ? nutrientsTab[dataTabKey]['calories'].toFixed(0) + ' calories' : '0 calories';
        caloriesTdElt.setAttribute('colspan', 2);
        const proteinsTdElt = document.createElement('td');
        proteinsTdElt.innerText = nutrientsTab.hasOwnProperty(dataTabKey) ? nutrientsTab[dataTabKey]['proteins'].toFixed(0) + ' g protéines' : '0g protéines';
        const carbsTdElt = document.createElement('td');
        carbsTdElt.innerText = nutrientsTab.hasOwnProperty(dataTabKey) ? nutrientsTab[dataTabKey]['carbs'].toFixed(0) + ' g glucides' : '0g glucides';
        const fatTdElt = document.createElement('td');
        fatTdElt.innerText = nutrientsTab.hasOwnProperty(dataTabKey) ? nutrientsTab[dataTabKey]['fat'].toFixed(0) + ' g lipides' : '0g lipides';
        
        const sugarTdElt = document.createElement('td');
        sugarTdElt.innerText = nutrientsTab.hasOwnProperty(dataTabKey) ? nutrientsTab[dataTabKey]['sugar'].toFixed(0) + ' g sucre' : '0g sucre';
        const fibersTdElt = document.createElement('td');
        fibersTdElt.innerText = nutrientsTab.hasOwnProperty(dataTabKey) ? nutrientsTab[dataTabKey]['fibers'].toFixed(0) + ' g fibres' : '0g fibres';
        const sodiumTdElt = document.createElement('td');
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
    
    buildProgramArrays() {
        this.dailyProgramItems = document.getElementsByClassName('daily-program-list');
        
        Object.keys(this.dailyProgramItems).forEach(dayKey => {
            console.log('Nouveau jour : ' + dayKey);
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
    
    displayDailyDataTab() {
        Object.keys(this.dayDataTabs).forEach(dayDataTabKey => {
            let dayDataTabElt = this.dayDataTabs[dayDataTabKey];
            this.buildNutrientsTable(this.nutrientsPerDay, dayDataTabElt, dayDataTabKey);
        });
    }
    
    displayMealsDataTab() {
        Object.keys(this.mealDataTabs).forEach(mealDataTabKey => {
            let mealDataTabElt = this.mealDataTabs[mealDataTabKey];
            this.buildNutrientsTable(this.nutrientsPerMeal, mealDataTabElt, mealDataTabKey);
        });
    }
    
    displayProgramData() {
        this.displayMealsDataTab();
        this.displayDailyDataTab();
        this.hideMealTables();
        this.addNutrientsTabToggleListeners();
    }
    
    gatherProgramMealsData() {
        let mealElt = this.dailyProgramItems[this.dayKey];

        this.dailyMealsParsedData = mealElt.getAttribute('data-daily-program');
    }
    
    getNutrientValue(ingredientData) {
        let isNutrientValueSet = ingredientData[this.nutrientKey] !== '-1';
        let isNutrientInGrams = ingredientData['measure'] === 'grammes';
        
        return isNutrientValueSet ? isNutrientInGrams ?  ingredientData[this.nutrientKey] / 100 * ingredientData['ingr_quantity'] : ingredientData[this.nutrientKey] * ingredientData['ingr_quantity'] : 'Missing data';
    }
    
    hideMealTables() {
        let dailyProgramLists = document.getElementsByClassName('daily-program-list');
        
        Object.keys(dailyProgramLists).forEach(mealItemKey => {
            dailyProgramLists[mealItemKey].classList.add('hidden');
        });
    }
    
    init() {
        this.buildProgramArrays();
        this.displayProgramData();
    }
    
    initNutrientsPerDayEntry() {
        this.nutrientsPerDay[this.dayKey] = {};
        
        this.nutrientsList.forEach((nutrient) => {
            this._nutrientsPerDay[this.dayKey][nutrient] = this.nutrientDefaultValue;
        });
    }
    
    initNutrientsPerMealEntry() {
        this.nutrientsPerMealIndex = (parseInt(this.dayKey) * Object.keys(this.dailyMealsParsedData).length + parseInt(this.mealKey)) - 1;
        this.nutrientsPerMeal[this.nutrientsPerMealIndex] = {};
        
        this.nutrientsList.forEach((nutrient) => {            
            this._nutrientsPerMeal[this.nutrientsPerMealIndex][nutrient] = this.nutrientDefaultValue;
        });
    }
    
    updateNutrientsPerDayData(rationNutrientValue) {
        let newNutrientsPerDayValue = this.computeNewNutrientPerDayValue(rationNutrientValue);
        this.nutrientsPerDayValue = newNutrientsPerDayValue;
    }
    
    updateNutrientsPerMealData(rationNutrientValue) {
        this.nutrientsPerMealValue = this.computeNewNutrientPerMealValue(rationNutrientValue);
    }
}