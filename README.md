poc-keycloak/
├── docker-compose.yml
├── keycloak/
├── mysql/
│   └── init.sql
├── site/
│   ├── Dockerfile
│   ├── login.php           # Page de connexion avec Bootstrap
│   ├── switch.php          # Traitement après authentification OpenID
│   ├── middleware.php      # Vérification de la clé pour tout le site
│   ├── index.php           # Page principale
│   ├── admin/
│   │   └── index.php       # Section admin
│   ├── conf/
│       └── apache.conf


docker-compose up --build

Ajoutez à /etc/hosts : 127.0.0.1 site.local

Page de connexion : http://site.local:8000/login.php.

Objectif du projet

Ce projet est un Proof of Concept (POC) montrant comment implémenter une authentification sécurisée via OpenID Connect avec Keycloak et PHP 5.6, sans framework, pour un site web protégé. Il inclut la génération d’une clé unique synchronisée avec la durée de vie des tokens de Keycloak, la gestion des rôles des utilisateurs, et une interface moderne pour la connexion.

Fonctionnalités principales

    Authentification OpenID Connect :
        Les utilisateurs se connectent via Keycloak, qui agit comme fournisseur d'identité (IdP).
        L'application reçoit et vérifie les ID Tokens (JWT) pour authentifier les utilisateurs.

    Gestion des sessions utilisateur :
        Une clé unique (auth_key) est générée côté application après une authentification réussie.
        La clé est synchronisée avec la durée de vie des tokens OpenID Connect et stockée en base de données.

    Contrôle d'accès :
        L'accès aux pages protégées (comme /admin) est autorisé uniquement si :
            La clé envoyée dans l'en-tête HTTP est valide.
            L'utilisateur dispose du rôle nécessaire (vérifié dans MySQL).

    Page de connexion moderne :
        Une page de connexion conçue avec Bootstrap 5, offrant une interface utilisateur simple et élégante.

    Sécurité renforcée :
        Vérification des tokens JWT avec la clé publique de Keycloak.
        Expiration automatique des clés pour sécuriser les sessions.

Architecture du projet

    Keycloak :
        Fournit l'authentification OpenID Connect.
        Gère les utilisateurs et les rôles.
        Génère les tokens (ID Token et Access Token).

    MySQL :
        Stocke les informations utilisateur, les rôles, et les clés d'authentification.

    PHP 5.6 :
        Traitement des tokens OpenID Connect.
        Génération et validation des clés.
        Gestion des rôles et des permissions.

    Apache avec mod_auth_openidc :
        Intègre OpenID Connect dans le serveur web.
        Transmet les tokens OpenID à PHP via les en-têtes HTTP.

Flux utilisateur

    Connexion :
        L'utilisateur arrive sur la page de connexion : http://site.local:8000/login.php.
        Il clique sur "Se connecter avec OpenID", ce qui le redirige vers Keycloak.

    Authentification via Keycloak :
        L'utilisateur s'authentifie (email/mot de passe).
        Keycloak redirige l'utilisateur vers l'application avec un ID Token.

    Génération de la clé :
        Le serveur PHP :
            Vérifie le ID Token.
            Génère une clé unique (auth_key) et définit une expiration basée sur le token Keycloak.
            Stocke ces informations dans la base MySQL.

    Accès aux pages protégées :
        Pour accéder à une page (comme /admin), l'utilisateur envoie sa clé dans l'en-tête HTTP (X-Auth-Key).
        PHP vérifie cette clé dans MySQL et valide le rôle de l'utilisateur.

    Expiration automatique :
        Si la clé a expiré, l'utilisateur doit se reconnecter via Keycloak.

Cas d'utilisation

    Accès admin protégé :
        Les administrateurs peuvent accéder à la route /admin, tandis que les utilisateurs non autorisés voient un message d'erreur.

    Connexion sécurisée multi-tenant :
        Peut être utilisé pour des applications nécessitant une gestion centralisée des identités avec Keycloak.

    Intégration simple dans un projet existant :
        Utilisation de technologies standard (Apache, PHP, MySQL) sans dépendances complexes.

Technologies utilisées

    Keycloak :
        Gestion des identités et des accès.
        OpenID Connect.

    PHP 5.6 :
        Gestion des requêtes HTTP et des sessions utilisateur.
        Décodage et validation des tokens JWT.

    MySQL :
        Stockage des utilisateurs, rôles, et clés d'authentification.

    Bootstrap 5 :
        Interface utilisateur moderne et responsive.

    Apache (mod_auth_openidc) :
        Intégration OpenID Connect au niveau du serveur web.

Points forts

    Sécurité :
        Les clés d'authentification expirent automatiquement.
        Utilisation de la signature JWT pour valider les tokens.

    Simplicité :
        Facile à intégrer dans une architecture PHP/MySQL existante.
        Pas besoin de framework PHP.

    Extensibilité :
        Supporte plusieurs rôles et permissions utilisateur.
        Peut être adapté pour des API ou des applications plus complexes.