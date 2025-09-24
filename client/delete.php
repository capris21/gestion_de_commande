<?php
include("../connexion.php");

// Vérifier si l'ID est présent dans l'URL
if (isset($_GET['id'])) {
    $id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT); // Sécuriser l'entrée

    // Préparer la requête de suppression
    $sql = "DELETE FROM Clients WHERE id_client = :id"; // Assurez-vous que le nom de la colonne est correct
    $statement = $connect->prepare($sql);
    $statement->bindValue(':id', $id, PDO::PARAM_INT);

    // Exécuter la requête
    if ($statement->execute()) {
        header('Location: index.php?message=Client supprimé avec succès');
        exit();
    } else {
        echo "<div class='alert alert-danger'>Erreur lors de la suppression du client.</div>";
    }
} else {
    echo "<div class='alert alert-warning'>Aucun élément sélectionné.</div>";
}
?>