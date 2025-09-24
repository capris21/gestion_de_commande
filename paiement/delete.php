<?php
include("../connexion.php");

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $sql = "DELETE FROM Paiements WHERE id_paiement = :id"; // Assurez-vous que la table s'appelle 'Paiements'
    $statement = $connect->prepare($sql);
    $statement->bindValue(':id', $id, PDO::PARAM_INT);

    if ($statement->execute()) {
        header('Location: index.php?message=Paiement supprimé avec succès');
        exit();
    } else {
        echo "Erreur lors de la suppression du paiement.";
    }
} else {
    echo "Aucun élément sélectionné.";
}
?>