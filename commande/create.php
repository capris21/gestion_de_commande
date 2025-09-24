<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
session_start();
include("../connexion.php");
include("../include/header.php");
include("../include/sidebar.php");

// Vérifiez si l'utilisateur est connecté
if (!isset($_SESSION['user'])) {
    header('Location: login.php');
    exit();
}

// Récupérer les clients avec leurs emails
$sql = "SELECT * FROM Clients";
$statementClient = $connect->prepare($sql);
$statementClient->execute();
$clients = $statementClient->fetchAll(PDO::FETCH_ASSOC);

// Récupérer les produits pour la liste déroulante
$produits = $connect->query("SELECT id_produit, nom_produit FROM Produits")->fetchAll(PDO::FETCH_ASSOC);

// Traitement du formulaire
if (
    isset($_POST["dateCommande"], $_POST["client_id"], $_POST["produit"], $_POST["quantite"])
    && !empty($_POST["produit"]) && !empty($_POST["quantite"])
) {
    $dateCommande = $_POST["dateCommande"];
    $client_id = $_POST["client_id"];
    $produit_id = $_POST["produit"];
    $quantite = intval($_POST["quantite"]);
    $etat = "Confirmée"; // État par défaut de la commande

    // Récupérer les informations du client
    $client = array_filter($clients, function($c) use ($client_id) {
        return $c['id_client'] == $client_id;
    });
    $client = reset($client);

    // Vérifier la quantité disponible
    $stmtProduit = $connect->prepare("SELECT quantité FROM Produits WHERE id_produit = :id");
    $stmtProduit->bindValue(':id', $produit_id, PDO::PARAM_INT);
    $stmtProduit->execute();
    $produitInfo = $stmtProduit->fetch(PDO::FETCH_ASSOC);

    if (!$produitInfo) {
        $error_message = "Produit introuvable.";
    } elseif ($quantite < 1 || $quantite > $produitInfo['quantité']) {
        $error_message = "Quantité demandée invalide ou supérieure au stock disponible.";
    } else {
        // Démarrer une transaction
        $connect->beginTransaction();

        try {
            // Insertion commande
            $sql = "INSERT INTO commandes(date, état, id_client)
                    VALUES (:dateCommande, :etat, :client_id);";
            $statement = $connect->prepare($sql);
            $statement->bindValue(':dateCommande', $dateCommande, PDO::PARAM_STR);
            $statement->bindValue(':etat', $etat, PDO::PARAM_STR);
            $statement->bindValue(':client_id', $client_id, PDO::PARAM_INT);
            
            if ($statement->execute()) {
                $commande_id = $connect->lastInsertId();

                // Insertion dans la table de liaison détails_commande
                $sqlLiaison = "INSERT INTO détails_commande (id_commande, id_produit, quantité) VALUES (:id_commande, :id_produit, :quantité)";
                $stmtLiaison = $connect->prepare($sqlLiaison);
                $stmtLiaison->bindValue(':id_commande', $commande_id, PDO::PARAM_INT);
                $stmtLiaison->bindValue(':id_produit', $produit_id, PDO::PARAM_INT);
                $stmtLiaison->bindValue(':quantité', $quantite, PDO::PARAM_INT); // paramètre avec accent
                $stmtLiaison->execute();

                // Mettre à jour la quantité du produit
                $sqlMaj = "UPDATE produits SET quantité = quantité - :quantité WHERE id_produit = :id_produit";
                $stmtMaj = $connect->prepare($sqlMaj);
                $stmtMaj->bindValue(':quantité', $quantite, PDO::PARAM_INT); // paramètre avec accent
                $stmtMaj->bindValue(':id_produit', $produit_id, PDO::PARAM_INT);
                $stmtMaj->execute();

                $connect->commit();
                $_SESSION['success_message'] = "Commande enregistrée avec succès.";
                header('Location: index.php');
                exit();
            } else {
                throw new Exception("Erreur lors de l'ajout de la commande.");
            }
        } catch (Exception $e) {
            $connect->rollBack();
            $error_message = $e->getMessage();
        }
    }
}
?>

<h2 class="mt-3 text-center">AJOUTER UNE COMMANDE</h2>   
<div class="container">
    <?php if (isset($error_message)): ?>
        <div class="alert alert-danger"><?php echo htmlspecialchars($error_message); ?></div>
    <?php endif; ?>
    
    <?php if (isset($_SESSION['success_message'])): ?>
        <div class="alert alert-success"><?php echo htmlspecialchars($_SESSION['success_message']); unset($_SESSION['success_message']); ?></div>
    <?php endif; ?>
    
    <div class="card mt-5" style="margin-left: 10%; margin-right: 15px;">
        <div class="card-header bg-primary">
            <h4 class="text-white">FORMULAIRE D'AJOUT</h4>
        </div>
        <div class="card-body">
            <form action="" method="POST">
                <table class="table table-striped">
                    <tr>
                        <td>
                            <label for="dateCommande">Date de Commande</label>
                            <input class="form-control" name="dateCommande" type="date" required>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <label for="client_id">Client</label>
                            <select class="form-control" name="client_id" required>
                                <option value="">Sélectionnez un client</option>
                                <?php foreach ($clients as $client): ?>
                                    <option value="<?= htmlspecialchars($client['id_client']) ?>">
                                        <?= htmlspecialchars($client['nom'] . ' ' . ($client['prenom'] ?? '')) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <label for="produit">Produit</label>
                            <select id="produit" name="produit" class="form-control" required>
                                <option value="">Sélectionnez un produit</option>
                                <?php
                                foreach ($produits as $produit) {
                                    echo '<option value="'.$produit['id_produit'].'">'.htmlspecialchars($produit['nom_produit']).'</option>';
                                }
                                ?>
                            </select>

                            <div id="infos-produit" style="margin-top:10px; display:none;">
                                <p>Prix unitaire : <span id="prix-produit"></span> €</p>
                                <p>Quantité disponible : <span id="quantite-dispo"></span></p>
                                <label for="quantite">Quantité à commander</label>
                                <input type="number" id="quantite" name="quantite" class="form-control" min="1">
                            </div>
                        </td>
                    </tr>
                </table>
                <center class="mt-3">   
                    <button type="reset" class="btn btn-danger">Annuler</button>
                    <button type="submit" class="btn btn-secondary">Enregistrer</button>
                </center>
            </form>
        </div>     
    </div>
</div>

<!-- JQuery et script AJAX pour récupérer prix et quantité -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$('#produit').on('change', function() {
    var idProduit = $(this).val();
    if (idProduit) {
        $.ajax({
            url: 'get_produit_info.php',
            type: 'GET',
            data: { id: idProduit },
            dataType: 'json',
            success: function(data) {
                if (data) {
                    $('#prix-produit').text(data.prix);
                    $('#quantite-dispo').text(data.quantite);
                    $('#quantite').attr('max', data.quantite);
                    $('#infos-produit').show();
                } else {
                    $('#infos-produit').hide();
                }
            }
        });
    } else {
        $('#infos-produit').hide();
    }
});
</script>

<?php include('../include/footer.php'); ?>