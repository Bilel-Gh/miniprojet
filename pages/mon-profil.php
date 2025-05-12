<?php
session_start();
include '../includes/db.php';

// Rediriger si l'utilisateur n'est pas connecté
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$user_id = $_SESSION['user_id'];
$message = '';

// Traitement des modifications du profil
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'update_profile') {
    $nom = trim($_POST['nom']);
    $prenom = trim($_POST['prenom']);
    $email = trim($_POST['email']);
    $current_password = $_POST['current_password'];
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];

    // Vérification de base
    if (empty($nom) || empty($prenom) || empty($email)) {
        $message = 'Les champs nom, prénom et email sont obligatoires.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $message = 'Veuillez entrer une adresse email valide.';
    } else {
        // Vérifier si l'email existe déjà pour un autre utilisateur
        $stmt = $conn->prepare("SELECT id FROM utilisateurs WHERE email = ? AND id != ?");
        $stmt->bind_param("si", $email, $user_id);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $message = 'Cette adresse email est déjà utilisée par un autre compte.';
        } else {
            // Mise à jour des informations de base
            $stmt = $conn->prepare("UPDATE utilisateurs SET nom = ?, prenom = ?, email = ? WHERE id = ?");
            $stmt->bind_param("sssi", $nom, $prenom, $email, $user_id);

            if ($stmt->execute()) {
                // Mise à jour des informations de session
                $_SESSION['user_nom'] = $nom;
                $_SESSION['user_prenom'] = $prenom;
                $_SESSION['user_email'] = $email;

                $message = 'Votre profil a été mis à jour avec succès.';
            } else {
                $message = 'Une erreur est survenue lors de la mise à jour du profil.';
            }

            // Si l'utilisateur souhaite changer son mot de passe
            if (!empty($current_password) && !empty($new_password) && !empty($confirm_password)) {
                if ($new_password !== $confirm_password) {
                    $message .= ' Cependant, les nouveaux mots de passe ne correspondent pas.';
                } else {
                    // Vérification du mot de passe actuel
                    $stmt = $conn->prepare("SELECT mot_de_passe FROM utilisateurs WHERE id = ?");
                    $stmt->bind_param("i", $user_id);
                    $stmt->execute();
                    $result = $stmt->get_result();
                    $user = $result->fetch_assoc();

                    if (password_verify($current_password, $user['mot_de_passe'])) {
                        // Mettre à jour le mot de passe
                        $password_hash = password_hash($new_password, PASSWORD_DEFAULT);
                        $stmt = $conn->prepare("UPDATE utilisateurs SET mot_de_passe = ? WHERE id = ?");
                        $stmt->bind_param("si", $password_hash, $user_id);

                        if ($stmt->execute()) {
                            $message .= ' Votre mot de passe a été modifié avec succès.';
                        } else {
                            $message .= ' Une erreur est survenue lors de la mise à jour du mot de passe.';
                        }
                    } else {
                        $message .= ' Le mot de passe actuel est incorrect.';
                    }
                }
            }
        }
    }
}

// Traitement de l'ajout d'un suivi de santé
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'add_health_tracking') {
    $date = $_POST['date'];
    $poids = !empty($_POST['poids']) ? $_POST['poids'] : null;
    $taille = !empty($_POST['taille']) ? $_POST['taille'] : null;
    $activite = !empty($_POST['activite_physique']) ? $_POST['activite_physique'] : null;
    $calories = !empty($_POST['calories']) ? $_POST['calories'] : null;
    $notes = $_POST['notes'];

    if (empty($date)) {
        $message = 'La date est obligatoire.';
    } else {
        // Insérer le suivi dans la base de données
        $stmt = $conn->prepare("INSERT INTO suivi_sante (id_utilisateur, date_enregistrement, poids, taille, activite_physique, calories_consommees, notes) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("isddiss", $user_id, $date, $poids, $taille, $activite, $calories, $notes);

        if ($stmt->execute()) {
            $message = 'Votre suivi de santé a été enregistré avec succès.';
        } else {
            $message = 'Une erreur est survenue lors de l\'enregistrement du suivi.';
        }
    }
}

// Récupérer les informations de l'utilisateur
$stmt = $conn->prepare("SELECT nom, prenom, email FROM utilisateurs WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

// Récupérer les 10 derniers suivis de santé
$stmt = $conn->prepare("SELECT * FROM suivi_sante WHERE id_utilisateur = ? ORDER BY date_enregistrement DESC LIMIT 10");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$health_trackings = [];
while ($row = $result->fetch_assoc()) {
    $health_trackings[] = $row;
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mon Profil - NutriVie</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
    <?php include '../includes/header.php'; ?>

    <main>
        <section class="profile-section">
            <h1>Mon Profil</h1>

            <?php if (!empty($message)): ?>
                <div class="message"><?php echo $message; ?></div>
            <?php endif; ?>

            <div class="tabs">
                <button class="tab-btn active" data-tab="profile">Informations personnelles</button>
                <button class="tab-btn" data-tab="health-tracking">Suivi de santé</button>
            </div>

            <div id="profile" class="tab-content active">
                <h2>Informations personnelles</h2>
                <form action="mon-profil.php" method="post">
                    <input type="hidden" name="action" value="update_profile">

                    <div class="form-group">
                        <label for="nom">Nom</label>
                        <input type="text" id="nom" name="nom" value="<?php echo htmlspecialchars($user['nom']); ?>" required>
                    </div>

                    <div class="form-group">
                        <label for="prenom">Prénom</label>
                        <input type="text" id="prenom" name="prenom" value="<?php echo htmlspecialchars($user['prenom']); ?>" required>
                    </div>

                    <div class="form-group">
                        <label for="email">Email</label>
                        <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" required>
                    </div>

                    <h3>Changer de mot de passe</h3>
                    <p>(Laissez vide si vous ne souhaitez pas modifier votre mot de passe)</p>

                    <div class="form-group">
                        <label for="current_password">Mot de passe actuel</label>
                        <input type="password" id="current_password" name="current_password">
                    </div>

                    <div class="form-group">
                        <label for="new_password">Nouveau mot de passe</label>
                        <input type="password" id="new_password" name="new_password">
                    </div>

                    <div class="form-group">
                        <label for="confirm_password">Confirmer le nouveau mot de passe</label>
                        <input type="password" id="confirm_password" name="confirm_password">
                    </div>

                    <button type="submit">Mettre à jour le profil</button>
                </form>
            </div>

            <div id="health-tracking" class="tab-content">
                <h2>Suivi de santé</h2>

                <h3>Ajouter un suivi</h3>
                <form action="mon-profil.php" method="post">
                    <input type="hidden" name="action" value="add_health_tracking">

                    <div class="form-group">
                        <label for="date">Date</label>
                        <input type="date" id="date" name="date" value="<?php echo date('Y-m-d'); ?>" required>
                    </div>

                    <div class="form-group">
                        <label for="poids">Poids (kg)</label>
                        <input type="number" id="poids" name="poids" step="0.1" min="0" max="500">
                    </div>

                    <div class="form-group">
                        <label for="taille">Taille (cm)</label>
                        <input type="number" id="taille" name="taille" step="0.1" min="0" max="300">
                    </div>

                    <div class="form-group">
                        <label for="activite_physique">Activité physique (minutes)</label>
                        <input type="number" id="activite_physique" name="activite_physique" min="0">
                    </div>

                    <div class="form-group">
                        <label for="calories">Calories consommées</label>
                        <input type="number" id="calories" name="calories" min="0">
                    </div>

                    <div class="form-group">
                        <label for="notes">Notes</label>
                        <textarea id="notes" name="notes" rows="4"></textarea>
                    </div>

                    <button type="submit">Enregistrer le suivi</button>
                </form>

                <h3>Historique des suivis</h3>
                <?php if (count($health_trackings) > 0): ?>
                    <table class="health-tracking-table">
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Poids (kg)</th>
                                <th>Taille (cm)</th>
                                <th>Activité (min)</th>
                                <th>Calories</th>
                                <th>Notes</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($health_trackings as $tracking): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($tracking['date_enregistrement']); ?></td>
                                    <td><?php echo $tracking['poids'] ? htmlspecialchars($tracking['poids']) : '-'; ?></td>
                                    <td><?php echo $tracking['taille'] ? htmlspecialchars($tracking['taille']) : '-'; ?></td>
                                    <td><?php echo $tracking['activite_physique'] ? htmlspecialchars($tracking['activite_physique']) : '-'; ?></td>
                                    <td><?php echo $tracking['calories_consommees'] ? htmlspecialchars($tracking['calories_consommees']) : '-'; ?></td>
                                    <td><?php echo htmlspecialchars($tracking['notes']); ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php else: ?>
                    <p>Aucun suivi de santé enregistré pour le moment.</p>
                <?php endif; ?>
            </div>
        </section>
    </main>

    <?php include '../includes/footer.php'; ?>

    <script>
        // Script pour gérer les onglets
        document.addEventListener('DOMContentLoaded', function() {
            const tabBtns = document.querySelectorAll('.tab-btn');
            const tabContents = document.querySelectorAll('.tab-content');

            tabBtns.forEach(function(btn) {
                btn.addEventListener('click', function() {
                    const tab = this.dataset.tab;

                    // Retirer la classe 'active' de tous les boutons et contenus
                    tabBtns.forEach(btn => btn.classList.remove('active'));
                    tabContents.forEach(content => content.classList.remove('active'));

                    // Ajouter la classe 'active' au bouton et au contenu correspondant
                    this.classList.add('active');
                    document.getElementById(tab).classList.add('active');
                });
            });
        });
    </script>
</body>
</html>
