<?php
session_start();
if (!isset($_SESSION['user'])) {
    header('Location: ../login.php');
    exit();
}
include("../connexion.php");
include("../include/header.php");
include("../include/sidebar.php");

// Récupérer les commandes
$sql = "SELECT * FROM Commandes";
$statement = $connect->prepare($sql);

if ($statement->execute()) {
    $commandes = $statement->fetchAll(PDO::FETCH_ASSOC);
} else {
    echo "Erreur lors de la récupération des commandes.";
    $commandes = [];
}
?>

<h2 class="mt-4 text-center text-primary fw-bold">GESTION DES COMMANDES</h2>
<div class="container my-5">
    <div class="card shadow-lg" style="max-width: 1100px; margin: 0 auto;">
        <div class="card-header bg-gradient-primary text-white d-flex justify-content-between align-items-center">
            <h4 class="mb-0">Liste des commandes</h4>
            <a href="create.php" class="btn btn-success btn-sm">
                <i class="bi bi-plus-circle"></i> Ajouter
            </a>
        </div>
        <div class="card-body bg-light">
            <table class="table table-hover table-bordered align-middle">
                <thead class="table-dark text-center">
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">Date de Commande</th>
                        <th scope="col">Client</th>
                        <th scope="col">État</th>
                        <th scope="col">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if (count($commandes) > 0) {
                        foreach ($commandes as $commande) {
                            // Récupérer le client
                            $sql = "SELECT * FROM Clients WHERE id_client = :client_id";
                            $statementClient = $connect->prepare($sql);
                            $statementClient->bindValue(':client_id', $commande['id_client'], PDO::PARAM_INT);
                            $statementClient->execute();
                            $client = $statementClient->fetch(PDO::FETCH_ASSOC);
                    ?>
                    <tr class="text-center">
                        <th scope="row"><?= htmlspecialchars($commande["id_commande"]) ?></th>
                        <td><?= htmlspecialchars($commande["date"]) ?></td>
                        <td><?= htmlspecialchars($client["nom"]) ?></td>
                        <td><?= htmlspecialchars($commande["état"]) ?></td>
                        <td>
                            <a href="show.php?id=<?= $commande["id_commande"] ?>" class="btn btn-outline-primary btn-sm" title="Détails">
                                <i class="bi bi-eye"></i>
                            </a>
                            <a href="edit.php?id=<?= $commande["id_commande"] ?>" class="btn btn-outline-info btn-sm" title="Modifier">
                                <i class="bi bi-pencil"></i>
                            </a>
                            <a href="delete.php?id=<?= $commande["id_commande"] ?>" class="btn btn-outline-danger btn-sm" title="Supprimer" onclick="return confirm('Voulez-vous vraiment supprimer cette commande ?');">
                                <i class="bi bi-trash"></i>
                            </a>
                        </td>
                    </tr>
                    <?php
                        }
                    } else {
                        echo "<tr><td colspan='5' class='text-center'>Aucune commande trouvée.</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php include('../include/footer.php'); ?>