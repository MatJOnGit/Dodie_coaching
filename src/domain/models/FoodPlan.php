<?php

namespace App\Domain\Models;

use App\Mixins;
use PDO;

final class FoodPlan {
    use Mixins\Database;
    
    public function dbConnect() {
        return $this->connect();
    }
    
    public function selectConfirmedMeals(int $subscriberId) {
        $db = $this->dbConnect();
        $selectSubscriberMealsQuery = "SELECT DISTINCT meal_index, meal AS meal_name FROM food_plans WHERE user_id = ? AND portion_status = 'confirmed' ORDER BY meal_index";
        $selectSubscriberMealsStatement = $db->prepare($selectSubscriberMealsQuery);
        $selectSubscriberMealsStatement->execute([$subscriberId]);
        
        return $selectSubscriberMealsStatement->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function selectIngredientsCount(int $subscriberId, string $weekDay, string $meal) {
        $db = $this->dbConnect();
        $selectIngredientsCountQuery = "SELECT COUNT(*) AS ingredientsCount FROM food_plans WHERE user_id = ? AND day = ? AND meal = ?";
        $selectIngredientsCountStatement = $db->prepare($selectIngredientsCountQuery);
        $selectIngredientsCountStatement->execute([$subscriberId, $weekDay, $meal]);
        
        return $selectIngredientsCountStatement->fetchAll(PDO::FETCH_ASSOC);
    }
    
    /******************************************************************
    Builds data of a meal with its nutrients value. Used for subscriber
    program page and subscriber's program file construction
    ******************************************************************/
    public function selectConfirmedMealDetails(string $day, string $meal, int $subscriberId) {
        $db = $this->dbConnect();
        $selectConfirmedMealDetailsQuery =
            "SELECT DISTINCT
                fp.item_type,
                fp.ingredient_id,
                fp.recipe_id,
                fp.quantity,
                ingr.name AS ingr_name,
                ingr.preparation,
                ingr.measure,
                rec.name AS rec_name,
                nut.calories,
                nut.fat,
                nut.proteins,
                nut.carbs,
                nut.sodium,
                nut.fibers,
                nut.sugar
            FROM food_plans fp
            INNER JOIN accounts acc ON fp.user_id = acc.id
            LEFT JOIN ingredients ingr ON fp.ingredient_id = ingr.id
            LEFT JOIN recipes rec ON fp.recipe_id = rec.recipe_id
            LEFT JOIN nutrients nut ON fp.ingredient_id = nut.id
            WHERE fp.day = ?
            AND fp.meal = ?
            AND acc.id = ?
            AND fp.portion_status = 'confirmed'";
        $selectConfirmedMealDetailsStatement = $db->prepare($selectConfirmedMealDetailsQuery);
        $selectConfirmedMealDetailsStatement->execute([$day, $meal, $subscriberId]);
        
        return $selectConfirmedMealDetailsStatement->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function selectMealIntakes(string $day, string $meal, int $subscriberId, string $ingredientsStatus) {
        $db = $this->dbConnect();
        $selectMealIntakesQuery = 
            "SELECT
                fp.quantity,
                ingr.name,
                ingr.measure,
                nut.calories,
                nut.fat,
                nut.proteins,
                nut.carbs,
                nut.sodium,
                nut.fibers,
                nut.sugar
            FROM food_plans fp
            LEFT JOIN ingredients ingr ON fp.ingredient_id = ingr.id
            LEFT JOIN nutrients nut ON fp.ingredient_id = nut.id
            WHERE fp.day = ?
            AND fp.meal = ?
            AND fp.user_id = ?
            AND fp.portion_status = ?";
        $selectMealIntakesStatement = $db->prepare($selectMealIntakesQuery);
        $selectMealIntakesStatement->execute([$day, $meal, $subscriberId, $ingredientsStatus]);
        
        return $selectMealIntakesStatement->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function selectWeeklyIngredients($subscriberId) {
        $db = $this->dbConnect();
        $selectWeeklyIngredientsQuery = 
            "SELECT name, measure, SUM(ingr_quantity) AS total_quantity
            FROM (
                SELECT ingr.name, ingr.measure, SUM(fp.quantity) AS ingr_quantity
                FROM food_plans fp
                INNER JOIN accounts acc ON fp.user_id = acc.id
                INNER JOIN ingredients ingr on fp.ingredient_id = ingr.id
                WHERE acc.id = ?
                AND fp.portion_status = 'confirmed'
                AND fp.ingredient_id IS NOT NULL
                GROUP BY ingr.name, ingr.measure
                UNION ALL
                SELECT ingr.name, ingr.measure, SUM(rec.ingredient_quantity * fp.quantity) AS ingr_quantity
                FROM food_plans fp
                INNER JOIN accounts acc on fp.user_id = acc.id
                INNER JOIN recipes rec on fp.recipe_id = rec.recipe_id
                INNER JOIN ingredients ingr on rec.ingredient_id = ingr.id
                WHERE acc.id = ?
                AND fp.portion_status = 'confirmed'
                AND fp.recipe_id IS NOT NULL
                GROUP BY ingr.name, ingr.measure
            ) AS combined_table
            GROUP BY name, measure";
        $selectWeeklyIngredientsStatement = $db->prepare($selectWeeklyIngredientsQuery);
        $selectWeeklyIngredientsStatement->execute([$subscriberId, $subscriberId]);
        
        return $selectWeeklyIngredientsStatement->fetchAll(PDO::FETCH_ASSOC);
    }
}