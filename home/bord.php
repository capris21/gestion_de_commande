<?php
session_start();
require_once('../connexion.php');

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['user'])) {
    header('Location: ../login.php');
    exit();
}

// Récupérer les statistiques
$stats = [];

try {
    // Compter le nombre de lignes dans chaque table
    $tables = [
        'clients', 
        'commandes', 
        'produits', 
        'paiements',
        'utilisateurs'
    ];
    
    foreach ($tables as $table) {
        $stmt = $connect->query("SELECT COUNT(*) as total FROM $table");
        $stats[$table] = $stmt->fetch()['total'];
    }
    
    
} catch (PDOException $e) {
    $error = "Erreur lors de la récupération des données: " . $e->getMessage();
}

include("../include/header.php");
include("../include/sidebar.php");
?>

<div class="main-content">
    <div class="container-fluid">
        <!-- En-tête du tableau de bord -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="h3 mb-0 text-primary">Statistiques de la base de données</h1>
            <div class="text-muted">
                <i class="fas fa-database me-2"></i>
                <?= date('d/m/Y') ?>
            </div>
        </div>
        
        <!-- Cartes de statistiques -->
        <div class="row g-4 mb-4">
            <!-- Clients -->
            <div class="col-xl-3 col-md-6">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="text-uppercase text-muted mb-2">Clients</h6>
                                <h2 class="mb-0"><?= number_format($stats['clients'] ?? 0, 0, ',', ' ') ?></h2>
                            </div>
                            <div class="icon-shape bg-primary bg-opacity-10 text-primary rounded-3 p-3">
                                <i class="fas fa-users fa-2x"></i>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer bg-transparent border-0 pt-0">
                        <a href="../clients/" class="text-primary text-decoration-none small">
                            Voir la liste complète <i class="fas fa-arrow-right ms-1"></i>
                        </a>
                    </div>
                </div>
            </div>
            
            <!-- commandes -->
            <div class="col-xl-3 col-md-6">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="text-uppercase text-muted mb-2">Commandes</h6>
                                <h2 class="mb-0"><?= number_format($stats['commandes'] ?? 0, 0, ',', ' ') ?></h2>
                            </div>
                            <div class="icon-shape bg-success bg-opacity-10 text-success rounded-3 p-3">
                                <i class="fas fa-map-marked-alt fa-2x"></i>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer bg-transparent border-0 pt-0">
                        <a href="../commandes/" class="text-success text-decoration-none small">
                            Voir les commandes <i class="fas fa-arrow-right ms-1"></i>
                        </a>
                    </div>
                </div>
            </div>
            
            <!-- commandes -->
            <div class="col-xl-3 col-md-6">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="text-uppercase text-muted mb-2">commandes</h6>
                                <h2 class="mb-0"><?= number_format($stats['reservations'] ?? 0, 0, ',', ' ') ?></h2>
                            </div>
                            <div class="icon-shape bg-info bg-opacity-10 text-info rounded-3 p-3">
                                <i class="fas fa-calendar-check fa-2x"></i>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer bg-transparent border-0 pt-0">
                        <a href="../reservations/" class="text-info text-decoration-none small">
                            Voir les commandes <i class="fas fa-arrow-right ms-1"></i>
                        </a>
                    </div>
                </div>
            </div>
            
        </div>
        
        <!-- Autres statistiques -->
        <div class="row g-4 mb-4">
            <!-- Utilisateurs -->
            <div class="col-xl-4 col-md-6">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-header bg-white border-0">
                        <h5 class="mb-0">Utilisateurs</h5>
                    </div>
                    <div class="card-body">
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <h2 class="mb-0"><?= number_format($stats['utilisateurs'] ?? 0, 0, ',', ' ') ?></h2>
                                <p class="text-muted mb-0">Utilisateurs enregistrés</p>
                            </div>
                            <div class="icon-shape bg-purple bg-opacity-10 text-purple rounded-3 p-3">
                                <i class="fas fa-user-shield fa-2x"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            
            
                
        </div>
        
        <!-- Tableau des dernières commandes -->
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white border-0 d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Dernières commandes</h5>
                <a href="../reservation/create.php" class="btn btn-sm btn-primary">
                    <i class="fas fa-plus me-1"></i> Nouvelle réservation
                </a>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="bg-light">
                            <tr>
                                <th class="border-0">Client</th>
                                <th class="border-0">Destination</th>
                                <th class="border-0">Date</th>
                                <th class="border-0 text-end">Montant</th>
                                <th class="border-0 text-center">Statut</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($dernieres_reservations)): ?>
                                <?php foreach ($dernieres_reservations as $reservation): ?>
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="avatar-sm bg-light rounded-circle me-2 d-flex align-items-center justify-content-center">
                                                <span class="text-primary fw-bold">
                                                    <?= strtoupper(substr($reservation['prenom'], 0, 1) . substr($reservation['nom'], 0, 1)) ?>
                                                </span>
                                            </div>
                                            <div>
                                                <h6 class="mb-0"><?= htmlspecialchars($reservation['prenom'] . ' ' . $reservation['nom']) ?></h6>
                                                <small class="text-muted">#<?= $reservation['id'] ?></small>
                                            </div>
                                        </div>
                                    </td>
                                    <td><?= htmlspecialchars($reservation['destination']) ?></td>
                                    <td><?= date('d/m/Y', strtotime($reservation['date_reservation'])) ?></td>
                                    <td class="text-end fw-bold"><?= number_format($reservation['montant_total'], 2, ',', ' ') ?> €</td>
                                    <td class="text-center">
                                        <span class="badge bg-<?= 
                                            $reservation['statut'] === 'confirmée' ? 'success' : 
                                            ($reservation['statut'] === 'en_attente' ? 'warning' : 'secondary') 
                                        ?>">
                                            <?= ucfirst(str_replace('_', ' ', $reservation['statut'])) ?>
                                        </span>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="5" class="text-center py-4">Aucune réservation trouvée</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
/* Styles personnalisés */
:root {
    --primary: #4361ee;
    --success: #28a745;
    --info: #17a2b8;
    --warning: #ffc107;
    --purple: #6f42c1;
    --orange: #fd7e14;
    --teal: #20c997;
}

.main-content {
    padding: 2rem;
    min-height: calc(100vh - 70px);
}

.card {
    border-radius: 0.5rem;
    transition: transform 0.2s ease-in-out;
}

.card:hover {
    transform: translateY(-5px);
}

.icon-shape {
    width: 60px;
    height: 60px;
    display: flex;
    align-items: center;
    justify-content: center;
}

.bg-purple {
    background-color: var(--purple) !important;
}

.bg-orange {
    background-color: var(--orange) !important;
}

.bg-teal {
    background-color: var(--teal) !important;
}

.text-purple {
    color: var(--purple) !important;
}

.text-orange {
    color: var(--orange) !important;
}

.text-teal {
    color: var(--teal) !important;
}

.avatar-sm {
    width: 40px;
    height: 40px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    border-radius: 50%;
}

.table th {
    font-weight: 600;
    text-transform: uppercase;
    font-size: 0.75rem;
    letter-spacing: 0.5px;
    border-top: none;
    padding: 1rem;
}

.table td {
    vertical-align: middle;
    padding: 1rem;
}

.badge {
    padding: 0.35em 0.65em;
    font-weight: 500;
}
</style>

<?php include('../include/footer.php'); ?>