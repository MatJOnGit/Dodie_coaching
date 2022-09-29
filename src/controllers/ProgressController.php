<?php

namespace App\Controllers;

require ('./../src/controllers/MemberPanelsController.php');
require_once ('./../src/model/ProgressManager.php');
use DateTime;
use App\Models\ProgressManager as ProgressManager;

class ProgressController extends MemberPanelsController {
    private $_progressScripts = [
        'classes/MemberPanels.model',
        'classes/Progress.model',
        'progressApp'
    ];

    private $_subMenuPage = 'progress';

    private $_reportDateTypes = ['current-weight', 'old-weight'];

    private $_minWeight = 0;

    public function areBaseFormDataSet(): bool {
        return (isset($_POST['user-weight']) && (isset($_POST['date-type'])));
    }

    public function areBaseFormDataValid(array $baseFormData): bool {
        $reportDateTypes = $this->_getReportDateTypes();
        $userWeight = $baseFormData['userWeight'];
        $dateType = $baseFormData['dateType'];

        $isWeightDataValid = is_numeric($userWeight) && ($userWeight > $this->_getMinWeight());
        $isWeightDateTypeValid = in_array($dateType, $reportDateTypes);

        return ($isWeightDataValid && $isWeightDateTypeValid);
    }

    public function areExtendedFormDataSet(): bool {
        return (isset($_POST['report-day']) && (isset($_POST['report-time'])));
    }

    public function areExtendedFormDataValid(array $extendedFormData): bool {
        $reportDay = $extendedFormData['day'];
        $reportTime = $extendedFormData['time'];

        $isReportDayValid = $this->_isReportDateValid($reportDay, 'Y-m-d');
        $isReportTimeValid = $this->_isReportDateValid($reportTime, 'H:i');

        return ($isReportDayValid && $isReportTimeValid);
    }

    public function deleteReport(array $progressHistory, int $reportId) {
        $progressManager = new ProgressManager;
        $reportDate = $progressHistory[$reportId-1]['report_date'];

        return $progressManager->deleteReport($reportDate, $_SESSION['user-email']);
    }

    public function formatBaseFormData(array $reportBaseFormData): array {
        $this->_setTimeZone();

        $fomatedBaseFormData = [
            'formatedUserWeight' => floatval(number_format($reportBaseFormData['userWeight'], 2)),
            'formatedDate' => date('Y-m-d H:i:s')
        ];

        return $fomatedBaseFormData;
    }

    public function formatExtendedFormData(array $reportExtendedFormData): array {
        $this->_setTimeZone();

        $fomatedExtendedFormData = [
            'formatedUserWeight' => $reportExtendedFormData['userWeight'],
            'formatedDate' =>  $reportExtendedFormData['day'] . ' ' . $reportExtendedFormData['time']
        ];

        return $fomatedExtendedFormData;
    }

    public function getBaseFormData(): array {
        return [
            'userWeight' => floatval(htmlspecialchars($_POST['user-weight'])),
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

    public function getMemberProgressHistory(): array {
        $progressManager = new ProgressManager;
        return $progressManager->getProgressHistory($_SESSION['user-email']);
    }

    public function getReportId(): string {
        return htmlspecialchars($_GET['id']);
    }

    public function isCurrentWeightReport(array $baseFormData): bool {
        return ($baseFormData['dateType'] === 'current-weight');
    }

    public function isReportIdParamExisting(array $progressHistory, int $reportId): bool {
        return array_key_exists($reportId-1, $progressHistory);
    }

    public function isReportIdParamValid(int $reportId): bool {
        return (is_numeric($reportId) && $reportId>=1);
    }

    public function isReportIdSet(): bool {
        return (isset($_GET['id']));
    }

    public function logWeightReport(array $formatedFormData) {
        $progressManager = new ProgressManager;
        $userWeight = $formatedFormData['formatedUserWeight'];
        $reportDate = $formatedFormData['formatedDate'];

        $progressManager->addWeightReport($_SESSION['user-email'], $userWeight, $reportDate);
    }

    public function renderMemberProgress(object $twig) {
        echo $twig->render('components/head.html.twig', ['stylePaths' => $this->_getMemberPanelsStyles()]);
        echo $twig->render('components/header.html.twig', ['requestedPage' => 'dashboard', 'memberPanels' => $this->_getMemberPanels(), 'subPanel' => $this->_getMemberPanelsSubpanels($this->_subMenuPage)]);
        echo $twig->render('member_panels/progress.html.twig', ['progressHistory' => $this->getMemberProgressHistory()]);
        echo $twig->render('components/footer.html.twig', ['pageScripts' => $this->_getProgressScripts()]);
    }

    private function _getMinWeight(): int {
        return $this->_minWeight;
    }

    private function _getProgressScripts(): array {
        return $this->_progressScripts;
    }

    private function _getReportDateTypes(): array {
        return $this->_reportDateTypes;
    }

    private function _isReportDateValid(string $date, string $format): bool {
        $dateFormat = DateTime::createFromFormat($format, $date);

        return ($dateFormat && $dateFormat->format($format) == $date);
    }
}