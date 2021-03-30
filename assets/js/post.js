$(document).ready(function(){
    $(".comment-button").click(function(){
        var user_id = $(".user_id").val();
        var content = $(".input-comment").val();
        var post_id = $(".post-id").attr("post-id");
        var comment_id = $("#comment_id").val();
        $.ajax({
            url: "/insertcomment",
            type: 'POST',
            data: {
                user_id:user_id,
                content:content,
                post_id:post_id,
                comment_id:comment_id
            }
        }).done(function(){
            $(".input-comment").val("");
        });
    });

    setInterval(function () {
        const commentbox = document.querySelector(".comments");
        $.ajax({
            url: "/getcomment",
            type: 'POST',
            beforeSend: function(xhr) {
                xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
            }
        }).done(function(res){
            var data = JSON.parse(res);
            var content ="";
            console.log(data);
            if(data.length > 0){
                for(var x of data){
                    content += "<div><strong>"
                    +x.firstname+" "
                    +x.lastname
                    +"("+x.position+"): </strong><span>"
                    +x.content+"</span>"
                    +"<a style='float:right;color:red;' href='/deletecomment/"
                    +x.id+"'>Xóa</a></div>";
                }
                $(".comments."+x.post_id).html(content);
                if(!$(".comments").addClass("active")){
                    commentbox.scrollTop = commentbox.scrollHeight;
                }
            }
        });
    }, 500);

    $(".comments").mouseenter(function(){
        $(this).addClass("active");
    });

    $(".comments").mouseleave(function(){
        $(this).removeClass("active");
    });
});