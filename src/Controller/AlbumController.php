<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\AlbumRepository;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;

class AlbumController extends AbstractController
{
	use \App\Utils\JsonResponseTrait;

	private $albumRepository;

	public function __construct(AlbumRepository $albumRepository)
	{
		$this->albumRepository = $albumRepository;
	}

    /**
     * @Route("/album", name="album")
     */
    public function index()
    {
        $albums = $this->albumRepository->findAll();

		$encoder = new JsonEncoder();
		$normalizer = new ObjectNormalizer();

		$normalizer->setCircularReferenceHandler(function ($object) {
			return $object->getTitle();
		});

		$serializer = new Serializer(array($normalizer), array($encoder));

		$data = $serializer->normalize($albums, null, 
			[
				'attributes' => [
					'token', 'title', 'cover', 'description', 
					'songs' => [
						'title', 'length'
					],
					'artist' => [
						'name', 'token'
					]
				]
			]
		);

		return $this->sendJsonResponse($serializer->serialize($data, 'json'), 200);
    }
}
