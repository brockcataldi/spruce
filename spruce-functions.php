<?php 

/**
 * Determining classes based on conditionals
 * 
 * @param array $classes the classes and the conditionals
 * @param bool $echo whether or not to echo or return
 * 
 * @return void|string the class list
 */
function classes(array $classes = array(), bool $echo = true){
	$valid = array();

	foreach($classes as $classname => $conditional){

		if(true === $conditional){
			$valid[] = $classname;
		}
	}

	if(true === $echo){
		echo implode(" ", $valid);
	}else{
		return $valid;
	}
}