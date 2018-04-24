<?php
/**
 * Created by PhpStorm.
 * User: manu
 * Date: 24/04/2018
 * Time: 18:55
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
