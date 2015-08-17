<?php

/*
@name Meteo
@author linquant <linquant@gmail.com>
@link 
@licence CC by nc sa
@version 1.0.0
@description Permet de recuperer la meteo

*/

//ajout lien dans menu setting

function meteo_plugin_setting_menu(){
	
	global $_;
	
	echo '<li '.(isset($_['section']) && $_['section']=='meteo'?'class="active"':'').'><a href="setting.php?section=meteo"><i class="fa fa-sun-o"></i></i> Meteo</a></li>';
	
}

// page du menu setting
function meteo_plugin_setting_page(){

	global $myUser,$_;
	
	if(isset($_['section']) && $_['section']=='meteo' ){
	
?>	
		<div style="margin-left:5px;">
		<h1>Meteo</h1>
		<p> Pour créer votre widget météo rendez vous sur <a href="http://www.meteocity.com/widget/">cette page</a> ou autre widget meteo de votre choix </p>
		</br>

	

<?php
	
		if (isset($_POST['codeMeteoCity'])){
	
			$codescript = '<center>'.$_POST['codeMeteoCity'].'</center>';
			
			echo $_POST['codeMeteoCity'];
			
			$monfichier = fopen('./plugins/meteo/codeMeteoCity.txt', 'a');
			ftruncate($monfichier,0);
			 
			//supprime les retours à la ligne.
            fputs($monfichier, str_replace(CHR(13).CHR(10),"",$codescript));
			fclose($monfichier);		
		}
	
?>
		<p> Copiez votre code dans cette zone :<p>
		<form action="./setting.php?section=meteo" method="post">
	    <TEXTAREA name="codeMeteoCity" rows="5" cols="300">Coller ici le code généré sur meteocity.com</TEXTAREA></br>
	    <INPUT TYPE="submit" NAME="nom" VALUE="Enregister">
	    </form>
	    </div>




	
<?php	
	

	}
}


//pas utilisé
function meteo_plugin_menu(&$menuItems){
	global $_;
	$menuItems[] = array('sort'=>10,'content'=>'<a href="index.php?module=meteo"><i class="fa fa-sun-o"></i></i> Meteo</a>');
}

//pas utilisé
function meteo_plugin_page($_){
	if(isset($_['module']) && $_['module']=='meteo'){
	?>

	<h1>Meteo</h1>
	<p> Pour créer votre widget météo rendez vous sur <a href="http://www.meteocity.com/widget/">cette page</a> <p>
	<p> pensez a faire un chmod </p>
	<p> copier votre code dans cette zone <p>
	

<?php
	
	if (isset($_POST['codeMeteoCity'])){

		$codescript = '<center>'.$_POST['codeMeteoCity'].'</center>';
		
		echo $codescript;
		
		$monfichier = fopen('./plugins/meteo/codeMeteoCity.txt', 'a');
		ftruncate($monfichier,0);
		
		fputs($monfichier,  $codescript);
		fclose($monfichier);		
	}

?>

	<form action="index.php?module=meteo" method="post">
    <TEXTAREA name="codeMeteoCity" rows="5" cols="300">Coller ici le code généré sur meteo city</TEXTAREA></br>
    <INPUT TYPE="submit" NAME="nom" VALUE="Enregister">
    </FORM>

	
<?php
	}
}


//Affichage du widget
function dash_meteo_plugin_menu(&$widgets){
    
		$widgets[] = array(
		    'uid'      => 'dash_monitoring_meteo',
		    'icon'     => 'fa fa fa-sun-o',
		    'label'    => 'meteo',
		    'background' => '#33CCFF', 
		    'color' => '#ffffff',
		    'onLoad'   => 'action.php?action=dash_meteo_plugin_load&bloc=meteo',
		    'onMove'   => 'action.php?action=dash_meteo_plugin_move&bloc=meteo',
		    'onDelete' => 'action.php?action=dash_meteo_plugin_delete&bloc=meteo',
		);
}

// Action du widget
function dash_meteo_plugin_actions(){
	global $myUser,$_,$conf;

	switch($_['action']){

		case 'dash_meteo_plugin_load':
			if($myUser==false) exit('Vous devez vous connecter pour cette action.');
			header('Content-type: application/json');

			$response = array();

			switch($_['bloc']){
				case 'meteo':
					
					$response['title'] = 'Meteo';
					
					if (file_exists('./plugins/meteo/codeMeteoCity.txt')) {
                        try {
                            $monfichier = fopen('./plugins/meteo/codeMeteoCity.txt', 'r');

                            $contenu = fgets($monfichier);
                            $response['content'] = $contenu;
                            fclose($monfichier);
                        } catch (Exception $e) {
                        }
						
					}
					else{
						
						 $response['content'] =' Rendez vous sur <a href="./setting.php?section=meteo">la page de configuration du plugin</a>';
						
						 
						
					}
					
				break;
			}
			
			
		
		echo json_encode($response);
		exit(0);
		
		break;
		
		
		
		case 'dash_monitoring_plugin_edit':
			echo '<label>Time Zone</label><input id="zone" type="text">';
		break;

		case 'dash_monitoring_plugin_save':

		break;
		case 'dash_monitoring_plugin_delete':

		break;
		case 'dash_monitoring_plugin_move':

		break;
	}
	
}	

Plugin::addCss('/css/style.css',true);
Plugin::addJs('/js/main.js',true);

//Plugin::addHook("menubar_pre_home", "meteo_plugin_menu");  
//Plugin::addHook("home", "meteo_plugin_page");

Plugin::addHook("widgets", "dash_meteo_plugin_menu");
Plugin::addHook("action_post_case", "dash_meteo_plugin_actions");

Plugin::addHook("setting_menu", "meteo_plugin_setting_menu");  
Plugin::addHook("setting_bloc", "meteo_plugin_setting_page");

?>