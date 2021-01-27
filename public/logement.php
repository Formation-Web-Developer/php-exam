<?php
/*
 * J'inclus toutes les dépendances & ressources.
 */
include_once '../src/includes/loader.php';

/*
 * J'importe de les classes FormBuilder, FormValidator qui se trouve dans mon dossier src.
 */
use NeutronStars\ImmobilierAgency\FormBuilder;
use NeutronStars\ImmobilierAgency\FormValidator;
/*
 * J'importe la class Image de Gregwar. (Installer avec Composer)
 */
use Gregwar\Image\Image;

/*
 * Je récupère les noms possible de mes types de logement qui se trouve dans la base de donnée. (Location ou Vente)
 */
$logementTypes = getLogementToArray($database);
/*
 * Je déclare un tableau d'erreur vide pour stocker les différentes erreurs si l'utilisateur à soumis des données.
 */
$errors = [];

/*
 * Si l'utilisateur à soumis des données alors je vais les traiter dans cette condition.
 */
if(!empty($_POST['submit']))
{
    /*
     * Je déclare une nouvelle instance de ma classe FormValidator puis j'insère mes conditions pour que les tableaux POST
     * et FILES soient valides.
     *
     * Si mes données n'ont pas de clé 'type' alors par défaut ils seront traitées comme des chaines de caractère.
     * la clé 'add' permet d'ajouter les mêmes règles que la clé d'origine.
     *
     * Exemple:
     *      title est une chaine de caractère qui doit respecter les conditions suivantes:
     *          min => Doit faire minimum 5 caractères.
     *          max => Doit faire maximum 255 caractères.
     *      address et city doivent respecter les mêmes règles que title.
     *
     * le require me permet de savoir si le champs est obligatoires ou non.
     * le values pour le type select me permet de lister toutes les valeurs possibles.
     *
     * le maxSize pour le type image me permet de connaitre la taille maximum accepté en Mo.
     *
     * PS: A noté que le FormValidator gère également les failles XSS.
     */
    $validator = new FormValidator($_POST+$_FILES, [
        'title' => [ 'min' => 5, 'max' => 255, 'add' => ['address', 'city'] ],
        'postal_code' => [ 'min' => 3, 'max' => 5, 'matches' => '/^[0-9]{3,5}$/' ],
        'description' => [ 'min' => 10, 'require' => false ],
        'price' => [ 'type' => 'number', 'min' => 0, 'add' => ['surface'] ],
        'type' => [ 'type' => 'select', 'values' => $logementTypes ],
        'picture' => [ 'type' => 'image', 'extensions' => ['jpeg', 'jpg', 'png'], 'maxSize' => 2000000, 'require' => false ]
    ]);

    /*
     * Si les données passées dans l'instance de FormValidator sont valides (donc qu'il n'y a pas d'erreur)
     * alors je peux les insérer dans la base de donnée sans risque.
     */
    if($validator->isValid()){
        /*
         * Je créais ma ligne avec les données saisies par l'utilisateur ainsi que sécurisé par le FormValidator.
         */
        createLogement($database, $validator->getValues());
        /*
         * Je déclare que tout c'est bien passé afin d'afficher un message de succès à l'utilisateur.
         */
        $success = true;

        /*
         * Si l'utilisateur à tenté d'ajouter une image lorsqu'il a posté ses données alors je rentre dans cette condition
         */
        if(!empty($_FILES['picture']['tmp_name'])){
            /*
             * Je récupère le dernier id inséré par la base de donnée qui n'est autre que le logement ajouté ci-dessus.
             */
            $id = $database->getLastInsertId();
            /*
             * Je récupère l'extension du fichier.
             */
            $extension = pathinfo($_FILES['picture']['name'],PATHINFO_EXTENSION);
            /*
             * Je déclare le chemin ou sera sauvegardé l'image sans ajouter l'extension tout de suite.
             */
            $path = 'assets/uploads/images/logement_'.$id;

            /*
             * Je créais une nouvel instance de la class Image avec le fichier temporaire stocké dans la super globale $_FILES.
             * Je force sont resize à 300px de chaque côté.
             * Puis je sauvegarde l'image en ajoutant 300_300 et l'extension du fichier à la fin du chemin de la variable $path.
             */
            (new Image($_FILES['picture']['tmp_name']))
                ->forceResize(300, 300)
                ->save(__DIR__.'/'.$path.'_300x300.'.$extension);

            /*
             * Je déplace l'image original dans le même dossier ou ce trouve la miniature.
             */
            move_uploaded_file($_FILES['picture']['tmp_name'], $path.'.'.$extension);
            /*
             * Je sauvegarde l'image original dans la base de donnée. (Il sera plus facile de travail sur son path que celui de la miniature.)
             */
            setImageLogement($database, $id, $path.'.'.$extension);
        }
    }
    /*
     * Sinon si les données passé à l'instance FormValidator ne sont pas correct alors je remplie mon tableau d'erreur avec
     * celui du FormValidator.
     */
    else {
        $errors = $validator->getErrors();
    }
}

/*
 * Je déclare le title qui est tout simplement mon H1 pour qu'ils soit utilisé dans mon header.php
 */
$title = 'Ajouter un logement';
/*
 * J'inclue mon header ainsi que le début de la page HTML.
 */
include_once '../src/includes/header.php';
?>

    <div class="container-2">
        <a class="btn" href="index.php">Retourner à l'accueil</a>
        <?php
        /*
         * Si l'utilisateur à soumis des données et qu'ils sont valides alors j'affiche un message de succès.
         */
        if(!empty($success)){ ?>
            <div class="success">
                Les données ont bien été enregistrées !
            </div>
        <?php }

            /*
             * Je créais une nouvelle instance de FormBuilder et je construis mon formulaire avec les inputs requis.
             *
             * Si une erreur est survenue ils apparaitront entre le label et l'input en question.
             * Les inputs seront également remplie avec les données saisie par l'utilisateur afin d'éviter qu'il les retapent.
             */
            echo (new FormBuilder())
                ->input('Titre *', 'title', getValueByArray($_POST, 'title', ''), getValueByArray($errors, 'title'))
                ->input('Adresse *', 'address', getValueByArray($_POST, 'address', ''), getValueByArray($errors, 'address'))
                ->input('Ville *', 'city', getValueByArray($_POST, 'city', ''), getValueByArray($errors, 'city'))
                ->input('Code Postal *', 'postal_code', getValueByArray($_POST, 'postal_code', ''), getValueByArray($errors, 'postal_code'))
                ->input('Surface *', 'surface', getValueByArray($_POST, 'surface', ''), getValueByArray($errors, 'surface'), 'integer')
                ->input('Prix *', 'price', getValueByArray($_POST, 'price', ''), getValueByArray($errors, 'price'), 'integer')
                ->select('Type de logement *', 'type', ['' => '']+$logementTypes,getValueByArray($_POST, 'type', ''), getValueByArray($errors, 'type'))
                ->textArea('Description', 'description', 5, '', getValueByArray($_POST, 'description', ''), getValueByArray($errors, 'description'))
                ->input('Mettre une image', 'picture', '', getValueByArray($errors, 'picture'), 'file')
                ->input(null, 'submit', 'Enregistrer', null, 'submit');
        ?>
    </div>

<?php
/*
 * J'inclue mon footer à ma page pour fermer les balise body & html.
 */
include_once '../src/includes/footer.php';
