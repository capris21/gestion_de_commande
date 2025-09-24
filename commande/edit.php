<?php
include("../connexion.php");
include("../include/header.php");
include("../include/sidebar.php");

if (isset($_GET['id'])) {
    $id_commande = $_GET['id'];

    // Récupérer la commande à modifier
    $sql = "SELECT * FROM Commandes WHERE id_commande = :id"; // Assurez-vous que la table s'appelle 'Commandes'
    $statement = $connect->prepare($sql);
    $statement->bindValue(':id', $id_commande, PDO::PARAM_INT);
    $statement->execute();
    $commande = $statement->fetch(PDO::FETCH_ASSOC);

    if (!$commande) {
        echo "Commande non trouvée.";
        exit;
    }

    // Récupérer les clients pour le formulaire
    $clients = $connect->query("SELECT * FROM Clients")->fetchAll(PDO::FETCH_ASSOC);

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        // Mettre à jour la commande
        $dateCommande = $_POST['dateCommande'];
        $client_id = $_POST['client_id'];
        $etat = $_POST['etat']; // État de la commande

        $sql = "UPDATE Commandes SET date = :dateCommande, id_client = :client_id, état = :etat WHERE id_commande = :id";
        $statement = $connect->prepare($sql);
        $statement->bindValue(':dateCommande', $dateCommande);
        $statement->bindValue(':client_id', $client_id);
        $statement->bindValue(':etat', $etat);
        $statement->bindValue(':id', $id_commande, PDO::PARAM_INT);

        if ($statement->execute()) {
            header('Location: index.php?message=Commande mise à jour avec succès');
            exit();
        } else {
            echo "Erreur lors de la mise à jour de la commande.";
        }
    }
}
?>

<h2 class="mt-3 text-center">Modifier la Commande</h2>
<div class="container">
    <form method="POST">
        <div class="mb-3">
            <label for="dateCommande" class="form-label">Date de Commande</label>
            <input type="date" class="form-control" name="dateCommande" value="<?= htmlspecialchars($commande['date']) ?>" required>
        </div>
        <div class="mb-3">
            <label for="client_id" class="form-label">Client</label>
            <select name="client_id" class="form-select" required>
                <?php foreach ($clients as $client) : ?>
                    <option value="<?= htmlspecialchars($client['id_client']) ?>" <?= $client['id_client'] == $commande['id_client'] ? 'selected' : '' ?>>
                        <?= htmlspecialchars($client['nom']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="mb-3">
            <label for="etat" class="form-label">État</label>
            <select name="etat" class="form-select" required>
                <option value="Confirmée" <?= $commande['état'] == 'Confirmée' ? 'selected' : '' ?>>Confirmée</option>
                <option value="Annulée" <?= $commande['état'] == 'Annulée' ? 'selected' : '' ?>>Annulée</option>
                <option value="En attente" <?= $commande['état'] == 'En attente' ? 'selected' : '' ?>>En attente</option>
            </select>
        </div>
        <button type="submit" class="btn btn-primary">Mettre à jour</button>
    </form>
</div>

<?php include('../include/footer.php'); ?>