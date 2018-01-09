<?php
/**
 * Created by PhpStorm.
 * User: jonas
 * Date: 2018-01-08
 * Time: 00:16
 */

class Factory
{
    static public function Image() {
        set_time_limit(0);
        $from = new Complex();
        $to = new Complex();

        $blocksize  = (isset($_GET['bsize'])) ? $_GET['bsize'] : NULL;
        $real       = (isset($_GET['real'])) ? $_GET['real'] : NULL;
        $imaginary  = (isset($_GET['imaginary'])) ? $_GET['imaginary'] : NULL;

        if ($blocksize !== NULL && $real !== NULL && $imaginary !== NULL) {
            $from->real         = $real;
            $from->imaginary    = $imaginary;
            $to->real           = ($real + $blocksize);
            $to->imaginary      = ($imaginary + $blocksize);
            $resolution         = ( 1.0 * ($blocksize / 200) );
        }
        else {
            /*
            $from->real = mt_rand(-2 * 1000000, 0) / 1000000;
            $from->imaginary = mt_rand(-2 * 1000000, 0) / 1000000;

            $to->real = mt_rand(1 * 1000000, 1 * 1000000) / 1000000;
            $to->imaginary = mt_rand(1 * 1000000, 1 * 1000000) / 1000000;
            */

            $from->real = -1.6;
            $from->imaginary = -1;

            $to->real = 1;
            $to->imaginary = 1;


            $resolution = .01;
        }
        $escape_depth = 15;
        $mandelbrot = new Mandelbrot($from, $to, $resolution, $escape_depth);
var_dump($mandelbrot);
die();

        return $mandelbrot;
    }
}