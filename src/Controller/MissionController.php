<?php

namespace App\Controller;

use App\Entity\Mission;
use App\Repository\MissionRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/mission', name: 'mission_')]
class MissionController extends AbstractController
{

    private EntityManagerInterface $entityManager;
    private MissionRepository $missionRepository;

    public function __construct(EntityManagerInterface $entityManager, MissionRepository $missionRepository)
    {
        $this->entityManager = $entityManager;
        $this->missionRepository = $missionRepository;
    }

    #[Route('/', name: 'mission_index', methods: ['GET'])]
    public function index(): Response
    {
        $missions = $this->missionRepository->findAll();
        return $this->json($missions);
    }

    #[Route('/create', name: 'mission_create', methods: ['POST'])]
    public function create(Request $request): Response
    {
        $mission = new Mission();
        $mission->setNomMission($request->request->get('nomMission'));
        $mission->setDescription($request->request->get('description'));
        $mission->setLieu($request->request->get('lieu'));
        $mission->setDate(new \DateTime($request->request->get('date')));
        $mission->setTypeMission($request->request->get('typeMission'));
        $mission->setCompetences($request->request->get('competences'));

        $this->entityManager->persist($mission);
        $this->entityManager->flush();

        return $this->json($mission, Response::HTTP_CREATED);
    }

    #[Route('/{id}', name: 'mission_show', methods: ['GET'])]
    public function show(Mission $mission): Response
    {
        return $this->json($mission);
    }

    #[Route('/update/{id}', name: 'mission_update', methods: ['PUT'])]
    public function update(Request $request, Mission $mission): Response
    {
        $mission->setNomMission($request->request->get('nomMission', $mission->getNomMission()));
        $mission->setDescription($request->request->get('description', $mission->getDescription()));
        $mission->setLieu($request->request->get('lieu', $mission->getLieu()));
        $mission->setDate(new \DateTime($request->request->get('date', $mission->getDate()->format('Y-m-d'))));
        $mission->setTypeMission($request->request->get('typeMission', $mission->getTypeMission()));
        $mission->setCompetences($request->request->get('competences', $mission->getCompetences()));

        $this->entityManager->flush();

        return $this->json($mission);
    }

    #[Route('/delete/{id}', name: 'mission_delete', methods: ['DELETE'])]
    public function delete(Mission $mission): Response
    {
        $this->entityManager->remove($mission);
        $this->entityManager->flush();

        return $this->json(['status' => 'Mission deleted'], Response::HTTP_NO_CONTENT);
    }
}
