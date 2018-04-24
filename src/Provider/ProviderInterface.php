<?php
/**
 * Emmanuel BORGES
 * contact@eborges.fr
 */

namespace App\Provider;


interface ProviderInterface
{
    public function getAllCompetitions(): array;
}