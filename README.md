Application blanche
====

Cette branche a pour objectif de donner naissance à une application blanche 
telle Zend Skeleton mais intégrant plusieurs fonctionnalités complémentaires :
  * La connexion via ZfcUser (Fait)
  * La gestion de son profil utilisateur depuis l'interface utilisateur ZfcUSer (A faire)
  * La gestion des utilisateurs depuis l'interface administrateur ZfcUSer (Fait)
  * La gestion des rôles depuis l'interface administrateur (En cours)
  * La gestion des rôles de chaque utilisateur (A faire)
  * La gestion des ressources et des rôles nécessaires depuis les fichiers de configuration (Fait)
  * La gestion des ressources et des rôles nécessaires depuis la base de données (A faire)
  * La mise en cache des ressources et des rôles nécessaires pour accélérer les traitements (A faire)
  * Un contrôleur Index avec quelques actions de démonstration (Fait)
  * Le paramétrage Mantis vers les bugs recensés et les demandes d'évolutions en cours (A valider)
  * Un plan de tests complet (en cours)
  * La gestion des bannissements (à faire)
  * La mise en place de travis (À tester et à mettre en oeuvre)

Installation
----
1. Téléchargez l'application : 
https://github.com/Alexandre-T/gmao/archive/blanche-zfc.zip
2. Décompressez l'archive dans le répertoire souhaité ```/var/www/blanche-zfc```
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
  3. Configurez la connexion à __Mantis__  :
    1. Editez le fichier local.php.dist
    2. Remplissez les informations de configuration à Mantis
    3. Sauvegardez le fichier sous local.php
        
Informations
----
La branche de développement est : blanche-zfc

Je recherche de l'aide pour :
 * La mise en place de travis
 * Les traductions (je dois tout repasser en anglais et créer les fichiers de traduction française)
