<?php
session_start();

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['user'])) {
    header('Location: login.php');
    exit();
}

// Connexion à la base de données
$db = new PDO('mysql:host=localhost;dbname=agence_voyage;charset=utf8', 'root', '');
$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$success = '';
$error = '';
$user = $_SESSION['user'];

// Récupérer les informations complètes de l'utilisateur
$stmt = $db->prepare("SELECT * FROM utilisateurs WHERE id = ?");
$stmt->execute([$user['id']]);
$user_data = $stmt->fetch(PDO::FETCH_ASSOC);

// Traitement du formulaire de mise à jour du profil
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_profile'])) {
    try {
        $nom = trim($_POST['nom']);
        $email = trim($_POST['email']);
        $telephone = trim($_POST['telephone']);
        
        // Validation des données
        if (empty($nom) || empty($email)) {
            throw new Exception("Tous les champs obligatoires doivent être remplis.");
        }
        
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new Exception("L'adresse email n'est pas valide.");
        }
        
        // Mise à jour dans la base de données
        $stmt = $db->prepare("UPDATE utilisateurs SET nom = ?, email = ?, telephone = ? WHERE id = ?");
        $stmt->execute([$nom, $email, $telephone, $user['id']]);
        
        // Mise à jour de la session
        $_SESSION['user'] = array_merge($user, [
            'nom' => $nom,
            'email' => $email,
            'telephone' => $telephone
        ]);
        
        $success = "Profil mis à jour avec succès !";
        $user = $_SESSION['user']; // Mettre à jour les données affichées
        
        // Recharger les données de l'utilisateur après mise à jour
        $stmt = $db->prepare("SELECT * FROM utilisateurs WHERE id = ?");
        $stmt->execute([$user['id']]);
        $user_data = $stmt->fetch(PDO::FETCH_ASSOC);
        
    } catch (Exception $e) {
        $error = $e->getMessage();
    }
}

// Traitement du formulaire de changement de mot de passe
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['change_password'])) {
    try {
        $current_password = $_POST['current_password'];
        $new_password = $_POST['new_password'];
        $confirm_password = $_POST['confirm_password'];
        
        if (empty($current_password) || empty($new_password) || empty($confirm_password)) {
            throw new Exception("Tous les champs du mot de passe sont obligatoires.");
        }
        
        if ($new_password !== $confirm_password) {
            throw new Exception("Les nouveaux mots de passe ne correspondent pas.");
        }
        
        if (strlen($new_password) < 8) {
            throw new Exception("Le mot de passe doit contenir au moins 8 caractères.");
        }
        
        // Vérifier l'ancien mot de passe (à adapter selon votre système d'authentification)
        $stmt = $db->prepare("SELECT mot_de_passe FROM utilisateurs WHERE id = ?");
        $stmt->execute([$user['id']]);
        $db_password = $stmt->fetchColumn();
        
        // Remplacer cette vérification par votre propre logique de vérification de mot de passe
        if (!password_verify($current_password, $db_password)) {
            throw new Exception("Le mot de passe actuel est incorrect.");
        }
        
        // Mettre à jour le mot de passe
        $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
        $stmt = $db->prepare("UPDATE utilisateurs SET mot_de_passe = ? WHERE id = ?");
        $stmt->execute([$hashed_password, $user['id']]);
        
        $success = "Mot de passe mis à jour avec succès !";
        
    } catch (Exception $e) {
        $error = $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mon Profil - Agence de Voyage</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --primary-color: #2c3e50;
            --secondary-color: #3498db;
            --accent-color: #e74c3c;
            --light-color: #f8f9fa;
            --dark-color: #2c3e50;
        }
        
        body {
            background-color: #f5f7fa;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        .profile-card {
            border: none;
            border-radius: 10px;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }
        
        .profile-header {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            color: white;
            padding: 2rem;
            text-align: center;
        }
        
        .profile-avatar {
            width: 120px;
            height: 120px;
            border-radius: 50%;
            background: white;
            margin: 0 auto 1rem;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 3rem;
            color: var(--primary-color);
            font-weight: bold;
            border: 4px solid white;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }
        
        .profile-name {
            font-size: 1.5rem;
            font-weight: 600;
            margin: 0.5rem 0 0.25rem;
        }
        
        .profile-role {
            font-size: 1rem;
            opacity: 0.9;
            margin-bottom: 0;
        }
        
        .profile-body {
            padding: 2rem;
            background: white;
        }
        
        .nav-pills .nav-link {
            color: var(--dark-color);
            border-radius: 5px;
            margin-bottom: 0.5rem;
            font-weight: 500;
        }
        
        .nav-pills .nav-link.active {
            background-color: var(--secondary-color);
            color: white;
        }
        
        .form-control:focus {
            border-color: var(--secondary-color);
            box-shadow: 0 0 0 0.2rem rgba(52, 152, 219, 0.25);
        }
        
        .btn-primary {
            background-color: var(--secondary-color);
            border-color: var(--secondary-color);
        }
        
        .btn-primary:hover {
            background-color: #2980b9;
            border-color: #2980b9;
        }
        
        .btn-outline-primary {
            color: var(--secondary-color);
            border-color: var(--secondary-color);
        }
        
        .btn-outline-primary:hover {
            background-color: var(--secondary-color);
            border-color: var(--secondary-color);
        }
        
        .tab-content {
            padding: 1.5rem 0;
        }
        
        .password-strength {
            height: 5px;
            margin-top: 0.5rem;
            border-radius: 5px;
            background-color: #e9ecef;
            overflow: hidden;
        }
        
        .password-strength-bar {
            height: 100%;
            width: 0%;
            background-color: #dc3545;
            transition: width 0.3s ease, background-color 0.3s ease;
        }
    </style>
</head>
<body>
    <?php include 'include/header.php'; ?>
    <?php include 'include/sidebar.php'; ?>
    
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <?php if ($success): ?>
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <?= htmlspecialchars($success) ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                <?php endif; ?>
                
                <?php if ($error): ?>
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <?= htmlspecialchars($error) ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                <?php endif; ?>
                
                <div class="card profile-card mb-4">
                    <div class="profile-header">
                        <div class="profile-avatar">
                            <?= strtoupper(substr($user_data['nom'] ?? 'U', 0, 1)) ?>
                        </div>
                        <h1 class="profile-name"><?= htmlspecialchars($user_data['nom'] ?? 'Utilisateur') ?></h1>
                        <p class="profile-role"><?= htmlspecialchars(ucfirst($user_data['role'] ?? 'Utilisateur')) ?></p>
                    </div>
                    
                    <div class="profile-body">
                        <ul class="nav nav-pills mb-4" id="profileTabs" role="tablist">
                            <li class="nav-item" role="presentation">
                                <button class="nav-link active" id="profile-tab" data-bs-toggle="pill" data-bs-target="#profile" type="button" role="tab" aria-controls="profile" aria-selected="true">
                                    <i class="fas fa-user me-2"></i>Profil
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="password-tab" data-bs-toggle="pill" data-bs-target="#password" type="button" role="tab" aria-controls="password" aria-selected="false">
                                    <i class="fas fa-lock me-2"></i>Mot de passe
                                </button>
                            </li>
                        </ul>
                        
                        <div class="tab-content" id="profileTabsContent">
                            <!-- Onglet Profil -->
                            <div class="tab-pane fade show active" id="profile" role="tabpanel" aria-labelledby="profile-tab">
                                <form method="POST" action="">
                                    <input type="hidden" name="update_profile" value="1">
                                    
                                    <div class="row">
                                        <div class="col-12 mb-3">
                                            <label for="nom" class="form-label">Nom complet</label>
                                            <input type="text" class="form-control" id="nom" name="nom" value="<?= htmlspecialchars($user_data['nom'] ?? '') ?>" required>
                                        </div>
                                    </div>
                                    
                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label for="email" class="form-label">Adresse email</label>
                                            <input type="email" class="form-control" id="email" name="email" value="<?= htmlspecialchars($user_data['email'] ?? '') ?>" required>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label for="telephone" class="form-label">Téléphone</label>
                                            <input type="tel" class="form-control" id="telephone" name="telephone" value="<?= htmlspecialchars($user_data['telephone'] ?? '') ?>">
                                        </div>
                                    </div>
                                    
                                    <div class="d-flex justify-content-end mt-4">
                                        <button type="submit" class="btn btn-primary px-4">
                                            <i class="fas fa-save me-2"></i>Enregistrer les modifications
                                        </button>
                                    </div>
                                </form>
                            </div>
                            
                            <!-- Onglet Mot de passe -->
                            <div class="tab-pane fade" id="password" role="tabpanel" aria-labelledby="password-tab">
                                <form method="POST" action="">
                                    <input type="hidden" name="change_password" value="1">
                                    
                                    <div class="mb-3">
                                        <label for="current_password" class="form-label">Mot de passe actuel</label>
                                        <div class="input-group">
                                            <input type="password" class="form-control" id="current_password" name="current_password" required>
                                            <button class="btn btn-outline-secondary toggle-password" type="button">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                        </div>
                                    </div>
                                    
                                    <div class="mb-3">
                                        <label for="new_password" class="form-label">Nouveau mot de passe</label>
                                        <div class="input-group">
                                            <input type="password" class="form-control" id="new_password" name="new_password" required>
                                            <button class="btn btn-outline-secondary toggle-password" type="button">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                        </div>
                                        <div class="password-strength mt-2">
                                            <div class="password-strength-bar" id="password-strength-bar"></div>
                                        </div>
                                        <small class="text-muted">Le mot de passe doit contenir au moins 8 caractères.</small>
                                    </div>
                                    
                                    <div class="mb-4">
                                        <label for="confirm_password" class="form-label">Confirmer le nouveau mot de passe</label>
                                        <div class="input-group">
                                            <input type="password" class="form-control" id="confirm_password" name="confirm_password" required>
                                            <button class="btn btn-outline-secondary toggle-password" type="button">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                        </div>
                                        <div id="password-match" class="mt-1"></div>
                                    </div>
                                    
                                    <div class="d-flex justify-content-end">
                                        <button type="submit" class="btn btn-primary px-4" id="submit-password">
                                            <i class="fas fa-key me-2"></i>Changer le mot de passe
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        // Afficher/masquer les mots de passe
        document.querySelectorAll('.toggle-password').forEach(button => {
            button.addEventListener('click', function() {
                const input = this.parentElement.querySelector('input');
                const icon = this.querySelector('i');
                
                if (input.type === 'password') {
                    input.type = 'text';
                    icon.classList.remove('fa-eye');
                    icon.classList.add('fa-eye-slash');
                } else {
                    input.type = 'password';
                    icon.classList.remove('fa-eye-slash');
                    icon.classList.add('fa-eye');
                }
            });
        });
        
        // Vérification de la force du mot de passe
        const newPassword = document.getElementById('new_password');
        const confirmPassword = document.getElementById('confirm_password');
        const strengthBar = document.getElementById('password-strength-bar');
        const passwordMatch = document.getElementById('password-match');
        const submitButton = document.getElementById('submit-password');
        
        if (newPassword) {
            newPassword.addEventListener('input', function() {
                const password = this.value;
                let strength = 0;
                
                // Au moins 8 caractères
                if (password.length >= 8) strength += 25;
                
                // Contient une lettre minuscule
                if (/[a-z]/.test(password)) strength += 25;
                
                // Contient une lettre majuscule
                if (/[A-Z]/.test(password)) strength += 25;
                
                // Contient un chiffre ou un caractère spécial
                if (/[0-9]/.test(password) || /[^A-Za-z0-9]/.test(password)) strength += 25;
                
                // Mise à jour de la barre de force
                strengthBar.style.width = strength + '%';
                
                // Changement de couleur en fonction de la force
                if (strength < 50) {
                    strengthBar.style.backgroundColor = '#dc3545'; // Rouge
                } else if (strength < 75) {
                    strengthBar.style.backgroundColor = '#ffc107'; // Jaune
                } else {
                    strengthBar.style.backgroundColor = '#28a745'; // Vert
                }
                
                // Vérification de la correspondance des mots de passe
                checkPasswordMatch();
            });
        }
        
        if (confirmPassword) {
            confirmPassword.addEventListener('input', checkPasswordMatch);
        }
        
        function checkPasswordMatch() {
            if (!newPassword || !confirmPassword) return;
            
            if (newPassword.value !== confirmPassword.value) {
                passwordMatch.innerHTML = '<span class="text-danger"><i class="fas fa-times-circle me-1"></i>Les mots de passe ne correspondent pas</span>';
                submitButton.disabled = true;
            } else if (newPassword.value.length > 0) {
                passwordMatch.innerHTML = '<span class="text-success"><i class="fas fa-check-circle me-1"></i>Les mots de passe correspondent</span>';
                submitButton.disabled = false;
            } else {
                passwordMatch.innerHTML = '';
                submitButton.disabled = true;
            }
        }
    });
    </script>
</body>
</html>
