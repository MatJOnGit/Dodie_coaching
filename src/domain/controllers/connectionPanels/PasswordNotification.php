<?php

namespace App\Domain\Controllers\ConnectionPanels;

final class PasswordNotification extends ConnectionPanel {
    public function renderMailNotificationPage(object $twig): void {
        echo $twig->render('connection_panels/mail-notification.html.twig', [
            'stylePaths' => $this->_getConnectionPanelsStyles(),
            'frenchTitle' => "Notification d'email",
            'appSection' => 'connectionPanels'
        ]);
    }
}