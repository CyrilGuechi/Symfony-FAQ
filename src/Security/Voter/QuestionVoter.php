<?php

namespace App\Security\Voter;

use App\Entity\Question;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\User\UserInterface;

class QuestionVoter extends Voter
{
    protected function supports($attribute, $subject)
    {
        // La méthode supports() sert à déclarer si le droit et l'objet à tester sont gérés par ce Voter
        // La ligne par défaut est une comparaison où on vérifie que le droit à tester est dans la liste
        // et que l'objet est bien l'instance d'une certaine entité
        // return in_array($attribute, ['POST_EDIT', 'POST_VIEW'])
        //     && $subject instanceof \App\Entity\BlogPost;

        // Cependant, on fait ce qu'on veut, du moment que supports() retourne true ou false

        if (in_array($attribute, ['QUESTION_EDIT', 'QUESTION_VALIDATE']) && $subject instanceof Question) {
            return true;
        } else {
            return false;
        }
    }

    protected function voteOnAttribute($attribute, $subject, TokenInterface $token)
    {
        // Si supports() retourne TRUE, Symfony va exécuter la méthode voteOnAttribute()
        // Ici on doit aussi retourner true ou false pour confirmer si le l'autorisation est donnée ou pas

        // Voici comment on récupére l'utilisateur connecté :
        $user = $token->getUser();

        // Ce code est fourni avec le maker de ce voter,
        // on le commente parce que le droit est testé sur une route qui n'est autorisé
        // que pour les utilisateurs connectés de toute façon
        // // if the user is anonymous, do not grant access
        // if (!$user instanceof UserInterface) {
        //     return false;
        // }

        // ... (check conditions and return true to grant permission) ...
        switch ($attribute) {
            case 'QUESTION_EDIT':
                // On doit vérifier si on a affaire soit à l'auteur de la question
                // soit à un modérateur, soit à un admin
                if ($user == $subject->getUser()) {
                    return true;
                }

                if ($user->getRole()->getRoleString() == 'ROLE_MODERATOR' || $user->getRole()->getRoleString() == 'ROLE_ADMIN') {
                    return true;
                }

                // On aurait pu écrire les deux if ci-dessus en une seule fois et de ces deux façons :
                // if ($user == $subject->getUser()
                //     || $user->getRole()->getRoleString() == 'ROLE_MODERATOR'
                //     || $user->getRole()->getRoleString() == 'ROLE_ADMIN'
                //     ) {
                //     return true;
                // }

                // return $user == $subject->getUser()
                //     || $user->getRole()->getRoleString() == 'ROLE_MODERATOR'
                //     || $user->getRole()->getRoleString() == 'ROLE_ADMIN';

                break;
            case 'QUESTION_VALIDATE':
                if ($user == $subject->getUser()) {
                    return true;
                }
                break;
        }

        // Y'a un tout petit peu de code qui se répéte, on pourrait écrire le switch de cette façon là
        // Ici, pour le cas où on test QUESTION_EDIT, les if L83 et L87 s'exéceutent
        // Pour le cas dûn QUESTION_VALIDATE, seul le if L87 s'exécute
        // switch ($attribute) {
        //     case 'QUESTION_EDIT':
        //         if ($user->getRole()->getRoleString() == 'ROLE_MODERATOR' || $user->getRole()->getRoleString() == 'ROLE_ADMIN') {
        //             return true;
        //         }
        //     case 'QUESTION_VALIDATE':
        //         if ($user == $subject->getUser()) {
        //             return true;
        //         }
        //         break;
        // }

        return false;
    }
}
