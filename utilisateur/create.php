<?php
include("../connexion.php");
include("../include/header.php");
include("../include/sidebar.php");

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;

require '../vendor/autoload.php';  //pour charger le fichier de phpmailer

function sendEmail($to, $subject, $body) {
    try {
        $mail = new PHPMailer();
        $mail->isSMTP();
        $mail->SMTPDebug = 3;
        $mail->Host = 'smtp.gmail.com'; //specifier le serveur smtp
        $mail->SMTPAuth = true;
        $mail->Username = 'calinecapris@gmail.com'; //votre adresse email
        $mail->Password = 'huhg zdbx zfrx lfit'; //votre mot de passe email
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587; //port pour TLS

        //Expediteur
        $mail->setFrom('calinecapris@gmail.com', 'Sir-Tech');
        $mail->addAddress($to, ' ');

        //contenu 
        $mail->isHTML(true);
        $mail->addReplyTo('calinecapris@gmail.com', 'Sir-Tech');
        $mail->Subject = $subject;
        $mail->Body = $body;
        $mail->setLanguage('fr', '/optional/path/to/language/directory');
        $mail->send();
        echo 'Email envoyé avec succès.';
    } catch (Exception $e) {
        echo "L'email n'a pas pu être envoyé. ERREUR: {$mail->ErrorInfo}";
    }
}

try {
    function generate_pass() {
        $pass = 'ABCDEFGHIJKLMNOPQRSTUVWYZabcbefghijklmnopqrstuvwyz0123456789';
        $pass_aleatoire = '';
        for ($i = 0; $i < 8; $i++) {
            $pass_aleatoire .= substr($pass, rand() % (strlen($pass)), 1);
        }
        return $pass_aleatoire;
    }

    if (isset($_POST["nom"], $_POST["role"], $_POST["email"])) {
        $nom = $_POST["nom"];
        $role = $_POST["role"];
        $email = $_POST["email"];
        $pass = generate_pass();
        $password = password_hash($pass, PASSWORD_DEFAULT);
        $from = "calinecapris@gmail.com";
        $fromName = "Sir-Tech";
        $to = $email;
        $subject = "Paramètre de connexion";
        $body = "Bonjour \n\n" . $nom . "\n\n" . "Votre compte a été créé avec succès.\n\n" . "Votre mot de passe est : " . $pass . "\n\n" . "Veuillez le changer dès que possible en vous connectant à l'application grâce à votre adresse mail et votre mot de passe reçu.\n\n" . "Cordialement, \n" . $fromName;

        sendEmail($to, $subject, $body);

        // Correction de la requête SQL pour correspondre à la structure de la table
        $sql = "INSERT INTO utilisateurs (nom, email, mot_de_passe, role) VALUES (:nom, :email, :mdp, :role)";
        $statement = $connect->prepare($sql);
        $statement->bindValue(':nom', $nom, PDO::PARAM_STR);
        $statement->bindValue(':email', $email, PDO::PARAM_STR);
        $statement->bindValue(':mdp', $password, PDO::PARAM_STR);
        $statement->bindValue(':role', $role, PDO::PARAM_STR);
        
        // Exécution de la requête
        if ($statement->execute()) {
            header('Location: index.php');
            exit();
        } else {
            echo "Erreur lors de l'insertion des données.";
        }
    }
} catch (PDOException $e) {
    exit("ERREUR:" . $e->getMessage());
}
?>

<h2 class="mt-3 text-center">GESTION DES UTILISATEURS</h2>
<div class="container">
    <div class="card mt-5" style="margin-left: 10%; margin-right: 15px;">
        <div class="card-header bg-primary">
            <h4 class="text-white">FORMULAIRES</h4>
        </div>
        <div class="card-body">
            <form action="" method="POST">
                <label for="nom">Nom</label>
                <input class="form-control" name="nom" type="text" required>
                <label for="role">Role</label>
                <select class="form-control" name="role" required>
                    <option value="admin">Admin</option>
                    <option value="caissier">Caissier</option>
                </select>
                <label for="email">Email</label>
                <input class="form-control" name="email" type="email" required>
                <center class="mt-3">
                    <button type="reset" class="btn btn-danger">Annuler</button>
                    <button type="submit" class="btn btn-secondary">Enregistrer</button>
                </center>
            </form>
        </div>
    </div>
</div>

<?php
include("../include/footer.php");
?>
