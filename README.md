![](assets/logo.png)

# AGAC – Application Géomatique d'Archives Cadastrales

Projet de seconde année de DUT Informatique

- Ulysse Delahaye
- Gwendal Lefort
- Luc Moiraud
- Aurélien Régy
- Emmeline Vouriot

## Installation

### Pré-requis

AGAC requiert les paquets PIP suivants :
 - opencv-python
 - scipy
 - scikit-image

À installer soit manuellement, soit en faisant `sudo pip3 install -r requirements.txt`.

Un serveur Apache est à utiliser de préférence pour la réécriture des URL. Nginx et autres fonctionneront mais il faudra configurer à la main.

PHP est requis, version >= 7.2.

Postgres est requis, les paramètres de connexion à la base sont à inscrire dans include/db/conn.php (voir le fichier conn.php.example dans le même dossier).

### Étapes

1. Télécharger le code dans un dossier accessible par le serveur web. S'assurer qu'il est accessible en écriture (chmod 777 éventuellement).
2. Renseigner les informations de connexion dans include/db/conn.php
3. Ouvrir dans le navigateur le fichier install.php
    - Si le serveur est local, l'URL ressemblera à : http://127.0.0.1/agac/install.php
4. Renseigner le mot de passe administrateur, puis lancer l'installation
5. Une fois l'installation terminée, supprimer le fichier install.php
6. Le compte administrateur est accessible avec le nom `admin` et le mot de passe renseigné durant l'installation.
