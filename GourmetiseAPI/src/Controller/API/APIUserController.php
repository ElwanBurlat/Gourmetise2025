<?php

namespace App\Controller\API;

use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\SerializerInterface;
use Doctrine\ORM\EntityManagerInterface;

use OpenApi\Attributes as OA;
#[OA\Tag(name: "User")]
final class APIUserController extends AbstractController
{
    #[Route('/api/user', methods :["POST"])]
    #[OA\Post(
        path: "/api/user",
        summary: "Créer un utilisateurs",
        tags: ["User"],
        responses: [
            new OA\Response(
                response: 200,
                description: "Utilisateur crée"
            )
        ]
    )]
    public function createUser(
        Request $request,
        EntityManagerInterface $entityManager,
        SerializerInterface $serializer,
    ) : JsonResponse
    {
        // récupérer le contenu JSON de la requête
        $data = $request->getContent();
        try {
            // désérialiser le JSON en une instance de l'entité ContestParams
            $user = $serializer->deserialize($data, User::class, 'json', ['groups' => 'User:Write']);

            // enregistrer le Bakery dans la base de données
            $entityManager->persist($user); //Pour preparer la sauvegarde de  cet objet dans la bdd
            $entityManager->flush(); //executer les operations pour rentrer l'objet dans la BDD

            // envoyer réponse de succès de la création
            return new JsonResponse(['message'=>'User created',
                'id' => $user->getId()
            ], Response::HTTP_CREATED);
        }
        catch (\Exception $e) {
            return new JsonResponse(['error' => $e->getMessage()], Response::HTTP_BAD_REQUEST);
        }
    }
    #[Route('/api/login', methods: ["POST"])]
    #[OA\Post(
        path: "/api/login",
        summary: "Connecter un utilisateur",
        tags: ["User"],
        responses: [
            new OA\Response(response: 200, description: "Connexion réussie"),
            new OA\Response(response: 401, description: "Identifiants incorrects")
        ]
    )]
    public function login(
        Request $request,
        UserRepository $userRepository,
        EntityManagerInterface $entityManager,
    ): JsonResponse {
        $data = json_decode($request->getContent(), true);

        $email = $data['email'] ;
        $password = $data['password'] ;

        if (!$email || !$password) {
            return new JsonResponse(['message' => 'Champs manquants'], Response::HTTP_BAD_REQUEST);
        }

        $user = $userRepository->findOneBy(['email' => $email]);

        if (!$user) {
            return new JsonResponse(['message' => 'Email ou mot de passe incorrect'], Response::HTTP_UNAUTHORIZED);
        }

        if (!password_verify($password, $user->getPasswordHash())) {
            return new JsonResponse(['message' => 'Email ou mot de passe incorrect'], Response::HTTP_UNAUTHORIZED);
        }

        return new JsonResponse([
            'id' => $user->getId(),
            'firstName' => $user->getFirstName(),
            'lastName' => $user->getLastName(),
            'email' => $user->getEmail(),
            'role' => $user->getRole(),
        ], Response::HTTP_OK);
    }

}
