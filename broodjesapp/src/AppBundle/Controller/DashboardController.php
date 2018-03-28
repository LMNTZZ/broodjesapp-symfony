<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Bestelling;
use AppBundle\Entity\Soep;
use AppBundle\Entity\Supplement;
use AppBundle\Entity\Brood;
use AppBundle\Entity\Beleg;
use AppBundle\Service\UserService;
use AppBundle\Service\BestellingService;
use AppBundle\Service\SoepService;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\Request;

/**
 * dashboard Controller
 * @Route("/dashboard")
 */

class DashboardController extends Controller
{
    private $userService;
    private $bestellingService;
    private $soepService;

    public function __construct(UserService $userService, BestellingService $bestellingService, SoepService $soepService)
    {
        $this->userService = $userService;
        $this->soepService = $soepService;
        $this->bestellingService = $bestellingService;
    }

    /**
     * @Route("/", name="dashboard_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $today = date('N'); 
        $bestellingen = $this->bestellingService->fetchAllBestellingen();

        $soepvdag = $this->soepService->fetchSoepVanDeDag($today);
        $users = $this->userService->fetchAllUsers();

        if(empty($soepRow))
        {
            $soepvdag = 'nog geen soepen in database';
        }
        else{

            $soepvdag = $soepRow['soep'];
        }
        
        return $this->render('dashboard/index.html.twig', [
            'bestellingen' => $bestellingen,
            'soepvdag' => $soepvdag,
            'users' => $users,
        ]);    
    }
    
    /**
    * @Route("/dashboard/soep/{id}/edit", name="dashboard_soep_edit")
    * @Method({"GET", "POST"})
    */
    public function editAction(Request $request, Soep $soep)
    {
        $editForm = $this->createForm('AppBundle\Form\SoepvdagType', $soep);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('dashboard_index', array('id' => $bestelling->getId()));
        }

        return $this->render('dashboard/edit_soep.html.twig', array(
            'soep' => $soep,
            'edit_soep_form' => $editForm->createView(),
        ));    
    }
}