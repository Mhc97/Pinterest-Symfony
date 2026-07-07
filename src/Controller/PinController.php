<?php

namespace App\Controller;

use App\Entity\Pin;
use App\Form\PinType;
use App\Repository\PinRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/pin')]
final class PinController extends AbstractController
{
    #[Route('', name: 'app_pin_index')]
    public function index(PinRepository $pinRepository): Response
    {
        return $this->render('pin/index.html.twig', [
           'pins' => $pinRepository->findLatest(),
        ]);
    }

    #[Route('/new', name: 'app_pin_new')]
    #[IsGranted('ROLE_USER')]
    public function new(Request $request, EntityManagerInterface $em): Response
    {
        $pin = new Pin();
        $pin->setUser($this->getUser());
        $pin->setCreatedAt(new \DateTimeImmutable());
        $pin->setUpdatedAt(new \DateTimeImmutable());

        $form = $this->createForm(PinType::class, $pin);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){
            $em->persist($pin);
            $em->flush();

            $this->addFlash('success', 'Pin publié avec succès !');
            return $this->redirectToRoute('app_pin_index');
        }

        return $this->render('pin/new.html.twig', [
            'form' => $form,
            'pin' => $pin,
        ]);
    }

    #[Route('/{id}', name: 'app_pin_show', requirements: ['id' => '\d+'])]
    public function show(Pin $pin): Response
    {
        return $this->render('pin/show.html.twig', [
            'pin' => $pin,
        ]);
    }

     #[Route('/{id}/edit', name: 'app_pin_edit', requirements: ['id' => '\d+'])]
    #[IsGranted('ROLE_USER')]
    public function edit(Request $request, Pin $pin, EntityManagerInterface $em): Response
    {
        if ($pin->getUser() !== $this->getUser()) {
            throw $this->createAccessDeniedException('Ce pin ne vous appartient pas.');
        }

        $form = $this->createForm(PinType::class, $pin, ['is_new' => false]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $pin->setUpdatedAt(new \DateTimeImmutable());
            $em->flush();

            $this->addFlash('success', 'Pin modifié avec succès !');
            return $this->redirectToRoute('app_pin_show', ['id' => $pin->getId()]);
        }
        return $this->render('pin/edit.html.twig', [
            'form' => $form,
            'pin' => $pin,
        ]);
    }

    #[Route('/{id}/delete', name: 'app_pin_delete', requirements: ['id' => '\d+'])]
    #[IsGranted('ROLE_USER')]
    public function delete(Request $request, Pin $pin, EntityManagerInterface $em): Response
    {
        if ($pin->getUser() !== $this->getUser()) {
            throw $this->createAccessDeniedException('Ce pin ne vous appartient pas.');
        }

        if ($this->isCsrfTokenValid('delete' . $pin->getId(), $request->request->get('_token'))) {
            $em->remove($pin);
            $em->flush();
            $this->addFlash('success', 'Pin supprimé !');
        }

        return $this->redirectToRoute('app_pin_index');
    }

}


