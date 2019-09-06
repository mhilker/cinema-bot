<?php

declare(strict_types=1);

namespace CinemaBot\Domain\Parser;

use CinemaBot\Domain\Downloader\CopyDownloader;
use CinemaBot\Domain\Movies;
use CinemaBot\Domain\URL;

final class Crawler
{
    public function crawl(URL $url): Movies
    {
        $downloader = new CopyDownloader();
        $fileName = $downloader->download($url);

        $weekParser = new WeekParser();
        $parsedURLs = $weekParser->parse($fileName);

        $fileNames = [];
        foreach ($parsedURLs as $parsedURL) {
            $fileNames[] = $downloader->download($parsedURL);
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
