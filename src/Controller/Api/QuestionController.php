<?php

namespace App\Controller\Api;

use App\Entity\Question;
use App\Repository\QuestionRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

/**
 * @Route("/api/questions", name="api_questions_")
 */
class QuestionController extends AbstractController
{
    /**
     * @Route("/", name="browse", methods={"GET"})
     */
    public function browse(QuestionRepository $questionRepository, Request $request, SerializerInterface $serializer)
    {
        // On inclut le QuestionRepository dans la méthode pour obtenir toutes les questions (findAll)
        // Cependant, ce n'est pas logique de fournir toutes les questions à chaque fois
        // Il viendra un moment donné tôt dans le projet où on aura trop de questions à envoyer
        // Il serait bien de pouvoir déjà paginer les résultats
        // Utilisons plutôt un findBy, mais il faudra lui préciser la limite et l'offset

        // On récupère les paramètre de l'url limit et offset
        // On leur attribue une valeur par défaut au cas où ils ne sont pas dans l'URL
        // grâce au deuxième argument de get()
        $limit = (int) $request->query->get('limit', 10);
        $offset = (int) $request->query->get('offset', 0);

        // On passe la limite et l'offset à findBy()
        // Comme c'est une API publique, on ne souhaitepas que les questions bloquées apparaissant
        // On ajoute donc un critère à findBy
        // Selon API.md, on devrait avoir la possibilité de préciser au moins un tag pour filtrer les questions
        // On ne l'a pas fait par manque de temps.
        // Pour le faire il faudrait récupérer le paramètre tags dans l'url,
        // et, si il est défini, l'ajouter à la requête
        // Pareil pour un paramètre de tri, qui pourrait s'appeller order ou sort et que,
        // si il est défini, on l'ajouterait à l'argument $orderBy de findBy()
        $questions = $questionRepository->findBy(['isBlocked' => false], [], $limit, $offset);

        // On envoie la réponse en JSON en prenant de normaliser nos questions d'abord
        return $this->json($serializer->normalize(
            $questions,
            null, ['groups' => 'question']
        ));
    }

    /**
     * @Route("/{id}", name="read", methods={"GET"})
     */
    public function read(Question $question, SerializerInterface $serializer)
    {
        return $this->json($serializer->normalize(
            $question,
            null, ['groups' => ['question', 'answer']]
        ));
    }

    /**
     * @Route("/{id}", name="edit", methods={"PUT", "PATCH"})
     */
    public function edit()
    {
        return $this->json([
            'message' => 'Welcome to your new controller!',
            'path' => 'src/Controller/Api/QuestionController.php',
        ]);
    }

    /**
     * @Route("/", name="add", methods={"POST"})
     */
    public function add()
    {
        return $this->json([
            'message' => 'Welcome to your new controller!',
            'path' => 'src/Controller/Api/QuestionController.php',
        ]);
    }

    /**
     * @Route("/{id}", name="delete", methods={"DELETE"})
     */
    public function delete()
    {
        return $this->json([
            'message' => 'Welcome to your new controller!',
            'path' => 'src/Controller/Api/QuestionController.php',
        ]);
    }
}
