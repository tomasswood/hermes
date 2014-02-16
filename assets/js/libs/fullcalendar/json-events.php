<?php

	$year = date('Y');
	$month = date('m');

	echo json_encode(array(
	
		array(
			'id' => 111,
			'title' => "Event1",
			'start' => "2014-1-10",
			'url' => "http://yahoo.com/"
		),
		
		array(
			'id' => 222,
			'title' => "Event2",
			'start' => "2014-1-10",
			'end' => "2014-1-12",
			'url' => "http://yahoo.com/"
		)
	
	));

?>
