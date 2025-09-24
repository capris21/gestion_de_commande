<?php
include("../connexion.php");
include("../include/header.php");
include("../include/sidebar.php");

if (isset($_GET['id'])) {
    $id_commande = $_GET['id'];

    // Récupérer les détails de la commande
    $sql = "SELECT * FROM Commandes WHERE id_commande = :id"; // Assurez-vous que la table s'appelle 'Commandes'
    $statement = $connect->prepare($sql);
    $statement->bindValue(':id', $id_commande, PDO::PARAM_INT);
    $statement->execute();
    $commande = $statement->fetch(PDO::FETCH_ASSOC);

    if (!$commande) {
        echo "Commande non trouvée.";
        exit;
    }

    // Récupérer le client associé
    $sql = "SELECT * FROM Clients WHERE id_client = :id_client"; // Assurez-vous que la table s'appelle 'Clients'
    $statement = $connect->prepare($sql);
    $statement->bindValue(':id_client', $commande['id_client'], PDO::PARAM_INT);
    $statement->execute();
    $client = $statement->fetch(PDO::FETCH_ASSOC);
}
?>

<h2 class="mt-3 text-center">Détails de la Commande</h2>
<div class="container">
    <table class="table">
        <tr>
            <th>ID Commande</th>
            <td><?= htmlspecialchars($commande['id_commande']) ?></td>
        </tr>
        <tr>
            <th>Date de Commande</th>
            <td><?= htmlspecialchars($commande['date']) ?></td>
        </tr>
        <tr>
            <th>État</th>
            <td><?= htmlspecialchars($commande['état']) ?></td>
        </tr>
        <tr>
            <th>Client</th>
            <td><?= htmlspecialchars($client['nom']) ?></td>
        </tr>
    </table>
    <a href="index.php" class="btn btn-secondary">Retour</a>
</div>

<?php include('../include/footer.php'); ?>