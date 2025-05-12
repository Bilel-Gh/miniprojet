<?php
session_start();
include '../includes/db.php';

// Pagination
$articles_par_page = 6;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$debut = ($page - 1) * $articles_par_page;

// Récupérer le nombre total d'articles
$sql_count = "SELECT COUNT(*) as total FROM articles";
$result_count = $conn->query($sql_count);
$row_count = $result_count->fetch_assoc();
$total_articles = $row_count['total'];
$total_pages = ceil($total_articles / $articles_par_page);

// Récupérer les articles pour la page actuelle
$sql = "SELECT a.*, u.nom, u.prenom
        FROM articles a
        LEFT JOIN utilisateurs u ON a.id_auteur = u.id
        ORDER BY a.date_creation DESC
        LIMIT ?, ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ii", $debut, $articles_par_page);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Articles - NutriVie</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
    <?php include '../includes/header.php'; ?>

    <main>
        <section class="articles-section">
            <h1>Nos articles sur la nutrition et le bien-être</h1>
            <p>Découvrez nos derniers articles pour vous aider à améliorer votre alimentation et votre bien-être au quotidien.</p>

            <?php if ($result->num_rows > 0): ?>
                <div class="articles-grid">
                    <?php while ($article = $result->fetch_assoc()): ?>
                        <div class="article-card">
                            <h2><?php echo htmlspecialchars($article['titre']); ?></h2>
                            <div class="article-meta">
                                <span class="date"><?php echo date('d/m/Y', strtotime($article['date_creation'])); ?></span>
                                <?php if ($article['nom'] && $article['prenom']): ?>
                                    <span class="author">Par <?php echo htmlspecialchars($article['prenom']) . ' ' . htmlspecialchars($article['nom']); ?></span>
                                <?php endif; ?>
                            </div>
                            <p class="article-excerpt">
                                <?php
                                    $excerpt = substr(strip_tags($article['contenu']), 0, 200);
                                    echo htmlspecialchars($excerpt) . '...';
                                ?>
                            </p>
                            <a href="article.php?id=<?php echo $article['id']; ?>" class="read-more">Lire la suite</a>
                        </div>
                    <?php endwhile; ?>
                </div>

                <?php if ($total_pages > 1): ?>
                    <div class="pagination">
                        <?php if ($page > 1): ?>
                            <a href="?page=<?php echo $page - 1; ?>" class="pagination-link">&laquo; Précédent</a>
                        <?php endif; ?>

                        <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                            <?php if ($i == $page): ?>
                                <span class="pagination-current"><?php echo $i; ?></span>
                            <?php else: ?>
                                <a href="?page=<?php echo $i; ?>" class="pagination-link"><?php echo $i; ?></a>
                            <?php endif; ?>
                        <?php endfor; ?>

                        <?php if ($page < $total_pages): ?>
                            <a href="?page=<?php echo $page + 1; ?>" class="pagination-link">Suivant &raquo;</a>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>

            <?php else: ?>
                <div class="message">
                    <p>Aucun article n'est disponible pour le moment. Revenez bientôt !</p>
                </div>
            <?php endif; ?>
        </section>

        <aside class="sidebar">
            <div class="sidebar-widget">
                <h3>Catégories</h3>
                <ul class="categories-list">
                    <li><a href="#">Nutrition équilibrée</a></li>
                    <li><a href="#">Recettes saines</a></li>
                    <li><a href="#">Activité physique</a></li>
                    <li><a href="#">Gestion du stress</a></li>
                    <li><a href="#">Sommeil et récupération</a></li>
                </ul>
            </div>

            <div class="sidebar-widget">
                <h3>Articles populaires</h3>
                <ul class="popular-articles">
                    <li><a href="#">Les bienfaits des protéines végétales</a></li>
                    <li><a href="#">Comment gérer le stress par l'alimentation</a></li>
                    <li><a href="#">Les meilleurs aliments pour récupérer après le sport</a></li>
                    <li><a href="#">Mythes et réalités sur les régimes</a></li>
                </ul>
            </div>

            <div class="sidebar-widget">
                <h3>Outils bien-être</h3>
                <p>Découvrez nos outils interactifs pour améliorer votre santé et votre bien-être.</p>
                <a href="outils.php" class="btn">Accéder aux outils</a>
            </div>
        </aside>
    </main>

    <?php include '../includes/footer.php'; ?>
</body>
</html>
