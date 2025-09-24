<?php
// Assurez-vous que la session est démarrée
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>

<!-- Sidebar -->
<div class="sidebar-wrapper">
    <div class="sidebar">
        <!-- Logo et marque -->
        <div class="sidebar-header text-center py-4">
            <a href="../index.php" class="d-flex align-items-center justify-content-center text-decoration-none">
                <div class="sidebar-logo">
                    <i class="fas fa-shopping-cart"></i>
                </div>
                <span class="ms-2 fs-5 fw-bold text-white">
                     GENIE-TECH
                </span>
            </a>
        </div>
        
        <!-- Menu de navigation -->
        <div class="sidebar-menu">
            <!-- Tableau de bord -->
            <div class="menu-section">
                <div class="menu-title">MENU PRINCIPAL</div>
                <ul class="nav flex-column">
                    <li class="nav-item">
                        <a href="../home/bord.php" class="nav-link">
                            <span class="icon"><i class="fas fa-tachometer-alt"></i></span>
                            <span class="title">Tableau de bord</span>
                        </a>
                    </li>
                </ul>
            </div>
            
            <!-- Gestion -->
            <div class="menu-section">
                <div class="menu-title">GESTION</div>
                <ul class="nav flex-column">
                    <li class="nav-item">
                        <a href="../produit/index.php" class="nav-link">
                            
                        <span class="icon"><i class="fas fa-box-open"></i></span>
                            <span class="title">Produit</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="../commande/index.php" class="nav-link">
                            <span class="icon"><i class="fas fa-calendar-check"></i></span>
                            <span class="title">commande</span>
                            <span class="badge bg-primary rounded-pill ms-auto"></span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="../client/index.php" class="nav-link">
                            <span class="icon"><i class="fas fa-users"></i></span>
                            <span class="title">Clients</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="../paiement/index.php" class="nav-link">
                            <span class="icon"><i class="fas fa-map-marked-alt"></i></span>
                            <span class="title">Paiement</span>
                        </a>
                    </li>
                </ul>
            </div>
            
        </div>
        
        <!-- Pied de page -->
        <div class="sidebar-footer">
            <div class="user-info">
                <div class="user-avatar">
                    <?php 
                    $initials = '';
                    if (isset($_SESSION['user']['prenom'])) $initials .= substr($_SESSION['user']['prenom'], 0, 1);
                    if (isset($_SESSION['user']['nom'])) $initials .= substr($_SESSION['user']['nom'], 0, 1);
                    echo $initials ?: 'US';
                    ?>
                </div>
                <div class="user-details">
                    <div class="user-name">
                        <?= htmlspecialchars($_SESSION['user']['prenom'] ?? 'Utilisateur') ?>
                        <?= htmlspecialchars($_SESSION['user']['nom'] ?? '') ?>
                    </div>
                    <div class="user-role">
                        <?= htmlspecialchars(ucfirst($_SESSION['user']['role'] ?? 'Utilisateur')) ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Styles de la sidebar -->
<style>
:root {
    --sidebar-width: 280px;
    --sidebar-bg: #2c3e50;
    --sidebar-color: #ecf0f1;
    --sidebar-active-bg: #3498db;
    --sidebar-hover-bg: rgba(255, 255, 255, 0.1);
    --sidebar-header-bg: #1a252f;
    --sidebar-divider: rgba(255, 255, 255, 0.1);
    --sidebar-icon-color: #b8c7ce;
    --sidebar-active-icon-color: #fff;
}

/* Layout de base */
.sidebar-wrapper {
    width: var(--sidebar-width);
    min-height: 100vh;
    background: var(--sidebar-bg);
    color: var(--sidebar-color);
    transition: all 0.3s;
    position: fixed;
    z-index: 1000;
    top: 0;
    left: 0;
    box-shadow: 2px 0 10px rgba(0, 0, 0, 0.1);
}

.sidebar {
    display: flex;
    flex-direction: column;
    height: 100vh;
}

/* En-tête */
.sidebar-header {
    padding: 20px 15px;
    background: var(--sidebar-header-bg);
    border-bottom: 1px solid var(--sidebar-divider);
}

.sidebar-logo {
    width: 36px;
    height: 36px;
    background: var(--sidebar-active-bg);
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 1.2rem;
}

/* Menu */
.sidebar-menu {
    flex: 1;
    overflow-y: auto;
    padding: 20px 0;
}

.menu-section {
    margin-bottom: 25px;
}

.menu-title {
    padding: 0 20px 10px;
    font-size: 0.75rem;
    text-transform: uppercase;
    color: #b8c7ce;
    font-weight: 600;
    letter-spacing: 0.5px;
}

.nav {
    flex-direction: column;
    padding: 0 10px;
}

.nav-link {
    color: var(--sidebar-color);
    padding: 12px 15px !important;
    margin: 2px 0;
    border-radius: 6px;
    display: flex;
    align-items: center;
    transition: all 0.3s ease;
    text-decoration: none;
    font-size: 0.95rem;
}

.nav-link:hover, .nav-link.active {
    background: var(--sidebar-hover-bg);
    color: #fff;
}

.nav-link .icon {
    width: 24px;
    margin-right: 12px;
    text-align: center;
    color: var(--sidebar-icon-color);
    font-size: 1.1rem;
}

.nav-link:hover .icon,
.nav-link.active .icon {
    color: var(--sidebar-active-icon-color);
}

.nav-link .title {
    flex: 1;
}

.badge {
    font-size: 0.7rem;
    padding: 0.25em 0.6em;
    font-weight: 500;
}

/* Pied de page */
.sidebar-footer {
    padding: 15px;
    border-top: 1px solid var(--sidebar-divider);
    background: rgba(0, 0, 0, 0.1);
}

.user-info {
    display: flex;
    align-items: center;
    padding: 10px 0;
}

.user-avatar {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    background: var(--sidebar-active-bg);
    color: white;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 600;
    font-size: 0.9rem;
    margin-right: 10px;
}

.user-details {
    flex: 1;
    overflow: hidden;
}

.user-name {
    font-weight: 500;
    margin: 0;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}

.user-role {
    font-size: 0.75rem;
    color: #b8c7ce;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}

/* Scrollbar personnalisée */
.sidebar-menu::-webkit-scrollbar {
    width: 5px;
}

.sidebar-menu::-webkit-scrollbar-track {
    background: transparent;
}

.sidebar-menu::-webkit-scrollbar-thumb {
    background: rgba(255, 255, 255, 0.2);
    border-radius: 3px;
}

/* Animation de survol */
.nav-link {
    position: relative;
    overflow: hidden;
}

.nav-link::after {
    content: '';
    position: absolute;
    left: 0;
    top: 0;
    height: 100%;
    width: 3px;
    background: var(--sidebar-active-bg);
    transform: scaleY(0);
    transition: transform 0.2s ease;
}

.nav-link:hover::after,
.nav-link.active::after {
    transform: scaleY(1);
}

/* Version réduite */
.sidebar-collapsed .sidebar-wrapper {
    width: 70px;
}

.sidebar-collapsed .sidebar-header span,
.sidebar-collapsed .menu-title,
.sidebar-collapsed .title,
.sidebar-collapsed .badge,
.sidebar-collapsed .user-details {
    display: none;
}

.sidebar-collapsed .sidebar-header {
    padding: 20px 0;
    display: flex;
    justify-content: center;
}

.sidebar-collapsed .nav-link {
    justify-content: center;
    padding: 15px 0 !important;
}

.sidebar-collapsed .nav-link .icon {
    margin-right: 0;
    font-size: 1.3rem;
}

.sidebar-collapsed .sidebar-footer {
    padding: 15px 5px;
    text-align: center;
}

.sidebar-collapsed .user-avatar {
    margin: 0;
    width: 40px;
    height: 40px;
}

/* Responsive */
@media (max-width: 992px) {
    .sidebar-wrapper {
        transform: translateX(-100%);
        z-index: 1050;
    }
    
    .sidebar-show .sidebar-wrapper {
        transform: translateX(0);
    }
    
    .sidebar-overlay {
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(0, 0, 0, 0.5);
        z-index: 1040;
    }
    
    .sidebar-show .sidebar-overlay {
        display: block;
    }
}
</style>

<!-- Script pour le toggle de la sidebar -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Ajouter la classe active aux liens actifs
    const currentPath = window.location.pathname.split('/').pop() || 'index.php';
    document.querySelectorAll('.nav-link').forEach(link => {
        const linkPath = link.getAttribute('href').split('/').pop();
        if (linkPath === currentPath || 
            (currentPath === '' && linkPath === 'index.php') ||
            (linkPath !== '#' && currentPath.includes(linkPath))) {
            link.classList.add('active');
        }
    });
    
    // Toggle sidebar sur mobile
    const sidebarToggle = document.querySelector('.sidebar-toggle');
    const sidebarWrapper = document.querySelector('.sidebar-wrapper');
    const sidebarOverlay = document.createElement('div');
    sidebarOverlay.className = 'sidebar-overlay';
    document.body.appendChild(sidebarOverlay);
    
    function toggleSidebar() {
        document.body.classList.toggle('sidebar-show');
    }
    
    if (sidebarToggle) {
        sidebarToggle.addEventListener('click', toggleSidebar);
    }
    
    sidebarOverlay.addEventListener('click', toggleSidebar);
    
    // Ajouter le bouton de toggle dans le header
    const header = document.querySelector('.navbar');
    if (header && !document.querySelector('.sidebar-toggle-btn')) {
        const toggleBtn = document.createElement('button');
        toggleBtn.className = 'btn btn-link text-white sidebar-toggle-btn me-2 d-lg-none';
        toggleBtn.innerHTML = '<i class="fas fa-bars"></i>';
        toggleBtn.addEventListener('click', toggleSidebar);
        header.insertBefore(toggleBtn, header.firstChild);
    }
});
</script>