<?php

$client_id = "";     //app_id
$client_secret = "";    //app_secret
$redirect_uri = "";    //redirect_uri
 $scope = "
user_photos,
friends_photos,
email,
publish_stream,
read_stream
";                  //取得したいパーミッションを順次追加

//ログインurl取得用
function getLoginUrl(){
  global $client_id,$redirect_uri,$scope;
  $url = "http://www.facebook.com/dialog/oauth?client_id=" . $client_id . "&redirect_uri=" . urlencode($redirect_uri) . "&scope=" . $scope;
  return $url;
}

//アクセストークン取得用
function getAccessToken($code){
  global $client_id,$redirect_uri,$scope,$client_secret;
  $token_url = 'https://graph.facebook.com/oauth/access_token?client_id=' . $client_id . '&client_secret=' . $client_secret . '&redirect_uri=' . urlencode($redirect_uri) . '&code=' . $code;
  $access_token = file_get_contents($token_url);
  $arr = explode("=",$access_token);
  $access_token = $arr[1];
  return $access_token;
}

//以下、いろいろ取得用のfunction


function getAllFriendId($access_token){
  $ch = curl_init();
  $fql="select uid1 from friend where uid2 = me()";
  $url="https://graph.facebook.com/fql/?q=" . rawurlencode($fql) . "&access_token=" . $access_token . "&format=json";
  curl_setopt($ch, CURLOPT_URL, $url);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
  $resp = curl_exec($ch);
  $resp = json_decode($resp);
  return $resp->data;
}

function getOneFriendId($access_token){
  $ch = curl_init();
  $fql="select uid1 from friend where uid2 = me() ORDER BY rand() LIMIT 1";
  $url="https://graph.facebook.com/fql/?q=" . rawurlencode($fql) . "&access_token=" . $access_token . "&format=json";
  curl_setopt($ch, CURLOPT_URL, $url);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
  $resp = curl_exec($ch);
  $resp = json_decode($resp);
  //var_dump($resp);die;
  return $resp->data;
}

function getOneData($access_token,$uid){
  $ch = curl_init();
  $fql="select name from user where uid = " . $uid . " ORDER BY rand() LIMIT 1";
  $url="https://graph.facebook.com/fql/?q=" . rawurlencode($fql) . "&access_token=" . $access_token . "&format=json";
  curl_setopt($ch, CURLOPT_URL, $url);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
  $resp = curl_exec($ch);
  $resp = json_decode($resp);
  return $resp->data;
}

function getPhotos($access_token,$uid){
  $ch = curl_init();
  $fql = 'SELECT src,src_big,pid,like_info FROM photo WHERE pid IN(SELECT pid FROM photo_tag WHERE subject=' . $uid . ')' ;
  $url="https://graph.facebook.com/fql/?q=" . rawurlencode($fql) . "&access_token=" . $access_token . "&format=json";
  curl_setopt($ch, CURLOPT_URL, $url);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
  $resp = curl_exec($ch);
  $resp = json_decode($resp);
  return $resp->data;
}

function cmp_like($a,$b){
  $a1 = $a->like_info->like_count;
  $b1 = $b->like_info->like_count;
  if($a1 > $b1){
    $cmp = -1;
  }elseif($a1 == $b1){
    $cmp = 0;
  }else{
    $cmp = 1;
  }
return $cmp;
}

?>
