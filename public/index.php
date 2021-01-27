<?php
/*
 * J'inclus toutes les dépendances & ressources.
 */
include_once '../src/includes/loader.php';

/*
 * J'importe de la class Pagination. (Une librairie que j'ai fait en amont)
 */
use NeutronStars\Pagination\Pagination;

/*
 * Système de pagination.
 * Je déclare une variable page dont j'assigne à 1 par défaut.
 */
$page = 1;

/*
 * Si l'utilisateur cherche à changer de page, j'aurais une clé 'page' dans la super global $_GET.
 * Je regarde si elle existe et si c'est bien un nombre numérique.
 */
if(!empty($_GET['page']) && is_numeric($_GET['page']))
{
    /*
     * Si c'est le cas alors je change ma variable $page
     */
    $page = intval($_GET['page']);
}

/*
 * J'assigne un nombre d'élément limité par page. (Pour ce cas, j'aurais 12 logements)
 */
$itemPerPage = 12;

/*
 * Je créais mon code HTML de ma pagination en fonction du nombre de logement que j'ai dans ma base de donnée.
 */
$paginationHTML = (new Pagination($page, $itemPerPage, getCount($database), [
   'previous-next' => [ 'active' => true ],
   'first-last' => [ 'active' => true ]
]))->toHTML();

/*
 * Je déclare le title qui est tout simplement mon H1 pour qu'ils soit utilisé dans mon header.php
 */
$title = 'Liste des logements';
/*
 * J'inclue mon header ainsi que le début de la page HTML.
 */
include_once '../src/includes/header.php';
?>

    <div class="container">
        <a class="btn" href="logement.php">Ajouter un logement</a>
        <?php
            /*
             * J'insère ma pagination en haut de page.
             */
            echo $paginationHTML;
        ?>
        <div class="logements">


            <?php
            /*
             * Je boucle tous mes logements stockés dans ma base de donnée en fonction de la page ou se trouve l'utilisateur
             * et du nombre de logement par page.
             */
            foreach (getLogements($database, $page, $itemPerPage) as $logement): ?>
                <div class="logement">
                    <div class="picture">

                        <?php
                        /*
                         * Si l'image existe dans la base de donnée alors je l'affiche
                         */
                        if(!empty($logement['picture'])){ ?>
                            <img src="<?=getMiniature($logement['picture'])?>" alt="<?=$logement['title']?>">
                        <?php }
                        /*
                         * Sinon j'affiche une image par défaut.
                         */
                        else { ?>
                            <img src="assets/img/logement_default.jpg" alt="<?=$logement['title']?>">
                        <?php } ?>
                        <span class="logement-type">En <?=$logement['logement_type']?></span>
                    </div>
                    <div class="misc">
                        <div class="header">
                            <h2><?=$logement['title']?></h2>
                            <span class="logement-type">En <?=$logement['logement_type']?></span>
                        </div>
                        <div class="content">
                            <?php
                            /*
                             * Si la description n'est pas null ou vide alors je l'affiche.
                             */
                            if(!empty($logement['description'])) {
                                /*
                                 * Si la description est fait plus que 50 caractères alors je ne prends que les 50 premiers
                                 * caractères et j'ajoute '...' à la fin.
                                 */
                                if(mb_strlen($logement['description']) > 50) { ?>
                                    <p><?=mb_substr($logement['description'], 0, 50).'...'?></p>
                                <?php }
                                /*
                                 * Sinon j'affiche la description au complete.
                                 */
                                else { ?>
                                    <p><?=$logement['description']?></p>
                            <?php } } ?>
                        </div>
                        <div class="footer">
                            <div class="address">
                                <p><?=$logement['address']?></p>
                                <p><?=$logement['postal_code'] . ' ' . $logement['city']?></p>
                            </div>
                            <div class="info">
                                <p><span>Surface:</span> <?=intFormat($logement['surface'])?>m²</p>
                                <p><span>Prix:</span> <?=intFormat($logement['price'])?>€</p>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
        <?php
            /*
             * J'insère ma pagination également en base de page.
             */
            echo $paginationHTML;
        ?>
    </div>

<?php

/*
 * J'inclue mon footer à ma page pour fermer les balise body & html.
 */
include_once '../src/includes/footer.php';
