<?php

namespace App\Controller;


use App\Repository\EventRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/event')]
class EventController extends AbstractController
{
    public function __construct(private EventRepository $eventRepository) {}

    #[Route('/new', name: 'add_event', methods: 'POST')]
    public function add(Request $request): JsonResponse
    {

        $data = json_decode($request->getContent(), true);

        $artist = $data['artist'];
        $hour = $data['hour'];
        $stage = $data['stage'];
        $image = $data['image'];

        if (empty($name) || empty($hour) || empty($stage) || empty($image))
        {
            throw new NotFoundHttpException('Expecting mandatory parameters!');
        }

        $this->eventRepository->saveEvent($name, $hour, $stage, $artist);

        return new JsonResponse(['status' => 'Event created!'], Response::HTTP_CREATED);
    }

    #[Route('/event/{id}', name: 'get_one_event', methods: 'GET')]
    public function get($id): JsonResponse
    {
        $event = $this->eventRepository->findOneBy(['id' => $id]);

        $data = [
            'id' => $event->getId(),
            'artist' => $event->getArtist(),
            'hour' => $event->getHour(),
            'stage' => $event->getStage(),
            'image' => $event->getImage(),
        ];

        return new JsonResponse($data, Response::HTTP_OK);
    }

    #[Route('/event', name: 'get_all_events', methods: 'GET')]
    public function getAll(): JsonResponse
    {
        $events = $this->eventRepository->findAll();
        $data = [];

        foreach ($events as $event)
        {
            $data[] = [
                'id' => $event->getId(),
                'artist' => $event->getArtist(),
                'hour' => $event->getHour(),
                'stage' => $event->getStage(),
                'image' => $event->getImage(),
            ];
        }

        return new JsonResponse($data, Response::HTTP_OK);
    }

    #[Route('/event/{id}', name: 'update_event', methods: 'PUT')]
    public function update($id, Request $request): JsonResponse
    {
        $event = $this->eventRepository->findOneBy(['id' => $id]);
        $data = json_decode($request->getContent(), true);

        empty($data['artist']) ? true : $event->setArtist($data['artist']);
        empty($data['hour']) ? true : $event->setName($data['hour']);
        empty($data['stage']) ? true : $event->setStage($data['stage']);
        empty($data['image']) ? true : $event->setDay($data['image']);

        $updatedEvent = $this->eventRepository->updateEvent($event);

        return new JsonResponse($updatedEvent->toArray(), Response::HTTP_OK);
    }

    #[Route('/event/{id}', name: 'delete_event', methods: 'DELETE')]
    public function delete($id): JsonResponse
    {
        $user = $this->eventRepository->findOneBy(['id' => $id]);

        $this->eventRepository->removeEvent($user);

        return new JsonResponse(['status' => 'Event deleted'], Response::HTTP_NO_CONTENT);
    }
}
