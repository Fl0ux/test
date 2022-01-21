<?php

namespace App\Entity;

class Country
{
    /**
     * @var string
     */
    private $name;

    /**
     * @var string
     */
    private $code;

    /**
     * @var float
     */
    private $vatRate;

    public function __construct(string $name, int $code, string $vatRate)
    {
        $this->name = $name;
        $this->code = $code;
        $this->vatRate = $vatRate;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getCode()
    {
        return $this->code;
    }

    public function getVatRate()
    {
        return $this->vatRate;
    }
}
