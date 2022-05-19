<?php


/**
 * SSL varsa ve açıksa kontrolü
 */
function SSL(){ 
  if (array_key_exists("HTTPS", $_SERVER) && 'on' === $_SERVER["HTTPS"]) {
    $secure = 'https';
  }elseif (array_key_exists("SERVER_PORT", $_SERVER) && 443 === (int)$_SERVER["SERVER_PORT"]) {
    $secure = 'https';
  }elseif (array_key_exists("HTTP_X_FORWARDED_SSL", $_SERVER) && 'on' === $_SERVER["HTTP_X_FORWARDED_SSL"]) {
    $secure = 'https';
  }elseif (array_key_exists("HTTP_X_FORWARDED_PROTO", $_SERVER) && 'https' === $_SERVER["HTTP_X_FORWARDED_PROTO"]) {
    $secure = 'https';
  }else{
    $secure = 'http';
  }
  return $secure;

}

/*
    Site URL
*/ 
function site_url($url=null){
    //sub_folder eğer script bir alt dizine kurulursa linklemelerde sorun yaşanmaması için
    $site =  SSL()."://".$_SERVER['SERVER_NAME'] ;
    $site = $url != NULL ? $site.( $url != '/' ? '/'.trim($url,'/') : null ) : $site.(isset($_SERVER['REQUEST_URI'])?$_SERVER['REQUEST_URI']:null) ;
      return $site;

}



/*
  Gelen isteğin post ajax isteği mi olduğunu kontrol et
*/

function isAjax(){
    return !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest';
  }
  


/*
    Kullanıcının gerçek IP adresini alma
*/

function GetIP(){
	if(getenv("HTTP_CLIENT_IP")) {
 		$ip = getenv("HTTP_CLIENT_IP");
 	} elseif(getenv("HTTP_X_FORWARDED_FOR")) {
 		$ip = getenv("HTTP_X_FORWARDED_FOR");
 		if (strstr($ip, ',')) {
 			$tmp = explode (',', $ip);
 			$ip = trim($tmp[0]);
 		}
 	} else {
 	$ip = getenv("REMOTE_ADDR");
 	}
	return $ip;
}





//değer var mı yok mu? 

function getRowsCount($tableName=null,$where=null,$column='*'){

  $select = select("SELECT $column FROM $tableName  ".( $where != null ? " where $where " : null ));
  return [
           count($select),
           count($select)?'<span class="right badge badge-danger">'.count($select).'</span>' : null
       ];

}







function arrayFindVal($cur_val, $arr)
{
    static $keys = [];
    foreach ($arr as $key => $val){
        if (is_array($val)){
            $keys[] = $key;
            return arrayFindVal($cur_val, $val);
        } else {
            if ($val == $cur_val)
                $keys[] = $key;
        }
    }
    return $keys;
}




function logsAdd($type=null,$message=null,$resultData=null){
  global $db;
  $resultData = is_array($resultData) ? json_encode($resultData,JSON_UNESCAPED_UNICODE) : $resultData ;

  $sqlim = "insert into logs set
  LogType='$type',
  LogUserIP='".GetIP()."',
  resultData='$resultData',
  LogMessage='$message'";


  if($db->query($sqlim)){
      return true;
  }else{
      return false;
  }	

}





/*
    Cookie Oluşturma
*/
function cookie_olustur($ad=null,$deger=null,$zaman=1,$saat=false,$httpOnly=true){


  //Cookie gün ise burası çalışacak
  if($saat==false){
  $cookie_olustur = setcookie($ad,$deger,strtotime("+".$zaman." day"),"/",null,null,$httpOnly);
  return $cookie_olustur;

  //Cookie saat ise burası çalışacak
  }elseif($saat==true){
  $cookie_olustur = setcookie($ad,$deger,strtotime("+".$zaman." hour"),"/",null,null,$httpOnly);
  return $cookie_olustur;
  }

}