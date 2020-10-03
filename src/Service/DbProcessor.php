<?php 

namespace App\Service;

use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Security\Core\Security;
use Symfony\Bridge\Monolog\Processor\DebugProcessor;


class DbProcessor
{
    private $request;
    private $security;
    private $test;

    public function __construct(RequestStack $request, Security $security, DebugProcessor $test)
    {
        $this->request = $request->getCurrentRequest();
        $this->security = $security;
        $this->test = $test;
    }

    public function __invoke(array $record)
    {
        dd($this->test->getLogs($this->request));
        //On modifie le record pour ajouter nos infos
        $record['extra']['clientIp'] = $this->request->getClientIp();
        $record['extra']['url'] = $this->request->getBaseUrl();

        $user = $this->security->getUser();
        $record['extra']['user'] = $user;

        return $record;
    }
}

?>