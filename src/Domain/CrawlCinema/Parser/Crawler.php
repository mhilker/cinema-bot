<?php

declare(strict_types=1);

namespace CinemaBot\Domain\CrawlCinema\Parser;

use CinemaBot\Domain\CrawlCinema\Downloader\Downloader;
use CinemaBot\Domain\Movies;
use CinemaBot\Domain\URL;

final class Crawler
{
    private Downloader $downloader;
    private Parser $parser;

    public function __construct(Downloader $downloader, Parser $parser)
    {
        $this->downloader = $downloader;
        $this->parser = $parser;
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

        foreach ($responses as $response) {
            $movies = $this->parser->parse($response);
            foreach ($movies as $movie) {
                $movieList[] = $movie;
            }
        }

        return Movies::from($movieList);
    }
}
