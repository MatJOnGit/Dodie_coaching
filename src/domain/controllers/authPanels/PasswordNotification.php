<?php

namespace App\Domain\Controllers\AuthPanels;

final class PasswordNotification extends AuthPanel {
    public function renderMailNotificationPage(object $twig): void {
        echo $twig->render('connection_panels/mail-notification.html.twig', [
            'stylePaths' => $this->_getAuthPanelsStyles(),
            'frenchTitle' => "Notification d'email",
            'appSection' => 'connectionPanels'
        ]);
    }
}