<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="utf-8">
    <title>投稿一覧</title>
    <style type="text/css">

    ::selection{ background-color: #E13300; color: white; }
    body {
        background-color: #fff;
        margin: 40px;
        font: 13px/20px normal Helvetica, Arial, sans-serif;
        color: #4F5155;
    }

    .tw {
        border:solid 1px; margin-top:30px;
    }

    </style>
    <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
</head>
<body>
    <header style="background:gray; color:white;">
        <div>
            <p><?php echo $this->session->userdata('USERNAME'); ?>さん</p>
            <a href="logout"><p>ログアウト</p></a>
        </div>
    </header>

    <?php
    echo validation_errors(); 

    $this->load->helper('form');

    echo form_open('tweet/tweet_entry');
    ?>

        <input name='tweet' type='text' size='140' value=''>
        <input type="submit" value="ツイート">
    <?php echo form_close(); ?>
    <p>投稿一覧</p>
    <div id="tweets">
        <div>
        <?php $i = 0; ?>
        <?php foreach ($ten_tweets as $v): ?>
        <div class = "tw" id = "<?php echo $v['tweet_id']; ?>">
            投稿時間 : <?php echo $v['tweeted']; ?><br>
            ユーザ名 : <?php echo $v['name']; ?><br>
            ツイート : <?php echo $v['tweet']; ?><br>
            投稿番号 : <?php echo $v['tweet_id']; ?><hr>
        </div>
        <?php
            $i++;
            if($i == 10){
                $oldest_tweetnumber = $i;
                break;
            }
        ?>
        <?php endforeach; ?>
    </div>
        <div id = "get_moretweet" name = "<?php echo $i; ?>">
            <p>
                <input id="get_moretweet" type="button" value="さらに読み込む"><br>
            </p>
        </div>
        <div id = "moretweet">
            <span class = "tweeted"></span><br>
            <span class = "name"></span><br>
            <span class = "tweet"></span><br>
            <span class = "tweet_id"></span><br>
        </div>
    </div>
    <script>

        $(function(){
            //ツイートボタンが押されたら、非同期で投稿されたツイートを表示する
            $("form").submit(function(event){
                event.preventDefault();
                var postData = {};

                $('form input:first')(function(){
                    postData[$(this).attr('name')] = $(this).val();
                });
                console.log(postData);
                $.ajax({
                    type : "POST",
                    url  : "tweet_entry",
                    data : postData.serialize(),
                    dataType : "json",
                    success : function(data){
                        // var v = data.serialize();
                        console.log(data);
                        clone = $('#moretweet').clone();
                        $(clone).children(".tweeted").html('投稿時間 : ' + v.tweeted);
                        $(clone).children(".name").html('ユーザ名 : ' + v.name);
                        $(clone).children(".tweet").html('ツイート : ' + v.tweet);
                        $(clone).children(".tweet_id").html('投稿番号 : ' + v.tweet_id);

                        $(clone).prependTo("#tweets");
                        }
                });
                return false;
            });

            //次の１０件を持ってくる
            $("#get_moretweet").click(function(){

                var oldest_tweetnumber = $(".tw:last").attr('id');
                console.log(oldest_tweetnumber);

                $.ajax({
                    type : "GET",
                    url  : "geting_moretweet",
                    data : {
                        oldest_tweetnumberdayo : "oldest_tweetnumber"
                    },
                    dataType : "json",
                    success: function(data){
                        var name = data[0]
                        $.each(data, function(i){
                            clone = $('#moretweet').clone().removeAttr("tweet_id").addClass("tw").attr({tweet_id: data[0].tweet_id});
                            $(clone).children(".tweeted").html('投稿時間 : ' + data[0].tweeted);
                            $(clone).children(".name").html('ユーザ名 : ' + data[0].name);
                            $(clone).children(".tweet").html('ツイート : ' + data[0].tweet);
                            $(clone).children(".tweet_id").html('投稿番号 : ' + data[0].tweet_id);

                            $("#get_moretweet").before(clone);
                        });
                    }
                });
                return false;
            });
        });

    </script>
</body>
</html>
