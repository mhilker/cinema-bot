<?php

declare(strict_types=1);

namespace CinemaBot\Domain\AddShowToCinema\Parser;

use CinemaBot\Domain\AddShowToCinema\Downloader\Downloader;
use CinemaBot\Domain\Movies;
use CinemaBot\Domain\URL;

final class Crawler
{
    private Downloader $downloader;

    public function __construct(Downloader $downloader)
    {
        $this->downloader = $downloader;
    }

    public function crawl(URL $url): Movies
    {
        $response = $this->downloader->download($url);

        $weekParser = new WeekParser();
        $parsedURLs = $weekParser->parse($response);

        $responses = [];
        foreach ($parsedURLs as $parsedURL) {
            $responses[] = $this->downloader->download($parsedURL);
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
