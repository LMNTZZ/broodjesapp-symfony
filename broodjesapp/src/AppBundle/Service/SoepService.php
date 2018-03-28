<?php

namespace AppBundle\Service;

use AppBundle\Entity\Soep;
use AppBundle\Service\Exception\ConnectivityException;
use Doctrine\DBAL\DBALException;
use Doctrine\ORM\EntityManagerInterface;

class SoepService
{
    private $entityManager;
    private $repository;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->repository = $entityManager->getRepository('AppBundle:Soep');
        $this->entityManager = $entityManager;
        
    }

    public function fetchSoepVanDeDag($id)
    {
        return $this->repository->findBy(['id' => $id]);
    }

    
}