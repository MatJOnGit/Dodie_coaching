<?php

namespace App\Entities;

use App\Domain\Models\ProgramFile as ProgramFileModel;
use App\Domain\Models\FoodPlan;

final class ProgramFile {
    private const PROGRAMS_FOLDER_ROUTE = './../var/nutrition_programs/';
    
    public function getFileName(array $subscriberHeaders): string {
        return 'Programme_nutritionnel_' . str_replace(' ', '_', $subscriberHeaders['name']);
    }
    
    /*****************************************************************************************
    Builds the full path to subscriber's program file if existing and if the file has a status
    *****************************************************************************************/
    public function getProgramsFilePath(int $subscriberId) {
        $programFile = new ProgramFileModel;
        
        $fileName = $programFile->selectFileName($_SESSION['email']);
        $fileStatus = $this->getProgramFileStatus($subscriberId);
        
        return ($fileName && $fileStatus) ? $this->_getProgramsFolderRoute() . $fileName[0] . '.pdf' : null;
    }
    
    public function getProgramFileStatus($subscriberId) {
        $programFile = new ProgramFileModel;
        
        $programFileStatus = $programFile->selectFileStatus($subscriberId);
        return $programFileStatus ? $programFileStatus['file_status'] : NULL;
    }
    
    /***********************************************************************************
    Return the value of the possibility to update the subscriber's program file based on
    its status and meals completion
    ***********************************************************************************/
    public function isProgramFileUpdatable(string $programFileStatus, int $subscriberId): bool {
        $calendar = new Calendar;
        $program = new Program;
        
        $updatableFileStatus = ['unhosted', 'depleted'];
        $isFileUpdatable = in_array($programFileStatus, $updatableFileStatus);
        
        $weekDays = $calendar->getWeekDays();
        $meals = $program->getProgramMeals($subscriberId);
        $isProgramCompleted = true;
        
        foreach($weekDays as $weekDay) {
            $englishWeekDay = $weekDay['english'];
            
            foreach($meals as $meal) {
                if (!$this->_isMealCompleted($subscriberId, $englishWeekDay, $meal)) {
                    $isProgramCompleted = false;
                }
            }
        }
        
        return ($isFileUpdatable && $isProgramCompleted);
    }
    
    public function renderFileContent(object $twig, object $program, object $meal, array $programData, array $subscriberHeaders, int $subscriberId) {
        return $twig->render('pdf_files/printable_program.html.twig', [
            'programData' => $programData,
            'subscriberHeaders' => $subscriberHeaders,
            'programMeals' => $program->getProgramMeals($subscriberId),
            'weekDaysTranslations' => $program->buildWeekDaysTranslations(),
            'mealsTranslations' => $meal->getMealsTranslations()
        ]);
    }
    
    public function savePdf(string $fileContent, string $fileName) {
        $filePath = self::PROGRAMS_FOLDER_ROUTE . $fileName . '.pdf';
        
        return file_put_contents($filePath, $fileContent);
    }
    
    public function setProgramFileData(int $subscriberId, string $fileName, string $fileStatus): void {
        $programFile = new ProgramFileModel;
        
        $programFile->updateProgramFileData($subscriberId, $fileStatus, $fileName);
    }
    
    private function _getProgramsFolderRoute(): string {
        return self::PROGRAMS_FOLDER_ROUTE;
    }
    
    private function _isMealCompleted(int $subscriberId, string $weekDay, string $meal): bool {
        $foodPlan = new FoodPlan;
        
        $isMealCompleted = true;
        
        $ingredientsCountPerMeal = $foodPlan->selectIngredientsCount($subscriberId, $weekDay, $meal);
        
        if ($ingredientsCountPerMeal[0]['ingredientsCount'] === '0') {
            $isMealCompleted = false;
        }
        
        return $isMealCompleted;
    }
}