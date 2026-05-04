<?php

namespace App\Controller\API;

use App\Entity\Bakery;
use App\Entity\ContestParams;
use App\Entity\Evaluation;
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
use App\Repository\EvaluationRepository;


use OpenApi\Attributes as OA;
#[OA\Tag(name: "Evaluations")]
class APIEvaluationController extends AbstractController
{
    #[Route('/api/evaluation', methods: ["POST"])]
    #[OA\Post(
        path: "/api/evaluation",
        summary: "Créer des évaluations",
        tags: ["Evaluations"],
        responses: [
            new OA\Response(
                response: 200,
                description: "Evaluations créées"
            )
        ]
    )]
    public function exportEvaluation(
        Request $request,
        EntityManagerInterface $entityManager,
        SerializerInterface $serializer,
        BakeryRepository $bakeryRepository
    ): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        try {
            $contestParams = $entityManager->getRepository(ContestParams::class)->find(1);

            if (!$contestParams) {
                return new JsonResponse(['message' => 'Contest params not found'], Response::HTTP_NOT_FOUND);
            }

            if ($contestParams->getStatus() !== Status::EVALUATION_OPEN) {
                return new JsonResponse(['message' => 'Status not correct'], Response::HTTP_BAD_REQUEST);
            }

            $items = isset($data[0]) ? $data : [$data];

            foreach ($items as $item) {
                $bakerySiret = $item['bakery'] ?? null;

                if (!$bakerySiret) {
                    return new JsonResponse(['error' => 'Bakery siret is missing'], Response::HTTP_BAD_REQUEST);
                }

                $bakery = $bakeryRepository->find($bakerySiret);

                if (!$bakery) {
                    return new JsonResponse(['error' => 'Bakery not found'], Response::HTTP_BAD_REQUEST);
                }

                unset($item['bakery']);

                $evaluation = $serializer->deserialize(
                    json_encode($item),
                    Evaluation::class,
                    'json'
                );

                $evaluation->setBakery($bakery);

                $entityManager->persist($evaluation);
            }

            $entityManager->flush();

            return new JsonResponse(['message' => 'Evaluation exported'], Response::HTTP_CREATED);

        } catch (\Exception $e) {
            return new JsonResponse(['error' => $e->getMessage()], Response::HTTP_BAD_REQUEST);
        }
    }

    #[Route('/api/evaluation', methods :["GET"])]
    #[OA\Get(
        path: "/api/evaluation",
        summary: "Données des évaluations",
        tags: ["Evaluations"],
        responses: [
            new OA\Response(
                response: 200,
                description: "Evaluations crées"
            )
        ]
    )]
    public function getevaluation(
        ContestParamsRepository $contestParamsRepository,
        EvaluationRepository $repository
        ) : JsonResponse
    {
        $evaluation = $repository->findScore();
        $contestParams = $contestParamsRepository->find(1);

        if ($contestParams->getStatus() !== Status::FINISHED) {
        return new JsonResponse(
            ['message' => 'Status not correct'],
            Response::HTTP_BAD_REQUEST
        );
    }
        return $this->json($evaluation, Response::HTTP_OK, []);
    }

    #[Route('/api/evaluation/{siret}', methods: ["GET"])]
    #[OA\Get(
        path: "/api/evaluation/{siret}",
        summary: "Données des évaluations selon le siret",
        tags: ["EvaluationsById"],
        responses: [
            new OA\Response(
                response: 200,
                description: "Evaluations crées"
            )
        ]
    )]
    public function getEvaluationById(
        ContestParamsRepository $contestParamsRepository,
        EvaluationRepository $repository,
        string $siret
    ) : JsonResponse
    {
        $evaluation = $repository->findScoreById($siret);
        $contestParams = $contestParamsRepository->find(1);

        if ($contestParams->getStatus() !== Status::FINISHED) {
            return new JsonResponse(
                ['message' => 'Status not correct'],
                Response::HTTP_BAD_REQUEST
            );
        }
        return $this->json($evaluation, Response::HTTP_OK, []);
    }



}
