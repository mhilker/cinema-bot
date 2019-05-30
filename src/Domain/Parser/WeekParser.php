<?php

declare(strict_types=1);

namespace CinemaBot\Domain\Parser;

use CinemaBot\Domain\ExistingFile;
use CinemaBot\Domain\URL;
use CinemaBot\Domain\URLs;
use DOMDocument;
use DOMXPath;

class WeekParser
{
    public function parse(ExistingFile $fileName): URLs
    {
        $document = new DOMDocument();
        @$document->loadHTMLFile($fileName->asString());
        $xpath = new DOMXPath($document);

        $urls = URLs::from([]);

        $nodes = $document->getElementsByTagName('link');
        foreach ($nodes as $node) {
            if ($node->getAttribute('rel') === 'canonical') {
                $href = $node->getAttribute('href');
                $urls->add(URL::from($href));
            }
        }


        $baseURI = rtrim((string) $document->baseURI, '/');
        $nodes = $xpath->query('//*[@id="movieWeekSelect"]/option');

        foreach ($nodes as $node) {
            $value = $node->getAttribute('value');
            if ($value === '') {
                continue;
            }

            $url = URL::from($baseURI . $value);
            $urls->add($url);
        }

        return $urls;
    }
}
