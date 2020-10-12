<?php

namespace App\Tests\Entity;

use App\Entity\Contact;
use App\Entity\Property;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class ContactTest extends KernelTestCase
{

    public function getEntity(): Contact
    {
        return (new Contact())
            ->setFirstname("cartman")
            ->setLastname("Eric")
            ->setPhone("0450226027")
            ->setEmail("cartmen.eric@gmail.com")
            ->setMessage("ccccccccccccccccccccc")
            ->setProperty(new Property());
    }

    /**
     * Test avec le composent validator
     *
     * @param Contact $contact
     * @param integer $number Nombre d'erreur attendue
     * @return void
     */
    public function assertHasErros(Contact $contact, int $number = 0)
    {
        self::bootKernel();
        $errors = self::$container->get('validator')->validate($contact);
        $messages = [];
        /**
         *  @var ConstraintViolation $error
         */
        foreach ($errors as $error) {
            $messages[] = $error->getPropertyPath() . ' => ' . $error->getMessage();
        }
        $this->assertCount($number, $errors, implode(', ', $messages));
    }

    public function testValidEntity()
    {
        $this->assertHasErros($this->getEntity(), 0);
    }

    public function testInvalidEntity()
    {
        $this->assertHasErros($this->getEntity()->setPhone("045022602r"), 1);
        $this->assertHasErros($this->getEntity()->setPhone("045022602"), 1);
    }

    public function testInvalidLengthFirstnameEntity()
    {
        $this->assertHasErros($this->getEntity()->setFirstname("a"), 1);
        $this->assertHasErros($this->getEntity()->setFirstname(""), 1);
    }

    public function testInvalidLengthLastnameEntity()
    {
        $this->assertHasErros($this->getEntity()->setLastname("d"), 1);
        $this->assertHasErros($this->getEntity()->setLastname(""), 1);
    }

    public function testInvalidBlankPhoneEntity()
    {
        $this->assertHasErros($this->getEntity()->setPhone(""), 1);
    }

    public function testInvalidLengthMessageEntity()
    {
        $this->assertHasErros($this->getEntity()->setMessage("ccccccc"), 1);
    }

    public function testInvalidEmailEntity()
    {
        $this->assertHasErros($this->getEntity()->setEmail("john.test.gmail.com"), 1);
    }
}
