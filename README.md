# MyGiftBox.net

Projet de développement web réalisé dans le cadre du module d'Architecture Logicielle (BUT Informatique S4 - IUT Nancy-Charlemagne).

## Présentation

MyGiftBox.net est une application web permettant de composer et offrir des coffrets cadeaux personnalisés à partir d'un catalogue de prestations (restauration, hébergement, activités, attentions particulières...).

## Fonctionnalités principales

- Affichage des catégories et des prestations
- Consultation de prestations par catégorie
- Visualisation du détail d'une prestation
- Création et modification d'un coffret cadeau (box)
- Validation et génération d'une URL d'accès à la box
- Authentification des utilisateurs
- API JSON pour une future application mobile

## Technologies utilisées

- PHP 8
- Slim Framework 4
- Twig (moteur de templates)
- Eloquent ORM (modèle de données)
- Docker et Docker Compose

## Installation

1. Cloner le dépôt

    ```bash
    git clone https://github.com/AudinotNoah/BUT4mygiftbox.git
    cd BUT4mygiftbox
    ```

2. Installer les dépendances PHP (depuis le dossier racine contenant `composer.json`)

    ```bash
    composer install
    ```

3. Copier le fichier de configuration

    ```bash
    cp src/conf/conf.ini.example src/conf/conf.ini
    ```

    Ensuite, éditez `src/conf/conf.ini` pour renseigner vos identifiants de base de données.

4. Lancer l'application

    ```bash
    docker-compose up --build -d
    ```


5. Accéder à l'application

    ```
    http://localhost:8000
    ```


## Auteurs

Projet réalisé par :

- Noah Audinot 
- Mathys Blonbou
- Vivien Herrmann

