<?php 




/* =======================> */
/**
 *  POST ve GET İŞLEMLERİ
 */
/* =======================> */

/**
 * Post temizle  ek işlem
 * https://hakanak.com.tr/php-ile-get-post-temizleme/
 */

function cleanInput($input=null) {
  
  $search = array(
    '@<script[^>]*?>.*?</script>@si',   // Javascript kodlarını temizleme
    '@<[\/\!]*?[^<>]*?>@si',            // HTML kodlarını temizleme
    '@<style[^>]*?>.*?</style>@siU',    // Stil kodlarını düzenleme
    '@<![\s\S]*?--[ \t\n\r]*>@'         // Çoklu yorum satırlarını temizleme
  );
  
    $output = preg_replace($search, '', $input);
    return $output;
  }
  
function sanitize($input=null) {
    if (is_array($input)) {
        foreach($input as $var=>$val) {
            $output[$var] = sanitize($val);
        }
    }
    else {
        $input  = cleanInput($input);
        $output = htmlspecialchars($input);
    }
    return $output;
}

/*
    Bu fonskiyon tekli değer döndürür
*/
function _post($name=null,$html=false){
  if (isset($_POST[$name])){
      if (is_array($_POST[$name])){
        return array_map(function($item){
          return $html ? $_POST[$name] : htmlspecialchars(trim($item)) ;
        },array_values(array_filter($_POST[$name])));
      }else{
        return $html ? $_POST[$name] : htmlspecialchars(trim($_POST[$name]));
      }

}else{
  return null;
}

return $_POST[$name];

}

/**
 * Bu fonksiyon post değerini dizi veya tekli şeklinde döndürürebilmek için.
*  Diğer _post fonsksiyonu sadece tek değer döndürür
 */
function post($name=null,$html=false){
  return  $name ? _post($name,$html) : sanitize($_POST) ;

}


/*
    Bu fonskiyon tekli değer döndürür
*/
function _get($name=null,$html=false){
  if (isset($_GET[$name])){
      if (is_array($_GET[$name])){
        return array_map(function($item){
          return $html ? $_GET[$name] : htmlspecialchars(trim($item)) ;
        },array_values(array_filter($_GET[$name])));
      }else{
        return $html ? $_GET[$name] : htmlspecialchars(trim($_GET[$name]));
      }

}else{
  return null;
}

return $_GET[$name];

}

/**
 * Bu fonksiyon post değerini dizi veya tekli şeklinde döndürürebilmek için.
*  Diğer _get fonsksiyonu sadece tek değer döndürür
 */
function get($name=null,$html=false){
  return  count($name)>0 ? _get($name,$html) : sanitize($_GET) ;

}









function isKey($cur_key=null, $arr=null){
  foreach ($arr as $key => $val){
      if ($key == $cur_key){
          return $val;
      }
      if (is_array($val)){
          return isKey($cur_key, $val); 
      }
  }
  return false;
}


function isValue($cur_val=null, $arr=null)
{
    
    foreach ($arr as $key => $val){
        if (is_array($val)){
            
            return find($cur_val, $val);
        } else {
            if ($val == $cur_val)
               return $val;
        }
    }
    return $false;
}

