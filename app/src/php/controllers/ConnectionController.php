<?php

require ('app/src/php/controllers/MainController.php');
require ('app/src/php/model/UserManager.php');

class ConnectionController extends MainController {
    public $connectionPagesStyles = [
        'pages/connection',
        'components/header',
        'components/internal-links',
        'components/form',
        'components/footer',
        'themes/member-section'
    ];

    public $userFirstName = '';
    public $userLastName = '';
    public $userEmail = '';
    public $userPassword = '';
    public $userConfirmationPassword = '';

    public $loginPageURL = 'index.php?page=login';
    public $registeringPageURL = 'index.php?page=registering';
    public $dashboardPageURL = 'index.php?page=dashboard';

    public $usedEmailErrorMessage = "Nous sommes désolés, mais votre e-mail est déjà pris. Merci d'utiliser une autre adresse e-mail.";
    public $invalidDataErrorMessage = "Certains champs du formulaire ne sont pas valides. Merci de réessayer.";
    public $nonExistentEmailErrorMessage = "Ce compte n'existe pas. Merci d'entrer une adresse e-mail valide, ou de créer un nouveau compte.";
    public $wrongPasswordErrorMessage = "Mot de passe erroné.";
    public $dbErrorMessage = "Votre compte n'a pas pu être créé. Merci de réessayer plus tard.";


    public function verifyRegistrationForm() {
        $this->userFirstName = htmlspecialchars($_POST['user-first-name']);
        $this->userLastName = htmlspecialchars($_POST['user-last-name']);
        $this->userEmail = htmlspecialchars($_POST['user-email']);
        $this->userPassword = htmlspecialchars($_POST['user-password']);
        $this->userConfirmationPassword = htmlspecialchars($_POST['user-confirmation-password']);
        
        if (
            (preg_match($this->usernameRegex, $this->userFirstName)) &&
            (preg_match($this->usernameRegex, $this->userLastName)) &&
            (preg_match($this->emailRegex, $this->userEmail)) &&
            (preg_match($this->passwordRegex, $this->userPassword)) &&
            (preg_match($this->passwordRegex, $this->userConfirmationPassword)) &&
            ($this->userPassword === $this->userConfirmationPassword)
        ) {
            $this->verifyUserForRegistration();
        }
        else {
            $_SESSION['form-error'] = $this->invalidDataErrorMessage;
            header("Location:{$this->registeringPageURL}");
        }
    }

    public function verifyUserForRegistration() {
        $userManager = new UserManager;
        if (empty($userManager->getUserPasswordFromEmail($this->userEmail))) {
            $isRegistrationSuccessful = $userManager->registerUser($this->userFirstName, $this->userLastName, $this->userEmail, $this->userPassword);
            $this->verifyActionSuccess($isRegistrationSuccessful, $this->registeringPageURL);
        }

        else {
            $_SESSION['form-error'] = $this->usedEmailErrorMessage;
            header("Location:{$this->registeringPageURL}");
        }
    }

    public function verifyLoginForm() {
        $this->userEmail = htmlspecialchars($_POST['user-email']);
        $this->userPassword = htmlspecialchars($_POST['user-password']);
        
        if (
            preg_match($this->emailRegex, $this->userEmail) &&
            (preg_match($this->passwordRegex, $this->userPassword))
        ) {
            $this->verifyUserForLoggin();
        }

        else {
            $_SESSION['form-error'] = $this->invalidDataErrorMessage;
            header("Location:{$this->loginPageURL}");
        }        
    }

    public function verifyUserForLoggin() {
        $userManager = new UserManager;
        $userPassword = $userManager->getUserPasswordFromEmail($this->userEmail);

        if (!$userPassword) {
            $_SESSION['form-error'] = $this->nonExistentEmailErrorMessage;
            header("Location:{$this->loginPageURL}");
        }

        elseif ($userPassword[0] === $this->userPassword) {
            $isUserLastLoginUpdateSuccessful = $userManager->updateUserLastLogin($this->userEmail);
            $this->verifyActionSuccess($isUserLastLoginUpdateSuccessful, $this->loginPageURL);
        }

        else {
            $_SESSION['form-error'] = $this->wrongPasswordErrorMessage;
            header("Location:{$this->loginPageURL}");
        }
    }

    public function verifyActionSuccess ($isActionSucessfull, $redirectionPageURL) {
        if ($isActionSucessfull) {
            $_SESSION['form-error'] = '';
            header("Location:{$this->dashboardPageURL}");
        }
        else {
            $_SESSION['form-error'] = $this->dbErrorMessage;
            header("Location:{$redirectionPageURL}");
        }
    }

    public function getFormError() {
        $formError = '' ;
        if (isset($_SESSION['form-error'])) {
            $formError = $_SESSION['form-error'];
        }

        return $formError;
    }

    public function renderLoginPage($twig) {
        echo $twig->render('templates/head.twig', ['stylePaths' => $this->connectionPagesStyles]);
        echo $twig->render('templates/header.twig', ['requestedPage' => 'connection']);
        echo $twig->render('connection/login.twig', ['previousFormError' => $this->getFormError()]);
        echo $twig->render('templates/footer.twig');
    }

    public function renderRegisteringPage($twig) {
        echo $twig->render('templates/head.twig', ['stylePaths' => $this->connectionPagesStyles]);
        echo $twig->render('templates/header.twig', ['requestedPage'=> 'connection']);
        echo $twig->render('connection/registering.twig', ['previousFormError' => $this->getFormError()]);
        echo $twig->render('templates/footer.twig');
    }

    public function renderPasswordRetrievingPage($twig) {
        echo $twig->render('templates/head.twig', ['stylePaths' => $this->connectionPagesStyles]);
        echo $twig->render('templates/header.twig', ['requestedPage'=> 'connection']);
        echo $twig->render('connection/password-retrieving.twig', ['previousFormError' => $this->getFormError()]);
        echo $twig->render('templates/footer.twig');
    }
}