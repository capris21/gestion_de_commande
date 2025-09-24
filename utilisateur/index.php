<?php
session_start();
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    header('Location: ../login.php');
    exit();
}
include("../connexion.php");
include("../include/header.php");
include("../include/sidebar.php");
?>

<?php
    $sql= "SELECT * FROM utilisateurs";
    $statement = $connect->prepare($sql);
    $statement->execute();
    $utilisateur = $statement->fetchAll(PDO::FETCH_ASSOC);
?>

<h2 class="mt-4 text-center text-primary fw-bold">GESTION DES UTILISATEURS</h2>
<div class="container my-5">
    <div class="card shadow-lg" style="max-width: 900px; margin: 0 auto;">
        <div class="card-header bg-gradient-primary text-white d-flex justify-content-between align-items-center">
            <h4 class="mb-0">Liste des utilisateurs</h4>
            <a href="create.php" class="btn btn-success btn-sm">
                <i class="bi bi-person-plus"></i> Ajouter un utilisateur
            </a>
        </div>
        <div class="card-body bg-light">
            <table class="table table-hover table-bordered align-middle">
                <thead class="table-dark text-center">
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">Nom</th>
                        <th scope="col">Rôle</th>
                        <th scope="col">Email</th>
                        <th scope="col">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($utilisateur as $item): ?>
                    <tr class="text-center">
                        <th scope="row"><?= htmlspecialchars($item["id"]) ?></th>
                        <td><?= htmlspecialchars($item["nom"]) ?></td>
                        <td>
                            <span class="badge bg-<?= $item["role"] === "admin" ? "primary" : "secondary" ?>">
                                <?= htmlspecialchars(ucfirst($item["role"])) ?>
                            </span>
                        </td>
                        <td><?= htmlspecialchars($item["email"]) ?></td>
                        <td>
                            <a href="show.php?id=<?= $item["id"] ?>" class="btn btn-outline-primary btn-sm" title="Détail">
                                <i class="bi bi-eye"></i>
                            </a>
                            <a href="edit.php?id=<?= $item["id"] ?>" class="btn btn-outline-info btn-sm" title="Modifier">
                                <i class="bi bi-pencil"></i>
                            </a>
                            <a href="delete.php?id=<?= $item["id"] ?>" class="btn btn-outline-danger btn-sm" title="Supprimer" onclick="return confirm('Voulez-vous vraiment supprimer cet utilisateur ?');">
                                <i class="bi bi-trash"></i>
                            </a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            <?php if (empty($utilisateur)): ?>
                <div class="alert alert-warning text-center mt-3">Aucun utilisateur trouvé.</div>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php
include("../include/footer.php");
?>