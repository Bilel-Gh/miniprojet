<?php
session_start();
include '../includes/db.php';
include '../includes/config.php';
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Outils bien-être - NutriVie</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
    <?php include '../includes/header.php'; ?>

    <main>
        <section class="tools-section">
            <h1>Outils bien-être</h1>
            <p>Découvrez nos outils interactifs pour vous aider à atteindre vos objectifs de bien-être et de santé.</p>

            <div class="tabs">
                <button class="tab-btn active" data-tab="bmi-calculator">Calculateur d'IMC</button>
                <button class="tab-btn" data-tab="calorie-calculator">Calculateur de calories</button>
                <button class="tab-btn" data-tab="nutrition-advice">Conseils nutritionnels</button>
            </div>

            <div id="bmi-calculator" class="tab-content active">
                <h2>Calculateur d'Indice de Masse Corporelle (IMC)</h2>
                <p>L'IMC est un indicateur qui permet d'évaluer la corpulence d'une personne en fonction de sa taille et de son poids.</p>

                <form id="bmi-form">
                    <div class="form-group">
                        <label for="weight">Poids (kg)</label>
                        <input type="number" id="weight" step="0.1" min="0" required>
                    </div>

                    <div class="form-group">
                        <label for="height">Taille (cm)</label>
                        <input type="number" id="height" step="0.1" min="0" required>
                    </div>

                    <button type="submit">Calculer mon IMC</button>
                </form>

                <div id="bmi-result" class="result"></div>

                <div class="info-box">
                    <h3>Interprétation de l'IMC</h3>
                    <ul>
                        <li><strong>Moins de 18,5 :</strong> Insuffisance pondérale</li>
                        <li><strong>18,5 à 24,9 :</strong> Corpulence normale</li>
                        <li><strong>25 à 29,9 :</strong> Surpoids</li>
                        <li><strong>30 à 34,9 :</strong> Obésité modérée</li>
                        <li><strong>35 à 39,9 :</strong> Obésité sévère</li>
                        <li><strong>40 et plus :</strong> Obésité morbide</li>
                    </ul>
                    <p><em>Note: L'IMC est un indicateur simple mais imparfait. Il ne prend pas en compte la répartition de la masse grasse et musculaire. Consultez un professionnel de santé pour une évaluation complète.</em></p>
                </div>
            </div>

            <div id="calorie-calculator" class="tab-content">
                <h2>Calculateur de besoins caloriques</h2>
                <p>Estimez votre besoin calorique quotidien en fonction de votre profil et de votre niveau d'activité.</p>

                <form id="calorie-form">
                    <div class="form-group">
                        <label for="age">Âge</label>
                        <input type="number" id="age" min="0" required>
                    </div>

                    <div class="form-group">
                        <label>Sexe</label>
                        <div class="radio-group">
                            <input type="radio" id="gender-male" name="gender" value="male" checked>
                            <label for="gender-male">Homme</label>

                            <input type="radio" id="gender-female" name="gender" value="female">
                            <label for="gender-female">Femme</label>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="weight-cal">Poids (kg)</label>
                        <input type="number" id="weight-cal" step="0.1" min="0" required>
                    </div>

                    <div class="form-group">
                        <label for="height-cal">Taille (cm)</label>
                        <input type="number" id="height-cal" step="0.1" min="0" required>
                    </div>

                    <div class="form-group">
                        <label for="activity">Niveau d'activité</label>
                        <select id="activity" required>
                            <option value="1.2">Sédentaire (peu ou pas d'exercice)</option>
                            <option value="1.375">Légèrement actif (exercice léger 1-3 jours/semaine)</option>
                            <option value="1.55" selected>Modérément actif (exercice modéré 3-5 jours/semaine)</option>
                            <option value="1.725">Très actif (exercice intense 6-7 jours/semaine)</option>
                            <option value="1.9">Extrêmement actif (exercice très intense, travail physique)</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="goal">Objectif</label>
                        <select id="goal" required>
                            <option value="lose">Perdre du poids</option>
                            <option value="maintain" selected>Maintenir mon poids</option>
                            <option value="gain">Prendre du poids</option>
                        </select>
                    </div>

                    <button type="submit">Calculer mes besoins caloriques</button>
                </form>

                <div id="calorie-result" class="result"></div>

                <div class="info-box">
                    <h3>À propos du calcul des calories</h3>
                    <p>Ce calculateur utilise la formule de Harris-Benedict pour estimer votre métabolisme de base (MB), c'est-à-dire les calories que votre corps brûle au repos. Puis, en fonction de votre niveau d'activité, il calcule votre besoin énergétique total.</p>
                    <p><em>Note: Il s'agit d'une estimation. Les besoins réels peuvent varier en fonction de plusieurs facteurs individuels. Consultez un nutritionniste pour un plan personnalisé.</em></p>
                </div>
            </div>

            <div id="nutrition-advice" class="tab-content">
                <h2>Conseils nutritionnels</h2>

                <div class="advice-card">
                    <h3>Les principes d'une alimentation équilibrée</h3>
                    <ul>
                        <li>Privilégiez les aliments non transformés</li>
                        <li>Mangez au moins 5 portions de fruits et légumes par jour</li>
                        <li>Limitez les aliments riches en sucres, sel et graisses saturées</li>
                        <li>Hydratez-vous suffisamment (environ 1,5L d'eau par jour)</li>
                        <li>Variez votre alimentation pour couvrir tous vos besoins nutritionnels</li>
                    </ul>
                </div>

                <div class="advice-card">
                    <h3>Macronutriments essentiels</h3>
                    <div class="nutrient">
                        <h4>Protéines</h4>
                        <p>Indispensables pour construire et réparer les tissus. On les trouve dans les viandes, poissons, œufs, légumineuses et produits laitiers.</p>
                    </div>
                    <div class="nutrient">
                        <h4>Glucides</h4>
                        <p>Source principale d'énergie. Privilégiez les glucides complexes (céréales complètes, légumineuses) plutôt que simples (sucre, confiseries).</p>
                    </div>
                    <div class="nutrient">
                        <h4>Lipides</h4>
                        <p>Nécessaires pour l'absorption des vitamines et le fonctionnement cellulaire. Préférez les graisses insaturées (huile d'olive, avocat, poissons gras).</p>
                    </div>
                </div>

                <div class="advice-card">
                    <h3>Conseils pour une bonne hygiène alimentaire</h3>
                    <ul>
                        <li>Prenez le temps de manger, mastiquez lentement</li>
                        <li>Écoutez vos sensations de faim et de satiété</li>
                        <li>Évitez de manger devant les écrans</li>
                        <li>Prévoyez des repas réguliers et équilibrés</li>
                        <li>Limitez les grignotages</li>
                    </ul>
                </div>

                <?php if (isset($_SESSION['user_id'])): ?>
                <div class="cta-box">
                    <h3>Envie d'un suivi personnalisé ?</h3>
                    <p>Utilisez notre outil de <a href="mon-profil.php">suivi de santé</a> pour enregistrer vos données et suivre votre progression au fil du temps.</p>
                </div>
                <?php else: ?>
                <div class="cta-box">
                    <h3>Envie d'un suivi personnalisé ?</h3>
                    <p><a href="register.php">Créez un compte</a> pour accéder à nos outils de suivi de santé et enregistrer vos données.</p>
                </div>
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

            // Calculateur d'IMC
            const bmiForm = document.getElementById('bmi-form');
            const bmiResult = document.getElementById('bmi-result');

            bmiForm.addEventListener('submit', function(e) {
                e.preventDefault();

                const weight = parseFloat(document.getElementById('weight').value);
                const height = parseFloat(document.getElementById('height').value) / 100; // Convertir en mètres

                if (weight > 0 && height > 0) {
                    const bmi = weight / (height * height);
                    let category = '';
                    let color = '';

                    if (bmi < 18.5) {
                        category = 'Insuffisance pondérale';
                        color = '#3498db'; // Bleu
                    } else if (bmi < 25) {
                        category = 'Corpulence normale';
                        color = '#2ecc71'; // Vert
                    } else if (bmi < 30) {
                        category = 'Surpoids';
                        color = '#f39c12'; // Orange
                    } else if (bmi < 35) {
                        category = 'Obésité modérée';
                        color = '#e74c3c'; // Rouge
                    } else if (bmi < 40) {
                        category = 'Obésité sévère';
                        color = '#c0392b'; // Rouge foncé
                    } else {
                        category = 'Obésité morbide';
                        color = '#7d3c98'; // Violet
                    }

                    bmiResult.innerHTML = `
                        <h3>Votre IMC est de <span style="color: ${color};">${bmi.toFixed(1)}</span></h3>
                        <p>Catégorie : <strong style="color: ${color};">${category}</strong></p>
                    `;
                }
            });

            // Calculateur de calories
            const calorieForm = document.getElementById('calorie-form');
            const calorieResult = document.getElementById('calorie-result');

            calorieForm.addEventListener('submit', function(e) {
                e.preventDefault();

                const age = parseInt(document.getElementById('age').value);
                const gender = document.querySelector('input[name="gender"]:checked').value;
                const weight = parseFloat(document.getElementById('weight-cal').value);
                const height = parseFloat(document.getElementById('height-cal').value);
                const activity = parseFloat(document.getElementById('activity').value);
                const goal = document.getElementById('goal').value;

                let bmr = 0;

                // Formule de Harris-Benedict
                if (gender === 'male') {
                    bmr = 88.362 + (13.397 * weight) + (4.799 * height) - (5.677 * age);
                } else {
                    bmr = 447.593 + (9.247 * weight) + (3.098 * height) - (4.330 * age);
                }

                // Appliquer le facteur d'activité
                let totalCalories = bmr * activity;

                // Ajuster en fonction de l'objectif
                let goalCalories = totalCalories;
                let goalText = '';

                if (goal === 'lose') {
                    goalCalories = totalCalories - 500; // Déficit de 500 calories pour perdre environ 0.5kg par semaine
                    goalText = 'Pour perdre du poids (environ 0.5kg par semaine)';
                } else if (goal === 'gain') {
                    goalCalories = totalCalories + 500; // Surplus de 500 calories pour gagner environ 0.5kg par semaine
                    goalText = 'Pour prendre du poids (environ 0.5kg par semaine)';
                } else {
                    goalText = 'Pour maintenir votre poids actuel';
                }

                calorieResult.innerHTML = `
                    <h3>Vos besoins caloriques quotidiens</h3>
                    <p>Métabolisme de base (calories brûlées au repos) : <strong>${Math.round(bmr)} kcal</strong></p>
                    <p>Dépense énergétique totale : <strong>${Math.round(totalCalories)} kcal</strong></p>
                    <p>${goalText} : <strong>${Math.round(goalCalories)} kcal</strong></p>
                `;
            });
        });
    </script>
</body>
</html>
