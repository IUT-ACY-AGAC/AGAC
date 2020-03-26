import sys
import os
#sys.path.insert(0, "/home/agac/.local/lib/python3.6/site-packages")

import math

import cv2 as cv
import numpy as np

from scipy.ndimage import label
from skimage import morphology

try:
    # on suppose que les deux images ont des ratio identiques
    # et qu'elles ont la même orientation cardinale

    W = 2000  # amené à être augmenté pour ne pas perdre d'information dans des images plus grandes

    images = sys.argv[1:3]

    if not images[0] or not images[1]:
        print(images)
        print("erreur")
        exit()

    # seuils (de qualité) définissants les points de correspondance à être retenus en fonction :
    epsd = 0.1  # de la pente
    epsD = 100  # de la distance

    # fonction principale de binarisation par cartographie
    # recherche dans l'image les parties isolées minoritaires
    # les retire, pour obtenir une image binarisée avec le moins de bruit possible
    def filtre(image, coeff=0.9, seuil=0.0001, bords=0.025):
        # Binarisation selon la moyenne (multiplié par un coeff)
        matrice = image < int(coeff * np.mean(image))
        # Récupération des bords
        matrice = matrice[
            int(matrice.shape[0] * bords):int(matrice.shape[0] * (1 - bords)),
            int(matrice.shape[1] * bords):int(matrice.shape[1] * (1 - bords))
        ]
        # Nettoyage de la matrice en supprimant les points isolés
        matrice = morphology.remove_small_objects(matrice, min_size=7, connectivity=1)
        # Cartographie de la matrice
        carte = label(matrice)[0]
        # Comptage du nombre de pixel par région
        sommes = np.unique(carte, return_counts=True)[1]
        # Suppression de la région zéro (zone noire)
        sommes[0] = 0
        # Récupération de l'aire de l'image pour un calcul avec des nombres plus petit
        aire = carte.shape[0] * carte.shape[1]
        # Conserver les régions les plus grandes (significatif)
        reg_ok = sommes[(sommes / aire) > seuil]
        # Récupération des numéros de régions à conserver
        idx = [np.where(sommes==x)[0][0] for x in reg_ok]
        # Renvoi de l'image avec les régions non-ok effacée
        return np.isin(carte, idx)

    # charge les images données, binarisée ou non, les redimentionne en fonction de W
    def load(img, g=True):
        i = cv.imread(img, cv.IMREAD_GRAYSCALE if g else 0)
        h, w, *_ = i.shape
        i = np.asarray(filtre(i) * 255, dtype=np.uint8)
        scale = W / w
        # change la taille de l'image pour qu'elle fasse une largeur de W
        i = cv.resize(i, (int(w * scale), int(h * scale)))
        return i

    # charge les images
    img1 = load(images[0])
    img2 = load(images[1])

    # initialise le détecteur de fonctions
    orb = cv.ORB_create()
    # trouve les fonctions
    kp1, des1 = orb.detectAndCompute(img1, None)
    kp2, des2 = orb.detectAndCompute(img2, None)
    # initialise le moteur de correspondance
    bf = cv.BFMatcher(cv.NORM_HAMMING, crossCheck=True)
    # trouve les correspondances
    matches = bf.match(des1, des2)
    # les trie par score de pertinence
    matches = sorted(matches, key=lambda x: x.distance)

    # calcule la pente entre deux points
    def diff(p1, p2):
        dx = W + p2[0] - p1[0]
        dy = p2[1] - p1[1]
        return dy / dx

    # calcule la distance entre deux points de l'objet de correspondance match
    def pdist(m):
        k1 = kp1[m.queryIdx].pt
        k2 = kp2[m.trainIdx].pt
        return math.hypot(k2[0] - k1[0], k2[1] - k1[1])

    mf = []  # tableau contenant les correspondances et leur pentes

    # calcule les correspondances des points de matches et leur pentes
    for m in matches:
        k1 = kp1[m.queryIdx].pt
        k2 = kp2[m.trainIdx].pt
        dif = diff(k1, k2)
        mf.append((m, dif))

    avgd = np.median([d for m, d in mf])  # mediane de la pente des points
    avgl = np.average([m.distance for m, d in mf])  # moyenne du "score" de correspondance des points
    avgD = np.median([pdist(m) for m, d in mf])  # médiane de la distance de chaque point deux à deux

    # filtrage par pente
    final1 = [(m, d) for m, d in mf if abs(d - avgd) < epsd]

    # filtrage par score
    final2 = [(m, d) for m, d in final1 if m.distance < avgl]

    # filtrage par écart relatif
    final = [(m, d) for m, d in final2 if pdist(m) < epsD]

    final = [m for m, d in final]

    # prends l'ensemble des points fiables présents sur les 2 images
    pts_src = np.array([kp1[m.queryIdx].pt for m in final]).astype(float)
    pts_dst = np.array([kp2[m.trainIdx].pt for m in final]).astype(float)

    # dit comment deformer train image pour avoir la forme de query image (status ne sera jamais utilisé par la suite)
    h, status = cv.findHomography(pts_src, pts_dst)

    # applique la matrice de transformation h sur train image
    im_dst = cv.warpPerspective(img1, h, img2.shape[::-1])

    # convertir de niveaux de gris vers couleur
    id1 = cv.cvtColor(img2, cv.COLOR_GRAY2RGB)
    id2 = cv.cvtColor(im_dst, cv.COLOR_GRAY2RGB)

    # ne garde que le rouge pour la deuxieme image
    id2[:, :, 0] = 0

    if "--debug" in sys.argv: # mode debug
        cv.imwrite("im1.png", id1)
        cv.imwrite("im2.png", id2)
    else:
        images = [
            [1, id1],
            [2, id2]
        ]

        j = []

        for img in images:
            img[1] = cv.imencode(".png", img[1])[1].tostring()

            j.append({
                    "id": img[0],
                    "len": len(img[1])
                })

        import json
        data = json.dumps(j)

        # écrit en en-tête la longueur en octets des données d'image, suivie des données elles-mêmes
        sys.stdout.buffer.write(len(data).to_bytes(4, byteorder='little'))
        sys.stdout.buffer.write(data.encode("utf-8"))

        for i, d in images:
            sys.stdout.buffer.write(d)
except:
    if "--debug" in sys.argv:
        raise
    else:
        exit()