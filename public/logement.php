<?php
include_once '../src/includes/loader.php';
use NeutronStars\TravelAgency\FormBuilder;
use NeutronStars\TravelAgency\FormValidator;
use Gregwar\Image\Image;

$logementTypes = getLogementToArray($database);
$errors = [];

if(!empty($_POST['submit']))
{
    $validator = new FormValidator($_POST+$_FILES, [
        'title' => [ 'min' => 5, 'max' => 255, 'add' => ['address', 'city'] ],
        'postal_code' => [ 'min' => 3, 'max' => 5, 'matches' => '/^[0-9]{3,5}$/' ],
        'description' => [ 'min' => 10, 'require' => false ],
        'price' => [ 'type' => 'number', 'min' => 0, 'add' => ['surface'] ],
        'type' => [ 'type' => 'select', 'value' => $logementTypes ],
        'picture' => [ 'type' => 'image', 'extensions' => ['jpeg', 'jpg', 'png'], 'maxSize' => 2000000, 'require' => false ]
    ]);

    if($validator->isValid()){
        createLogement($database, $validator->getValues());
        $success = true;

        if(!empty($_FILES['picture']['tmp_name'])){
            $id = $database->getLastInsertId();
            $extension = pathinfo($_FILES['picture']['name'],PATHINFO_EXTENSION);
            $path = 'assets/uploads/images/logement_'.$id;
            (new Image($_FILES['picture']['tmp_name']))
                ->forceResize(300, 300)
                ->save(__DIR__.'/'.$path.'_300x300.'.$extension);
            setImageLogement($database, $id, $path.'.'.$extension);
            move_uploaded_file($_FILES['picture']['tmp_name'], $path.'.'.$extension);
        }
    }else {
        $errors = $validator->getErrors();
    }
}

$title = 'Ajouter un logement';
include_once '../src/includes/header.php';
?>

    <div class="container-2">
        <a class="btn" href="index.php">Retourner à l'accueil</a>
        <?php if(!empty($success)){ ?>
            <div class="success">
                Les données ont bien été enregistrées !
            </div>
        <?php }
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

include_once '../src/includes/footer.php';
