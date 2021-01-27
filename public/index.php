<?php
include_once '../src/includes/loader.php';
use NeutronStars\Pagination\Pagination;

$page = 1;
if(!empty($_GET['page']) && is_numeric($_GET['page']))
{
    $page = intval($_GET['page']);
}

$itemPerPage = 12;

$paginationHTML = (new Pagination($page, $itemPerPage, getCount($database), [
   'previous-next' => [ 'active' => true ],
   'first-last' => [ 'active' => true ]
]))->toHTML();

$title = 'Liste des logements';
include_once '../src/includes/header.php';

?>

    <div class="container">
        <a class="btn" href="logement.php">Ajouter un logement</a>
        <?=$paginationHTML?>
        <div class="logements">
            <?php foreach (getLogements($database, $page, $itemPerPage) as $logement): ?>
                <div class="logement">
                    <div class="picture">
                        <?php if(!empty($logement['picture'])){ ?>
                            <img src="<?=getMiniature($logement['picture'])?>" alt="<?=$logement['title']?>">
                        <?php }else { ?>
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
                            <?php if(!empty($logement['description'])) {
                                if(mb_strlen($logement['description']) > 50) { ?>
                                    <p><?=mb_substr($logement['description'], 0, 50).'...'?></p>
                                <?php } else { ?>
                                    <p><?=$logement['description']?></p>
                            <?php } } ?>
                        </div>
                        <div class="footer">
                            <div class="address">
                                <p><?=$logement['address']?></p>
                                <p><?=$logement['postal_code'] . ' ' . $logement['city']?></p>
                            </div>
                            <div class="info">
                                <p><span>Surface:</span> <?=$logement['surface']?>€</p>
                                <p><span>Prix:</span> <?=$logement['price']?>€</p>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
        <?=$paginationHTML?>
    </div>

<?php

include_once '../src/includes/footer.php';
