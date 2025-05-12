document.addEventListener('DOMContentLoaded', function() {
    // Afficher/masquer le menu sur mobile
    const mobileMenuToggle = document.querySelector('.mobile-menu-toggle');
    if (mobileMenuToggle) {
        mobileMenuToggle.addEventListener('click', function() {
            const nav = document.querySelector('nav');
            nav.classList.toggle('active');
        });
    }

    // Afficher/masquer les messages après un délai
    const messages = document.querySelectorAll('.message');
    if (messages.length > 0) {
        setTimeout(function() {
            messages.forEach(function(message) {
                message.classList.add('fade-out');
                setTimeout(function() {
                    message.style.display = 'none';
                }, 500);
            });
        }, 3000);
    }

    // Gestion des onglets (si présents sur la page)
    const tabBtns = document.querySelectorAll('.tab-btn');
    if (tabBtns.length > 0) {
        tabBtns.forEach(function(btn) {
            btn.addEventListener('click', function() {
                const tab = this.dataset.tab;

                // Retirer la classe 'active' de tous les boutons et contenus
                document.querySelectorAll('.tab-btn').forEach(btn => btn.classList.remove('active'));
                document.querySelectorAll('.tab-content').forEach(content => content.classList.remove('active'));

                // Ajouter la classe 'active' au bouton et au contenu correspondant
                this.classList.add('active');
                document.getElementById(tab).classList.add('active');
            });
        });
    }

    // Gestion des FAQ (si présentes sur la page)
    const faqItems = document.querySelectorAll('.faq-item h3');
    if (faqItems.length > 0) {
        faqItems.forEach(function(item) {
            item.addEventListener('click', function() {
                this.parentElement.classList.toggle('active');
            });
        });
    }

    // Validation des formulaires
    const forms = document.querySelectorAll('form');
    if (forms.length > 0) {
        forms.forEach(function(form) {
            form.addEventListener('submit', function(event) {
                let isValid = true;

                // Vérifier les champs obligatoires
                const requiredFields = form.querySelectorAll('[required]');
                requiredFields.forEach(function(field) {
                    if (!field.value.trim()) {
                        isValid = false;
                        field.classList.add('error');
                    } else {
                        field.classList.remove('error');
                    }
                });

                // Vérifier les adresses email
                const emailFields = form.querySelectorAll('input[type="email"]');
                emailFields.forEach(function(field) {
                    if (field.value.trim() && !isValidEmail(field.value.trim())) {
                        isValid = false;
                        field.classList.add('error');
                    }
                });

                // Empêcher l'envoi du formulaire si non valide
                if (!isValid) {
                    event.preventDefault();

                    // Afficher un message d'erreur
                    const formMessage = document.createElement('div');
                    formMessage.className = 'message error';
                    formMessage.textContent = 'Veuillez corriger les erreurs dans le formulaire.';

                    // Insérer le message au début du formulaire
                    form.insertBefore(formMessage, form.firstChild);

                    // Supprimer le message après un délai
                    setTimeout(function() {
                        formMessage.remove();
                    }, 3000);
                }
            });
        });
    }

    // Fonction de validation d'email
    function isValidEmail(email) {
        const regex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        return regex.test(email);
    }

    // Gestion des graphiques pour le suivi (si présent sur la page)
    const healthTrackingTable = document.querySelector('.health-tracking-table');
    if (healthTrackingTable) {
        // Ici, vous pourriez ajouter du code pour créer des graphiques
        // basés sur les données du tableau (avec Chart.js par exemple)
    }

    // Animation du défilement lors du clic sur les ancres
    const anchorLinks = document.querySelectorAll('a[href^="#"]:not([href="#"])');
    if (anchorLinks.length > 0) {
        anchorLinks.forEach(function(link) {
            link.addEventListener('click', function(e) {
                e.preventDefault();

                const targetId = this.getAttribute('href');
                const targetElement = document.querySelector(targetId);

                if (targetElement) {
                    window.scrollTo({
                        top: targetElement.offsetTop - 80, // 80px de décalage pour la navigation fixe
                        behavior: 'smooth'
                    });
                }
            });
        });
    }

    // Bouton de retour en haut de page
    const scrollTopBtn = document.querySelector('.scroll-top');
    if (scrollTopBtn) {
        window.addEventListener('scroll', function() {
            if (window.pageYOffset > 300) {
                scrollTopBtn.classList.add('visible');
            } else {
                scrollTopBtn.classList.remove('visible');
            }
        });

        scrollTopBtn.addEventListener('click', function() {
            window.scrollTo({
                top: 0,
                behavior: 'smooth'
            });
        });
    }
});
