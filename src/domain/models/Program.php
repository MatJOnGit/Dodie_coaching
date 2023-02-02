<?php

namespace App\Domain\Models;

use App\Mixins;
use PDO;

class Program {
    use Mixins\Database;

    public function selectProgramMeals(int $subscriberId) {
        $db = $this->dbConnect();
        $selectProgramMealsQuery = "SELECT meals_list FROM subscribers_data WHERE user_id = ?";
        $selectProgramMealsStatement = $db->prepare($selectProgramMealsQuery);
        $selectProgramMealsStatement->execute([$subscriberId]);
        
        return $selectProgramMealsStatement->fetch(PDO::FETCH_ASSOC);
    }
    
    public function selectMealsIngredients(string $email) {
        $db = $this->dbConnect();
        $selectMealsIngredientsQuery =
            "SELECT
                ingr.french_name,
                ingr.measure,
                SUM(fp.quantity) AS ingredient_quantity
            FROM ingredients ingr
            INNER JOIN food_plans fp ON fp.ingredient_id = ingr.id
            INNER JOIN accounts acc ON fp.user_id = acc.id
            WHERE acc.email = ?
            GROUP BY ingr.french_name, ingr.measure";
        $selectMealsIngredientsStatement = $db->prepare($selectMealsIngredientsQuery);
        $selectMealsIngredientsStatement->execute([$email]);
        
        return $selectMealsIngredientsStatement->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function selectMealDetails(string $day, string $meal, string $email) {                
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
            AND acc.email = ?";
        $selectMealDetailsStatement = $db->prepare($selectMealDetailsQuery);
        $selectMealDetailsStatement->execute([$day, $meal, $email]);
        
        return $selectMealDetailsStatement->fetchAll(PDO::FETCH_ASSOC);
    }

    public function dbConnect() {
        return $this->connect();
    }
}