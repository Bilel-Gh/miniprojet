<?php
session_start();
include 'includes/db.php';
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>NutriVie - Votre partenaire santé et bien-être</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <?php include 'includes/header.php'; ?>

    <main>
        <section class="hero">
            <h1>Bienvenue sur NutriVie</h1>
            <p>Votre partenaire pour une meilleure santé et un bien-être optimal</p>
        </section>

        <section class="features">
            <h2>Nos Services</h2>
            <div class="feature-cards">
                <div class="card">
                    <h3>Conseils Nutritionnels</h3>
                    <p>Découvrez des conseils personnalisés pour améliorer votre alimentation.</p>
                </div>
                <div class="card">
                    <h3>Programme d'Exercices</h3>
                    <p>Accédez à des programmes d'exercices adaptés à vos besoins.</p>
                </div>
                <div class="card">
                    <h3>Suivi Personnel</h3>
                    <p>Suivez votre progression et vos objectifs de bien-être.</p>
                </div>
            </div>
        </section>

        <section class="latest-articles">
            <h2>Nos Derniers Articles</h2>
            <?php
            // Récupérer les 3 derniers articles
            $sql = "SELECT * FROM articles ORDER BY date_creation DESC LIMIT 3";
            $result = $conn->query($sql);

            if ($result && $result->num_rows > 0) {
                echo '<div class="article-list">';
                while ($row = $result->fetch_assoc()) {
                    echo '<div class="article">';
                    echo '<h3>' . htmlspecialchars($row['titre']) . '</h3>';
                    echo '<p>' . substr(htmlspecialchars($row['contenu']), 0, 150) . '...</p>';
                    echo '<a href="pages/article.php?id=' . $row['id'] . '">Lire la suite</a>';
                    echo '</div>';
                }
                echo '</div>';
            } else {
                echo '<p>Aucun article disponible pour le moment.</p>';
            }
            ?>
        </section>
    </main>

    <?php include 'includes/footer.php'; ?>
    <script src="js/main.js"></script>
</body>
</html>
