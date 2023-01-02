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

        // Contains an array of intakes for every day, with a index from 0 to 6 (or can be from "lundi" to "dimanche")
        // In this array, meals intakes from the same day are cumulated
        this._nutrientsPerDay = {};
        this._nutrientsPerDayIndex;

        // Contains an array of intakes for every meals, with a index from 0 to 27 (based on a collection of 4 meals for a 7 days program)
        this._nutrientsPerMeal = {};
        this._nutrientsPerMealIndex;

        // Variable allowing to get the day french name as a key
        this._weekDays = ['lundi', 'mardi', 'mercredi', 'jeudi', 'vendredi', 'samedi', 'dimanche'];
    }

    get nutrientDefaultValue() {
        return this._nutrientDefaultValue;
    }

    get nutrientsPerDayIndex() {
        return this._nutrientsPerDayIndex;
    }

    get nutrientsPerMealIndex() {
        return this._nutrientsPerMealIndex;
    }

    get nutrientsList() {
        return this._nutrientsList;
    }

    get dayKey() {
        return this._dayKey;
    }

    get mealKey() {
        return this._mealKey;
    }

    get nutrientKey() {
        return this._nutrientKey;
    }

    get nutrientsPerDay() {
        return this._nutrientsPerDay;
    }

    get nutrientsPerMeal() {
        return this._nutrientsPerMeal;
    }

    get weekDays() {
        return this._weekDays;
    }

    get dailyMealsParsedData() {
        return this._dailyMealsParsedData;
    }

    get programMealsItems() {
        return this._programMealsItems;
    }

    set nutrientsPerMealIndex(index) {
        this._nutrientsPerMealIndex = index;
    }

    set mealKey(mealKey) {
        this._mealKey = mealKey;
    }

    set dayKey(dayKey) {
        this._dayKey = dayKey;
    }

    set nutrientKey(nutrientKey) {
        this._nutrientKey = nutrientKey;
    }

    set programMealsItems(weeklyMealsElts) {
        this._programMealsItems = weeklyMealsElts;
    }

    set dailyMealsParsedData(dailyMealsData) {
        this._dailyMealsParsedData = JSON.parse(dailyMealsData);
    }

    set nutrientPerMealValue(nutrientValue) {
        this._nutrientsPerMeal[this.nutrientsPerMealIndex][this.nutrientKey] = nutrientValue;
    }

    initNutrientsPerMealEntry() {
        console.log("On traite un nouveau menu, donc on initialise les valeurs des nutriments")
        this.nutrientsPerMealIndex = this.computeNutrientsPerMealIndex();
        this.nutrientsPerMeal[this.nutrientsPerMealIndex] = {}

        this.nutrientsList.forEach((nutrient) => {            
            this._nutrientsPerMeal[this.nutrientsPerMealIndex][nutrient] = this.nutrientDefaultValue;
        });
    }

    init() {
        this.programMealsItems = document.getElementsByClassName('program-meals-list');

        if (this.programMealsItems) {
            this.buildProgramArrays();
        }
    }

    buildProgramArrays() {
        Object.keys(this.programMealsItems).forEach(dayKey => {
            this.dayKey = dayKey;
            this.gatherProgramMealsData();
            this.buildDailyNutrientsData();
        });

        // Notes de suivi
        console.log('Fin du traitement des données. Résultats ... this.nutrientsPerMeal vaut :')
        console.log(this.nutrientsPerMeal)
    }

    gatherProgramMealsData() {
        let mealElt = this.programMealsItems[this.dayKey];
        this.dailyMealsParsedData = mealElt.getAttribute('data-meals');
    }

    buildDailyNutrientsData() {
        Object.keys(this.dailyMealsParsedData).forEach(mealKey => {
            this.mealKey = mealKey;
            let mealData = this.dailyMealsParsedData[this.mealKey];

            this.buildMealNutrientsData(mealData);
            
            // Notes de suivi            
            console.log('***************************************************************************');
            console.log('***************************************************************************');
            console.log("Fin du traitement d'un repas. On stock les nutriments de chaque repas dans un array".toUpperCase())
        });
        
        // Notes de suivi
        console.log('**************************************************************');
        console.log('**************************************************************');
        console.log('*************     Fin des data de la journée     *************'.toUpperCase())
        console.log('**************************************************************');
        console.log('**************************************************************');
    }
    
    computeNutrientsPerMealIndex() {
        return (parseInt(this.dayKey) * this.dailyMealsParsedData.length + parseInt(this.mealKey));
    }

    buildMealNutrientsData(mealData) {
        this.initNutrientsPerMealEntry();

        Object.keys(mealData).forEach(ingredientKey => {
            this.ingredientKey = ingredientKey;
            const ingredientData = mealData[ingredientKey];

            console.log(" --- Début du traitement pour l'ingrédient " + ingredientData['french_name'] + " ---")
            console.log('-------------------------------------------------------')

            this.buildIngredientsNutrientsData(ingredientData);

            console.log('-------------------------------------------------------')
        })
    }

    buildIngredientsNutrientsData(ingredientData) {
        Object.keys(this.nutrientsList).forEach(nutrientKey => {
            this.nutrientKey = this.nutrientsList[nutrientKey]
            const rationNutrientValue = this.getNutrientValue(ingredientData)

            this.updateMealNutrients(rationNutrientValue);
        });
    }

    getNutrientValue(ingredientData) {
        console.log('On traite maintenant le nutriment ' + this.nutrientKey)
        console.log('La valeur de ce nutriment est : ' + ingredientData[this.nutrientKey])

        let isNutrientValueSet = ingredientData[this.nutrientKey] !== '-1';
        let isNutrientInGrams = ingredientData['measure'] === 'grammes';
        let isBaseValueNotNull = ingredientData['measure_base_value'] !== '0';

        return isNutrientValueSet ? isNutrientInGrams ? isBaseValueNotNull ? ingredientData[this.nutrientKey] / ingredientData['measure_base_value'] * ingredientData['quantity'] :'Missing data' : ingredientData[this.nutrientKey] * ingredientData['quantity'] : 'Missing data';
    }

    updateMealNutrients(rationNutrientValue) {
        let newNutientPerMealValue = this.computeNewNutrientPerMealValue(rationNutrientValue)

        this.nutrientPerMealValue = newNutientPerMealValue
    }
    
    computeNewNutrientPerMealValue(rationNutrientValue) {
        let oldNutrientValue = this.nutrientsPerMeal[this.nutrientsPerMealIndex][this.nutrientKey];

        console.log("Ancienne valeur de this.nutrientsPerMeal[" + this.nutrientsPerMealIndex + "][" + this.nutrientKey + "] : " + oldNutrientValue);
        
        return typeof oldNutrientValue === 'number' && typeof rationNutrientValue === 'number' ? oldNutrientValue + rationNutrientValue : 'Missing data';
    }
}