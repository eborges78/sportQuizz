<?php
/**
 * Emmanuel BORGES
 * contact@eborges.fr
 */

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Routing\Annotation\Route;

class AdminController extends Controller
{
    /**
     * @Route("/", name="admin.index")
     * @return string
     */
    public function index()
    {
        return $this->render('admin/index.html.twig');
    }
}
