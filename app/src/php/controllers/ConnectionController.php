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

    public function registerAccount() {
        $userManager = new UserManager;

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
            if (empty($userManager->verifyUserMail($this->userMail))) {
                $userManager = new UserManager;
                $isRegistrationSuccessfull = $userManager->registerUser($this->userFirstName, $this->userLastName, $this->userMail, $this->userPassword);
                $this->manageRegistrationResults($isRegistrationSuccessfull);
            }
            else {
                
                $_SESSION['userFirstName'] = $this->userFirstName;
                echo 'Nous sommes désolés, ' . $_SESSION['userFirstName'] . ', mais votre email est déjà pris. Merci d\'utiliser une autre adresse mail.';
            }
        }
        else {
            echo 'Certaines données du formulaire ne sont pas valides. Redirection vers le formulaire.';
        }
    }

    public function manageRegistrationResults($isRegistrationSuccessfull) {
        if ($isRegistrationSuccessfull) {
            $_SESSION['userFirstName'] = $this->userFirstName;
            echo 'Merci, ' . $_SESSION['userFirstName'] . '. Votre compte a bien été créé';
        }
        else {
            echo 'Erreur lors de la création de votre compte. Affichage d\'une page 404.';
        }
    }

    public function renderLoginPage($twig) {
        echo $twig->render('templates/head.twig', ['stylePaths' => $this->connectionPagesStyles]);
        echo $twig->render('templates/header.twig', ['requestedPage'=> 'connection']);
        echo $twig->render('connection/login.twig');
        echo $twig->render('templates/footer.twig');
    }

    public function renderRegisteringPage($twig) {
        echo $twig->render('templates/head.twig', ['stylePaths' => $this->connectionPagesStyles]);
        echo $twig->render('templates/header.twig', ['requestedPage'=> 'connection']);
        echo $twig->render('connection/registering.twig');
        echo $twig->render('templates/footer.twig');
    }

    public function renderPasswordRetrievingPage($twig) {
        echo $twig->render('templates/head.twig', ['stylePaths' => $this->connectionPagesStyles]);
        echo $twig->render('templates/header.twig', ['requestedPage'=> 'connection']);
        echo $twig->render('connection/password-retrieving.twig');
        echo $twig->render('templates/footer.twig');
    }
}