<?php
declare(strict_types=1);

namespace App\JsonRepresentative;

use DateTime;
use JsonSerializable;

class Date extends DateTime implements JsonSerializable
{
    public function jsonSerialize(): string
    {
        return $this->format(DateTime::ATOM);
    }
}