<?php

require ('./../src/controllers/MemberPanelsController.php');

class ProgressController extends MemberPanelsController {

    private $progressScripts = [
        'Progress.model',
        'progressApp'
    ];

    private $subMenuPage = 'progress';

    public function addWeightReport() {
        $this->setTimeZone();
        $dashboardManager = new DashboardManager;
        $reportDate = $this->verifyWeightReportDateValidity($this->buildReportDate(), 'Y-m-d H:i') ? $this->buildReportDate() : date('Y-m-d H-i-s');
        $userId = $dashboardManager->getMemberId($_SESSION['user-email']);
        $userWeight = floatval(number_format($_POST['user-weight'], 2));

        $dashboardManager->addNewWeightReport($userId, $userWeight, $reportDate);
    }

    private function buildReportDate() {
        return htmlspecialchars($_POST['report-date']) . ' ' . htmlspecialchars($_POST['report-time']);
    }

    public function deleteMemberReport($progressHistory, $reportId) {
        $reportDate = $progressHistory[$reportId-1]['report_date'];
        $dashboardManager = new DashboardManager;
        $dashboardManager->deleteReport($reportDate, $_SESSION['user-email']);
    }

    public function getDeleteWeightReportId() {
        return htmlspecialchars($_GET['id']);
    }

    public function getMemberProgressHistory() {
        $dashboardManager = new DashboardManager;

        return $dashboardManager->getMemberProgressHistory($_SESSION['user-email']);
    }

    private function getProgressScripts() {
        return $this->progressScripts;
    }

    private function getRequestedWeightReportId($weightReportId) {
        return is_numeric($weightReportId) ? $weightReportId-1 : NULL;
    }

    public function renderMemberProgress($twig) {
        echo $twig->render('components/head.html.twig', ['stylePaths' => $this->getMemberPanelsStyles()]);
        echo $twig->render('components/header.html.twig', ['requestedPage' => 'dashboard', 'memberPanels' => $this->getMemberPanels(), 'subPanel' => $this->getMemberPanelsSubpanels($this->subMenuPage)]);
        echo $twig->render('member_panels/progress.html.twig', ['progressHistory' => $this->getMemberProgressHistory()]);
        echo $twig->render('components/footer.html.twig', ['pageScripts' => $this->getProgressScripts()]);
    }

    public function verifyAddWeightFormValidity() {
        $weightReport = floatval(htmlspecialchars($_POST['user-weight']));
        $isWeightReportValid = is_numeric($weightReport) && ($weightReport != 0);
        $reportDateType = htmlspecialchars($_POST['date-type']);
        $reportDateTypes = ["current-weight", "old-weight"];
        $isWeightDateTypeValid = in_array($reportDateType, $reportDateTypes);

        if (isset($_POST['report-date']) && (isset($_POST['report-time']))) {
            $reportDate = htmlspecialchars($_POST['report-date']) . ' ' . htmlspecialchars($_POST['report-time']);
            $isReportDateValid = $this->verifyWeightReportDateValidity($reportDate, 'Y-m-d H:i');
        }

        if ($reportDateType !== 'old-weight') {
            $isWeightReportValidityVerified = $isWeightReportValid && $isWeightDateTypeValid;
        }
        else {
            $isWeightReportValidityVerified = $isWeightReportValid && $isWeightDateTypeValid && $isReportDateValid;
        }

        return $isWeightReportValidityVerified;
    }

    private function verifyWeightReportDateValidity($date, $format) {
        $dateFormat = DateTime::createFromFormat($format, $date);

        return $dateFormat && $dateFormat->format($format) == $date;
    }

    public function verifyWeightReportIdValidity($progressHistory, $weightReportId) {
        return array_key_exists($this->getRequestedWeightReportId($weightReportId), $progressHistory);
    }
}