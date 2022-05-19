<?php

include_once "./config.php";


$userID     = (isset($_SESSION["userID"]) ? $_SESSION["userID"] : ( isset($_COOKIE["userID"]) ? $_COOKIE["userID"] : false ));
if( !$userID ){
    header('location: ./');
    exit;
}



//kullanıcı durumunu güncelle
if( isset($_SESSION["questionFinish"]) && $userID ){
    update("update users set userQuestionFinish=? where userID=? && userQuestionFinish=? && userStatus=?",['1',$userID,'0','1']);
}


//toplam doğru ve yanlış
$answers = select("select answerResult from answers where answerUserID =?",[$userID]);
$true = 0;
$false = 0;
foreach ($answers as $key => $value) {
    if( $value["answerResult"] == 'true' ){
        $true++;
    }else{
        $false++;
    }
}



//değerleri sil
setcookie("questionID","",(time() - 3600*30) ,'/');
setcookie("userID","",(time() - 3600*30) ,'/');

unset($_SESSION["questionID"]);
unset($_SESSION["questionFinish"]);
unset($_SESSION["userID"]);



include_once "./static/head.inc.php"; 

?>

<body class="confetti">

<?php include_once "./static/header.inc.php"; ?>


<section class="main">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="main__box">
                    <div class="row justify-content-center">
                        <div class="col-lg-3">
                            <img src="assets/img/tebrik.png" alt="tebrikler img">
                        </div>
                    </div>
                  <div class="main__box__title">
                      <h2>Tebrikler!</h2>
                      <p>Cevapladığın doğru cevap sayısı kadar çekiliş hakkı kazandın!</p>
                  </div>
                  <div class="row justify-content-center">
                    <div class="col-lg-6">
                        <!--<div class="sonuc">
                            <div class="dogru"><span></span><p>Doğru Cevaplanan: </p><b><?=$true?></b></div>
                            <div class="yanlis"><span></span><p>Yanlış Cevaplanan:</p> <b><?=$false?></b></div>
                        </div>-->
                        <div class="btn-tebrik">
                            <a href="./" class="btn">ANA SAYFAYA DÖN</a>
                        </div>
                    </div>
                </div>
                </div>
            </div>
        </div>
    </div>
</section>









<?php include_once "./static/footer.inc.php"; ?>

</body>

</html>