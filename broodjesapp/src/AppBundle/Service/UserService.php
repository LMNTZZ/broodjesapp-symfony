<?php

namespace AppBundle\Service;

use AppBundle\Entity\User;
use AppBundle\Service\Exception\ConnectivityException;
use Doctrine\DBAL\DBALException;
use Doctrine\ORM\EntityManagerInterface;

class UserService
{
    private $entityManager;
    private $repository;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->repository = $entityManager->getRepository('AppBundle:User');
        $this->entityManager = $entityManager;
        
    }
    
    public function fetchAllUsers()
    {
        return $this->repository->findAll();
    }
}