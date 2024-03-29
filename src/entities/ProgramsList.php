<?php

namespace App\Entities;

final class ProgramsList {
    private const PROGRAMS_LIST = [
        'monthly' => [
            'name' => 'monthly',
            'frenchTitle' => 'Formule mois',
            'duration' => 30,
            'subscriptionPrice' => 219,
            'description' => "Vous avez un mariage de prévu et vous avez besoin d'un coup de main pour rentrer dans votre robe ou votre costume ?<br><br>Vous souhaitez peut-être simplement tester par vous-même nos services ?<br><br>Ce programme vous donnera des bases solides pour commencer à prendre soin de vous, et en toute sérénité."
        ],
        'quarterly' => [
            'name' => 'quarterly',
            'frenchTitle' => 'Formule trimestre',
            'duration' => 90,
            'subscriptionPrice' => 649,
            'description' => ""
        ],
        'halfyearly' => [
            'name' => 'halfyearly',
            'frenchTitle' => 'Formule 6 mois',
            'duration' => 180,
            'subscriptionPrice' => 1199,
            'description' => ""
        ]
        // for tests with more than 3 programs, uncomment those following lines
        // ,'annual' => [
        //     'name' => 'annual',
        //     'frenchTitle' => 'Formule annuelle',
        //     'duration' => 365,
        //     'subscriptionPrice' => 1999,
        //     'description' => ""
        // ]
    ];
    
    public function getProgramDetails(string $program): array {
        return self::PROGRAMS_LIST[$program];
    }
    
    public function getProgramsList(): array {
        return self::PROGRAMS_LIST;
    }
    
    public function isProgramAvailable(string $requestedProgram): bool {
        return in_array($requestedProgram, array_keys($this->getProgramsList()));
    }
    
    public function isProgramsListAvailable(): bool {
        return count($this->getProgramsList()) > 0;
    }
}