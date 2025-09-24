<?php
include("../connexion.php");
include("../include/header.php");
include("../include/sidebar.php");
?>

<?php
if ($_GET['id']) {
    $id = $_GET['id'];
    $sql = "SELECT * FROM utilisateur WHERE id = :id";
    $statement = $connect->prepare($sql);
    $statement->bindValue(':id', $id, PDO::PARAM_INT);
    $statement->execute();
    $utilisateurs = $statement->fetchAll(PDO::FETCH_ASSOC);
    $utilisateur = $utilisateurs[0];
} else {
    echo "Aucun élément sélectionné";
}
?>

<h2 class="mt-3 text-center">GESTION DES UTILISATEURS</h2>   
<div class="container">
    <div class="card mt-5" style="margin-left: 10%;">
        <div class="card-header bg-primary">
            <h4 class="text-white">Détails d'un utilisateur</h4>
        </div>
        <div class="card-body">
            <div class="row">
                <h5 class="col-md-4"><b>Nom : </b></h5>
                <h5 class="col-md-8"><?=$utilisateur["nom"]?></h5>
            </div>
            <hr>
            <div class="row">
                <h5 class="col-md-4"><b>Email : </b></h5>
                <h5 class="col-md-8"><?=$utilisateur["email"]?></h5>
            </div>
            <hr>
            <div class="row">
                <h5 class="col-md-4"><b>Rôle : </b></h5>
                <h5 class="col-md-8"><?=$utilisateur["role"]?></h5>
            </div>
        </div>
    </div>   
    <center class="mt-3">   
        <button class="btn btn-danger" onclick="window.history.back();">Retour</button>
    </center>  
</div>  

<?php
include("../include/footer.php");
?>
