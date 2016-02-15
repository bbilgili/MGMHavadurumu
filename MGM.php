<?php

class MGM {
	
	var $mgmUrl = "http://www.mgm.gov.tr/mobile/tahmin-il-ve-ilceler.aspx?m=";
	var $location = "";
	
	public function getWheaterCondition(){
		
		$result = array();
		
		if($this->location == false){
			$result["statusCode"] = "0";
			$result["status"] = "Sehir Bilgisi girilmemis";
			
			echo json_encode($result);
			exit;
		}
		
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $this->mgmUrl.$this->location);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		$resulthtml = curl_exec($ch);
		curl_close($ch);
		
		$dochtml = new DOMDocument();
		$dochtml->loadHTML($resulthtml);
		
		$result["statusCode"] = "1";
		
		//Get Min Value
		$minValue = $dochtml->getElementById('cpContent_thmMin1');
		
		if(empty($minValue)){
			$result["statusCode"] = "0";
			$result["status"] = "Herhangi bir sonuç bulunamadı";
			
			echo json_encode($result);
			exit;
		}
		
		$result["min"] = $minValue->nodeValue;         
		
		//Get Max Value
		$maxValue = $dochtml->getElementById('cpContent_thmMax1');
		$result["max"] = $maxValue->nodeValue;  

		//Get Img Src
		$imgSrc = $dochtml->getElementById('cpContent_imgHadise1');
		$imgSrc = $imgSrc->getAttribute('src');
		
		$wheaterConditionStr = "";
		$media = "";
		
		switch ($imgSrc) {
			case "../FILES/imgIcon/99/a1-25x25-gif/-23.gif":  $media = "http://www.mgm.gov.tr/FILES/imgIcon/99/a1-25x25-gif/-23.gif"; $wheaterConditionStr = "Çok Bulutlu"; break;
			case "../FILES/imgIcon/99/a1-25x25-gif/-25.gif":  $media = "http://www.mgm.gov.tr/FILES/imgIcon/99/a1-25x25-gif/-25.gif"; $wheaterConditionStr = "Parçalı Bulutlu"; break;
			case "../FILES/imgIcon/99/a1-25x25-gif/-28.gif":  $media = "http://www.mgm.gov.tr/FILES/imgIcon/99/a1-25x25-gif/-28.gif"; $wheaterConditionStr = "Az Bulutlu"; break;
			case "../FILES/imgIcon/99/a1-25x25-gif/-29.gif":  $media = "http://www.mgm.gov.tr/FILES/imgIcon/99/a1-25x25-gif/-29.gif"; $wheaterConditionStr = "Açık"; break;
			case "../FILES/imgIcon/99/a1-25x25-gif/45.gif":   $media = "http://www.mgm.gov.tr/FILES/imgIcon/99/a1-25x25-gif/-45.gif"; $wheaterConditionStr = "Sisli"; break;
			case "../FILES/imgIcon/99/a1-25x25-gif/61.gif":   $media = "http://www.mgm.gov.tr/FILES/imgIcon/99/a1-25x25-gif/-61.gif"; $wheaterConditionStr = "Hafif Yağmurlu"; break;
			case "../FILES/imgIcon/99/a1-25x25-gif/63.gif":   $media = "http://www.mgm.gov.tr/FILES/imgIcon/99/a1-25x25-gif/-63.gif"; $wheaterConditionStr = "Yağmurlu"; break;
			case "../FILES/imgIcon/99/a1-25x25-gif/65.gif":   $media = "http://www.mgm.gov.tr/FILES/imgIcon/99/a1-25x25-gif/-65.gif"; $wheaterConditionStr = "Kuvvetli Yağmurlu"; break;
			case "../FILES/imgIcon/99/a1-25x25-gif/68.gif":   $media = "http://www.mgm.gov.tr/FILES/imgIcon/99/a1-25x25-gif/-68.gif"; $wheaterConditionStr = "Karla Karışık Yağmurlu"; break;
			case "../FILES/imgIcon/99/a1-25x25-gif/71.gif":   $media = "http://www.mgm.gov.tr/FILES/imgIcon/99/a1-25x25-gif/-71.gif"; $wheaterConditionStr = "Hafif Kar Yağışlı"; break;
			case "../FILES/imgIcon/99/a1-25x25-gif/73.gif":   $media = "http://www.mgm.gov.tr/FILES/imgIcon/99/a1-25x25-gif/-63.gif"; $wheaterConditionStr = "Kar Yağışlı"; break;
			case "../FILES/imgIcon/99/a1-25x25-gif/80.gif";   $media = "http://www.mgm.gov.tr/FILES/imgIcon/99/a1-25x25-gif/80.gif"; $wheaterConditionStr = "Sağnak Yağışlı"; break;
			case "../FILES/imgIcon/99/a1-25x25-gif/-81.gif";  $media = "http://www.mgm.gov.tr/FILES/imgIcon/99/a1-25x25-gif/-81.gif"; $wheaterConditionStr = "Sağnak Yağışlı"; break;
			case "../FILES/imgIcon/99/a1-25x25-gif/82.gif":   $media = "http://www.mgm.gov.tr/FILES/imgIcon/99/a1-25x25-gif/-82.gif"; $wheaterConditionStr = "Kuvvetli Sağnak Yağışlı"; break;
			case "../FILES/imgIcon/99/a1-25x25-gif/75.gif":   $media = "http://www.mgm.gov.tr/FILES/imgIcon/99/a1-25x25-gif/-75.gif"; $wheaterConditionStr = "Yoğun Kar Yağışlı"; break;
			case "../FILES/imgIcon/99/a1-25x25-gif/-44.gif":  $media = "http://www.mgm.gov.tr/FILES/imgIcon/99/a1-25x25-gif/-44.gif"; $wheaterConditionStr = "Sıcak"; break;
			case "../FILES/imgIcon/99/a1-25x25-gif/-42.gif":  $media = "http://www.mgm.gov.tr/FILES/imgIcon/99/a1-25x25-gif/-42.gif"; $wheaterConditionStr = "Sıcak"; break;
		}
		
		$result["name"] = $this->location;
		$result["status"] = $wheaterConditionStr;
		$result["imageUrl"] = $media;
		$result["serverDate"] = $this->_date_tr('j F Y, l', date("Y-m-d"));
		$result["serverHour"] = date("H:i");
		
		return json_encode($result);
		
	}
	
	function _date_tr($f, $zt = 'now') 
	{
		$z = date("$f", strtotime($zt));
		$replace = array(
			'Monday'	=> 'Pazartesi',
			'Tuesday'	=> 'Salı',
			'Wednesday'	=> 'Çarşamba',
			'Thursday'	=> 'Perşembe',
			'Friday'	=> 'Cuma',
			'Saturday'	=> 'Cumartesi',
			'Sunday'	=> 'Pazar',
			'January'	=> 'Ocak',
			'February'	=> 'Şubat',
			'March'		=> 'Mart',
			'April'		=> 'Nisan',
			'May'		=> 'Mayıs',
			'June'		=> 'Haziran',
			'July'		=> 'Temmuz',
			'August'	=> 'Ağustos',
			'September'	=> 'Eylül',
			'October'	=> 'Ekim',
			'November'	=> 'Kasım',
			'December'	=> 'Aralık',
			'Mon'		=> 'Pts',
			'Tue'		=> 'Sal',
			'Wed'		=> 'Çar',
			'Thu'		=> 'Per',
			'Fri'		=> 'Cum',
			'Sat'		=> 'Cts',
			'Sun'		=> 'Paz',
			'Jan'		=> 'Oca',
			'Feb'		=> 'Şub',
			'Mar'		=> 'Mar',
			'Apr'		=> 'Nis',
			'Jun'		=> 'Haz',
			'Jul'		=> 'Tem',
			'Aug'		=> 'Ağu',
			'Sep'		=> 'Eyl',
			'Oct'		=> 'Eki',
			'Nov'		=> 'Kas',
			'Dec'		=> 'Ara',
		);
		foreach($replace as $en => $tr){
			$z = str_replace($en, $tr, $z);
		}
		if(strpos($z, 'Mayıs') !== false && strpos($f, 'F') === false) $z = str_replace('Mayıs', 'May', $z);
		return $z;
	}
}

?>
