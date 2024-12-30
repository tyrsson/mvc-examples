<?php

declare(strict_types=1);

namespace User\Controller;

use Laminas\Http\PhpEnvironment\Request;
use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\View\Model\ViewModel;
use User\Form\SignInForm;

final class LoginController extends AbstractActionController
{
    /** @var Request */
    protected $request;

    public function __construct(
        private SignInForm $signInForm
    ) {
    }

    public function indexAction(): ViewModel
    {
        if ($this->request->isPost()) {
            $data = $this->request->getPost()->toArray();
            $this->signInForm->setData($this->request->getPost()->toArray());
            if ($this->signInForm->isValid()) {
                $this->redirect()->toRoute('home');
            }
        }
        return new ViewModel(['form' => $this->signInForm]);
    }
}
