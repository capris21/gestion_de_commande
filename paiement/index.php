<?php
include("../connexion.php");
include("../include/header.php");
include("../include/sidebar.php");
?>

<h2 class="mt-4 text-center text-primary fw-bold">GESTION DES PAIEMENTS</h2>
<div class="container my-5">
    <div class="card shadow-lg" style="max-width: 1100px; margin: 0 auto;">
        <div class="card-header bg-gradient-primary text-white d-flex justify-content-between align-items-center">
            <h4 class="mb-0">Liste des paiements</h4>
            <a href="create.php" class="btn btn-success btn-sm">
                <i class="bi bi-plus-circle"></i> Ajouter
            </a>
        </div>
        <div class="card-body bg-light">
            <table class="table table-hover table-bordered align-middle">
                <thead class="table-dark text-center">
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">Montant</th>
                        <th scope="col">Date</th>
                        <th scope="col">Méthode</th>
                        <th scope="col">ID Commande</th>
                        <th scope="col">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $sql = "SELECT * FROM Paiements";
                    $statement = $connect->prepare($sql);
                    $statement->execute();
                    $paiements = $statement->fetchAll(PDO::FETCH_ASSOC);

                    if ($statement->rowCount() > 0) {
                        foreach ($paiements as $paiement) {
                    ?>
                    <tr class="text-center">
                        <th scope="row"><?= htmlspecialchars($paiement['id_paiement']) ?></th>
                        <td><?= htmlspecialchars($paiement['montant']) ?> €</td>
                        <td><?= htmlspecialchars($paiement['date']) ?></td>
                        <td><?= htmlspecialchars($paiement['méthode']) ?></td>
                        <td><?= htmlspecialchars($paiement['id_commande']) ?></td>
                        <td>
                            <a href="show.php?id=<?= $paiement['id_paiement'] ?>" class="btn btn-outline-primary btn-sm" title="Détails">
                                <i class="bi bi-eye"></i>
                            </a>
                            <a href="edit.php?id=<?= $paiement['id_paiement'] ?>" class="btn btn-outline-info btn-sm" title="Modifier">
                                <i class="bi bi-pencil"></i>
                            </a>
                            <a href="delete.php?id=<?= $paiement['id_paiement'] ?>" class="btn btn-outline-danger btn-sm" title="Supprimer" onclick="return confirm('Voulez-vous vraiment supprimer ce paiement ?');">
                                <i class="bi bi-trash"></i>
                            </a>
                        </td>
                    </tr>
                    <?php
                        }
                    } else {
                        echo "<tr><td colspan='6' class='text-center'>Aucun paiement trouvé.</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php include('../include/footer.php'); ?>