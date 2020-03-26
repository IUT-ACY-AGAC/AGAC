<?php

set("title", "Connexion");

function content()
{
    ?>
    <div class="container-fluid" style="padding-top: 20px; padding-bottom: 20px">
        <div class="w-100">
            <div class="card mx-auto text-center" style="width: 450px">
                <div class="card-header"><h4 class="mb-0">Connexion</h4></div>

                <div class="card-body">
                    <form method="POST" action="<?= url("/connexion") ?>"
                          enctype="multipart/form-data">
                        <div class="form-group">
                            <input id="login" type="text" class="form-control" name="user" placeholder="Identifiant"
                                   value="" required autofocus>
                        </div>

                        <div class="form-group">
                            <input id="password" type="password" class="form-control" name="password" required
                                   placeholder="Mot de passe">
                        </div>

                        <?php if (get_flash("login_err") === "incorrect"): ?>
                            <div class="alert alert-danger" role="alert">
                                <span class="alertContent">L'identifiant ou le mot de passe est incorrect.</span>
                            </div>
                        <?php endif; ?>

                        <div class="form-group mb-0">
                            <button type="submit" class="btn btn-primary">
                                Connexion
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <?php
}