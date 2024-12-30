<?php

declare(strict_types=1);

namespace User\Form;

use Laminas\Filter\StringTrim;
use Laminas\Filter\StripTags;
use Laminas\Filter\ToInt;
use Laminas\Form\Element\Button;
use Laminas\Form\Element\Checkbox;
use Laminas\Form\Element\Email;
use Laminas\Form\Element\Hidden;
use Laminas\Form\Element\Password;
use Laminas\Form\Element\Text;
use Laminas\Form\Form;
use Laminas\InputFilter\InputFilterProviderInterface;
use Laminas\Validator\Between;

class PersonalSignUpForm extends Form implements InputFilterProviderInterface
{
    public function __construct($name = 'personal-sign-up-form', $options = [])
    {
        parent::__construct($name, $options);
    }

    public function init()
    {
        $this->setAttribute('method', 'post');
        $this->setAttribute('class', 'personal-sign-up-form');
        $this->setAttribute('id', 'personal-sign-up-form');
        $this->setAttribute('role', 'form');

        $this
            ->add([
                'type'       => Email::class,
                'name'       => 'username',
                'attributes' => [
                    'id'             => 'createAccountForm-username',
                    'required'       => true,
                    'class'          => 'form-control',
                    'autocomplete'   => 'username',
                    'autocapitalize' => 'none',
                    'maxlength'      => 255,
                    'placeholder'    => 'jsmith@mail.com',
                ],
                'options'    => [
                    'label'            => 'Email',
                    'label_attributes' => [
                        'class' => 'control-label required',
                    ],
                ],
            ])
            ->add([
                'type'       => Password::class,
                'name'       => 'password',
                'attributes' => [
                    'id'             => 'createAccountForm-password',
                    'required'       => true,
                    'class'          => 'form-control',
                    'autocomplete'   => 'new-password',
                    'autocapitalize' => 'none',
                    'minlength'      => 6,
                    'maxlength'      => 16,
                ],
                'options'    => [
                    'label'            => 'Password',
                    'label_attributes' => [
                        'class' => 'control-label required',
                    ],
                ],
            ])
            ->add([
                'type'       => Password::class,
                'name'       => 'confirm_password',
                'attributes' => [
                    'id'             => 'createAccountForm-confirm_password',
                    'required'       => true,
                    'class'          => 'form-control',
                    'autocomplete'   => 'new-password',
                    'autocapitalize' => 'none',
                    'minlength'      => 6,
                    'maxlength'      => 16,
                ],
                'options'    => [
                    'label'            => 'Confirm Password',
                    'label_attributes' => [
                        'class' => 'control-label required',
                    ],
                ],
            ])
            ->add([
                'type'       => Text::class,
                'name'       => 'first_name',
                'attributes' => [
                    'id'             => 'createAccountForm-first_name',
                    'required'       => true,
                    'class'          => 'form-control',
                    'autocomplete'   => 'given-name',
                    'autocapitalize' => 'words',
                    'maxlength'      => 35,
                    'placeholder'    => 'John',
                ],
                'options'    => [
                    'label'            => 'First Name',
                    'label_attributes' => [
                        'class' => 'control-label required',
                    ],
                ],
            ])
            ->add([
                'type'       => Text::class,
                'name'       => 'last_name',
                'attributes' => [
                    'id'             => 'createAccountForm-last_name',
                    'required'       => true,
                    'class'          => 'form-control',
                    'autocomplete'   => 'family-name',
                    'autocapitalize' => 'words',
                    'maxlength'      => 35,
                    'placeholder'    => 'Smith',
                ],
                'options'    => [
                    'label'            => 'Last Name',
                    'label_attributes' => [
                        'class' => 'control-label required',
                    ],
                ],
            ])
            ->add([
                'type'       => Text::class,
                'name'       => 'address',
                'attributes' => [
                    'id'             => 'createAccountForm-address',
                    'required'       => true,
                    'class'          => 'form-control',
                    'autocomplete'   => 'family-name',
                    'autocapitalize' => 'words',
                    'maxlength'      => 255,
                    'placeholder'    => '76 Main St',
                ],
                'options'    => [
                    'label'            => 'Address',
                    'label_attributes' => [
                        'class' => 'control-label required',
                    ],
                ],
            ])
            ->add([
                'type'       => Text::class,
                'name'       => 'zip',
                'attributes' => [
                    'id'             => 'createAccountForm-zip',
                    'required'       => true,
                    'class'          => 'form-control',
                    'autocomplete'   => 'family-name',
                    'autocapitalize' => 'words',
                    'maxlength'      => 6,
                    'placeholder'    => '06106',
                ],
                'options'    => [
                    'label'            => 'Zip',
                    'label_attributes' => [
                        'class' => 'control-label required',
                    ],
                ],
            ])
            ->add([
                'type'       => Text::class,
                'name'       => 'city',
                'attributes' => [
                    'id'             => 'createAccountForm-city',
                    'required'       => true,
                    'class'          => 'form-control',
                    'autocomplete'   => 'family-name',
                    'autocapitalize' => 'words',
                    'maxlength'      => 255,
                    'readonly'       => true,
                    'placeholder'    => 'Hartford',
                ],
                'options'    => [
                    'label'            => 'City (read only)',
                    'label_attributes' => [
                        'class' => 'control-label required',
                    ],
                ],
            ])
            ->add([
                'type'       => Checkbox::class,
                'name'       => 'agree',
                'attributes' => [
                    'id'       => 'createAccountForm-agree',
                    'class'    => 'form-check-input',
                    'required' => true,
                ],
                'options'    => [
                    'use_hidden_element' => false,
                    'label'              => 'I have read and agree to the <a href="#" class="terms-link" title="Terms Agreement">Terms and Conditions of Usage</a>',
                    'label_attributes'   => [
                        'class' => 'form-check-label required',
                    ],
                    'label_options'      => [
                        'disable_html_escape' => true,
                    ],
                ],
            ])
            ->add([
                'type'       => Hidden::class,
                'name'       => 'account_type',
                'attributes' => [
                    'id'       => 'createAccountForm-account_type',
                    'value'    => '3',
                ],
            ])
            ->add([
                'type'       => Hidden::class,
                'name'       => 'zip_id',
                'attributes' => [
                    'id'       => 'createAccountForm-zip_id',
                    'value'    => '0',
                ],
            ])
            ->add([
                'type'       => Button::class,
                'name'       => 'submitButton',
                'attributes' => [
                    'type'  => 'submit',
                    'class' => 'btn btn-primary',
                ],
                'options'    => [
                    //TODO implement loading animation for buttons
                    //<span class="indicator-progress">Hang On<span class="spinner-border spinner-border-sm align-middle ms-2"></span></span>
                    'label'         => '<span class="indicator-label">Continue</span>',
                    'label_options' => [
                        'disable_html_escape' => true,
                    ],
                ],
            ]);
    }

    public function getInputFilterSpecification()
    {
        return [
            'username'        => [
                'required'   => true,
                'filters'    => [
                    [
                        'name' => StripTags::class,
                    ],
                    [
                        'name' => StringTrim::class,
                    ],
                ],
                'validators' => [
                    [
                        'name'    => Between::class, /* allows for setting min and max */
                        'options' => [
                            'min' => 1,
                            'max' => 255,
                        ],
                    ],
                ],
            ],
            'password'      => [
                'required'  => true,
                'filters'   => [
                    [
                        'name' => StripTags::class,
                    ],
                    [
                        'name' => StringTrim::class,
                    ]
                ],
                'validators' => [
                    [
                        'name'  => Between::class,
                        'options' => [
                            'min' => 6,
                            'max' => 16,
                        ]
                    ]
                ]
            ],
            'confirm_password'  => [
                'required'  => true,
                'filters'   => [
                    [
                        'name' => StripTags::class,
                    ],
                    [
                        'name' => StringTrim::class,
                    ]
                ],
                'validators' => [
                    [
                        'name'  => Between::class,
                        'options' => [
                            'min' => 6,
                            'max' => 16,
                        ]
                    ]
                ]
            ],
            'first_name'    => [
                'required'  => true,
                'filters'   => [
                    [
                        'name' => StripTags::class,
                    ],
                    [
                        'name' => StringTrim::class,
                    ]
                ],
                'validators' => [
                    [
                        'name' => Between::class,
                        'options' => [
                            'min' => 1,
                            'max' => 35,
                        ]
                    ]
                ]
            ],
            'last_name'     => [
                'required'  => true,
                'filters'   => [
                    [
                        'name' => StripTags::class,
                    ],
                    [
                        'name' => StringTrim::class,
                    ]
                ],
                'validators' => [
                    [
                        'name' => Between::class,
                        'options' => [
                            'min' => 1,
                            'max' => 35
                        ]
                    ]
                ]
            ],
            'address'   => [
                'required'  => true,
                'filters'   => [
                    [
                        'name' => StripTags::class,
                    ],
                    [
                        'name' => StringTrim::class,
                    ]
                ],
                'validators' => [
                    [
                        'name' => Between::class,
                        'options' => [
                            'min' => 1,
                            'max' => 255
                        ]
                    ]
                ]
            ],
            'zip'   => [
                'required'  => true,
                'filters'   => [
                    [
                        'name' => ToInt::class,
                    ],
                ],
                'validators' => [
                    [
                        'name' => Between::class,
                        'options' => [
                            'min' => 6,
                            'max' => 6
                        ]
                    ]
                ]
            ],
        ];
    }
}
