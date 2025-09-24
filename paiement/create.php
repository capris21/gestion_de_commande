<?php
include("../connexion.php");
include("../include/header.php");
include("../include/sidebar.php");

// Récupérer les commandes pour la liste déroulante
$commandes = [];
$sql = "SELECT id_commande, date FROM Commandes";
$stmt = $connect->prepare($sql);
$stmt->execute();
$commandes = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<?php
if (isset($_POST["montant"], $_POST["date"], $_POST["méthode"], $_POST["id_commande"])) {
    $montant = $_POST["montant"];
    $date = $_POST["date"];
    $methode = $_POST["méthode"]; // variable sans accent
    $id_commande = $_POST["id_commande"];

    $sql = "INSERT INTO Paiements(montant, date, méthode, id_commande) VALUES (:montant, :date, :methode, :id_commande)";
    $statement = $connect->prepare($sql);
    $statement->bindValue(':montant', $montant, PDO::PARAM_STR);
    $statement->bindValue(':date', $date, PDO::PARAM_STR);
    $statement->bindValue(':methode', $methode, PDO::PARAM_STR); // paramètre sans accent
    $statement->bindValue(':id_commande', $id_commande, PDO::PARAM_INT);
    $statement->execute();

    header('Location: index.php');
    exit();
}
?>

<h2 class="mt-3 text-center">AJOUTER UN PAIEMENT</h2>   
<div class="container">
    <div class="card mt-5">
        <div class="card-header bg-primary">
            <h4 class="text-white">Formulaire d'ajout</h4>
        </div>
        <div class="card-body">
            <form action="" method="POST">
                <label for="montant">Montant</label>
                <input class="form-control" name="montant" type="number" step="0.01" required>
                
                <label for="date">Date</label>
                <input class="form-control" name="date" type="date" required>
                
                <label for="méthode">Méthode</label>
                <input class="form-control" name="méthode" type="text" required>

                <label for="id_commande">Commande</label>
                <select class="form-control" name="id_commande" required>
                    <option value="">Sélectionnez une commande</option>
                    <?php foreach ($commandes as $commande): ?>
                        <option value="<?= htmlspecialchars($commande['id_commande']) ?>">
                            <?= htmlspecialchars($commande['id_commande']) ?> - <?= htmlspecialchars($commande['date']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
                
                <center class="mt-3">   
                    <button type="reset" class="btn btn-danger">Annuler</button>
                    <button type="submit" class="btn btn-secondary">Enregistrer</button>
                </center>
            </form>
        </div>     
    </div>
</div>

<?php include('../include/footer.php'); ?>