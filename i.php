<?php
use Facebook\Facebook;
use Facebook\Exceptions\FacebookResponseException;
use Facebook\Exceptions\FacebookSDKException;

session_start();
require_once __DIR__ . '/Facebook/autoload.php';
$fb = new Facebook([
  'app_id' => '<Enter your app ID here>',
  'app_secret' => '<Enter your app secret here>',
  'default_graph_version' => 'v2.9',
  ]);
$helper = $fb->getRedirectLoginHelper();
//$permissions = ['email']; // optional

$permissions =  array("email","user_friends");	
try {
	if (isset($_SESSION['facebook_access_token'])) {
		$accessToken = $_SESSION['facebook_access_token'];
	} else {
  		$accessToken = $helper->getAccessToken();
	}
} catch(Facebook\Exceptions\FacebookResponseException $e) {
 	// When Graph returns an error
 	echo 'Graph returned an error: ' . $e->getMessage();
  	exit;
} catch(Facebook\Exceptions\FacebookSDKException $e) {
 	// When validation fails or other local issues
	echo 'Facebook SDK returned an error: ' . $e->getMessage();
  	exit;
 }
if (isset($accessToken)) {
	if (isset($_SESSION['facebook_access_token'])) {
		$fb->setDefaultAccessToken($_SESSION['facebook_access_token']);
	} else {
		// getting short-lived access token
		$_SESSION['facebook_access_token'] = (string) $accessToken;
	  	// OAuth 2.0 client handler
		$oAuth2Client = $fb->getOAuth2Client();
		// Exchanges a short-lived access token for a long-lived one
		$longLivedAccessToken = $oAuth2Client->getLongLivedAccessToken($_SESSION['facebook_access_token']);
		$_SESSION['facebook_access_token'] = (string) $longLivedAccessToken;
		// setting default access token to be used in script
		$fb->setDefaultAccessToken($_SESSION['facebook_access_token']);
	}
	// redirect the user back to the same page if it has "code" GET variable
	if (isset($_GET['code'])) {
		header('Location: ./');
	}
    
    
    // Getting user facebook profile info
    try {
 
        $profileRequest = $fb->get('/me?fields=name,first_name,last_name,locale,picture',$_SESSION['facebook_access_token']);
        $profileRequest1 = $fb->get('/me?fields=name');
        $requestPicture = $fb->get('/me/picture?redirect=false&height=310&width=300'); //getting user picture
        $requestFriends = $fb->get('/me/taggable_friends?fields=name&limit=20');
		$fbUserProfile = $profileRequest->getGraphNode()->asArray();
		$friends = $requestFriends->getGraphEdge();
		$a = $fb->get('/me/friends?fields=name');
		$b = $a ->getGraphEdge();
        $fbUserProfile1 = $profileRequest1->getGraphNode();
        $picture = $requestPicture->getGraphNode();
        $fbUserProfile3 = $profileRequest3->getGraphNode();
        
		
		// If button is clicked a photo with a caption will be uploaded to facebook
		if(isset($_POST['insert'])){
     	$data = ['source' => $fb->fileToUpload(__DIR__.'/photo.jpeg'), 'message' => 'Check out this app! It is awesome http://localhost:8080/crazy/i.pnp '];
		$request = $fb->post('/me/photos', $data);
		$response = $request->getGraphNode()->asArray();
		header("Location: http://facebook.com");
     
    }
        
        
        
    } catch(FacebookResponseException $e) {
    
    	
        echo 'Graph returned an errrrrrror: ' . $e->getMessage();
        session_destroy();
        header("Location: ./");
        exit;
    } catch(FacebookSDKException $e) {
        echo 'Facebook SDK returned an error: ' . $e->getMessage();
        exit;
    }
   
  $randomInteger = rand(0,19);
  $name= $friends[$randomInteger]['name'];
  

  
  $output = $fbUserProfile1;
  
  
  
  
 
?>
<html>
<head>
<title>Crazy app</title>
 <script src="html2canvas.js"></script> 
<style type="text/css">
body {
    background-image: url("wallpaper.jpeg");
    background-size: 1600px 800px;
  	background-repeat: no-repeat;
  
}
    .warning{font-family:Arial, Helvetica, sans-serif;color:#FFF; top:0px;position:relative;left:450px;}
    .you { position: relative; top: -200px; left: 300px; } 
    .cross { position: absolute; top: -200px; left: 270px; } 
    .blackboard{position:absolute; top:-200px; left:800px;}
    .content{font-family: Papyrus,fantasy;top:-450px;left:830px;position:relative;font-size:20px; }
    
    
    .loader{
    
    border: 16px solid #f3f3f3;
    border-radius: 50%;
    border-top: 16px solid #3498db;
    width: 120px;
    height: 120px;
    -webkit-animation: spin 1s linear 3;
    animation: spin 1s linear 3;
    position:relative;
    top:130px;
    left:350px;
    
    
    }
    .loader2{
    
    border: 16px solid #f3f3f3;
    border-radius: 50%;
    border-top: 16px solid #3498db;
    width: 120px;
    height: 120px;
    -webkit-animation: spin 1s linear 3;
    animation: spin 1s linear 3;
    position:relative;
    top:-35px;
    left:900px;
    
    
    }
    
    
    @-webkit-keyframes spin {
    0% { -webkit-transform: rotate(0deg); }
    100% { -webkit-transform: rotate(360deg); }
    }

    @keyframes spin {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
    }
    
    .button{
    background-image: url("share.png");
    background-size: 400px 50px;
    width: 400px;
    height:50px;
    }
 
    
    </style>
    <script>
    var hidden = false;






</script>

 
</head>
<body>
<form method="post"><center><input type="submit" name="insert" class="button" value=""/></center></form>


	<h1 class="warning"><b><?php echo $name." is your craziest friend!"; ?></b></h1>
    


    </body>
</html>
