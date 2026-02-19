<?php

namespace App\Domain\Exception;

final class InvalidUser extends \InvalidArgumentException
{
    public function __construct(string ...$emptyFields)
    {
        $lastField = array_pop($emptyFields);
        $firstFields = implode(', ', $emptyFields);
        $formattedFields = implode(' and ', array_filter([$firstFields, $lastField]));

        parent::__construct(sprintf("The user's %s cannot be empty.", $formattedFields));
    }
}
