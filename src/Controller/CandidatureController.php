<?php

namespace App\Controller;

use App\Entity\Candidature;
use App\Repository\CandidatureRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
#[Route('/candidature', name: 'candidature_')]
class CandidatureController extends AbstractController
{
    private EntityManagerInterface $entityManager;
    private CandidatureRepository $candidatureRepository;

    public function __construct(EntityManagerInterface $entityManager, CandidatureRepository $candidatureRepository)
    {
        $this->entityManager = $entityManager;
        $this->candidatureRepository = $candidatureRepository;
    }

    #[Route('/', name: 'candidature_index', methods: ['GET'])]
    public function index(): Response
    {
        $candidatures = $this->candidatureRepository->findAll();
        return $this->json($candidatures);
    }

    #[Route('/create', name: 'candidature_create', methods: ['POST'])]
    public function create(Request $request): Response
    {
        $candidature = new Candidature();
        $candidature->setDateInscription(new \DateTime($request->request->get('dateInscription')));
        $candidature->setStatus($request->request->get('status'));

        $this->entityManager->persist($candidature);
        $this->entityManager->flush();

        return $this->json($candidature, Response::HTTP_CREATED);
    }

    #[Route('/{id}', name: 'candidature_show', methods: ['GET'])]
    public function show(Candidature $candidature): Response
    {
        return $this->json($candidature);
    }

    #[Route('/update/{id}', name: 'candidature_update', methods: ['PUT'])]
    public function update(Request $request, Candidature $candidature): Response
    {
        $candidature->setDateInscription(new \DateTime($request->request->get('dateInscription', $candidature->getDateInscription()->format('Y-m-d'))));
        $candidature->setStatus($request->request->get('status', $candidature->getStatus()));

        $this->entityManager->flush();

        return $this->json($candidature);
    }

    #[Route('/delete/{id}', name: 'candidature_delete', methods: ['DELETE'])]
    public function delete(Candidature $candidature): Response
    {
        $this->entityManager->remove($candidature);
        $this->entityManager->flush();

        return $this->json(['status' => 'Candidature deleted'], Response::HTTP_NO_CONTENT);
    }
}
