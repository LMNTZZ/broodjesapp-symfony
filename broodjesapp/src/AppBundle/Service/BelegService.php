<?php

namespace AppBundle\Service;

use AppBundle\Entity\Beleg;
use AppBundle\Service\Exception\ConnectivityException;
use Doctrine\DBAL\DBALException;
use Doctrine\ORM\EntityManagerInterface;

class BelegService
{
    private $entityManager;
    private $repository;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->repository = $entityManager->getRepository('AppBundle:Beleg');
        $this->entityManager = $entityManager;
        
    }

    public function findAllBeleg()
    {
        return $this->repository->findAll();
    }

    public function persist(Beleg $Beleg)
    {
        $this->entityManager->persist($Beleg);
        $this->entityManager->flush();
    }

    public function remove(Beleg $Beleg)
    {
        $this->entityManager->remove($beleg);
        $this->entityManager->flush();
    }
}