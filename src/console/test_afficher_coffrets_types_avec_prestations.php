<?php
require __DIR__ . '/../../vendor/autoload.php';

use Illuminate\Database\Capsule\Manager as DB;
use gift\appli\models\CoffretType;

$config = parse_ini_file(__DIR__ . '/../conf/conf.ini');

$db = new DB;
$db->addConnection($config);
$db->setAsGlobal();
$db->bootEloquent();

echo "--- Coffrets Types et leurs Prestations Suggérées ---\n";

try {
    $coffrets = CoffretType::with('prestations')->get();

    if ($coffrets->isEmpty()) {
        echo "Aucun coffret type trouvé.\n";
    } else {
        foreach ($coffrets as $coffret) {
            echo "\nCoffret Type ID : " . $coffret->id . "\n";
            echo "Libellé         : " . $coffret->libelle . "\n";
            echo "-- Prestations Suggérées --\n";

            if ($coffret->prestations->isEmpty()) {
                echo "  Aucune prestation suggérée pour ce coffret type.\n";
            } else {
                foreach ($coffret->prestations as $prestation) {
                    echo "  Presta ID : " . $prestation->id . "\n";
                    echo "  Libellé   : " . $prestation->libelle . "\n";
                    echo "  Tarif     : " . $prestation->tarif . "\n";
                    echo "  ----------\n";
                }
            }
            echo "=========================================\n";
        }
    }
} catch (\Exception $e) {
    echo "Erreur : " . $e->getMessage() . "\n";
}
