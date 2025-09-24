<?php
include("../connexion.php");
include("../include/header.php");
include("../include/sidebar.php");

// Vérifier si l'ID est présent dans l'URL
if (isset($_GET['id'])) {
    $id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT); // Sécuriser l'entrée
    $sql = "SELECT * FROM Clients WHERE id_client = :id"; // Vérifiez que le nom de la colonne est correct
    $statement = $connect->prepare($sql);
    $statement->bindValue(':id', $id, PDO::PARAM_INT);
    $statement->execute();
    
    $client = $statement->fetch(PDO::FETCH_ASSOC); // Récupérer un seul client

    if (!$client) {
        echo "<div class='alert alert-warning'>Aucun client trouvé avec cet ID.</div>";
        exit();
    }
} else {
    echo "<div class='alert alert-danger'>Aucun élément sélectionné.</div>";
    exit();
}

// Traitement du formulaire de modification
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = filter_input(INPUT_POST, 'id_client', FILTER_SANITIZE_NUMBER_INT);
    $nom = filter_input(INPUT_POST, 'nom', FILTER_SANITIZE_STRING);
    $adresse = filter_input(INPUT_POST, 'adresse', FILTER_SANITIZE_STRING); // Changement de "prenom" à "adresse"
    $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
    $telephone = filter_input(INPUT_POST, 'telephone', FILTER_SANITIZE_STRING);
    
    // Préparer la requête de mise à jour
    $sql = "UPDATE Clients SET nom = :nom, adresse = :adresse, email = :email, téléphone = :telephone WHERE id_client = :id";
    $statement = $connect->prepare($sql);
    $statement->bindValue(':id', $id, PDO::PARAM_INT);
    $statement->bindValue(':nom', $nom, PDO::PARAM_STR);
    $statement->bindValue(':adresse', $adresse, PDO::PARAM_STR); // Liaison pour l'adresse
    $statement->bindValue(':email', $email, PDO::PARAM_STR);
    $statement->bindValue(':telephone', $telephone, PDO::PARAM_STR);
    
    // Exécuter la requête
    if ($statement->execute()) {
        header('Location: index.php?message=Client mis à jour avec succès');
        exit();
    } else {
        echo "<div class='alert alert-danger'>Erreur lors de la mise à jour du client.</div>";
    }
}
?>

<h2 class="mt-3 text-center">GESTION DES CLIENTS</h2>   
<div class="container">
    <div class="card mt-5" style="margin-left: 15%;">
        <div class="card-header bg-primary">
            <h4 class="text-white">Modification d'un client</h4>
        </div>
        <div class="card-body">
            <form action="" method="POST">
                <input type="hidden" name="id_client" value="<?= htmlspecialchars($client["id_client"]) ?>" required>
                
                <label for="nom">Nom</label>
                <input class="form-control" name="nom" value="<?= htmlspecialchars($client["nom"]) ?>" type="text" required>
                
                <label for="adresse">Adresse</label> <!-- Changement de "prenom" à "adresse" -->
                <input class="form-control" name="adresse" value="<?= htmlspecialchars($client["adresse"]) ?>" type="text" required>
                
                <label for="email">Email</label>
                <input class="form-control" name="email" value="<?= htmlspecialchars($client["email"]) ?>" type="email" required>
                
                <label for="telephone">Téléphone</label>
                <input class="form-control" name="telephone" value="<?= htmlspecialchars($client["téléphone"]) ?>" type="text" required>
                
                <center class="mt-3">   
                    <button class="btn btn-danger" type="button" onclick="window.history.back();">Annuler</button>
                    <button class="btn btn-secondary" type="submit">Enregistrer</button>
                </center>
            </form>
        </div>     
    </div>
</div>

<?php include('../include/footer.php'); ?>