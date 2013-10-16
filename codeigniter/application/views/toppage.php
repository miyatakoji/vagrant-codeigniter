<!DOCTYPE html>
<html lang="ja">
<head>
	<meta charset="utf-8">
	<title>投稿一覧</title>
	<style type="text/css">

	::selection{ background-color: #E13300; color: white; }
	::moz-selection{ background-color: #E13300; color: white; }
	::webkit-selection{ background-color: #E13300; color: white; }

	body {
		background-color: #fff;
		margin: 40px;
		font: 13px/20px normal Helvetica, Arial, sans-serif;
		color: #4F5155;
	}


	#body{
		margin: 0 15px 0 15px;
	}
	
	p.footer{
		text-align: right;
		font-size: 11px;
		border-top: 1px solid #D0D0D0;
		line-height: 32px;
		padding: 0 10px 0 10px;
		margin: 20px 0 0 0;
	}
	
	#container{
		margin: 10px;
		border: 1px solid #D0D0D0;
		-webkit-box-shadow: 0 0 8px #D0D0D0;
	}
	</style>
    <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.4.2/jquery.min.js"></script>
	<script>
        $(function(){

            $('form').submit(function(){
                var postData = {};

                $('form').find(':input').each(function(){
                    postData[$(this).attr('name')] = $(this).val();
                });
                console.log(postData);
                $.ajax({
                    type : "POST",
                    url  : "tweet/tweet_entry",
                    data : postData,
                    dataType : "json",
                    success: function(data){
                        
                        clone = $('#moretweet').clone();
                        $(clone).children(".tweeted_date").html('投稿時間 : ' + data.tweeted_date);
                        $(clone).children(".name").html('ユーザ名 : ' + data.name);
                        $(clone).children(".tweet").html('ツイート : ' + data.tweet);
                        $(clone).children(".tweet_number").html('投稿番号 : ' + data.tweet_number);

                        $(clone).prependTo("#tweets");
                    }

                });
                return false;
            });

            $("#get_moretweet").click(function(){

                var oldest_tweetnumber = $(".tweet_add:last").attr('tweet_number');

                $.ajax({
                    type : "POST",
                    url  : "tweet/GetMoreTweet",
                    data : {'oldest_tweetnumber': oldest_tweetnumber},
                    dataType : "json",
                    success: function(data){
                    	var data_ = JSON.parse( data );

                        $.each(data, function(i){
                            clone = $('#moretweet').clone();
                            $(clone).children(".tweeted_date").html('投稿時間 : ' + data.tweeted_date);
                            $(clone).children(".username").html('ユーザ名 : ' + data.name);
                            $(clone).children(".tweet").html('ツイート : ' + data.tweet);
                            $(clone).children(".tweet_number").html('投稿番号 : ' + data.tweet_number);
                            
                            $("#get_moretweet").before(clone);
                        });
                    },
                });
                return false;
            });
        });
 	</script>

</head>
<body>
	<header style="background:gray; color:white;">
		<div>
			<p><?php echo $this->session->userdata('USERNAME'); ?></p>
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
		<div style="border:solid 1px; margin-top:30px;">
		<?php $i = 0; ?>
		<?php foreach ($ten_tweets as $v): ?>
        <div class = "tw" id = "<?php echo $v['tweet_number']; ?>">
            投稿時間 : <?php echo $v['tweeted_date']; ?><br>
            ユーザ名 : <?php echo $v['name']; ?><br>
            ツイート : <?php echo $v['tweet']; ?><br>
            投稿番号 : <?php echo $v['tweet_number']; ?><hr>
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
		<div id = "get_moretweet">
            <p>
                <a href="#">さらに読み込む</a><br>
            </p>
        </div>

        <div id = "moretweet">
            <span class = "tweeted_date"></span><br>
            <span class = "name"></span><br>
            <span class = "tweet"></span><br>
            <span class = "tweet_number"></span><br>
        </div>
    </div>


</body>
</html>