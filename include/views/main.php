<?php

set("title", "AGAC");
set("bodyclasses", "noscroll");

function head()
{
    ?>
    <style>
        :root {
            --sidebar-width: 300px;
        }

        div.noscroll > * > * {
            padding-top: 15px;
        }

        .sidebar {
            box-shadow: inset 1px 0 0 rgba(0, 0, 0, .1);
            width: var(--sidebar-width);
            min-width: var(--sidebar-width);
            flex-grow: 0;
        }

        .form-control::placeholder {
            font-size: 0.95rem;
            color: #aaa;
            font-style: italic;
        }

        .form-control:focus {
            box-shadow: none;
        }

        .sidebar .divider {
            height: 1px;
            margin-left: -15px;
            margin-right: -16px;
            overflow: hidden;
            background-color: #e5e5e5;
        }

        .algolia-autocomplete {
            flex-grow: 1;
        }

        .algolia-autocomplete .aa-hint {
            color: #999;
        }

        .algolia-autocomplete .aa-dropdown-menu {
            width: 100%;
            background-color: #fff;
            border: 1px solid #999;
            border-top: none;
        }

        .algolia-autocomplete .aa-dropdown-menu .aa-suggestion {
            cursor: pointer;
            padding: 5px 4px;
        }

        .algolia-autocomplete .aa-dropdown-menu .aa-suggestion.aa-cursor {
            background-color: #B2D7FF;
        }

        .algolia-autocomplete .aa-dropdown-menu .aa-suggestion em {
            font-weight: bold;
            font-style: normal;
        }

        main, #initial {
            width: calc(100vw - var(--sidebar-width)) !important;
            height: var(--content-height) !important;
        }

        #loader {
            position: absolute;
            left: 50%;
            top: 50%;
            transform: translate(-50%, -50%);
        }

        .loader {
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        .lds-spinner {
            width: 80px;
            height: 80px;
        }

        .lds-spinner div {
            transform-origin: 40px 40px;
            animation: lds-spinner 1.2s linear infinite;
        }

        .lds-spinner div:after {
            content: " ";
            display: block;
            position: absolute;
            top: 3px;
            left: 37px;
            width: 6px;
            height: 18px;
            border-radius: 20%;
            background: #000;
        }

        .lds-spinner div:nth-child(1) {
            transform: rotate(0deg);
            animation-delay: -1.1s;
        }

        .lds-spinner div:nth-child(2) {
            transform: rotate(30deg);
            animation-delay: -1s;
        }

        .lds-spinner div:nth-child(3) {
            transform: rotate(60deg);
            animation-delay: -0.9s;
        }

        .lds-spinner div:nth-child(4) {
            transform: rotate(90deg);
            animation-delay: -0.8s;
        }

        .lds-spinner div:nth-child(5) {
            transform: rotate(120deg);
            animation-delay: -0.7s;
        }

        .lds-spinner div:nth-child(6) {
            transform: rotate(150deg);
            animation-delay: -0.6s;
        }

        .lds-spinner div:nth-child(7) {
            transform: rotate(180deg);
            animation-delay: -0.5s;
        }

        .lds-spinner div:nth-child(8) {
            transform: rotate(210deg);
            animation-delay: -0.4s;
        }

        .lds-spinner div:nth-child(9) {
            transform: rotate(240deg);
            animation-delay: -0.3s;
        }

        .lds-spinner div:nth-child(10) {
            transform: rotate(270deg);
            animation-delay: -0.2s;
        }

        .lds-spinner div:nth-child(11) {
            transform: rotate(300deg);
            animation-delay: -0.1s;
        }

        .lds-spinner div:nth-child(12) {
            transform: rotate(330deg);
            animation-delay: 0s;
        }

        @keyframes lds-spinner {
            0% {
                opacity: 1;
            }
            100% {
                opacity: 0;
            }
        }

        :root {
            --initial-color: #888;
            --initial-radius: 10px;
        }

        #initial > div {
            position: absolute;
        }

        #initial_h {
            height: 2px;
            left: calc(50% + var(--initial-radius));
            width: calc(50% - var(--initial-radius) - 15px - 3px);
            top: calc(15px + 46px / 2);
            background-color: var(--initial-color);
            transform: translate(-1px, -1px);
        }

        #initial_rnd {
            width: var(--initial-radius);
            height: var(--initial-radius);
            border-top-left-radius: 6px;
            border: 2px solid var(--initial-color);
            top: calc(15px + 46px / 2);
            left: 50%;
            border-right: 0;
            border-bottom: 0;
            transform: translate(-1px, -1px);
        }

        #initial_v {
            left: 50%;
            top: calc(15px + 46px / 2 + var(--initial-radius));
            width: 2px;
            height: calc(30% - (15px + 46px / 2 + var(--initial-radius)));
            background-color: var(--initial-color);
            transform: translate(-1px, -1px);
        }

        #initial_msg {
            top: 30%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: fit-content;
            background-color: white;
            padding: 6px;
            color: var(--initial-color);
        }

        #initial_arrow {
            width: 0;
            height: 0;
            border-top: 6px solid transparent;
            border-bottom: 6px solid transparent;
            border-left: 6px solid var(--initial-color);
            right: 15px;
            top: calc(15px + 46px / 2);
            transform: translate(0, -50%);
        }

        .cadastre {
            padding-top: 0.75em;
            padding-bottom: 0.0em;
            display: flex;
            flex-direction: column;
        }

        .cadastre > div {
            padding-bottom: 0.5rem;
        }

        #cadastres {
            margin-left: -1rem;
            margin-right: -1rem;
            padding-left: 1rem;
            padding-right: 1rem;
            overflow-y: scroll;
            max-height: calc(100vh - 70px - 48px - 61px - 66px - 1rem);
        }
    </style>
    <?php
}

function foot()
{
    ?>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/autocomplete.js/0.37.0/autocomplete.jquery.min.js"
            integrity="sha256-AEeiDMvWyxSUajsdRg7FG0WsK2aQp6WwnpPXcK898d8=" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/fabric.js/3.5.0/fabric.min.js"
            integrity="sha256-8EOeE9K9px4vVwOtEAV/kb/NoZK4xLDy4iAj5eZlg3A=" crossorigin="anonymous"></script>
    <script>
        $(document).ready(function () {
            let initial = $("#initial");
            let loaderSide = $("#loaderSide");
            loaderSide.hide();
            let loaderModal = $("#loaderModal");
            loaderModal.hide();
            let loader = $("#loader");
            loader.hide();

            $('#txtVille').autocomplete({hint: false, debug: true, openOnFocus: true, minLength: 0}, [
                {
                    source: function (query, callback) {
                        $.get("recherche", {
                            "q": query
                        }, callback);
                    },
                    displayKey: "nomlieu"
                }
            ]).on('autocomplete:selected', function (event, suggestion, dataset, context) {
                chargerVille(suggestion.idlieu);
            }).blur(function () {
                $('#txtVille').autocomplete('close');
            });

            let fab = new fabric.Canvas("canvas");

            fab.on('mouse:wheel', function (opt) {
                let delta = opt.e.deltaY;
                let zoom = fab.getZoom();
                zoom = zoom - delta / 400;
                //if (zoom > 20) zoom = 20;
                //if (zoom < 0.01) zoom = 0.01;
                fab.zoomToPoint(opt.pointer, zoom);
                opt.e.preventDefault();
                opt.e.stopPropagation();
            });

            function chargerVille(id) {
                initial.hide();
                fab.clear();

                loaderSide.show();

                $.ajax({
                    url: "cadastres/liste",
                    method: "GET",
                    data: {
                        "id": id
                    },
                    success: function (response) {
                        loaderSide.hide();
                        chargerCadastres(response);
                    }
                });
            }

            function chargerCad(id1, id2) {
                initial.hide();
                fab.clear();
                fab.setViewportTransform([1,0,0,1,0,0]);

                $('#lblTitreModal').html("Chargement...");
                $('#lblTexteModal').html("");
                $("#modalFooter").hide();
                loaderModal.show();
                for(let item of items) {
                    desactiver(item);
                    item[2].prop("checked", false);
                    item[4].val(50);
                }
                $.ajax({
                    url: "exec",
                    method: "GET",
                    data: {
                        "id2": id1[0],
                        "id1": id2[0]
                    },
                    xhrFields: {
                        responseType: 'blob'
                    },
                    success: function (response) {
                        response.arrayBuffer().then(function (data) {
                            loader.hide();
                            let view = new DataView(data, 0);
                            let pos = 0;
                            let headerLen = view.getUint32(0, true);
                            pos += 4;
                            let header = JSON.parse(new TextDecoder("utf-8").decode(data.slice(pos, pos += headerLen)));
                            let cur = 0;
                            for (let img of header) {
                                let imgData = response.slice(pos, pos += img["len"]);
                                let url = URL.createObjectURL(imgData);
                                let item = [id1, id2][cur];
                                fabric.Image.fromURL(url, function (img) {
                                    fab.add(item[5] = img.scale($("main").width() / 2000.0 / 2)
                                        .set({
                                            left: $("main").width() / 4,
                                            top: 10,
                                            opacity: 0.5
                                        }));
                                    fab.centerObject(item[5]);
                                });
                                activer(item);
                                item[2].prop("checked", true);
                                cur++;
                            }

                            $('#modal').modal('hide');
                        });
                    },
                    error: function () {
                        $("#modalFooter").show();
                        $('#lblTitreModal').html("Erreur");
                        $('#lblTexteModal').html(
                            "Une erreur est survenue lors du traitement des images.<br/>" +
                            "Cela indique généralement une trop grande différences entre les images.<br/><br/>" +
                            "Si le problème persiste, contactez le groupe 13.");
                        loaderModal.hide();
                    }
                });
            }

            $(window).resize(function (e) {
                fab.setWidth($("main").width());
                fab.setHeight($("main").height());
                fab.calcOffset();
            }).trigger("resize");

            let cads = $("#cadastres");

            let items = [];

            function activer(item) {
                item[2].attr("disabled", false);
                item[4].attr("disabled", false);
            }

            function desactiver(item) {
                item[2].attr("disabled", true);
                item[4].attr("disabled", true);
            }

            function chargerCadastres(dat) {
                cads.empty();

                items = [];

                for (let d of dat) {
                    let current = items.length;
                    let item = $($("#cadastre").html());

                    let check = item
                        .find(".affichage")
                        .find("input")
                        .attr("id", "aff" + d.idcadastre)
                        .change(function () {
                            items[current][5].visible = $(this).is(":checked");
                            fab.renderAll();
                        });
                    item.find(".affichage").find("label").attr("for", check.attr("id"));

                    let check2 = item.find(".activation").find("input");
                    check2.attr("id", "act" + d.idcadastre);
                    check2.change(function () {
                        //($(this).is(':checked') ? activer : desactiver)(current);
                    });

                    item.find(".activation").find("label").attr("for", check2.attr("id")).text(d["titrecadastre"]);

                    let slider = item
                        .find("input[type='range']")
                        .change(function() {
                            items[current][5].opacity = $(this).val() / 100.0;
                            fab.renderAll();
                        });

                    cads.append(item);

                    cads.append($("<div></div>").addClass("divider"));

                    let apercu = item.find("button");

                    apercu.click(function() {
                        $("#apercuImg").attr("src", "<?=url("/cadastres/voir")?>?id=" + d["idcadastre"]);
                        $("#apercu").modal();
                    });

                    items.push([d["idcadastre"], item, check, check2, slider, null, apercu]);

                    desactiver(items[items.length - 1]);
                }
            }

            function alertModal(title, body) {
                $('#lblTitreModal').html(title);
                $('#lblTexteModal').html(body);
                loaderModal.hide();
                $("#modalFooter").show();
                $('#modal').modal('show');
            }

            function erreurCad() {
                alertModal("Erreur", "Vous devez sélectionnez exactement 2 cadastres.");
                return;
            }

            $("#btnAssoc").click(function () {
                $('#lblTitreModal').html("Chargement...");
                $('#lblTexteModal').html();
                loaderModal.show();
                $("#modalFooter").hide();
                $('#modal').modal('show');

                let cad1 = null;
                let cad2 = null;
                for (let i = 0; i < items.length; i++) {
                    if (items[i][3].is(":checked")) {
                        if (cad1 === null)
                            cad1 = items[i];
                        else if (cad2 === null)
                            cad2 = items[i];
                        else {
                            erreurCad();
                            return;
                        }
                    }
                }

                if (cad1 === null || cad2 === null) {
                    erreurCad();
                    return;
                }

                chargerCad(cad1, cad2);
            });
        });
    </script>
    <?php
}

function content()
{
    ?>
    <div class="container-fluid noscroll" style="padding-top: 0">
        <div class="row h-100">
            <main class="col ml-sm-auto p-0 flex-grow-1">
                <div id="initial">
                    <div id="initial_h"></div>
                    <div id="initial_rnd"></div>
                    <div id="initial_v"></div>
                    <div id="initial_arrow"></div>
                    <div id="initial_msg">
                        Pour commencer, choisissez une ville
                    </div>
                </div>
                <div id="loader" class="loader">
                    <div class="lds-spinner">
                        <div></div>
                        <div></div>
                        <div></div>
                        <div></div>
                        <div></div>
                        <div></div>
                        <div></div>
                        <div></div>
                        <div></div>
                        <div></div>
                        <div></div>
                        <div></div>
                    </div>
                    <div class="mt-4 text-muted">
                        Chargement en cours
                    </div>
                </div>
                <canvas id="canvas" class="w-100 h-100"></canvas>
            </main>
            <nav class="col d-md-block bg-light sidebar px-3">
                <div class="p-1 bg-white rounded rounded-pill shadow-sm mb-3">
                    <div class="input-group ml-1 pr-1 d-flex">
                        <input type="search" placeholder="Ville" id="txtVille"
                               class="form-control border-0 bg-white pr-0">
                        <div class="input-group-append">
                            <button id="button-addon1" type="submit" class="btn btn-link text-primary"><i
                                        class="fa fa-search"></i></button>
                        </div>
                    </div>
                </div>
                <div class="divider"></div>
                <div class="py-3">
                    <button class="btn btn-primary mr-2 w-100" id="btnAssoc"><i
                                class="fa fa-cogs mr-2" aria-hidden="true"
                                title="Associer"></i>Associer
                    </button>
                </div>
                <div class="divider"></div>
                <div id="cadastres"></div>
                <template id="cadastre">
                    <div class="cadastre">
                        <div class="custom-control custom-checkbox activation">
                            <input type="checkbox" class="custom-control-input">
                            <label class="custom-control-label w-100"></label>
                        </div>
                        <table class="mb-3">
                            <tr>
                                <td rowspan="2">
                                    <button class="btn btn-secondary btn-cadastre-apercu"><i
                                                class="fa fa-search" aria-hidden="true" title="Aperçu"></i>
                                    </button>
                                </td>
                                <td>
                                    <label class="mb-0 text-muted" style="font-size: 80%">Opacité</label>
                                </td>
                                <td>
                                    <input type="range" class="custom-range" min="0" max="100" step="1">
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <label class="mb-0 text-muted" style="font-size: 80%">Afficher</label>
                                </td>
                                <td>
                                    <div class="custom-control custom-checkbox affichage">
                                        <input type="checkbox" class="custom-control-input">
                                        <label class="mb-0 custom-control-label"></label>
                                    </div>
                                </td>
                            </tr>
                        </table>
                    </div>
                </template>
                <div class="loader" id="loaderSide">
                    <div class="lds-spinner">
                        <div></div>
                        <div></div>
                        <div></div>
                        <div></div>
                        <div></div>
                        <div></div>
                        <div></div>
                        <div></div>
                        <div></div>
                        <div></div>
                        <div></div>
                        <div></div>
                    </div>
                    <div class="mt-4">
                        Chargement en cours
                    </div>
                </div>
            </nav>
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

    <div class="modal fade" id="modal" tabindex="-1" role="dialog" aria-labelledby="lblTitreModal" aria-hidden="true"
         data-keyboard="false" data-backdrop="static">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="lblTitreModal"></h5>
                </div>
                <div class="modal-body">
                    <div id="lblTexteModal">

                    </div>
                    <div class="loader" id="loaderModal">
                        <div class="lds-spinner">
                            <div></div>
                            <div></div>
                            <div></div>
                            <div></div>
                            <div></div>
                            <div></div>
                            <div></div>
                            <div></div>
                            <div></div>
                            <div></div>
                            <div></div>
                            <div></div>
                        </div>
                        <div class="mt-4 text-muted">
                            Chargement en cours
                        </div>
                    </div>
                </div>
                <div class="modal-footer" id="modalFooter">
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Fermer</button>
                </div>
            </div>
        </div>
    </div>
    <?php
}
