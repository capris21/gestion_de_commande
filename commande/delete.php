<?php
include("../connexion.php");

if (isset($_GET['id'])) {
    $id_commande = $_GET['id'];

    // Supprimer la commande
    $sql = "DELETE FROM Commandes WHERE id_commande = :id";
    $statement = $connect->prepare($sql);
    $statement->bindValue(':id', $id_commande, PDO::PARAM_INT);

    if ($statement->execute()) {
        header('Location: index.php?message=Commande supprimée avec succès');
        exit();
    } else {
        echo "Erreur lors de la suppression de la commande.";
    }
} else {
    echo "ID de commande non spécifié.";
}
?>