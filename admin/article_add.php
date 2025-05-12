<?php
session_start();
include '../includes/db.php';
include '../includes/config.php';
// Vérifier si l'utilisateur est connecté et a le rôle d'administrateur
if (!isset($_SESSION['user_id']) || !isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header('Location: ../index.php');
    exit;
}

$message = '';

// Traitement du formulaire d'ajout d'article
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $titre = trim($_POST['titre']);
    $contenu = trim($_POST['contenu']);
    $id_auteur = $_SESSION['user_id'];

    // Validation de base
    if (empty($titre) || empty($contenu)) {
        $message = 'Le titre et le contenu sont obligatoires.';
    } else {
        // Insérer l'article dans la base de données
        $stmt = $conn->prepare("INSERT INTO articles (titre, contenu, id_auteur) VALUES (?, ?, ?)");
        $stmt->bind_param("ssi", $titre, $contenu, $id_auteur);

        if ($stmt->execute()) {
            $article_id = $conn->insert_id;
            $message = 'L\'article a été ajouté avec succès.';

            // Rediriger vers la liste des articles après 2 secondes
            header('Refresh: 2; URL=articles.php');
        } else {
            $message = 'Une erreur est survenue lors de l\'ajout de l\'article.';
        }

        $stmt->close();
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ajouter un article - NutriVie Admin</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
    <div class="admin-container">
        <?php include 'sidebar.php'; ?>

        <main class="admin-main">
            <header class="admin-header">
                <h1>Ajouter un article</h1>
                <div class="admin-user">
                    <span>Connecté en tant que <?php echo htmlspecialchars($_SESSION['user_prenom'] . ' ' . $_SESSION['user_nom']); ?></span>
                    <a href="../pages/logout.php" class="logout-btn">Déconnexion</a>
                </div>
            </header>

            <div class="admin-content">
                <?php if (!empty($message)): ?>
                    <div class="message"><?php echo $message; ?></div>
                <?php endif; ?>

                <form action="article_add.php" method="post" class="admin-form">
                    <div class="form-group">
                        <label for="titre">Titre de l'article</label>
                        <input type="text" id="titre" name="titre" required>
                    </div>

                    <div class="form-group">
                        <label for="contenu">Contenu de l'article</label>
                        <textarea id="contenu" name="contenu" rows="15" required></textarea>
                    </div>

                    <div class="form-actions">
                        <button type="submit" class="primary-btn">Publier l'article</button>
                        <a href="articles.php" class="secondary-btn">Annuler</a>
                    </div>
                </form>
            </div>
        </main>
    </div>
</body>
</html>
