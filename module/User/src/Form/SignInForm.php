<?php

declare(strict_types=1);

namespace User\Form;

use Laminas\Authentication\AuthenticationServiceInterface;
use Laminas\Authentication\Validator\Authentication;
use Laminas\Filter\StringTrim;
use Laminas\Filter\StripTags;
use Laminas\Filter\ToInt;
use Laminas\Form\Element\Button;
use Laminas\Form\Element\Checkbox;
use Laminas\Form\Element\Email;
//use Laminas\Form\Element\Hidden;
use Laminas\Form\Element\Password;
use Laminas\Form\Form;
use Laminas\InputFilter\InputFilterProviderInterface;
use Laminas\Validator\EmailAddress;

class SignInForm extends Form implements InputFilterProviderInterface
{
    public function __construct(
        private AuthenticationServiceInterface $authService,
        private array $authConfig,
        $name = null,
        $options = []
    ) {
        parent::__construct($name, $options);
    }

    public function init()
    {
        $this->setAttribute('method', 'post');
        $this->setAttribute('class', 'sign-in-form');
        $this->setAttribute('id', 'sign-in-form');
        $this->setAttribute('role', 'form');
        $this
        ->add([
            'type'       => Email::class,
            'name'       => $this->authConfig['identity'],
            'attributes' => [
                'id'             => 'signInForm-username',
                'required'       => true,
                'class'          => 'form-control',
                'autocomplete'   => 'username',
                'autocapitalize' => 'none',
                'maxlength'      => 255,
            ],
            'options'    => [
                'label'            => 'Email',
                'label_attributes' => [
                    'class' => 'font-size-h6 text-dark',
                ],
            ],
        ])
        ->add([
            'type'       => Password::class,
            'name'       => 'password',
            'attributes' => [
                'id'             => 'signInForm-password',
                'required'       => true,
                'class'          => 'form-control',
                'autocomplete'   => 'current-password',
                'autocapitalize' => 'none',
                'maxlength'      => 16,
            ],
            'options'    => [
                'label'            => 'Password',
                'label_attributes' => [
                    'class' => 'font-size-h6 text-dark pt-5',
                ],
            ],
        ])
        ->add([
            'type'       => Checkbox::class,
            'name'       => 'rememberMe',
            'attributes' => [
                'id'       => 'signInForm-rememberMe',
                'required' => false,
            ],
            'options'    => [
                'use_hidden_element' => false,
                'label'              => 'Remember Me',
                'label_attributes'   => [
                    'class' => '',
                ],
            ],
        ])
        //<span class="indicator-progress">Hang On<span class="spinner-border spinner-border-sm align-middle ms-2"></span></span>
        ->add([
            'type'       => Button::class,
            'name'       => 'submitButton',
            'attributes' => [
                'type'  => 'submit',
                'class' => 'btn btn-primary',
            ],
            'options'    => [
                'label'         => '<span class="indicator-label">Sign In</span>',
                'label_options' => [
                    'disable_html_escape' => true,
                ],
            ],
        ]);
    }

    public function getInputFilterSpecification()
    {
        return [
            /*'utcOffset'  => [
                'required' => false,
            ],
            'csrf'       => [
                'required' => false,
            ],
            'redirect'   => [
                'required' => false,
            ],*/
            $this->authConfig['identity']   => [
                'required'   => true,
                'filters'    => [
                    [
                        'name' => StringTrim::class,
                    ],
                    [
                        'name' => StripTags::class,
                    ],
                ],
                'validators' => [
                    [
                        'name' => EmailAddress::class,
                    ],
                ],
            ],
            'password'   => [
                'required'   => true,
                'filters'    => [
                    ['name' => StringTrim::class],
                    ['name' => StripTags::class],
                ],
                'validators' => [ // needs a StringLength for the max length for the column
                    [
                        'name'    => Authentication::class,
                        'options' => [
                            'identity'   => $this->authConfig['identity'],
                            'credential' => $this->authConfig['credential'],
                            'service'    => $this->authService,
                            'messages'   => [
                                Authentication::IDENTITY_NOT_FOUND => 'Have you activated your account?',
                                Authentication::CREDENTIAL_INVALID => 'Invalid credentials received',
                            ],
                        ],
                    ],
                ],
            ],
            'rememberMe' => [
                'required' => false,
                'filters'  => [
                    ['name' => ToInt::class],
                ],
            ],
        ];
    }
}
