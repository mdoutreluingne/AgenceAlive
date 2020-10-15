# Agence alive

[![Build Status](https://travis-ci.com/mdoutreluingne/AgenceAlive.svg?branch=master)](https://travis-ci.com/mdoutreluingne/AgenceAlive)
[![GitHub license](https://img.shields.io/github/license/mdoutreluingne/AgenceAlive)](https://github.com/mdoutreluingne/AgenceAlive/blob/master/LICENSE)
[![Maintainability](https://api.codeclimate.com/v1/badges/a72f6316de51b3bbfae9/maintainability)](https://codeclimate.com/github/mdoutreluingne/AgenceAlive/maintainability)


# Instructions d'installation :
+ Clonez ou téléchargez le contenu du dépôt GitHub : git clone https://github.com/mdoutreluingne/AgenceAlive.git
+ Editez le fichier situé à la racine intitulé ".env" afin de remplacer les valeurs de paramétrage de la base de données.
+ Installez les dépendances du projet avec : composer install et yarn install
+ Créez la base de données avec la commande suivante : php bin/console doctrine:database:create
+ Lancer les migrations avec la commande : php bin/console doctrine:migrations:migrate
+ Importez ensuite le jeu de données initiales avec : php bin/console doctrine:fixtures:load
+ Afin de lancer le serveur, lancez la commande: symfony server:start
+ Bravo, c'est désormais accessible à l'adresse : localhost:8000 !
