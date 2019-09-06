<?php

declare(strict_types=1);

namespace CinemaBot\Domain\AddShowToCinema\Parser;

use CinemaBot\Domain\AddShowToCinema\Downloader\CopyDownloader;
use CinemaBot\Domain\Movies;
use CinemaBot\Domain\URL;

final class Crawler
{
    public function crawl(URL $url): Movies
    {
        $downloader = new CopyDownloader();
        $response = $downloader->download($url);

        $weekParser = new WeekParser();
        $parsedURLs = $weekParser->parse($response);

        $responses = [];
        foreach ($parsedURLs as $parsedURL) {
            $responses[] = $downloader->download($parsedURL);
        }

        $movieList = [];

        $parser = new DOMParser();
        foreach ($responses as $response) {
            $movies = $parser->parse($response);
            foreach ($movies as $movie) {
                $movieList[] = $movie;
            }
        }

        return Movies::from($movieList);
    }
}
