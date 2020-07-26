<?php 
namespace App\Entity;

use Symfony\Component\Validator\Constraints as Assert;

class PropertyFilter{
    /**
     *
     * @var int
     */
    private $maxPrice;

    /**
     * @var int
     * @Assert\Range(
     *      min = 10,
     *      max = 400
     * )
     */
    private $minSurface;

    /**
     * Get the value of maxPrice
     *
     * @return  int
     */ 
    public function getMaxPrice()
    {
        return $this->maxPrice;
    }

    /**
     * Set the value of maxPrice
     *
     * @param  int  $maxPrice
     *
     * @return  self
     */ 
    public function setMaxPrice(int $maxPrice)
    {
        $this->maxPrice = $maxPrice;

        return $this;
    }


    /**
     * Get the value of minSurface
     *
     * @return  int
     */ 
    public function getMinSurface()
    {
        return $this->minSurface;
    }

    /**
     * Set the value of minSurface
     *
     * @param  int  $minSurface
     *
     * @return  self
     */ 
    public function setMinSurface(int $minSurface)
    {
        $this->minSurface = $minSurface;

        return $this;
    }
}
?>