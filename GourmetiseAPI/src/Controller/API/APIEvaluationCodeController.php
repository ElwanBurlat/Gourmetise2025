<?php

namespace App\Controller\API;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Repository\EvaluationCodeRepository;

final class APIEvaluationCodeController extends AbstractController
{

    #[Route('/api/evaluation-code/verify', name: 'api_evaluation_code_verify', methods: ['POST'])]
    public function verify(Request $request, EvaluationCodeRepository $evaluationCodeRepository): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        $code     = $data['code'] ?? null;
        $bakeryId = $data['bakery_id'] ?? null;

        if (!$code || !$bakeryId) {
            return $this->json(['valid' => false, 'message' => 'Code ou boulangerie manquant.'], 400);
        }

        $evaluationCode = $evaluationCodeRepository->findValidCode($code, $bakeryId);

        if (!$evaluationCode) {
            return $this->json(['valid' => false, 'message' => 'Code invalide ou déjà utilisé.'], 404);
        }

        return $this->json(['valid' => true, 'message' => 'Code valide !']);
    }

}
