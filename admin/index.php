<?php
session_start();
include '../includes/db.php';

// Vérifier si l'utilisateur est connecté et a le rôle d'administrateur
if (!isset($_SESSION['user_id']) || !isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header('Location: ../index.php');
    exit;
}

// Récupérer quelques statistiques pour le tableau de bord
$stats = [
    'total_users' => 0,
    'total_articles' => 0,
    'total_messages' => 0,
    'unread_messages' => 0
];

// Nombre total d'utilisateurs
$sql = "SELECT COUNT(*) as count FROM utilisateurs";
$result = $conn->query($sql);
if ($result) {
    $row = $result->fetch_assoc();
    $stats['total_users'] = $row['count'];
}

// Nombre total d'articles
$sql = "SELECT COUNT(*) as count FROM articles";
$result = $conn->query($sql);
if ($result) {
    $row = $result->fetch_assoc();
    $stats['total_articles'] = $row['count'];
}

// Nombre total de messages de contact
$sql = "SELECT COUNT(*) as count FROM contact";
$result = $conn->query($sql);
if ($result) {
    $row = $result->fetch_assoc();
    $stats['total_messages'] = $row['count'];
}

// Nombre de messages non lus
$sql = "SELECT COUNT(*) as count FROM contact WHERE lu = 0";
$result = $conn->query($sql);
if ($result) {
    $row = $result->fetch_assoc();
    $stats['unread_messages'] = $row['count'];
}

// Récupérer les derniers utilisateurs inscrits
$sql = "SELECT id, nom, prenom, email, date_inscription FROM utilisateurs ORDER BY date_inscription DESC LIMIT 5";
$result = $conn->query($sql);
$recent_users = [];
if ($result) {
    while ($row = $result->fetch_assoc()) {
        $recent_users[] = $row;
    }
}

// Récupérer les derniers messages de contact
$sql = "SELECT id, nom, email, sujet, date_envoi, lu FROM contact ORDER BY date_envoi DESC LIMIT 5";
$result = $conn->query($sql);
$recent_messages = [];
if ($result) {
    while ($row = $result->fetch_assoc()) {
        $recent_messages[] = $row;
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Administration - NutriVie</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
    <div class="admin-container">
        <?php include 'sidebar.php'; ?>

        <main class="admin-main">
            <header class="admin-header">
                <h1>Tableau de bord</h1>
                <div class="admin-user">
                    <span>Connecté en tant que <?php echo htmlspecialchars($_SESSION['user_prenom'] . ' ' . $_SESSION['user_nom']); ?></span>
                    <a href="../pages/logout.php" class="logout-btn">Déconnexion</a>
                </div>
            </header>

            <div class="dashboard">
                <div class="stats-cards">
                    <div class="stat-card">
                        <h3>Utilisateurs</h3>
                        <div class="stat-number"><?php echo $stats['total_users']; ?></div>
                        <a href="users.php" class="stat-link">Gérer les utilisateurs</a>
                    </div>

                    <div class="stat-card">
                        <h3>Articles</h3>
                        <div class="stat-number"><?php echo $stats['total_articles']; ?></div>
                        <a href="articles.php" class="stat-link">Gérer les articles</a>
                    </div>

                    <div class="stat-card">
                        <h3>Messages</h3>
                        <div class="stat-number"><?php echo $stats['total_messages']; ?></div>
                        <div class="stat-detail"><?php echo $stats['unread_messages']; ?> non lus</div>
                        <a href="messages.php" class="stat-link">Voir les messages</a>
                    </div>
                </div>

                <div class="dashboard-sections">
                    <section class="recent-users">
                        <h2>Derniers utilisateurs inscrits</h2>
                        <?php if (count($recent_users) > 0): ?>
                            <table class="admin-table">
                                <thead>
                                    <tr>
                                        <th>Nom</th>
                                        <th>Email</th>
                                        <th>Date d'inscription</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($recent_users as $user): ?>
                                        <tr>
                                            <td><?php echo htmlspecialchars($user['prenom'] . ' ' . $user['nom']); ?></td>
                                            <td><?php echo htmlspecialchars($user['email']); ?></td>
                                            <td><?php echo date('d/m/Y', strtotime($user['date_inscription'])); ?></td>
                                            <td>
                                                <a href="user_edit.php?id=<?php echo $user['id']; ?>" class="action-btn">Modifier</a>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                            <a href="users.php" class="view-all-link">Voir tous les utilisateurs</a>
                        <?php else: ?>
                            <p>Aucun utilisateur inscrit.</p>
                        <?php endif; ?>
                    </section>

                    <section class="recent-messages">
                        <h2>Derniers messages reçus</h2>
                        <?php if (count($recent_messages) > 0): ?>
                            <table class="admin-table">
                                <thead>
                                    <tr>
                                        <th>Expéditeur</th>
                                        <th>Sujet</th>
                                        <th>Date</th>
                                        <th>Statut</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($recent_messages as $message): ?>
                                        <tr class="<?php echo ($message['lu'] == 0) ? 'unread' : ''; ?>">
                                            <td><?php echo htmlspecialchars($message['nom']); ?></td>
                                            <td><?php echo htmlspecialchars($message['sujet']); ?></td>
                                            <td><?php echo date('d/m/Y H:i', strtotime($message['date_envoi'])); ?></td>
                                            <td><?php echo ($message['lu'] == 0) ? 'Non lu' : 'Lu'; ?></td>
                                            <td>
                                                <a href="message_view.php?id=<?php echo $message['id']; ?>" class="action-btn">Voir</a>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                            <a href="messages.php" class="view-all-link">Voir tous les messages</a>
                        <?php else: ?>
                            <p>Aucun message reçu.</p>
                        <?php endif; ?>
                    </section>
                </div>

                <div class="quick-actions">
                    <h2>Actions rapides</h2>
                    <div class="action-buttons">
                        <a href="article_add.php" class="action-button">
                            <i class="fa fa-plus-circle"></i>
                            Ajouter un article
                        </a>
                        <a href="user_add.php" class="action-button">
                            <i class="fa fa-user-plus"></i>
                            Ajouter un utilisateur
                        </a>
                        <a href="../index.php" class="action-button">
                            <i class="fa fa-home"></i>
                            Voir le site
                        </a>
                    </div>
                </div>
            </div>
        </main>
    </div>
</body>
</html>
