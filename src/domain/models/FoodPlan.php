<?php

namespace App\Domain\Models;

use App\Mixins;
use PDO;

final class FoodPlan {
    use Mixins\Database;

    public function selectMealsIndexes(int $subscriberId) {
        $db = $this->dbConnect();
        $selectSubscriberMealsQuery = "SELECT DISTINCT(meal_index) FROM food_plans WHERE user_id = ? ORDER BY meal_index";
        $selectSubscriberMealsStatement = $db->prepare($selectSubscriberMealsQuery);
        $selectSubscriberMealsStatement->execute([$subscriberId]);
        
        return $selectSubscriberMealsStatement->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function selectMealIngredients(int $subscriberId, string $day, string $mealOrder) {
        $db = $this->dbConnect();
        $selectProgramIngredientsQuery = 
            "SELECT
                fp.day,
                fp.meal,
                fp.meal_index,
                fp.ingredient_id,
                fp.quantity,
                ingr.id,
                ingr.name,
                ingr.french_name,
                ingr.measure,
                nut.measure_base_value,
                nut.calories,
                nut.fat,
                nut.proteins,
                nut.carbs,
                nut.sodium,
                nut.potassium,
                nut.fibers,
                nut.sugar
            FROM food_plans fp
            LEFT JOIN ingredients ingr ON fp.ingredient_id = ingr.id
            LEFT JOIN nutrients nut ON fp.ingredient_id = nut.ingredient_id
            WHERE fp.user_id = ?
            AND fp.day = ?
            AND fp.meal_index = ?";
        $selectProgramIngredientsStatement = $db->prepare($selectProgramIngredientsQuery);
        $selectProgramIngredientsStatement->execute([$subscriberId, $day, $mealOrder]);
        
        return $selectProgramIngredientsStatement->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function selectIngredientsCount(int $subscriberId, string $weekDay, string $meal) {
        $db = $this->dbConnect();
        $selectIngredientsCountQuery = "SELECT COUNT(*) AS ingredientsCount FROM food_plans WHERE user_id = ? AND day = ? AND meal = ?";
        $selectIngredientsCountStatement = $db->prepare($selectIngredientsCountQuery);
        $selectIngredientsCountStatement->execute([$subscriberId, $weekDay, $meal]);
        
        return $selectIngredientsCountStatement->fetchAll(PDO::FETCH_ASSOC);
    }

    public function dbConnect() {
        return $this->connect();
    }
}