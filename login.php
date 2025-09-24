<?php
session_start();
require_once('connexion.php');

$message = '';
$email = '';

if (isset($_POST['login'])) {
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    
    if (empty($email) || empty($password)) {
        $message = '<div class="alert alert-warning">Veuillez remplir tous les champs</div>';
    } else {
        $sql = "SELECT * FROM `utilisateurs` WHERE email = :email";
        $user_con = $connect->prepare($sql);
        $user_con->bindValue(":email", $email);
        $user_con->execute();
        $users = $user_con->fetch();

        if (!empty($users)) {
            if (password_verify($password, $users["mot_de_passe"])) {
                $_SESSION['user'] = $users;

                // Redirection selon le rôle
                $redirect = 'home/bord.php';
                header("Location: $redirect");
                exit();
            } else {
                $message = '<div class="alert alert-danger">Email ou mot de passe incorrect</div>';
            }
        } else {
            $message = '<div class="alert alert-danger">Aucun compte trouvé avec cet email</div>';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion - Gestion de commande</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --primary-color: #4361ee;
            --secondary-color: #3f37c9;
            --light-color: #f8f9fa;
            --dark-color: #212529;
        }
        
        body {
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        .login-container {
            max-width: 400px;
            width: 90%;
            margin: 0 auto;
            animation: fadeIn 0.5s ease-in-out;
        }
        
        .login-card {
            border: none;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            background: white;
        }
        
        .login-header {
            background: var(--primary-color);
            color: white;
            padding: 2rem;
            text-align: center;
            position: relative;
            overflow: hidden;
        }
        
        .login-header::before {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, rgba(255,255,255,0) 60%);
            transform: rotate(30deg);
        }
        
        .login-logo {
            font-size: 2.5rem;
            margin-bottom: 0.5rem;
        }
        
        .login-body {
            padding: 2rem;
        }
        
        .form-control {
            padding: 0.75rem 1rem;
            border-radius: 8px;
            border: 1px solid #e0e0e0;
            transition: all 0.3s;
        }
        
        .form-control:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 0.2rem rgba(67, 97, 238, 0.25);
        }
        
        .form-floating>label {
            padding: 0.75rem 1rem;
        }
        
        .form-floating>.form-control:focus~label,
        .form-floating>.form-control:not(:placeholder-shown)~label {
            transform: scale(0.85) translateY(-0.5rem) translateX(0.15rem);
        }
        
        .btn-login {
            background: var(--primary-color);
            border: none;
            padding: 0.75rem;
            font-weight: 600;
            letter-spacing: 0.5px;
            transition: all 0.3s;
        }
        
        .btn-login:hover {
            background: var(--secondary-color);
            transform: translateY(-2px);
        }
        
        .forgot-password {
            color: #6c757d;
            text-decoration: none;
            font-size: 0.9rem;
            transition: color 0.3s;
        }
        
        .forgot-password:hover {
            color: var(--primary-color);
            text-decoration: none;
        }
        
        .input-group-text {
            background: transparent;
            border-right: none;
        }
        
        .input-group .form-control {
            border-left: none;
        }
        
        .input-group .form-control:focus {
            border-color: #ced4da;
        }
        
        .input-group:focus-within .input-group-text,
        .input-group:focus-within .form-control {
            border-color: var(--primary-color);
        }
        
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        .password-toggle {
            cursor: pointer;
            background: transparent;
            border: none;
            position: absolute;
            right: 10px;
            top: 50%;
            transform: translateY(-50%);
            z-index: 10;
            color: #6c757d;
        }
        
        .password-input-group {
            position: relative;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="login-container">
            <div class="login-card">
                <div class="login-header">
                    <div class="login-logo">
                        <i class="fas fa-plane-departure"></i>
                    </div>
                    <h2>GESTION DES COMMANDES</h2>
                    <p class="mb-0">Connectez-vous à votre espace</p>
                </div>
                <div class="login-body">
                    <?php if (!empty($message)) echo $message; ?>
                    
                    <form method="POST" action="" class="needs-validation" novalidate>
                        <div class="mb-4">
                            <div class="form-floating">
                                <input type="email" class="form-control" id="email" name="email" placeholder="nom@exemple.com" value="<?= htmlspecialchars($email) ?>" required>
                                <label for="email">Adresse email</label>
                                <div class="invalid-feedback">
                                    Veuillez entrer une adresse email valide.
                                </div>
                            </div>
                        </div>
                        
                        <div class="mb-4">
                            <div class="form-floating position-relative">
                                <input type="password" class="form-control" id="password" name="password" placeholder="Mot de passe" required>
                                <label for="password">Mot de passe</label>
                                <button type="button" class="password-toggle" onclick="togglePassword()">
                                    <i class="far fa-eye" id="toggleIcon"></i>
                                </button>
                                <div class="invalid-feedback">
                                    Veuillez entrer votre mot de passe.
                                </div>
                            </div>
                            <div class="d-flex justify-content-end mt-2">
                                <a href="#" class="forgot-password">Mot de passe oublié ?</a>
                            </div>
                        </div>
                        
                        <div class="d-grid gap-2">
                            <button type="submit" name="login" class="btn btn-primary btn-lg btn-login">
                                <i class="fas fa-sign-in-alt me-2"></i>Se connecter
                            </button>
                        </div>
                    </form>
                </div>
            </div>
            
            <div class="text-center mt-4">
                <p class="text-muted"> <?= date('Y') ?> Gestion de Commande. Tous droits réservés.</p>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        // Fonction pour basculer la visibilité du mot de passe
        function togglePassword() {
            const passwordInput = document.getElementById('password');
            const toggleIcon = document.getElementById('toggleIcon');
            
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                toggleIcon.classList.remove('fa-eye');
                toggleIcon.classList.add('fa-eye-slash');
            } else {
                passwordInput.type = 'password';
                toggleIcon.classList.remove('fa-eye-slash');
                toggleIcon.classList.add('fa-eye');
            }
        }
        
        // Validation des formulaires Bootstrap
        (function () {
            'use strict'
            
            // Récupérer tous les formulaires auxquels nous voulons ajouter la validation des styles personnalisés
            var forms = document.querySelectorAll('.needs-validation')
            
            // Boucle sur les champs et empêche la soumission
            Array.prototype.slice.call(forms)
                .forEach(function (form) {
                    form.addEventListener('submit', function (event) {
                        if (!form.checkValidity()) {
                            event.preventDefault()
                            event.stopPropagation()
                        }
                        
                        form.classList.add('was-validated')
                    }, false)
                })
        })()
    </script>
</body>
</html>
