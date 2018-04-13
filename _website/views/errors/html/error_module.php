<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$params = NULL;

if( count($application['params']) > 0 ) {
	$params = "array(";
	$i = 0;
	foreach($application['params'] as $key => $value) {
		if($i === 0) {
			$params .= "'{$key}'=>'{$value}'";
		} else {
			$params .= ", '{$key}'=>'{$value}'";
		}
		
		$i++;
	}
	$params .= ") ";
}
?>

<style type="text/css">

::selection { background-color: #E13300; color: white; }
::-moz-selection { background-color: #E13300; color: white; }

body {
	background-color: #fff;
	margin: 40px;
	font: 13px/20px normal Helvetica, Arial, sans-serif;
	color: #4F5155;
}

a {
	color: #003399;
	background-color: transparent;
	font-weight: normal;
}

h2 {
	color: #444;
	background-color: transparent;
	border-bottom: 1px solid #D0D0D0;
	font-size: 19px;
	font-weight: normal;
	margin: 0 15px 15px;
	padding: 15px 0px;
}

code {
	font-family: Consolas, Monaco, Courier New, Courier, monospace;
	font-size: 12px;
	background-color: #f9f9f9;
	border: 1px solid #D0D0D0;
	color: #002166;
	display: block;
	margin: 15px;
	padding: 15px;
}

#container {
	margin: 10px;
	border: 1px solid #D0D0D0;
	box-shadow: 0 0 8px #D0D0D0;
}

p {
	margin: 12px 15px 12px 15px;
}
</style>

<div id="container">
	<h2>Module error: <?php echo $application['error_type']; ?></h2>
	
	<p>
		<?php echo $application['error']; ?>
	</p>
	<code>
		// Your call was<br>
		<?php if( strlen($application['method']) > 0 ) { ?>
		$this->load->module('<?php echo $module; ?>', '<?php echo $application['method']; ?>', <?php echo $params; ?>);
		<?php } else { ?>
		$this->load->module('<?php echo $module; ?>');
		<?php } ?>
	</code>
	<p>
		Summary:
	</p>
	<code>
		<?php 
			echo '$application = array(<br>';
			foreach($application as $key => $value) {
				if( is_array($value) ) {
					echo "&nbsp;&nbsp;&nbsp;&nbsp;{$key}=>array(<br>";
					foreach($value as $k => $v) {
						echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{$k}=>{$v}<br>";
					}
					echo "&nbsp;&nbsp;&nbsp;&nbsp;)<br>";
				} else {
					echo "&nbsp;&nbsp;&nbsp;&nbsp;{$key}=>{$value}<br>";
				}
			}
			echo ");";
		?>
	</code>
</div>