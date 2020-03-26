# AGAC – Application Géomatique d'Archives Cadastrales

Projet de seconde année de DUT Informatique

- Ulysse Delahaye
- Gwendal Lefort
- Luc Moiraud
- Aurélien Régy
- Emmeline Vouriot

## Installation

AGAC requiert les paquets PIP suivants :
 - opencv-python
 - scipy
 - scikit-image

À installer via `pip3 install nomdupaquet` au préalable.

Un serveur Apache est à utiliser de préférence pour la réécriture des URL. Nginx et autres fonctionneront mais il faudra configurer à la main.

PHP est requis, version >= 7.2.

Postgres est requis, les paramètres de connexion à la base sont à inscrire dans include/db/conn.php (voir le fichier conn.php.example dans le même dossier).
