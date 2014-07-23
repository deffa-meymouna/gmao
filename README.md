Application IP-Trevise
====

Cette application se base sur le noyau de [l'Application Blanche]
(https://github.com/Alexandre-T/gmao/tree/blanche).

Elle a pour objectif de :
1. Gérer les sous-réseaux
2. Gérer les adresses IP libres, utilisées et réservées
3. Gérer les machines associées à ces IP

Installation
----
1. Téléchargez l'application : 
https://github.com/Alexandre-T/gmao/archive/ip-trevise.zip
2. Décompressez l'archive dans le répertoire souhaité ```/var/www/blanche```
3. Configurez un hôte virtuel Apache pointant sur le répertoire.
Le répertoire docs contient un exemple d'hôte virtuel pour Apache 2.4
4. Installez les modules nécessaires 
```$ php composer.phar install```
5. Configurez les modules nécessaires en éditant les fichiers du répertoire 
config/autoload :
  1. Configurez le module _DoctrineOrm_  :
    1. Editez le fichier doctrineorm.local.php.dist
    2. Remplissez les informations de configuration
    3. Sauvegardez le fichier sous doctrineorm.local.php
  2. Configurez le module _Cerbere_  :
    1. Editez le fichier cerbere.local.php.dist
    2. Remplissez les informations de configuration
    3. Sauvegardez le fichier sous cerbere.local.php
  3. Configurez la connexion à __Mantis__  :
    1. Editez le fichier local.php.dist
    2. Remplissez les informations de configuration à Mantis
    3. Sauvegardez le fichier sous local.php
        
