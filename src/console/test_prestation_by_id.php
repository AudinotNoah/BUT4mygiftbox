<?php
require __DIR__ . '/../../vendor/autoload.php';

use Illuminate\Database\Capsule\Manager as DB;
use gift\core\domain\entities\Prestation;
use Illuminate\Database\Eloquent\ModelNotFoundException;

$prestationId = $argv[1];

$config = parse_ini_file(__DIR__ . '/../conf/conf.ini');

$db = new DB;
$db->addConnection($config);
$db->setAsGlobal();
$db->bootEloquent();


try {
    $prestation = Prestation::findOrFail($prestationId);

    echo "Prestation trouvée :\n";
    echo "ID        : " . $prestation->id . "\n";
    echo "Libellé   : " . $prestation->libelle . "\n";
    echo "Description: " . $prestation->description . "\n";

} catch (ModelNotFoundException $e) {
    echo "Erreur : Aucune prestation trouvée avec l'ID '$prestationId'.\n";
} catch (\Exception $e) {
    echo "Erreur générale : " . $e->getMessage() . "\n";
}
