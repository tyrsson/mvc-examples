<?php

declare(strict_types=1); // MUST be present

namespace User\Entity;

use Application\Entity\EntityInterface;
use Laminas\InputFilter\Exception\RuntimeException;
use Laminas\InputFilter\Factory;
use Laminas\InputFilter\InputFilter;
use Laminas\InputFilter\InputFilterAwareInterface;
use Laminas\InputFilter\InputFilterInterface;
use Laminas\Stdlib\ArrayObject;

use function password_hash;

use const PASSWORD_DEFAULT;

/*
 * User can be two things, a customer or a business, their role_id determines this and what
 * information is required and whether they can post or take reservations
 *
 * Business details are stored in a separate table and the user is given a business id number
 */

class User extends ArrayObject implements EntityInterface, InputFilterAwareInterface
{
    /**
     * We type this to the interface so that we can change this to a different implementation
     * @var InputFilterInterface
     */
    private InputFilterInterface $inputFilter;

    public const ACTIVE = 1;
    public const INACTIVE = 0;

    public function __construct(array $data = [])
    {
        parent::__construct($data, self::ARRAY_AS_PROPS);
    }

    public function getInputFilter()
    {
        /**
         * I will check this usage of the input filter later
         */
        if (! $this->inputFilter) {
            $inputFilter = new InputFilter();
            $factory = new Factory();

            $inputFilter->add($factory->createInput([
                'name'     => 'id',
                'required' => true,
                'filters'  => [
                    ['name' => 'ToInt'],
                ],
            ]));

            $inputFilter->add($factory->createInput([
                'name'       => 'username',
                'required'   => true,
                'filters'    => [
                    ['name' => 'StringTrim'],
                    ['name' => 'StripTags'],
                ],
                'validators' => [
                    [
                        'name'    => 'StringLength',
                        'options' => [
                            'encoding' => 'UTF-8',
                            'min'      => 1,
                            'max'      => 255,
                        ],
                    ],
                ],
            ]));

            $inputFilter->add($factory->createInput([
                'name'       => 'password',
                'required'   => true,
                'filters'    => [
                    ['name' => 'StringTrim'],
                    ['name' => 'StripTags'],
                ],
                'validators' => [
                    [
                        'name'    => 'StringLength',
                        'options' => [
                            'encoding' => 'UTF-8',
                            'min'      => 1,
                            'max'      => 255,
                        ],
                    ],
                ],
            ]));

            $inputFilter->add($factory->createInput([
                'name'     => 'role_id',
                'required' => false,
                'filters'  => [
                    ['name' => 'ToInt'],
                ],
            ]));

            $inputFilter->add($factory->createInput([
                'name'       => 'status',
                'required'   => true,
                'filters'    => [
                    ['name' => 'StringTrim'],
                    ['name' => 'StripTags'],
                ],
                'validators' => [
                    [
                        'name'    => 'InArray',
                        'options' => [
                            'haystack' => [self::ACTIVE, self::INACTIVE],
                        ],
                    ],
                ],
            ]));

            //to keep track of offenses and potential account bans
            $inputFilter->add($factory->createInput([
                'name'      => 'strikes',
                'required'  => false,
                'filters'   => [
                    ['name' => 'ToInt'],
                ]
            ]));

            $inputFilter->add($factory->createInput([
                'name'       => 'fname',
                'required'   => true,
                'filters'    => [
                    ['name' => 'StringTrim'],
                    ['name' => 'StripTags'],
                ],
                'validators' => [
                    [
                        'name'    => 'StringLength',
                        'options' => [
                            'encoding' => 'UTF-8',
                            'min'      => 1,
                            'max'      => 255,
                        ],
                    ],
                ],
            ]));

            $inputFilter->add($factory->createInput([
                'name'       => 'lname',
                'required'   => true,
                'filters'    => [
                    ['name' => 'StringTrim'],
                    ['name' => 'StripTags'],
                ],
                'validators' => [
                    [
                        'name'    => 'StringLength',
                        'options' => [
                            'encoding' => 'UTF-8',
                            'min'      => 1,
                            'max'      => 255,
                        ],
                    ],
                ],
            ]));

            $inputFilter->add($factory->createInput([
                'name'       => 'address',
                'required'   => true,
                'filters'    => [
                    ['name' => 'StringTrim'],
                    ['name' => 'StripTags'],
                ],
                'validators' => [
                    [
                        'name'    => 'StringLength',
                        'options' => [
                            'encoding' => 'UTF-8',
                            'min'      => 1,
                            'max'      => 255,
                        ],
                    ],
                ],
            ]));

            $inputFilter->add($factory->createInput([
                'name'     => 'zip_id',
                'required' => true,
                'filters'  => [
                    ['name' => 'ToInt'],
                ],
            ]));

            $inputFilter->add($factory->createInput([
                'name'     => 'business_id',
                'required' => true,
                'filters'  => [
                    ['name' => 'ToInt'],
                ],
            ]));

            $inputFilter->add($factory->createInput([
                'name'       => 'create_date',
                'required'   => false,
                'filters'    => [
                    ['name' => 'ToNull'],
                ],
                'validators' => [
                    [
                        'name'    => 'Date',
                        'options' => [
                            'format' => 'Y-m-d H:i:s',
                        ],
                    ],
                ],
            ]));

            $inputFilter->add($factory->createInput([
                'name'       => 'update_date',
                'required'   => false,
                'filters'    => [
                    ['name' => 'ToNull'],
                ],
                'validators' => [
                    [
                        'name'    => 'Date',
                        'options' => [
                            'format' => 'Y-m-d H:i:s',
                        ],
                    ],
                ],
            ]));

            $this->inputFilter = $inputFilter;
        }
        return $this->inputFilter;
    }

    public function setInputFilter(InputFilterInterface $inputFilter)
    {
        throw new RuntimeException('This instance does not support setting an alternate input filter');
    }

    /**
     * No idea what this is being used for currently, but it is here
     */
    public function setDefaults()
    {
        $this->exchangeArray([
            'role_id'      => 0,
            'status'       => self::INACTIVE,
            'strikes'      => 0,
            'create_date'  => gmdate('Y-m-d H:i:s'), // The should ideally use DateTimeImmutable so that they can be used with DateInterval
            'update_date'  => gmdate('Y-m-d H:i:s'),
        ]);
    }

    public static function encryptPassword($password)
    {
        return password_hash($password, PASSWORD_DEFAULT);
    }
}
