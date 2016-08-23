<?php
/**
 * Created by PhpStorm.
 * User: marcelinsakou
 * Date: 04/06/2016
 * Time: 17:09
 */
session_start();
$decompo_url = \requete\HTTPRequest::decompositionurl();
$l = \requete\HTTPRequest::langue();

if(isset($decompo_url[4]) AND $decompo_url[4] !=NULL){

   /* if($decompo_url[4]=="register"){
        include('vue/'.$l.'/'.$l.'-register-developer.php');
    }else{

        include("C:/wamp/www/MaxFramework/error/".$l."/error.php");
    }*/
    if(isset($_SESSION['devId'])){

        if($decompo_url[4] == "parameters") {
            include('vue/'.$l.'/'.$l.'-developer-parameters.php');

        }elseif($decompo_url[4] == "logout"){
            include('vue/'.$l.'/'.$l.'-developer-logout.php');
        }elseif(($decompo_url[4] == "publish")|| ($decompo_url[4] == "publier") ){

            include('vue/'.$l.'/'.$l.'-developer-publish.php');
        }else{

            include('vue/'.$l.'/'.$l.'-developer.php');
        }

        //include('vue/'.$l.'/'.$l.'-developer.php');

    }else {
        if ($decompo_url[4] == "register") {

            include('vue/' . $l . '/' . $l . '-register-developer.php');

        }elseif($decompo_url[4] == "forget"){

            include('vue/' . $l . '/' . $l . '-forget-password.php');

        }

        elseif (strpos($decompo_url[4],"validation?log=")===FALSE) {


            include("C:/wamp/www/MaxFramework/error/" . $l . "/error.php");

        } else {
            include('vue/' . $l . '/' . $l . '-validation.php');

        }
    }





}else{

   if(isset($_SESSION['devId'])){
       include('vue/'.$l.'/'.$l.'-developer.php');
    }else{
        include('vue/'.$l.'/'.$l.'-register-developer.php');
    }

  //  include('vue/'.$l.'/'.$l.'-developer.php');
}