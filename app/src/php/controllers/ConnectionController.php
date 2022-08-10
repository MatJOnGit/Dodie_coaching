<?php

require ('app/src/php/controllers/MainController.php');
require ('app/src/php/model/AccountManager.php');

class ConnectionController extends MainController {
    public $connectionPagesStyles = [
        'pages/connection-panels',
        'components/header',
        'components/form',
        'components/buttons',
        'components/footer'
    ];

    public $connectionPagesURL = array(
        'login' => 'index.php?page=login',
        'registering' => 'index.php?page=registering',
        'dashboard' => 'index.php?page=dashboard'
    );

    public $errorMessage = array(
        'usedEmail' => "Nous sommes désolés, mais votre e-mail est déjà pris. Merci d'utiliser une autre adresse e-mail.",
        'invalidFormData' => "Certains champs du formulaire ne sont pas valides. Merci de réessayer.",
        'unknownEmail' => "Ce compte n'existe pas. Merci d'entrer une adresse e-mail valide, ou de créer un nouveau compte.",
        'wrongPassword' => "Mot de passe erroné.",
        'dbError' => "Votre compte n'a pas pu être créé. Merci de réessayer plus tard."
    );

    public function verifyLoginFormData() {
        $_SESSION['user-email'] = htmlspecialchars($_POST['user-email']);
        $_SESSION['user-password'] = htmlspecialchars($_POST['user-password']);

        $isLoginFormVerified = (
            preg_match($this->emailRegex, $_SESSION['user-email']) &&
            (preg_match($this->passwordRegex, $_SESSION['user-password']))
        ) ? true : false;

        return $isLoginFormVerified;
    }

    public function verifyRegisteringFormData() {
        $_SESSION['user-first-name'] = htmlspecialchars($_POST['user-first-name']);
        $_SESSION['user-last-name'] = htmlspecialchars($_POST['user-last-name']);
        $_SESSION['user-email'] = htmlspecialchars($_POST['user-email']);
        $_SESSION['user-password'] = htmlspecialchars($_POST['user-password']);
        $_SESSION['user-confirmation-password'] = htmlspecialchars($_POST['user-confirmation-password']);

        $isRegistrationFormVerified = (
            (preg_match($this->usernameRegex, $_SESSION['user-first-name'])) &&
            (preg_match($this->usernameRegex, $_SESSION['user-last-name'])) &&
            (preg_match($this->emailRegex, $_SESSION['user-email'])) &&
            (preg_match($this->passwordRegex, $_SESSION['user-password'])) &&
            (preg_match($this->passwordRegex, $_SESSION['user-confirmation-password'])) &&
            ($_SESSION['user-password'] === $_SESSION['user-confirmation-password'])
        ) ? true : false;

        return $isRegistrationFormVerified;
    }

    public function getUserEmail() {
        return $_SESSION['user-email'];
    }

    public function getUserPassword() {
        return $_SESSION['user-password'];
    }

    public function verifyUserInDatabase() {
        $accountManager = new AccountManager;
        $dbUserPassword = $accountManager->getUserPassword($this->getUserEmail());

        return $dbUserPassword;
    }

    public function updateLoginDate() {
        $accountManager = new AccountManager;
        $isLoginDateUpdated = $accountManager->updateUserLastLogin($this->getUserEmail());

        return $isLoginDateUpdated;
    }

    public function setNewAccount() {
        $accountManager = new AccountManager;
        $isAccountRegistered = $accountManager->registerAccount($_SESSION['user-first-name'], $_SESSION['user-last-name'], $_SESSION['user-email'], $_SESSION['user-password']);

        return $isAccountRegistered;
    }

    public function setFormErrorMessage($errorType) {
        $_SESSION['form-error'] = $this->errorMessage[$errorType];
    }

    public function getPreviousFormError() {
        if (isset($_SESSION['form-error'])) {
            $formError = $_SESSION['form-error'];
        }
        else {
            $formError = '';
        }

        return $formError;
    }

    public function renderLoginPage($twig) {
        echo $twig->render('components/head.html.twig', ['stylePaths' => $this->connectionPagesStyles]);
        echo $twig->render('components/header.html.twig', ['requestedPage' => 'connection']);
        echo $twig->render('connection/login.html.twig', ['previousFormError' => $this->getPreviousFormError()]);
        echo $twig->render('components/footer.html.twig');
    }

    public function renderRegisteringPage($twig) {
        echo $twig->render('components/head.html.twig', ['stylePaths' => $this->connectionPagesStyles]);
        echo $twig->render('components/header.html.twig', ['requestedPage'=> 'connection']);
        echo $twig->render('connection/registering.html.twig', ['previousFormError' => $this->getPreviousFormError()]);
        echo $twig->render('components/footer.html.twig');
    }

    public function renderPasswordRetrievingPage($twig) {
        echo $twig->render('components/head.html.twig', ['stylePaths' => $this->connectionPagesStyles]);
        echo $twig->render('components/header.html.twig', ['requestedPage' => 'connection']);
        echo $twig->render('connection/password-retrieving.html.twig', ['previousFormError' => $this->getPreviousFormError()]);
        echo $twig->render('components/footer.html.twig');
    }
}