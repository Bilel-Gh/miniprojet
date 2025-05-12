<?php
session_start();
include '../includes/db.php';

// Vérifier si l'ID de l'article est présent dans l'URL
if (!isset($_GET['id']) || empty($_GET['id']) || !is_numeric($_GET['id'])) {
    header('Location: articles.php');
    exit;
}

$article_id = (int)$_GET['id'];

// Récupérer les détails de l'article
$sql = "SELECT a.*, u.nom, u.prenom
        FROM articles a
        LEFT JOIN utilisateurs u ON a.id_auteur = u.id
        WHERE a.id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $article_id);
$stmt->execute();
$result = $stmt->get_result();

// Vérifier si l'article existe
if ($result->num_rows === 0) {
    header('Location: articles.php');
    exit;
}

// Récupérer les données de l'article
$article = $result->fetch_assoc();

// Récupérer d'autres articles similaires ou récents
$sql_related = "SELECT id, titre FROM articles WHERE id != ? ORDER BY date_creation DESC LIMIT 5";
$stmt_related = $conn->prepare($sql_related);
$stmt_related->bind_param("i", $article_id);
$stmt_related->execute();
$result_related = $stmt_related->get_result();
$related_articles = [];
while ($row = $result_related->fetch_assoc()) {
    $related_articles[] = $row;
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($article['titre']); ?> - NutriVie</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
    <?php include '../includes/header.php'; ?>

    <main>
        <article class="article-full">
            <header class="article-header">
                <h1><?php echo htmlspecialchars($article['titre']); ?></h1>
                <div class="article-meta">
                    <span class="date"><?php echo date('d/m/Y', strtotime($article['date_creation'])); ?></span>
                    <?php if ($article['nom'] && $article['prenom']): ?>
                        <span class="author">Par <?php echo htmlspecialchars($article['prenom']) . ' ' . htmlspecialchars($article['nom']); ?></span>
                    <?php endif; ?>
                </div>
            </header>

            <div class="article-content">
                <?php
                    // Conversion des retours à la ligne en balises <p> pour une meilleure présentation
                    $paragraphs = explode("\n\n", $article['contenu']);
                    foreach ($paragraphs as $paragraph) {
                        if (trim($paragraph) !== '') {
                            echo '<p>' . nl2br(htmlspecialchars($paragraph)) . '</p>';
                        }
                    }
                ?>
            </div>

            <footer class="article-footer">
                <div class="share-buttons">
                    <h3>Partagez cet article</h3>
                    <div class="social-share">
                        <a href="#" title="Partager sur Facebook" class="share-facebook"><i class="fa fa-facebook-square"></i> Facebook</a>
                        <a href="#" title="Partager sur Twitter" class="share-twitter"><i class="fa fa-twitter-square"></i> Twitter</a>
                        <a href="#" title="Partager par email" class="share-email"><i class="fa fa-envelope"></i> Email</a>
                    </div>
                </div>

                <?php if (count($related_articles) > 0): ?>
                    <div class="related-articles">
                        <h3>Articles associés</h3>
                        <ul>
                            <?php foreach ($related_articles as $related): ?>
                                <li>
                                    <a href="article.php?id=<?php echo $related['id']; ?>">
                                        <?php echo htmlspecialchars($related['titre']); ?>
                                    </a>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                <?php endif; ?>

                <div class="article-navigation">
                    <a href="articles.php" class="back-to-articles">Retour à la liste des articles</a>
                </div>
            </footer>
        </article>

        <aside class="sidebar">
            <div class="sidebar-widget">
                <h3>Outils bien-être</h3>
                <p>Découvrez nos outils interactifs pour améliorer votre santé et votre bien-être.</p>
                <a href="outils.php" class="btn">Accéder aux outils</a>
            </div>

            <div class="sidebar-widget">
                <h3>Besoin de conseils personnalisés ?</h3>
                <p>Contactez-nous pour obtenir des conseils adaptés à vos besoins spécifiques.</p>
                <a href="contact.php" class="btn">Contactez-nous</a>
            </div>

            <?php if (!isset($_SESSION['user_id'])): ?>
                <div class="sidebar-widget cta-box">
                    <h3>Rejoignez notre communauté</h3>
                    <p>Créez un compte gratuit pour accéder à tous nos outils de suivi et personnaliser votre expérience.</p>
                    <a href="register.php" class="btn">S'inscrire gratuitement</a>
                </div>
            <?php endif; ?>
        </aside>
    </main>

    <?php include '../includes/footer.php'; ?>
</body>
</html>
