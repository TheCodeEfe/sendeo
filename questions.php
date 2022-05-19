<?php

include_once "./config.php";

//daha önce soruları çözmüş olduğuna dair cookie var ise
/*if( isset($_COOKIE["questionFinish"]) ){
  header("location:./tebrikler.php?question=finish");
  exit;
}*/

$userID     = (isset($_SESSION["userID"]) ? $_SESSION["userID"] : ( isset($_COOKIE["userID"]) ? $_COOKIE["userID"] : false ));
$questionID = (isset($_SESSION["questionID"]) ? $_SESSION["questionID"] : ( isset($_COOKIE["questionID"]) ? $_COOKIE["questionID"] : false ));


//sessionları güncelleyelim
$_SESSION["userID"] = $userID ;
$_SESSION["questionID"] = $questionID ;


if( !$userID ){
    $message = 'Önce formu doldurmalısınız! <br> <br><a href="./" class="btn btn-primary">Formu Doldur</a>';
}elseif( !$questionID ){
    $message = 'Önce formu doldurmalısınız1! <br> <br><a href="./" class="btn btn-primary">Formu Doldur</a>';
}

if( !$message ){

  $user = select("select * from users where userID=?",[$userID],false);
  
  if( $user["userStatus"] != '1' ){
    $message = 'Öncelikle formu doldurup telefonuzı numaranızı doğrulamalısınız! <br> <br><a href="./" class="btn btn-primary">Formu Doldur</a>';
  }else if( $user["userQuestionFinish"] != '0' ){
    $message = 'Zaten soruları cevapladınız!';
  }


 // $answers = select("select * from answers where answerUserID=?",[$user["userID"]]);

  $questions = select("select * from questions where questionStatus=? && questionID=?",['1',$questionID],false);
  
  if( !$questions ){
    header("location:./tebrikler.php?question=finish");
    exit;
  }
 
  
  
}


/*
$question = [

  "title" => 'Aşağıdakilerden hangisi Artvin ilimizin meşhur yemeğidir?',
  "img"   => 'assets/question-img/img-1.jpg',
  "answer" => '0',
  "options" => [
      "Kebap",
      "Simit",
      "Laz Böreği",
      "Mantı"
    
  ];

  print_r(json_encode($question,JSON_UNESCAPED_UNICODE));*/


  





include_once "./static/head.inc.php"; 

?>

<body>

<?php include_once "./static/header.inc.php"; ?>



<?php if( $message ){?>
  
<section class="slide">
  <div class="container">
    <div class="row">
      <div class="col-lg-12">
        
        <div class="alert alert-warning"><?=$message?></div>
      </div>
    </div>
  </div>
</section>


<?php }else{?>
<section class="slide">
  <div class="container">
    <div class="row">
      <div class="col-lg-12 p-0">
        <?php 
                if( strpos($questions["questionImg"],'youtube.com') === false ){
                  echo '<img src="'.$questions["questionImg"].'" id="questionImg" alt="'.$questions["questionTitle"].'" class="soru-img">';
              }else{
                 echo '<div class="col-12 m-auto text-center">'.$questions["questionImg"].'</div>';
              }
        ?>
        
      </div>
    </div>
  </div>
</section>
<section class="main">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="main__box">
                  <div class="main__box__title">
                   <span><b id="questionOrder"><?=$_SESSION["questionOrder"]?></b>/3</span>
                    <h4 id="questionTitle"><?=$questions["questionTitle"]?></h4>
                  </div>
                  <div class="row justify-content-center">
                    <div class="col-lg-6">
                      <ul id="questionUL">
                        <?php foreach ( json_decode($questions["questionOptions"],true) as $key => $value) {?>
                        <li data-reply="<?=$questions["questionReply"]?>">
                          <label for="question_<?=$key?>">
                            <input type="radio" >
                            <span class="radios"></span>
                              <?=$value?>
                          </label>
                        </li>
                        <?php }?>
                      </ul>
                      <div class="btn-oyun">
                        <a href="javascript:void(0)" disabled class="btn" id="devamEt">DEVAM ET</a>
                      </div>
                    </div>
                </div>
                </div>
            </div>
        </div>
    </div>
</section>

<?php }?>






<?php include_once "./static/footer.inc.php"; ?>

</body>

</html>