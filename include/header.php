<?php
  ob_start();
  if (session_status() === PHP_SESSION_NONE) {
    session_start();
  }
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- <link rel="stylesheet" href="../assets/css/dashboard.css"> -->
    <title>Agence de Voyage - Administration</title>
    <style>
        :root {
            --header-height: 64px;
            --sidebar-width: 280px;
            --primary-color: #2c3e50;
            --secondary-color: #3498db;
            --accent-color: #e74c3c;
            --light-color: #ecf0f1;
            --dark-color: #2c3e50;
            --header-bg: #fff;
            --header-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            padding-top: var(--header-height);
            padding-left: var(--sidebar-width);
            background-color: #f5f7fa;
            min-height: 100vh;
        }
        
        /* Header Styles */
        .main-header {
            position: fixed;
            top: 0;
            right: 0;
            left: var(--sidebar-width);
            height: var(--header-height);
            background: var(--header-bg);
            box-shadow: var(--header-shadow);
            z-index: 1000;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            padding: 0 1.5rem;
        }
        
        .header-content {
            display: flex;
            align-items: center;
            justify-content: space-between;
            width: 100%;
        }
        
        /* Search Bar */
        .search-bar {
            position: relative;
            max-width: 400px;
            width: 100%;
        }
        
        .search-bar input {
            width: 100%;
            padding: 0.5rem 1rem 0.5rem 2.5rem;
            border: 1px solid #e0e0e0;
            border-radius: 20px;
            font-size: 0.9rem;
            transition: all 0.3s ease;
        }
        
        .search-bar input:focus {
            border-color: var(--secondary-color);
            box-shadow: 0 0 0 0.2rem rgba(52, 152, 219, 0.25);
            outline: none;
        }
        
        .search-bar i {
            position: absolute;
            left: 1rem;
            top: 50%;
            transform: translateY(-50%);
            color: #95a5a6;
        }
        
        /* User Menu */
        .user-menu {
            display: flex;
            align-items: center;
            gap: 1.5rem;
        }
        
        .notification-bell {
            position: relative;
            color: var(--dark-color);
            font-size: 1.25rem;
            cursor: pointer;
        }
        
        .notification-badge {
            position: absolute;
            top: -5px;
            right: -5px;
            background: var(--accent-color);
            color: white;
            border-radius: 50%;
            width: 18px;
            height: 18px;
            font-size: 0.65rem;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
        }
        
        .user-profile {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            cursor: pointer;
            position: relative;
            background: none;
            border: none;
            padding: 0.25rem 0.5rem;
            border-radius: 20px;
            transition: all 0.2s ease;
        }
        
        .user-profile:hover {
            background-color: rgba(0, 0, 0, 0.05);
        }
        
        .user-avatar {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
            font-size: 0.9rem;
            flex-shrink: 0;
        }
        
        .user-info {
            display: flex;
            flex-direction: column;
            text-align: left;
        }
        
        .user-name {
            font-weight: 600;
            font-size: 0.9rem;
            color: var(--dark-color);
            margin: 0;
            line-height: 1.2;
            white-space: nowrap;
        }
        
        .user-role {
            font-size: 0.75rem;
            color: #7f8c8d;
            margin: 0;
            line-height: 1.2;
        }
        
        /* Dropdown Menu */
        .dropdown-menu {
            border: none;
            border-radius: 8px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
            padding: 0.5rem 0;
            margin-top: 10px;
            min-width: 220px;
        }
        
        .dropdown-item {
            padding: 0.5rem 1.25rem;
            color: #2c3e50;
            font-size: 0.9rem;
            display: flex;
            align-items: center;
            gap: 0.75rem;
            transition: all 0.2s ease;
        }
        
        .dropdown-item i {
            width: 20px;
            text-align: center;
        }
        
        .dropdown-item:hover {
            background-color: #f8f9fa;
            color: var(--secondary-color);
            padding-left: 1.5rem;
        }
        
        .dropdown-divider {
            margin: 0.5rem 0;
            border-color: #f1f1f1;
        }
        
        /* Responsive */
        @media (max-width: 1199.98px) {
            body {
                padding-left: 0;
            }
            
            .main-header {
                left: 0;
            }
            
            .sidebar-collapsed + .main-content .main-header {
                left: 70px;
            }
        }
        
        @media (max-width: 767.98px) {
            .search-bar {
                display: none;
            }
            
            .user-info {
                display: none;
            }
            
            .main-header {
                padding: 0 1rem;
            }
        }
    </style>
</head>
<body>
    <!-- Header -->
    <header class="main-header">
        <div class="header-content">
            <div class="d-flex align-items-center">
                <button class="btn btn-link text-dark d-lg-none me-2 sidebar-toggle">
                    <i class="fas fa-bars"></i>
                </button>
                <div class="search-bar">
                    <i class="fas fa-search"></i>
                    <input type="text" placeholder="Rechercher...">
                </div>
            </div>
            
            <div class="user-menu">
                <div class="notification-bell">
                    <i class="far fa-bell"></i>
                    <span class="notification-badge">3</span>
                </div>
                
                <div class="dropdown">
                    <button class="user-profile dropdown-toggle d-flex align-items-center" 
                            type="button" 
                            id="userDropdown" 
                            data-bs-toggle="dropdown" 
                            aria-expanded="false">
                        <div class="user-avatar">
                            <?php 
                            $initials = '';
                            if (isset($_SESSION['user']['prenom'])) $initials .= substr($_SESSION['user']['prenom'], 0, 1);
                            if (isset($_SESSION['user']['nom'])) $initials .= substr($_SESSION['user']['nom'], 0, 1);
                            echo $initials ?: 'U';
                            ?>
                        </div>
                        <div class="user-info d-none d-md-block">
                            <span class="user-name">
                                <?= htmlspecialchars($_SESSION['user']['prenom'] ?? 'Utilisateur') ?>
                                <?= htmlspecialchars($_SESSION['user']['nom'] ?? '') ?>
                            </span>
                            <span class="user-role">
                                <?= htmlspecialchars(ucfirst($_SESSION['user']['role'] ?? 'Utilisateur')) ?>
                            </span>
                        </div>
                        <i class="fas fa-chevron-down ms-1 text-muted small d-none d-md-block"></i>
                    </button>
                    
                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
                        <li>
                            <a class="dropdown-item" href="../profil.php">
                                <i class="far fa-user"></i>
                                <span>Mon profil</span>
                            </a>
                        </li>
                        <li><hr class="dropdown-divider m-0"></li>
                        <li>
                            <a class="dropdown-item text-danger" href="../deconnexion.php">
                                <i class="fas fa-sign-out-alt"></i>
                                <span>Déconnexion</span>
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </header>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
        <!-- Scripts -->
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
        <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Toggle sidebar
            const sidebarToggle = document.querySelector('.sidebar-toggle');
            if (sidebarToggle) {
                sidebarToggle.addEventListener('click', function() {
                    document.body.classList.toggle('sidebar-collapsed');
                    
                    // Save state in localStorage
                    if (document.body.classList.contains('sidebar-collapsed')) {
                        localStorage.setItem('sidebarCollapsed', 'true');
                    } else {
                        localStorage.removeItem('sidebarCollapsed');
                    }
                });
            }
            
            // Check for saved sidebar state
            if (localStorage.getItem('sidebarCollapsed') === 'true') {
                document.body.classList.add('sidebar-collapsed');
            }
            
            // Initialize dropdowns
            var dropdownElementList = [].slice.call(document.querySelectorAll('.dropdown-toggle'));
            var dropdownList = dropdownElementList.map(function (dropdownToggleEl) {
                return new bootstrap.Dropdown(dropdownToggleEl);
            });
        });
        </script>
    </body>
    </html>
