<?php

namespace App\Entities;

final class Meal {
    private const MEALS_TRANSLATIONS = [
        ['english' => 'breakfast', 'french' => 'petit-déjeuner'],
        ['english' => 'snack #1', 'french' => 'en-cas de 10h'],
        ['english' => 'lunch', 'french' => 'déjeuner'],
        ['english' => 'snack #2', 'french' => 'goûté'],
        ['english' => 'diner', 'french' => 'dîner']
    ];
    
    public function getMealsTranslations() {
        return self::MEALS_TRANSLATIONS;
    }
}

