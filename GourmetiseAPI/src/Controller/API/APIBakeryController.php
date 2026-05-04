<?php

namespace App\Controller\API;

use App\Entity\Bakery;
use App\Entity\ContestParams;
use App\Repository\BakeryRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\SerializerInterface;
use App\Entity\User;
use App\Enum\Status;
use App\Repository\ContestParamsRepository;
use OpenApi\Attributes as OA;

#[OA\Tag(name: "BakeryRegistrations")]
final class APIBakeryController extends AbstractController
{

    #[Route('/api/bakery', methods :["GET"])]
    #[OA\Get(
        path: "/api/bakery",
        summary: "Obtenir les boulangerie",
        tags: ["BakeryRegistrations"],
        responses: [
            new OA\Response(
                response: 200,
                description: "Boulangeries récupérés"
            )
        ]
    )]
    public function getbakery(
        ContestParamsRepository $contestParamsRepository,
        BakeryRepository $repository
        ) : JsonResponse
    {
        $bakery = $repository->findAll();
        $contestParams = $contestParamsRepository->find(1);

        if ($contestParams->getStatus() !== Status::EVALUATION_OPEN) {
        return new JsonResponse(
            ['message' => 'Hors période d’évaluation'],
            Response::HTTP_BAD_REQUEST
        );
    }
        return $this->json($bakery, Response::HTTP_OK, [], ['groups' => ['Bakery:Write']]);
    }

    #[Route('/api/bakery', methods :["POST"])]
    #[OA\Post(
        path: "/api/bakery",
        summary: "Crée une boulangerie",
        tags: ["BakeryRegistrations"],
        responses: [
            new OA\Response(
                response: 200,
                description: "Boulangerie crée"
            )
        ]
    )]
    public function createbakery(
        Request $request,
        EntityManagerInterface $entityManager,
        SerializerInterface $serializer,
        BakeryRepository $repository
    ) : JsonResponse
    {
        // récupérer le contenu JSON de la requête
        $data = $request->getContent();

        try {
            // désérialiser le JSON en une instance de l'entité ContestParams
            $bakery = $serializer->deserialize($data, Bakery::class, 'json');
            $siret=$bakery->getSiret();
            $email =$bakery->getBakeryUser()->getEmail();
            $user = $entityManager->getRepository(User::class)->findOneBy(['email' => $email]);
            $contestParams = $entityManager->getRepository(ContestParams::class)->find(1);

            if($contestParams->getStatus()!==Status::REGISTRATION_OPEN){
                return new JsonResponse(['message'=>'Status not correct'], Response::HTTP_BAD_REQUEST);
            }
            if($user ==null){
                return new JsonResponse(['message'=>'email not exist'], Response::HTTP_BAD_REQUEST);
            }

            if ($user->getRole() !== 'ROLE_BAKER') {
                return new JsonResponse(['message' => 'User is not a bakery'], Response::HTTP_BAD_REQUEST);
            }

            $existeForUser=$repository->findOneBy(['bakeryUser' => $user]);
            if($existeForUser !== null){
                return new JsonResponse(['message'=>'User has already a bakery'], Response::HTTP_BAD_REQUEST);
            }

            $existingbakery = $repository->find($siret);
            if($existingbakery){
                return new JsonResponse(['message'=>'Siret already exist'], Response::HTTP_BAD_REQUEST);
            }
            // enregistrer le Bakery dans la base de données
            $bakery->setBakeryUser($user);
            $bakery->setDataConsent(new \DateTimeImmutable());
            $bakery->setDataDecline(null);
            $entityManager->persist($bakery); //Pour preparer la sauvegarde de  cet objet dans la bdd
            $entityManager->flush(); //executer les operations pour rentrer l'objet dans la BDD

            // envoyer réponse de succès de la création
            return new JsonResponse(['message'=>'Bakery created'], Response::HTTP_CREATED);
        }
        catch (\Exception $e) {
            return new JsonResponse(['error' => $e->getMessage()], Response::HTTP_BAD_REQUEST);
        }
    }

}
