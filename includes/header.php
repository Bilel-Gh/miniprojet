<header>
    <div class="logo">
        <h1>NutriVie</h1>
    </div>
    <nav>
        <ul>
            <li><a href="<?php echo $root_path; ?>index.php">Accueil</a></li>
            <li><a href="<?php echo $root_path; ?>pages/articles.php">Articles</a></li>
            <li><a href="<?php echo $root_path; ?>pages/outils.php">Outils bien-être</a></li>
            <li><a href="<?php echo $root_path; ?>pages/contact.php">Contact</a></li>
            <?php
            if (isset($_SESSION['user_id'])) {
                echo '<li><a href="' . $root_path . 'pages/mon-profil.php">Mon Profil</a></li>';

                // Si c'est un administrateur, ajouter un lien vers le panneau d'administration
                if (isset($_SESSION['role']) && $_SESSION['role'] == 'admin') {
                    echo '<li><a href="' . $root_path . 'admin/index.php">Administration</a></li>';
                }

                echo '<li><a href="' . $root_path . 'pages/logout.php">Déconnexion</a></li>';
            } else {
                echo '<li><a href="' . $root_path . 'pages/login.php">Connexion</a></li>';
                echo '<li><a href="' . $root_path . 'pages/register.php">Inscription</a></li>';
            }
            ?>
        </ul>
    </nav>
</header>
