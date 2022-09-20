<?php

require_once('./../src/model/Manager.php');

class NutritionProgramManager extends Manager {
    public function getMealDetails($day, $meal, $memberEmail) {
        $db = $this->dbConnect();
        $mealDetailsQuery = 'SELECT ingr.name, ingr.french_name, ingr.type, fp.quantity, ingr. measure_unit FROM food_plans fp INNER JOIN ingredients ingr ON fp.ingredient_id = ingr.id WHERE day = ? AND meal = ? AND user_id = (SELECT id FROM accounts WHERE email = ?)';
        $mealDetailsStatement = $db->prepare($mealDetailsQuery);
        $mealDetailsStatement->execute([$day, $meal, $memberEmail]);

        return $mealDetailsStatement->fetchAll(PDO::FETCH_ASSOC);
    }
}