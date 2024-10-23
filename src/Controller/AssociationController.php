<?php

namespace App\Controller;

use App\Entity\Association;
use App\Repository\AssociationRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/association', name: 'association_')]
class AssociationController extends AbstractController
{

    private EntityManagerInterface $entityManager;
    private AssociationRepository $associationRepository;

    public function __construct(EntityManagerInterface $entityManager, AssociationRepository $associationRepository)
    {
        $this->entityManager = $entityManager;
        $this->associationRepository = $associationRepository;
    }

    #[Route('/', name: 'association_index', methods: ['GET'])]
    public function index(): Response
    {
        $associations = $this->associationRepository->findAll();
        return $this->json($associations);
    }

    #[Route('/create', name: 'association_create', methods: ['POST'])]
    public function create(Request $request): Response
    {
        $association = new Association();
        $association->setNom($request->request->get('nom'));
        $association->setEmail($request->request->get('email'));
        $association->setPassword($request->request->get('password')); // Remember to hash passwords properly
        $association->setSiteInternet($request->request->get('siteInternet'));

        $this->entityManager->persist($association);
        $this->entityManager->flush();

        return $this->json($association, Response::HTTP_CREATED);
    }

    #[Route('/{id}', name: 'association_show', methods: ['GET'])]
    public function show(Association $association): Response
    {
        return $this->json($association);
    }

    #[Route('/update/{id}', name: 'association_update', methods: ['PUT'])]
    public function update(Request $request, Association $association): Response
    {
        $association->setNom($request->request->get('nom', $association->getNom()));
        $association->setEmail($request->request->get('email', $association->getEmail()));
        $association->setSiteInternet($request->request->get('siteInternet', $association->getSiteInternet()));

        $this->entityManager->flush();

        return $this->json($association);
    }

    #[Route('/delete/{id}', name: 'association_delete', methods: ['DELETE'])]
    public function delete(Association $association): Response
    {
        $this->entityManager->remove($association);
        $this->entityManager->flush();

        return $this->json(['status' => 'Association deleted'], Response::HTTP_NO_CONTENT);
    }
}
