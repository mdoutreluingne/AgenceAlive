<?php 

namespace App\Service;

use App\Entity\Log;
use Doctrine\ORM\EntityManagerInterface;
use Monolog\Handler\AbstractProcessingHandler;

class DbHandler extends AbstractProcessingHandler
{
    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        parent::__construct();
        $this->em = $em;
    }

    protected function write(array $record): void 
    {
        //Ici on envoi le log dans la bdd
        $log = new Log();

        $log->setContext($record['context']);
        $log->setLevel($record['level']);
        $log->setLevelName($record['level_name']);
        $log->setMessage($record['message']);
        $log->setExtra($record['extra']);
        $log->setUser($record['extra']['user']);

        $this->em->persist($log);
        $this->em->flush();
    }
}
?>