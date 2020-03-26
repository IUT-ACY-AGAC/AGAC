<?php

set("title", "Lieux");

function head()
{
    ?>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.6.0/leaflet.css"
          integrity="sha256-SHMGCYmST46SoyGgo4YR/9AlK1vf3ff84Aq9yK4hdqM=" crossorigin="anonymous"/>
    <style>
        #map {
            width: 100%;
            height: 300px;
        }
    </style>
    <?php
}

function foot()
{
    ?>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.6.0/leaflet.js"
            integrity="sha256-fNoRrwkP2GuYPbNSJmMJOCyfRB2DhPQe0rGTgzRsyso=" crossorigin="anonymous"></script>
    <script>
        $(document).ready(function () {
            var map = L.map('map').setView([48.833, 2.333], 5);

            var osmLayer = L.tileLayer('http://{s}.tile.osm.org/{z}/{x}/{y}.png', {
                attribution: '© OpenStreetMap contributors',
                maxZoom: 19
            });

            map.addLayer(osmLayer);

            map.marker = null;

            function loadGeocode(data) {
                if (data["features"].length > 0) {
                    if (map.marker == null) {
                        map.marker = L.marker([0, 0]).addTo(map);
                    }

                    map.marker.bindPopup(data.features[0].properties.label);

                    var loc = data.features[0].geometry.coordinates;
                    var ll = L.latLng(loc[1], loc[0]);
                    map.marker.setLatLng(ll);
                    map.setView(ll, 11);

                    $("#nom").val(data.features[0].properties.label);
                    $("#loc").val(data.features[0].properties.context);

                    validate();
                }
            }

            function validate() {
                $("#btnSubmit").text('<?=has("modif")?"Modifier":"Ajouter"?> "' + $("#nom").val() + '"');
            }

            $("#nom").change(validate).keyup(validate);
            $("#loc").change(validate).keyup(validate);

            $("#btnSearch").click(function () {
                $.get("https://api-adresse.data.gouv.fr/search/", {
                    "q": $("#search").val(),
                    "limit": 1,
                    "autocomplete": 1,
                    "lat": map.getCenter().lat,
                    "lon": map.getCenter().lng
                }, function (data) {
                    loadGeocode(data);
                });
            });

            $("#search").keypress(function (e) {
                if (e.keyCode === 13) {
                    $("#btnSearch").click();
                }
            });

            let supprId = null;

            $("#btnConfSuppr").click(function() {
                if (supprId === null)
                    return;

                $.post("<?=url("/lieux/supprimer")?>", {
                    "id": supprId
                }, function() {
                    window.location = "<?=url("/lieux")?>";
                });
            });

            $(".btn-supprimer").click(function() {
                supprId = $(this).data("id");
                $("#confirmation").modal();
            })
        });
    </script>
    <?php
}

function content()
{
    ?>
    <div class="container-fluid" style="padding-top: 20px; padding-bottom: 65px">
        <div class="row">
            <div class="col-auto">
                <div class='card bg-light' style="min-width: 500px;">
                    <div class="card-header">
                        <h4 class="mb-0"><?=has("modif") ? "Modifier" : "Ajouter"?> un lieu</h4>
                    </div>
                    <div class="card-body">
                        <?php if (get_flash("lieux_ajouter_err") === "upload"): ?>
                                <div class="alert alert-danger" role="alert">
                                    <span class="alertContent">Une erreur interne s'est produite lors de l'ajout.</span>
                                </div>
                            <?php endif; ?>

                            <?php if (get_flash("lieux_ajouter_err") === "succes"): ?>
                                <div class="alert alert-success" role="alert">
                                    <span class="alertContent">L'opération a réussi.</span>
                                </div>
                            <?php endif; ?>
                        <div class="form-row form-group">
                            <div class="col">
                                <input id="search" type="search" class="form-control"/></div>
                            <div class="col-auto">
                                <button id="btnSearch" class="btn btn-primary"><i class="fa fa-search"
                                                                                  aria-hidden="true"></i>
                                </button>
                            </div>
                        </div>
                        <div class="form-group">
                            <div id="map"></div>
                        </div>
                        <form action="<?= url("/lieux/ajouter") ?>" method="post">
                                                        <div class="form-row">
                                <div class="col">
                                    <div class="form-group">
                                        <label for="nom">Nom</label>
                                        <input id="nom" type="text"
                                               class="form-control <?= get_flash("lieux_ajouter_err") === "doublon" ? "is-invalid" : "" ?>"
                                               name="nom" maxlength="100" required
                                value="<?=has("modif")?htmlspecialchars(get("modif")->nomlieu): ""?>">
                                    </div>
                                </div>
                                <div class="col">
                                    <div class="form-group">
                                        <label for="loc">Localisation</label>
                                        <input id="loc" type="text"
                                               class="form-control"
                                               name="loc" maxlength="100" required
                                value="<?=has("modif")?htmlspecialchars(get("modif")->localisation): ""?>">
                                    </div>
                                </div>
                            </div>

                            <?php if (get_flash("lieux_ajouter_err") === "doublon"): ?>
                                <div class="alert alert-danger" role="alert">
                                    <span class="alertContent">Un lieu du même nom existe déjà.</span>
                                </div>
                            <?php endif; ?>

                            <?php if (has("modif")): ?>
                            <input type="hidden" name="modif" value="<?=get("modif")->idlieu?>" />
                            <?php endif; ?>

                            <button id="btnSubmit" type="submit" class="btn btn-primary btn-block"><i
                                        class="mr-1 fas fa-<?=has("modif") ? "pencil-alt" : "plus"?>"></i> <?=has("modif")?"Modifier":"Ajouter"?>
                            </button>
                        </form>

                        <?php if (has("modif")): ?>
                            <form action="<?=url("/lieux")?>">
                                <button type="submit" class="btn btn-outline-secondary btn-block mt-2"><i
                                            class="mr-1 fas fa-door-open"></i> Abandonner les modifications
                                </button>
                            </form>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            <div class="col">
                <h2 class="mb-3">Liste des lieux</h2>
                <?php if (empty(get("lieux"))): ?>
                    <h5 class="mt-3">Vous n'avez enregistré aucun point relais.</h5>
                <?php else: ?>
                    <ul class="list-group">
                        <?php foreach (get("lieux") as $l): ?>
                            <li class="list-group-item">
                                <div class="row">
                                    <div class="col-auto d-inline-flex display-flex flex-row pr-0">
                                        <button data-id="<?=$l->idlieu?>" type="submit" class="btn-supprimer btn btn-danger mr-2" style="height: min-content"><i
                                                        class="fa fa-times" aria-hidden="true" title="Supprimer"></i></button>
                                    <a href="<?= url("/lieux") ?>?modif=<?= $l->idlieu ?>">
                                                    <button type="submit"
                                                            class="btn btn-warning mr-2"><i
                                                                class="fa fa-pencil-alt text-white" aria-hidden="true"
                                                                title="Modifier"></i></button>
                                                </a>
                                    </div>
                                    <div class="col">
                                        <h6><?= $l->nomlieu ?></h6>
                                        <?= $l->localisation ?>
                                    </div>
                                </div>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                <?php endif; ?>
            </div>
        </div>
    </div>
    <div class="modal fade" id="confirmation" tabindex="-1" role="dialog" aria-labelledby="lblTitreConf" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="lblTitreConf">Confirmer la suppression ?</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Fermer">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    Cette action est irréversible.
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Annuler</button>
                    <button type="button" class="btn btn-danger" id="btnConfSuppr">Supprimer</button>
                </div>
            </div>
        </div>
    </div>
    <?php
}