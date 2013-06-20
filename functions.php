<?php
/**
 * IBIS CMS Core Functionality
 *
 * Definitions of constants, variables and includes of files
 *
 * PHP 5
 *
 * @copyright     IBIS Servicios (http://ibisservicios.com)
 * @link          http://ibisservicios.com
 * @version       1.1
 * @license       View the LICENSE file
 */

date_default_timezone_set("Africa/Accra");

define ("ENCODING", "UTF-8");
define ("LANGUAGE", "en");
define ("TAGS", "<p><strong><a><ul><li><em><ol><span><table><tr><th><td><img><h1><h2><h3><h4><h5><h6><div><iframe><b><i><hr><pre><br>");

if (file_exists(__DIR__ . '/config.php')) {
  require_once __DIR__ . '/config.php';
} else {
  die("Configuration file does not exist. Please create this file to proceed. View the readme for more information.");
}

require_once(__DIR__ . '/opensource.functions.php');

$db = new DB;
$css_version = filesize("css/styles.css");

$head = '
<meta charset="utf-8" />
<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
<base href="' . URL . '" />
<link rel="shortcut icon" href="favicon.ico" />
<link rel="apple-touch-icon" href="apple-touch-icon.png" />
<link rel="stylesheet" href="css/bootstrap.css" />
<link rel="stylesheet" href="css/reset.css" />
<link rel="stylesheet" href="css/styles.' . $css_version . '.css" />
<script src="js/modernizr-2.6.1.min.js"></script>';

$head .= LOCAL ? 
'<script src="http://e/js/jq.1.8.0.js"></script>' : 
'<script src="//ajax.googleapis.com/ajax/libs/jquery/1.8.0/jquery.min.js"></script>' . "
<script>
  var _gaq=[['_setAccount','UA-XXXXX-X'],['_trackPageview']];
  (function(d,t){var g=d.createElement(t),s=d.getElementsByTagName(t)[0];
  g.src=('https:'==location.protocol?'//ssl':'//www')+'.google-analytics.com/ga.js';
  s.parentNode.insertBefore(g,s)}(document,'script'));
</script>";

$head .= '<script src="js/bootstrap.js"></script>';

?>
