<?php
include("../connexion.php");
?>

<?php
if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $sql = "DELETE FROM Produits WHERE id_produit = :id"; // Assurez-vous que la table s'appelle 'Produits'
    $statement = $connect->prepare($sql);
    $statement->bindValue(':id', $id, PDO::PARAM_INT);
    
    if ($statement->execute()) {
        header('Location: index.php?message=Produit supprimé avec succès');
        exit();
    } else {
        echo "Erreur lors de la suppression du produit.";
    }
} else {
    echo "Aucun élément sélectionné.";
}
?>