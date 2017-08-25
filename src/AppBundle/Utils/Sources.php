<?php

namespace AppBundle\Utils;

class Sources
{

    private $sources;

    public function __construct()
    {
        $this->sources = array(
            'TDD' => array(
                'separator' => '|'
            ),
            'ZNX' => array(
                'separator' => ','
            )
        );
    }

    public function getSeparator($source)
    {
        return $this->sources[$source]['separator'];
    }
}