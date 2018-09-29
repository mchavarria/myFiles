<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

/**
 * Class AccountController
 * @Route("/account")
 */
class AccountController extends Controller
{
    /**
     * @Template("@App/Account/index.html.twig")
     *
     * @Route("/index", name="app_account_index")
     *
     * @return array
     */
    public function indexAction()
    {
        return [
            'files' => []
        ];
    }
    
}
