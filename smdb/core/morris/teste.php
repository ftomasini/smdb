
<link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/morris.js/0.5.1/morris.css">
<script src="//ajax.googleapis.com/ajax/libs/jquery/1.9.0/jquery.min.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/raphael/2.1.0/raphael-min.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/morris.js/0.5.1/morris.min.js"></script>
<!--
-->

<?php


include '../Autoload.php';

// Require: use your own path
//require_once 'morris.php';
//require_once 'morris-charts.php';

// Optional: include chart line
//require_once 'morris-line-charts.php';
//require_once 'morris-donut-charts.php';
//require_once 'morris-bar-charts.php';
//require_once 'morris-area-charts.php';



tipo1();
tipo2();
tipo3();
tipo4();
/**
 * Display
 *
 * @brief Display
 */
function tipo1()
{
  ?>
  <div id="my-charts1"></div>
<?php
  $morris = new MorrisLineCharts( 'my-charts1' );
  $morris->xkey = array( 'date' );
  $morris->resize = true;
$morris->hideHover = 'auto';
  $morris->ykeys = array( 'value' );
  $morris->labels = array( 'Money' );
  $morris->data = array(
    array( 'date' => '2010', 'value' => 88 ),
    array( 'date' => '2011', 'value' => 18 ),
    array( 'date' => '2012', 'value' => 28 ),
    array( 'date' => '2013', 'value' => 48 ),
  );
  echo $morris->toJavascript();
}



function tipo2()
{
  ?>
  <div id="my-charts2"></div>
<?php
  $morris = new MorrisDonutCharts( 'my-charts2' );
  $morris->resize = true;
  $morris->data = array(
    array( 'label' => '2010', 'value' => 88 ),
    array( 'label' => '2011', 'value' => 18 ),
    array( 'label' => '2012', 'value' => 28 ),
    array( 'label' => '2013', 'value' => 48 ),
  );
  echo $morris->toJavascript();
}

function tipo3()
{
  ?>
  <div id="my-charts3"></div>
<?php
  $morris = new MorrisBarCharts( 'my-charts3' );
  $morris->resize = true;
  $morris->xkey = array( 'date' );
  $morris->ykeys = array( 'value' );
  $morris->labels = array( 'Money' );
  $morris->data = array(
    array( 'date' => '2010', 'value' => 88 ),
    array( 'date' => '2011', 'value' => 18 ),
    array( 'date' => '2012', 'value' => 28 ),
    array( 'date' => '2013', 'value' => 48 ),
  );
  echo $morris->toJavascript();
}

function tipo4()
{
  ?>
  <div id="my-charts4"></div>
<?php
  $morris = new MorrisAreaCharts( 'my-charts4' );
  $morris->resize = true;
  $morris->xkey = array( 'date' );
  $morris->ykeys = array( 'value' );
  $morris->labels = array( 'Money' );
  $morris->data = array(
    array( 'date' => '2010', 'value' => 88 ),
    array( 'date' => '2011', 'value' => 18 ),
    array( 'date' => '2012', 'value' => 28 ),
    array( 'date' => '2013', 'value' => 48 ),
  );
  echo $morris->toJavascript();
}

?>
