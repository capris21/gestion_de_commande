<?php
include("../connexion.php");
include("../include/header.php");
include("../include/sidebar.php");
?>

<?php
if (isset($_POST["nom_produit"], $_POST["prix"], $_POST["description"], $_POST["quantité"])) {
    $nom_produit = $_POST["nom_produit"];
    $prix = $_POST["prix"];
    $description = $_POST["description"];
    $quantite = $_POST["quantité"]; // variable sans accent

    $sql = "INSERT INTO Produits (nom_produit, prix, description, quantité) VALUES (:nom_produit, :prix, :description, :quantite)";
    $statement = $connect->prepare($sql);
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
    <div class="card mt-5" style="margin-left: 10%; margin-right: 15px;">
        <div class="card-header bg-primary">
            <h4 class="text-white">FORMULAIRE D'AJOUT</h4>
        </div>
        <div class="card-body">
            <form action="" method="POST">
                <table class="table table-striped">
                    <tr>
                        <td>
                            <label for="nom_produit">Nom du Produit</label>
                            <input class="form-control" name="nom_produit" type="text" required>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <label for="prix">Prix</label>
                            <input class="form-control" name="prix" type="number" step="0.01" required>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <label for="description">Description</label>
                            <textarea class="form-control" name="description" required></textarea>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <label for="quantité">Quantité</label>
                            <input class="form-control" name="quantité" type="number" required>
                        </td>
                    </tr>
                </table>
                <center class="mt-3">   
                    <button type="reset" class="btn btn-danger">Annuler</button>
                    <button type="submit" class="btn btn-secondary">Enregistrer</button>
                </center>
            </form>
        </div>     
    </div>
</div>

<?php include('../include/footer.php'); ?>