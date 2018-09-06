<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\ArtistRepository;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;

class ArtistController extends AbstractController
{
	use \App\Utils\JsonResponseTrait;

	private $artistRepository;

	public function __construct(ArtistRepository $artistRepository)
	{
		$this->artistRepository = $artistRepository;
	}

    /**
     * @Route("/artist", name="artist")
     */
    public function index()
    {
    	$artists = $this->artistRepository->findAll();

		$encoder = new JsonEncoder();
		$normalizer = new ObjectNormalizer();

		$normalizer->setCircularReferenceHandler(function ($object) {
			return $object->getTitle();
		});

		$serializer = new Serializer(array($normalizer), array($encoder));

		$data = $serializer->normalize($artists, null, 
			[
				'attributes' => [
					'name','token', 'albums' => [
						'token', 'title', 'cover'
					]
				]
			]
		);

		return $this->sendJsonResponse($serializer->serialize($data, 'json'), 200);
    }

    /**
     * @Route("/artist/{token}", name="get_artist")
     */
    public function getArtist($token)
    {
    	$artist = $this->artistRepository->findOneByToken($token);

    	$encoder = new JsonEncoder();
		$normalizer = new ObjectNormalizer();

		$normalizer->setCircularReferenceHandler(function ($object) {
			return $object->getTitle();
		});

		$serializer = new Serializer(array($normalizer), array($encoder));

    	if (!$artist instanceof \App\Entity\Artist) {
    		return $this->sendJsonResponse($serializer->serialize(
    			[
    				'message' => 'Artist does not exist'
    			], 
    			'json'), 404);
    	}

		$data = $serializer->normalize($artist, null, 
			[
				'attributes' => [
					'name','token', 'albums' => [
						'token', 'title', 'cover'
					]
				]
			]
		);

		return $this->sendJsonResponse($serializer->serialize($data, 'json'), 200);
    }

    
}
