<?php
/**
 * Created by PhpStorm.
 * User: jonas
 * Date: 2018-01-07
 * Time: 21:59
 */

//Representation of a complex number
class Complex
{
    //A complex number has a real and imaginary part
    /** @var int Real part */
    public $real = 0;
    /** @var int Imaginary part */
    public $imaginary = 0;

    /**
     * Returns the product of two complex numbers
     * @param Complex $other
     * @return Complex
     */
    public function times($other) {
        $result = new self();
        $result->real = ($this->real * $other->real) - ($this->imaginary * $other->imaginary);
        $result->imaginary = ($this->real * $other->imaginary) + ($this->imaginary * $other->real);
        return $result;
    }

    /**
     * Returns the sum of two complex numbers
     * @param Complex $other
     * @return Complex
     */
    public function plus($other) {
        $result = new self();
        $result->real = $this->real + $other->real;
        $result->imaginary = $this->imaginary + $other->imaginary;
        return $result;
    }
    /**
     * Returns the Euclidean distance between the complex number and the origin on the complex plane
     * @return float
     */
    public function magnitude() {
        return sqrt(pow($this->real, 2) + pow($this->imaginary, 2));
    }
}