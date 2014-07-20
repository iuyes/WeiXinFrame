<?php

class LocationController extends AppController
{
	const X_PI = 52.35987756;

	public function index()
	{
		$x = $this->requestInfo['Msg']['Location_X'];
		$y = $this->requestInfo['Msg']['Location_Y'];
		$href = 'http://billboardsweb.sinaapp.com/Billboards/show/' . $x . '/' . $y;
		$this->returnText($href);
	}

	private function convert_GCJ02_To_BD09($lat, $lng)
	{
		$x = $lng;
		$y = $lat;
		$z =sqrt($x * $x + $y * $y) + 0.00002 * sin($y * LocationController::X_PI);
		$theta = atan2($y, $x) + 0.000003 * cos($x * LocationController::X_PI);
		$lng = $z * cos($theta) + 0.0065;
		$lat = $z * sin($theta) + 0.006;
		return array('lat' => $lat, 'lng' => $lng);
	}

	public function Convert_BD09_To_GCJ02($lat, $lng)
	{
		$x = $lng - 0.0065;
		$y = $lat - 0.006;
		$z = sqrt($x * $x + $y * $y) - 0.00002 * sin($y * LocationController::X_PI);
		$theta = stan2($y, $x) - 0.000003 * cos($x * LocationController::X_PI);
		$lng = $z * cos($theta);
		$lat = $z * sin($theta);
		return array('lat' => $lat, 'lng' => $lng);
	}
}