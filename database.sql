CREATE TABLE users (
                       user_id INT AUTO_INCREMENT PRIMARY KEY,
                       username VARCHAR(50) NOT NULL UNIQUE,
                       email VARCHAR(100) NOT NULL UNIQUE,
                       password VARCHAR(255) NOT NULL,
                       preferred_theme ENUM('light', 'dark') DEFAULT 'dark',
                       preferred_text_color VARCHAR(20) DEFAULT '#00bcd4',
                       created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
                       updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

CREATE TABLE user_preferences (
                                  pref_id INT AUTO_INCREMENT PRIMARY KEY,
                                  user_id INT NOT NULL,
                                  savename VARCHAR(20) NOT NULL,
                                  theme ENUM('light', 'dark') DEFAULT 'dark',
                                  text_color VARCHAR(20) DEFAULT '#00bcd4',
                                  widgets_config TEXT,
                                  FOREIGN KEY (user_id) REFERENCES users(user_id) ON DELETE CASCADE
);

CREATE TABLE accounts (
                          account_id INT AUTO_INCREMENT PRIMARY KEY,
                          user_id INT NOT NULL,
                          account_name VARCHAR(100) NOT NULL,
                          account_type ENUM('bank', 'cash', 'crypto', 'investment', 'credit_card') NOT NULL,
                          bank_subtype ENUM('current', 'savings') NULL,
                          currency VARCHAR(10) DEFAULT 'EUR',
                          balance DECIMAL(15,2) DEFAULT 0,
                          created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
                          updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                          FOREIGN KEY (user_id) REFERENCES users(user_id) ON DELETE CASCADE,
                          CHECK ( (account_type = 'bank' AND bank_subtype IS NOT NULL) OR (account_type != 'bank' AND bank_subtype IS NULL) )
);

CREATE TABLE categories (
                            category_id INT AUTO_INCREMENT PRIMARY KEY,
                            user_id INT,
                            name VARCHAR(50) NOT NULL,
                            parent_id INT DEFAULT NULL,
                            FOREIGN KEY (parent_id) REFERENCES categories(category_id) ON DELETE SET NULL,
                            FOREIGN KEY (user_id) REFERENCES users(user_id) ON DELETE CASCADE
);

CREATE TABLE transactions (
                              transaction_id INT AUTO_INCREMENT PRIMARY KEY,
                              account_id INT NOT NULL,
                              category_id INT DEFAULT NULL,
                              amount DECIMAL(15,2) NOT NULL,
                              transaction_date DATE NOT NULL,
                              value_date DATE NOT NULL,
                              type ENUM('debit', 'credit') NOT NULL,
                              payment_method ENUM('bank_card', 'check', 'transfer', 'check_deposit', 'direct_debit', 'cash_deposit', 'other') NOT NULL,
                              description VARCHAR(255),
                              source ENUM('manual', 'import') DEFAULT 'manual',
                              created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
                              FOREIGN KEY (account_id) REFERENCES accounts(account_id) ON DELETE CASCADE,
                              FOREIGN KEY (category_id) REFERENCES categories(category_id) ON DELETE SET NULL
);

CREATE TABLE budgets (
                         budget_id INT AUTO_INCREMENT PRIMARY KEY,
                         user_id INT NOT NULL,
                         category_id INT NOT NULL,
                         budget_amount DECIMAL(15,2) NOT NULL,
                         period ENUM('monthly', 'quarterly', 'annually', 'custom') NOT NULL DEFAULT 'monthly',
                         start_date DATE NOT NULL,
                         end_date DATE NULL,
                         carry_over_under BOOLEAN DEFAULT 0,
                         carry_over_over BOOLEAN DEFAULT 0,
                         created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
                         FOREIGN KEY (user_id) REFERENCES users(user_id) ON DELETE CASCADE,
                         FOREIGN KEY (category_id) REFERENCES categories(category_id) ON DELETE CASCADE
);

CREATE TABLE budget_accounts (
                                 budget_id INT NOT NULL,
                                 account_id INT NOT NULL,
                                 PRIMARY KEY (budget_id, account_id),
                                 FOREIGN KEY (budget_id) REFERENCES budgets(budget_id) ON DELETE CASCADE,
                                 FOREIGN KEY (account_id) REFERENCES accounts(account_id) ON DELETE CASCADE
);

CREATE TABLE goals (
                       goal_id INT AUTO_INCREMENT PRIMARY KEY,
                       user_id INT NOT NULL,
                       goal_name VARCHAR(100) NOT NULL,
                       target_amount DECIMAL(15,2) NOT NULL,
                       currency VARCHAR(10) NOT NULL,
                       current_amount DECIMAL(15,2) DEFAULT 0,
                       due_date DATE,
                       status ENUM('in_progress', 'achieved', 'failed') DEFAULT 'in_progress',
                       comment TEXT,
                       created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
                       updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                       FOREIGN KEY (user_id) REFERENCES users(user_id) ON DELETE CASCADE
);

CREATE TABLE investments (
                             investment_id INT AUTO_INCREMENT PRIMARY KEY,
                             user_id INT NOT NULL,
                             account_id INT,
                             asset_type ENUM('stock', 'crypto', 'real_estate', 'other') NOT NULL,
                             asset_name VARCHAR(100) NOT NULL,
                             purchase_price DECIMAL(15,4) NOT NULL,
                             current_price DECIMAL(15,4) DEFAULT NULL,
                             quantity DECIMAL(15,4) NOT NULL,
                             purchase_date DATE NOT NULL,
                             created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
                             updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                             FOREIGN KEY (user_id) REFERENCES users(user_id) ON DELETE CASCADE,
                             FOREIGN KEY (account_id) REFERENCES accounts(account_id) ON DELETE SET NULL
);



-- Insérer les catégories principales et stocker leurs IDs
INSERT INTO categories (user_id, name, parent_id) VALUES (NULL, 'Abonnements & Factures', NULL);
SET @abonnements_id = LAST_INSERT_ID();

INSERT INTO categories (user_id, name, parent_id) VALUES (NULL, 'Achat & Shopping', NULL);
SET @achat_id = LAST_INSERT_ID();

INSERT INTO categories (user_id, name, parent_id) VALUES (NULL, 'Alimentation & Restaurant', NULL);
SET @alimentation_id = LAST_INSERT_ID();

INSERT INTO categories (user_id, name, parent_id) VALUES (NULL, 'Animaux', NULL);
SET @animaux_id = LAST_INSERT_ID();

INSERT INTO categories (user_id, name, parent_id) VALUES (NULL, 'Auto & Transport', NULL);
SET @auto_id = LAST_INSERT_ID();

INSERT INTO categories (user_id, name, parent_id) VALUES (NULL, 'Autre', NULL);
SET @autre_id = LAST_INSERT_ID();

INSERT INTO categories (user_id, name, parent_id) VALUES (NULL, 'Banque & Assurance', NULL);
SET @banque_id = LAST_INSERT_ID();

INSERT INTO categories (user_id, name, parent_id) VALUES (NULL, 'Bien-être & Soins', NULL);
SET @bien_etre_id = LAST_INSERT_ID();

INSERT INTO categories (user_id, name, parent_id) VALUES (NULL, 'Bourse', NULL);
SET @bourse_id = LAST_INSERT_ID();

INSERT INTO categories (user_id, name, parent_id) VALUES (NULL, 'Enfants & Éducation', NULL);
SET @enfants_id = LAST_INSERT_ID();

INSERT INTO categories (user_id, name, parent_id) VALUES (NULL, 'Habitation', NULL);
SET @habitation_id = LAST_INSERT_ID();

INSERT INTO categories (user_id, name, parent_id) VALUES (NULL, 'Hors budget', NULL);
SET @hors_budget_id = LAST_INSERT_ID();

INSERT INTO categories (user_id, name, parent_id) VALUES (NULL, 'Impôts & Taxes', NULL);
SET @impots_id = LAST_INSERT_ID();

INSERT INTO categories (user_id, name, parent_id) VALUES (NULL, 'Retraits Chq. Vir.', NULL);
SET @retraits_id = LAST_INSERT_ID();

INSERT INTO categories (user_id, name, parent_id) VALUES (NULL, 'Revenus', NULL);
SET @revenus_id = LAST_INSERT_ID();

INSERT INTO categories (user_id, name, parent_id) VALUES (NULL, 'Santé', NULL);
SET @sante_id = LAST_INSERT_ID();

INSERT INTO categories (user_id, name, parent_id) VALUES (NULL, 'Sorties', NULL);
SET @sorties_id = LAST_INSERT_ID();

INSERT INTO categories (user_id, name, parent_id) VALUES (NULL, 'Vie quotidienne', NULL);
SET @vie_quotidienne_id = LAST_INSERT_ID();

-- Insérer les sous-catégories enrichies pour chaque catégorie principale

-- Abonnements & Factures
INSERT INTO categories (user_id, name, parent_id) VALUES
                                                      (NULL, 'Internet', @abonnements_id),
                                                      (NULL, 'Téléphone mobile', @abonnements_id),
                                                      (NULL, 'Téléphone fixe', @abonnements_id),
                                                      (NULL, 'Électricité', @abonnements_id),
                                                      (NULL, 'Gaz', @abonnements_id),
                                                      (NULL, 'Eau', @abonnements_id),
                                                      (NULL, 'Chauffage', @abonnements_id),
                                                      (NULL, 'Assurance habitation', @abonnements_id),
                                                      (NULL, 'Assurance auto', @abonnements_id),
                                                      (NULL, 'Assurance moto', @abonnements_id),
                                                      (NULL, 'Abonnement streaming', @abonnements_id),
                                                      (NULL, 'Abonnement presse/magazines', @abonnements_id),
                                                      (NULL, 'Abonnement salle de sport', @abonnements_id),
                                                      (NULL, 'Abonnement transport', @abonnements_id),
                                                      (NULL, 'Forfait cloud', @abonnements_id),
                                                      (NULL, 'Abonnement logiciel', @abonnements_id);

-- Achat & Shopping
INSERT INTO categories (user_id, name, parent_id) VALUES
                                                      (NULL, 'Vêtements', @achat_id),
                                                      (NULL, 'Chaussures', @achat_id),
                                                      (NULL, 'Accessoires', @achat_id),
                                                      (NULL, 'Électronique', @achat_id),
                                                      (NULL, 'Électroménager', @achat_id),
                                                      (NULL, 'Meubles', @achat_id),
                                                      (NULL, 'Décoration', @achat_id),
                                                      (NULL, 'Cadeaux', @achat_id),
                                                      (NULL, 'Livres', @achat_id),
                                                      (NULL, 'Jeux vidéo', @achat_id),
                                                      (NULL, 'Articles de sport', @achat_id),
                                                      (NULL, 'Matériel de bricolage', @achat_id),
                                                      (NULL, 'Produits de jardinage', @achat_id),
                                                      (NULL, 'Jouets pour adultes', @achat_id),
                                                      (NULL, 'Articles de collection', @achat_id);

-- Alimentation & Restaurant
INSERT INTO categories (user_id, name, parent_id) VALUES
                                                      (NULL, 'Supermarché', @alimentation_id),
                                                      (NULL, 'Marché local', @alimentation_id),
                                                      (NULL, 'Restaurant', @alimentation_id),
                                                      (NULL, 'Fast-food', @alimentation_id),
                                                      (NULL, 'Café/Bar', @alimentation_id),
                                                      (NULL, 'Livraison à domicile', @alimentation_id),
                                                      (NULL, 'Épicerie fine', @alimentation_id),
                                                      (NULL, 'Boulangerie/Pâtisserie', @alimentation_id),
                                                      (NULL, 'Boucherie/Charcuterie', @alimentation_id),
                                                      (NULL, 'Poissonnerie', @alimentation_id),
                                                      (NULL, 'Boissons alcoolisées', @alimentation_id),
                                                      (NULL, 'Repas au travail', @alimentation_id);

-- Animaux
INSERT INTO categories (user_id, name, parent_id) VALUES
                                                      (NULL, 'Nourriture', @animaux_id),
                                                      (NULL, 'Vétérinaire', @animaux_id),
                                                      (NULL, 'Médicaments pour animaux', @animaux_id),
                                                      (NULL, 'Accessoires', @animaux_id),
                                                      (NULL, 'Toilettage', @animaux_id),
                                                      (NULL, 'Garde/Pension', @animaux_id),
                                                      (NULL, 'Assurance animaux', @animaux_id),
                                                      (NULL, 'Dressage/Éducation', @animaux_id),
                                                      (NULL, 'Équipement d’aquarium/terrarium', @animaux_id),
                                                      (NULL, 'Frais d’adoption', @animaux_id);

-- Auto & Transport
INSERT INTO categories (user_id, name, parent_id) VALUES
                                                      (NULL, 'Essence', @auto_id),
                                                      (NULL, 'Diesel', @auto_id),
                                                      (NULL, 'Recharge électrique', @auto_id),
                                                      (NULL, 'Transports en commun', @auto_id),
                                                      (NULL, 'Taxi/Uber', @auto_id),
                                                      (NULL, 'Parking', @auto_id),
                                                      (NULL, 'Péages', @auto_id),
                                                      (NULL, 'Entretien/Réparation', @auto_id),
                                                      (NULL, 'Location de voiture', @auto_id),
                                                      (NULL, 'Assurance auto', @auto_id),
                                                      (NULL, 'Achat de véhicule', @auto_id),
                                                      (NULL, 'Accessoires auto', @auto_id),
                                                      (NULL, 'Vélo', @auto_id),
                                                      (NULL, 'Trottinette électrique', @auto_id),
                                                      (NULL, 'Bateau', @auto_id);

-- Autre
INSERT INTO categories (user_id, name, parent_id) VALUES
                                                      (NULL, 'Dons', @autre_id),
                                                      (NULL, 'Frais bancaires', @autre_id),
                                                      (NULL, 'Pénalités/Amendes', @autre_id),
                                                      (NULL, 'Divers', @autre_id),
                                                      (NULL, 'Frais postaux', @autre_id),
                                                      (NULL, 'Cotisations associatives', @autre_id),
                                                      (NULL, 'Frais juridiques', @autre_id),
                                                      (NULL, 'Pertes/vols', @autre_id);

-- Banque & Assurance
INSERT INTO categories (user_id, name, parent_id) VALUES
                                                      (NULL, 'Frais bancaires', @banque_id),
                                                      (NULL, 'Assurance vie', @banque_id),
                                                      (NULL, 'Assurance maladie', @banque_id),
                                                      (NULL, 'Assurance voyage', @banque_id),
                                                      (NULL, 'Assurance scolaire', @banque_id),
                                                      (NULL, 'Assurance animaux', @banque_id),
                                                      (NULL, 'Assurance prêt immobilier', @banque_id),
                                                      (NULL, 'Frais de gestion de compte', @banque_id),
                                                      (NULL, 'Cotisations mutuelle', @banque_id);

-- Bien-être & Soins
INSERT INTO categories (user_id, name, parent_id) VALUES
                                                      (NULL, 'Coiffeur', @bien_etre_id),
                                                      (NULL, 'Spa/Massage', @bien_etre_id),
                                                      (NULL, 'Produits de beauté', @bien_etre_id),
                                                      (NULL, 'Sport/Fitness', @bien_etre_id),
                                                      (NULL, 'Manucure/Pédicure', @bien_etre_id),
                                                      (NULL, 'Parfums', @bien_etre_id),
                                                      (NULL, 'Soins thermaux', @bien_etre_id),
                                                      (NULL, 'Cours de yoga/méditation', @bien_etre_id),
                                                      (NULL, 'Abonnement bien-être', @bien_etre_id),
                                                      (NULL, 'Tatouages/Piercings', @bien_etre_id);

-- Bourse
INSERT INTO categories (user_id, name, parent_id) VALUES
                                                      (NULL, 'Actions', @bourse_id),
                                                      (NULL, 'Obligations', @bourse_id),
                                                      (NULL, 'Cryptomonnaies', @bourse_id),
                                                      (NULL, 'Frais de courtage', @bourse_id),
                                                      (NULL, 'ETF/Fonds indiciels', @bourse_id),
                                                      (NULL, 'Options/Dérivés', @bourse_id),
                                                      (NULL, 'Investissements immobiliers', @bourse_id),
                                                      (NULL, 'Crowdfunding', @bourse_id),
                                                      (NULL, 'PEA', @bourse_id);

-- Enfants & Éducation
INSERT INTO categories (user_id, name, parent_id) VALUES
                                                      (NULL, 'Garde d’enfants', @enfants_id),
                                                      (NULL, 'École/Université', @enfants_id),
                                                      (NULL, 'Activités extrascolaires', @enfants_id),
                                                      (NULL, 'Fournitures scolaires', @enfants_id),
                                                      (NULL, 'Jouets', @enfants_id),
                                                      (NULL, 'Vêtements enfants', @enfants_id),
                                                      (NULL, 'Cours particuliers', @enfants_id),
                                                      (NULL, 'Colonie de vacances', @enfants_id),
                                                      (NULL, 'Équipement bébé', @enfants_id),
                                                      (NULL, 'Frais de cantine', @enfants_id),
                                                      (NULL, 'Livres éducatifs', @enfants_id);

-- Habitation
INSERT INTO categories (user_id, name, parent_id) VALUES
                                                      (NULL, 'Loyer', @habitation_id),
                                                      (NULL, 'Hypothèque', @habitation_id),
                                                      (NULL, 'Charges', @habitation_id),
                                                      (NULL, 'Entretien/Réparations', @habitation_id),
                                                      (NULL, 'Jardinage', @habitation_id),
                                                      (NULL, 'Assurance habitation', @habitation_id),
                                                      (NULL, 'Équipement maison', @habitation_id),
                                                      (NULL, 'Travaux/améliorations', @habitation_id),
                                                      (NULL, 'Déménagement', @habitation_id),
                                                      (NULL, 'Stockage', @habitation_id),
                                                      (NULL, 'Taxe ordures ménagères', @habitation_id);

-- Hors budget
INSERT INTO categories (user_id, name, parent_id) VALUES
                                                      (NULL, 'Cadeaux reçus', @hors_budget_id),
                                                      (NULL, 'Remboursements', @hors_budget_id),
                                                      (NULL, 'Gains divers', @hors_budget_id),
                                                      (NULL, 'Prêts remboursés', @hors_budget_id),
                                                      (NULL, 'Héritage', @hors_budget_id),
                                                      (NULL, 'Gains de jeux/loterie', @hors_budget_id),
                                                      (NULL, 'Ventes d’objets personnels', @hors_budget_id);

-- Impôts & Taxes
INSERT INTO categories (user_id, name, parent_id) VALUES
                                                      (NULL, 'Impôt sur le revenu', @impots_id),
                                                      (NULL, 'Taxe foncière', @impots_id),
                                                      (NULL, 'Taxe d’habitation', @impots_id),
                                                      (NULL, 'Autres taxes', @impots_id),
                                                      (NULL, 'Droits de douane', @impots_id),
                                                      (NULL, 'Taxe sur les véhicules', @impots_id),
                                                      (NULL, 'Contribution audiovisuelle', @impots_id);

-- Retraits Chq. Vir.
INSERT INTO categories (user_id, name, parent_id) VALUES
                                                      (NULL, 'Retraits d’espèces', @retraits_id),
                                                      (NULL, 'Chèques émis', @retraits_id),
                                                      (NULL, 'Virements sortants', @retraits_id),
                                                      (NULL, 'Prélèvements automatiques', @retraits_id),
                                                      (NULL, 'Paiements par carte différés', @retraits_id);

-- Revenus
INSERT INTO categories (user_id, name, parent_id) VALUES
                                                      (NULL, 'Salaire', @revenus_id),
                                                      (NULL, 'Revenus locatifs', @revenus_id),
                                                      (NULL, 'Dividendes', @revenus_id),
                                                      (NULL, 'Intérêts bancaires', @revenus_id),
                                                      (NULL, 'Ventes personnelles', @revenus_id),
                                                      (NULL, 'Freelance/Prestations', @revenus_id),
                                                      (NULL, 'Pension alimentaire', @revenus_id),
                                                      (NULL, 'Allocations', @revenus_id),
                                                      (NULL, 'Remboursements fiscaux', @revenus_id),
                                                      (NULL, 'Revenus agricoles', @revenus_id),
                                                      (NULL, 'Gains de paris', @revenus_id);

-- Santé
INSERT INTO categories (user_id, name, parent_id) VALUES
                                                      (NULL, 'Médecin', @sante_id),
                                                      (NULL, 'Pharmacie', @sante_id),
                                                      (NULL, 'Dentiste', @sante_id),
                                                      (NULL, 'Opticien', @sante_id),
                                                      (NULL, 'Hôpital', @sante_id),
                                                      (NULL, 'Assurance santé', @sante_id),
                                                      (NULL, 'Kinésithérapeute', @sante_id),
                                                      (NULL, 'Psychologue', @sante_id),
                                                      (NULL, 'Orthopédie', @sante_id),
                                                      (NULL, 'Médecine alternative', @sante_id),
                                                      (NULL, 'Analyses médicales', @sante_id);

-- Sorties
INSERT INTO categories (user_id, name, parent_id) VALUES
                                                      (NULL, 'Cinéma', @sorties_id),
                                                      (NULL, 'Théâtre/Concert', @sorties_id),
                                                      (NULL, 'Sport', @sorties_id),
                                                      (NULL, 'Voyages/Vacances', @sorties_id),
                                                      (NULL, 'Bars/Clubs', @sorties_id),
                                                      (NULL, 'Parcs d’attractions', @sorties_id),
                                                      (NULL, 'Musées/Expositions', @sorties_id),
                                                      (NULL, 'Festivals', @sorties_id),
                                                      (NULL, 'Sorties nature', @sorties_id),
                                                      (NULL, 'Billets d’avion/hôtel', @sorties_id);

-- Vie quotidienne
INSERT INTO categories (user_id, name, parent_id) VALUES
                                                      (NULL, 'Hygiène personnelle', @vie_quotidienne_id),
                                                      (NULL, 'Produits ménagers', @vie_quotidienne_id),
                                                      (NULL, 'Tabac', @vie_quotidienne_id),
                                                      (NULL, 'Journaux/Magazines', @vie_quotidienne_id),
                                                      (NULL, 'Laverie/Pressing', @vie_quotidienne_id),
                                                      (NULL, 'Papeterie', @vie_quotidienne_id),
                                                      (NULL, 'Frais de cohabitation', @vie_quotidienne_id),
                                                      (NULL, 'Abonnements numériques', @vie_quotidienne_id),
                                                      (NULL, 'Petites réparations', @vie_quotidienne_id);