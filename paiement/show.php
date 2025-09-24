<?php
include("../connexion.php");
include("../include/header.php");
include("../include/sidebar.php");
?>

<?php
if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $sql = "SELECT * FROM Paiements WHERE id_paiement = :id"; // Vérifiez si la table s'appelle 'Paiements'
    $statement = $connect->prepare($sql);
    $statement->bindValue(':id', $id, PDO::PARAM_INT);
    $statement->execute();
    $paiements = $statement->fetchAll(PDO::FETCH_ASSOC);
    $paiement = $paiements[0];
} else {
    echo "Aucun élément sélectionné.";
    exit;
}
?>

<h2 class="mt-3 text-center">DÉTAILS DU PAIEMENT</h2>   
<div class="container">
    <div class="card mt-5">
        <div class="card-header bg-primary">
            <h4 class="text-white">Détails d'un paiement</h4>
        </div>
        <div class="card-body">
            <div class="row">
                <h5 class="col-md-4"><b>Montant : </b></h5>
                <h5 class="col-md-8"><?= htmlspecialchars($paiement["montant"]) ?> €</h5>
            </div>
            <hr>
            <div class="row">
                <h5 class="col-md-4"><b>Date : </b></h5>
                <h5 class="col-md-8"><?= htmlspecialchars($paiement["date"]) ?></h5>
            </div>
            <hr>
            <div class="row">
                <h5 class="col-md-4"><b>Méthode : </b></h5>
                <h5 class="col-md-8"><?= htmlspecialchars($paiement["méthode"]) ?></h5>
            </div>
            <hr>
            <div class="row">
                <h5 class="col-md-4"><b>ID Commande : </b></h5>
                <h5 class="col-md-8"><?= htmlspecialchars($paiement["id_commande"]) ?></h5>
            </div>
        </div>
    </div>   
    <center class="mt-3">   
        <button class="btn btn-danger" onclick="window.history.back();">Annuler</button>
        <a href="modifier_paiement.php?id=<?= htmlspecialchars($paiement['id_paiement']) ?>" class="btn btn-secondary">Modifier</a>
    </center>  
</div>  

<?php include('../include/footer.php'); ?>