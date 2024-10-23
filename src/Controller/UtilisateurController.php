<?php

namespace App\Controller;

use App\Entity\Utilisateur;
use App\Repository\UtilisateurRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;

#[Route('/utilisateur', name: 'utilisateur_')]
class UtilisateurController extends AbstractController
{
    private EntityManagerInterface $entityManager;
    private UtilisateurRepository $utilisateurRepository;
    private UserPasswordHasherInterface $passwordHasher;

    public function __construct(
        EntityManagerInterface $entityManager,
        UtilisateurRepository $utilisateurRepository,
        UserPasswordHasherInterface $passwordHasher
    ) {
        $this->entityManager = $entityManager;
        $this->utilisateurRepository = $utilisateurRepository;
        $this->passwordHasher = $passwordHasher;
    }

    #[Route('/', name: 'utilisateur_index', methods: ['GET'])]
    public function index(): Response
    {
        $utilisateurs = $this->utilisateurRepository->findAll();
        return $this->json($utilisateurs);
    }

    #[Route('/{id}', name: 'utilisateur_show', methods: ['GET'])]
    public function show(Utilisateur $utilisateur): Response
    {
        return $this->json($utilisateur);
    }

    #[Route('/create', name: 'utilisateur_create', methods: ['POST'])]
    public function create(Request $request): Response
    {
        $utilisateur = new Utilisateur();
        $utilisateur->setNom($request->request->get('nom'));
        $utilisateur->setPrenom($request->request->get('prenom'));
        $utilisateur->setEmail($request->request->get('email'));
        $hashedPassword = $this->passwordHasher->hashPassword($utilisateur, $request->request->get('password'));
        $utilisateur->setPassword($hashedPassword);
        $utilisateur->setTelephone($request->request->get('telephone'));
        $utilisateur->setAdresse($request->request->get('adresse'));
        $utilisateur->setCodePostal($request->request->get('codePostal'));
        $utilisateur->setVille($request->request->get('ville'));
        $utilisateur->setAnneeNaissance($request->request->get('anneeNaissance'));

        $this->entityManager->persist($utilisateur);
        $this->entityManager->flush();

        return $this->json($utilisateur, Response::HTTP_CREATED);
    }

    #[Route('/update/{id}', name: 'utilisateur_update', methods: ['PUT'])]
    public function update(Request $request, Utilisateur $utilisateur): Response
    {
        $utilisateur->setNom($request->request->get('nom', $utilisateur->getNom()));
        $utilisateur->setPrenom($request->request->get('prenom', $utilisateur->getPrenom()));
        $utilisateur->setEmail($request->request->get('email', $utilisateur->getEmail()));
        $utilisateur->setTelephone($request->request->get('telephone', $utilisateur->getTelephone()));
        $utilisateur->setAdresse($request->request->get('adresse', $utilisateur->getAdresse()));
        $utilisateur->setCodePostal($request->request->get('codePostal', $utilisateur->getCodePostal()));
        $utilisateur->setVille($request->request->get('ville', $utilisateur->getVille()));
        $utilisateur->setAnneeNaissance($request->request->get('anneeNaissance', $utilisateur->getAnneeNaissance()));

        if ($request->request->get('password')) {
            $hashedPassword = $this->passwordHasher->hashPassword($utilisateur, $request->request->get('password'));
            $utilisateur->setPassword($hashedPassword);
        }

        $this->entityManager->flush();

        return $this->json($utilisateur, Response::HTTP_OK);
    }

    #[Route('/delete/{id}', name: 'utilisateur_delete', methods: ['DELETE'])]
    public function delete(Utilisateur $utilisateur): Response
    {
        $this->entityManager->remove($utilisateur);
        $this->entityManager->flush();

        return $this->json(['status' => 'User deleted'], Response::HTTP_NO_CONTENT);
    }
}
