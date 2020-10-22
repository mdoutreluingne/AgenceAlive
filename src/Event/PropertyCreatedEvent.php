<?php 

namespace App\Event;

use App\Entity\Property;
use Symfony\Contracts\EventDispatcher\Event;

class PropertyCreatedEvent extends Event
{
    const NAME = "app.property.created";

    /**
     *
     * @var Property
     */
    private $property;

    public function __construct(Property $property)
    {
        $this->property = $property;
    }


    /**
     * Get the value of property
     *
     * @return  Property
     */ 
    public function getProperty()
    {
        return $this->property;
    }
}
?>