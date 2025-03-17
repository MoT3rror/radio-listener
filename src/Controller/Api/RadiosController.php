<?php

namespace App\Controller\Api;

use App\JsonRepresentative\Date;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

final class RadiosController extends AbstractController
{
    #[Route('/api/radio/{radioId}/recordings', name: 'app_api_radio_recordings', methods: ['GET'])]
    public function recordings(Request $request, int $radioId): JsonResponse
    {
        return $this->json([
            'radio' => $radioId,
            'date' => $request->query->get('date'),
            'data' => [
                [
                    'id' => 1,
                    'recording_start' => new Date()->modify('-4 hours'),
                    'recording_end' => new Date()->modify('-4 hours')->modify('+30 seconds'),
                ],
                [
                    'id' => 2,
                    'recording_start' => new Date()->modify('-4 hours')->modify('+1 minute'),
                    'recording_end' => new Date()->modify('-4 hours')->modify('+30 seconds')->modify('+1 minute'),
                ],
                [
                    'id' => 3,
                    'recording_start' => new Date(),
                    'recording_end' => new Date(),
                ],
            ],
        ]);
    }
}
