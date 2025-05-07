<?php
require __DIR__ . '/../../vendor/autoload.php';

use Illuminate\Database\Capsule\Manager as DB;
use gift\appli\models\CoffretType;

$config = parse_ini_file(__DIR__ . '/../conf/conf.ini');

$db = new DB;
$db->addConnection($config);
$db->setAsGlobal();
$db->bootEloquent();


try {
    $coffrets = CoffretType::with('theme')->get();

    if ($coffrets->isEmpty()) {
        echo "Aucun coffret type trouvé.\n";
    } else {
        foreach ($coffrets as $coffret) {
            echo "ID          : " . $coffret->id . "\n";
            echo "Libellé     : " . $coffret->libelle . "\n";
            echo "Description : " . $coffret->description . "\n";
            if ($coffret->theme) {
                echo "Thème       : " . $coffret->theme->libelle . " (ID: " . $coffret->theme_id . ")\n";
            } else {
                echo "Thème ID    : " . $coffret->theme_id . " (Thème non trouvé)\n";
            }
            echo "-----------------------------------------\n";
        }
    }
} catch (\Exception $e) {
    echo "Erreur : " . $e->getMessage() . "\n";
}
