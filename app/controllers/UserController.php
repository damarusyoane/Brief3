<?php
require_once __DIR__ . '/../core/Controller.php';
require_once __DIR__ . '/../core/Auth.php';
require_once __DIR__ . '/../../config/PHPMailer/PHPMailer.php';
require_once __DIR__ . '/../../config/PHPMailer/SMTP.php';
require_once __DIR__ . '/../../config/PHPMailer/Exception.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;

use Controller;
use Auth;
class UserController extends Controller {
    public function resetPassword() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = $_POST['email'];
            $userModel = new UserModel();
            $user = $userModel->getUserByEmail($email);

            if ($user) {
                // Generate a unique token for password reset
                $token = bin2hex(random_bytes(50));
                // Store the token in the database (you may need to create a new method in UserModel)
                $userModel->storePasswordResetToken($user['id'], $token);

                // Send email with PHPMailer
                $this->sendPasswordResetEmail($email, $token);
                header('Location: /auth/connexion?message=Check your email for the password reset link.');
            } else {
                $this->view('auth/motdepasse_oublier', ['error' => 'Email not found.']);
            }
        } else {
            $this->view('auth/motdepasse_oublier');
        }
    }

    private function sendPasswordResetEmail($email, $token) {
        // PHPMailer setup
        $mail = new PHPMailer(true);
        try {
            // SMTP configuration
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'damarusngankou@gmail.com';
            $mail->Password = 'btss mbkp ydjr thov';
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port = 587;

            // Recipients
            $mail->setFrom('no-reply@D-Cars.com', 'D-CARS');
            $mail->addAddress($email);

            // Content
            $resetLink = "http://localhost:8080/motdepasse_res.php?token=$token";
            $mail->isHTML(true);
            $mail->Subject = 'Réinitialisation de votre mot de passe';;
            $mail->Body =  "Cliquez sur ce lien pour réinitialiser votre mot de passe : <a href='$resetLink'>Réinitialiser le mot de passe</a>";
            $mail->AltBody = "Cliquez sur ce lien pour réinitialiser votre mot de passe : $resetLink";

            $mail->send();
            echo "<div class='mt-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded'>Un lien de réinitialisation a été envoyé à votre adresse e-mail.</div>";
        } catch (Exception $e) {
            error_log("Erreur lors de l'envoi de l'e-mail : " . $e->getMessage());
            echo "<div class='mt-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded'>Échec de l'envoi de l'e-mail. Erreur : " . $e->getMessage() . "</div>";
        }

    }

    public function profile() {
        // Existing profile method logic

        if (!isset($_SESSION['user_id'])) {
            header('Location: /auth/login');
        }

        $userModel = new UserModel();
        $user = $userModel->getUserById($_SESSION['user_id']);

        $this->view('client/profile', ['user' => $user]);
    }

    // Ajouter d'autres méthodes pour la gestion du profil, historique, etc.
}
