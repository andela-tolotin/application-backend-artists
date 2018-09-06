<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use App\Repository\ArtistRepository;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;

class ArtistController extends AbstractController
{
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
		$artist = null;
		if (count($artists) > 0) {
			$artist = $artists[0];
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

        return new Response(
        	$serializer->serialize($data, 'json'),
        	200,
        	[
        		'Content-Type' => 'application/json'
        	]
        );
    }
}
