<?php

$finder = Symfony\CS\Finder\DefaultFinder::create()->in('.')
;

return Symfony\CS\Config\Config::create()
    ->setUsingCache(true)
    ->fixers(['-phpdoc_short_description', 'ordered_use'])
    ->finder($finder)
;
