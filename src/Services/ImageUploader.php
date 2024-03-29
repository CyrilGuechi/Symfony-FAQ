<?php
namespace App\Services;

use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class ImageUploader
{
    private $params;

    public function __construct(ParameterBagInterface $params)
    {
        $this->params = $params;
    }

    public function upload($file, $directory = null)
    {
        if ($file === null) {
            return false;
        }
        // Dans move() on peut spécifier un dossier relatif à partir de /public
        // On peut spécifier un chemin absolu en "dur"
        // On peut aussi lui donner une valeur qui se trouve dans le .ENV
        //          $_ENV['QUESTION_IMAGES_DIR']
        // On peut aussi lui demander d'utiliser un paramètre déclaré dans services.yaml
        //          $this->getParameter('image_directory')

        // On détermine le dossier cible en fonction de $directory
        if ($directory === null) {
            // $directory = 'uploads/images';
            // $directory = $_ENV['QUESTION_IMAGES_DIR'];

            // Pour utiliser les paramètres déclarés dans services.yaml
            // on ne peut pas utilsier getParameter() qui n'est disponible que dans le contrôleur
            // On doit faire une injection de dépendance dans notre service
            $directory = $this->params->get('image_directory');
        }

        // On utilise la méthode move() comme on le faisait le contrôleur
        $file->move($directory, $file->getClientOriginalName());
        return true;
    }
}