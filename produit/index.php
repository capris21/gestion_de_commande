<?php
session_start();
if (!isset($_SESSION['user'])) {
    header('Location: ../login.php');
    exit();
}
include("../connexion.php");
include("../include/header.php");
include("../include/sidebar.php");
?>

<?php
// Récupérer tous les produits
$sql = "SELECT * FROM Produits";
$statement = $connect->prepare($sql);
$statement->execute();
$produits = $statement->fetchAll(PDO::FETCH_ASSOC);
?>

<h2 class="mt-4 text-center text-primary fw-bold">GESTION DES PRODUITS</h2>
<div class="container my-5">
    <div class="card shadow-lg" style="max-width: 1100px; margin: 0 auto;">
        <div class="card-header bg-gradient-primary text-white d-flex justify-content-between align-items-center">
            <h4 class="mb-0">Liste des produits</h4>
            <a href="create.php" class="btn btn-success btn-sm">
                <i class="bi bi-plus-circle"></i> Ajouter
            </a>
        </div>
        <div class="card-body bg-light">
            <table class="table table-hover table-bordered align-middle">
                <thead class="table-dark text-center">
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">Nom du Produit</th>
                        <th scope="col">Prix</th>
                        <th scope="col">Description</th>
                        <th scope="col">Quantité</th>
                        <th scope="col">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if ($statement->rowCount() > 0) {
                        foreach ($produits as $produit) {
                    ?>
                    <tr class="text-center">
                        <th scope="row"><?= htmlspecialchars($produit['id_produit']) ?></th>
                        <td><?= htmlspecialchars($produit['nom_produit']) ?></td>
                        <td><?= htmlspecialchars($produit['prix']) ?> €</td>
                        <td><?= htmlspecialchars($produit['description']) ?></td>
                        <td><?= htmlspecialchars($produit['quantité']) ?></td>
                        <td>
                            <a href="show.php?id=<?= $produit['id_produit'] ?>" class="btn btn-outline-primary btn-sm" title="Détails">
                                <i class="bi bi-eye"></i>
                            </a>
                            <a href="edit.php?id=<?= $produit['id_produit'] ?>" class="btn btn-outline-info btn-sm" title="Modifier">
                                <i class="bi bi-pencil"></i>
                            </a>
                            <a href="delete.php?id=<?= $produit['id_produit'] ?>" class="btn btn-outline-danger btn-sm" title="Supprimer" onclick="return confirm('Voulez-vous vraiment supprimer ce produit ?');">
                                <i class="bi bi-trash"></i>
                            </a>
                        </td>
                    </tr>
                    <?php
                        }
                    } else {
                        echo "<tr><td colspan='6' class='text-center'>Aucun produit trouvé.</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php include('../include/footer.php'); ?>