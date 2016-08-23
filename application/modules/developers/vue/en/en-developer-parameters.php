<?php
header( 'content-type: text/html; charset=utf-8' );
//session_start();
// Indique à PHP que nous allons effectivement manipuler du texte UTF-8
mb_internal_encoding('UTF-8');

// indique à PHP que nous allons afficher du texte UTF-8 dans le navigateur web
mb_http_output('UTF-8');

require "C:/wamp/www/MaxFramework/application/database/ConnectPDO.class.php";
require "C:/wamp/www/MaxFramework/application/helper/ValidationFormulaire.class.php";
require "C:/wamp/www/MaxFramework/application/modules/developers/modele/entities/Developer.class.php";
require "C:/wamp/www/MaxFramework/application/modules/members/modele/entities/PassHash.class.php";
require "C:/wamp/www/MaxFramework/application/modules/developers/modele/sessions/DeveloperManager.class.php";
require "C:/wamp/www/MaxFramework/application/modules/developers/modele/sessions/DeveloperManager_PDO.class.php";
require "C:/wamp/www/MaxFramework/application/helper/GestionImages.class.php";
require "C:/wamp/www/MaxFramework/application/helper/MiseEnFormeDate.class.php";

$db = \database\ConnectPDO::getMysqlPDO('localhost','biblio_apps','root','');
$n = new \developers\modele\sessions\DeveloperManager_PDO($db);
/*if(!empty($_POST)) {

    if(isset($_POST['sumit'])){
        session_destroy();
        header("Location:/MaxFramework/fr/");
    }
}*/

$id = intval($_SESSION['devId']);
$membre = $n->getUniqueDeveloper($id);

$message="";
if(!empty($_POST)) {
    if (isset($_POST['pass'])) {
        $pass1 = strip_tags(htmlspecialchars(trim($_POST['new_password'])));
        $pass2 = strip_tags(htmlspecialchars(trim($_POST['confirm_password'])));
        if(empty($pass1)){
            $message = '<span style="padding: 3px 10px; margin-right: 10px; margin-left: 5px;margin-bottom: 10px;margin-top: 30px;" class="alert alert-danger">Attention veuillez entrer votre nouveau mot de passe !</span>';
        }elseif(empty($pass2)){
            $message = '<span style="padding: 3px 10px; margin-right: 10px; margin-left: 5px;margin-bottom: 10px;margin-top: 30px;" class="alert alert-danger">Attention veuillez confirmer votre nouveau mot de passe !</span>';
        }elseif($pass1 != $pass2){
            $message = '<span style="padding: 3px 10px; margin-right: 10px; margin-left: 5px;margin-bottom: 10px;margin-top: 30px;" class="alert alert-danger">Attention vos mots de passe ne correspondent pas !</span>';
        }else{
            $res = $n->changePasswd($id,$pass1);
            if(!$res){
                $message = '<span style="padding: 3px 10px; margin-right: 10px; margin-left: 5px;margin-bottom: 10px;margin-top: 30px;" class="alert alert-danger">Une erreur est survenue au niveau du serveur lors du changement de votre mot de passe</span>';
            }else{
                $message = '<span style="padding: 3px 10px; margin-right: 10px; margin-left: 5px;margin-bottom: 10px;margin-top: 30px;" class="alert alert-success">Vous avez reinitialisé avec succès votre mot de passe!</span>';
            }
        }
    }

    if (isset($_POST['updateprofil'])) {
        $pseudo = strip_tags(htmlspecialchars(trim($_POST['username'])));
        $mail = strip_tags(htmlspecialchars(trim($_POST['email'])));
        $membre->setPseudo($pseudo);
        $membre->setEmail1($mail);
        $result = $n->update($membre);

        if($result){
            $message = '<span style="padding: 3px 10px; margin-right: 10px; margin-left: 5px;margin-bottom: 10px;margin-top: 30px;" class="alert alert-success">Mise à jour effectuée avec succès!</span>';
        }else{
            $message = '<span style="padding: 3px 10px; margin-right: 10px; margin-left: 5px;margin-bottom: 10px;margin-top: 30px;" class="alert alert-danger">Une erreur est survenue au niveau du serveur lors de la mise à jour de votre profil</span>';
        }

    }

    if (isset($_POST['changephoto'])) {

        if( !empty($_FILES['avatar']['name']) )
        {
            $avatar = $_FILES['avatar'];

            $res = \applications\helper\GestionImages::checkAvatar($avatar);

            if(strpos($res,"OK-")===FALSE){

                $message = '<span style="padding: 3px 10px; margin-right: 10px; margin-left: 5px;margin-bottom: 10px;margin-top: 30px;" class="alert alert-danger">'.$res.'</span>';


            }else{
                $tab = explode("-",$res);

                $avatarname = $tab[1];
                $membre->setAvatar($avatarname);
                $result1 = $n->update($membre);

                if($result1){
                    $message = '<span style="padding: 3px 10px; margin-right: 10px; margin-left: 5px;margin-bottom: 10px;margin-top: 30px;" class="alert alert-success">Vous avez changé avec succès votre photo!</span>';
                }else{
                    $message = '<span style="padding: 3px 10px; margin-right: 10px; margin-left: 5px;margin-bottom: 10px;margin-top: 30px;" class="alert alert-danger">Une erreur est survenue au niveau du serveur lors de la mise à jour de votre photo de profil</span>';
                }

            }

        }else
        {

            $message = '<span style="padding: 3px 10px; margin-right: 10px; margin-left: 5px;margin-bottom: 10px;margin-top: 30px;" class="alert alert-danger">Vous n\'avez pas chargé d\'image !!!</span>';

        }

    }

}

?>
<!DOCTYPE html>
<html>
<head lang="fr">
    <title>Espace développeur | mybiblioapps</title>
    <!--<meta http-equiv="refresh" content="300" />-->
    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0" />
    <meta name="keywords" content="">
    <link rel="alternate" href="android-app://com.eurosport/eurosport/home-sport/www.eurosport.fr/22" />
    <link rel="canonical" href="http://www.biblioapps.com/" >
    <!-- Meta -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">
    <style >
        .teamimg {
            -webkit-transition: opacity 0.2s ease-in-out;
            -moz-transition: opacity 0.2s ease-in-out;
            -ms-transition: opacity 0.2s ease-in-out;
            -o-transition: opacity 0.2s ease-in-out;
            transition: opacity 0.2s ease-in-out;
        }
        .teamimg:hover {
            -ms-filter: "progid:DXImageTransform.Microsoft.Alpha(Opacity=60)";
            filter: alpha(opacity=60);
            -moz-opacity: 0.6;
            -khtml-opacity: 0.6;
            opacity: 0.6;
        }

    </style>

</head>

<!-- Favicon -->

<link rel="shortcut icon" href="/MaxFramework/public/images/logo/biblioapps.png">

<!-- Web Fonts -->

<link href="/MaxFramework/public/resources/awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">
<link href="/MaxFramework/public/resources/iconfont/material-icons.css" rel="stylesheet" type="text/css">
<link href="/MaxFramework/public/css/footer-v1.css" rel="stylesheet" type="text/css">
<link href="/MaxFramework/public/resources/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css">
<link type="text/css" rel="stylesheet" href="/MaxFramework/public/css/materialize.min.css"  media="screen,projection"/>


<link href="/MaxFramework/public/css/home.css" rel="stylesheet" type="text/css">

<!--<link href="/MaxFramework/public/css/style.css" rel="stylesheet" type="text/css">-->



</head>
<!--<body class="grey lighten-2">-->
<body class="grey lighten-2">
<div class="container">


    <div class="row" id="topbar">
        <div class="col l2 s10 offset-s1  push-l9">
            <a class='dropdown-button btn light-blue darken-3' href='#' data-activates='dropdown1'><i class="material-icons">language e894</i>Langes <!--<i class="material-icons right">arrow_drop_down</i>--></a>

            <!-- Dropdown Structure -->
            <ul id='dropdown1' class='dropdown-content'>
                <li><a href="/MaxFramework/fr/developpeur"><img src="/MaxFramework/public/images/flag1/fr.png" height="11px" width="16px"  alt="fr" />   French</a></li>
                <li class="divider"></li>
                <li><a href="/MaxFramework/en/developers"><img src="/MaxFramework/public/images/flag1/en.png" height="11px" width="16px" />   English</a></li>
                <li class="divider"></li>
                <li><a href="/MaxFramework/es/developers"><img src="/MaxFramework/public/images/flag1/es.png" height="11px" width="16px" />   Spain</a></li>
                <li class="divider"></li>
                <li><a href="/MaxFramework/de/developers"><img src="/MaxFramework/public/images/flag1/de.png" height="11px" width="16px"/>    Deusch</a></li>

            </ul>

        </div>
        <div class="col l1 hide-on-med-and-down">

            <!--<img src="/MaxFramework/public/images/logo/logo2.png">-->
            <div class="blue-text text-lighten-1" id="logo">MYBIBLIOAPPS</div>
        </div>
        <div class="col l2 offset-l2 hide-on-med-and-down">

            <a href="/MaxFramework/fr/membre/register" class="waves-effect waves-light btn light-blue darken-3"><i class="fa fa-lock"></i>&nbsp;INSCRIPTION | CONNEXION</a>
        </div>
    </div>
    <div class="row">
        <div class="col l12">
            <h5 class="center header">L'encyclopédie mondiale d'applications mobiles android.</h5>
            <!--<h6 class="center">&quot;Education,histoire,information,interaction.&quot;</h6>-->
        </div>
    </div>
    <!--========================================================================================================================================================   -->
    <!--========================================================================================================================================================   -->
    <!--  <div class="hide-on-large-only wrapper grey lighten-2 ">-->
    <!--  <div class="hide-on-med-and-up wrapper grey lighten-2 ">-->
    <div class="hide-on-med-only hide-on-large-only wrapper grey lighten-2 " style="margin-top: 0px;">
        <div class="header">
            <div class="container">

                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-responsive-collapse">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="fa fa-bars"></span>
                </button>

            </div>


            <div class="collapse navbar-collapse mega-menu navbar-responsive-collapse">
                <!-- <div class="container">-->

                <ul class="nav navbar-nav collection">
                    <div class="row">
                        <li class="collection-item avatar">
                            <i class="material-icons circle blue">home</i>
                            <a href="/MaxFramework/fr/" class="secondary-content">
                                Accueil
                            </a>
                        </li></div>
                    <div class="row">  <li class="collection-item avatar">
                            <i class="material-icons circle blue">apps</i>
                            <a href="/MaxFramework/fr/applications" class="secondary-content" >
                                Applications mobiles
                            </a>
                        </li></div>
                    <div class="row">  <li class="collection-item avatar">
                            <i class="material-icons circle blue">group</i>
                            <a href="/MaxFramework/fr/forum" class="secondary-content">
                                Forum
                            </a>
                        </li></div>
                    <div class="row">     <li class="collection-item avatar" >
                            <i class="material-icons circle blue">person</i>
                            <a href="/MaxFramework/fr/members" class="secondary-content" >
                                Espace membre
                            </a>
                        </li></div>
                    <div class="row">     <li class="collection-item avatar">
                            <i class="material-icons circle blue">add_box</i>
                            <a href="/MaxFramework/fr/livre-d-or" class="secondary-content">
                                Livre d'or
                            </a>
                        </li></div>
                    <div class="row">        <li class="collection-item avatar">
                            <i class="material-icons circle blue">assessment</i>
                            <a href="/MaxFramework/fr/annonceur" class="secondary-content" >
                                Annonceurs
                            </a>
                        </li></div>
                    <div class="row">   <li class="collection-item avatar" >
                            <i class="material-icons circle blue">email</i>
                            <a href="/MaxFramework/fr/newsletters" class="secondary-content" >
                                Newsletters
                            </a>
                        </li></div>
                    <div class="row">    <li class="collection-item avatar" >
                            <i class="material-icons circle blue">info</i>
                            <a href="/MaxFramework/fr/a-propos" class="secondary-content">
                                A propos
                            </a>
                        </li></div>
                    <div class="row">   <li class="collection-item avatar">
                            <i class="material-icons circle blue">phone</i>
                            <a href="/MaxFramework/fr/contact" class="secondary-content">
                                Contact
                            </a>
                        </li></div>

                </ul>

                <!--</div>-->
            </div>
        </div>
    </div>


    <!--========================================================================================================================================================   -->
    <!--========================================================================================================================================================   -->
    <div class="row">
        <div class="col s10  hide-on-large-only" style="margin-top: 0px;height: 8px;">
            <a href="/MaxFramework/fr/membre/register" class="waves-effect waves-light btn light-blue darken-3"><i class="fa fa-lock"></i>&nbsp;INSCRIPTION | CONNEXION</a>
        </div>
    </div>
    <div class="row">
        <div class="col s10 offset-s2 hide-on-large-only" >

            <img src="/MaxFramework/public/images/logo/logo2.png">

        </div>
    </div>
    <header style="margin-top:0px;">
        <nav id="nav">
            <div class="nav-wrapper">
                <!--<a href="#!" class="brand-logo">MYBIBLIOAPPS</a>-->
                <a href="#" data-activates="mobile-demo" class="button-collapse"><i class="material-icons">menu</i></a>
                <ul class="right hide-on-med-and-down">

                    <li ><a href="/MaxFramework/fr/">ACCUEIL</a></li>
                    <li><a href="/MaxFramework/fr/applications">APPLICATIONS</a></li>
                    <li><a href="/MaxFramework/fr/forum">FORUM</a></li>
                    <li><a href="/MaxFramework/fr/membre">ESPACE MEMBRE</a></li>
                    <li><a href="/MaxFramework/fr/livre-d-or">LIVRE D'OR</a></li>
                    <li><a href="/MaxFramework/fr/annonceur">ANNONCEURS</a></li>
                    <li ><a href="/MaxFramework/fr/newsletters">NEWSLETTERS</a></li>
                    <li ><a href="/MaxFramework/fr/a-propos">A PROPOS</a></li>
                    <li id="last"><a href="/MaxFramework/fr/contact">CONTACT</a></li>

                </ul>
                <!--<ul class="side-nav blue lighten-3 collection" id="mobile-demo">-->
                <ul class="side-nav grey lighten-2 collection" id="mobile-demo">

                    <li class="collection-item avatar">
                        <i class="material-icons circle blue">home</i>
                        <a href="/MaxFramework/fr/" class="secondary-content">
                            Accueil
                        </a>
                    </li>
                    <li class="collection-item avatar">
                        <i class="material-icons circle blue">apps</i>
                        <a href="/MaxFramework/fr/applications" class="secondary-content" >
                            Applications mobiles
                        </a>
                    </li>
                    <li class="collection-item avatar">
                        <i class="material-icons circle blue">group</i>
                        <a href="/MaxFramework/fr/forum" class="secondary-content">
                            Forum
                        </a>
                    </li>
                    <li class="collection-item avatar">
                        <i class="material-icons circle blue">person</i>
                        <a href="/MaxFramework/fr/members" class="secondary-content" >
                            Espace membre
                        </a>
                    </li>
                    <li class="collection-item avatar">
                        <i class="material-icons circle blue">add_box</i>
                        <a href="/MaxFramework/fr/livre-d-or" class="secondary-content">
                            Livre d'or
                        </a>
                    </li>
                    <li class="collection-item avatar">
                        <i class="material-icons circle blue">assessment</i>
                        <a href="/MaxFramework/fr/annonceur" class="secondary-content" >
                            Annonceurs
                        </a>
                    </li>
                    <li class="collection-item avatar" >
                        <i class="material-icons circle blue">email</i>
                        <a href="/MaxFramework/fr/newsletters" class="secondary-content" >
                            Newsletters
                        </a>
                    </li>
                    <li class="collection-item avatar" >
                        <i class="material-icons circle blue">info</i>
                        <a href="/MaxFramework/fr/a-propos" class="secondary-content">
                            A propos
                        </a>
                    </li>
                    <li class="collection-item avatar">
                        <i class="material-icons circle blue">phone</i>
                        <a href="/MaxFramework/fr/contact" class="secondary-content">
                            Contact
                        </a>
                    </li>

                </ul>
            </div>
        </nav>
    </header>
    <!--=== Breadcrumbs ===-->


    <div class="row" id="breadcrumb">
        <div class="col s12">
            <span class="black-text text-darken-4">Vous &ecirc;tes ici   &nbsp;&nbsp; &nbsp;</span>
            <a href="/MaxFramework/fr/" class="">Accueil <span class="glyphicon glyphicon-chevron-right"></span></a>
            <span>espace développeur</span>


        </div><!--/container-->
    </div>
</div>
<!-- <div class="divider green darken-4"></div>-->
<!--<ul class="breadcrumb">
    <span class="black-text text-darken-4">Vous &ecirc;tes ici   &nbsp;&nbsp; &nbsp;</span>
    <li><a href="#">Accueil</a></li>
    <li><a href="#">Library</a></li>
    <li class="active">Data</li>
</ul>-->


<div class="container">
    <?php if(!empty($message)){echo'<div class="center">';echo $message;echo'</div>';echo'<br><br>';}  ?>
    <hr style="border-width: 2px;border-color:#0d47a1;border-style: solid;margin-top:4px;"/>

    <div class="row" >

        <div class="col l3 m4 s12">
            <div class="teammembers" style="border: 1px dashed #0000FF;margin: 20px 0;padding: 15px;">
                <div class="valign">
                    <div class="teamimg">
                        <img height="30%" width="30%" style="margin:0;padding:4px;" data-effect="helix" class="img-circle" src="<?='/MaxFramework/public/images/avatars/'.$membre->getAvatar(); ?>" alt="">
                    </div>
                    <div class="desc" style="padding-left:15px;">
                        <h5 style="padding:0;font-weight:600;margin:10px 0 0;"><?=$membre->getPseudo(); ?></h5>
                        <small><?=$membre->getEmail1(); ?></small>
                    </div>
                </div><!-- valign -->
                <hr style="border-width: 2px;border-color:#0d47a1;border-style: solid;margin-top:4px;"/>

                <div class="list-group">
                    <a style="font-weight: bolder;" class="list-group-item" href="/MaxFramework/fr/developpeur"><i class="fa fa-user"></i>&nbsp;&nbsp;Mes applications</a>
                    <a style="font-weight: bolder;" class="list-group-item active" href="#"><i class="fa fa-cogs"></i>&nbsp;&nbsp; Mes param&egrave;tres</a>
                    <a style="font-weight: bolder;" class="list-group-item " href="/MaxFramework/fr/developpeur/publier"><i class="fa fa-android"></i>&nbsp;&nbsp; Publier</a>
                    <a style="font-weight: bolder;" class="list-group-item" href="/MaxFramework/fr/developpeur/logout"><i class="fa fa-sign-out"></i>&nbsp;&nbsp; D&eacute;connexion</a>
                </div>

            </div>


        </div>

        <div class="col l9 m8 s12">
            <ul id="servicetab" class="nav nav-tabs" style="font-weight: bolder;">
                <li class="active"><a href="#service11" data-toggle="tab"><i class="fa fa-user"></i> MODIFIER MON PROFIL</a></li>
                <li><a href="#service12" data-toggle="tab"><i class="fa fa-lock"></i> MODIFIER MON MOT DE PASSE</a></li>
            </ul>
            <div id="servicetabcontent" class="tab-content">
                <div class="tab-pane fade in active clearfix" id="service11">

                    <div class="col-lg-12">
                        <div class="bs-example">
                            <table class="table">
                                <tbody>
                                <tr>
                                    <td><strong style="font-weight: bolder;">Nom utilisateur</strong></td>
                                    <td><?=$membre->getPseudo();?></td>
                                </tr>
                                <tr>
                                    <td><strong style="font-weight: bolder;">Adresse Email</strong></td>
                                    <td><?=$membre->getEmail1();?></td>
                                </tr>
                                </tbody>
                            </table>
                        </div><!-- /example -->
                    </div>
                    <div class="col l3 s12">
                        <a href="#" data-target="#photo1-modal" data-toggle="modal"><button style="background-color:#0d47a1;margin-top: 20px; " class="btn btn-primary fa fa-upload">&nbsp;changer ma photo</button> </a>

                    </div>
                    <div class="col l3 offset-l3 s12">
                        <a href="#" data-target="#profil1-modal" data-toggle="modal"><button style="background-color:#0d47a1;margin-top: 20px; " class="btn btn-primary fa fa-edit">&nbsp; MODIFIER MON PROFIL</button> </a>
                    </div>


                </div>
                <div class="tab-pane fade clearfix" id="service12">
                    <div class="col-lg-8 col-md-8 col-sm-12" style="margin: auto;">

                        <form id="registerform" method="post" name="registerform" action="" enctype="multipart/form-data">
                            <br>
                            <div class="form-group">
                                <div class="input-group">
                                    <span class="input-group-addon"><i class="fa fa-lock"></i></span>
                                    <input id="new1" type="password" name="new_password" class="form-control" placeholder="Entrez le nouveau mot de passe">
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="input-group">
                                    <span class="input-group-addon"><i class="fa fa-lock"></i></span>
                                    <input id="new2"  type="password" name="confirm_password" class="form-control" placeholder="Confirmer le nouveau mot de passe">
                                </div>
                            </div>

                            <input type="hidden" name="idMbre" value="<?=$membre->getId();?>">

                            <div class="form-group">
                                <input id="pass" name="pass" type="submit" class="btn btn-primary" value="MODIFIER" style="background-color:#0d47a1;margin-top: 20px;">
                            </div>
                        </form>
                    </div>
                </div>
            </div>

        </div>



    </div>
</div>




<!--  début   -->

<!--  début   -->
<?php include("footer.html");  ?>
<!--=== End Footer Version 1 ===-->
<script type="text/javascript" src="/MaxFramework/public/js/jquery.min.js"></script>
<script type="text/javascript" src="/MaxFramework/public/resources/bootstrap/js/bootstrap.min.js"></script>
<script type="text/javascript" src="/MaxFramework/public/js/materialize.js"></script>
<script type="text/javascript" src="/MaxFramework/public/js/init.js"></script>
<!--<script type="text/javascript" src="/MaxFramework/public/js/jquery-2.js"></script>-->

<script type="text/javascript">

    $(function() {

        $('#pass').click(function(){

            var pass1 = $('#new1').val();
            var pass2 = $('#new2').val();
            if(pass1==''){
                $('#mac').remove();
                $('#registerform').before('<div class="mac"><br><div  class="alert alert-danger">Veuillez entrer votre nouveau mot de passe</div></div>');
                return false;
            }else if(pass1.length<4){

                $('.mac').remove();
                $('#registerform').before('<div class="mac"><br><div  class="alert alert-danger">Votre nouveau mot de passe est trop court !!!</div></div>');
                return false;
            }else if(pass1.length>50){

                $('.mac').remove();
                $('#registerform').before('<div class="mac"><br><div  class="alert alert-danger">Votre nouveau mot de passe  est trop long !!!</div></div>');
                return false;
            }else if(pass2==''){

                $('.mac').remove();
                $('#registerform').before('<div class="mac"><br><div  class="alert alert-danger">Veuillez confirmer  votre nouveau mot de passe !!!</div></div>');
                return false;
            }
            else if(pass1 != pass2){

                $('.mac').remove();
                $('#registerform').before('<div class="mac"><br><div  class="alert alert-danger">Vos mots de passe ne correspondent pas !!!</div></div>');
                return false;
            } else{
                $('.mac').remove();

            }
        });

        $('#updateprofil').click(function(){
            var pseudo = $('#username').val();
            var mail = $('#email').val();
            var emailReg = /^[a-z0-9._-]+@[a-z0-9._-]{2,}\.[a-z]{2,4}$/;
            if(pseudo==''){
                $('#mac').remove();
                $('#login').before('<div class="mac"><br><div  class="alert alert-danger">Veuillez entrer votre nouveau pseudo !!!</div><br></div>');
                return false;
            }else if(pseudo.length<3){

                $('.mac').remove();
                $('#login').before('<div class="mac"><br><div  class="alert alert-danger">Votre nouveau pseudo est trop court !!!</div><br></div>');
                return false;
            }else if(pseudo.length>32){

                $('.mac').remove();
                $('#login').before('<div class="mac"><br><div  class="alert alert-danger">Votre nouveau pseudo est trop long !!!</div><br></div>');
                return false;
            }else if(mail==''){

                $('.mac').remove();
                $('#login').before('<div class="mac"><br><div  class="alert alert-danger">Veuillez entrer votre nouvelle adresse email !!!</div><br></div>');
                return false;
            }else if(!emailReg.test(mail)){

                $('.mac').remove();
                $('#login'). before('<div class="mac"><br><div  class="alert alert-danger">Veuillez entrer une adresse email valide !!!</div><br></div>');
                return false;
            }else{
                $('.mac').remove();
            }
        });




    });


</script>

</body>
</html>