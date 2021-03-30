$(document).ready(function(){
    $(".comment-button").click(function(){
        var post_id = $(this).attr("data-attr");
        var user_id = $(".user_id").val();
        var content = $("#input-comment-"+post_id).val();
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
        $.ajax({
            url: "/getcomment",
            type: 'POST',
            beforeSend: function(xhr) {
                xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
            }
        }).done(function(res){
            if(res!==""){
                var data = JSON.parse(res);
                var content ="";
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
                    $(".comments").html(content);
                }
            }
        });
    }, 500);
});