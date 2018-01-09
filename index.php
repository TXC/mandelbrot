<?php
function __autoload($class) {
    require_once 'classes/'.$class.'.php';
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <title>Mandelbrot</title>
    <link rel="stylesheet" href="style.css" />
    <script src="https://code.jquery.com/jquery-3.2.1.min.js" type="text/javascript"></script>
    <script src="ui.js" type="text/javascript"></script>
</head>
<body>

<h1>SVG Mandelbrot Set</h1>
<a id="help-icon" href="#">?</a>

<div id="information">
    <h2>Information</h2>
    <p>
        The Mandelbrot set is a set of complex numbers.
        It is defined as all complex numbers c for which the recurrence relation z_n+1 = (z_n)^2+c does not grow to infinty.
        A visualization of the Mandelbrot set can be created by plotting the real and imaginary components of each constituent complex number along the x and y axes.
    </p>
    <p>
        The Mandelbrot picture below is a Scalable Vector Graphic (SVG), not a raster image.
        Fractals are costly to render using SVGs because each point in the fractal must be represented by an SVG element.
        I tried to esacape this problem by grouping contiguous points into single-element blocks, thus greatly reducing the total amount of markup required.
        Clicking on each block will load a zoomed image of the points in that block, perhaps revealing details hidden at the previous resolution.
    </p>
</div>

<div id="options">
    <form>
        <button id="clear">Initialize</button>
    </form>
</div>

<div id="result">
    <?php echo Factory::Image()->draw(); ?>
</div>

</body>
</html>