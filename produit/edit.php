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

<?php
if (isset($_POST["id"], $_POST["nom_produit"], $_POST["prix"], $_POST["description"], $_POST["quantité"])) {
    $id = $_POST["id"];
    $nom_produit = $_POST["nom_produit"];
    $prix = $_POST["prix"];
    $description = $_POST["description"];
    $quantite = $_POST["quantité"]; // variable sans accent

    $sql = "UPDATE Produits SET nom_produit = :nom_produit, prix = :prix, description = :description, quantité = :quantite WHERE id_produit = :id";
    $statement = $connect->prepare($sql);
    $statement->bindValue(':id', $id, PDO::PARAM_INT);
    $statement->bindValue(':nom_produit', $nom_produit, PDO::PARAM_STR);
    $statement->bindValue(':prix', $prix, PDO::PARAM_STR);
    $statement->bindValue(':description', $description, PDO::PARAM_STR);
    $statement->bindValue(':quantite', $quantite, PDO::PARAM_INT); // paramètre sans accent
    $statement->execute();

    header('Location: index.php');
    exit();
}
?>

<h2 class="mt-3 text-center">GESTION DES PRODUITS</h2>   
<div class="container">
    <div class="card mt-5" style="margin-left: 15%;">
        <div class="card-header bg-primary">
            <h4 class="text-white">Modification d'un produit</h4>
        </div>
        <div class="card-body">
            <form action="" method="POST">
                <input required type="hidden" value="<?= htmlspecialchars($produit["id_produit"]) ?>" name="id">
                
                <label for="nom_produit">Nom du Produit</label>
                <input class="form-control" name="nom_produit" value="<?= htmlspecialchars($produit["nom_produit"]) ?>" type="text" required>
                
                <label for="prix">Prix</label>
                <input class="form-control" name="prix" value="<?= htmlspecialchars($produit["prix"]) ?>" type="number" step="0.01" required>
                
                <label for="description">Description</label>
                <textarea class="form-control" name="description" required><?= htmlspecialchars($produit["description"]) ?></textarea>
                
                <label for="quantité">Quantité</label>
                <input class="form-control" name="quantité" value="<?= htmlspecialchars($produit["quantité"]) ?>" type="number" required>
                
                <center class="mt-3">   
                    <button type="button" class="btn btn-danger" onclick="window.history.back();">Annuler</button>
                    <button type="submit" class="btn btn-secondary">Enregistrer</button>
                </center>
            </form>
        </div>     
    </div>
</div>

<?php include('../include/footer.php'); ?>