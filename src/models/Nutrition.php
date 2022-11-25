<?php

namespace Dodie_Coaching\Models;

use PDO;

class Nutrition extends Main {
    public function selectMealDetails(string $day, string $meal, string $email) {
        $db = $this->dbConnect();
        $selectMealQuery =
            "SELECT
                ingr.name,
                ingr.french_name,
                ingr.recipe,
                ingr.type,
                fp.quantity,
                ingr. measure_unit
            FROM food_plans fp
            INNER JOIN ingredients ingr ON fp.ingredient_id = ingr.id
            INNER JOIN accounts acc ON fp.user_id = acc.id
            WHERE fp.day = ?
            AND fp.meal = ?
            AND acc.email = ?";
        $selectMealStatement = $db->prepare($selectMealQuery);
        $selectMealStatement->execute([$day, $meal, $email]);
        
        return $selectMealStatement->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function selectMealsIngredients(string $email) {
        $db = $this->dbConnect();
        $selectMealsIngredientsQuery =
            "SELECT
                ingr.french_name,
                ingr.measure_unit ,
                SUM(fp.quantity) AS ingredient_quantity
            FROM ingredients ingr
            INNER JOIN food_plans fp ON fp.ingredient_id = ingr.id
            INNER JOIN accounts acc ON fp.user_id = acc.id
            WHERE acc.email = ?
            GROUP BY ingr.french_name, ingr.measure_unit
            ";
        $selectMealsIngredientsStatement = $db->prepare($selectMealsIngredientsQuery);
        $selectMealsIngredientsStatement->execute([$email]);
        
        return $selectMealsIngredientsStatement->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function selectProgramFileName(string $email) {
        $db = $this->dbConnect();
        $selectProgramFileNameQuery = "SELECT pf.nutrition_file_name FROM programs_files pf INNER JOIN accounts acc ON acc.id = pf.user_id WHERE acc.email = ?";
        $selectProgramFileNameStatement = $db->prepare($selectProgramFileNameQuery);
        $selectProgramFileNameStatement->execute([$email]);
        
        return $selectProgramFileNameStatement->fetch();
    }
}