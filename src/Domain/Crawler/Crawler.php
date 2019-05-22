<?php

declare(strict_types=1);

namespace CinemaBot\Domain\Crawler;

use CinemaBot\Domain\Downloader\CopyDownloader;
use CinemaBot\Domain\Movies;
use CinemaBot\Domain\Parser\DOMParser;
use CinemaBot\Domain\URL;

class Crawler
{
    public function crawl(): Movies
    {
        $url = URL::from('https://www.cinemotion-kino.de/hameln/kinoprogramm');

        $downloader = new CopyDownloader();
        $fileName = $downloader->download($url);

        $weekParser = new WeekParser();
        $urls = $weekParser->parse($fileName);

        $fileNames = [];
        foreach ($urls as $url) {
            $fileNames[] = $downloader->download($url);
        }

        $movieList = Movies::from([]);

        $parser = new DOMParser();
        foreach ($fileNames as $fileName) {
            $movies = $parser->parse($fileName);
            foreach ($movies as $movie) {
                $movieList->add($movie);
            }
        }

        return $movieList;
    }
}
