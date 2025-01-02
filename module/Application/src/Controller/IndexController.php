<?php

declare(strict_types=1);

namespace Application\Controller;

use Axleus\Log\Event\LogEvent;
use Axleus\Log\LogChannel;
use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\View\Model\ViewModel;
use Monolog\Level;

class IndexController extends AbstractActionController
{
    public function indexAction()
    {
        $this->getEventManager()->trigger(LogEvent::EVENT_LOG, $this, [
            //'channel' => LogChannel::User,
            'level' => Level::Info,
            'message' => 'Logged from Application\Controller\IndexController::indexAction()',
        ]);
        return new ViewModel();
    }
}
