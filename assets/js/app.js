/*const icon = document.querySelector("#menuicon");
const menu = document.querySelector(".menuul");

icon.addEventListener('click', ()=>{
    const visiblity = menu.getAttribute("deta-visible");
    console.log(visiblity);
})*/

$(function () {
   
    $("#phone").inputmask({"mask": "(999)-999-9999"});


    $(document).on("focusout",".form__content-bg input",function(){
        let indexLabel = $(".form__content-bg input").index(this);
        let thisVal = $(this).val();
        if( thisVal.length > 0 ){
            $(".form__content-bg .label").eq(indexLabel).css({
                fontSize:"0.75rem",
                top:"0.25rem"
            })
        }else{
            $(".form__content-bg .label").eq(indexLabel).removeAttr("style")
        }
    });


    //Mail kontrol function 
    function MailKontrol(email) {
        var kontrol = new RegExp(/^[^0-9][a-zA-Z0-9_]+([.][a-zA-Z0-9_]+)*[@][a-zA-Z0-9_]+([.][a-zA-Z0-9_]+)*[.][a-zA-Z]{2,8}$/i);
        return kontrol.test(email);
    }
    $('#numbermob').modal({
        backdrop: 'static',
        keyboard: false
    });

    //form post alanı 
    $(document).on('submit', 'form.thecode_form_post', function() {

        $('.alert').removeClass("alert-danger").addClass('d-none');

        var data = new FormData(this);
        var button = $('button[type=submit]', this);
        var buttonText = button.text();

        let username = $('input#username').val();
        let phone = $('input#phone').val();
        let email = $('input#email').val();
        let business = $('input#business').val();
        let checkbox1 = $('input#checkbox1').prop("checked");
        let checkbox2 = $('input#checkbox2').prop("checked");
        let checkbox3 = $('input#checkbox3').prop("checked");
        

        
        let mesajError;

        if( !username ){
            mesajError = 'Ad soyad alanı zorunlu!';
        }else if( username.length < 5 ){
            mesajError = 'Adınız ve soyadınız çok kısa!';
        }else if( phone.length != 14 ){
            mesajError = 'Lütfen telefon numarasını doğru formatta giriniz!';
        }else if( !MailKontrol(email) ){
            mesajError = 'Lütfen e-posta adresinizi doğru formatta giriniz!';
        }else if( business.length < 3 ){
            mesajError = 'Lütfen firma adını giriniz!';
        }else if( !checkbox1 ){
            mesajError = 'Lütfen "Veri paylaşım iznini" kabul ediniz!';
        }else if( !checkbox2 ){
            mesajError = 'Lütfen "Açık rıza metnini" kabul ediniz!';
        }else if( !checkbox3 ){
            mesajError = 'Lütfen "Kullanım koşullarını" kabul ediniz!';
        }
        

        if( mesajError ){
            $('.alert').removeClass("d-none").addClass('alert-danger');
            $('.alert').text(mesajError);
            mesajError=null;
            return false;
        }
        
        
        
        
        $.ajax({
            url: 'ajax/ajax.php',
            type: "POST",
            contentType: false,
            processData: false,
            data: data, 
            beforeSend: function() {
                button.prop("disabled", true);
                button.html('Kaydediliyor...');
            },
            success: function(params) {
                var params = JSON.parse(params);

                $('.alert').removeClass("alert-danger").addClass('d-none');
                
                if (params.success) {
                    
                    //$('.alert').removeClass("d-none").addClass('alert-success');
                    //$(".alert").text(params.success);

                    $('.modal .p-modal').html(params.success);
                    $('#numbermob').modal('show');

                    //$('form.thecode_form_post')[0].reset();

                } else if (params.error) {
                    $('.alert').removeClass("d-none").addClass('alert-danger');
                    $(".alert").text(params.error);

                } else if (!params) {

                    $('.alert').removeClass("d-none").addClass('alert-danger');
                    $(".alert").text("Bir sorun oluştu!");
                }

                button.prop("disabled", false);
                button.text(buttonText);

    

            },
            error: function () {
                $('.alert').removeClass("d-none").addClass('alert-danger');
                $(".alert").text("Bir sorun oluştu!");
                
                button.prop("disabled", false);
                button.text(buttonText);
            }
        });
    });



$(document).on('keyup','input#vericationCode4',function () {

    
    let thisVal = $(this).val();
    if( thisVal && thisVal >= 0 && thisVal <= 9 ){
        $('form.vericationForm button[type="submit"]').prop("disabled",false);
        $('form.vericationForm button[type="submit"]').addClass("vericationButton");
    }else if( !thisVal ){
        $('form.vericationForm button[type="submit"]').prop("disabled",true);
        $('form.vericationForm button[type="submit"]').removeClass("vericationButton");
    }    
});


    //kod doğrulama form post alanı 
    $(document).on('submit', 'form.vericationForm', function() {

        
            var data = new FormData(this);
            var button = $('button[type=submit]', this);
            var buttonText = button.text();

    
            
            $.ajax({
                url: 'ajax/ajax.php',
                type: "POST",
                contentType: false,
                processData: false,
                data: data, 
                beforeSend: function() {
                    button.prop("disabled", true);
                    button.html('Lütfen bekleyiniz...');
                },
                success: function(params) {
                    var params = JSON.parse(params);
                    
                    if (params.success) {
                        
                        //$('.alert').removeClass("d-none").addClass('alert-success');
                        //$(".alert").text(params.success);

                        window.location.href = 'questions.php?action=start' ;

                        //$('form.vericationForm')[0].reset();

                    } else if (params.error) {
                        alert(params.error);

                    } else if (!params) {
                        $(".alert").text("Bir sorun oluştu!");
                    }

                button.prop("disabled", false);
                button.text(buttonText);

    

                },
                error: function () {
                    alert("Bir sorun oluştu!");
                    
                    button.prop("disabled", false);
                    button.text(buttonText);
                }
        });
  
    });


    let img = '';
    let questionOrder = '';
    let questionTitle = '';
    let questinList = '';
    let questionReply = '';
    let clickLi = '';

    $(document).on('click','ul#questionUL li',function () {

        console.log("clickLi1=>"+clickLi);
        
        if( clickLi !== '' ) return false;
        
        console.log("clickLi2=>"+clickLi);
        //let questionID = $('ul#questionUL li').data("id");
        questionReply = $('ul#questionUL li').data("reply");
        clickLi = $('ul#questionUL li').index(this);
        
        //nedense tıkjlayınca iki defa post yapıyor bende böyle bir çözüm ürettim
        $('ul#questionUL li input').prop("disabled",true);
        $('ul#questionUL li:eq('+clickLi+') input[type="radio"]').prop("checked",true);

        if( clickLi == questionReply ){
            $(this).addClass('true');
        }else{
            $(this).addClass('wrong');
            $('ul#questionUL li:eq('+questionReply+')').addClass("true");
        }

        //$('a#devamEt').prop("disabled",false);

        $.post("ajax/ajax.php",{questionPost:1,answer:clickLi},function (params) {


            $('a#devamEt').prop("disabled",true);
            
            if( params.success && params.html ){

                //$('img#questionImg').html(params.img);
                //$('h4#questionTitle').html(params.title);
                //$('ul#questionUL').html(params.html);
                $('a#devamEt').prop("disabled",false);

                questionOrder   = params.questionOrder;
                img             = params.img;
                questionTitle   = params.title;
                questinList     = params.html;

            }else if( params.success && !params.html ){

                //window.location.href = 'tebrikler.php?action=finish';
                $('a#devamEt').prop("disabled",false);
                $('a#devamEt').attr("href","./tebrikler.php?action=finish");

            }else{
                alert("bir hata oluştu!");
            }

    },"json");

        
    });



    $(document).on('click','a#devamEt',function () {
       
        setTimeout(() => {
            $('a#devamEt').prop("disabled",true);
            if( img.indexOf('youtube.com') == -1 ){
                $('img#questionImg').attr('src',img != '' ? img : '');
            }else{
                $('img#questionImg').addClass("d-none");
                $('img#questionImg').after('<div class="col-12 m-auto text-center">'+img+'</div>');
            }
            $('b#questionOrder').html(questionOrder != '' ? questionOrder : '');
            $('h4#questionTitle').html(questionTitle != '' ? questionTitle : '');
            $('ul#questionUL').html(questinList != '' ? questinList : '');

            questionReply='';
            clickLi='';
            questionOrder   = '';
            img             = '';
            questionTitle   = '';
            questinList     = '';

        }, 1000);

        
    });



    $("#hemenKazan").click(function() {
        console.log($("#h1Form").outerHeight());
      
        

        $('html, body').animate({
            scrollTop: $('#h1Form').offset().top - 20 
        }, 'fast');
    });



    // dopğrulama kodu girme alanı otomatik cod kutucuklarını geçme
    $(document).on('keyup','input[name="code[]"]',function (event) {

        //silme tuşu işlem yapma
        // backspace=8;
        // delete=46;
        // sol ok tuşu(left key)=37;
        // sağ ok tuşu(right key)=39;
        if (event.keyCode == 8 || event.keyCode == 46 || event.keyCode == 37 || event.keyCode == 39 ) return;       
        let indexCode = $('input[name="code[]"]').index(this);
        let codeValue = $('input[name="code[]"]:eq('+indexCode+')').val();
        if( codeValue >= 0 && codeValue <= 9 && Number.isInteger(Number(codeValue))){
            if( $('input[name="code[]"]:eq('+(indexCode+1)+')').length > 0 ){
                $('input[name="code[]"]:eq('+(indexCode+1)+')').focus();
            }
        }
    });


    const Confettiful = function (el) {
        this.el = el;
        this.containerEl = null;
      
        this.confettiFrequency = 3;
        this.confettiColors = ["#fce18a", "#ff726d", "#b48def", "#f4306d"];
        this.confettiAnimations = ["slow", "medium", "fast"];
      
        this._setupElements();
        this._renderConfetti();
      };
      
      Confettiful.prototype._setupElements = function () {
        const containerEl = document.createElement("div");
        const elPosition = this.el.style.position;
      
        if (elPosition !== "relative" || elPosition !== "absolute") {
          this.el.style.position = "relative";
        }
      
        containerEl.classList.add("confetti-container");
      
        this.el.appendChild(containerEl);
      
        this.containerEl = containerEl;
      };
      
      Confettiful.prototype._renderConfetti = function () {
        this.confettiInterval = setInterval(() => {
          const confettiEl = document.createElement("div");
          const confettiSize = Math.floor(Math.random() * 3) + 7 + "px";
          const confettiBackground = this.confettiColors[
            Math.floor(Math.random() * this.confettiColors.length)
          ];
          const confettiLeft = Math.floor(Math.random() * this.el.offsetWidth) + "px";
          const confettiAnimation = this.confettiAnimations[
            Math.floor(Math.random() * this.confettiAnimations.length)
          ];
      
          confettiEl.classList.add(
            "confetti",
            "confetti--animation-" + confettiAnimation
          );
          confettiEl.style.left = confettiLeft;
          confettiEl.style.width = confettiSize;
          confettiEl.style.height = confettiSize;
          confettiEl.style.backgroundColor = confettiBackground;
      
          confettiEl.removeTimeout = setTimeout(function () {
            confettiEl.parentNode.removeChild(confettiEl);
          }, 3000);
      
          this.containerEl.appendChild(confettiEl);
        }, 25);
    };
      
    if( $('.confetti').length > 0 ){
        window.confettiful = new Confettiful(document.querySelector(".confetti"));
    }
      



});