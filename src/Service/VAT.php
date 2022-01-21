<?php

namespace App\Service;

use App\Entity\Country;

final class VAT
{
    public static function brandsRules(string $brand): int
    {
        switch ($brand) {
            case 'Farmitoo':
                $percentage = 20;
                break;
            case 'Gallagher':
                $percentage = 5;
                break;
            default:
                $percentage = 20;
        }

        return $percentage;
    }

    public static function countriesRules(Country $country): float
    {
        return $country->getVatRate();
    }
}
