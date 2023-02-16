<?php

namespace App\Domain\Models;

use App\Mixins;
use PDO;

final class FoodPlan {
    use Mixins\Database;
    
    public function dbConnect() {
        return $this->connect();
    }
    
    public function selectDailyMealsIntakes(int $subscriberId, string $day, string $mealIndex) {
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
        $selectProgramIngredientsStatement->execute([$subscriberId, $day, $mealIndex]);
        
        return $selectProgramIngredientsStatement->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function selectIngredientsCount(int $subscriberId, string $weekDay, string $meal) {
        $db = $this->dbConnect();
        $selectIngredientsCountQuery = "SELECT COUNT(*) AS ingredientsCount FROM food_plans WHERE user_id = ? AND day = ? AND meal = ?";
        $selectIngredientsCountStatement = $db->prepare($selectIngredientsCountQuery);
        $selectIngredientsCountStatement->execute([$subscriberId, $weekDay, $meal]);
        
        return $selectIngredientsCountStatement->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function selectMealDetails(string $day, string $meal, int $subscriberId) {                
        $db = $this->dbConnect();
        $selectMealDetailsQuery =
            "SELECT
                ingr.name,
                ingr.french_name,
                ingr.recipe,
                ingr.type,
                fp.quantity,
                ingr.measure
            FROM food_plans fp
            INNER JOIN ingredients ingr ON fp.ingredient_id = ingr.id
            INNER JOIN accounts acc ON fp.user_id = acc.id
            WHERE fp.day = ?
            AND fp.meal = ?
            AND acc.id = ?";
        $selectMealDetailsStatement = $db->prepare($selectMealDetailsQuery);
        $selectMealDetailsStatement->execute([$day, $meal, $subscriberId]);
        
        return $selectMealDetailsStatement->fetchAll(PDO::FETCH_ASSOC);
    }

    public function selectDisplayableMealIntakes($day, $meal, $displayedMealStatus, $subscriberId) {
        /* selectionne tous les ingrédients "confirmed" d'un programme et ses apports nutritionnels
        pour un repas, un jour et un utilisateur spécifique */
    }
    
    public function selectMealsIndexes(int $subscriberId) {
        $db = $this->dbConnect();
        $selectSubscriberMealsQuery = "SELECT DISTINCT(meal_index) FROM food_plans WHERE user_id = ? AND portion_status = 'confirmed' ORDER BY meal_index";
        $selectSubscriberMealsStatement = $db->prepare($selectSubscriberMealsQuery);
        $selectSubscriberMealsStatement->execute([$subscriberId]);
        
        return $selectSubscriberMealsStatement->fetchAll(PDO::FETCH_ASSOC);
    }

    public function selectMealIntakes(string $meal, string $day, string $ingredientsStatus, int $subscriberId) {
        $db = $this->dbConnect();
        $selectMealStagedIngredientsQuery = 
            "SELECT
                fp.quantity,
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
            AND fp.meal = ?
            AND fp.portion_status = ?";
        $selectMealStagedIngredientsStatement = $db->prepare($selectMealStagedIngredientsQuery);
        $selectMealStagedIngredientsStatement->execute([$subscriberId, $day, $meal, $ingredientsStatus]);

        return $selectMealStagedIngredientsStatement->fetchAll(PDO::FETCH_ASSOC);
    }
}