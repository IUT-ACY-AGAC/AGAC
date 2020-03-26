<?php

set("title", "Cadastres");

function head()
{
    ?>
    <?php
}

function foot()
{
    ?>
    <script>
        $(document).ready(function () {
            $(".btn-cadastre-apercu").click(function () {
                $("#apercuImg").attr("src", "<?=url("/cadastres/voir")?>?id=" + $(this).data("id"));
                $("#apercu").modal();
            });

            let supprId = null;

            $("#btnConfSuppr").click(function () {
                if (supprId === null)
                    return;

                $.post("<?=url("/cadastres/supprimer")?>", {
                    "id": supprId
                }, function () {
                    window.location = "<?=url("/cadastres")?>";
                });
            });

            $(".btn-supprimer").click(function () {
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
                <div class='card bg-light' style="width: 400px;">
                    <div class="card-header">
                        <h4 class="mb-0"><?=has("modif") ? "Modifier" : "Ajouter"?> un cadastre</h4>
                    </div>
                    <div class="card-body">
                        <form action="<?= url("/cadastres/ajouter") ?>" method="post" enctype="multipart/form-data">
                            <?php if (get_flash("cadastres_ajouter_err") === "upload"): ?>
                                <div class="alert alert-danger" role="alert">
                                    <span class="alertContent">Une erreur interne s'est produite lors de l'ajout.</span>
                                </div>
                            <?php endif; ?>

                            <?php if (get_flash("cadastres_ajouter_err") === "succes"): ?>
                                <div class="alert alert-success" role="alert">
                                    <span class="alertContent">L'opération a réussi.</span>
                                </div>
                            <?php endif; ?>

                            <div class="form-row">
                                <div class="col">
                                    <div class="form-group">
                                        <label for="loc">Lieu</label>
                                        <select name="loc" id="loc" class="form-control" required>
                                            <option value="" selected disabled></option>
                                            <?php foreach (get("lieux") as $l): ?>
                                                <option <?=has("modif") && get("modif")->idlieu==$l->idlieu ? "selected" : ""?> value="<?= $l->idlieu ?>"><?= $l->nomlieu ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="col">
                                    <div class="form-group mb-2" style="width:185.5px">
                                        <label for="upload1">Image</label>
                                        <div class="custom-file text-left">
                                            <input type="file" class="custom-file-input" id="upload1"
                                                   name="1"
                                                   accept=".jpg,.jpeg,.gif,.png,.tif,.tiff" <?=has("modif")?"":"required"?>>
                                            <label id="upload1" class="custom-file-label"><span></span></label>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="nom">Nom</label>
                                <input id="nom" type="text"
                                       class="form-control <?= get_flash("cadastres_ajouter_err") === "doublon" ?
                                           "is-invalid" : "" ?>"
                                       name="nom" maxlength="100" required
                                        value="<?=has("modif")?htmlspecialchars(get("modif")->titrecadastre): ""?>">
                            </div>

                            <div class="form-group">
                                <label for="legende">Légende</label>
                                <input id="legende" type="text"
                                       class="form-control"
                                       name="legende" maxlength="200"
                                       value="<?=has("modif")?htmlspecialchars(get("modif")->legendecadastre): ""?>">
                            </div>

                            <div class="form-group">
                                <label for="description">Description</label>
                                <textarea id="description"
                                          class="form-control"
                                          name="description"
                                          rows="3"><?=has("modif")?htmlspecialchars(get("modif")->descriptioncadastre): ""?></textarea>
                            </div>

                            <?php if (get_flash("cadastres_ajouter_err") === "doublon"): ?>
                                <div class="alert alert-danger" role="alert">
                                    <span class="alertContent">Un cadastre du même nom existe déjà.</span>
                                </div>
                            <?php endif; ?>

                            <?php if (has("modif")): ?>
                            <input type="hidden" name="modif" value="<?=get("modif")->idcadastre?>" />
                            <?php endif; ?>

                            <button type="submit" class="btn btn-primary btn-block"><i
                                        class="mr-1 fas fa-<?=has("modif") ? "pencil-alt" : "plus"?>"></i> <?=has("modif")?"Modifier":"Ajouter"?>
                            </button>
                        </form>

                        <?php if (has("modif")): ?>
                            <form action="<?=url("/cadastres")?>">
                                <button type="submit" class="btn btn-outline-secondary btn-block mt-2"><i
                                            class="mr-1 fas fa-door-open"></i> Abandonner les modifications
                                </button>
                            </form>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            <div class="col">
                <h2 class="mb-3">Liste des cadastres</h2>
                <?php if (empty(get("cadastres"))): ?>
                    <h5 class="mt-3">Vous n'avez enregistré aucun cadastre.</h5>
                <?php else: ?>
                    <?php
                    $groups = [];
                    foreach (get("cadastres") as $c)
                        $groups[$c->idlieu][] = $c;

                    foreach ($groups as $idlieu => $cadastres)
                    {
                        $lieu = Lieu::retrieveByPK($idlieu);
                        ?>
                        <div class="card mb-3">
                            <div class="card-header card-hf-divided">
                                <div class="card-hf-column">
                                    <strong><?= $lieu->nomlieu ?></strong>
                                </div>
                                <div class="card-hf-column">
                                    <?= $lieu->localisation ?>
                                </div>
                            </div>
                            <div class="card-body card-body-row pb-3 pt-1">
                                <table class="table table-hover table-thin-padding table-no-border mb-0">
                                    <thead>
                                    <tr>
                                        <th></th>
                                        <th>Titre</th>
                                        <th>Légende</th>
                                        <th>Description</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php foreach ($cadastres as $c): ?>
                                        <tr>
                                            <td class="col-auto flex-shrink-1 d-inline-flex display-flex flex-row">
                                                <button data-id="<?= $c->idcadastre ?>" type="submit"
                                                        class="btn-supprimer btn btn-danger mr-2"><i
                                                            class="fa fa-times" aria-hidden="true"
                                                            title="Supprimer"></i></button>
                                                <a href="<?= url("/cadastres") ?>?modif=<?= $c->idcadastre ?>">
                                                    <button type="submit"
                                                            class="btn btn-warning mr-2"><i
                                                                class="fa fa-pencil-alt text-white" aria-hidden="true"
                                                                title="Modifier"></i></button>
                                                </a>
                                                <button data-id="<?= $c->idcadastre ?>"
                                                        class="btn btn-secondary btn-cadastre-apercu"><i
                                                            class="fa fa-search" aria-hidden="true" title="Aperçu"></i>
                                                </button>
                                            </td>
                                            <td class="col">
                                                <?= $c->titrecadastre ?>
                                            </td>
                                            <td class="col">
                                                <?= $c->legendecadastre ?: "<em>Pas de légende</em>" ?>
                                            </td>
                                            <td class="col">
                                                <?= nl2br($c->descriptioncadastre) ?: "<em>Pas de description</em>" ?>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <?php
                    }
                    ?>
                <?php endif; ?>
            </div>
        </div>
    </div>
    <div class="modal fade" id="confirmation" tabindex="-1" role="dialog" aria-labelledby="lblTitreConf"
         aria-hidden="true">
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
    <div class="modal fade" id="apercu" tabindex="-1" role="dialog" aria-labelledby="lblTitreAper" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="lblTitreAper">Aperçu du cadastre</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Fermer">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <img id="apercuImg" src="#" alt="Aperçu" style="max-width: 100%"/>
                </div>
            </div>
        </div>
    </div>
    <?php
}