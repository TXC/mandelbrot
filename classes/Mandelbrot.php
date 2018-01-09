<?php
/**
 * Created by PhpStorm.
 * User: jonas
 * Date: 2018-01-07
 * Time: 22:24
 */

class Mandelbrot
{
    /** @var array Set of complex points within the Mandelbrot set */
    private $set = [];
    /** @var array  */
    private $reals;
    /** @var array  */
    private $imaginaries;

    /** @var float  */
    private $resolution;
    /** @var int */
    private $escape_depth;
    /** @var Complex */
    private $min;
    /** @var Complex */
    private $max;

    private $size = 800;

    static private $colors = ['red', 'black', 'green', 'blue', 'orange', 'yellow', 'pink', 'brown'];
    /** @var DOMElement */
    static private $svg;
    /** @var DOMDocument */
    private $DOM;
    private $nodeStyle;

    /**
     * Returns of
     *
     * @param Complex $min
     * @param Complex $max
     * @param float $resolution
     * @param int $escape_depth
     */
    public function __construct($min, $max, $resolution, $escape_depth) {
        $this->min = $min;
        $this->max = $max;
        $this->resolution = $resolution;
        $this->escape_depth = $escape_depth;

        for ($r = $min->real; $r < $max->real; $r += $resolution)
        {
            $this->set[$r] = [];
            for ($i = $min->imaginary; $i < $max->imaginary; $i += $resolution)
            {
                $current = new Complex();
                $current->real = $r;
                $current->imaginary = $i;
                $this->set[round($r, 7).""][round($i, 7).""] = $this->isInside($current);
            }
        }

        $this->reals = array_keys($this->set);
        $this->imaginaries = array_keys($this->set[$this->reals[0]]);
        $this->nodeStyle = 'fill:gray; stroke-width:1; stroke:black;';
    }

    /**
     * Returns a 0 if point is in set (given escape depth);
     * if not in set, returns the number of iterations before escaping
     *
     * @param Complex $point
     * @return int
     */
    private function isInside($point) {

        $zn = new Complex();
        $zn->real = 0;
        $zn->imaginary = 0;
        for ($i=0; $i < $this->escape_depth; $i++)
        {
            $zn = $this->compute($zn, $point);
            if ($zn->magnitude() >= 2)
            {
                return $i;
            }
        }
        return 0;
    }

    /**
     * @param Complex $z
     * @param Complex $c
     * @return mixed
     */
    private function compute($z, $c) {
        return $z->times($z)->plus($c);
    }

    /**
     * Echo the SVG XML of a Mandelbrot set
     */
    function draw() {
        $this->DOM = new DOMDocument('1.0', 'UTF-8');
        $this->DOM->formatOutput = true;
        self::$svg = $this->DOM->createElement('svg');
        self::$svg->setAttribute('width', $this->size);
        self::$svg->setAttribute('height', $this->size);
        self::$svg->setAttribute('version', '1.1');
        self::$svg->setAttribute('xmlns', 'http://www.w3.org/2000/svg');

        $style = $this->DOM->createElement('style', 'rect { ' . $this->nodeStyle .' }');
        self::$svg->appendChild($style);

        //Parameters for the drawing algorithm
        $max_sub = 15; //The max number of points to try to group together for drawing
        $min_sub = 0;  //The min number of points to try to group together for drawing
        $step = 3;     //The step size down from $max_sub to $min_sub

        for ($size = $max_sub; $size >= $min_sub; $size -= $step)
        {
            $size = ($size == 0) ? 1 : $size;
            $this->fillBlocks($size);
        }
//        $this->DOM->appendChild(self::$svg);
        return $this->DOM->saveXML(self::$svg);
    }

    /**
     * Fill in large uniform blocks
     *
     * @param $size
     */
    function fillBlocks($size) {
        $col = 0;
        for ($r = 0; $r < sizeof($this->reals); $r += $size)
        {
            $this->imaginaries = $this->reals[$r];
            for ($i = 0; $i < sizeof($this->imaginaries); $i += $size)
            {
                //If the block is connected...
                $connected = 1;
                for ($row = $r; $row < ($r + $size); $row++)
                {
                    for ($col = $i; $col < ($i + $size); $col++)
                    {
                        if (
                            !empty($this->reals[$row])
                            && !empty($this->imaginaries[$col])
                            && !empty($this->set[$this->reals[$row]])
                            && !empty($this->set[$this->reals[$row]][$this->imaginaries[$col]])
                            && $this->set[$this->reals[$row]][$this->imaginaries[$col]] != 0
                        ) {
                            $connected = 0;
                            break;
                        }
                    }
                }
                //...then spit out an SVG for the block and mark the block as drawn
                if ($connected)
                {
                    for ($row = $r; $row < ($r + $size); $row++)
                    {
                        for ($col = $i; $col < ($i + $size); $col++)
                        {
                            /*
                            if(
                                !empty($this->reals[$row])
                                && !empty($this->imaginaries[$col])
                                && !empty($this->set[$this->reals[$row]])
                                && !empty($this->set[$this->reals[$row]][$this->imaginaries[$col]])
                            ) {
                            */
                                $this->set[$this->reals[$row]][$this->imaginaries[$col]] = -1;
                            // }
                        }
                    }
                    $this->drawBlock($row, $col, $size);
                }
            }
        }
    }

    /**
     * Echo out SVG code for a block
     *
     * @param int $row
     * @param int $col
     * @param int $size
     */
    function drawBlock($row, $col, $size) {

        /**
         * The side length of the square used to represent a single point
         */
        $block = 3;

        $real = ( ( 1.0 * $this->min->real ) + ( $row * $this->resolution ) - ( $size * $this->resolution ) );
        $imaginary = ( ( 1.0 * $this->min->imaginary ) + ( $col * $this->resolution ) - ( $size * $this->resolution ) );
        $blocksize = ( 1.0 * ( $this->resolution * $size ) );

        $rect = $this->DOM->createElement('rect');
        $rect->setAttribute('x', (($row * $block) - ($block * $size)));
        $rect->setAttribute('y', (($col * $block) - ($block * $size)));
        $rect->setAttribute('width', ($block * $size));
        $rect->setAttribute('height', ($block * $size));
        //$rect->setAttribute('style', $this->nodeStyle);
        $rect->setAttribute('id', $row.'-'.$col.'-'.$size);
        $rect->setAttribute('data-r', $real);
        $rect->setAttribute('data-i', $imaginary);
        $rect->setAttribute('data-b', $blocksize);

        self::$svg->appendChild($rect);
    }

}