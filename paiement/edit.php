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

<?php
if (isset($_POST["montant"], $_POST["date"], $_POST["méthode"], $_POST["id_commande"])) {
    $id = $_POST["id"];
    $montant = $_POST["montant"];
    $date = $_POST["date"];
    $méthode = $_POST["méthode"];
    $id_commande = $_POST["id_commande"];

    $sql = "UPDATE Paiements SET montant = :montant, date = :date, méthode = :methode, id_commande = :id_commande WHERE id_paiement = :id";
    $statement = $connect->prepare($sql);
    $statement->bindValue(':montant', $montant, PDO::PARAM_STR);
    $statement->bindValue(':date', $date, PDO::PARAM_STR);
    $statement->bindValue(':methode', $méthode, PDO::PARAM_STR); // paramètre sans accent
    $statement->bindValue(':id_commande', $id_commande, PDO::PARAM_INT);
    $statement->bindValue(':id', $id, PDO::PARAM_INT);
    $statement->execute();

    header('Location: index.php');
    exit();
}
?>

<h2 class="mt-3 text-center">MODIFIER UN PAIEMENT</h2>   
<div class="container">
    <div class="card mt-5">
        <div class="card-header bg-primary">
            <h4 class="text-white">Formulaire de modification</h4>
        </div>
        <div class="card-body">
            <form action="" method="POST">
                <input type="hidden" name="id" value="<?= htmlspecialchars($paiement["id_paiement"]) ?>" required>
                
                <label for="montant">Montant</label>
                <input class="form-control" name="montant" value="<?= htmlspecialchars($paiement["montant"]) ?>" type="number" step="0.01" required>
                
                <label for="date">Date</label>
                <input class="form-control" name="date" value="<?= htmlspecialchars($paiement["date"]) ?>" type="date" required>
                
                <label for="méthode">Méthode</label>
                <input class="form-control" name="méthode" value="<?= htmlspecialchars($paiement["méthode"]) ?>" type="text" required>

                <label for="id_commande">ID Commande</label>
                <input class="form-control" name="id_commande" value="<?= htmlspecialchars($paiement["id_commande"]) ?>" type="number" required>
                
                <center class="mt-3">   
                    <button type="button" class="btn btn-danger" onclick="window.history.back();">Annuler</button>
                    <button type="submit" class="btn btn-secondary">Enregistrer</button>
                </center>
            </form>
        </div>     
    </div>
</div>

<?php include('../include/footer.php'); ?>