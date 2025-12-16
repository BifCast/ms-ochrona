<?php

namespace App\Controller;

use App\Repository\EventsRepository;
use App\Repository\UserRepository;
use App\Entity\User;
use App\Entity\Events;
use App\Form\NewEvent;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

class EventsController extends AbstractController
{

    public array $types = [
        1 => 'Interwencja',
        2 => 'Zgłoszenie mieszkańca',
        3 => 'Incydent agresji',
        4 => 'Zdarzenie drogowe',
        5 => 'Inne',
    ];
    // przy zmianie typow nalezy dodac je jeszcze w pliku NewEvent.php:40

    #[Route(path: '/events', name: 'app_events')]
    #[IsGranted('ROLE_USER')]
    public function index(EventsRepository $eventsRepository, userRepository $userRepository): Response
    {
        $user = $this->getUser();
        $events = $eventsRepository->findBy([], ['date' => 'DESC']);

        foreach ($events as $event) {
            $author_ID = $event->getAuthorID();
            $userE = $userRepository->find($author_ID);
            $event->authorName = $userE->getLogin();
            $event->typeName = $this->types[$event->getTypeID()] ?? 'Nieznany';
        }

        return $this->render('events/events.html.twig', [
            'events' => $events,
            'user' => $user
        ]);
    }

    #[Route('/events/new', name: 'event_new')]
    #[Route('/events/{id}', name: 'event')]
    public function new(?int $id, Request $request, EntityManagerInterface $em, EventsRepository $eventsRepository): Response
    {
        if (!$id) {
            $event = new Events();
            $event->setAuthorID($this->getUser()->getId());
        } else {
            $event = $eventsRepository->find($id);
            if (!$event) {
                throw $this->createNotFoundException('Event nie istnieje!');
            }

            $date = $event->getDate();
            $now = new \DateTime();
            if ($date && $now > (clone $date)->modify('+24 hours')) {
                $this->addFlash('error', 'Ten event nie może być edytowany, minęło już 24h od jego utworzenia.');
                return $this->redirectToRoute('app_events');
            }
        }

        $form = $this->createForm(NewEvent::class, $event);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($event);
            $em->flush();
            
            $message = $event->getId() ? 'Event został zaktualizowany!' : 'Event został utworzony!';
            $this->addFlash('success', $message);
            return $this->redirectToRoute('app_events');
        }

        return $this->render('events/new.html.twig', [
            'form' => $form->createView(),
            'event' => $event
        ]);
    }

    #[Route('/events/show/{id}', name: 'event_show')]
    public function show(int $id, EventsRepository $eventsRepository, userRepository $userRepository): Response
    {
        $event = $eventsRepository->find($id);

        if (!$event) {
            throw $this->createNotFoundException('Event nie istnieje');
        }

        $event->typeName = $this->types[$event->getTypeID()] ?? 'Nieznany';

        $author = $userRepository->find($event->getAuthorID());
        $event->authorName = $author ? $author->getLogin() : 'Nieznany';

        return $this->render('events/show.html.twig', [
            'event' => $event,
        ]);
    }
}
