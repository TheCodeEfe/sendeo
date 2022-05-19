<?php 


/*
function smsSend ($message=null,$phone=null){

    if( !$message || !$phone ){
        return [
            "status" => "error",
            "message" => "Mesaj veya telefon numarası eksik "
        ];
    }

    try {
        $smsApi = new MesajPaneliApi();
    
        $data = [
            "msg" => $message,
            "tel" => [ $phone ]
        ];
    
        $smsCevap = $smsApi->topluMesajGonder( 'TheCodeYzlm', $data );
    
        
        return [
            "status" => "success",
            "result" => $smsCevap
        ];
    }
    catch ( Exception $e ) {
      
        return [
            "status" => "error",
            "message" => $e->getMessage()
        ];
    }


}*/

function sms_gonder ($message,$phone){

    
    //'DONUS|OK:178531988'
    //DONUS|HATA:NUMARA_YOK|Gonderilecek numaralari yazmalisiniz.

    $strRequest = [
        "islem"=>1,
        "user"=>5323535279,
        "pass"=>'yeni1234',
        "mesaj"=>$message,
        "numaralar"=>$phone,
        "baslik"=>'850'
    ] ;

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, 'http://api.smsvitrini.com/index.php');
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_POST, 1) ;
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($strRequest,'','&'));
    curl_setopt($ch, CURLOPT_TIMEOUT, 30);
    $result = curl_exec($ch);
    curl_close($ch);

    $status = strpos($result,'DONUS|OK') !== false ? "success" : "error" ;
        
        return  [
            "status" => $status,
            "message" => $result,

        ];

    }     



    