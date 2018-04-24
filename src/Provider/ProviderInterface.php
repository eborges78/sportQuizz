<?php
/**
 * Created by PhpStorm.
 * User: manu
 * Date: 24/04/2018
 * Time: 19:33
 */

namespace App\Provider;


interface ProviderInterface
{
    public function getAllCompetitions(): array;
}