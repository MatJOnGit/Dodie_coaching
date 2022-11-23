<?php

namespace Dodie_Coaching\Controllers;

class SubscriberProgram extends AdminSubscribers {
    public function renderSubscriberProgramPage(object $twig, int $subscriberId) {
        echo $twig->render('admin_panels/subscriber-program.html.twig', [
            'stylePaths' => $this->_getAdminPanelsStyle(),
            'frenchTitle' => "Programme",
            'appSection' => 'userPanels',
            'prevPanel' => ['subscriber-profile&id=' . $subscriberId, 'Profil abonnés'],
            'subscriberHeaders' => $this->_getSubscriberHeaders($subscriberId),
            'weekDays' => $this->_getWeekDays()
        ]);
    }
}