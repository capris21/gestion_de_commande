<?php
session_start();
include("../connexion.php");
include("../include/header.php");
include("../include/sidebar.php");

if (isset($_POST["nom"])) {
    // Récupérer les données du formulaire et les sécuriser
    $nom = filter_input(INPUT_POST, 'nom', FILTER_SANITIZE_STRING);
    $adresse = filter_input(INPUT_POST, 'adresse', FILTER_SANITIZE_STRING);
    $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
    $telephone = filter_input(INPUT_POST, 'telephone', FILTER_SANITIZE_STRING);
    
    // Vérifier si l'email est valide
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo "<div class='alert alert-danger'>Email invalide.</div>";
    } else {
        // Préparer la requête d'insertion
        $sql = "INSERT INTO Clients (nom, adresse, email, téléphone) VALUES (:nom, :adresse, :email, :telephone)";
        $statement = $connect->prepare($sql);
        $statement->bindValue(':nom', $nom, PDO::PARAM_STR);
        $statement->bindValue(':adresse', $adresse, PDO::PARAM_STR);
        $statement->bindValue(':email', $email, PDO::PARAM_STR);
        $statement->bindValue(':telephone', $telephone, PDO::PARAM_STR);
        
        // Exécuter la requête
        if ($statement->execute()) {
            // Rediriger vers la page d'index en cas de succès
            header('Location: index.php?message=Client ajouté avec succès');
            exit();
        } else {
            echo "<div class='alert alert-danger'>Erreur lors de l'ajout du client.</div>";
        }
    }
}
?>

<h2 class="mt-3 text-center">GESTION DES CLIENTS</h2>   
<div class="container">
    <div class="card mt-5" style="margin-left: 15%;">
        <div class="card-header bg-primary">
            <h4 class="text-white">FORMULAIRE D'AJOUT DE CLIENT</h4>
        </div>
        <div class="card-body">
            <form action="" method="POST">
                <table class="table table-striped">
                    <tr>
                        <td><label for="nom">Nom</label></td>
                        <td><input class="form-control" name="nom" type="text" required></td>
                    </tr>
                    <tr>
                        <td><label for="adresse">Adresse</label></td>
                        <td><input class="form-control" name="adresse" type="text" required></td>
                    </tr>
                    <tr>
                        <td><label for="email">Email</label></td>
                        <td><input class="form-control" name="email" type="email" required></td>
                    </tr>
                    <tr>
                        <td><label for="telephone">Téléphone</label></td>
                        <td><input class="form-control" name="telephone" type="text" required></td>
                    </tr>
                </table>
                <center class="mt-3">   
                    <button class="btn btn-danger" type="button" onclick="window.history.back();">Annuler</button>
                    <button class="btn btn-secondary" type="submit">Enregistrer</button>
                </center>
            </form>
        </div>     
    </div>
</div>

<?php include('../include/footer.php'); ?>