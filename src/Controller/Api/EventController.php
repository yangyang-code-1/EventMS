<?php

namespace App\Controller\Api;

use App\Entity\Event;
use App\Repository\EventRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api/events')]
class EventController extends AbstractController
{
    #[Route('', name: 'api_event_index', methods: ['GET'])]
    public function index(EventRepository $eventRepository): JsonResponse
    {
        $events = $eventRepository->findAll();

        $data = array_map(fn(Event $event) => [
            'id' => $event->getId(),
            'title' => $event->getTitle(),
            // add other fields you have on your Event entity
        ], $events);

        return $this->json($data);
    }

    #[Route('/{id}', name: 'api_event_show', methods: ['GET'])]
    public function show(Event $event): JsonResponse
    {
        return $this->json([
            'id' => $event->getId(),
            'title' => $event->getTitle(),
            // add other fields
        ]);
    }

    #[Route('', name: 'api_event_create', methods: ['POST'])]
    public function create(Request $request, EntityManagerInterface $em): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        $event = new Event();
        $event->setTitle($data['title']);
        // set other fields

        $em->persist($event);
        $em->flush();

        return $this->json(['id' => $event->getId()], 201);
    }

    #[Route('/{id}', name: 'api_event_delete', methods: ['DELETE'])]
    public function delete(Event $event, EntityManagerInterface $em): JsonResponse
    {
        $em->remove($event);
        $em->flush();

        return $this->json(null, 204);
    }
}