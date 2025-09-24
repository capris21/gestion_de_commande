<?php
include("../connexion.php");
include("../include/header.php");
include("../include/sidebar.php");
?>

<?php 
if ($_GET['id']) {
    $id = $_GET['id'];
    $sql = "SELECT * FROM utilisateurs WHERE id = :id";
    $statement = $connect->prepare($sql);
    $statement->bindValue(':id', $id, PDO::PARAM_INT);
    $statement->execute();
    $utilisateurs = $statement->fetchAll(PDO::FETCH_ASSOC);
    $utilisateur = $utilisateurs[0];
} else {
    echo "Aucun élément sélectionné";
}
?>

<?php
if (isset($_POST["nom"], $_POST["email"], $_POST["role"])) {
    $nom = $_POST["nom"];
    $email = $_POST["email"];
    $role = $_POST["role"];
    
    // Si vous souhaitez permettre la mise à jour du mot de passe, vous pouvez ajouter un champ pour cela
    $password = isset($_POST["mot_de_passe"]) ? password_hash($_POST["mot_de_passe"], PASSWORD_DEFAULT) : null;

    // Correction de la requête SQL
    $sql = "UPDATE utilisateur SET nom = :nom, email = :email, role = :role" . ($password ? ", mot_de_passe = :mdp" : "") . " WHERE id = :id";
    $statement = $connect->prepare($sql);
    $statement->bindValue(':nom', $nom, PDO::PARAM_STR);
    $statement->bindValue(':email', $email, PDO::PARAM_STR);
    $statement->bindValue(':role', $role, PDO::PARAM_STR);
    if ($password) {
        $statement->bindValue(':mdp', $password, PDO::PARAM_STR);
    }
    $statement->bindValue(':id', $id, PDO::PARAM_INT);
    
    if ($statement->execute()) {
        header('Location: index.php');
        exit();
    } else {
        echo "Erreur lors de la mise à jour des données.";
    }
}
?>

<h2 class="mt-3 text-center">GESTION DES UTILISATEURS</h2>   
<div class="container">
    <div class="card mt-5" style="margin-left: 15%;">
        <div class="card-header bg-primary">
            <h4 class="text-white">Modification d'un utilisateur</h4>
        </div>
        <div class="card-body">
            <form action="" method="POST">
                <input required type="text" value="<?=$utilisateur["id"]?>" hidden name="id">
                <label for="nom">Nom</label>
                <input class="form-control" name="nom" value="<?=$utilisateur["nom"]?>" type="text" required>
                
                <label for="email">Email</label>
                <input class="form-control" name="email" value="<?=$utilisateur["email"]?>" type="email" required>
                
                <label for="role">Rôle</label>
                <select class="form-control" name="role" required>
                    <option value="admin" <?= $utilisateur["role"] === 'admin' ? 'selected' : '' ?>>Admin</option>
                    <option value="caissier" <?= $utilisateur["role"] === 'caissier' ? 'selected' : '' ?>>Caissier</option>
                </select>
                
                <label for="mot_de_passe">Mot de passe (laisser vide pour ne pas changer)</label>
                <input class="form-control" name="mot_de_passe" type="password">
            </div>
            <center class="mt-3">   
                <button type="button" class="btn btn-danger" onclick="window.history.back();">Annuler</button>
                <button type="submit" class="btn btn-secondary">Enregistrer</button>
            </center>
            </form>
        </div>     
    </div>
</div>

<?php
include("../include/footer.php");
?>
