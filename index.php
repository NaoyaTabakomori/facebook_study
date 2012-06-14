<?php
ini_set( 'display_errors', 1 );  //エラー表示用
require_once("facebook_lib.php");  //ライブラリインポート
//session_set_cookie_params(30);
session_start();  //セッション開始
?>
<meta charset=UTF-8>
<a href="<?php echo getLoginUrl(); ?>">facebook_login</a><br> 

<?php 
  if(!empty($_GET['code']) || !empty($_SESSION['access_token'])){ //アクセストークン取得可能な時
    if(empty($_SESSION['access_token'])){//セッションにトークンが入ってない時
      $_SESSION['access_token'] =  getAccessToken($_GET['code']);
    }
//ここに、動かしたいコードを書く！

    echo "ログインしてます";

  }
?>
