<?php
ini_set( 'display_errors', 1 );  //エラー表示用
require_once("facebook_lib.php");  //ライブラリインポート
//session_set_cookie_params(30);
session_start();  //セッション開始
?>
<meta charset=UTF-8>
<a href="<?php echo getLoginUrl(); ?>">ログインurl</a><br> 

<?php 
  if(!empty($_GET['code']) || !empty($_SESSION['access_token'])){ //アクセストークン取得可能な時
    if(empty($_SESSION['access_token'])){//セッションにトークンが入ってない時
      $_SESSION['access_token'] =  getAccessToken($_GET['code']);
    }
//ここに、動かしたいコードを書く！
    
    echo "<a href=''>更新</a><br><br>";
    $uid = getOneFriendId($_SESSION['access_token']);
    $user = getOneData($_SESSION['access_token'],$uid[0]->uid1);
    echo $user[0]->name . "さんが写っている写真<br><br><br>";
    $photos = getPhotos($_SESSION['access_token'],$uid[0]->uid1);
    if(!empty($photos)){  //写真が見つかった場合
      usort($photos,"cmp_like");  //人気順にソート
      foreach($photos as $key => $value){
        echo "<img src=" . $value->src_big . ">"; 
        echo $value->like_info->like_count . "いいね！";     
      }
    }else{
      echo "写真がありませんがな";
    }
  }
?>
