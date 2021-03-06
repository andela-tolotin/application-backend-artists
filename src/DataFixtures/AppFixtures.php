<?php

namespace App\DataFixtures;

use App\Entity\Song;
use App\Entity\Album;
use App\Entity\Artist;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
    	// Read the JSON file to get the artists data
    	$artists = $this->readFile();

        foreach ($artists as $artistField) {
        	$artist = $this->addArtist($artistField);
        	// Persist artist
        	$manager->persist($artist);
        	// Set Artist albums
        	$albums = $artistField['albums'];
        	foreach ($albums as $albumField) {
        		$album = $this->addAlbum($artist, $albumField);
        		// Persist album
        		$manager->persist($album);
        		// Set Songs per album
        		$songs = $albumField['songs'];
        		foreach ($songs as $songField) {
        			$song = $this->addSong($album, $songField);
        			// Persist song
        			$manager->persist($song);
        		}
        	}
        }

        $manager->flush();
    }

    public function readFile(): ?array
    {
    	$jsonString = file_get_contents(__DIR__.'/data/artist-albums.json');

    	return json_decode($jsonString, true);
    }

    public function addArtist($artistField): ?Artist
    {
        $artist = new Artist();
        $artist->setName($artistField['name']);
        $artist->setToken($artist->getToken());

        return $artist;
    }

    public function addSong($album, $songField): ?Song
    {
        $song = new Song();
        $song->setTitle($songField['title']);
        $song->setLength($songField['length']);
        // Set Album on songs
        $song->setAlbum($album);

        return $song;
    }

    public function addAlbum($artist, $albumField): ?Album
    {
        $album = new Album();
        $album->setTitle($albumField['title']);
        $album->setCover($albumField['cover']);
        $album->setDescription($albumField['description']);
        $album->setToken($album->getToken());
        // Set artists
        $album->setArtist($artist);

        return $album;
    }
}
