<?php

namespace Plugin\Statistics;

class Filter
{
    public static function ipSendResponse($response)
    {
        if (!($response instanceof \Ip\Response\Layout)) {
            return $response;
        }

		ipAddJs('assets/count-statistics');
		return $response;
    }
}
