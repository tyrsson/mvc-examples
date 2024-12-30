<?php

declare(strict_types=1);

namespace Application\Form\Authentication;

use Laminas\Filter\StringTrim;
use Laminas\Filter\StripTags;
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
    public function __construct($name = null, $options = [])
    {
        parent::__construct($name, $options);

        $this->setAttribute('method', 'post');
        $this->setAttribute('action', '');
        $this->setAttribute('class', 'sign-in-form');
        $this->setAttribute('id', 'sign-in-form');
        $this->setAttribute('role', 'form');

        $this
            ->add([
                    'type'       => Email::class,
                    'name'       => 'username',
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
            'username'   => [
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
                    [
                        'name' => StringTrim::class,
                    ],
                    [
                        'name' => StripTags::class,
                    ],
                ],
                'validators' => [
                ],
            ],
            'rememberMe' => [
                'required' => false,
                'filters'  => [
                    function ($value) {
                        return ($value !== null);
                    },
                ],
            ],
        ];
    }
}
