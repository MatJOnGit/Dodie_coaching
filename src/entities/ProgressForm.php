<?php

namespace App\Entities;

use DateTime;

final class ProgressForm extends Form {
    private const DATE_TYPES = ['current-weight', 'old-weight'];
    private const MIN_WEIGHT = 0;
    
    public function areBaseFormDataSet(): bool {
        return (isset($_POST['weight']) && (isset($_POST['date-type'])));
    }
    
    public function areBaseFormDataValid(array $baseFormData): bool {
        $weight = $baseFormData['userWeight'];
        $dateType = $baseFormData['dateType'];
        
        $isWeightDataValid = is_numeric($weight) && ($weight > $this->_getMinWeight());
        $isWeightDateTypeValid = in_array($dateType, $this->_getDateTypes());
        
        return ($isWeightDataValid && $isWeightDateTypeValid);
    }
    
    public function areExtendedFormDataSet(): bool {
        return (isset($_POST['report-day']) && (isset($_POST['report-time'])));
    }
    
    public function areExtendedFormDataValid(array $extendedFormData): bool {
        $date = $extendedFormData['day'] . ' ' . $extendedFormData['time'];
        
        return $this->_isDateValid($date, 'Y-m-d H:i');
    }
    
    public function getBaseFormData(): array {
        return [
            'userWeight' => floatval(htmlspecialchars($_POST['weight'])),
            'dateType' => htmlspecialchars($_POST['date-type'])
        ];
    }
    
    public function getExtendedFormData(array $baseFormData): array {
        $baseFormData += [
            'day' => htmlspecialchars($_POST['report-day']),
            'time' => htmlspecialchars($_POST['report-time'])
        ];
        
        return $baseFormData;
    }
    
    public function getFormatedBaseFormData(array $reportBaseFormData): array {
        $timezone = new Timezone;
        $timezone->setTimezone();
        
        return [
            'formatedUserWeight' => floatval(number_format($reportBaseFormData['userWeight'], 2)),
            'formatedDate' => date('Y-m-d H:i:s')
        ];
    }
    
    public function getFormatedExtendedFormData(array $reportExtendedFormData): array {
        $timezone = new Timezone;
        $timezone->setTimeZone();
        
        return [
            'formatedUserWeight' => $reportExtendedFormData['userWeight'],
            'formatedDate' =>  $reportExtendedFormData['day'] . ' ' . $reportExtendedFormData['time']
        ];
    }
    
    private function _getDateTypes(): array {
        return self::DATE_TYPES;
    }
    
    private function _getMinWeight(): int {
        return self::MIN_WEIGHT;
    }
    
    private function _isDateValid(string $date, string $format): bool {
        $dateFormat = DateTime::createFromFormat($format, $date);
        
        return ($dateFormat && $dateFormat->format($format) == $date);
    }
}