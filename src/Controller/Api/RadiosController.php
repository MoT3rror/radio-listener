<?php

namespace App\Controller\Api;

use App\JsonRepresentative\Date;
use App\Repository\RadioRepository;
use App\Repository\RecordingRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

final class RadiosController extends AbstractController
{
    public function __construct(
        private RadioRepository $radioRepository,
        private RecordingRepository $recordingRepository,
    )
    {}
    
    #[Route('/api/radio/{radioId}/recordings', name: 'app_api_radio_recordings', methods: ['GET'])]
    public function recordings(Request $request, int $radioId): JsonResponse
    {
        $radio = $this->radioRepository->find($radioId);

        if (! $radio) {
            return $this->json([
                'error' => 'Radio not found',
            ], 404);
        }
        
        return $this->json([
            'radio' => $radioId,
            'date' => $request->query->get('date'),
            'data' => $this->recordingRepository->findRecordingsByRadioId(
                $radioId,
                new Date($request->query->get('date'))
            ),
        ]);
    }
}
