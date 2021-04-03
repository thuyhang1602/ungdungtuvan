$(document).ready(function(){

    $("#search").click(function(){
        var key = $("#key").val();
        var unique_id = $("#unique_id").val();
        $("#loader").css("display", "block");
        $.ajax({
          url: "/search",
          type: 'POST',
          data: {
              key:key,
              unique_id:unique_id
          },
          beforeSend: function(xhr) {
            xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
          }
      }).done(function(res){
            $('.users-list').html(res);
            $("#loader").css("display", "none");
            $('#key').val("");
      });
    });

    $(".typing-area").submit(function(e){
        e.preventDefault();
    });

    $("#send").click(function(){
        var data = $(".input-field").val();
        var incoming_id = $(".incoming_id").val();
        $.ajax({
            url: "/insertchat",
            type: 'POST',
            data: {
                data:data,
                incoming_id:incoming_id
            }
        }).done(function(){
            document.querySelector(".typing-area").querySelector(".input-field").value = " "
        });
    });

    $("#position").on('change',function(){
        if(this.value === 'student'){
            $('label[for="major"]').text('Chuyên ngành');
            $("#major").attr("placeholder", "Nhập chuyên ngành");
        }else{
            $('label[for="major"]').text('Bộ môn');
            $("#major").attr("placeholder", "Nhập bộ môn");
        }
    });

    $("#position").on('change',function(){
        if(this.value === 'student'){
            $('label[for="school_year"]').css("display","block");
            $("#school_year").css("display", "block");
        }else{
            $('label[for="school_year"]').css("display","none");
            $("#school_year").css("display", "none");
        }
    });

    setInterval(function () {
        const chatbox = document.querySelector(".chat-box");
        var incoming_id = $(".incoming_id").val();
        $.ajax({
            url: "/getchat",
            type: 'POST',
            data: {
                incoming_id:incoming_id
            },
            beforeSend: function(xhr) {
                xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
            }
        }).done(function(res){
            $(".chat-box").html(res);
            if(!$(".chat-box").addClass("active")){
                chatbox.scrollTop = chatbox.scrollHeight;
            }
        });
    }, 500);

    $(".chat-box").mouseenter(function(){
        $(this).addClass("active");
    });

    $(".chat-box").mouseleave(function(){
        $(this).removeClass("active");
    });
});

var closebtns = document.getElementsByClassName("close");
var i;

for (i = 0; i < closebtns.length; i++) {
  closebtns[i].addEventListener("click", function() {
    this.parentElement.style.display = 'none';
  });
}