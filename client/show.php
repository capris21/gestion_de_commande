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
?>

<h2 class="mt-3 text-center">GESTION DES CLIENTS</h2>   
<div class="container">
    <div class="card mt-5" style="margin-left: 10%;">
        <div class="card-header bg-primary">
            <h4 class="text-white">Détails d'un client</h4>
        </div>
        <div class="card-body">
            <div class="row">
                <h5 class="col-md-4"><b>Nom : </b></h5>
                <h5 class="col-md-8"><?= htmlspecialchars($client["nom"]) ?></h5>
            </div>
            <div class="row">
                <h5 class="col-md-4"><b>Adresse : </b></h5> <!-- Changement de "Prénom" à "Adresse" -->
                <h5 class="col-md-8"><?= htmlspecialchars($client["adresse"]) ?></h5>
            </div>
            <div class="row">
                <h5 class="col-md-4"><b>Email : </b></h5>
                <h5 class="col-md-8"><?= htmlspecialchars($client["email"]) ?></h5>
            </div>
            <div class="row">
                <h5 class="col-md-4"><b>Téléphone : </b></h5>
                <h5 class="col-md-8"><?= htmlspecialchars($client["téléphone"]) ?></h5> <!-- Assurez-vous que le nom de la colonne est correct -->
            </div>
        </div>
    </div>     
</div>  

<?php include('../include/footer.php'); ?>