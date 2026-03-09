<?php

namespace App\Controller\API;

use App\Entity\User;
use App\Repository\UserRepository;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\SerializerInterface;
use Doctrine\ORM\EntityManagerInterface;

final class APIUserController extends AbstractController
{


    private JWTTokenManagerInterface $JWTManager;
    private UserRepository $userRepository;
    private UserPasswordHasherInterface $passwordHasher;

    public function __construct(UserRepository $repository, JWTTokenManagerInterface $JWTManager, UserPasswordHasherInterface $passwordHasher)
    {
       $this->userRepository = $repository;
       $this->JWTManager = $JWTManager;
       $this->passwordHasher = $passwordHasher;
    }


    #[Route('/api/user', methods :["POST"])]
    public function createUser(
        Request $request,
        EntityManagerInterface $entityManager,
        SerializerInterface $serializer
    ) : JsonResponse
    {
        // récupérer le contenu JSON de la requête
        $data = $request->getContent();
        try {
            // désérialiser le JSON en une instance de l'entité ContestParams
            $user = $serializer->deserialize($data, User::class, 'json', ['groups' => 'User:Write']);

            $this->userRepository->registerUser($user);
            // enregistrer le Bakery dans la base de données
            //$entityManager->persist($user); //Pour preparer la sauvegarde de  cet objet dans la bdd
            //$entityManager->flush(); //executer les operations pour rentrer l'objet dans la BDD

            // envoyer réponse de succès de la création
            return new JsonResponse(['message'=>'User created'], Response::HTTP_CREATED);
        }
        catch (\Exception $e) {
            return new JsonResponse(['error' => $e->getMessage()], Response::HTTP_BAD_REQUEST);
        }
    }
    #[Route('/api/profile', name: 'profile', methods: ['GET'])]
    public function getProfile(): JsonResponse
    {
        // Récupérer l'utilisateur connecté
        $user = $this->getUser();
        return $this->json($user, status: Response::HTTP_OK);
    }
}
