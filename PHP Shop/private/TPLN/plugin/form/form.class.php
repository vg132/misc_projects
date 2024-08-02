<?php

class Form
{
 private $msg_err 		= array();  					 // Tableau des messages d'erreurs
 private $last_obj		= '';									 // Dernier objet passé en paramètre
 private $input			= array();  						 // Tableau d'objet
 private $objError		= array();  					 // Tableau des objet en erreur / permet de les signaler en javascript
 private $custom_msg	= '';									 // Personnalise les messages d'erreurs
 private $formErrorLangue = TPLN_ERROR_LANG; // Récupération de la langue par defaut 
 
 
 // methode privée généric
 // test s'il existe un message d'erreur personnalisé
function errorCustom()	
 {
 	// récupere les objet en erreur pour les signal au niveau du javascript
	if(!(in_array($this->last_obj, $this->objError) ||
	     in_array(substr($this->last_obj, 0, (strlen($this->last_obj)-3) ), $this->objError)
	     ))
	{
		if(ereg('\[\]$',$this->last_obj))
		{
			$this->objError[] = substr($this->last_obj, 0, (strlen($this->last_obj)-2) ) ;
		}
		else
			$this->objError[] = $this->last_obj;
	}
		
 	if(!empty($this->custom_msg))
	{
		$this->msg_err[] = $this->custom_msg;		
		$this->custom_msg = '';
		return true;
	}
	else
		return False;
 }
 
function rules($obj)
 {
 	if($_POST)
	{
		if(empty($obj))$obj = $this->last_obj;
		if(empty($obj))
		{
			if($this->formErrorLangue == 'fr')
				die('TPLN Form: Aucun objet trouvé');
			else
				die('TPLN Form: No object found');
		}
		$this->last_obj = $obj;
		
		return true;
	}
	else 
		return false; # obligatoire
		
 }
 

 function formIsValid($inc_js = true) // détruit le bloc error s'il n'y a pas de post
 {
	if(!$_POST)
	{

		$this->EraseBloc('form_error');
		return;
		
	}
	else
	{
		if(count($this->msg_err) == 0)
		{
			$this->EraseBloc('form_error');
			return true;
		}
		else
		{
			
			// Codage html de la sortie des messages d'erreur
			foreach($this->msg_err as $key => $val)
					$this->msg_err[$key] = htmlentities($val);			
			
		
			if($inc_js) $this->incJavascript();		
			
			for($i=0; $i < count($this->msg_err);$i++)
			{									
				if($inc_js && $i == count($this->msg_err)-2) # message d'erreur à la fin
					$m = $this->msg_err[$i].$this->msg_err[$i+1];
				else
					$m = $this->msg_err[$i];
				
				$this->Parse('form_error.msg', $m);		
				$this->Loop('form_error');		
				
				// on casse la boucle avant !
				if($inc_js && $i == count($this->msg_err)-2) break;
						
			}
		}
	}	
 }  
 
 function setMessage($msg) // Personnalise un message d'erreur
 {
	$this->custom_msg = $msg;
 }

 function notEmpty($obj='') // Vérifie qu'un objet n'est pas vide
 {
	
	if(!$this->rules($obj))return;
	
	// si notEmpty est utilisé sur une objet file alors fileControl est appellé à la place
	if(isset($_FILES[$this->last_obj]))
	{
  	$this->fileControl($this->last_obj,'oui');
  	return;
  }
	
	// Deux traitement differents pour les tableau et les non-tableau
	if(!ereg('\[\]$',$this->last_obj))
	{
		// s'il l'objet n'est pas initilisé il ne sera pas poster
		if(!isset($_POST[$this->last_obj]))
			$_POST[$this->last_obj] = '';
	
		// Vérifie si un objet a été défini
		if(empty($_POST[$this->last_obj]))
		{
			if(!$this->errorCustom())
			{
				if($this->formErrorLangue == 'fr')
					$this->msg_err[] = "Le champ '$this->last_obj' ne peut être vide";
				else
					$this->msg_err[] = "Field '$this->last_obj' can not be empty";
			}		
		}
	}
	else	# Tableau
	{
		// Traitement du tableau non initialiser
		if(!isset($_POST[substr($this->last_obj, 0, (strlen($this->last_obj)-2) )])) 
		{ 
			$_POST[str_replace('[]','',$this->last_obj)] = ''; # inclusion das post
			
			if(!$this->errorCustom())
			{
				if($this->formErrorLangue == 'fr')
					$this->msg_err[] = "Le champ '$this->last_obj' ne peut être vide";
				else
					$this->msg_err[] = "Field '$this->last_obj' can not be empty";
			}
			
		}
	}
 }
 
 function onlyDigit($obj='')
 {
	if(!$this->rules($obj))return;
	if(!ctype_digit($_POST[$this->last_obj]))
	{
		if(!$this->errorCustom())
		{
			if($this->formErrorLangue == 'fr')
				$this->msg_err[] = "Le champ '$this->last_obj' ne peut contenir que des chiffres";
			else
				$this->msg_err[] = "Field '$this->last_obj' can only countain digit";
		}
	}	
 }
 
 function onlyLetter($obj='')
 {
	if(!$this->rules($obj))return;
	if(!ctype_alpha($_POST[$this->last_obj]))
	{
		if(!$this->errorCustom())
		{
			if($this->formErrorLangue == 'fr')
				$this->msg_err[] = "Le champ '$this->last_obj' ne peut contenir que des lettres";
			else
				$this->msg_err[] = "Field '$this->last_obj' can only countain letters";
		}
	}	
	
 }

 function email($obj='')
 {
	if(!$this->rules($obj))return;
	if(!eregi("^[_\.0-9a-z-]+@([0-9a-z-]+\.)+[a-z]{2,4}$",$_POST[$this->last_obj]) && (!empty($_POST[$this->last_obj])))
	{
		if(!$this->errorCustom())
		{
			if($this->formErrorLangue == 'fr')
				$this->msg_err[] = "Le champ '$this->last_obj' n'est pas une adresse mail valide";
			else
				$this->msg_err[] = "Field '$this->last_obj' is not a valid mail address";
		}	
	}
 }

 function charLength($obj='',$length)
 { 
 	if(!$this->rules($obj))return;
	if( strlen($_POST[$this->last_obj]) != $length )
	{
		if(!$this->errorCustom())
		{
			if($this->formErrorLangue == 'fr')
				$this->msg_err[] = "Le champ '$this->last_obj' doit contenir '$length' caractères";
			else
				$this->msg_err[] = "Field '$this->last_obj' must contain '$length' characters";
		}
	}
 }
 
 function minLength($obj='',$length)
 {
	if(!$this->rules($obj))return;
	if(strlen($_POST[$this->last_obj]) < $length)
	{
		if(!$this->errorCustom())
		{
			if($this->formErrorLangue == 'fr')
				$this->msg_err[] = "Le champ '$this->last_obj' doit contenir au moins '$length' caractères";
			else
				$this->msg_err[] = "Field '$this->last_obj' must contain at least '$length' characters";
		}
	}
 }
 
 function maxLength($obj='',$length)
 {
	if(!$this->rules($obj))return;
	if(strlen($_POST[$this->last_obj]) > $length)
	{
		if(!$this->errorCustom())
		{
			if($this->formErrorLangue == 'fr')
				$this->msg_err[] = "Le champ '$this->last_obj' doit contenir au maximum '$length' caractères";
			else
				$this->msg_err[] = "Field '$this->last_obj' must contain '$length' characters maximum";
		}
	}
	
 }

 
function incJavascript()
 {
	 // ajout de la fonction javascript pour la redistribution des valeurs
	 $js_obj_name = '';
	 $js_obj_val = '';  
	 $js_fobj_name = '';
	 $js_fobj_val = ''; 	                                            
	
	 // recup des  clés/valeurs de _POST
	 foreach($_POST as $key => $val)
	 {
	 	// rajoute de virgule entre chaque champ du tableau
		if(!empty($js_obj_name))$js_obj_name .= ',';	 
		if(!empty($js_obj_val))$js_obj_val .= ',';
		
		$js_obj_name .= "'$key'";			
		
		$val = ereg_replace("(\r|\n){1,2}",'\n',$val);
		
		// Construit la chaine de déclaration du tableau en javascript en fonction d'un tableau ou non 
	 	if(!is_array($val))
		{
			$val = addslashes($val); #protection de la valeurs JS
			$js_obj_val .= "'$val'";			
		}
		// Construit un tableau dans le tableau
		else
		{
			$js_obj_val .= "[";
			for($i=0; $i < count($val) ;$i++)
			{
				$js_obj_val .= "'$val[$i]',";
			}
			$js_obj_val[strlen($js_obj_val)-1] = "]";
		}
	 }

	 // recuperation variable JS
	 $js_obj_err = '';
	 for($i=0; $i < count($this->objError); $i++)
	 {
		if(!empty($js_obj_err))$js_obj_err .= ',';
		$js_obj_err .= "'".$this->objError[$i]."'";
	 }   	 

	 $tmp_js = "
	 <script language='javascript'> 
	 
	 var tabFormObj = [$js_obj_name];
	 var tabFormVal = [$js_obj_val];
	 var tabFormObjErr = [$js_obj_err];
	
	 function formRedistrib()
	 {
		for(i=0; i < tabFormObj.length ;i++)
		{
			n = tabFormObj[i];
			obj = document.forms[0].elements[n];
			
			// Detection d'une objet du type select-multiple
			if( (typeof(obj) == 'undefined') &&
			    (typeof(document.forms[0].elements[tabFormObj[i] + '[]']) != 'undefined') &&
				  ((document.forms[0].elements[tabFormObj[i] + '[]'].type) == 'select-multiple') 
			  )	
			{
				// Reconstuction des selections de l'utilisateur
				n = n + '[]';
				obj = document.forms[0].elements[n];
				
				for(j=0; j < obj.length ;j++)
				{
					for(k=0; k < tabFormVal[i].length ;k++)
					{
						if(obj[j].value == tabFormVal[i][k])
							obj[j].selected = 'true';
					}
				}
			}
			
			// exception pour les radio et checkbox			
			if(!obj.name) obj = obj[0];	


			// text
			if(obj.type == 'text' || 
			   obj.type == 'hidden' ||
			   obj.type == 'textarea')
			 {
				obj.value = tabFormVal[i];
				
				for(j=0; j < tabFormObjErr.length ; j++)
					if(tabFormObj[i] == tabFormObjErr[j])
						obj.style.border = '1px solid red';
			 }
			
			// radio
			if(obj.type == 'radio')
			{
				obj = document.forms[0].elements[n];
				
				for(j=0; j < obj.length ; j++)
				{
					if(obj[j].value == tabFormVal[i])
					{
						obj[j].checked = true;
						break;
					}
				}
				
				// erreur ?
				for(j=0; j < tabFormObjErr.length ; j++)
					if(tabFormObj[i] == tabFormObjErr[j])
					{
						for(k=0; k <  obj.length; k++)
							obj[k].style.border = '1px solid red';
					}
				
			}
			

			// checkbox : parcours tous les element du chekbox et vérifie si sa valeur a été sauvegarder
			
			if(obj.type == 'checkbox')
			{
				obj = document.forms[0].elements[n];
				
				// vérifie s'il existe un ou plusier checkbox
				// en javascript un seul checkbox ne suffit pas pour créer un tableau
				if((!obj.length) && (tabFormVal[i].length))
				{
					obj.checked = true;
				}
				else
				{
				
  				for(j=0; j < obj.length ; j++)
  				{
  					for(k=0; k < tabFormVal[i].length ; k++)
  					{
  						if(obj[j].value == tabFormVal[i][k])
  							obj[j].checked = true;
  					}
  				}
  				for(j=0; j < tabFormObjErr.length ; j++)
  					if(tabFormObj[i] == tabFormObjErr[j])
  					{
  						for(k=0; k < obj.length; k++)
  							obj[k].style.border = '1px solid red';
  					}				
				}
				
				

			}		
			
			// select-one;
			if(obj.type == 'select-one')
			{
				for(j=0; j < obj.length ;j++)
					if(obj[j].value == tabFormVal[i])
						obj.selectedIndex = j;
			}
			
		}
	 }
	
	 window.onload=formRedistrib;
	 </script>
	 ";
	
	 // création du script $this->msg_err
	 $this->msg_err[] = $tmp_js;
 }
 
  
 function regexControl($pattern,$obj='',$mess)
 {
 	if(!$this->rules($obj))return;
 	if(!eregi($pattern,$_POST[$this->last_obj]))
	{
		$this->setMessage($mess);
		$this->errorCustom();
	}
 }
 
 function fileControl($obj,$required='',$taille='',$ext='')
 {
 	// Vérifie si l'obj est passé en parametre, par defaut ou manquant
 	if(!$this->rules($obj)) return;

  	// vérifie que lors de l'upload l'objet controler est bien du type upload
  	if($_FILES)
  	{
  		if(empty($_FILES[$this->last_obj]))
  			// Erreur les argument sont bien manquant
     			if($this->formErrorLangue == 'fr')
    				die("<b>Erreur</b> : La restriction <b>fileControl</b> peut pas controler l'objet <b>$obj</b> <br> ou alors l'objet <b>$obj</b> n'existe pas");
    			else
    				die("<b>Error</b> :  The restriction <b>fileControl</b> cannot control the object <b>$obj</b> or then the <b>$obj</b> object does not exist");		
   	
  	// Control de présence
   	if(!is_uploaded_file($_FILES[$this->last_obj]['tmp_name']))
  	{
  		if(!empty($required))
  		{
    		if(!$this->errorCustom())
    		{
    			if($this->formErrorLangue == 'fr')
    				$this->msg_err[] = "Le champ {$this->last_obj} doit être remplit";
    			else
    				$this->msg_err[] = "Field {$this->last_obj} cannot be empty";
    		}
  		}		
  		return;
  	}
  	
  	// Control de la taille maximum du fichier
  	if(!empty($taille) && ($_FILES[$this->last_obj]['size'] > $taille))
  	{
  		if(!$this->errorCustom())
  		{
  			if($this->formErrorLangue == 'fr')
  				$this->msg_err[] = "La taille du fichier {$_FILES[$this->last_obj]['name']} dépasse la taille maximum autorisée qui est de '$taille'";
  			else
  				$this->msg_err[] = "The {$_FILES[$this->last_obj]['name']} 's file size is over than the allowed's maximum size which is '$taille'";
  		}
  	}
  	
  	// Control du type de fichier
  	if(!empty($ext) && !strstr($_FILES[$this->last_obj]['type'], $ext))
  	{
  		if(!$this->errorCustom())
  		{
  			if($this->formErrorLangue == 'fr')
  				$this->msg_err[] = "L'extention du fichier {$_FILES[$this->last_obj]['name']} n'est pas autorisée, seule '$ext' l'est";
  			else
  				$this->msg_err[] = "The {$_FILES[$this->last_obj]['name']} 's file type is not allowed, only '$ext' is";
  		}
  	}
  }
 }
 
 
 
 // Cette methode permet de sélectionne la langue dans laquelle vont être afficher les erreurs
 function formSetLang($lang)
 {
  if(empty($lang) || ($lang != 'fr' && $lang != 'en'))
  {
  	// gestion d'erreur d'utilisation de la fonction
    if($this->formErrorLangue == 'fr')
    	die("Erreur : $lang n'est pas un argument invalide pour la fonction formSetLang");
    else
    	die("Error : $lang isn't a argument invalidates for the function formSetLang");		
  }
  else
  {
		if($lang == 'fr')
			$this->formErrorLangue = 'fr';
		if($lang == 'en')
			$this->formErrorLangue = 'en';	
  }
 }

 // Ajoute l'objet passé en parametre à l'ensemble des objet erreur
 function addError($obj='', $message='')
 {
 		if(!$this->rules($obj))return;
 		
 		if(!empty($message))
 			$this->msg_err[] = $message;
 		else
 		{
      if(!$this->errorCustom())
  		{
  			if($this->formErrorLangue == 'fr')
  				$this->msg_err[] = "Erreur sur '$this->last_obj'";
  			else
  				$this->msg_err[] = "Error on '$this->last_obj'";
  		}
		}	
 }
 
 // Controle la taille d'une image
 function imgStrictDimension($obj='',$w='',$h='')
 {
 		
 		// Vérifie si l'obj est passé en parametre, par defaut ou manquant
 		if(!$this->rules($obj)) return;

 		// Vérifie qu'il existe au moins une restriction
 		if((empty($w)) && (empty($h)) )
 		{
 			// Erreur les argument sont bien manquant
 			if($this->formErrorLangue == 'fr')
				die("Argument manquant sur la methode imgStrictDimension contolant l'objet $this->last_obj ");
			else
				die("Argument missing on the method imgStrictDimension controling the $this->last_obj object");
 		}
 		else // Au moins 1 argument existe
 		{
 			// vérifie que lors de l'upload l'objet controler est bien du type upload
 			if($_FILES)
			{
				if(!isset($_FILES[$this->last_obj]))
				{
  				// Erreur l'objet est mauvais
     			if($this->formErrorLangue == 'fr')
    				die("Erreur : La restriction imgStrictDimension peut pas controler l'objet $this->last_obj ou alors l'objet $this->last_obj n'existe pas");
    			else
    				die("Error :  The restriction imgStrictDimension cannot control the object $this->last_obj or then the $this->last_obj object does not exist");
    		}	
  		}			
  		
			// Si l'on upload une image et si l'image existe
 			if(($_FILES) && # si on upload
			   (eregi('^image/',$_FILES[$this->last_obj]['type'])) && # ,que le type est image
			   ($dimension = getimagesize($_FILES[$this->last_obj]['tmp_name'])) # et que l'image existe
			  )
 			{	
  	 		// test de largeur
  	 		if(!empty($w) && ($w != $dimension[0]))
  			{
  				if(!$this->errorCustom()) # l'on regard si l'erreur a déjà été référencé
      		{          				
      			if($this->formErrorLangue == 'fr') # on sélectionne la langue
      			{
      				if(($dimension[0] - $w) > 0) # calcul des différences
      					$relation = 'petite';
      				else
      					$relation = 'grande';
      				$this->msg_err[] = "La largeur de l'image {$_FILES[$this->last_obj]['name']} est trop $relation de ".abs($dimension[0] - $w)." pixel";
      			}
      			else
      			{
      				if(($dimension[0] - $w) > 0)
      					$relation = 'small';
      				else
      					$relation = 'big';
      				$this->msg_err[] = "The width of the image {$_FILES[$this->last_obj]['name']}  is too $relation by ".abs($dimension[0] - $w)." pixel";
        		}
      		}				 					
  			}
  			
  			// test de hauteur	
  			if(!empty($h) && ($h != $dimension[1]))
  			{
  				if(!$this->errorCustom())
      		{          				
      			if($this->formErrorLangue == 'fr')
      			{
      				if(($dimension[1] - $h) > 0)
      					$relation = 'petite';
      				else
      					$relation = 'grande';
      				$this->msg_err[] = "La hauteur de l'image {$_FILES[$this->last_obj]['name']} est trop $relation de ".abs($dimension[1] - $h)." pixel";
      			}
      			else
      			{
      				if(($dimension[1] - $h) > 0)
      					$relation = 'small';
      				else
      					$relation = 'big';
      				$this->msg_err[] = "The height of the image {$_FILES[$this->last_obj]['name']}  is too $relation by ".abs($dimension[1] - $h)." pixel";
        		}
      		}				 					
  			}
  			
 			}
 		}
 }
 
// fin de la class form
}
?>