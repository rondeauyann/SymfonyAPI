<?php

namespace App\Controller;


use App\Repository\ArtistRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/artist')]
class ArtistController extends AbstractController
{

    public function __construct(private ArtistRepository $artistRepository) {}


    #[Route('/new', name: 'artist_new', methods: ['GET','POST'])]
    public function new(Request $request): JsonResponse
    {

        $data = json_decode($request->getContent(), true);

        $name = $data['name'];
        $image = $data['image'];
        $description = $data['description'];

        if (empty($name) || empty($image) || empty($description))
        {
            throw new NotFoundHttpException('Expecting mandatory parameters!');
        }

        $this->artistRepository->saveArtist($name, $image, $description);

        return new JsonResponse(['status' => 'Artist created!'], Response::HTTP_CREATED);
    }

    #[Route('/artist/{id}', name: 'get_one_artist', methods: 'GET')]
    public function get($id): JsonResponse
    {
        $artist = $this->artistRepository->findOneBy(['id' => $id]);

        $data = [
            'id' => $artist->getId(),
            'name' => $artist->getName(),
            'image' => $artist->getImage(),
            'description' => $artist->getDescription(),
        ];

        return new JsonResponse($data, Response::HTTP_OK);
    }

    #[Route('/all', name: 'get_all_artists', methods: 'GET')]
    public function getAll(): JsonResponse
    {
        $artists = $this->artistRepository->findAll();
        $data = [];

        foreach ($artists as $artist)
        {
            $data[] = [
                'id' => $artist->getId(),
                'name' => $artist->getName(),
                'image' => $artist->getImage(),
                'description' => $artist->getDescription(),
            ];
        }

        return new JsonResponse($data, Response::HTTP_OK);
    }

    #[Route('/artist/{id}', name: 'update_artist', methods: 'PUT')]
    public function update($id, Request $request): JsonResponse
    {
        $artist = $this->artistRepository->findOneBy(['id' => $id]);
        $data = json_decode($request->getContent(), true);

        empty($data['name']) ? true : $artist->setName($data['name']);
        empty($data['image']) ? true : $artist->setImage($data['image']);
        empty($data['description']) ? true : $artist->setDescription($data['description']);

        $updatedArtist = $this->artistRepository->updateArtist($artist);

        return new JsonResponse($updatedArtist->toArray(), Response::HTTP_OK);
    }

    #[Route('/artist/{id}', name: 'delete_artist', methods: 'DELETE')]
    public function delete($id): JsonResponse
    {
        $artist = $this->artistRepository->findOneBy(['id' => $id]);

        $this->artistRepository->removeArtist($artist);

        return new JsonResponse(['status' => 'artist deleted'], Response::HTTP_NO_CONTENT);
    }
}
