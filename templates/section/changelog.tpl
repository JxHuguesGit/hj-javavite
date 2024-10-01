    <section class="file-panel row col-12 col-lg-8 offset-lg-2 my-3">
        <form method="post" action= "/" class="col-4 offset-1" enctype="multipart/form-data">
            <section class="file-panel col-12">
                <div class="mb-3">
                    <input class="form-control" type="file" id="formFile" name="formFile">
                </div>
                <input type="hidden" name="formAction" value="upload"/>
                <button class="form-control mt-3 bg-info" type="submit" value="">Téléverser</button>
            </section>
        </form>
        <form method="post" action= "/" class="col-4 offset-1">
            <section class="file-panel col-12">
                <select class="form-select" aria-label="Choix du fichier à analyser" name="logSelection">
                    <option value='-1' selected="selected">Choisir un fichier</option>
                    %1$s
                </select>
                <input type="hidden" name="formAction" value="consult"/>
                <button class="form-control mt-3 bg-info" type="submit" value="">Consulter</button>
            </section>
        </form>
    </section>

    <section class="file-panel col-12 col-lg-4 offset-lg-4 my-3">
        <h5>Reste à faire</h5>
        <ul>
            <li>Global
                <ul>
                    <li>Gérer les abandons : Blocage</li>
                    <li>Gérer l'usage de pneus en cas de Blocage</li>
                    <li>Gérer les freins lors Blocage</li>
                </ul>
            </li>
            <li>Divers
                <ul>
                    <li>Pouvoir imprimer en PDF le compte-rendu</li>
                    <li>Pouvoir uploader un fichier de log</li>
                </ul>
            </li>
        </ul>
    </section>

    <section class="file-panel col-12 col-lg-4 offset-lg-4 my-3">
        <h5>Change log v 0.2</h5>
        <ul>
            <li>Gérer la rétrogradation 3 rapports</li>
        </ul>
    </section>

    <section class="file-panel col-12 col-lg-4 offset-lg-4 my-3">
        <h5>Change log v 0.1</h5>
        <ul>
            <li>Mettre à jour la position de départ du pilote qui hoste</li>
            <li>Décompter les freins lors d'aspirations</li>
            <li>Gérer les abandons : Carrosserie, Moteur, Pneus</li>
            <li>Gérer les annulations de frein</li>
            <li>Gérer les tête à queue</li>
            <li>Ne pas tenir compte des déplacements lors des arrêts rapides</li>
            <li>Gérer les aspirations</li>
            <li>Ne pas tenir compte des déplacements lors des aspirations</li>
            <li>Traiter les panneaux individuels</li>
        </ul>
    </section>
