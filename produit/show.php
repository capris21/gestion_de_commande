<?php
include("../connexion.php");
include("../include/header.php");
include("../include/sidebar.php");
?>

<?php
if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $sql = "SELECT * FROM Produits WHERE id_produit = :id"; // Assurez-vous que la table s'appelle 'Produits'
    $statement = $connect->prepare($sql);
    $statement->bindValue(':id', $id, PDO::PARAM_INT);
    $statement->execute();
    $produits = $statement->fetchAll(PDO::FETCH_ASSOC);
    $produit = $produits[0];
} else {
    echo "Aucun élément sélectionné.";
    exit;
}
?>

<h2 class="mt-3 text-center">GESTION DES PRODUITS</h2>   
<div class="container">
    <div class="card mt-5" style="margin-left: 10%;">
        <div class="card-header bg-primary">
            <h4 class="text-white">Détails d'un produit</h4>
        </div>
        <div class="card-body">
            <div class="row">
                <h5 class="col-md-4"><b>Nom : </b></h5>
                <h5 class="col-md-8"><?= htmlspecialchars($produit["nom_produit"]) ?></h5>
            </div>
            <hr>
            <div class="row">
                <h5 class="col-md-4"><b>Prix : </b></h5>
                <h5 class="col-md-8"><?= htmlspecialchars($produit["prix"]) ?> €</h5>
            </div>
            <hr>
            <div class="row">
                <h5 class="col-md-4"><b>Description : </b></h5>
                <h5 class="col-md-8"><?= htmlspecialchars($produit["description"]) ?></h5>
            </div>
            <hr>
            <div class="row">
                <h5 class="col-md-4"><b>Quantité : </b></h5>
                <h5 class="col-md-8"><?= htmlspecialchars($produit["quantité"]) ?></h5>
            </div>
        </div>
    </div>   
    <center class="mt-3">   
        <button class="btn btn-danger" onclick="window.history.back();">Annuler</button>
        <a href="modifier_produit.php?id=<?= htmlspecialchars($produit['id_produit']) ?>" class="btn btn-secondary">Modifier</a>
    </center>  
</div>  

<?php include('../include/footer.php'); ?>