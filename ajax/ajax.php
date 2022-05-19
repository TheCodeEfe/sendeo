<?php 

include_once "../config.php";





if( !isset($_POST) || $_SERVER['REQUEST_METHOD'] !== 'POST' || !isAjax() ){

    $json["error"] = "Geçersiz istek!";
    echo json_encode($json);
    logsAdd("Hata",$json["error"],$_POST);
    exit;
}












// form kayıt
if( post('username') ){


    

    $username   =   post("username");
    $phone      =   str_replace(['(',')','-'],'',post("phone"));
    $email      =   post("email");
    $business   =   post("business");
    $ip         =   GetIP();



    if( !$username ){
        $json["error"] = "Ad soyad alanı zorunlu!";
        echo json_encode($json);
        logsAdd("Hata",$json["error"],$_POST);
        exit;
    }elseif( strlen($phone) != 10  ){
        $json["error"] = "Telefon numarasını 10 haneli olacak şekilde giriniz! ";
        echo json_encode($json);
        logsAdd("Hata",$json["error"],$_POST);
        exit;
    }elseif( substr($phone,0,1)  != 5 || !is_numeric($phone) ){
        $json["error"] = "Geçerli bir telefon numarası giriniz!";
        echo json_encode($json);
        logsAdd("Hata",$json["error"],$_POST);
        exit;
    }
    elseif( strlen($username) < 5 ){
        $json["error"] = "Adınız ve soyadınız çok kısa!";
        echo json_encode($json);
        logsAdd("Hata",$json["error"],$_POST);
        exit;
    }
    elseif( strlen($business) < 3 ){
        $json["error"] = "Lütfen firma adını giriniz!";
        echo json_encode($json);
        logsAdd("Hata",$json["error"],$_POST);
        exit;
    }
    elseif( !filter_var($email, FILTER_VALIDATE_EMAIL)  ){
        $json["error"] = "E-posta adresi geçersiz!";
        echo json_encode($json);
        logsAdd("Hata",$json["error"],$_POST);
        exit;
    }


    //güvenlik amaçlı sorgulama
/*
    $vericationLogs = select("seelct vericationID from verications where DAY(vericationDate) = DAY(CURDATE()) && vericationPhone=?",[$phone]);

    if( $vericationLogs && count($vericationLogs) >= 5 ){
        $json["error"] = "Bir gün içerisinde çok fazla kayıt işlemi yaptın. Daha sonra tekrar dene!";
        echo json_encode($json);
        logsAdd("Hata",$json["error"],$_POST);
        exit;
    }*/

/*
    $searchUser = select("select userID from users where userPhone=? || userEmail=?",[$phone,$email],false);

    if( $searchUser ){
        $json["error"] = "Girilen bilgilerle daha önce kayıt yapılmış!";
        echo json_encode($json);
        logsAdd("Hata",$json["error"],$_POST);
        exit;
    }*/


    //güvenlik amaçlı kaç defa kayıt yaptığını bulmak için
    insert("insert into verications set vericationPhone=?, vericationIP=?",[$phone,$ip]);


    $addUser = insert("insert into users set 
        userFullName=?,
        userPhone=?,
        userEmail=?,
        userBusiness=?,
        userIP=?
    ",[
        $username,
        $phone,
        $email,
        $business,
        $ip
    ]);

    


    if( $addUser ){

        $userVericationCode = rand(1111,9999);

        $update = update("update users set userVericationCode=? where userID=?",[ $userVericationCode,$addUser ]);

        if( $update ){

            $sms_gonder = sms_gonder('Sendeo doğrulama kodunuz: '.$userVericationCode,$phone);

            if( $sms_gonder["status"] == "success" ){


                $_SESSION["userID"] = $addUser;
                cookie_olustur("userID",$_SESSION["userID"],7);
    
                $_SESSION["questionID"] = select('select questionID from questions where questionStatus=? order by questionID asc',['1'],false)["questionID"];
    
                cookie_olustur("questionID",$_SESSION["questionID"],7);
    
                $_SESSION["questionOrder"] = 1 ;
                $json["success"] = "<strong>".str_replace(['(',')','-'],['','',' '],post("phone"))."</strong> numaralı telefon numarasına gönderilen 4 haneli doğrulama kodunu gir.";
                echo json_encode($json);
                exit;


            }else{
                $json["error"] = "Kayıt işlemi başarılı, doğrulama kodu gönderilemedi!";
                echo json_encode($json);
                logsAdd("Hata",$sms_gonder["message"],$sms_gonder["message"]);
                exit;
            }
            


        }else{
            $json["error"] = "Kayıt işlemi başarısız. Tekrar deneyiniz!";
            echo json_encode($json);
            logsAdd("Hata",$json["error"],$_POST);
            exit;
        }
        


    }else{
        $json["error"] = "Kayıt işlemi başarısız. Tekrar deneyiniz!";
        echo json_encode($json);
        logsAdd("Hata",$json["error"],$_POST);
        exit;
    }

}





// form kayıt
if( post('vericationCode') ){


    $code   = implode('',$_POST["code"]);
    $ip     = GetIP();


    if( !isset($_SESSION["userID"]) ){
        $json["error"] = "Öncelikle kayıt yapmalısınız!";
        echo json_encode($json);
        logsAdd("Hata",$json["error"],$_POST);
        exit;
    }


    $searchUser = select("select userID from users where userIP=? && userID =? && userVericationCode=? && userStatus=?",[$ip,$_SESSION["userID"],$code,'0'],false);

    if( !$searchUser ){
        $json["error"] = "Girilen kod hatalı!";
        echo json_encode($json);
        logsAdd("Hata",$json["error"],$_POST);
        exit;
    }


    //durumu güncelle
    $update = update("update users set userStatus=? where userID=?",['1',$searchUser["userID"] ] );

    if( $update ){

        $json["success"] = "Kod duğrulama başarılı!";
        echo json_encode($json);
        exit;

    }else{
        $json["error"] = "Bir hata oluştu ve kod doğrulanamadı!";
        echo json_encode($json);
        logsAdd("Hata",$json["error"],$_POST);
        exit;
    }

}


if( post("questionPost") ){


    $userID = $_SESSION["userID"];
    //$questionID = post("questionID");
    $questionID     = $_SESSION["questionID"];
    $answerChoice   = post("answer");
    $answerResult   = 'false';


    //kullanıcıyı sorgula
    $searchUser = select("select userID,userStatus from users where userID=?",[$userID],false);

    if( !$searchUser ){
        $json["error"] = "Bir hata oluştu!";
        echo json_encode($json);
        logsAdd("Hata",$json["error"],$_POST);
        exit;
    }elseif( $searchUser["userStatus"] != '1' ){
        $json["error"] = "Öncelikle formu doldurup telefonuza gelen kodu doğrulamalısınız!";
        echo json_encode($json);
        logsAdd("Hata",$json["error"],$_POST);
        exit;
    }



    //soruyu sorgula
    $searchQuestion = select("select questionID,questionReply from questions where questionID =? && questionStatus=?",[$questionID,'1'],false);

    if( !$searchQuestion ){
        $json["error"] = "Bu soru bulunamadı!";
        echo json_encode($json);
        logsAdd("Hata",$json["error"],$_POST);
        exit;
    }

    if( $searchQuestion["questionReply"] == $answerChoice ){
        $answerResult = "true";
    }



    //kullanıcının cevabını kaydedelim

    $insert = insert("insert into answers set 
        answerUserID=?,
        answerResult=?,
        answerChoice=?,
        answerQuestionID=?",
        [
        $userID,
        $answerResult,
        $answerChoice,
        $questionID
    ]);


    if( $insert ){

        $lastQuestion = select("select * from questions where questionID > ? order by questionID asc",[$questionID],false);

        if( $lastQuestion ){

            $html='';

            foreach ( json_decode($lastQuestion["questionOptions"],true) as $key => $value) {
                $html .= '
                    <li data-reply="'.$lastQuestion["questionReply"].'">
                        <label for="question_'.$key.'">
                        <input type="radio">
                        <span class="radios"></span>
                            '.$value.'
                        </label>
                    </li>
                ';
            }

            
            $_SESSION["questionOrder"] = $_SESSION["questionOrder"] ? $_SESSION["questionOrder"] + 1 : 1 ;
            $_SESSION["questionID"] = $lastQuestion["questionID"];
            cookie_olustur("questionID",$lastQuestion["questionID"],7);

            $json["questionOrder"] = $_SESSION["questionOrder"];
            $json["img"] = $lastQuestion["questionImg"];
            $json["title"] = $lastQuestion["questionTitle"];
            $json["html"] = $html;
            $json["success"] = "Devam Et!";
            echo json_encode($json);
            exit;

        }else{

            $_SESSION["questionID"] = $lastQuestion["questionID"];
            $_SESSION["questionFinish"] = true;
            //cookie_olustur("questionFinish",true,7);

            $json["success"] = "Bitti!";
            echo json_encode($json);
            exit;
        }

    }else{
        
        $json["error"] = "Bir hata oluştu, cevap kaydedilemedi!";
        echo json_encode($json);
        logsAdd("Hata",$json["error"],$_POST);
        exit;
    }





}