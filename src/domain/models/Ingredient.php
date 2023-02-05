<?php

namespace App\Domain\Models;

use App\Mixins;
use PDO;

final class Ingredient {
    use Mixins\Database;
    
    public function dbConnect() {
        return $this->connect();
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
}