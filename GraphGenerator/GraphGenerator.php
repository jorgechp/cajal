<?php   
 /* CAT:Bar Chart */
define('ROOT', __DIR__ .'/');
 /* pChart library inclusions */
 require_once(ROOT."../lib/pChart214/class/pData.class.php");
 require_once(ROOT."../lib/pChart214/class/pDraw.class.php");
 require_once(ROOT."../lib/pChart214/class/pImage.class.php");

 /**
  * Trabaja con la librería pChart para la generación de gráficos
  * 
  * Para la generación de gráficos, se debe llamar a este fichero php, que genera
  * un archivo binario con la imagen del gráfico generado. Los parámetros 
  * del gráfico deberán ser enviados mediante GET. 
  * 
  *  -> pTypes -> Número de variables diferentes, incluyendo el nombre de cada punto.
  *  -> YLabel -> Nombre del eje Y
  *  -> height -> Largo de la imagen
  *  -> width -> Ancho de la imagen
  *  -> legend -> 0 si no se muestra leyenda, 1 para mostrarla
  *  -> fontSize -> Tamaño de la fuente en pixeles
  *  -> point -> Array de puntos con el siguiente formato:
  *               Si se quieren representar n variables diferentes que adquieren, en total
  *                k valores. point debe almacenar:
  *                    desde 0 a k:
  *                            desde i = 0 a n:
  *                                     point[] = i
  * 
  *  Dicho de otra forma, el orden de los parámetros del array de puntos es, en 
  *  primer lugar, los n primeros valores de las variables, luego los n segundos
  *  valores... y así sucesivamente hasta almacenar hasta k*n
  * 
  *  -> nameSeries -> Nombre de cada serie de datos a representar
  */
 class GraphGenerator{
     /**
      * Crea y genera el gráfico, que es renderizado y representado como un
      * objeto binario
      * @param Object[] $points
      * @param String[] $nameSeries
      * @param String $Ylabel
      * @param integer $fontSize
      * @param String $legend
      * @param integer $height
      * @param integer $width
      */
    public static function  createGraph($points,$nameSeries,$Ylabel,$fontSize,$legend,$height,$width){
      
    /* Create and populate the pData object */
           /* Create and populate the pData object */
 /* Create and populate the pData object */
    $MyData = new pData();  
    $limite = count($points)-1;
    for ($index = 0; $index < $limite; $index++) {        
        $MyData->addPoints($points[$index],$nameSeries[$index]);        
    }
    
    $MyData->setAxisName(0,$Ylabel);
    $MyData->addPoints($points[$limite],$nameSeries[$limite]);
    $MyData->setSerieDescription($points[$limite],$points[$limite]);
    $MyData->setAbscissa($nameSeries[$limite]);

    
    /* Create the pChart object */
    $myPicture = new pImage($height,$width,$MyData);

    /* Turn of Antialiasing */
    $myPicture->Antialias = FALSE;

    /* Add a border to the picture */
    $myPicture->drawGradientArea(0,0,$height,$width,DIRECTION_VERTICAL,array("StartR"=>240,"StartG"=>240,"StartB"=>240,"EndR"=>180,"EndG"=>180,"EndB"=>180,"Alpha"=>100));
    $myPicture->drawGradientArea(0,0,$height,$width,DIRECTION_HORIZONTAL,array("StartR"=>240,"StartG"=>240,"StartB"=>240,"EndR"=>180,"EndG"=>180,"EndB"=>180,"Alpha"=>20));
    $myPicture->drawRectangle(0,0,$height-1,$width-1,array("R"=>0,"G"=>0,"B"=>0));

    /* Set the default font */
    $myPicture->setFontProperties(array("FontName"=>ROOT."../lib/pChart214/fonts/pf_arma_five.ttf","FontSize"=>$fontSize));

    /* Define the chart area */
    $myPicture->setGraphArea(100,15,$height-50,$width-60);

    /* Draw the scale */
    $scaleSettings = array("XMargin"=>AUTO,'LabelRotation'=>6, "Floating"=>FALSE,"GridR"=>00,"GridG"=>0,"GridB"=>0,"DrawSubTicks"=>FALSE,"CycleBackground"=>TRUE);
    $myPicture->drawScale($scaleSettings);

    if($legend == 1){
        /* Write the chart legend */
        $myPicture->drawLegend(580,12,array("Style"=>LEGEND_NOBORDER,"Mode"=>LEGEND_HORIZONTAL));
    }

    /* Turn on shadow computing */ 
    $myPicture->setShadow(TRUE,array("X"=>1,"Y"=>1,"R"=>0,"G"=>0,"B"=>0,"Alpha"=>10));

    /* Draw the chart */
    $myPicture->setShadow(TRUE,array("X"=>1,"Y"=>1,"R"=>0,"G"=>0,"B"=>0,"Alpha"=>10));
    $settings = array("Surrounding"=>-30,"InnerSurrounding"=>30,"Interleave"=>0);
    $myPicture->drawBarChart($settings);

    /* Render the picture (choose the best way) */
    $myPicture->autoOutput("pictures/example.drawBarChart.spacing.png"); 
    }
 }
 



 if(isset($_GET['generateIndicator'])){
     $points = array();
     
     /**
      * Obtiene los parámetros pasados por GET
      */
     $variables = filter_input(INPUT_GET,'pTypes',FILTER_SANITIZE_SPECIAL_CHARS);
     $Ylabel = filter_input(INPUT_GET,'Ylabel',FILTER_SANITIZE_SPECIAL_CHARS);
     $height = filter_input(INPUT_GET,'height',FILTER_SANITIZE_SPECIAL_CHARS);
     $width = filter_input(INPUT_GET,'width',FILTER_SANITIZE_SPECIAL_CHARS);
     $legend = filter_input(INPUT_GET,'legend',FILTER_SANITIZE_SPECIAL_CHARS);
     $fontSize = filter_input(INPUT_GET,'fontSize',FILTER_SANITIZE_SPECIAL_CHARS);
     $point = $_GET['point'];//filter_input(INPUT_GET,'point',FILTER_SANITIZE_SPECIAL_CHARS,FILTER_REQUIRE_ARRAY);
     $nameSeries = $_GET['nameSeries'];//filter_input(INPUT_GET,'nameSeries',FILTER_SANITIZE_SPECIAL_CHARS,FILTER_REQUIRE_ARRAY);
     $nPuntos = count($point);
     $puntos = array();
     
     
     for ($index = 0; $index < $nPuntos; ) {         
         for ($index1 = 0; $index1 < $variables; $index1++) {             
             $puntos[$index1][] = $point[$index];
             $index++;
         }
     }
   
     GraphGenerator::createGraph($puntos,$nameSeries,$Ylabel,$fontSize,$legend,$height,$width);
 }
 

?>