<?php

	function getIsCrawler($userAgent)
	{
		$crawlers = 'Google|msnbot|Rambler|Yahoo|AbachoBOT|accoona|' .
		'AcioRobot|ASPSeek|CocoCrawler|Dumbot|FAST-WebCrawler|' .
	'GeonaBot|Gigabot|Lycos|MSRBOT|Scooter|AltaVista|IDBot|eStyle|Scrubby';
		$isCrawler = (preg_match("/$crawlers/", $userAgent) > 0);
		return $isCrawler;
	}

	$routes['Plugin/Statistics/assets/count-statistics'] = function() {

	header("Cache-Control: no-cache, must-revalidate"); // HTTP/1.1
	header("Expires: Sat, 26 Jul 1997 05:00:00 GMT"); // Date in the past

	if (session_status() == PHP_SESSION_NONE) {
		session_start();
	}

	if (!getIsCrawler($_SERVER['HTTP_USER_AGENT'])){ //prüfen ob kein Crawler
		$sql2 = "";
		
		if (!isset($_SESSION['firstVisit']) && !isset($_COOKIE["firstvisit"])){ //prüfen, ob neuer Besucher
			$_SESSION['firstVisit'] = "no";
			setcookie("firstvisit","no",strtotime('today 23:59'));
			$sql2 = "visitors=visitors+1, ";
		}

		if (isset($_SESSION['firstVisit']) || isset($_COOKIE["firstvisit"])){ //nur zählen, wenn cookie, oder session erfolgreich
			$sql2 = "update ".ipTable('statistics')." set ". $sql2 ." pages=pages+1 where date=CURDATE();";
			
			$result = ipDb()->execute($sql2);

			if ($result==0) { //heute noch kein Besucher vorhanden
				$sql2 = "insert into ".ipTable('statistics')."  (date, pages, visitors) values (CURDATE(),1,1);";
				ipDb()->execute($sql2);
			}
		}
	}
		
	return new \Ip\Response\Json( array('count' => 'true'));
	};
?>