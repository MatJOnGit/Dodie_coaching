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

    public $usernameRegex = '^[-[:alpha:] \']+$^';
    public $emailRegex = '#^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$#';
    public $passwordRegex = '#^(?=.*[A-Z])(?=.*[0-9])(?=.*[a-z]).{6,}$#';

    public $userFirstName = '';
    public $userLastName = '';
    public $userMail = '';
    public $userPassword = '';

    public $registeringPageURL = 'index.php?page=registering';
    public $dashboardPageURL = 'index.php?page=dashboard';

    public function registerAccount() {
        $this->userFirstName = htmlspecialchars($_POST['user-first-name']);
        $this->userLastName = htmlspecialchars($_POST['user-last-name']);
        $this->userMail = htmlspecialchars($_POST['user-mail']);
        $this->userPassword = htmlspecialchars($_POST['user-password']);
        $this->userConfirmationPassword = htmlspecialchars($_POST['user-confirmation-password']);
        
        if (
            (preg_match($this->usernameRegex, $this->userFirstName)) &&
            (preg_match($this->usernameRegex, $this->userLastName)) &&
            (preg_match($this->emailRegex, $this->userMail)) &&
            (preg_match($this->passwordRegex, $this->userPassword)) &&
            (preg_match($this->passwordRegex, $this->userConfirmationPassword)) &&
            ($this->userPassword === $this->userConfirmationPassword)
        ) {
            $userManager = new UserManager;
            if (empty($userManager->verifyUserMail($this->userMail))) {
                $isRegistrationSuccessfull = $userManager->registerUser($this->userFirstName, $this->userLastName, $this->userMail, $this->userPassword);

                $this->manageRegistrationResults($isRegistrationSuccessfull);

                header("Location:{$this->dashboardPageURL}");
            }

            else {
                $_SESSION['form-error'] = "Nous sommes désolés, mais votre email est déjà pris. Merci d'utiliser une autre adresse mail.";
                header("Location:{$this->registeringPageURL}");
            }
        }

        else {
            $_SESSION['form-error'] = "Les champs renseignés ne respectent pas les indications. Merci de réessayer.";
            header("Location:{$this->registeringPageURL}");
        }
    }
    
    public function logAccount() {
        $this->userMail = htmlspecialchars($_POST['user-email']);
        $this->userPassword = htmlspecialchars($_POST['user-password']);

        if (
            preg_match($this->emailRegex, $this->userMail) &&
            (preg_match($this->passwordRegex, $this->userPassword))
        ) {
            $userManager = new UserManager;
            $userPassword = $userManager->verifyUserMail($this->userMail);

            if (!$userPassword) {
                $_SESSION['form-error'] = "Ce compte n'existe pas. Merci d'entrer une adresse mail valide, ou créer un nouveau compte.";
                header("Location:{$this->registeringPageURL}");
            }

            elseif ($userPassword[0] === $this->userPassword) {
                $userManager->updateUserLastLogin($this->userMail);
                header("Location:{$this->dashboardPageURL}");
            }

            else {
                $_SESSION['form-error'] = "Mot de passe erroné.";
                header("Location:{$this->registeringPageURL}");
            }
        }

        else {
            $_SESSION['form-error'] = "Certaines données du formulaire ne sont pas valides.";
            header("Location:{$this->registeringPageURL}");
        }        
    }

    public function manageRegistrationResults($isRegistrationSuccessfull) {
        if ($isRegistrationSuccessfull) {
            echo "Merci, {$this->userFirstName}. Votre compte a bien été créé";
        }
        else {
            echo "Erreur lors de la création de votre compte. Affichage d'une page 404.";
        }
    }

    public function renderLoginPage($twig) {
        echo $twig->render('templates/head.twig', ['stylePaths' => $this->connectionPagesStyles]);
        echo $twig->render('templates/header.twig', ['requestedPage' => 'connection']);
        echo $twig->render('connection/login.twig');
        echo $twig->render('templates/footer.twig');
    }

    public function renderRegisteringPage($twig) {
        $displayableError = $this->getFormError();

        echo $twig->render('templates/head.twig', ['stylePaths' => $this->connectionPagesStyles]);
        echo $twig->render('templates/header.twig', ['requestedPage'=> 'connection']);

        if (isset($_SESSION['form-error'])) {
            echo $twig->render('connection/registering.twig', ['displayableError' => $displayableError]);
        }
        else {
            echo $twig->render('connection/registering.twig');
        }

        echo $twig->render('templates/footer.twig');
    }

    public function getFormError() {
        $formError = '' ;
        if (isset($_SESSION['form-error'])) {
            $formError = $_SESSION['form-error'];
        }

        return $formError;
    }

    public function renderPasswordRetrievingPage($twig) {
        echo $twig->render('templates/head.twig', ['stylePaths' => $this->connectionPagesStyles]);
        echo $twig->render('templates/header.twig', ['requestedPage'=> 'connection']);
        echo $twig->render('connection/password-retrieving.twig');
        echo $twig->render('templates/footer.twig');
    }
}