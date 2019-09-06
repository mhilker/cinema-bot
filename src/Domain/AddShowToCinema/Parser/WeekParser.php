<?php

declare(strict_types=1);

namespace CinemaBot\Domain\AddShowToCinema\Parser;

use CinemaBot\Domain\ExistingFile;
use CinemaBot\Domain\URL;
use CinemaBot\Domain\URLs;
use DOMDocument;
use DOMXPath;

final class WeekParser
{
    public function parse(ExistingFile $fileName): URLs
    {
        $document = new DOMDocument();
        @$document->loadHTMLFile($fileName->asString());
        $xpath = new DOMXPath($document);

        $urls = [];

        $nodes = $document->getElementsByTagName('link');
        foreach ($nodes as $node) {
            if ($node->getAttribute('rel') === 'canonical') {
                $urls[] = URL::from($node->getAttribute('href'));
            }
        }

        $baseURI = rtrim((string) $document->baseURI, '/');
        $nodes = $xpath->query('//*[@id="movieWeekSelect"]/option');

        foreach ($nodes as $node) {
            $value = $node->getAttribute('value');
            if ($value === '') {
                continue;
            }

            $urls[] = URL::from($baseURI . $value);
        }

        return URLs::from($urls);
    }
}
