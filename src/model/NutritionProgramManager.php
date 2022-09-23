<?php

require_once('./../src/model/Manager.php');

class NutritionProgramManager extends Manager {
    public function getMealDetails($day, $meal, $memberEmail) {
        $db = $this->dbConnect();
        $mealDetailsQuery = 'SELECT ingr.name, ingr.french_name, ingr.recipe, ingr.type, fp.quantity, ingr. measure_unit FROM food_plans fp INNER JOIN ingredients ingr ON fp.ingredient_id = ingr.id WHERE day = ? AND meal = ? AND user_id = (SELECT id FROM accounts WHERE email = ?)';
        $mealDetailsStatement = $db->prepare($mealDetailsQuery);
        $mealDetailsStatement->execute([$day, $meal, $memberEmail]);

        return $mealDetailsStatement->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getWeeklyMealsIngredients($memberEmail) {
        $db = $this->dbConnect();
        $weeklyMealsIngredientsQuery = 'SELECT ingr.french_name, ingr.measure_unit , SUM(fp.quantity) AS ingredient_quantity FROM ingredients ingr INNER JOIN food_plans fp ON fp.ingredient_id = ingr.id WHERE fp.user_id = (SELECT id FROM accounts WHERE email = ?) GROUP BY ingr.french_name, ingr.measure_unit';
        $weeklyMealsIngredientsStatement = $db->prepare($weeklyMealsIngredientsQuery);
        $weeklyMealsIngredientsStatement->execute([$memberEmail]);

        return $weeklyMealsIngredientsStatement->fetchAll(PDO::FETCH_ASSOC);
    }
}