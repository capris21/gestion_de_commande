
<?php
include("../connexion.php");
if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $stmt = $connect->prepare("SELECT prix, quantité FROM produits WHERE id_produit = :id");
    $stmt->bindValue(':id', $id, PDO::PARAM_INT);
    $stmt->execute();
    $produit = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($produit) {
        echo json_encode([
            'prix' => $produit['prix'],
            'quantite' => $produit['quantité']
        ]);
    } else {
        echo json_encode(null);
    }
}
?>