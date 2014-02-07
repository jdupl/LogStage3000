LogStage3000
============

Un générateur de site structuré en markdown pour un écrire rapidement un suivi de travail/stage journalier.

## Fonctionnement général
* Remplacer les variables dans le fichier prévu à cet effet pour avoir les bons chemins et paramètres de génération
* Utiliser le script d'init pour générer la base markdown
* Ajouter du contenu dans les fichiers markdown générés
* Générer le site à l'aide du script compiler afin de convertir en HTML le contenu du site
* Profit !

## Fonctionnalités 
* L'acceuil du site est générée automatiquement
* Chaque page est une semaine de stage (du lundi au vendredi)
* Une page sera générée pour contenir le contenu de toutes les semaines
* Intégration automatique du thème de base Bootstrap

### Outils nécessaires
* Un interpréteur PHP afin de convertir le markdown en HTML
* Un navigateur pour voir le contenu HTML généré OU
* Un serveur web qui peut servir des pages html statiques (pour rendre public le site web)


### Exemple d'un site généré
En temps normal, mon [log de stage](http://stage.jduplessis.me) devrait être à jour avec ce générateur (dès la première version fonctionnelle). 
