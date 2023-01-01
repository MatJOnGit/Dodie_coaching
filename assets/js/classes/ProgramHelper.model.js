class ProgramHelper extends UserPanels {
    constructor() {
        super();

        this._nutrientDefaultValue = 0;

        this._programMealsItems;
        this._dailyMealsParsedData;

        // Contains an array of intakes for every day, with a index from 0 to 6 (or can be from "lundi" to "dimanche")
        // In this array, meals intakes from the same day are cumulated
        this._nutrientsPerDay = {};

        // Contains an array of intakes for every meals, with a index from 0 to 27 (based on a collection of 4 meals for a 7 days program)
        this._nutrientsPerMeal = {};

        // Buffer variables
        this._dailyNutrients = [];
        this._mealNutrients = {
            'calories': 0,
            'carbs': 0,
            'fat': 0,
            'fibers': 0,
            'potassium': 0,
            'proteins': 0,
            'sodium': 0,
            'sugar': 0
        };

        // Variable allowing to get the day french name as a key
        this._weekDays = ['lundi', 'mardi', 'mercredi', 'jeudi', 'vendredi', 'samedi', 'dimanche'];
    }

    get nutrientsPerMeal() {
        return this._nutrientsPerMeal;
    }

    get nutrientDefaultValue() {
        return this._nutrientDefaultValue;
    }

    get dailyNutrients() {
        return this._dailyNutrients;
    }

    get mealNutrients() {
        return this._mealNutrients;
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

    set programMealsItems(weeklyMealsElts) {
        this._programMealsItems = weeklyMealsElts;
    }

    set dailyMealsParsedData(dailyMealsData) {
        this._dailyMealsParsedData = JSON.parse(dailyMealsData);
    }

    setDailyNutrientsValues(mealKey, nutrientsValues) {
        this._dailyNutrients[mealKey] = nutrientsValues;
    }

    setMealNutrientValue(nutrientKey, nutrientValue) {
        this._mealNutrients[nutrientKey] = nutrientValue;
    }

    addDailyNutrients(dailyNutrients) {
        this._weeklyNutrients += dailyNutrients;
    }

    initMealNutrientsTab(mealData, dayKey, mealKey) {
        console.log(('Nouveau repas du ' + this.weekDays[dayKey] + '. On réinitialise la valeur de this.mealNutrients').toUpperCase());
        
        Object.keys(this.mealNutrients).forEach(nutrientKey => {
            this.mealNutrients[nutrientKey] = this.nutrientDefaultValue;
        })

        // console.log(this.mealNutrients)
        console.log('***************************************************************************');

    }

    init() {
        this.programMealsItems = document.getElementsByClassName('program-meals-list');

        if (this.programMealsItems) {
            this.buildNutrientsData();
            // this.testnutrientsPerMeal();
        }
    }

    gatherProgramMealsData(dayKey) {
        let mealElt = this.programMealsItems[dayKey];
        this.dailyMealsParsedData = mealElt.getAttribute('data-meals');
    }

    buildNutrientsData() {
        Object.keys(this.programMealsItems).forEach(dayKey => {
            this.gatherProgramMealsData(dayKey);
            this.buildDailyNutrientsData(dayKey);
        });

        // Notes de suivi
        console.log('Fin du traitement des données. Résultats ... this.nutrientsPerMeal vaut :')
        console.log(this.nutrientsPerMeal)
        // console.log("Test d'accès aux nutriments du petit déjeuner du lundi (indice 0) :")
        // console.log(this.weeklyCumulatedNutrients[0])
        // console.log("Test d'accès aux calories du petit déjeuner du lundi :")
        // console.log(this.weeklyCumulatedNutrients[0]['calories'])
    }

    buildDailyNutrientsData(dayKey) {
        Object.keys(this.dailyMealsParsedData).forEach(mealKey => {
            let mealData = this.dailyMealsParsedData[mealKey];

            this.initMealNutrientsTab(mealData, dayKey, mealKey);
            this.buildMealNutrientsData(mealData);
            
            // Notes de suivi
            console.log('Cumul des nutriments pour le repas #' + mealKey + ' du ' + this.weekDays[dayKey] + ' en cours ... ')
            console.log("Résultat ... this.mealNutrients['calories'] vaut :")

            console.log('Fin des data du repas'.toUpperCase())
                        
            // la fonction suivante doit push mealNutrients dans un array qui contiendras les apports de chaque repas, sans nom de repas associé (juste un indice). Pour quatre repas par jour, on aura donc des indices variants de 0 à 27.
            this.fillNutrientsPerMeal(this.mealNutrients, dayKey, mealKey);

            console.log("On a sauvegardé les données des nutriments")
            
            // console.log(this.weeklyCumulatedNutrients);
            console.log(' ')
            console.log('***************************************************************************');
            console.log('***************************************************************************');
            console.log("Fin du traitement d'un repas. On stock les nutriments de chaque repas dans un array ")
        });
        
        // Notes de suivi
        console.log('**************************************************************');
        console.log('**************************************************************');
        console.log('*************     Fin des data de la journée     *************'.toUpperCase())
        console.log('**************************************************************');
        console.log('**************************************************************');
    }

    buildMealNutrientsData(mealData) {
        Object.keys(mealData).forEach(ingredientKey => {
            const ingredientData = mealData[ingredientKey];

            console.log(" --- Début du traitement pour l'ingrédient " + ingredientData['french_name'] + " ---")
            console.log('-------------------------------------------------------')

            Object.keys(this._mealNutrients).forEach(nutrientKey => {
                const rationNutrientValue = this.getNutrientValue(ingredientData, nutrientKey)

                this.updateMealNutrients(rationNutrientValue, nutrientKey);
                // this.updateDailyNutrients(rationNutrientValue, nutrientKey, )
            });

            console.log('-------------------------------------------------------')
        })
    }

    updateMealNutrients(rationNutrientValue, nutrientKey) {
        let cumulatedNutrientValue = this.getCumulatedNutrientValue(this.mealNutrients[nutrientKey], rationNutrientValue)

        console.log("Ancienne valeur de " + nutrientKey + " : " + this.mealNutrients[nutrientKey])

        console.log("Valeur à ajouter : " + rationNutrientValue);

        this.setMealNutrientValue(nutrientKey, cumulatedNutrientValue)

        console.log("Nouvelle valeur de " + nutrientKey + " : " + this.mealNutrients[nutrientKey])
        console.log(this.mealNutrients)
    }

    getCumulatedNutrientValue(initialNutrientTabValue, newNutientValue) {
        return (typeof initialNutrientTabValue === 'number' && typeof newNutientValue === 'number' ? initialNutrientTabValue + newNutientValue : 'Missing data');
    }

    getNutrientValue(ingredient, nutrient) {
        console.log(ingredient)

        console.log(ingredient[nutrient])
        console.log('Valeur de la base :')
        console.log(ingredient['measure_base_value'])

        let isNutrientValueSet = ingredient[nutrient] !== '-1';
        let isNutrientInGrams = ingredient['measure'] === 'grammes';
        let isBaseValueNotNull = ingredient['measure_base_value'] !== '0';

        console.log(ingredient[nutrient])
        console.log(ingredient['quantity'])
        console.log(ingredient[nutrient] * ingredient['quantity'])

        return isNutrientValueSet ? isNutrientInGrams ? isBaseValueNotNull ? ingredient[nutrient] / ingredient['measure_base_value'] * ingredient['quantity'] :'Missing data' : ingredient[nutrient] * ingredient['quantity'] : 'Missing data';
    }

    computeNutrientsPerMealIndex(dayKey, mealKey) {
        return dayKey * this.dailyMealsParsedData.length + mealKey;
    }

    fillNutrientsPerMeal(mealNutrientValues, dayKey, mealKey) {
        console.log("Initialisation de la méthode this.fillWeeklyCumultar");

        console.log("Valeur initiale de l'array this._nutrientsPerMeal :");
        console.log(this.nutrientsPerMeal);

        console.log("Données à ajouter : ");
        console.log(mealNutrientValues);

        let nutrientsPerMealIndex = this.computeNutrientsPerMealIndex(parseInt(dayKey), parseInt(mealKey));

        console.log('Test')
        console.log(this.nutrientsPerMeal)

        this._nutrientsPerMeal[nutrientsPerMealIndex] = [];

        Object.keys(mealNutrientValues).forEach(nutrientKey => {
            this._nutrientsPerMeal[nutrientsPerMealIndex][nutrientKey] = mealNutrientValues[nutrientKey];
        });

        console.log(this.nutrientsPerMeal[nutrientsPerMealIndex]['calories']);

        console.log("Valeur finale de l'array this._nutrientsPerMeal : ");
        console.log(this.nutrientsPerMeal);
    }
}