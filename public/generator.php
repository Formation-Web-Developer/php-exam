<?php
include_once '../src/includes/loader.php';
use Gregwar\Image\Image;

$titles = [
    'Lorem ipsum',
    'Suspendisse',
    'Praesent pretium',
    'Cras aliquet',
    'Praesent ullamcorper',
    'Maecenas aliquam',
    'Vivamus molestie',
    'Phasellus rhoncus',
    'Curabitur',
    'In elementum'
];

$address = [
    'Etiam volutpat sed arcu',
    'Suspendisse blandit pretium',
    'Quis rutrum eros tristique nec.',
    'Sed maximus molestie felis',
    'Donec luctus eu erat eu pellentesque',
    'Quisque condimentum elit interdum',
    'Donec maximus aliquam mauris',
    'Mauris placerat odio non mi eleifend',
    'Maecenas aliquam scelerisque erat',
    'Sed tempor magna in condimentum',
    'Ut porttitor faucibus vulputate'
];

$cities = [
    'Lorem ipsum',
    'Curabitur',
    'Suspendisse',
    'Nam aliquam',
    'Etiam volutpat',
    'Duis quis tristique',
    'Nulla non',
    'Fusce non',
    'Praesent pretium',
    'Praesent ullamcorper',
    'Quisque condimentum',
    'Maecenas aliquam'
];

$description = [
    null,
    'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Proin sapien erat, commodo et vulputate sit amet, euismod nec ligula. Nulla volutpat laoreet dapibus. Vestibulum ac facilisis risus. Vivamus elementum id elit id congue. Cras tellus enim, dignissim in euismod eu, dapibus eu mi. Ut interdum augue in aliquet laoreet. Suspendisse tempus elit eu efficitur pulvinar. Etiam dictum mi euismod blandit tincidunt.',
    'Morbi eu lectus lobortis, efficitur tellus at, venenatis ex. Donec iaculis, dui id lobortis laoreet, nunc lorem lobortis velit, placerat maximus ante orci eu lacus. Cras lectus erat, dignissim quis nulla eget, ultrices porta elit. Fusce luctus est at mollis pharetra. Pellentesque posuere est nec ipsum feugiat, sit amet ultrices metus varius. Pellentesque dapibus, elit aliquam tincidunt eleifend, sapien metus convallis libero, sit amet bibendum neque elit ac mi. Duis fermentum neque nec ex interdum, vel mattis sem tempus. Cras porta lorem ac efficitur fermentum. Ut dui dui, congue id congue quis, commodo vitae tortor.',
    'Vestibulum vitae nisi eu ligula scelerisque venenatis. Vivamus pretium id erat at lobortis. Etiam eget ex lorem. Aliquam nulla est, consequat quis sodales vitae, tincidunt a mi. Ut ut magna pellentesque ligula efficitur vulputate sodales at massa. Curabitur vitae neque a enim gravida pulvinar. Nam eget semper ex.',
    'Sed pellentesque condimentum vulputate. Suspendisse in magna et enim pretium interdum. Nulla vulputate purus ex, ut gravida neque molestie sed. Donec venenatis nisi vel orci facilisis varius. Sed molestie tempor turpis quis elementum. Suspendisse potenti. Cras semper tortor tellus, vitae commodo massa rhoncus et. Praesent ac efficitur eros. Ut vitae pulvinar turpis, ac elementum tellus. Curabitur feugiat, velit ut maximus sagittis, libero ante rhoncus justo, tristique lacinia arcu eros non turpis. Nam eget feugiat felis, convallis cursus lacus. Phasellus feugiat purus nec eros vehicula, at feugiat libero ornare. Vivamus scelerisque, nibh et porta viverra, elit metus sodales tortor, sit amet elementum diam mi ut ante.',
    'Maecenas et ultrices metus. Etiam a leo ipsum. Nulla vestibulum pellentesque faucibus. Sed faucibus varius ante, et congue lacus efficitur vel. Proin viverra, mauris ut suscipit sodales, elit libero lacinia magna, vel bibendum ante lacus a enim. Vivamus ut justo blandit, consectetur magna sit amet, volutpat orci. Sed ultrices consectetur magna et mattis. Sed id nisi vel tellus iaculis ornare quis sed orci. Praesent maximus venenatis lorem, sed gravida arcu luctus vel. Integer at maximus odio. Sed sed tortor lectus.',
    'Sed fermentum tempor urna, nec mattis leo condimentum sed. Nulla sagittis tellus ac tempus efficitur. Sed efficitur dolor diam, et bibendum tortor vestibulum sit amet. Quisque vel est efficitur, egestas sapien at, commodo ipsum. Vestibulum id libero a nunc finibus tempus tincidunt sed metus. Nunc ut quam turpis. Nunc cursus eu lectus ac auctor. Curabitur ac laoreet massa, vitae dapibus ipsum. Aliquam augue leo, molestie a eros non, mattis dapibus felis.'
];


$files = [
    null,
    'C:\Users\pro\Pictures\téléchargé (1).jpg',
    'C:\Users\pro\Pictures\téléchargé (2).jpg',
    'C:\Users\pro\Pictures\téléchargé (3).jpg',
    'C:\Users\pro\Pictures\téléchargé (4).jpg',
    'C:\Users\pro\Pictures\villa-architecture-house-design.jpg',
    'C:\Users\pro\Pictures\stock-photo-devostock-courtyard-england-villa-3655-4k-117796.jpg',
    'C:\Users\pro\Pictures\islamic-686019_1280.jpg',
    'C:\Users\pro\Pictures\téléchargé.jpg'
];


for($i = 0; $i < 20; $i++)
{
    $values = [
        'title' => $titles[rand()%count($titles)],
        'address' => $address[rand()%count($address)],
        'city' => $cities[rand()%count($cities)],
        'postal_code' => rand(100, 90000),
        'surface' => rand(50, 1000),
        'price' => rand(1000, 2000000),
        'type' => rand(1, 2),
        'description' => $description[rand()%count($description)],
    ];

    createLogement($database, $values);

    $file = $files[rand()%count($files)];
    if($file != null){
        $id = $database->getLastInsertId();
        $extension = pathinfo($file,PATHINFO_EXTENSION);
        $path = 'assets/uploads/images/logement_'.$id;

        $image = new Image($file);
        $image->save(__DIR__.'/'.$path.'.'.$extension);

        $image->forceResize(300, 300)
            ->save(__DIR__.'/'.$path.'_300x300.'.$extension);

        setImageLogement($database, $id, $path.'.'.$extension);
    }

    echo '<br/>';
    echo $i.' - SUCCESS !';
}
