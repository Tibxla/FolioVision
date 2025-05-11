// Garantit que le code s'exécute uniquement après le chargement complet du DOM pour éviter les erreurs liées à des éléments non disponibles
document.addEventListener('DOMContentLoaded', function() {
    // Fonction anonyme regroupant l'ensemble du code principal, exécutée une fois le DOM prêt

    // === 1. Déclaration des éléments du DOM ===
    // Variables capturant les références aux éléments HTML pour une manipulation future
    const form = document.getElementById('contact-form'); // Formulaire général utilisé pour le contact
    const modal = document.getElementById('modal'); // Fenêtre modale générique pour afficher des messages à l'utilisateur
    const modalMessage = document.getElementById('modal-message'); // Texte affiché dans la fenêtre modale
    const username = document.querySelector('.username'); // Élément affichant le nom de l'utilisateur actuellement connecté
    const dropdown = document.querySelector('.dropdown-content'); // Menu déroulant contenant les options utilisateur
    const sidebar = document.getElementById('sidebar'); // Barre latérale servant à la navigation dans l'application
    const toggleButton = document.querySelector('.sidebar-toggle'); // Bouton permettant d'afficher ou masquer la barre latérale
    const content = document.querySelector('.dashboard-content'); // Zone principale du tableau de bord affichant le contenu
    const presetForm = document.getElementById('preset-form'); // Formulaire dédié à la sauvegarde d'un thème personnalisé
    const applyButton = document.getElementById('apply-button'); // Bouton pour appliquer un thème sans le sauvegarder
    const colorInput = document.getElementById('text_color'); // Champ de sélection de couleur pour personnaliser l'interface
    const colorPreview = document.getElementById('color-preview'); // Zone d'aperçu affichant la couleur sélectionnée
    const addAccountButton = document.getElementById('add-account-button'); // Bouton déclenchant l'ouverture du formulaire d'ajout de compte
    const addAccountForm = document.getElementById('add-account-form'); // Formulaire pour la création d'un nouveau compte
    const accountForm = document.getElementById('account-form'); // Formulaire contenant les détails spécifiques d'un compte
    const errorMessage = document.getElementById('error-message'); // Élément réservé à l'affichage des messages d'erreur
    // Éléments liés à la fenêtre modale des transactions
    const openTransactionModalBtn = document.getElementById('open-transaction-modal'); // Bouton ouvrant la modale pour ajouter une transaction
    const transactionModal = document.getElementById('transaction-modal'); // Fenêtre modale pour la gestion des transactions
    const addTransactionForm = document.getElementById('add-transaction-form'); // Formulaire pour enregistrer une nouvelle transaction
    // Éléments liés à la fenêtre modale des catégories
    const categoryModal = document.getElementById('category-modal'); // Fenêtre modale pour la création de catégories
    const closeCategoryModal = document.getElementById('close-category-modal'); // Bouton fermant la modale des catégories
    const addCategoryForm = document.getElementById('add-category-form'); // Formulaire pour ajouter une nouvelle catégorie
    const categorySelect = document.getElementById('category_id'); // Menu déroulant listant les catégories existantes
    const mainCategorySelect = document.getElementById('main_category_id'); // Menu déroulant des catégories principales
    const subCategorySelect = document.getElementById('sub_category_id'); // Menu déroulant des sous-catégories
    const openMainCategoryModalBtn = document.getElementById('open-main-category-modal'); // Bouton ouvrant la modale pour une catégorie principale
    const mainCategoryModal = document.getElementById('main-category-modal'); // Fenêtre modale pour créer une catégorie principale
    const closeMainCategoryModal = document.getElementById('close-main-category-modal'); // Bouton fermant la modale de catégorie principale
    const addMainCategoryForm = document.getElementById('add-main-category-form'); // Formulaire pour ajouter une catégorie principale
    // Éléments liés à la fenêtre modale des sous-catégories
    const openSubCategoryModalBtn = document.getElementById('open-sub-category-modal'); // Bouton ouvrant la modale pour une sous-catégorie
    const subCategoryModal = document.getElementById('sub-category-modal'); // Fenêtre modale pour créer une sous-catégorie
    const closeSubCategoryModal = document.getElementById('close-sub-category-modal'); // Bouton fermant la modale de sous-catégorie
    const addSubCategoryForm = document.getElementById('add-sub-category-form'); // Formulaire pour ajouter une sous-catégorie
    const accountTypeSelect = document.getElementById('account_type'); // Menu déroulant pour choisir le type de compte
    const bankSubtypeSelect = document.getElementById('bank_subtype'); // Menu déroulant pour le sous-type bancaire
    const bankSubtypeLabel = document.querySelector('label[for="bank_subtype"]'); // Étiquette associée au sous-type bancaire
    const profileInfoForm = document.getElementById('profile-info-form'); // Formulaire pour mettre à jour les informations du profil utilisateur
    const passwordForm = document.getElementById('password-form'); // Formulaire pour modifier le mot de passe de l'utilisateur
    // Ajout d'éléments supplémentaires dans la section DOM
    const openAddInvestmentBtn = document.getElementById('open-add-investment');
    const addInvestmentFormContainer = document.getElementById('add-investment-form-container');
    const cancelAddInvestmentBtn = document.getElementById('cancel-add-investment');
    const addInvestmentForm = document.getElementById('add-investment-form');
    const editInvestmentForm = document.getElementById('edit-investment-form');
    const editInvestmentModal = document.getElementById('edit-investment-modal');
    const closeEditInvestmentModal = document.getElementById('close-edit-investment-modal');
    // Liste prédéfinie des types d'investissement valides selon la base de données
    const validAssetTypes = ['stock', 'crypto', 'real_estate', 'other'];
    // Éléments spécifiques à la page des objectifs (goals)
    const openAddGoalBtn = document.getElementById('open-add-goal');
    const addGoalModal = document.getElementById('add-goal-modal');
    const closeAddGoalModal = document.getElementById('close-add-goal-modal');
    const cancelAddGoalBtn = document.getElementById('cancel-add-goal');
    const addMoneyForm = document.getElementById('add-money-form');
    const removeMoneyForm = document.getElementById('remove-money-form');
    const addMoneyModal = document.getElementById('add-money-modal');
    const removeMoneyModal = document.getElementById('remove-money-modal');
    const closeAddMoneyModal = document.getElementById('close-add-money-modal');
    const closeRemoveMoneyModal = document.getElementById('close-remove-money-modal');
    const editGoalModal = document.getElementById('edit-goal-modal'); // Fenêtre modale pour modifier les objectifs
    const editGoalForm = document.getElementById('edit-goal-form'); // Formulaire intégré à la modale d'édition
    const closeEditGoalModal = document.getElementById('close-edit-goal-modal');
    const cancelEditGoalBtn = document.getElementById('cancel-edit-goal');

    // === 2. Gestion des formulaires de profil et mot de passe ===
    // Traitement de la soumission du formulaire des informations personnelles
    if (profileInfoForm) { // Vérifie l'existence du formulaire avant d'attacher un événement
        profileInfoForm.addEventListener('submit', function(event) { // Écoute l'événement de soumission du formulaire
            event.preventDefault(); // Bloque le rechargement par défaut de la page
            const formData = new FormData(this); // Crée un objet contenant les données saisies dans le formulaire

            fetch('/FolioVision/pages/api/update_profile.php', { // Envoie les données au serveur via une requête AJAX
                method: 'POST', // Utilise la méthode POST pour transmettre les données
                body: formData // Contient les données du formulaire à envoyer
            })
                .then(response => response.json()) // Transforme la réponse du serveur en objet JSON
                .then(data => { // Traite les données renvoyées par le serveur
                    if (data.status === 'success') { // Si la mise à jour a réussi
                        alert(data.message); // Affiche une notification de succès à l'utilisateur
                        // Met à jour les champs visibles sans recharger la page
                        document.getElementById('username').value = data.username; // Actualise le champ du nom d'utilisateur
                        document.getElementById('email').value = data.email; // Actualise le champ de l'email
                    } else { // Si une erreur est survenue
                        alert('Erreur : ' + data.message); // Affiche le message d'erreur fourni par le serveur
                    }
                })
                .catch(error => console.error('Erreur lors de la mise à jour des informations :', error)); // Enregistre les erreurs dans la console
        });
    }

    // Traitement de la soumission du formulaire de changement de mot de passe
    if (passwordForm) { // Vérifie si le formulaire existe avant d'ajouter un écouteur
        passwordForm.addEventListener('submit', function(event) { // Écoute l'événement de soumission
            event.preventDefault(); // Empêche le rechargement par défaut de la page
            const formData = new FormData(this); // Récupère les données saisies dans un objet FormData

            fetch('/FolioVision/pages/api/update_password.php', { // Envoie les données au serveur via AJAX
                method: 'POST', // Utilise la méthode POST pour l'envoi
                body: formData // Contient les données du formulaire
            })
                .then(response => response.json()) // Convertit la réponse en JSON
                .then(data => { // Traite la réponse du serveur
                    if (data.status === 'success') { // Si le mot de passe a été mis à jour avec succès
                        alert(data.message); // Affiche une confirmation à l'utilisateur
                        passwordForm.reset(); // Réinitialise les champs du formulaire
                    } else { // En cas d'échec
                        alert('Erreur : ' + data.message); // Affiche le message d'erreur
                    }
                })
                .catch(error => console.error('Erreur lors de la mise à jour du mot de passe :', error)); // Journalise les erreurs
        });
    }

    // === 3. Gestion des comptes (type et sous-type) ===
    // Fonction pour afficher ou masquer le champ de sous-type bancaire selon le type de compte sélectionné
    function toggleBankSubtype() { // Gère l'affichage du sous-type bancaire
        if (accountTypeSelect && bankSubtypeSelect && bankSubtypeLabel) { // Vérifie que les éléments nécessaires existent
            if (accountTypeSelect.value === 'bank') { // Si le type de compte est "banque"
                bankSubtypeSelect.disabled = false; // Active le menu déroulant du sous-type
                bankSubtypeSelect.style.display = 'block'; // Rend le menu visible
                bankSubtypeLabel.style.display = 'block'; // Affiche l'étiquette correspondante
            } else { // Pour tout autre type de compte
                bankSubtypeSelect.disabled = true; // Désactive le menu déroulant
                bankSubtypeSelect.style.display = 'none'; // Masque le menu
                bankSubtypeLabel.style.display = 'none'; // Masque l'étiquette
                bankSubtypeSelect.value = ''; // Réinitialise la sélection du sous-type
            }
        }
    }

    // Applique l'état initial du champ de sous-type bancaire au chargement de la page
    if (accountTypeSelect) { // Vérifie l'existence du menu déroulant du type de compte
        toggleBankSubtype(); // Initialise l'état du sous-type bancaire
    }

    // Réagit aux changements dans le menu déroulant du type de compte
    if (accountTypeSelect) { // Vérifie si le menu existe
        accountTypeSelect.addEventListener('change', toggleBankSubtype); // Met à jour l'affichage du sous-type lors d'un changement
    }

    // === 4. Gestion des modales pour les catégories ===
    // Ouvre la fenêtre modale pour ajouter une catégorie principale
    if (openMainCategoryModalBtn && mainCategoryModal) { // Vérifie l'existence du bouton et de la modale
        openMainCategoryModalBtn.addEventListener('click', function() { // Écoute le clic sur le bouton
            mainCategoryModal.style.display = 'block'; // Affiche la modale
        });
    }

    // Ferme la fenêtre modale des catégories principales
    if (closeMainCategoryModal && mainCategoryModal) { // Vérifie l'existence du bouton et de la modale
        closeMainCategoryModal.addEventListener('click', function() { // Écoute le clic sur le bouton de fermeture
            mainCategoryModal.style.display = 'none'; // Masque la modale
        });
    }

    // Ouvre la fenêtre modale des sous-catégories et charge les catégories principales
    if (openSubCategoryModalBtn && subCategoryModal) { // Vérifie l'existence du bouton et de la modale
        openSubCategoryModalBtn.addEventListener('click', function() { // Écoute le clic sur le bouton
            subCategoryModal.style.display = 'block'; // Affiche la modale
            loadMainCategoriesForSubModal(); // Remplit le menu déroulant avec les catégories principales
        });
    }

    // Ferme la fenêtre modale des sous-catégories
    if (closeSubCategoryModal && subCategoryModal) { // Vérifie l'existence du bouton et de la modale
        closeSubCategoryModal.addEventListener('click', function() { // Écoute le clic sur le bouton de fermeture
            subCategoryModal.style.display = 'none'; // Masque la modale
        });
    }

    // Ferme les modales si l'utilisateur clique à l'extérieur de leur contenu
    window.addEventListener('click', function(event) { // Écoute les clics sur l'ensemble de la fenêtre
        if (mainCategoryModal && event.target === mainCategoryModal) { // Si le clic est sur le fond de la modale principale
            mainCategoryModal.style.display = 'none'; // Ferme la modale
        }
        if (subCategoryModal && event.target === subCategoryModal) { // Si le clic est sur le fond de la modale des sous-catégories
            subCategoryModal.style.display = 'none'; // Ferme la modale
        }
    });

    // Gère la soumission du formulaire pour créer une catégorie principale
    if (addMainCategoryForm) { // Vérifie si le formulaire existe
        addMainCategoryForm.addEventListener('submit', function(event) { // Écoute la soumission du formulaire
            event.preventDefault(); // Empêche le rechargement de la page
            const formData = new FormData(this); // Récupère les données saisies
            formData.append('parent_category_id', ''); // Indique que cette catégorie n'a pas de parent (catégorie principale)

            const categoryName = formData.get('category_name'); // Récupère le nom de la catégorie
            if (!categoryName || categoryName.trim() === '') { // Vérifie si le nom est vide
                alert('Le nom de la catégorie est requis.'); // Affiche une alerte si le champ est vide
                return; // Interrompt le traitement
            }

            fetch('/FolioVision/pages/api/add_category.php', { // Envoie les données au serveur via AJAX
                method: 'POST', // Utilise la méthode POST
                body: formData // Contient les données du formulaire
            })
                .then(response => response.json()) // Convertit la réponse en JSON
                .then(data => { // Traite les données retournées
                    if (data.status === 'success') { // Si la catégorie a été créée avec succès
                        alert('Catégorie principale créée avec succès !'); // Informe l'utilisateur

                        // Ajoute la nouvelle catégorie au menu déroulant des catégories principales
                        const mainCategorySelect = document.getElementById('main_category_id'); // Récupère le menu déroulant
                        const newOption = document.createElement('option'); // Crée une nouvelle option
                        newOption.value = data.category_id; // Définit l'identifiant de la catégorie
                        newOption.textContent = categoryName; // Affiche le nom de la catégorie
                        mainCategorySelect.appendChild(newOption); // Ajoute l'option au menu

                        // Trie les options du menu par ordre alphabétique
                        sortSelectOptions(mainCategorySelect); // Appelle la fonction de tri

                        // Sélectionne automatiquement la nouvelle catégorie
                        mainCategorySelect.value = data.category_id; // Met la nouvelle catégorie en sélection

                        // Réinitialise le menu des sous-catégories
                        const subCategorySelect = document.getElementById('sub_category_id'); // Récupère le menu des sous-catégories
                        subCategorySelect.innerHTML = '<option value="">-- Sélectionner une sous-catégorie --</option>'; // Remet une option par défaut

                        // Ferme la modale et réinitialise le formulaire
                        if (mainCategoryModal) mainCategoryModal.style.display = 'none'; // Masque la modale
                        addMainCategoryForm.reset(); // Vide les champs du formulaire
                    } else { // En cas d'erreur
                        alert('Erreur : ' + data.message); // Affiche le message d'erreur
                    }
                })
                .catch(error => console.error('Erreur lors de la création de la catégorie principale :', error)); // Journalise les erreurs
        });
    }

    // Gère la soumission du formulaire pour créer une sous-catégorie
    if (addSubCategoryForm) { // Vérifie si le formulaire existe
        addSubCategoryForm.addEventListener('submit', function(event) { // Écoute la soumission du formulaire
            event.preventDefault(); // Empêche le rechargement de la page

            // Récupère les données saisies dans le formulaire
            const formData = new FormData(this); // Crée un objet FormData
            const categoryName = formData.get('category_name'); // Récupère le nom de la sous-catégorie
            const parentCategoryId = formData.get('parent_category_id'); // Récupère l'ID de la catégorie parente

            // Vérifie les données avant envoi
            if (!categoryName || categoryName.trim() === '') { // Si le nom est vide
                alert('Le nom de la sous-catégorie est requis.'); // Affiche une alerte
                return; // Interrompt le traitement
            }
            if (!parentCategoryId) { // Si aucune catégorie parente n'est sélectionnée
                alert('Veuillez sélectionner une catégorie parente.'); // Affiche une alerte
                return; // Interrompt le traitement
            }

            // Envoie les données au serveur
            fetch('/FolioVision/pages/api/add_category.php', { // Requête AJAX pour créer la sous-catégorie
                method: 'POST', // Utilise la méthode POST
                body: formData // Contient les données du formulaire
            })
                .then(response => response.json()) // Convertit la réponse en JSON
                .then(data => { // Traite la réponse du serveur
                    if (data.status === 'success') { // Si la sous-catégorie est créée avec succès
                        alert('Sous-catégorie créée avec succès !'); // Informe l'utilisateur

                        // Récupère les menus déroulants
                        const mainCategorySelect = document.getElementById('main_category_id'); // Menu des catégories principales
                        const subCategorySelect = document.getElementById('sub_category_id'); // Menu des sous-catégories

                        // Vérifie si la catégorie parente correspond à celle sélectionnée
                        if (mainCategorySelect.value !== parentCategoryId) { // Si la catégorie parente n'est pas déjà sélectionnée
                            mainCategorySelect.value = parentCategoryId; // Sélectionne la catégorie parente

                            // Charge les sous-catégories liées à cette catégorie parente
                            fetch(`/FolioVision/pages/api/get_subcategories.php?main_category_id=${parentCategoryId}`) // Requête AJAX
                                .then(response => response.json()) // Convertit la réponse en JSON
                                .then(subData => { // Traite les données des sous-catégories
                                    if (subData.status === 'success') { // Si les sous-catégories sont récupérées
                                        // Réinitialise le menu des sous-catégories
                                        subCategorySelect.innerHTML = '<option value="">-- Sélectionner une sous-catégorie --</option>'; // Option par défaut
                                        // Ajoute les sous-catégories existantes
                                        subData.subcategories.forEach(subcategory => { // Parcourt les sous-catégories
                                            const option = document.createElement('option'); // Crée une nouvelle option
                                            option.value = subcategory.category_id; // Définit l'ID
                                            option.textContent = subcategory.name; // Définit le nom
                                            subCategorySelect.appendChild(option); // Ajoute au menu
                                        });
                                        // Ajoute la nouvelle sous-catégorie et la sélectionne
                                        const newOption = document.createElement('option'); // Crée une option pour la nouvelle sous-catégorie
                                        newOption.value = data.category_id; // Définit l'ID
                                        newOption.textContent = categoryName; // Définit le nom
                                        subCategorySelect.appendChild(newOption); // Ajoute au menu
                                        subCategorySelect.value = data.category_id; // Sélectionne la nouvelle sous-catégorie
                                    }
                                })
                                .catch(error => console.error('Erreur lors du chargement des sous-catégories :', error)); // Journalise les erreurs
                        } else { // Si la catégorie parente est déjà sélectionnée
                            // Ajoute simplement la nouvelle sous-catégorie au menu
                            const newOption = document.createElement('option'); // Crée une nouvelle option
                            newOption.value = data.category_id; // Définit l'ID
                            newOption.textContent = categoryName; // Définit le nom
                            subCategorySelect.appendChild(newOption); // Ajoute au menu

                            // Trie les options par ordre alphabétique
                            sortSelectOptions(subCategorySelect); // Appelle la fonction de tri

                            // Sélectionne la nouvelle sous-catégorie
                            subCategorySelect.value = data.category_id; // Met la nouvelle sous-catégorie en sélection
                        }

                        // Ferme la modale et réinitialise le formulaire
                        if (subCategoryModal) subCategoryModal.style.display = 'none'; // Masque la modale
                        addSubCategoryForm.reset(); // Vide les champs du formulaire
                    } else { // En cas d'erreur
                        alert('Erreur : ' + data.message); // Affiche le message d'erreur
                    }
                })
                .catch(error => console.error('Erreur lors de la création de la sous-catégorie :', error)); // Journalise les erreurs
        });
    }

    // Charge les catégories principales dans le menu déroulant de la modale des sous-catégories
    function loadMainCategoriesForSubModal() { // Récupère et affiche les catégories principales dynamiquement
        fetch('/FolioVision/pages/api/get_main_categories.php') // Requête AJAX pour obtenir les catégories
            .then(response => response.json()) // Convertit la réponse en JSON
            .then(data => { // Traite les données reçues
                const parentCategorySelect = document.getElementById('parent_category_id'); // Récupère le menu déroulant
                if (parentCategorySelect) { // Vérifie son existence
                    parentCategorySelect.innerHTML = '<option value="">-- Sélectionner une catégorie parente --</option>'; // Réinitialise avec une option par défaut
                    if (data.status === 'success') { // Si les données sont récupérées avec succès
                        data.main_categories.forEach(category => { // Parcourt les catégories principales
                            const option = document.createElement('option'); // Crée une nouvelle option
                            option.value = category.category_id; // Définit l'ID
                            option.textContent = category.name; // Définit le nom
                            parentCategorySelect.appendChild(option); // Ajoute au menu
                        });
                    }
                }
            })
            .catch(error => console.error('Erreur lors de la récupération des catégories principales :', error)); // Journalise les erreurs
    }

    // Trie les options d'un menu déroulant par ordre alphabétique
    function sortSelectOptions(selectElement) { // Prend un élément <select> comme paramètre
        // Récupère toutes les options sous forme de tableau
        const options = Array.from(selectElement.options); // Convertit les options en tableau
        // Sépare l'option par défaut (première option) des autres
        const defaultOption = options.shift(); // Retire et conserve l'option par défaut

        // Trie les options restantes alphabétiquement
        options.sort((a, b) => a.textContent.localeCompare(b.textContent)); // Compare le texte des options

        // Vide le menu déroulant actuel
        selectElement.innerHTML = ''; // Supprime toutes les options
        // Replace l'option par défaut en premier
        selectElement.appendChild(defaultOption); // Ajoute l'option par défaut
        // Ajoute les options triées
        options.forEach(option => selectElement.appendChild(option)); // Ajoute chaque option triée
    }

    // Charge dynamiquement les sous-catégories en fonction de la catégorie principale sélectionnée
    if (mainCategorySelect && subCategorySelect) { // Vérifie l'existence des deux menus déroulants
        mainCategorySelect.addEventListener('change', function() { // Écoute les changements dans le menu des catégories principales
            const mainCategoryId = this.value; // Récupère l'ID de la catégorie principale sélectionnée
            subCategorySelect.innerHTML = '<option value="">-- Sélectionner une sous-catégorie --</option>'; // Réinitialise le menu des sous-catégories

            if (mainCategoryId) { // Si une catégorie principale est sélectionnée
                fetch(`/FolioVision/pages/api/get_subcategories.php?main_category_id=${mainCategoryId}`) // Requête AJAX pour les sous-catégories
                    .then(response => response.json()) // Convertit la réponse en JSON
                    .then(data => { // Traite les données
                        if (data.status === 'success') { // Si les sous-catégories sont récupérées
                            data.subcategories.forEach(subcategory => { // Parcourt les sous-catégories
                                const option = document.createElement('option'); // Crée une nouvelle option
                                option.value = subcategory.category_id; // Définit l'ID
                                option.textContent = subcategory.name; // Définit le nom
                                subCategorySelect.appendChild(option); // Ajoute au menu
                            });
                        }
                    })
                    .catch(error => console.error('Erreur lors de la récupération des sous-catégories :', error)); // Journalise les erreurs
            }
        });
    }

    // Ferme la fenêtre modale de création de catégorie
    if (closeCategoryModal) { // Vérifie si le bouton de fermeture existe
        closeCategoryModal.addEventListener('click', function() { // Écoute le clic sur le bouton
            categoryModal.style.display = 'none'; // Masque la modale
        });
    }

    // Ferme la modale si l'utilisateur clique à l'extérieur
    if (categoryModal) { // Vérifie si la modale existe
        window.addEventListener('click', function(event) { // Écoute les clics sur la fenêtre
            if (event.target === categoryModal) { // Si le clic est sur le fond de la modale
                categoryModal.style.display = 'none'; // Ferme la modale
            }
        });
    }

    // Charge les catégories existantes dans un menu déroulant pour sélectionner une catégorie parente
    function loadCategoriesForParentSelect() { // Récupère et affiche les catégories disponibles
        fetch('/FolioVision/pages/api/get_categories.php') // Requête AJAX pour obtenir les catégories
            .then(response => response.json()) // Convertit la réponse en JSON
            .then(data => { // Traite les données
                const parentCategorySelect = document.getElementById('parent_category_id'); // Récupère le menu déroulant
                parentCategorySelect.innerHTML = '<option value="">-- Aucune (catégorie de base) --</option>'; // Réinitialise avec une option par défaut
                if (data.status === 'success') { // Si les catégories sont récupérées
                    data.categories.forEach(category => { // Parcourt les catégories
                        const option = document.createElement('option'); // Crée une nouvelle option
                        option.value = category.category_id; // Définit l'ID
                        option.textContent = category.name; // Définit le nom
                        parentCategorySelect.appendChild(option); // Ajoute au menu
                    });
                }
            })
            .catch(error => console.error('Erreur lors de la récupération des catégories :', error)); // Journalise les erreurs
    }

    // Gère la soumission du formulaire pour créer une catégorie
    if (addCategoryForm) { // Vérifie si le formulaire existe
        addCategoryForm.addEventListener('submit', function(event) { // Écoute la soumission
            event.preventDefault(); // Empêche le rechargement de la page
            const formData = new FormData(this); // Récupère les données saisies
            const categoryName = formData.get('category_name'); // Récupère le nom de la catégorie
            const parentCategoryId = formData.get('parent_category_id'); // Récupère l'ID de la catégorie parente

            if (!categoryName || categoryName.trim() === '') { // Vérifie si le nom est vide
                alert('Le nom de la catégorie est requis.'); // Affiche une alerte
                return; // Interrompt le traitement
            }

            fetch('/FolioVision/pages/api/add_category.php', { // Envoie les données via AJAX
                method: 'POST', // Utilise la méthode POST
                body: formData // Contient les données du formulaire
            })
                .then(response => response.json()) // Convertit la réponse en JSON
                .then(data => { // Traite la réponse
                    if (data.status === 'success') { // Si la catégorie est créée
                        alert('Catégorie créée avec succès !'); // Informe l'utilisateur
                        // Ajoute la nouvelle catégorie au menu déroulant des transactions
                        const newOption = document.createElement('option'); // Crée une nouvelle option
                        newOption.value = data.category_id; // Définit l'ID
                        newOption.textContent = categoryName; // Définit le nom
                        categorySelect.appendChild(newOption); // Ajoute au menu
                        newOption.selected = true; // Sélectionne la nouvelle catégorie
                        // Ferme la modale et réinitialise le formulaire
                        categoryModal.style.display = 'none'; // Masque la modale
                        addCategoryForm.reset(); // Vide les champs
                    } else { // En cas d'erreur
                        alert('Erreur : ' + data.message); // Affiche le message d'erreur
                    }
                })
                .catch(error => console.error('Erreur lors de la création de la catégorie :', error)); // Journalise les erreurs
        });
    }

    // === 5. Gestion de la prévisualisation des couleurs ===
    // Met à jour l'aperçu visuel de la couleur sélectionnée par l'utilisateur
    function updateColorPreview() { // Actualise la couleur de fond de l'élément d'aperçu
        if (colorInput && colorPreview) { // Vérifie que les éléments existent
            colorPreview.style.backgroundColor = colorInput.value; // Applique la couleur choisie à l'aperçu
        }
    }

    // Initialise l'aperçu et écoute les modifications de la couleur
    if (colorInput && colorPreview) { // Vérifie l'existence des éléments
        updateColorPreview(); // Met à jour l'aperçu au chargement initial
        colorInput.addEventListener('input', updateColorPreview); // Met à jour l'aperçu à chaque changement de couleur
    }

    // === 6. Gestion du formulaire de contact ===
    if (form) { // Vérifie si le formulaire de contact existe
        form.addEventListener('submit', function(event) { // Écoute la soumission du formulaire
            event.preventDefault(); // Empêche le rechargement de la page
            const formData = new FormData(this); // Récupère les données saisies
            fetch('/FolioVision/pages/api/submit_contact.php', { // Envoie les données via AJAX
                method: 'POST', // Utilise la méthode POST
                body: formData // Contient les données du formulaire
            })
                .then(response => response.json()) // Convertit la réponse en JSON
                .then(data => { // Traite la réponse
                    modalMessage.textContent = data.success ? 'Votre message a été envoyé avec succès.' : 'Erreur : ' + data.message; // Affiche un message selon le résultat
                    modal.style.display = 'block'; // Ouvre la modale pour montrer le message
                })
                .catch(error => { // En cas d'erreur réseau
                    modalMessage.textContent = 'Une erreur est survenue : ' + error; // Affiche l'erreur rencontrée
                    modal.style.display = 'block'; // Ouvre la modale
                });
        });
    }

    // Gère la fermeture de la modale générique
    if (document.getElementById('modal')) {
        const modal = document.getElementById('modal');
        const closeModal = modal.querySelector('.close');
        if (closeModal) {
            closeModal.addEventListener('click', function() {
                modal.style.display = 'none'; // Ferme la modale lorsque le bouton de fermeture est cliqué
            });
        }
    }

    // Ferme la modale si l'utilisateur clique à l'extérieur
    window.addEventListener('click', function(event) { // Écoute les clics sur la fenêtre
        if (event.target === modal) { // Si le clic est sur le fond de la modale
            modal.style.display = 'none'; // Ferme la modale
        }
    });

    // === 7. Gestion des transactions ===
    // Ouvre la fenêtre modale pour ajouter une transaction
    if (openTransactionModalBtn && transactionModal) { // Vérifie l'existence du bouton et de la modale
        openTransactionModalBtn.addEventListener('click', function() { // Écoute le clic sur le bouton
            transactionModal.style.display = 'block'; // Affiche la modale
        });
    }

    // Gère la fermeture de la modale des transactions
    if (document.getElementById('transaction-modal')) {
        const transactionModal = document.getElementById('transaction-modal');
        const transactionCloseModal = transactionModal.querySelector('.close');
        if (transactionCloseModal) {
            transactionCloseModal.addEventListener('click', function() {
                transactionModal.style.display = 'none'; // Ferme la modale au clic sur le bouton de fermeture
            });
        }
    }

    // Ferme la modale des transactions si clic à l'extérieur
    if (transactionModal) { // Vérifie si la modale existe
        window.addEventListener('click', function(event) { // Écoute les clics
            if (event.target === transactionModal) { // Si le clic est sur le fond
                transactionModal.style.display = 'none'; // Ferme la modale
            }
        });
    }

    // Gère la soumission du formulaire pour ajouter une transaction
    if (addTransactionForm) { // Vérifie si le formulaire existe
        addTransactionForm.addEventListener('submit', function(event) { // Écoute la soumission
            event.preventDefault(); // Empêche le rechargement de la page
            const formData = new FormData(this); // Récupère les données saisies
            const accountId = new URLSearchParams(window.location.search).get('account_id'); // Récupère l'ID du compte depuis l'URL
            formData.append('account_id', accountId); // Ajoute l'ID du compte aux données

            fetch('/FolioVision/pages/api/add_transaction.php', { // Envoie les données via AJAX
                method: 'POST', // Utilise la méthode POST
                body: formData // Contient les données du formulaire
            })
                .then(response => response.json()) // Convertit la réponse en JSON
                .then(data => { // Traite la réponse
                    if (data.status === 'success') { // Si la transaction est ajoutée avec succès
                        alert('Transaction ajoutée avec succès !'); // Informe l'utilisateur
                        loadTransactions(); // Met à jour la liste des transactions
                        updateBalance(accountId); // Actualise le solde du compte
                        addTransactionForm.reset(); // Réinitialise le formulaire
                        if (transactionModal) transactionModal.style.display = 'none'; // Ferme la modale
                    } else { // En cas d'erreur
                        alert('Erreur : ' + data.message); // Affiche le message d'erreur
                    }
                })
                .catch(error => console.error('Erreur lors de l\'ajout de la transaction :', error)); // Journalise les erreurs
        });
    }

    // Met à jour l'affichage du solde d'un compte
    function updateBalance(accountId) { // Prend l'ID du compte comme paramètre
        fetch(`/FolioVision/pages/api/get_account_balance.php?account_id=${accountId}`) // Requête AJAX pour récupérer le solde
            .then(response => response.json()) // Convertit la réponse en JSONexpecting JSON
            .then(data => { // Traite les données
                if (data.status === 'success') { // Si le solde est récupéré avec succès
                    const balanceElement = document.getElementById('balance-display'); // Récupère l'élément d'affichage du solde
                    if (balanceElement) { // Vérifie son existence
                        balanceElement.innerHTML = `<strong>Solde :</strong> ${data.balance} ${data.currency}`; // Met à jour l'affichage avec le solde et la devise
                    } else { // Si l'élément n'est pas trouvé
                        console.error('Élément du solde non trouvé'); // Enregistre une erreur dans la console
                    }
                } else { // En cas d'échec
                    console.error('Erreur lors de la récupération du solde :', data.message); // Journalise l'erreur
                }
            })
            .catch(error => console.error('Erreur lors de la récupération du solde :', error)); // Journalise les erreurs réseau
    }

    // === 8. Gestion des préférences utilisateur (thèmes) ===
    // Gère la soumission du formulaire de personnalisation des thèmes
    if (presetForm) { // Vérifie si le formulaire existe
        presetForm.addEventListener('submit', function(event) { // Écoute la soumission
            event.preventDefault(); // Empêche le rechargement de la page
            const formData = new FormData(this); // Récupère les données saisies

            fetch('/FolioVision/pages/user/settings.php', { // Envoie les données via AJAX
                method: 'POST', // Utilise la méthode POST
                body: formData // Contient les données du formulaire
            })
                .then(response => response.json()) // Convertit la réponse en JSON
                .then(data => { // Traite la réponse
                    if (data.status === 'success') { // Si le thème est sauvegardé avec succès
                        // Gestion de l'affichage des thèmes sauvegardés
                        const preferencesContainer = document.querySelector('.preferences-container'); // Récupère le conteneur des préférences
                        const vosPresetsTitle = preferencesContainer.querySelector('h2'); // Récupère le titre "Vos Presets"

                        // Supprime le message "Aucun preset sauvegardé" s'il est présent
                        const noPresetMessage = preferencesContainer.querySelector('p'); // Cherche un message
                        if (noPresetMessage && noPresetMessage.textContent === 'Aucun preset sauvegardé pour le moment.') { // Vérifie son contenu
                            noPresetMessage.remove(); // Supprime le message
                        }

                        // Crée ou récupère la liste des thèmes sauvegardés
                        let presetList = document.querySelector('.preferences-container ul'); // Cherche une liste existante
                        if (!presetList) { // Si aucune liste n'existe
                            presetList = document.createElement('ul'); // Crée une nouvelle liste
                            vosPresetsTitle.insertAdjacentElement('afterend', presetList); // Insère la liste après le titre
                        }

                        // Ajoute le nouveau thème à la liste
                        const newPreset = document.createElement('li'); // Crée un nouvel élément de liste
                        // Définit le contenu HTML avec le nom, thème, couleur et options
                        newPreset.innerHTML = `
                            ${data.savename} - Thème : ${data.theme}, Couleur : ${data.text_color}
                            <a href="?apply=${data.pref_id}" class="btn btn-apply" data-theme="${data.theme}" data-text-color="${data.text_color}">Appliquer</a>
                            <a href="?delete=${data.pref_id}" class="btn btn-secondary" onclick="return confirm('Voulez-vous vraiment supprimer ce preset ?');">Supprimer</a>
                        `;
                        presetList.appendChild(newPreset); // Ajoute l'élément à la liste

                        // Réinitialise le formulaire après soumission
                        presetForm.reset(); // Vide les champs du formulaire

                        // Applique les styles du thème sauvegardé immédiatement
                        const theme = data.theme; // Récupère le thème sélectionné
                        const textColor = data.text_color; // Récupère la couleur sélectionnée

                        if (theme === 'light') { // Si le thème est clair
                            document.documentElement.style.setProperty('--background-color', '#ffffff'); // Définit le fond principal
                            document.documentElement.style.setProperty('--container-background', '#f5f5f5'); // Fond des conteneurs
                            document.documentElement.style.setProperty('--secondary-container-background', '#e0e0e0'); // Fond secondaire
                            document.documentElement.style.setProperty('--text-color', '#333333'); // Couleur du texte
                            document.documentElement.style.setProperty('--light-text-color', '#000000'); // Couleur du texte clair
                        } else { // Si le thème est sombre
                            document.documentElement.style.setProperty('--background-color', '#121212'); // Définit le fond principal
                            document.documentElement.style.setProperty('--container-background', '#1a1a1a'); // Fond des conteneurs
                            document.documentElement.style.setProperty('--secondary-container-background', '#242424'); // Fond secondaire
                            document.documentElement.style.setProperty('--text-color', '#e0e0e0'); // Couleur du texte
                            document.documentElement.style.setProperty('--light-text-color', '#ffffff'); // Couleur du texte clair
                        }
                        document.documentElement.style.setProperty('--accent-color', textColor); // Applique la couleur d'accentuation

                        // Synchronise le thème avec la session utilisateur
                        fetch(`?apply=${data.pref_id}`, { method: 'GET' }) // Requête GET pour mettre à jour la session
                            .then(response => console.log('Session mise à jour')) // Confirme la mise à jour dans la console
                            .catch(error => console.error('Erreur AJAX :', error)); // Journalise les erreurs

                        // Met à jour l'aperçu de la couleur
                        updateColorPreview(); // Applique la couleur à l'élément d'aperçu

                        alert('Preset sauvegardé et appliqué avec succès !'); // Informe l'utilisateur
                    } else { // En cas d'erreur
                        alert('Erreur : ' + data.message); // Affiche le message d'erreur
                    }
                })
                .catch(error => console.error('Erreur lors de la sauvegarde :', error)); // Journalise les erreurs
        });
    }

    // Gère le bouton "Appliquer" pour tester un thème sans le sauvegarder
    if (applyButton) { // Vérifie si le bouton existe
        applyButton.addEventListener('click', function() { // Écoute le clic sur le bouton
            const theme = document.getElementById('theme').value; // Récupère le thème choisi
            const textColor = document.getElementById('text_color').value; // Récupère la couleur choisie

            // Applique les styles immédiatement sans sauvegarde
            if (theme === 'light') { // Si le thème est clair
                document.documentElement.style.setProperty('--background-color', '#ffffff'); // Fond principal
                document.documentElement.style.setProperty('--container-background', '#f5f5f5'); // Fond des conteneurs
                document.documentElement.style.setProperty('--secondary-container-background', '#e0e0e0'); // Fond secondaire
                document.documentElement.style.setProperty('--text-color', '#333333'); // Couleur du texte
                document.documentElement.style.setProperty('--light-text-color', '#000000'); // Couleur du texte clair
            } else { // Si le thème est sombre
                document.documentElement.style.setProperty('--background-color', '#121212'); // Fond principal
                document.documentElement.style.setProperty('--container-background', '#1a1a1a'); // Fond des conteneurs
                document.documentElement.style.setProperty('--secondary-container-background', '#242424'); // Fond secondaire
                document.documentElement.style.setProperty('--text-color', '#e0e0e0'); // Couleur du texte
                document.documentElement.style.setProperty('--light-text-color', '#ffffff'); // Couleur du texte clair
            }
            document.documentElement.style.setProperty('--accent-color', textColor); // Applique la couleur d'accentuation
        });
    }

    // Fonction pour assombrir une couleur (utilisée pour les effets de survol)
    function darkenColor(color, percent) { // Prend une couleur hexadécimale et un pourcentage d'assombrissement
        let num = parseInt(color.replace("#", ""), 16), // Convertit la couleur hexadécimale en nombre
            amt = Math.round(2.55 * percent), // Calcule la quantité à réduire
            R = (num >> 16) - amt, // Extrait et ajuste la composante rouge
            G = (num >> 8 & 0x00FF) - amt, // Extrait et ajuste la composante verte
            B = (num & 0x0000FF) - amt; // Extrait et ajuste la composante bleue
        return "#" + ( // Reconstruit la couleur hexadécimale
            0x1000000 +
            (R < 255 ? (R < 1 ? 0 : R) : 255) * 0x10000 + // Limite la composante rouge entre 0 et 255
            (G < 255 ? (G < 1 ? 0 : G) : 255) * 0x100 + // Limite la composante verte
            (B < 255 ? (B < 1 ? 0 : B) : 255) // Limite la composante bleue
        ).toString(16).slice(1); // Convertit en hexadécimal et supprime le préfixe "1"
    }

    // Gère les boutons "Appliquer" pour les thèmes existants
    document.querySelectorAll('.btn-apply').forEach(button => { // Parcourt tous les boutons "Appliquer"
        button.addEventListener('click', function(event) { // Écoute le clic sur chaque bouton
            event.preventDefault(); // Empêche le rechargement de la page
            const theme = this.getAttribute('data-theme'); // Récupère le thème depuis l'attribut
            const textColor = this.getAttribute('data-text-color'); // Récupère la couleur depuis l'attribut
            const prefId = this.getAttribute('href').split('=')[1]; // Extrait l'ID du thème depuis le lien

            // Applique les styles selon le thème sélectionné
            if (theme === 'light') { // Si le thème est clair
                document.documentElement.style.setProperty('--background-color', '#ffffff');
                document.documentElement.style.setProperty('--container-background', '#f5f5f5');
                document.documentElement.style.setProperty('--secondary-container-background', '#e0e0e0');
                document.documentElement.style.setProperty('--text-color', '#333333');
                document.documentElement.style.setProperty('--light-text-color', '#000000');
            } else { // Si le thème est sombre
                document.documentElement.style.setProperty('--background-color', '#121212');
                document.documentElement.style.setProperty('--container-background', '#1a1a1a');
                document.documentElement.style.setProperty('--secondary-container-background', '#242424');
                document.documentElement.style.setProperty('--text-color', '#e0e0e0');
                document.documentElement.style.setProperty('--light-text-color', '#ffffff');
            }
            document.documentElement.style.setProperty('--accent-color', textColor); // Applique la couleur d'accentuation
            const hoverColor = darkenColor(textColor, 20); // Calcule une version assombrie pour le survol
            document.documentElement.style.setProperty('--accent-hover-color', hoverColor); // Applique la couleur au survol

            // Met à jour la session avec le thème appliqué
            fetch(`?apply=${prefId}`, { method: 'GET' }) // Requête GET pour synchroniser la session
                .then(response => console.log('Session mise à jour')) // Confirme la mise à jour
                .catch(error => console.error('Erreur AJAX :', error)); // Journalise les erreurs
        });
    });

    // Met à jour l'aperçu de la couleur lors des changements dans l'input
    if (colorInput) { // Vérifie si l'élément d'entrée de couleur existe
        colorInput.addEventListener('input', updateColorPreview); // Met à jour l'aperçu à chaque modification
    }

    // === 9. Gestion du menu déroulant utilisateur ===
    if (username && dropdown) { // Vérifie l'existence du nom d'utilisateur et du menu déroulant
        username.addEventListener('click', function(event) { // Écoute le clic sur le nom d'utilisateur
            event.stopPropagation(); // Empêche la fermeture immédiate par l'écouteur global
            dropdown.classList.toggle('show'); // Affiche ou masque le menu déroulant
        });

        window.addEventListener('click', function() { // Écoute les clics sur la fenêtre
            if (dropdown.classList.contains('show')) { // Si le menu est visible
                dropdown.classList.remove('show'); // Masque le menu
            }
        });
    }

    window.addEventListener('click', function(event) { // Écoute les clics sur la fenêtre
        if (event.target === modal) { // Si le clic est sur le fond de la modale
            modal.style.display = 'none'; // Ferme la modale
        }
    });

    // === 10. Gestion du menu mobile ===
    function toggleMenu() { // Affiche ou masque le menu mobile
        const menu = document.getElementById('nav-menu'); // Récupère le menu de navigation
        if (menu) { // Vérifie son existence
            menu.classList.toggle('show'); // Alterne entre affichage et masquage
        }
    }

    const menuToggle = document.querySelector('.menu-toggle'); // Récupère le bouton de bascule du menu
    if (menuToggle) { // Vérifie son existence
        menuToggle.addEventListener('click', toggleMenu); // Écoute le clic pour basculer le menu
    }

    // === 11. Gestion de la barre latérale ===
    if (toggleButton && sidebar) { // Vérifie l'existence du bouton et de la barre latérale
        toggleButton.addEventListener('click', function(event) { // Écoute le clic sur le bouton
            event.stopPropagation(); // Empêche la propagation de l'événement au document
            sidebar.classList.toggle('show'); // Affiche ou masque la barre latérale
            if (content) { // Vérifie l'existence du contenu principal
                content.style.marginLeft = sidebar.classList.contains('show') ? '250px' : '0'; // Ajuste la marge du contenu
            }
        });

        // Ferme la barre latérale si l'utilisateur clique à l'extérieur
        document.addEventListener('click', function(event) { // Écoute les clics sur le document
            if (sidebar.classList.contains('show') && // Si la barre latérale est visible
                !sidebar.contains(event.target) && // Si le clic n'est pas dans la barre latérale
                !toggleButton.contains(event.target)) { // Ni sur le bouton
                sidebar.classList.remove('show'); // Masque la barre latérale
                if (content) { // Vérifie l'existence du contenu
                    content.style.marginLeft = '0'; // Réinitialise la marge du contenu
                }
            }
        });
    }

    // === 12. Gestion des comptes ===
    if (addAccountButton) { // Vérifie si le bouton d'ajout existe
        addAccountButton.addEventListener('click', function() { // Écoute le clic
            addAccountForm.style.display = 'block'; // Affiche le formulaire d'ajout de compte
        });
    }

    if (accountForm) { // Vérifie si le formulaire de compte existe
        accountForm.addEventListener('submit', function(event) { // Écoute la soumission
            event.preventDefault(); // Empêche le rechargement de la page
            const formData = new FormData(this); // Récupère les données saisies

            // Validation des champs côté client
            const accountName = formData.get('account_name'); // Récupère le nom du compte
            const accountType = formData.get('account_type'); // Récupère le type de compte
            const balance = formData.get('balance'); // Récupère le solde initial
            const currency = formData.get('currency'); // Récupère la devise

            if (!accountName || accountName.trim() === '') { // Vérifie si le nom est vide
                errorMessage.textContent = 'Le nom du compte est requis.'; // Affiche une erreur
                return; // Interrompt le traitement
            }
            if (!accountType) { // Vérifie si le type est sélectionné
                errorMessage.textContent = 'Le type de compte est requis.'; // Affiche une erreur
                return; // Interrompt le traitement
            }
            if (isNaN(balance)) { // Vérifie si le solde est un nombre valide
                errorMessage.textContent = 'Le solde doit être un nombre.'; // Affiche une erreur
                return; // Interrompt le traitement
            }
            if (!currency || currency.trim() === '') { // Vérifie si la devise est vide
                errorMessage.textContent = 'La devise est requise.'; // Affiche une erreur
                return; // Interrompt le traitement
            }

            // Envoie les données au serveur via AJAX
            fetch('/FolioVision/pages/api/add_account.php', { // Requête pour ajouter le compte
                method: 'POST', // Utilise la méthode POST
                body: formData // Contient les données du formulaire
            })
                .then(response => response.json()) // Convertit la réponse en JSON
                .then(data => { // Traite la réponse
                    if (data.status === 'success') { // Si le compte est ajouté avec succès
                        const newAccount = document.createElement('li'); // Crée un nouvel élément de liste
                        newAccount.textContent = `${data.account_name} - Solde : ${data.balance} ${data.currency}`; // Définit le texte avec les détails
                        const accountList = document.querySelector('.accounts-container ul') || document.createElement('ul'); // Récupère ou crée une liste
                        accountList.appendChild(newAccount); // Ajoute le compte à la liste
                        if (!document.querySelector('.accounts-container ul')) { // Si la liste n'existait pas
                            document.querySelector('.accounts-container').appendChild(accountList); // Ajoute la liste au conteneur
                        }
                        addAccountForm.style.display = 'none'; // Masque le formulaire
                        accountForm.reset(); // Réinitialise les champs
                        errorMessage.textContent = ''; // Efface les messages d'erreur
                        alert('Compte ajouté avec succès !'); // Informe l'utilisateur
                        location.reload(); // Recharge la page pour refléter les changements
                    } else { // En cas d'erreur
                        errorMessage.textContent = data.message; // Affiche le message d'erreur
                    }
                })
                .catch(error => console.error('Erreur lors de l\'ajout du compte :', error)); // Journalise les erreurs
        });

        // Gère le bouton "Annuler" du formulaire
        document.getElementById('cancel-add').addEventListener('click', function() { // Écoute le clic
            addAccountForm.style.display = 'none'; // Masque le formulaire
        });
    }

    // Gère les boutons "Modifier" pour les comptes existants
    document.querySelectorAll('.edit-account').forEach(button => { // Parcourt tous les boutons "Modifier"
        button.addEventListener('click', function() { // Écoute le clic sur chaque bouton
            const existingForm = document.getElementById('edit-account-form'); // Vérifie si un formulaire de modification existe déjà
            if (existingForm) { // S'il existe
                existingForm.parentElement.remove(); // Supprime le formulaire existant
            }

            const accountId = this.getAttribute('data-id'); // Récupère l'ID du compte à modifier
            fetch(`/FolioVision/pages/api/get_account.php?account_id=${accountId}`) // Requête pour récupérer les données du compte
                .then(response => response.json()) // Convertit la réponse en JSON
                .then(data => { // Traite les données
                    if (data.status === 'success') { // Si les données sont récupérées avec succès
                        const account = data.account; // Stocke les informations du compte
                        const formContainer = document.createElement('div'); // Crée un conteneur pour le formulaire
                        formContainer.className = 'account-form-container'; // Ajoute une classe au conteneur
                        // Définit le contenu HTML du formulaire avec les données actuelles
                        formContainer.innerHTML = `
                            <h2>Modifier le compte</h2>
                            <form id="edit-account-form">
                                <div class="form-group">
                                    <label for="edit_account_name">Nom du compte :</label>
                                    <input type="text" id="edit_account_name" name="account_name" value="${account.account_name}" required>
                                </div>
                                <div class="form-group">
                                    <label for="edit_account_type">Type de compte :</label>
                                    <select id="edit_account_type" name="account_type" required>
                                        <option value="bank" ${account.account_type === 'bank' ? 'selected' : ''}>Banque</option>
                                        <option value="cash" ${account.account_type === 'cash' ? 'selected' : ''}>Espèces</option>
                                        <option value="crypto" ${account.account_type === 'crypto' ? 'selected' : ''}>Crypto</option>
                                        <option value="investment" ${account.account_type === 'investment' ? 'selected' : ''}>Investissement</option>
                                        <option value="credit_card" ${account.account_type === 'credit_card' ? 'selected' : ''}>Carte de crédit</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="edit_bank_subtype">Sous-type :</label>
                                    <select id="edit_bank_subtype" name="bank_subtype">
                                        <option value="current" ${account.bank_subtype === 'current' ? 'selected' : ''}>Compte courant</option>
                                        <option value="savings" ${account.bank_subtype === 'savings' ? 'selected' : ''}>Compte d'épargne</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="edit_balance">Solde :</label>
                                    <input type="number" id="edit_balance" name="balance" step="0.01" value="${account.balance}" required>
                                </div>
                                <div class="form-group">
                                    <label for="edit_currency">Devise :</label>
                                    <input type="text" id="edit_currency" name="currency" value="${account.currency}" required>
                                </div>
                                <button type="submit" class="btn">Sauvegarder</button>
                                <button type="button" class="btn btn-secondary" id="cancel-edit">Annuler</button>
                            </form>
                        `;

                        // Insère le formulaire après l'élément du compte dans la liste
                        const accountItem = this.closest('li'); // Trouve l'élément <li> parent
                        accountItem.insertAdjacentElement('afterend', formContainer); // Ajoute le formulaire après

                        // Gère l'affichage du sous-type bancaire dans le formulaire
                        const editAccountTypeSelect = document.getElementById('edit_account_type'); // Menu du type de compte
                        const editBankSubtypeSelect = document.getElementById('edit_bank_subtype'); // Menu du sous-type
                        const editBankSubtypeLabel = document.querySelector('label[for="edit_bank_subtype"]'); // Étiquette du sous-type

                        function toggleEditBankSubtype() { // Affiche ou masque le sous-type selon le type de compte
                            if (editAccountTypeSelect.value === 'bank') { // Si le type est "banque"
                                editBankSubtypeSelect.disabled = false; // Active le menu du sous-type
                                editBankSubtypeSelect.style.display = 'block'; // Affiche le menu
                                editBankSubtypeLabel.style.display = 'block'; // Affiche l'étiquette
                            } else { // Pour les autres types
                                editBankSubtypeSelect.disabled = true; // Désactive le menu
                                editBankSubtypeSelect.style.display = 'none'; // Masque le menu
                                editBankSubtypeLabel.style.display = 'none'; // Masque l'étiquette
                                editBankSubtypeSelect.value = ''; // Réinitialise la sélection
                            }
                        }

                        toggleEditBankSubtype(); // Applique l'état initial du sous-type
                        editAccountTypeSelect.addEventListener('change', toggleEditBankSubtype); // Écoute les changements de type

                        // Gère la soumission du formulaire de modification
                        const form = document.getElementById('edit-account-form'); // Récupère le formulaire
                        form.addEventListener('submit', function(event) { // Écoute la soumission
                            event.preventDefault(); // Empêche le rechargement de la page
                            const formData = new FormData(this); // Récupère les données modifiées
                            formData.append('account_id', accountId); // Ajoute l'ID du compte

                            fetch('/FolioVision/pages/api/update_account.php', { // Envoie les données au serveur
                                method: 'POST', // Utilise la méthode POST
                                body: formData // Contient les données du formulaire
                            })
                                .then(response => response.json()) // Convertit la réponse en JSON
                                .then(data => { // Traite la réponse
                                    if (data.status === 'success') { // Si la mise à jour est réussie
                                        alert('Compte modifié avec succès !'); // Informe l'utilisateur
                                        location.reload(); // Recharge la page pour refléter les changements
                                    } else { // En cas d'erreur
                                        alert('Erreur : ' + data.message); // Affiche le message d'erreur
                                    }
                                })
                                .catch(error => console.error('Erreur lors de la modification :', error)); // Journalise les erreurs
                        });

                        // Gère le bouton "Annuler" du formulaire de modification
                        document.getElementById('cancel-edit').addEventListener('click', function() { // Écoute le clic
                            formContainer.remove(); // Supprime le formulaire de modification
                        });
                    } else { // En cas d'erreur lors de la récupération
                        alert('Erreur : ' + data.message); // Affiche le message d'erreur
                    }
                })
                .catch(error => console.error('Erreur lors de la récupération du compte :', error)); // Journalise les erreurs
        });
    });

    // Gère les boutons "Supprimer" pour les comptes
    document.querySelectorAll('.delete-account').forEach(button => { // Parcourt tous les boutons "Supprimer"
        button.addEventListener('click', function() { // Écoute le clic sur chaque bouton
            const accountId = this.getAttribute('data-id'); // Récupère l'ID du compte à supprimer
            if (confirm('Voulez-vous vraiment supprimer ce compte ?')) { // Demande une confirmation à l'utilisateur
                fetch('/FolioVision/pages/api/delete_account.php', { // Envoie la requête de suppression
                    method: 'POST', // Utilise la méthode POST
                    headers: { 'Content-Type': 'application/json' }, // Définit le type de contenu comme JSON
                    body: JSON.stringify({ account_id: accountId }) // Envoie l'ID du compte en JSON
                })
                    .then(response => response.json()) // Convertit la réponse en JSON
                    .then(data => { // Traite la réponse
                        if (data.status === 'success') { // Si la suppression est réussie
                            const accountItem = this.closest('li'); // Trouve l'élément <li> correspondant
                            if (accountItem) { // Vérifie son existence
                                accountItem.remove(); // Supprime l'élément de la liste
                            }
                            alert('Compte supprimé avec succès !'); // Informe l'utilisateur
                        } else { // En cas d'erreur
                            alert('Erreur : ' + data.message); // Affiche le message d'erreur
                        }
                    })
                    .catch(error => console.error('Erreur lors de la suppression :', error)); // Journalise les erreurs
            }
        });
    });

    // === 13. Gestion des transactions (liste et actions) ===
    // Charge la liste des transactions dynamiquement avec filtres optionnels
    function loadTransactions(startDate = '', endDate = '') { // Prend des dates de début et de fin comme paramètres optionnels
        const accountId = new URLSearchParams(window.location.search).get('account_id'); // Récupère l'ID du compte depuis l'URL
        if (!accountId || accountId === 'null') { // Vérifie si l'ID est valide
            console.log('Aucun account_id valide fourni. La fonction loadTransactions est ignorée.'); // Journalise un message
            return; // Interrompt le traitement
        }
        fetch(`/FolioVision/pages/api/get_transactions.php?account_id=${accountId}&start_date=${startDate}&end_date=${endDate}`) // Requête AJAX avec filtres
            .then(response => response.json()) // Convertit la réponse en JSON
            .then(data => { // Traite les données
                const transactionList = document.getElementById('transaction-list'); // Récupère la liste des transactions
                if (transactionList) { // Vérifie son existence
                    transactionList.innerHTML = ''; // Vide la liste actuelle
                    if (data.status === 'success') { // Si les transactions sont récupérées
                        data.transactions.forEach(transaction => { // Parcourt chaque transaction
                            const transactionItem = document.createElement('div'); // Crée un conteneur pour la transaction
                            transactionItem.className = 'transaction-item'; // Ajoute une classe au conteneur

                            // Construit le contenu HTML de la transaction
                            let html = `
                                <p><strong>Date :</strong> ${transaction.transaction_date}</p>
                                <p><strong>Montant :</strong> ${transaction.amount} ${transaction.currency}</p>
                                <p><strong>Type :</strong> ${transaction.type}</p>
                                <p><strong>Catégorie principale :</strong> ${transaction.category_name || 'N/A'}</p>
                            `;
                            if (transaction.sub_category_name && transaction.sub_category_name.trim() !== '') { // Ajoute la sous-catégorie si présente
                                html += `<p><strong>Sous-catégorie :</strong> ${transaction.sub_category_name}</p>`;
                            }
                            if (transaction.description && transaction.description.trim() !== '') { // Ajoute la description si présente
                                html += `<p><strong>Description :</strong> ${transaction.description}</p>`;
                            }
                            // Ajoute les options d'action pour la transaction
                            html += `
                                <div class="transaction-actions">
                                    <span class="settings-icon">⚙️</span>
                                    <div class="action-menu">
                                        <a href="#" onclick="editTransaction(${transaction.transaction_id}); return false;">Modifier l'opération</a>
                                        <a href="#" onclick="duplicateTransaction(${transaction.transaction_id}); return false;">Dupliquer l'opération</a>
                                        <a href="#" onclick="openMoveModal(${transaction.transaction_id}); return false;">Déplacer dans un compte</a>
                                        <a href="#" onclick="openCopyModal(${transaction.transaction_id}); return false;">Copier dans un compte</a>
                                        <a href="#" onclick="deleteTransaction(${transaction.transaction_id}); return false;">Supprimer l'opération</a>
                                    </div>
                                </div>
                            `;

                            transactionItem.innerHTML = html; // Applique le contenu HTML
                            transactionList.appendChild(transactionItem); // Ajoute l'élément à la liste
                        });
                    } else { // Si aucune transaction n'est trouvée
                        transactionList.innerHTML = '<p>Aucune transaction trouvée.</p>'; // Affiche un message
                    }
                }
            })
            .catch(error => console.error('Erreur lors de la récupération des transactions :', error)); // Journalise les erreurs
    }

    // Charge les transactions au démarrage si la liste existe
    if (document.getElementById('transaction-list')) { // Vérifie l'existence de la liste
        loadTransactions(); // Charge les transactions initialement
    }

    // Gère le filtre des transactions par date
    const transactionFilterForm = document.getElementById('transaction-filter-form'); // Récupère le formulaire de filtre
    if (transactionFilterForm) { // Vérifie son existence
        transactionFilterForm.addEventListener('submit', function(event) { // Écoute la soumission
            event.preventDefault(); // Empêche le rechargement de la page
            const startDate = document.getElementById('start_date').value; // Récupère la date de début
            const endDate = document.getElementById('end_date').value; // Récupère la date de fin
            loadTransactions(startDate, endDate); // Recharge les transactions avec les filtres
        });
    }

    // Ouvre la modale pour modifier une transaction
    window.editTransaction = function(transactionId) { // Définit une fonction globale pour modifier une transaction
        fetch(`/FolioVision/pages/api/get_transaction.php?transaction_id=${transactionId}`) // Requête pour récupérer les détails
            .then(response => response.json()) // Convertit la réponse en JSON
            .then(data => { // Traite les données
                if (data.status === 'success') { // Si la transaction est récupérée
                    const transaction = data.transaction; // Stocke les données de la transaction
                    // Remplit les champs de la modale avec les données actuelles
                    document.getElementById('edit_transaction_id').value = transaction.transaction_id; // ID de la transaction
                    document.getElementById('edit_transaction_date').value = transaction.transaction_date; // Date
                    document.getElementById('edit_payment_method').value = transaction.payment_method; // Moyen de paiement
                    document.getElementById('edit_amount').value = transaction.amount; // Montant
                    document.getElementById('edit_type').value = transaction.type; // Type
                    document.getElementById('edit_description').value = transaction.description || ''; // Description (optionnelle)

                    const mainCategorySelect = document.getElementById('edit_main_category_id'); // Menu des catégories principales
                    mainCategorySelect.value = transaction.parent_category_id || (transaction.sub_category_name === null ? transaction.category_id : ''); // Sélectionne la catégorie principale

                    loadSubCategoriesForEdit(mainCategorySelect.value, transaction.category_id); // Charge les sous-catégories associées

                    document.getElementById('edit-transaction-modal').style.display = 'block'; // Affiche la modale de modification
                } else { // En cas d'erreur
                    alert('Erreur lors de la récupération de la transaction : ' + data.message); // Affiche le message d'erreur
                }
            })
            .catch(error => console.error('Erreur lors de la récupération de la transaction :', error)); // Journalise les erreurs
    }

    // Charge les sous-catégories dans la modale de modification lors d'un changement de catégorie principale
    const editMainCategorySelect = document.getElementById('edit_main_category_id'); // Récupère le menu
    if (editMainCategorySelect) { // Vérifie son existence
        editMainCategorySelect.addEventListener('change', function() { // Écoute les changements
            const mainCategoryId = this.value; // Récupère l'ID de la catégorie principale
            loadSubCategoriesForEdit(mainCategoryId, null); // Charge les sous-catégories sans présélection
        });
    }

    // Ferme la modale de modification
    const closeEditModalBtn = document.getElementById('close-edit-transaction-modal'); // Récupère le bouton de fermeture
    if (closeEditModalBtn) { // Vérifie son existence
        closeEditModalBtn.addEventListener('click', function() { // Écoute le clic
            document.getElementById('edit-transaction-modal').style.display = 'none'; // Masque la modale
        });
    }

    // Ferme la modale de modification si clic à l'extérieur
    window.addEventListener('click', function(event) { // Écoute les clics sur la fenêtre
        const editModal = document.getElementById('edit-transaction-modal'); // Récupère la modale
        if (editModal && event.target === editModal) { // Si le clic est sur le fond
            editModal.style.display = 'none'; // Ferme la modale
        }
    });

    // Gère la soumission du formulaire de modification de transaction
    const editTransactionForm = document.getElementById('edit-transaction-form'); // Récupère le formulaire
    if (editTransactionForm) { // Vérifie son existence
        editTransactionForm.addEventListener('submit', function(event) { // Écoute la soumission
            event.preventDefault(); // Empêche le rechargement de la page
            const formData = new FormData(this); // Récupère les données modifiées
            const accountId = new URLSearchParams(window.location.search).get('account_id'); // Récupère l'ID du compte

            fetch('/FolioVision/pages/api/update_transaction.php', { // Envoie les données au serveur
                method: 'POST', // Utilise la méthode POST
                body: formData // Contient les données du formulaire
            })
                .then(response => response.json()) // Convertit la réponse en JSON
                .then(data => { // Traite la réponse
                    if (data.status === 'success') { // Si la mise à jour est réussie
                        alert('Transaction modifiée avec succès !'); // Informe l'utilisateur
                        loadTransactions(); // Recharge la liste des transactions
                        updateBalance(accountId); // Met à jour le solde du compte
                        editTransactionForm.reset(); // Réinitialise le formulaire
                        document.getElementById('edit-transaction-modal').style.display = 'none'; // Ferme la modale
                    } else { // En cas d'erreur
                        alert('Erreur : ' + data.message); // Affiche le message d'erreur
                    }
                })
                .catch(error => console.error('Erreur lors de la modification :', error)); // Journalise les erreurs
        });
    }
    // === 14. Gestion des actions sur les transactions (copie, déplacement) ===
// Fonction qui charge les comptes dans une modale avec une option de recherche
    function loadAccountsForModal(modalId, excludeAccountId = null) {
        // Récupère l'élément HTML où la liste des comptes sera affichée
        const accountList = document.getElementById(modalId + '-account-list');
        // Récupère le champ de recherche associé à la modale
        const searchInput = document.getElementById(modalId + '-account-search');

        // Effectue une requête pour obtenir la liste des comptes depuis l'API
        fetch('/FolioVision/pages/api/get_accounts.php')
            .then(response => response.json()) // Transforme la réponse en objet JSON
            .then(data => {
                if (data.status === 'success') { // Vérifie que la requête a réussi
                    // Filtre les comptes pour exclure celui avec l'ID spécifié (si fourni)
                    const accounts = data.accounts.filter(account => !excludeAccountId || account.account_id != excludeAccountId);
                    // Affiche les comptes dans la liste
                    renderAccounts(accounts, accountList);

                    // Ajoute un écouteur d'événements pour filtrer les comptes lors de la saisie
                    searchInput.addEventListener('input', function() {
                        // Récupère la valeur saisie, convertie en minuscules
                        const searchTerm = this.value.toLowerCase();
                        // Filtre les comptes en fonction du terme de recherche
                        const filteredAccounts = accounts.filter(account =>
                            account.account_name.toLowerCase().includes(searchTerm)
                        );
                        // Met à jour la liste avec les comptes filtrés
                        renderAccounts(filteredAccounts, accountList);
                    });
                }
            })
            .catch(error => console.error('Erreur lors du chargement des comptes :', error)); // Affiche les erreurs dans la console
    }

// Fonction qui affiche les comptes sous forme de liste avec des cases à cocher
    function renderAccounts(accounts, accountList) {
        // Réinitialise le contenu de la liste
        accountList.innerHTML = '';
        // Parcourt chaque compte pour créer les éléments HTML
        accounts.forEach(account => {
            // Crée une case à cocher pour le compte
            const checkbox = document.createElement('input');
            checkbox.type = 'checkbox'; // Définit le type comme case à cocher
            checkbox.value = account.account_id; // Utilise l'ID du compte comme valeur
            checkbox.id = `account-${account.account_id}`; // Attribue un ID unique
            // Crée une étiquette associée à la case à cocher
            const label = document.createElement('label');
            label.htmlFor = checkbox.id; // Lie l'étiquette à la case via son ID
            label.textContent = account.account_name; // Affiche le nom du compte
            // Crée un conteneur pour regrouper la case et l'étiquette
            const div = document.createElement('div');
            div.appendChild(checkbox); // Ajoute la case au conteneur
            div.appendChild(label); // Ajoute l'étiquette au conteneur
            // Ajoute le conteneur à la liste des comptes
            accountList.appendChild(div);
        });
    }

// Fonction pour ouvrir la modale permettant de copier une transaction
    window.openCopyModal = function(transactionId) {
        // Récupère l'ID du compte actuel depuis les paramètres de l'URL
        const accountId = new URLSearchParams(window.location.search).get('account_id');
        // Définit l'ID de la transaction à copier dans un champ caché
        document.getElementById('copy_transaction_id').value = transactionId;
        // Charge les comptes dans la modale, en excluant le compte actuel
        loadAccountsForModal('copy', accountId);
        // Affiche la modale de copie
        document.getElementById('copy-transaction-modal').style.display = 'block';
    }

// Fonction pour ouvrir la modale permettant de déplacer une transaction
    window.openMoveModal = function(transactionId) {
        // Récupère l'ID du compte actuel depuis les paramètres de l'URL
        const accountId = new URLSearchParams(window.location.search).get('account_id');
        // Définit l'ID de la transaction à déplacer dans un champ caché
        document.getElementById('move_transaction_id').value = transactionId;
        // Charge les comptes dans la modale, en excluant le compte actuel
        loadAccountsForModal('move', accountId);
        // Affiche la modale de déplacement
        document.getElementById('move-transaction-modal').style.display = 'block';
    }

// Fonction pour dupliquer une transaction dans le même compte
    window.duplicateTransaction = function(transactionId) {
        // Envoie une requête POST à l'API pour dupliquer la transaction
        fetch('/FolioVision/pages/api/duplicate_transaction.php', {
            method: 'POST', // Utilise la méthode POST pour envoyer des données
            headers: { 'Content-Type': 'application/json' }, // Indique que les données sont en JSON
            body: JSON.stringify({ transaction_id: transactionId }) // Convertit l'ID en JSON
        })
            .then(response => response.json()) // Transforme la réponse en objet JSON
            .then(data => {
                if (data.status === 'success') { // Si la duplication est réussie
                    alert('Transaction dupliquée avec succès'); // Affiche une confirmation
                    loadTransactions(); // Recharge la liste des transactions
                    // Récupère l'ID du compte actuel depuis l'URL
                    const accountId = new URLSearchParams(window.location.search).get('account_id');
                    updateBalance(accountId); // Met à jour le solde du compte
                } else { // Si une erreur survient
                    alert('Erreur : ' + data.message); // Affiche le message d'erreur
                }
            })
            .catch(error => { // En cas d'erreur réseau ou autre
                console.error('Erreur lors de la duplication :', error); // Affiche l'erreur dans la console
                alert('Une erreur est survenue lors de la duplication'); // Notifie l'utilisateur
            });
    }

// Fonction pour supprimer une transaction après confirmation de l'utilisateur
    window.deleteTransaction = function(transactionId) {
        // Demande à l'utilisateur de confirmer la suppression
        if (confirm('Voulez-vous vraiment supprimer cette transaction ?')) {
            // Envoie une requête POST à l'API pour supprimer la transaction
            fetch('/FolioVision/pages/api/delete_transaction.php', {
                method: 'POST', // Utilise la méthode POST
                headers: { 'Content-Type': 'application/json' }, // Indique le format JSON
                body: JSON.stringify({ transaction_id: transactionId }) // Envoie l'ID en JSON
            })
                .then(response => response.json()) // Transforme la réponse en JSON
                .then(data => {
                    if (data.status === 'success') { // Si la suppression est réussie
                        alert('Transaction supprimée avec succès'); // Affiche une confirmation
                        loadTransactions(); // Recharge la liste des transactions
                        // Récupère l'ID du compte actuel depuis l'URL
                        const accountId = new URLSearchParams(window.location.search).get('account_id');
                        updateBalance(accountId); // Met à jour le solde du compte
                    } else { // Si une erreur survient
                        alert('Erreur : ' + data.message); // Affiche le message d'erreur
                    }
                })
                .catch(error => { // En cas d'erreur réseau ou autre
                    console.error('Erreur lors de la suppression :', error); // Affiche l'erreur dans la console
                    alert('Une erreur est survenue lors de la suppression'); // Notifie l'utilisateur
                });
        }
    }

// Gestion de la soumission du formulaire pour copier une transaction
    const copyTransactionForm = document.getElementById('copy-transaction-form');
    if (copyTransactionForm) { // Vérifie si le formulaire existe dans le DOM
        copyTransactionForm.addEventListener('submit', function(event) {
            event.preventDefault(); // Empêche le rechargement de la page
            // Récupère l'ID de la transaction à copier
            const transactionId = document.getElementById('copy_transaction_id').value;
            // Récupère la liste des comptes sélectionnés (cases cochées)
            const selectedAccounts = Array.from(document.querySelectorAll('#copy-account-list input:checked')).map(input => input.value);

            // Envoie une requête POST à l'API pour copier la transaction
            fetch('/FolioVision/pages/api/copy_transaction.php', {
                method: 'POST', // Utilise la méthode POST
                headers: { 'Content-Type': 'application/json' }, // Indique le format JSON
                body: JSON.stringify({ transaction_id: transactionId, target_account_ids: selectedAccounts }) // Envoie les données en JSON
            })
                .then(response => response.json()) // Transforme la réponse en JSON
                .then(data => {
                    if (data.status === 'success') { // Si la copie est réussie
                        alert('Transaction copiée avec succès'); // Affiche une confirmation
                        document.getElementById('copy-transaction-modal').style.display = 'none'; // Ferme la modale
                    } else { // Si une erreur survient
                        alert('Erreur : ' + data.message); // Affiche le message d'erreur
                    }
                })
                .catch(error => console.error('Erreur lors de la copie :', error)); // Affiche les erreurs dans la console
        });
    }

// Gestion de la soumission du formulaire pour déplacer une transaction
    const moveTransactionForm = document.getElementById('move-transaction-form');
    if (moveTransactionForm) { // Vérifie si le formulaire existe dans le DOM
        moveTransactionForm.addEventListener('submit', function(event) {
            event.preventDefault(); // Empêche le rechargement de la page
            // Récupère l'ID de la transaction à déplacer
            const transactionId = document.getElementById('move_transaction_id').value;
            // Récupère la liste des comptes sélectionnés (cases cochées)
            const selectedAccounts = Array.from(document.querySelectorAll('#move-account-list input:checked')).map(input => input.value);

            // Envoie une requête POST à l'API pour déplacer la transaction
            fetch('/FolioVision/pages/api/move_transaction.php', {
                method: 'POST', // Utilise la méthode POST
                headers: { 'Content-Type': 'application/json' }, // Indique le format JSON
                body: JSON.stringify({ transaction_id: transactionId, target_account_ids: selectedAccounts }) // Envoie les données en JSON
            })
                .then(response => response.json()) // Transforme la réponse en JSON
                .then(data => {
                    if (data.status === 'success') { // Si le déplacement est réussi
                        alert('Transaction déplacée avec succès'); // Affiche une confirmation
                        loadTransactions(); // Recharge la liste des transactions
                        // Récupère l'ID du compte actuel depuis l'URL
                        const accountId = new URLSearchParams(window.location.search).get('account_id');
                        updateBalance(accountId); // Met à jour le solde du compte
                        document.getElementById('move-transaction-modal').style.display = 'none'; // Ferme la modale
                    } else { // Si une erreur survient
                        alert('Erreur : ' + data.message); // Affiche le message d'erreur
                    }
                })
                .catch(error => console.error('Erreur lors du déplacement :', error)); // Affiche les erreurs dans la console
        });
    }

// Gestion de la fermeture de la modale de copie via le bouton dédié
    const closeCopyModal = document.getElementById('close-copy-modal');
    if (closeCopyModal) { // Vérifie si le bouton existe dans le DOM
        closeCopyModal.addEventListener('click', function() {
            const copyTransactionModal = document.getElementById('copy-transaction-modal');
            if (copyTransactionModal) { // Vérifie si la modale existe
                copyTransactionModal.style.display = 'none'; // Masque la modale
            }
        });
    }

// Gestion de la fermeture de la modale de déplacement via le bouton dédié
    const closeMoveModal = document.getElementById('close-move-modal');
    if (closeMoveModal) { // Vérifie si le bouton existe dans le DOM
        closeMoveModal.addEventListener('click', function() {
            const moveTransactionModal = document.getElementById('move-transaction-modal');
            if (moveTransactionModal) { // Vérifie si la modale existe
                moveTransactionModal.style.display = 'none'; // Masque la modale
            }
        });
    }

// Gestion de la fermeture des modales en cliquant à l'extérieur
    window.addEventListener('click', function(event) {
        // Si l'utilisateur clique sur le fond de la modale de copie
        if (event.target === document.getElementById('copy-transaction-modal')) {
            document.getElementById('copy-transaction-modal').style.display = 'none'; // Ferme la modale
        }
        // Si l'utilisateur clique sur le fond de la modale de déplacement
        if (event.target === document.getElementById('move-transaction-modal')) {
            document.getElementById('move-transaction-modal').style.display = 'none'; // Ferme la modale
        }
    });

// === 15. Gestion des budgets ===
// Charge les sous-catégories dynamiquement en fonction de la catégorie principale sélectionnée
    const budgetMainCategorySelect = document.getElementById('main_category_id');
    const budgetSubCategorySelect = document.getElementById('sub_category_id');
    if (budgetMainCategorySelect && budgetSubCategorySelect) { // Vérifie si les deux éléments existent
        budgetMainCategorySelect.addEventListener('change', function() {
            // Récupère l'ID de la catégorie principale sélectionnée
            const mainCategoryId = this.value;
            // Réinitialise la liste des sous-catégories avec une option par défaut
            budgetSubCategorySelect.innerHTML = '<option value="">-- Sélectionner une sous-catégorie --</option>';
            if (mainCategoryId) { // Si une catégorie principale est sélectionnée
                // Envoie une requête pour récupérer les sous-catégories associées
                fetch(`/FolioVision/pages/api/get_subcategories.php?main_category_id=${mainCategoryId}`)
                    .then(response => response.json()) // Transforme la réponse en JSON
                    .then(data => {
                        if (data.status === 'success') { // Si la requête réussit
                            // Parcourt chaque sous-catégorie pour l'ajouter à la liste
                            data.subcategories.forEach(subcategory => {
                                const option = document.createElement('option'); // Crée une nouvelle option
                                option.value = subcategory.category_id; // Définit l'ID de la sous-catégorie
                                option.textContent = subcategory.name; // Définit le nom de la sous-catégorie
                                budgetSubCategorySelect.appendChild(option); // Ajoute l'option au menu déroulant
                            });
                        }
                    })
                    .catch(error => console.error('Erreur lors de la récupération des sous-catégories :', error)); // Affiche les erreurs dans la console
            }
        });
    }

// Gestion de la soumission du formulaire pour ajouter un nouveau budget
    const addBudgetForm = document.getElementById('add-budget-form');
    if (addBudgetForm) { // Vérifie si le formulaire existe dans le DOM
        addBudgetForm.addEventListener('submit', function(event) {
            event.preventDefault(); // Empêche le rechargement de la page
            // Récupère toutes les données du formulaire
            const formData = new FormData(this);

            // Envoie une requête POST à l'API pour ajouter le budget
            fetch('/FolioVision/pages/api/add_budget.php', {
                method: 'POST', // Utilise la méthode POST
                body: formData // Envoie les données du formulaire
            })
                .then(response => response.json()) // Transforme la réponse en JSON
                .then(data => {
                    if (data.status === 'success') { // Si l'ajout est réussi
                        alert('Budget créé avec succès !'); // Affiche une confirmation
                        loadBudgets(); // Recharge la liste des budgets
                        addBudgetForm.reset(); // Réinitialise le formulaire
                        document.getElementById('add-budget-form-container').style.display = 'none'; // Ferme le conteneur du formulaire
                    } else { // Si une erreur survient
                        alert('Erreur : ' + data.message); // Affiche le message d'erreur
                    }
                })
                .catch(error => console.error('Erreur lors de la création du budget :', error)); // Affiche les erreurs dans la console
        });
    }

// Fonction pour initialiser les interactions spécifiques à la page des budgets
    function initializeBudgetPage() {
        const openAddBudgetBtn = document.getElementById('open-add-budget'); // Bouton pour ouvrir le formulaire
        const addBudgetFormContainer = document.getElementById('add-budget-form-container'); // Conteneur du formulaire
        const cancelAddBudgetBtn = document.getElementById('cancel-add-budget'); // Bouton pour annuler

        if (openAddBudgetBtn && addBudgetFormContainer) { // Vérifie si les éléments existent
            openAddBudgetBtn.addEventListener('click', function() {
                addBudgetFormContainer.style.display = 'block'; // Affiche le conteneur du formulaire
            });
        }

        if (cancelAddBudgetBtn && addBudgetFormContainer) { // Vérifie si les éléments existent
            cancelAddBudgetBtn.addEventListener('click', function() {
                addBudgetFormContainer.style.display = 'none'; // Masque le conteneur du formulaire
            });
        }
    }

// Initialise la page des budgets si la classe "budget-page" est présente sur le body
    if (document.body.classList.contains('budget-page')) {
        initializeBudgetPage(); // Appelle la fonction d'initialisation
    }

// Fonction pour charger et afficher la liste des budgets
    function loadBudgets() {
        // Envoie une requête pour récupérer la liste des budgets depuis l'API
        fetch('/FolioVision/pages/api/get_budgets.php')
            .then(response => response.json()) // Transforme la réponse en JSON
            .then(data => {
                const budgetList = document.getElementById('budget-list');
                if (budgetList) { // Vérifie si la liste existe dans le DOM
                    budgetList.innerHTML = ''; // Réinitialise la liste
                    // Détermine si la page actuelle est le tableau de bord
                    const isDashboard = window.location.pathname.includes('dashboard.php');
                    if (data.status === 'success' && Array.isArray(data.budgets)) { // Si les budgets sont récupérés
                        data.budgets.forEach(budget => { // Parcourt chaque budget
                            // Calcule le pourcentage d'utilisation du budget
                            const percentage = (budget.used_amount / budget.budget_amount) * 100;
                            // Prépare l'affichage de la catégorie (avec sous-catégorie si présente)
                            const categoryDisplay = budget.sub_category_name ? `${budget.main_category_name} > ${budget.sub_category_name}` : budget.main_category_name;
                            // Crée un élément pour afficher le budget
                            const budgetItem = document.createElement('div');
                            budgetItem.className = 'budget-item'; // Ajoute une classe CSS
                            let html = `
                            <p><strong>Catégorie :</strong> ${categoryDisplay}</p>
                            <p><strong>Montant alloué :</strong> ${budget.budget_amount} €</p>
                            <p><strong>Dépensé :</strong> ${budget.used_amount} €</p>
                            <div class="progress-container">
                                <progress value="${percentage}" max="100"></progress>
                                <span class="progress-percentage">${percentage.toFixed(2)}%</span>
                            </div>
                        `;
                            // Ajoute les options de modification/suppression si ce n'est pas le tableau de bord
                            if (!isDashboard) {
                                html += `
                                <div class="budget-actions">
                                    <span class="settings-icon">⚙️</span>
                                    <div class="action-menu">
                                        <a href="#" onclick="editBudget(${budget.budget_id}); return false;">Modifier</a>
                                        <a href="#" onclick="deleteBudget(${budget.budget_id}); return false;">Supprimer</a>
                                    </div>
                                </div>
                            `;
                            }
                            budgetItem.innerHTML = html; // Applique le contenu HTML
                            budgetList.appendChild(budgetItem); // Ajoute l'élément à la liste
                        });
                    } else if (data.status === 'error') { // Si une erreur est renvoyée par l'API
                        budgetList.innerHTML = `<p>Erreur : ${data.message}</p>`; // Affiche le message d'erreur
                    } else { // Si aucun budget n'est trouvé
                        budgetList.innerHTML = '<p>Aucun budget trouvé.</p>'; // Affiche un message par défaut
                    }
                }
            })
            .catch(error => console.error('Erreur lors de la récupération des budgets :', error)); // Affiche les erreurs dans la console
    }

// Charge automatiquement les budgets si l'élément "budget-list" existe
    if (document.getElementById('budget-list')) {
        loadBudgets();
    }

// Fonction pour ouvrir la modale de modification d'un budget
    window.editBudget = function(budgetId) {
        // Envoie une requête pour récupérer les détails du budget spécifié
        fetch(`/FolioVision/pages/api/get_budget.php?budget_id=${budgetId}`)
            .then(response => response.json()) // Transforme la réponse en JSON
            .then(data => {
                if (data.status === 'success') { // Si la récupération réussit
                    const budget = data.budget; // Stocke les données du budget
                    // Remplit les champs de la modale avec les informations du budget
                    document.getElementById('edit_budget_id').value = budget.budget_id;
                    document.getElementById('edit_budget_amount').value = budget.budget_amount;
                    document.getElementById('edit_period').value = budget.period;
                    document.getElementById('edit_start_month').value = budget.start_date ? budget.start_date.substring(0, 7) : '';
                    document.getElementById('edit_end_month').value = budget.end_date ? budget.end_date.substring(0, 7) : '';
                    document.getElementById('edit_carry_over_under').checked = budget.carry_over_under == 1;
                    document.getElementById('edit_carry_over_over').checked = budget.carry_over_over == 1;

                    // Charge les catégories principales et sous-catégories dans la modale
                    loadMainCategoriesForEdit(budget.main_category_id, budget.sub_category_id);

                    // Charge les comptes associés au budget
                    loadAccountsForBudgetEdit(budget.budget_id);

                    // Affiche la modale de modification
                    document.getElementById('edit-budget-modal').style.display = 'block';
                } else { // Si une erreur survient
                    alert('Erreur lors de la récupération du budget : ' + data.message); // Affiche le message d'erreur
                }
            })
            .catch(error => console.error('Erreur lors de la récupération du budget :', error)); // Affiche les erreurs dans la console
    };

// Gestion de la soumission du formulaire de modification d'un budget
    const editBudgetForm = document.getElementById('edit-budget-form');
    if (editBudgetForm) { // Vérifie si le formulaire existe dans le DOM
        editBudgetForm.addEventListener('submit', function(event) {
            event.preventDefault(); // Empêche le rechargement de la page
            const formData = new FormData(this); // Récupère les données du formulaire

            // Envoie une requête POST à l'API pour mettre à jour le budget
            fetch('/FolioVision/pages/api/update_budget.php', {
                method: 'POST', // Utilise la méthode POST
                body: formData // Envoie les données du formulaire
            })
                .then(response => response.json()) // Transforme la réponse en JSON
                .then(data => {
                    if (data.status === 'success') { // Si la mise à jour réussit
                        alert('Budget mis à jour avec succès'); // Affiche une confirmation
                        loadBudgets(); // Recharge la liste des budgets
                        document.getElementById('edit-budget-modal').style.display = 'none'; // Ferme la modale
                    } else { // Si une erreur survient
                        alert('Erreur : ' + data.message); // Affiche le message d'erreur
                    }
                })
                .catch(error => console.error('Erreur lors de la mise à jour du budget :', error)); // Affiche les erreurs dans la console
        });
    }

// Fonction pour charger les catégories principales dans la modale de modification
    function loadMainCategoriesForEdit(selectedCategoryId, selectedSubCategoryId) {
        // Envoie une requête pour récupérer les catégories principales
        fetch('/FolioVision/pages/api/get_main_categories.php')
            .then(response => response.json()) // Transforme la réponse en JSON
            .then(data => {
                const mainCategorySelect = document.getElementById('edit_main_category_id');
                // Réinitialise le menu déroulant avec une option par défaut
                mainCategorySelect.innerHTML = '<option value="">-- Sélectionner une catégorie principale --</option>';
                if (data.status === 'success') { // Si la récupération réussit
                    data.main_categories.forEach(category => { // Parcourt chaque catégorie
                        const option = document.createElement('option'); // Crée une nouvelle option
                        option.value = category.category_id; // Définit l'ID de la catégorie
                        option.textContent = category.name; // Définit le nom de la catégorie
                        if (category.category_id == selectedCategoryId) { // Si c'est la catégorie sélectionnée
                            option.selected = true; // Pré-sélectionne cette option
                        }
                        mainCategorySelect.appendChild(option); // Ajoute l'option au menu
                    });
                    // Charge les sous-catégories si une catégorie principale est sélectionnée
                    if (selectedCategoryId) {
                        loadSubCategoriesForEdit(selectedCategoryId, selectedSubCategoryId);
                    }
                }
            })
            .catch(error => console.error('Erreur lors de la récupération des catégories principales :', error)); // Affiche les erreurs dans la console
    }

// Fonction pour charger les sous-catégories dans la modale de modification
    function loadSubCategoriesForEdit(mainCategoryId, selectedSubCategoryId) {
        const subCategorySelect = document.getElementById('edit_sub_category_id');
        // Réinitialise le menu déroulant avec une option par défaut
        subCategorySelect.innerHTML = '<option value="">-- Sélectionner une sous-catégorie --</option>';
        if (mainCategoryId) { // Si une catégorie principale est spécifiée
            // Envoie une requête pour récupérer les sous-catégories associées
            fetch(`/FolioVision/pages/api/get_subcategories.php?main_category_id=${mainCategoryId}`)
                .then(response => response.json()) // Transforme la réponse en JSON
                .then(data => {
                    if (data.status === 'success') { // Si la récupération réussit
                        data.subcategories.forEach(subcategory => { // Parcourt chaque sous-catégorie
                            const option = document.createElement('option'); // Crée une nouvelle option
                            option.value = subcategory.category_id; // Définit l'ID de la sous-catégorie
                            option.textContent = subcategory.name; // Définit le nom de la sous-catégorie
                            if (subcategory.category_id == selectedSubCategoryId) { // Si c'est la sous-catégorie sélectionnée
                                option.selected = true; // Pré-sélectionne cette option
                            }
                            subCategorySelect.appendChild(option); // Ajoute l'option au menu
                        });
                    }
                })
                .catch(error => console.error('Erreur lors de la récupération des sous-catégories :', error)); // Affiche les erreurs dans la console
        }
    };

// Fonction pour charger les comptes associés à un budget dans la modale de modification
    function loadAccountsForBudgetEdit(budgetId) {
        // Envoie une requête pour récupérer les comptes liés au budget
        fetch(`/FolioVision/pages/api/get_budget_accounts.php?budget_id=${budgetId}`)
            .then(response => response.json()) // Transforme la réponse en JSON
            .then(data => {
                const accountList = document.getElementById('edit_account_list');
                accountList.innerHTML = ''; // Réinitialise la liste des comptes
                if (data.status === 'success') { // Si la récupération réussit
                    data.accounts.forEach(account => { // Parcourt chaque compte
                        const div = document.createElement('div'); // Crée un conteneur pour chaque compte
                        div.className = 'account-item'; // Ajoute une classe CSS
                        div.style.display = 'flex'; // Utilise Flexbox pour l'affichage
                        div.style.justifyContent = 'space-between'; // Espace les éléments
                        div.style.alignItems = 'center'; // Centre les éléments verticalement
                        div.innerHTML = `
                        <label for="edit_account_${account.account_id}">${account.account_name}</label>
                        <input type="checkbox" name="accounts[]" value="${account.account_id}" id="edit_account_${account.account_id}" ${account.selected ? 'checked' : ''}>
                    `; // Ajoute une étiquette et une case à cocher (cochée si sélectionnée)
                        accountList.appendChild(div); // Ajoute le conteneur à la liste
                    });
                }
            })
            .catch(error => console.error('Erreur lors de la récupération des comptes :', error)); // Affiche les erreurs dans la console
    }

// Fonction pour supprimer un budget après confirmation
    window.deleteBudget = function(budgetId) {
        // Demande à l'utilisateur de confirmer la suppression
        if (confirm('Voulez-vous vraiment supprimer ce budget ?')) {
            // Envoie une requête POST à l'API pour supprimer le budget
            fetch('/FolioVision/pages/api/delete_budget.php', {
                method: 'POST', // Utilise la méthode POST
                headers: { 'Content-Type': 'application/json' }, // Indique le format JSON
                body: JSON.stringify({ budget_id: budgetId }) // Envoie l'ID du budget en JSON
            })
                .then(response => response.json()) // Transforme la réponse en JSON
                .then(data => {
                    if (data.status === 'success') { // Si la suppression réussit
                        alert('Budget supprimé avec succès'); // Affiche une confirmation
                        loadBudgets(); // Recharge la liste des budgets
                    } else { // Si une erreur survient
                        alert('Erreur : ' + data.message); // Affiche le message d'erreur
                    }
                })
                .catch(error => console.error('Erreur lors de la suppression du budget :', error)); // Affiche les erreurs dans la console
        }
    }

// Gestion de la fermeture de la modale de modification via le bouton dédié
    const closeEditBudgetModal = document.getElementById('close-edit-budget-modal');
    if (closeEditBudgetModal) { // Vérifie si le bouton existe dans le DOM
        closeEditBudgetModal.addEventListener('click', function() {
            document.getElementById('edit-budget-modal').style.display = 'none'; // Masque la modale
        });
    }

// Gestion de la fermeture de la modale de modification en cliquant à l'extérieur
    window.addEventListener('click', function(event) {
        const editBudgetModal = document.getElementById('edit-budget-modal');
        if (editBudgetModal && event.target === editBudgetModal) { // Si l'utilisateur clique sur le fond de la modale
            editBudgetModal.style.display = 'none'; // Ferme la modale
        }
    });

// Fonction pour charger et afficher la liste des investissements
    function loadInvestments() {
        // Envoie une requête pour récupérer la liste des investissements
        fetch('/FolioVision/pages/api/get_investments.php')
            .then(response => {
                if (!response.ok) { // Vérifie si la réponse est valide
                    throw new Error('Network response was not ok');
                }
                return response.text(); // Récupère la réponse sous forme de texte
            })
            .then(text => {
                try {
                    const data = JSON.parse(text); // Convertit le texte en objet JSON
                    const investmentList = document.getElementById('investment-list');
                    investmentList.innerHTML = ''; // Réinitialise la liste
                    if (data.status === 'success' && Array.isArray(data.investments)) { // Si les investissements sont récupérés
                        data.investments.forEach(investment => { // Parcourt chaque investissement
                            const investmentItem = document.createElement('div'); // Crée un élément pour l'investissement
                            investmentItem.className = 'investment-item'; // Ajoute une classe CSS
                            const assetType = investment.asset_type || 'Non spécifié'; // Définit le type ou une valeur par défaut
                            investmentItem.innerHTML = `
                            <p><strong>Type :</strong> ${assetType}</p>
                            <p><strong>Nom :</strong> ${investment.asset_name}</p>
                            <p><strong>Prix d'achat :</strong> ${investment.purchase_price} €</p>
                            <p><strong>Valeur actuelle :</strong> ${investment.current_price ? investment.current_price + ' €' : 'Non définie'}</p>
                            <p><strong>Date :</strong> ${investment.purchase_date}</p>
                            <div class="investment-actions">
                                <span class="settings-icon">⚙️</span>
                                <div class="action-menu">
                                    <a href="#" onclick="editInvestment(${investment.investment_id}); return false;">Modifier</a>
                                    <a href="#" onclick="deleteInvestment(${investment.investment_id}); return false;">Supprimer</a>
                                </div>
                            </div>
                        `; // Ajoute le contenu HTML
                            investmentList.appendChild(investmentItem); // Ajoute l'élément à la liste
                        });
                    } else { // Si aucun investissement n'est trouvé
                        investmentList.innerHTML = '<p>Aucun investissement trouvé.</p>'; // Affiche un message par défaut
                    }
                } catch (e) { // En cas d'erreur lors du parsing JSON
                    console.error('Échec du parsing JSON :', text); // Affiche l'erreur dans la console
                    investmentList.innerHTML = '<p>Erreur de chargement des investissements.</p>'; // Affiche un message d'erreur
                    throw new Error('Réponse JSON invalide');
                }
            })
            .catch(error => console.error('Erreur lors de la récupération des investissements :', error)); // Affiche les erreurs dans la console
    }

// Fonction pour initialiser les interactions de la page des investissements
    function initializeInvestmentPage() {
        if (openAddInvestmentBtn && addInvestmentFormContainer) { // Vérifie si les éléments existent
            openAddInvestmentBtn.addEventListener('click', function() {
                addInvestmentFormContainer.style.display = 'block'; // Affiche le conteneur du formulaire
            });
        }
        if (cancelAddInvestmentBtn && addInvestmentFormContainer) { // Vérifie si les éléments existent
            cancelAddInvestmentBtn.addEventListener('click', function() {
                addInvestmentFormContainer.style.display = 'none'; // Masque le conteneur du formulaire
            });
        }
        if (addInvestmentForm) { // Vérifie si le formulaire existe dans le DOM
            addInvestmentForm.addEventListener('submit', function(event) {
                event.preventDefault(); // Empêche le rechargement de la page
                const formData = new FormData(this); // Récupère les données du formulaire
                const investmentType = formData.get('investment_type'); // Récupère le type d'investissement

                // Vérifie si le type d'investissement est valide
                if (!investmentType || !validAssetTypes.includes(investmentType)) {
                    alert('Veuillez sélectionner un type d\'investissement valide : Actions, Crypto-monnaie, Immobilier ou Autre.');
                    return; // Arrête l'exécution si invalide
                }

                // Prépare les données pour l'API en renommant les champs
                formData.set('asset_type', investmentType);
                formData.set('asset_name', formData.get('investment_name'));
                formData.set('purchase_price', formData.get('investment_amount'));
                formData.set('current_price', formData.get('current_price'));
                formData.set('quantity', formData.get('quantity'));
                formData.set('purchase_date', formData.get('investment_date'));
                formData.delete('investment_type'); // Supprime les champs originaux
                formData.delete('investment_name');
                formData.delete('investment_amount');
                formData.delete('investment_date');

                console.log('Données envoyées à add_investment.php :', [...formData.entries()]); // Affiche les données pour le débogage

                // Envoie une requête POST à l'API pour ajouter l'investissement
                fetch('/FolioVision/pages/api/add_investment.php', {
                    method: 'POST', // Utilise la méthode POST
                    body: formData // Envoie les données du formulaire
                })
                    .then(response => {
                        if (!response.ok) { // Vérifie si la réponse est valide
                            throw new Error('Network response was not ok');
                        }
                        return response.text(); // Récupère la réponse sous forme de texte
                    })
                    .then(text => {
                        try {
                            const data = JSON.parse(text); // Convertit le texte en JSON
                            if (data.status === 'success') { // Si l'ajout réussit
                                alert('Investissement ajouté avec succès !'); // Affiche une confirmation
                                loadInvestments(); // Recharge la liste des investissements
                                addInvestmentForm.reset(); // Réinitialise le formulaire
                                addInvestmentFormContainer.style.display = 'none'; // Ferme le conteneur
                            } else { // Si une erreur survient
                                alert('Erreur : ' + data.message); // Affiche le message d'erreur
                            }
                        } catch (e) { // En cas d'erreur lors du parsing JSON
                            console.error('Échec du parsing JSON :', text); // Affiche l'erreur dans la console
                            throw new Error('Réponse JSON invalide');
                        }
                    })
                    .catch(error => console.error('Erreur lors de l\'ajout de l\'investissement :', error)); // Affiche les erreurs dans la console
            });
        }
    }

// Fonction pour ouvrir la modale de modification d'un investissement
    window.editInvestment = function(investmentId) {
        // Envoie une requête pour récupérer les détails de l'investissement spécifié
        fetch(`/FolioVision/pages/api/get_investment.php?investment_id=${investmentId}`)
            .then(response => response.json()) // Transforme la réponse en JSON
            .then(data => {
                if (data.status === 'success') { // Si la récupération réussit
                    const investment = data.investment; // Stocke les données de l'investissement
                    // Remplit les champs de la modale avec les informations de l'investissement
                    document.getElementById('edit_investment_id').value = investment.investment_id;
                    document.getElementById('edit_investment_name').value = investment.asset_name;
                    document.getElementById('edit_investment_amount').value = investment.purchase_price;
                    document.getElementById('edit_current_price').value = investment.current_price || '';
                    document.getElementById('edit_quantity').value = investment.quantity;
                    document.getElementById('edit_investment_date').value = investment.purchase_date;

                    const typeSelect = document.getElementById('edit_investment_type');
                    typeSelect.value = investment.asset_type; // Définit le type d'investissement

                    // Si le type n'existe pas dans la liste, l'ajoute dynamiquement
                    if (!typeSelect.querySelector(`option[value="${investment.asset_type}"]`)) {
                        const newOption = document.createElement('option');
                        newOption.value = investment.asset_type;
                        newOption.textContent = investment.asset_type.charAt(0).toUpperCase() + investment.asset_type.slice(1);
                        typeSelect.appendChild(newOption);
                        typeSelect.value = investment.asset_type;
                    }

                    editInvestmentModal.style.display = 'block'; // Affiche la modale de modification
                } else { // Si une erreur survient
                    alert('Erreur lors de la récupération de l\'investissement : ' + data.message); // Affiche le message d'erreur
                }
            })
            .catch(error => console.error('Erreur lors de la récupération de l\'investissement :', error)); // Affiche les erreurs dans la console
    }

// Gestion de la soumission du formulaire de modification d'un investissement
    if (editInvestmentForm) { // Vérifie si le formulaire existe dans le DOM
        editInvestmentForm.addEventListener('submit', function(event) {
            event.preventDefault(); // Empêche le rechargement de la page
            const formData = new FormData(this); // Récupère les données du formulaire

            // Récupère les valeurs des champs pour validation
            const investmentId = formData.get('investment_id');
            const assetType = formData.get('investment_type');
            const assetName = formData.get('investment_name');
            const purchasePrice = formData.get('investment_amount');
            const currentPrice = formData.get('current_price');
            const quantity = formData.get('quantity');
            const purchaseDate = formData.get('investment_date');

            // Vérifie que tous les champs requis sont remplis
            if (!investmentId || !assetType || !assetName || !purchasePrice || !quantity || !purchaseDate) {
                alert('Tous les champs sont requis.');
                console.error('Valeurs manquantes :', {
                    investmentId, assetType, assetName, purchasePrice, quantity, purchaseDate
                });
                return; // Arrête l'exécution si des champs manquent
            }

            // Prépare un nouvel objet FormData pour envoyer les données à l'API
            const dataToSend = new FormData();
            dataToSend.append('investment_id', investmentId);
            dataToSend.append('asset_type', assetType);
            dataToSend.append('asset_name', assetName);
            dataToSend.append('purchase_price', purchasePrice);
            dataToSend.append('current_price', currentPrice);
            dataToSend.append('quantity', quantity);
            dataToSend.append('purchase_date', purchaseDate);

            console.log('Données envoyées à update_investment.php :', [...dataToSend.entries()]); // Affiche les données pour le débogage

            // Envoie une requête POST à l'API pour mettre à jour l'investissement
            fetch('/FolioVision/pages/api/update_investment.php', {
                method: 'POST', // Utilise la méthode POST
                body: dataToSend // Envoie les données
            })
                .then(response => {
                    if (!response.ok) { // Vérifie si la réponse est valide
                        throw new Error('Erreur réseau : ' + response.status);
                    }
                    return response.json(); // Transforme la réponse en JSON
                })
                .then(data => {
                    if (data.status === 'success') { // Si la mise à jour réussit
                        alert('Investissement mis à jour avec succès'); // Affiche une confirmation
                        loadInvestments(); // Recharge la liste des investissements
                        editInvestmentModal.style.display = 'none'; // Ferme la modale
                    } else { // Si une erreur survient
                        alert('Erreur : ' + data.message); // Affiche le message d'erreur
                    }
                })
                .catch(error => {
                    console.error('Erreur lors de la mise à jour de l’investissement :', error); // Affiche l'erreur dans la console
                    alert('Une erreur est survenue. Vérifiez la console pour plus de détails.'); // Notifie l'utilisateur
                });
        });
    }

// Gestion de la fermeture de la modale de modification via le bouton dédié
    if (closeEditInvestmentModal) { // Vérifie si le bouton existe dans le DOM
        closeEditInvestmentModal.addEventListener('click', function() {
            editInvestmentModal.style.display = 'none'; // Masque la modale
        });
    }

// Gestion de la fermeture de la modale de modification en cliquant à l'extérieur
    window.addEventListener('click', function(event) {
        if (event.target === editInvestmentModal) { // Si l'utilisateur clique sur le fond de la modale
            editInvestmentModal.style.display = 'none'; // Ferme la modale
        }
    });

// Fonction pour supprimer un investissement après confirmation
    window.deleteInvestment = function(investmentId) {
        // Demande à l'utilisateur de confirmer la suppression
        if (confirm('Voulez-vous vraiment supprimer cet investissement ?')) {
            // Envoie une requête POST à l'API pour supprimer l'investissement
            fetch('/FolioVision/pages/api/delete_investment.php', {
                method: 'POST', // Utilise la méthode POST
                headers: { 'Content-Type': 'application/json' }, // Indique le format JSON
                body: JSON.stringify({ investment_id: investmentId }) // Envoie l'ID en JSON
            })
                .then(response => {
                    if (!response.ok) { // Vérifie si la réponse est valide
                        throw new Error('Erreur réseau : ' + response.status);
                    }
                    return response.json(); // Transforme la réponse en JSON
                })
                .then(data => {
                    if (data.status === 'success') { // Si la suppression réussit
                        alert('Investissement supprimé avec succès'); // Affiche une confirmation
                        loadInvestments(); // Recharge la liste des investissements
                    } else { // Si une erreur survient
                        alert('Erreur : ' + data.message); // Affiche le message d'erreur
                    }
                })
                .catch(error => {
                    console.error('Erreur lors de la suppression de l\'investissement :', error); // Affiche l'erreur dans la console
                    alert('Une erreur est survenue lors de la suppression'); // Notifie l'utilisateur
                });
        }
    }

// Charge les investissements et initialise la page si la classe "investments-page" est présente
    if (document.body.classList.contains('investments-page')) {
        loadInvestments(); // Charge la liste des investissements
        initializeInvestmentPage(); // Initialise les interactions de la page
    }

// === 16. Gestion des projets (goals) ===
// Fonction pour charger et afficher la liste des objectifs avec mise à jour automatique du statut
    function loadGoals() {
        // Envoie une requête pour récupérer la liste des objectifs
        fetch('/FolioVision/pages/api/get_goals.php')
            .then(response => response.json()) // Transforme la réponse en JSON
            .then(data => {
                const goalList = document.getElementById('goal-list');
                goalList.innerHTML = ''; // Réinitialise la liste
                if (data.status === 'success') { // Si les objectifs sont récupérés
                    // Définit les traductions des statuts pour l'affichage
                    const statusTranslations = {
                        'in_progress': 'En cours',
                        'achieved': 'Atteint',
                        'failed': 'Échoué'
                    };
                    data.goals.forEach(goal => { // Parcourt chaque objectif
                        const current = parseFloat(goal.current_amount); // Montant actuel
                        const target = parseFloat(goal.target_amount); // Montant cible

                        // Détermine le statut actuel de l'objectif
                        let newStatus = 'in_progress';
                        if (current >= target) { // Si le montant actuel atteint ou dépasse la cible
                            newStatus = 'achieved';
                        } else if (goal.due_date && goal.due_date !== '0000-00-00') { // Si une date d'échéance existe
                            const today = new Date().toISOString().split('T')[0]; // Date actuelle
                            if (today > goal.due_date && current < target) { // Si la date est dépassée et la cible non atteinte
                                newStatus = 'failed';
                            }
                        }

                        // Met à jour le statut si nécessaire
                        if (newStatus !== goal.status) {
                            updateGoalStatus(goal.goal_id, newStatus); // Appelle la fonction de mise à jour
                        }

                        // Calcule le pourcentage de progression
                        const progress = (target > 0) ? (current / target) * 100 : 0;

                        // Crée un élément pour afficher l'objectif
                        const goalItem = document.createElement('div');
                        goalItem.className = 'goal-item'; // Ajoute une classe CSS
                        let html = `
                        <h3>${goal.goal_name}</h3>
                        <p><strong>Montant actuel :</strong> ${current.toFixed(2)} ${goal.currency}</p>
                        <p><strong>Montant cible :</strong> ${target.toFixed(2)} ${goal.currency}</p>
                    `;
                        // Ajoute la date d'échéance ou "Aucune" si non définie
                        if (goal.due_date && goal.due_date !== '0000-00-00') {
                            html += `<p><strong>Date d'échéance :</strong> ${goal.due_date}</p>`;
                        } else {
                            html += `<p><strong>Date d'échéance :</strong> Aucune</p>`;
                        }
                        const statusFr = statusTranslations[newStatus] || newStatus; // Traduit le statut
                        html += `<p><strong>Statut :</strong> ${statusFr}</p>`;
                        if (goal.comment) { // Ajoute le commentaire s'il existe
                            html += `<p><strong>Commentaire :</strong> ${goal.comment}</p>`;
                        }
                        html += `
                        <div class="progress-container">
                            <progress value="${progress}" max="100"></progress>
                            <span class="progress-percentage">${progress.toFixed(2)}%</span>
                        </div>
                        <div class="button-group">
                            <button class="btn add-money" data-goal-id="${goal.goal_id}">Ajouter de l'argent</button>
                            <button class="btn remove-money" data-goal-id="${goal.goal_id}">Retirer de l'argent</button>
                        </div>
                        <div class="goal-actions">
                            <span class="settings-icon">⚙️</span>
                            <div class="action-menu">
                                <a href="#" onclick="editGoal(${goal.goal_id}); return false;">Modifier</a>
                                <a href="#" onclick="deleteGoal(${goal.goal_id}); return false;">Supprimer</a>
                            </div>
                        </div>
                    `; // Ajoute la barre de progression et les actions
                        goalItem.innerHTML = html; // Applique le contenu HTML
                        goalList.appendChild(goalItem); // Ajoute l'élément à la liste
                    });

                    // Ajoute des écouteurs pour les boutons "Ajouter de l'argent"
                    document.querySelectorAll('.add-money').forEach(button => {
                        button.addEventListener('click', function() {
                            const goalId = this.getAttribute('data-goal-id'); // Récupère l'ID de l'objectif
                            document.getElementById('add_money_goal_id').value = goalId; // Définit l'ID dans le champ caché
                            addMoneyModal.style.display = 'block'; // Affiche la modale
                        });
                    });

                    // Ajoute des écouteurs pour les boutons "Retirer de l'argent"
                    document.querySelectorAll('.remove-money').forEach(button => {
                        button.addEventListener('click', function() {
                            const goalId = this.getAttribute('data-goal-id'); // Récupère l'ID de l'objectif
                            document.getElementById('remove_money_goal_id').value = goalId; // Définit l'ID dans le champ caché
                            removeMoneyModal.style.display = 'block'; // Affiche la modale
                        });
                    });
                } else { // Si aucun objectif n'est trouvé
                    goalList.innerHTML = '<p>Aucun projet trouvé.</p>'; // Affiche un message par défaut
                }
            })
            .catch(error => console.error('Erreur lors de la récupération des projets :', error)); // Affiche les erreurs dans la console
    }

// Fonction pour mettre à jour le statut d'un objectif
    function updateGoalStatus(goalId, newStatus) {
        // Envoie une requête POST à l'API pour mettre à jour le statut
        fetch('/FolioVision/pages/api/update_goal_status.php', {
            method: 'POST', // Utilise la méthode POST
            headers: { 'Content-Type': 'application/json' }, // Indique le format JSON
            body: JSON.stringify({ goal_id: goalId, status: newStatus }) // Envoie les données en JSON
        })
            .then(response => response.json()) // Transforme la réponse en JSON
            .then(data => {
                if (data.status === 'success') { // Si la mise à jour réussit
                    console.log('Statut mis à jour avec succès'); // Affiche un message dans la console
                    loadGoals(); // Recharge la liste des objectifs
                } else { // Si une erreur survient
                    console.error('Erreur lors de la mise à jour du statut :', data.message); // Affiche l'erreur dans la console
                }
            })
            .catch(error => console.error('Erreur lors de la mise à jour du statut :', error)); // Affiche les erreurs dans la console
    }

// Gestion de la soumission du formulaire pour ajouter de l'argent à un objectif
    if (addMoneyForm) { // Vérifie si le formulaire existe dans le DOM
        addMoneyForm.addEventListener('submit', function(event) {
            event.preventDefault(); // Empêche le rechargement de la page
            const formData = new FormData(this); // Récupère les données du formulaire
            // Envoie une requête POST à l'API pour mettre à jour l'objectif
            fetch('/FolioVision/pages/api/update_goal.php', {
                method: 'POST', // Utilise la méthode POST
                body: formData // Envoie les données
            })
                .then(response => response.json()) // Transforme la réponse en JSON
                .then(data => {
                    if (data.status === 'success') { // Si la mise à jour réussit
                        alert('Montant ajouté avec succès !'); // Affiche une confirmation
                        loadGoals(); // Recharge la liste des objectifs
                        addMoneyForm.reset(); // Réinitialise le formulaire
                        addMoneyModal.style.display = 'none'; // Ferme la modale
                    } else { // Si une erreur survient
                        alert('Erreur : ' + data.message); // Affiche le message d'erreur
                    }
                })
                .catch(error => console.error('Erreur lors de l\'ajout d\'argent :', error)); // Affiche les erreurs dans la console
        });
    }

// Gestion de la soumission du formulaire pour retirer de l'argent d'un objectif
    if (removeMoneyForm) { // Vérifie si le formulaire existe dans le DOM
        removeMoneyForm.addEventListener('submit', function(event) {
            event.preventDefault(); // Empêche le rechargement de la page
            const formData = new FormData(this); // Récupère les données du formulaire
            // Envoie une requête POST à l'API pour mettre à jour l'objectif
            fetch('/FolioVision/pages/api/update_goal.php', {
                method: 'POST', // Utilise la méthode POST
                body: formData // Envoie les données
            })
                .then(response => response.json()) // Transforme la réponse en JSON
                .then(data => {
                    if (data.status === 'success') { // Si la mise à jour réussit
                        alert('Montant retiré avec succès !'); // Affiche une confirmation
                        loadGoals(); // Recharge la liste des objectifs
                        removeMoneyForm.reset(); // Réinitialise le formulaire
                        removeMoneyModal.style.display = 'none'; // Ferme la modale
                    } else { // Si une erreur survient
                        alert('Erreur : ' + data.message); // Affiche le message d'erreur
                    }
                })
                .catch(error => console.error('Erreur lors du retrait d\'argent :', error)); // Affiche les erreurs dans la console
        });
    }

// Fonction pour initialiser les interactions de la page des objectifs
    function initializeGoalsPage() {
        if (openAddGoalBtn && addGoalModal) { // Vérifie si les éléments existent
            openAddGoalBtn.addEventListener('click', function() {
                addGoalModal.style.display = 'block'; // Affiche la modale d'ajout d'objectif
            });
        }

        if (closeAddGoalModal) { // Vérifie si le bouton existe dans le DOM
            closeAddGoalModal.addEventListener('click', function() {
                addGoalModal.style.display = 'none'; // Ferme la modale d'ajout
            });
        }

        if (cancelAddGoalBtn) { // Vérifie si le bouton existe dans le DOM
            cancelAddGoalBtn.addEventListener('click', function() {
                addGoalModal.style.display = 'none'; // Ferme la modale d'ajout
            });
        }

        if (closeAddMoneyModal) { // Vérifie si le bouton existe dans le DOM
            closeAddMoneyModal.addEventListener('click', function() {
                addMoneyModal.style.display = 'none'; // Ferme la modale d'ajout d'argent
            });
        }

        if (closeRemoveMoneyModal) { // Vérifie si le bouton existe dans le DOM
            closeRemoveMoneyModal.addEventListener('click', function() {
                removeMoneyModal.style.display = 'none'; // Ferme la modale de retrait d'argent
            });
        }

        if (closeEditGoalModal) { // Vérifie si le bouton existe dans le DOM
            closeEditGoalModal.addEventListener('click', function() {
                editGoalModal.style.display = 'none'; // Ferme la modale de modification
            });
        }

        if (cancelEditGoalBtn) { // Vérifie si le bouton existe dans le DOM
            cancelEditGoalBtn.addEventListener('click', function() {
                editGoalModal.style.display = 'none'; // Ferme la modale de modification
            });
        }

        // Gestion de la soumission du formulaire de modification d'un objectif
        if (editGoalForm) { // Vérifie si le formulaire existe dans le DOM
            editGoalForm.addEventListener('submit', function(event) {
                event.preventDefault(); // Empêche le rechargement de la page
                event.stopPropagation(); // Empêche la propagation de l'événement

                console.log('Formulaire soumis'); // Affiche un message pour le débogage

                const formData = new FormData(this); // Récupère les données du formulaire
                // Envoie une requête POST à l'API pour mettre à jour l'objectif
                fetch('/FolioVision/pages/api/update_goal.php', {
                    method: 'POST', // Utilise la méthode POST
                    body: formData // Envoie les données
                })
                    .then(response => response.json()) // Transforme la réponse en JSON
                    .then(data => {
                        if (data.status === 'success') { // Si la mise à jour réussit
                            alert('Projet mis à jour avec succès !'); // Affiche une confirmation
                            loadGoals(); // Recharge la liste des objectifs
                            editGoalModal.style.display = 'none'; // Ferme la modale
                        } else { // Si une erreur survient
                            alert('Erreur : ' + data.message); // Affiche le message d'erreur
                        }
                    })
                    .catch(error => console.error('Erreur lors de la mise à jour du projet :', error)); // Affiche les erreurs dans la console
            }, { once: true }); // Garantit que l'écouteur ne s'exécute qu'une fois par soumission
        }

        const addGoalForm = document.getElementById('add-goal-form');
        if (addGoalForm) { // Vérifie si le formulaire existe dans le DOM
            addGoalForm.addEventListener('submit', function(event) {
                event.preventDefault(); // Empêche le rechargement de la page
                const formData = new FormData(this); // Récupère les données du formulaire

                // Envoie une requête POST à l'API pour ajouter un nouvel objectif
                fetch('/FolioVision/pages/api/add_goal.php', {
                    method: 'POST', // Utilise la méthode POST
                    body: formData // Envoie les données
                })
                    .then(response => response.json()) // Transforme la réponse en JSON
                    .then(data => {
                        if (data.status === 'success') { // Si l'ajout réussit
                            alert('Projet ajouté avec succès !'); // Affiche une confirmation
                            loadGoals(); // Recharge la liste des objectifs
                            addGoalForm.reset(); // Réinitialise le formulaire
                            addGoalModal.style.display = 'none'; // Ferme la modale
                        } else { // Si une erreur survient
                            alert('Erreur : ' + data.message); // Affiche le message d'erreur
                        }
                    })
                    .catch(error => console.error('Erreur lors de l\'ajout du projet :', error)); // Affiche les erreurs dans la console
            });
        }
    }

// Fonction pour ouvrir la modale de modification d'un objectif
    window.editGoal = function(goalId) {
        // Envoie une requête pour récupérer les détails de l'objectif spécifié
        fetch(`/FolioVision/pages/api/get_goal.php?goal_id=${goalId}`)
            .then(response => response.json()) // Transforme la réponse en JSON
            .then(data => {
                if (data.status === 'success') { // Si la récupération réussit
                    const goal = data.goal; // Stocke les données de l'objectif
                    // Remplit les champs de la modale avec les informations de l'objectif
                    document.getElementById('edit_goal_id').value = goal.goal_id;
                    document.getElementById('edit_goal_name').value = goal.goal_name;
                    document.getElementById('edit_target_amount').value = goal.target_amount;
                    document.getElementById('edit_currency').value = goal.currency;
                    document.getElementById('edit_initial_amount').value = goal.current_amount;
                    document.getElementById('edit_due_date').value = goal.due_date || '';
                    document.getElementById('edit_comment').value = goal.comment || '';
                    editGoalModal.style.display = 'block'; // Affiche la modale de modification
                } else { // Si une erreur survient
                    alert('Erreur lors de la récupération du projet : ' + data.message); // Affiche le message d'erreur
                }
            })
            .catch(error => console.error('Erreur lors de la récupération du projet :', error)); // Affiche les erreurs dans la console
    }

// Fonction pour supprimer un objectif après confirmation
    window.deleteGoal = function(goalId) {
        // Demande à l'utilisateur de confirmer la suppression
        if (confirm('Voulez-vous vraiment supprimer ce projet ?')) {
            // Envoie une requête POST à l'API pour supprimer l'objectif
            fetch('/FolioVision/pages/api/delete_goal.php', {
                method: 'POST', // Utilise la méthode POST
                headers: { 'Content-Type': 'application/json' }, // Indique le format JSON
                body: JSON.stringify({ goal_id: goalId }) // Envoie l'ID en JSON
            })
                .then(response => response.json()) // Transforme la réponse en JSON
                .then(data => {
                    if (data.status === 'success') { // Si la suppression réussit
                        alert('Projet supprimé avec succès'); // Affiche une confirmation
                        loadGoals(); // Recharge la liste des objectifs
                    } else { // Si une erreur survient
                        alert('Erreur : ' + data.message); // Affiche le message d'erreur
                    }
                })
                .catch(error => console.error('Erreur lors de la suppression du projet :', error)); // Affiche les erreurs dans la console
        }
    }

// Gestion de la fermeture des modales des objectifs en cliquant à l'extérieur
    window.addEventListener('click', function(event) {
        if (event.target === addGoalModal) { // Si clic sur le fond de la modale d'ajout
            addGoalModal.style.display = 'none'; // Ferme la modale
        }
        if (event.target === addMoneyModal) { // Si clic sur le fond de la modale d'ajout d'argent
            addMoneyModal.style.display = 'none'; // Ferme la modale
        }
        if (event.target === removeMoneyModal) { // Si clic sur le fond de la modale de retrait
            removeMoneyModal.style.display = 'none'; // Ferme la modale
        }
        if (event.target === editGoalModal) { // Si clic sur le fond de la modale de modification
            editGoalModal.style.display = 'none'; // Ferme la modale
        }
    });

// Charge les objectifs et initialise la page si la classe "goals-page" est présente
    if (document.body.classList.contains('goals-page')) {
        loadGoals(); // Charge la liste des objectifs
        initializeGoalsPage(); // Initialise les interactions de la page
    }
});