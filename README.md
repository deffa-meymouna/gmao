Application blanche
====

Cette branche a pour objectif de donner naissance à une application blanche 
telle Zend Skeleton mais intégrant plusieurs fonctionnalités complémentaires :
  * La connexion via Cerbere (Fait)
  * La gestion des utilisateurs depuis l'interface graphique (Fait)
  * La gestion des rôles depuis l'interface graphique (Fait)
  * La gestion des rôles de chaque utilisateur (Fait)
  * La gestion des ressources et des rôles nécessaires depuis les fichiers de configuration (Fait)
  * Un contrôleur Index avec quelques actions de démonstration (A faire)
  * Le paramétrage Mantis vers les bugs recensés et les demandes d'évolutions en cours (A valider)

Installation
----
1. Téléchargez l'application : 
https://github.com/Alexandre-T/gmao/archive/blanche.zip
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
        
