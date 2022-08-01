<?php

require ('app/src/php/controllers/MainController.php');
require ('app/src/php/model/UserManager.php');

class ConnectionController extends MainController {
    public $connectionPagesStyles = [
        'pages/connection',
        'components/header',
        'components/form',
        'components/buttons',
        'components/footer',
        'themes/purple-theme'
    ];

    public $userInfo = array(
        'firstName' => '',
        'lastName' => '',
        'email' => '',
        'password' => '',
        'confirmationPassword' => ''
    );

    public $connectionPagesURL = array(
        'login' => 'index.php?page=login',
        'registering' => 'index.php?page=registering',
        'dashboard' => 'index.php?page=dashboard'
    );

    public $usedEmailErrorMessage = "Nous sommes désolés, mais votre e-mail est déjà pris. Merci d'utiliser une autre adresse e-mail.";
    public $invalidDataErrorMessage = "Certains champs du formulaire ne sont pas valides. Merci de réessayer.";
    public $nonExistentEmailErrorMessage = "Ce compte n'existe pas. Merci d'entrer une adresse e-mail valide, ou de créer un nouveau compte.";
    public $wrongPasswordErrorMessage = "Mot de passe erroné.";
    public $dbErrorMessage = "Votre compte n'a pas pu être créé. Merci de réessayer plus tard.";


    public function verifyRegistrationForm() {
        $this->userInfo['firstName'] = htmlspecialchars($_POST['user-first-name']);
        $this->userInfo['lastName'] = htmlspecialchars($_POST['user-last-name']);
        $this->userInfo['email'] = htmlspecialchars($_POST['user-email']);
        $this->userInfo['password'] = htmlspecialchars($_POST['user-password']);
        $this->userInfo['confirmationPassword'] = htmlspecialchars($_POST['user-confirmation-password']);
        
        if (
            (preg_match($this->usernameRegex, $this->userInfo['firstName'])) &&
            (preg_match($this->usernameRegex, $this->userInfo['lastName'])) &&
            (preg_match($this->emailRegex, $this->userInfo['email'])) &&
            (preg_match($this->passwordRegex, $this->userInfo['password'])) &&
            (preg_match($this->passwordRegex, $this->userInfo['confirmationPassword'])) &&
            ($this->userInfo['password'] === $this->userInfo['confirmationPassword'])
        ) {
            $this->verifyUserForRegistration();
        }
        else {
            $_SESSION['form-error'] = $this->invalidDataErrorMessage;
            header("Location:{$this->connectionPagesURL['registering']}");
        }
    }

    public function verifyUserForRegistration() {
        $userManager = new UserManager;
        if (empty($userManager->getUserPasswordFromEmail($this->userInfo['email']))) {
            $isRegistrationSuccessful = $userManager->registerUser($this->userInfo['firstName'], $this->userInfo['lastName'], $this->userInfo['email'], $this->userInfo['password']);
            $this->verifyActionSuccess($isRegistrationSuccessful, $this->connectionPagesURL['registering']);
        }

        else {
            $_SESSION['form-error'] = $this->usedEmailErrorMessage;
            header("Location:{$this->connectionPagesURL['registering']}");
        }
    }

    public function verifyLoginForm() {
        $this->userInfo['email'] = htmlspecialchars($_POST['user-email']);
        $this->userInfo['password'] = htmlspecialchars($_POST['user-password']);
        
        if (
            preg_match($this->emailRegex, $this->userInfo['email']) &&
            (preg_match($this->passwordRegex, $this->userInfo['password']))
        ) {
            $this->verifyUserForLoggin();
        }

        else {
            $_SESSION['form-error'] = $this->invalidDataErrorMessage;
            header("Location:{$this->connectionPagesURL['login']}");
        }        
    }

    public function verifyUserForLoggin() {

        $userManager = new UserManager;
        $dbUserPassword = $userManager->getUserPasswordFromEmail($this->userInfo['email']);
        if (!$dbUserPassword) {
            $_SESSION['form-error'] = $this->nonExistentEmailErrorMessage;
            header("Location:{$this->connectionPagesURL['login']}");
        }

        elseif ($dbUserPassword[0] === $this->userInfo['password']) {
            $isUserLastLoginUpdateSuccessful = $userManager->updateUserLastLogin($this->userInfo['email']);
            $this->verifyActionSuccess($isUserLastLoginUpdateSuccessful, $this->connectionPagesURL['login']);
        }

        else {
            $_SESSION['form-error'] = $this->wrongPasswordErrorMessage;
            header("Location:{$this->connectionPagesURL['login']}");
        }
    }

    public function verifyActionSuccess ($isActionSucessfull, $redirectionPageURL) {
        if ($isActionSucessfull) {
            $_SESSION['form-error'] = '';
            header("Location:{$this->connectionPagesURL['dashboard']}");
        }
        else {
            $_SESSION['form-error'] = $this->dbErrorMessage;
            header("Location:{$redirectionPageURL}");
        }
    }

    public function getFormError() {
        if (isset($_SESSION['form-error'])) { $formError = $_SESSION['form-error']; }
        else { $formError = ''; }

        return $formError;
    }

    public function eraseError() {
        $_SESSION['form-error'] = '';
    }

    public function renderLoginPage($twig) {
        echo $twig->render('templates/head.html.twig', ['stylePaths' => $this->connectionPagesStyles]); // ok
        echo $twig->render('templates/header.html.twig', ['requestedPage' => 'connection']); // ok
        echo $twig->render('connection/login.html.twig', ['previousFormError' => $this->getFormError()]);
        echo $twig->render('templates/footer.html.twig');
    }

    public function renderRegisteringPage($twig) {
        echo $twig->render('templates/head.html.twig', ['stylePaths' => $this->connectionPagesStyles]);
        echo $twig->render('templates/header.html.twig', ['requestedPage'=> 'connection']);
        echo $twig->render('connection/registering.html.twig', ['previousFormError' => $this->getFormError()]);
        echo $twig->render('templates/footer.html.twig');
    }

    public function renderPasswordRetrievingPage($twig) {
        echo $twig->render('templates/head.html.twig', ['stylePaths' => $this->connectionPagesStyles]);
        echo $twig->render('templates/header.html.twig', ['requestedPage'=> 'connection']);
        echo $twig->render('connection/password-retrieving.html.twig', ['previousFormError' => $this->getFormError()]);
        echo $twig->render('templates/footer.html.twig');
    }
}