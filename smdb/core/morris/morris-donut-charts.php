<?php

/**
 * Morris Donut Charts
 *
 * @class           MorrisAreaCharts
 * @author          =undo= <info@wpxtre.me>
 * @copyright       Copyright (C) 2012-2014 wpXtreme Inc. All Rights Reserved.
 * @date            2014-04-01
 * @version         1.0.0
 *
 */
class MorrisDonutCharts extends Morris {

  /**
   * An array of strings containing HTML-style hex colors for each of the donut segments. Note: if there are fewer
   * colors than segments, the colors will cycle back to the start of the array when exhausted.
   *
   * @brief Colors
   *
   * @var array $colors
   */
  //public $colors = array( '#0B62A4', '#3980B5', '#95BBD7', '#B0CCE1', '#095791', '#095085', '#083E67', '#052C48', '#042135' );


  public $colors = array( '#0B62A4','#30637a','#763186','#915032','#469d33','#4b3588','#9a374e','#84a037','#38b295','#44b2a3','#c0495a','#a5cf5a');

  /**
   * A function that will translate a y-value into a label for the centre of the donut.
   *
   * eg: currency function (y, data) { return '$' + y }
   *
   * Note: if required, the method is also passed an optional second argument, which is the complete data row for the given value.
   *
   * @brief Formatter
   *
   * @var string $formatter
   */
  public $formatter = '';

  public $backgroundColor = '#FFFFFF';
  public $labelColor = '#000000';

  /**
   * Create an instance of MorrisDonutCharts class
   *
   * @brief Construct
   *
   * @param string $element_id The element id
   *
   * @return MorrisDonutCharts
   */
  public function __construct( $element_id )
  {
    parent::__construct( $element_id, MorrisChartTypes::DONUT );
  }

  public function toJavascript()
  {
    ob_start();
    ?>
    <script type="text/javascript">
      jQuery( function ( $ )
      {
        "use strict";

        Morris.<?php echo $this->__chart_type ?>(
            <?php
               $json = $this->toJSON();
               $json = str_replace('"REPLACE"', "function (y, data) { return y + '%'}", $json);

               echo $json;
            ?>
            ).select(0);
      });
    </script>
    <?php
    $buffer = ob_get_contents();
    ob_end_clean();

    return $buffer;
  }




}