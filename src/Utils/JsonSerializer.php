<?php

namespace Pipedrive\Utils;

use JsonSerializable;

class JsonSerializer implements JsonSerializable
{
    private $args;

    public function __construct($input)
    {
        $this->args = $input;
    }

    /**
     * Encode this object to JSON
     * @return mixed
     */
    public function jsonSerialize(): mixed
    {
        return $this->args;
    }
}