<?php

/*********************************************************************************
           TPLN  by HAYOUN Laurent

           website: http://tpln.sourceforge.net
           email: php_work@hotmail.com
           License : LGPL
**********************************************************************************/

//Initialisation de l'include_path
$old_path = ini_get('include_path');
$os = ((stristr(getenv('SERVER_SOFTWARE'), 'win') || stristr(getenv('SERVER_SOFTWARE'), 'microsoft'))? ';' : ':');
$path = $os.ini_get('include_path');
ini_set('include_path', $path);

//Inclusion du fichier de configuration et de Db
require_once('TPLN_Cfg.php');
require_once(TPLN_DB_PEAR);   // inclusion de PEAR DB

/******************* Structure d'un fichier ***************************************


   f - f_no - name                 - string     // nom de fichier
            - buffer               - string     // contenu du fichier
            - items                - array      // contiens tous les items
            - php_items            - array      // contiens les items avec $
            - cmd_items            - array      // contiens tous les items includes
            - create_cached_file   - bool       // crée le fichier en cache ?
            - time_started         - long int   // début du chrono
            - cache_expire         - bool       // expiration du cache ?
            - execution_time       - long int   // temps d'execution
            - chrono_started       - long int   // chrono started

            Attention par reference pour les vérifications et performances

            - shortcut_blocs       - array      //
               |_ all              - array      // contient le nom de tous les blocs
               |_ used             - array      // contient les blocs appelés par l'utilsateur
               |_ name             - none       // contient le nom du bloc
                    |_ items       - array      // contient les items

            - def_blocs            - array      // contiens tous les blocs structurés
                                                 // definis par l'utisateur lors d'un appel'
                                                 // voir plus bas leurs structures
*/
/******************* Structure d'un bloc ***************************************

  def_blocs - name                  - string     // nom du bloc
              |_ structure          - string     // contenu du bloc
              |_ parsed             - array      // contiens les sessions de blocs
              |_ is_looped          - boolean    // loopé ?
              |_ children           - array      // contiens les bloc enfants

*************************************************************************************/



class TPLN
{
 private $TPLN_version 	= '2.0';
 private $def_tpl 		= array(); // liste des template défini avec un nom
 private $f_no 	        = -1; // indice de fichier
 private $vf_no 		= -1; // indice de fichier virtuel
 private $f 	        = array(); // c'est un array qui contient toutes les propriètés
 private $chrono_type 	= array(); // on veut le chrono de qui ALL ou pas ?

  // DataBase
 private $db_index 		= -1;
 private $db 		= array(); // objet contenant les informations de connection
 private $req		= array(); // stockage des resultats


  // Xtra
 private $cons_query 	= array(); // la requete dislokée est stockée ici
 private $NavColorFirst	= ''; // couleur pour la navigation
 private $NavColorSecond    = '';
 private $url		= ''; // contient les variables
 private $url_var		= ''; // contient les paramètres de l'url
 private $UrlRgxPatterns    = ''; // contient le regex pour formatter les urls
 private $UrlRgxReplace     = '';

// propriétaire
 private $NbRecordPerPage   = 0; // nombre de résultats voulues par pages
 private $NbResults		= 0; // les résultats
 private $Count		= 0;
 private $T_first		= 0; // celui uliser pour déterminer l'Id
 private $First		= 0;
 private $Last		= 0;
 private $PageNumber	= 0;
 private $PageCount		= 0;
 private $NavColor		= '';

 // Error
 private $error_msg	        = '';
 private $error_signaled    = array();

 // debugage
 private $struct_mode 	= 0;
 private $struct_tab	= array(); // contient le tableau entier
 
function loadPlugin($name)
{
 $name = strtolower($name);
 $tmp = TPLN_PATH."plugin/$name/$name.class.php";  
   
 include_once($tmp);
 
 // PHP's version // création dynmique d'objet
 $_php_ver = (float) PHP_VERSION;
 if (($_php_ver > 4.199999) && ($_php_ver < 5.0))
   	aggregate($this, $name);
 else
 {
 		DIE('version invalide');
 		// Création dynamique d'objet sans la fonction aggregate
 }
} 

function FileStructMode()
{
 $this->struct_mode     = 1;
}

function TraceMode()
{
 $this->trace_mode      = 1;
}

 /*****************  Parent function ******/
function _StructOpen()
{
 if($this->struct_mode == 0){return;}

  $this->_StructFile();
  $this->_StructItems();
  $this->_StructBlocs();
  $this->_Arr2HtmlTab();
}


function _StructFile()
{
  $this->struct_tab[] = "<b>File:</b> {$this->f[$this->f_no]['name']}<br>\n";
}


function _StructItems()
 {
 $p_var = array('_UrlBng',
                '_UrlPrev',
                '_UrlNext',
                '_UrlEnd',
                '_UrlPageNav',
                '_PageNumber',
                '_PageCount',
                '_First',
                '_Last',
                '_Count',
                '_NavColor',
                '_Chrono',
                '_Logo',
                '_Version',
                '_Field');

  $tab = '<b>Variable(s) found:</b> '.count($this->f[$this->f_no]['items'])."<br>\n";

 $cur_item = array(); // initiamisation
 $tpln_var = array(); // initialisation

   if(count($this->f[$this->f_no]['items']) > 0)
   {

     $item_tmp = array_unique($this->f[$this->f_no]['items']);
     foreach($item_tmp as $name)
       {
         if(!in_array($name, $p_var))
            {
              $cur_item[] = $name;
            }
          else
            {
              $tpln_var[] = $name;
           }
       }
   }

   $tab .= $this->_Arr2List('Php variable(s)',$this->f[$this->f_no]['php_items'],'{$','}');
   $tab .= $this->_Arr2List('User variable(s)',$cur_item,'{','}');
   $tab .= $this->_Arr2List('Private variable(s)',$tpln_var,'{','}');
   $tab .= $this->_Arr2List('Tpln Include Command(s)',$this->f[$this->f_no]['cmd_items'],'{#include(',')}');

   $this->struct_tab[] = $tab;

 }

function _StructBlocs()
 {
   $this->struct_tab[] = $this->_Arr2List('Blocs',$this->f[$this->f_no]['shortcut_blocs']['all']);
 }


/************ extra functions ************/
function _Arr2List($text='',$arr,$bng='',$end='')
 {
  $txt = NULL;

  if(!empty($text))
  {$txt .= "<b>$text:</b> ".count($arr).'<br>';}

  if(count($arr) > 0)
  {
   $txt .= '<ul>';

   foreach($arr as $name)
     {
      $txt .= '<li>'.$bng.$name.$end."</li>\n";

      if($text == 'Blocs')
       {
            if(count($this->f[$this->f_no]['shortcut_blocs'][$name]['items']) > 0)
               {
                 $txt .=  '<ul>';

                            foreach($this->f[$this->f_no]['shortcut_blocs'][$name]['items'] as $item)
                              {
                               $txt .= "<li>\{$item}</li>";
                              }

                 $txt .=  '</ul>';
                }

       }
     }

    $txt .= '</ul>';
  }

  return $txt;
}

function _Arr2HtmlTab()
{
  for($i=0;$i < count($this->struct_tab);$i++)
  {
   if($i == 0)
     {
      echo '<table width="100%" border="1" cellspacing="0" cellpadding="3">';
     }

    echo "<tr><td>{$this->struct_tab[$i]}</td></tr>";

   if($i == count($this->struct_tab)-1)
    {
     echo '</table>';
    }

  }

}


//   DataBase

function DbConnect($db_type='',$host='',$login='',$password='',$base='',$port='')
 {

   // initialisation des variable
   if(empty($db_type)){$db_type = TPLN_DB_TYPE_DEFAULT;}
   if(empty($host)){$host = TPLN_DB_HOST_DEFAULT;}
   if(empty($login)){$login = TPLN_DB_LOGIN_DEFAULT;}
   if(empty($password)){$password = TPLN_DB_PASSWORD_DEFAULT;}
   if(empty($base)){$base = TPLN_DB_BASE_DEFAULT;}
   if(empty($port)){$port = TPLN_DB_PORT;}

   // avec ou sans porc ;-) ?
   //$dsn = "mysql://$user:$pass@$host/$db_name";
   if(!empty($port))
    {
      $dsn = "$db_type://$login:$password@$host:$port/$base";
    }
    else
    {
      $dsn = "$db_type://$login:$password@$host/$base";
    }


   $this->db_index++;
   $this->db[$this->db_index] =  DB::connect($dsn, true);

   if (DB::isError($this->db[$this->db_index]))
    {
      $this->_DBError(0,$this->db[$this->db_index]->getMessage());
    }

 }


function ChangeConnection($db_index)
{
  if($db_index < 0 || $db_index >= count($this->db))
   {
     $this->_DBError('2.1',$db_index);
     return;
   }

  $this->db_index = $db_index;

}

function DbClose()
 {
 if(!is_object($this->db[$this->db_index]))
 {
   $this->_DBError(0.1);
   return;
 }

  $this->db[$this->db_index]->disconnect();
  if (DB::isError($this->db[$this->db_index]))
    {
      $this->_DBError(1,$this->db[$this->db_index]->getMessage());
    }
  }

function DoQuery($query)
 {
 if(!is_object($this->db[$this->db_index]))
 {
   $this->_DBError(0.1);
   return;
 }

   $this->req = $this->db[$this->db_index]->query($query);
   if (DB::isError($this->req))
    {
      // sans debuggage ?
      if(TPLN_SQL_QUERY_DEBUG == 0)
          $this->_DBError(2,$this->req->getMessage());
      else
          $this->_DBError(2,$this->req->getMessage()." <br><br>\n<pre><i><strong>".$query.'</strong></i></pre>');
    }
 }

function GetRowsCount()
 {
   if(!is_object($this->req))
    {
      $this->_DBError(2.2);
      return;
    }

   $rows_count = $this->req->numRows();
   return $rows_count;
 }


function DBNumRows()
 {
   if(!is_object($this->req))
    {
      $this->_DBError(2.2);
      return;
    }

  $rows_count = $this->GetRowsCount();
  return $rows_count;
 }


function DBFetchArray()
 {
   if(!is_object($this->req))
    {
      $this->_DBError(2.2);
      return;
    }

   $row = $this->req->fetchRow();
   return $row;
 }


function DBFetchAssoc()
 {
   if(!is_object($this->req))
    {
      $this->_DBError(2.2);
      return;
    }

   $row = $this->req->fetchRow(DB_FETCHMODE_ASSOC);
   return $row;
 }


function DBFreeResult()
 {
  if(!is_object($this->req))
    {
      $this->_DBError(2.2);
      return;
    }

   $this->req->free();
 }


 // prise des résulats dans un array
function GetData($type='ASSOC')
 {
   $i = 0;

   if($type == 'ASSOC')
    {
      while($row = $this->DBFetchAssoc())
          {
           $results[$i++] = $row;
          }
    }

   if($type == 'ARRAY')
    {
      while($row = $this->DBFetchArray())
          {
           $results[$i++] = $row;
          }

    }

      return $results;
 }


function GetOne()
{
  $res = $this->GetData('ARRAY');
  return $res[0][0];

}


function GetNumFields()
{
   if(!is_object($this->req))
    {
      $this->_DBError(2.2);
      return;
    }

   $fields_count = $this->req->numCols();
   return $fields_count;
}

function GetFields($query)
{
 $fields = array();

 // limit ?
 if(!preg_match("/LIMIT(.*)/mi",$query,$match))
 {
    $query .= ' LIMIT 1';
 }


 $this->DoQuery($query);
 $row = $this->DBFetchAssoc();

 if(is_array($row))
  $fields = array_keys($row);

 return $fields;
}



function GetFieldName($field_no)
{
 if(!is_object($this->req))
    {
      $this->_DBError(2.2);
      return;
    }

  if($field_no < 0 || $field_no >= $this->GetNumFields())
   {
     $this->_DBError(2.3,$field_no);
     return;
   }

 // on prend la première ligne
 $row = $this->DBFetchAssoc();
 $col = array_keys($row);

 return $col[$field_no];

}


function GetDBList()
{
 if(!is_object($this->db[$this->db_index]))
 {
   $this->_DBError(0.1);
   return;
 }

 $db_list = $this->db[$this->db_index]->getListOf('databases');
 return $db_list;
}



function GetTableList()
{
 if(!is_object($this->db[$this->db_index]))
 {
   $this->_DBError(0.1);
   return;
 }

 $this->DoQuery('SHOW tables');

 while($row = $this->DbFetchArray())
 {
   $res[] = $row[0];
 }

 return $res;

}

// DataBase_Xtra

 // constuction de la requete
function _SetQuery($query)
 {

  // encodage de la requete
 $query = str_replace("\n",' ',$query);
 $query = str_replace("\r",' ',$query);


  $this->cons_query['STRING'] = $query;
  // on parcours la requete à l'envers ;-)'
  // limit ?
  if(preg_match("/LIMIT(.*)/mi",$query,$match))
   {
    $this->cons_query['LIMIT'] = trim($match[1]);
    $query = str_replace($match[0],'',$query); // on remplace ds la chaine
   }

  // order by
  if(preg_match("/ORDER BY(.*)/mi",$query,$match))
   {
    $this->cons_query['ORDER BY'] = trim($match[1]);
    $query = str_replace($match[0],'',$query); // on remplace ds la chaine
   }

  // where
  if(preg_match("/WHERE(.*)/mi",$query,$match))
   {
    $this->cons_query['WHERE'] = trim($match[1]);
    $query = str_replace($match[0],'',$query); // on remplace ds la chaine
   }

   // from
  if(!preg_match("/FROM(.*)/mi",$query,$match))
   {$this->_DBError(4);}

    $this->cons_query['FROM'] = trim($match[1]);
    $query = str_replace($match[0],'',$query); // on remplace ds la chaine


   // select
  if(!preg_match("/SELECT(.*)/mi",$query,$match))
   {$this->_DBError(3);}

    $this->cons_query['SELECT'] = trim($match[1]);
    $query = str_replace($match[0],'',$query); // on remplace ds la chaine
 }

// recréé une requete avec les nouveau paramètres de LIMIT ;-)
function _SetQueryLimit()
 {
  if(empty($this->PageNumber)){$this->PageNumber = 1;}


 // calcul du nombre de pages
 if($this->NbRecordPerPage == 0)
 {
    $this->PageCount = 1;
    $this->PageNumber = 1;
    $this->First = 1;
    $this->T_first = $this->First;
    $this->Last = $this->Count;
 }
 else
 {
    $this->PageCount = ceil($this->Count / $this->NbRecordPerPage); // arrondi a l'entier superieur
    if($this->PageNumber > $this->PageCount){$this->PageNumber = 1;} // erreur


     // on determine debut du limit
     $this->First = ($this->PageNumber - 1) * $this->NbRecordPerPage;
     $this->T_first = $this->First ;
     $this->Last = $this->First + $this->NbRecordPerPage;
 }


  // on reconstruit la requete
 $query = 'SELECT ';
 $query .= $this->cons_query['SELECT'];
 $query .= ' FROM ';
 $query .= $this->cons_query['FROM'];

 // Where
 if(!empty($this->cons_query['WHERE']))
   {$query .= ' WHERE ';
    $query .= $this->cons_query['WHERE'];}

 // Order By
 if(!empty($this->cons_query['ORDER BY']))
   {$query .= ' ORDER BY ';
    $query .= $this->cons_query['ORDER BY'];}

 // limit ?
 if($this->NbRecordPerPage > 0)
 {
     $query .= " LIMIT  $this->First,$this->NbRecordPerPage";

      //if($this->First == 0){$this->First = 1;}
      $this->First++;
      if($this->Last > $this->Count){$this->Last = $this->Count;}
 }

 return $query;
}

// retourne la bonne url formattée
function _getUrl($t_pg='')
 {
   $url = $this->Url."tpg=$t_pg".$this->url_var;

   
   if(!empty($this->UrlRgxPatterns) && !empty($this->UrlRgxReplace))
       $url = @preg_replace('/'.$this->UrlRgxPatterns.'/i',$this->UrlRgxReplace,$url);

   return $url;
 }


function RewriteUrl($patterns,$replace)
{
   $this->UrlRgxPatterns = $patterns;
   $this->UrlRgxReplace = $replace;
}


function SetNavColor($color1,$color2)
 {
   $this->NavColorFirst = $color1;
   $this->NavColorSecond = $color2;
 }

 // prise totale des résulats
function _GetTotalCount()
 {
   $query = 'SELECT COUNT(*) ';
   $query .= 'FROM ';
   $query .= $this->cons_query['FROM'];

   if(!empty($this->cons_query['WHERE']))
     {
      $query .= ' WHERE ';
      $query .= $this->cons_query['WHERE'];
     }

   $this->DoQuery($query);
   $this->Count = $this->GetOne();
   //$this->Count = $this->db[$this->db_index]->getOne($query);
 }


// redefini les variables propriaitaire
function _ApplyPrivateVar()
 {
   if(empty($this->Url))
    {
      $this->Url = basename($_SERVER['PHP_SELF']).'?';
    }

   // Variable TPLN
   if($this->ItemExists('_Count','data'))
      $this->Parse('data._Count',$this->Count);

   if($this->ItemExists('_First','data'))
      $this->Parse('data._First',$this->First);

   if($this->ItemExists('_Last','data'))
      $this->Parse('data._Last',$this->Last);

   if($this->ItemExists('_PageCount','data'))
      $this->Parse('data._PageCount',$this->PageCount);

   if($this->ItemExists('_PageNumber','data'))
      $this->Parse('data._PageNumber',$this->PageNumber);

   // PREVIOUS
   if($this->BlocExists('previous'))
   {
     if(!$this->ItemExists('_Url','previous'))
     {
         $this->_Error('1.1',$this->f[$this->f_no]['name'],'previous','_Url');
         return;
     }

     if($this->PageNumber <= $this->PageCount && $this->PageCount != 1 && $this->PageNumber != 1)
     {
        $prev_pg = $this->PageNumber - 1;
        $url = $this->_getUrl($prev_pg);
        $this->Parse('data.previous._Url',$url);
     }
     else
        $this->EraseBloc('data.previous');
   }

   // NEXT
   if($this->BlocExists('next'))
   {
     if(!$this->ItemExists('_Url','next'))
     {
         $this->_Error('1.1',$this->f[$this->f_no]['name'],'next','_Url');
         return;
     }

     if($this->PageNumber < $this->PageCount && $this->PageCount != 1)
     {
        $next_pg = $this->PageNumber + 1;
        $url = $this->_getUrl($next_pg);
        $this->Parse('data.next._Url',$url);
     }
     else
        $this->EraseBloc('data.next');
   }

   // START
   if($this->BlocExists('start'))
   {
     if(!$this->ItemExists('_Url','start'))
     {
         $this->_Error('1.1',$this->f[$this->f_no]['name'],'start','_Url');
         return;
     }

     if($this->PageCount > 1)
     {
        $url = $this->_getUrl(1);
        $this->Parse('data.start._Url',$url);
     }
     else
        $this->EraseBloc('data.start');
   }

   // END
   if($this->BlocExists('end'))
   {
     if(!$this->ItemExists('_Url','end'))
     {
         $this->_Error('1.1',$this->f[$this->f_no]['name'],'end','_Url');
         return;
     }

     if($this->PageCount > 1)
     {
        $url = $this->_getUrl($this->PageCount);
        $this->Parse('data.end._Url',$url);
     }
     else
        $this->EraseBloc('data.end');
   }

   // PAGER
   if($this->BlocExists('pager'))
   {
     // bloc out & in
     if(!$this->BlocExists('out'))
     {
       $this->_Error(2,$this->f[$this->f_no]['name'],'out');
       return;
     }
     if(!$this->ItemExists('_Url','out'))
     {
         $this->_Error('1.1',$this->f[$this->f_no]['name'],'out','_Url');
         return;
     }
     if(!$this->ItemExists('_Page','out'))
     {
         $this->_Error('1.1',$this->f[$this->f_no]['name'],'out','_Page');
         return;
     }

     if(!$this->BlocExists('in'))
     {
       $this->_Error(2,$this->f[$this->f_no]['name'],'in');
       return;
     }

     if(!$this->ItemExists('_Page','in'))
     {
         $this->_Error('1.1',$this->f[$this->f_no]['name'],'in','_Page');
         return;
     }

     if($this->PageCount > 1)
     {
       $in = $this->GetBlocInFile('data.pager.in');
       $out = $this->GetBlocInFile('data.pager.out');

       //$this->EraseBloc("in");
       //$this->EraseBloc("out");

       $str = '';

       for($l=1; $l <= $this->PageCount; $l++)
       {
         if($l == $this->PageNumber)
         {
           $str .= str_replace('{_Page}',$l,$in);
         }
         else
         {
           $url = $this->_getUrl($l);

           $tmp = str_replace('{_Page}',$l,$out);
           $tmp = str_replace('{_Url}',$url,$tmp);

           $str .= $tmp;
         }
       }

       $this->ParseBloc('data.pager',$str);
     }
     else
     {
        $this->EraseBloc('data.pager');
     }
   }

    // initialise la navigation
    if(empty($this->NavColorFirst)){$this->NavColorFirst = TPLN_DB_NavColorFirst;}
    if(empty($this->NavColorSecond)){$this->NavColorSecond = TPLN_DB_NavColorSecond;}

 }

function SetUrl($url)
 {
   $this->Url = $url;
 }


function UrlAddVar($url_add)
 {
  $this->url_var = "&$url_add";
 }


function CreateFieldVars($path,$fields)
{
  // chemin défini en array
  if(is_array($path))
  {
     for($i=0;$i<count($path);$i++)
     {
          $this->CreateFieldVars($path[$i],$fields);
     }
     return;
  }

  $arr = @explode('.',$path);

  if(count($arr) == 1)
  {
     $this->_Error('13',$this->f[$this->f_no]['name'],$arr[0]);
     return;
  }

  $lastbloc = $arr[count($arr)-1];

  // Verification du mot clefs _Field
  if(!$this->ItemExists('_Field',$lastbloc))
  {
     $this->_Error('1.1',$this->f[$this->f_no]['name'],$lastbloc,'_Field');
     return;
  }

  $cur_bloc_ini = $this->GetBlocInFile($lastbloc);
  $str = '';
  $all = '';
  $tab = array();


  for($i=0;$i < count($fields);$i++)
  {
      // on remplace le mot clef field
      $str =  str_replace('{_Field}','{'.$fields[$i].'}',$cur_bloc_ini);
      $all .= $str;

  }

  // on remplace le contenu du champs
  //$this->ParseBloc($path, $all);
  $this->ParseBloc($lastbloc, $all);

  // on retire le dernier du path
  // on veut que le chemin des peres
  $path = $this->_GetFathers($path,'ARRAY',0);
  $path = array_slice ($path, 0, count($path)-1);
  $path = join($path,'.');

  $this->ReloadBlocVars($path);

}




function ShowRecords($query,$nb_result_per_page=0)
 {
   // vérification du second paramètre
   if(!is_int($nb_result_per_page))
   {
       $this->_DBError(5);
       return;
   }
   if($nb_result_per_page <  0)
   {
      $this->_DBError('5.1');
      return;
   }

   $this->NbRecordPerPage = $nb_result_per_page;


   if($this->NbRecordPerPage != 0 && isset($_GET['tpg']))
   {
    $this->PageNumber = $_GET['tpg']; // variable passée en parametre
   }
   else
   {
    $this->PageNumber = NULL;
   }

   $this->_SetQuery($query); // reconstruit la requete
   $this->_GetTotalCount(); //  nb totl d'enregistrements

   // pas du tout d'enregistrement
   if($this->Count == 0)
    {
     $no_records = $this->GetBlocInFile('data.norecord');
     $this->EraseBloc('data.norecord');
     $this->ParseBloc('data',$no_records);
     return;
    }

   $new_query = $this->_SetQueryLimit(); // requete contenant les limites
   $this->DoQuery($new_query); // execute la requete
   //$results = $this->GetData(); // recupere les résultats
   $this->NbResults = $this->DbNumRows(); // resultats obtenues != $this->count


   if($this->NbResults == 0)// il y'en a pas pour une recherche
    {
     // a redefinir pour la recherche
     $no_records = $this->GetBlocInFile('data.norecord');
     $this->EraseBloc('data.norecord');
     $this->ParseBloc('data',$no_records);
     return;
    }
   else // si il y a des résultats alors
    {
      $this->EraseBloc('data.norecord'); // on efface norecord
      $this->_ApplyPrivateVar(); // on modifie les items propriaitaire $Count...
      if(count($this->f[$this->f_no]['shortcut_blocs']['loop']['items']) == 0){ return;}

       // parsing
       $i = 0; // pour la _NavColor
       $T_id = $this->T_first;  // pour _Id
       while($row = $this->DbFetchAssoc())
        {
          $keys = @array_keys($row);// prise du mon des clefs

          // echo "{$row['User']} {$row['Password']}<br>";
          foreach($keys as $key)
           {
                // ajout de la nav color
                if($this->ItemExists('_NavColor','loop'))
                {
                  $color = ($i % 2) ? $this->NavColorFirst  : $this->NavColorSecond;
                  $this->Parse('data.loop._NavColor',$color);
                }

                // ajout de l'Id
                if($this->ItemExists('_Id','loop'))
                  $this->Parse('data.loop._Id',$T_id);

                if($this->ItemExists($key,'loop'))
                   $this->Parse("data.loop.$key",$row[$key]);
           }
           $i++;
           $T_id++;
           $this->Loop('data.loop');
        }
  }


 }


 // Gestiond des erreurs
function _Error($err_no,$file='',$bloc='',$item='')
 {
   $file_color = '#FF0000';
   $item_color = '#0000FF';
   $bloc_color = '#008000';

  switch($err_no)
    {
         // 0 pas de fichier trouvé
         case 0:
             if(TPLN_ERROR_LANG == 'en'){$err_msg = "File <font color=\"$file_color\"><b>$file</b></font> not found";}
             if(TPLN_ERROR_LANG == 'fr'){$err_msg = "Fichier <font color=\"$file_color\"><b>$file</b></font> non trouvé";}
         break;

         // 1 item non trouvé
         case 1 :
             if(TPLN_ERROR_LANG == 'en'){$err_msg = "Item <font color=\"$item_color\">\{$item}</font> not found in file <font color=\"$file_color\"><b>$file</b></font>";}
             if(TPLN_ERROR_LANG == 'fr'){$err_msg = "Item <font color=\"$item_color\">\{$item}</font> non trouvé dans le fichier <font color=\"$file_color\"><b>$file</b></font>";}
         break;

         // 1 item non trouvé dans le bloc
         case 1.1 :
             if(TPLN_ERROR_LANG == 'en'){$err_msg = "Item <font color=\"$item_color\">\{$item}</font> not found in bloc <font color=\"$bloc_color\">&lt;bloc::$bloc&gt;</font> of file <font color=\"$file_color\"><b>$file</b></font>";}
             if(TPLN_ERROR_LANG == 'fr'){$err_msg = "Item <font color=\"$item_color\">\{$item}</font> non trouvé dans le bloc <font color=\"$bloc_color\">&lt;bloc::$bloc&gt;</font> du fichier <font color=\"$file_color\"><b>$file</b></font>";}
         break;

         // 2 bloc inexistant
         case 2:
             if(TPLN_ERROR_LANG == 'en'){$err_msg = "Bloc <font color=\"$bloc_color\">&lt;bloc::$bloc&gt;</font> not found in file <font color=\"$file_color\"><b>$file</b></font>";}
             if(TPLN_ERROR_LANG == 'fr'){$err_msg = "Bloc <font color=\"$bloc_color\">&lt;bloc::$bloc&gt;</font> non trouvé dans le fichier <font color=\"$file_color\"><b>$file</b></font>";}
         break;

         // 2.1 bloc double
         case 2.1:
             if(TPLN_ERROR_LANG == 'en'){$err_msg = "Bloc <font color=\"$bloc_color\">&lt;bloc::$bloc&gt;</font>is in double in file <font color=\"$file_color\"><b>$file</b></font>";}
             if(TPLN_ERROR_LANG == 'fr'){$err_msg = "Bloc <font color=\"$bloc_color\">&lt;bloc::$bloc&gt;</font>est en double dans le fichier <font color=\"$file_color\"><b>$file</b></font>";}
         break;

        // 3 bloc inexistant dans un bloc
         case 3:
             if(TPLN_ERROR_LANG == 'en'){$err_msg = "Bloc <font color=\"$bloc_color\">&lt;bloc::$bloc&gt;</font> not found in Bloc <font color=\"$item_color\">\{$item}</font> in file <font color=\"$file_color\"><b>$file</b></font>";}
             if(TPLN_ERROR_LANG == 'fr'){$err_msg = "Bloc <font color=\"$bloc_color\">&lt;bloc::$bloc&gt;</font> non trouvé dans le bloc <font color=\"$item_color\">\{$item}</font> du fichier <font color=\"$file_color\"><b>$file</b></font>";}
         break;

        // 4 chemin du bloc error
        case 4:
             if(TPLN_ERROR_LANG == 'en'){$err_msg = "Bloc path <font color=\"$item_color\">$bloc</font> in file <font color=\"$file_color\"><b>$file</b></font>";}
             if(TPLN_ERROR_LANG == 'fr'){$err_msg = "Chemin du bloc <font color=\"$item_color\">$bloc</font> dans le fichier <font color=\"$file_color\"><b>$file</b></font>";}
         break;

        // 4.1 chemin du bloc error in a loop bloc
        case 4.1:
             if(TPLN_ERROR_LANG == 'en'){$err_msg = "Bloc path <font color=\"$item_color\">$bloc</font> in Loop function of file <font color=\"$file_color\"><b>$file</b></font>";}
             if(TPLN_ERROR_LANG == 'fr'){$err_msg = "Chemin du bloc <font color=\"$item_color\">$bloc</font> dans la fonction Loop du fichier <font color=\"$file_color\"><b>$file</b></font>";}
         break;

        // 4.2 chemin du bloc error in looped bloc
        case 4.2:
             if(TPLN_ERROR_LANG == 'en'){$err_msg = "Bloc path <font color=\"$item_color\">$bloc</font> is already looped in file <font color=\"$file_color\"><b>$file</b></font>";}
             if(TPLN_ERROR_LANG == 'fr'){$err_msg = "Chemin du bloc <font color=\"$item_color\">$bloc</font> est déjà défini comme Loop dans le fichier <font color=\"$file_color\"><b>$file</b></font>";}
         break;

        // 5 define a bloc
        case 5:
             if(TPLN_ERROR_LANG == 'en'){$err_msg = "Can't define an empty bloc";}
             if(TPLN_ERROR_LANG == 'fr'){$err_msg = 'Ne peux définir un bloc vide';}
         break;

        // 6 define an item
        case 6:
             if(TPLN_ERROR_LANG == 'en'){$err_msg = "Can't define an empty item";}
             if(TPLN_ERROR_LANG == 'fr'){$err_msg = 'Ne peux définir un item vide';}
         break;

         // 7 impossible cache directory creation
         case 7:
             if(TPLN_ERROR_LANG == 'en'){$err_msg = "Can't create cache dir ".TPLN_CACHE_DIR;}
             if(TPLN_ERROR_LANG == 'fr'){$err_msg = 'Ne peux créer le répertoire de cache '.TPLN_CACHE_DIR;}
         break;

         // 7.1 impossible cache directory creation
         case 7.1:
             if(TPLN_ERROR_LANG == 'en'){$err_msg = "Can't create dir ".$file;}
             if(TPLN_ERROR_LANG == 'fr'){$err_msg = 'Ne peux créer le répertoire de cache '.$file;}
         break;

         // 7.2 impossible de trouver le repertoire par défaut
         case 7.2:
             if(TPLN_ERROR_LANG == 'en'){$err_msg = "Can't create default templates directory ".TPLN_DEFAULT_PATH;}
             if(TPLN_ERROR_LANG == 'fr'){$err_msg = 'Ne peux créer le répertoire de templates par défaut '.TPLN_DEFAULT_PATH;}
         break;

         // 8 impossible de trouver la fin du bloc
         case 8:
             if(TPLN_ERROR_LANG == 'en'){$err_msg = "Can't find end bloc <font color=\"$bloc_color\">$bloc</font> in file <font color=\"$file_color\"><b>$file</b></font>";}
             if(TPLN_ERROR_LANG == 'fr'){$err_msg = "Ne peux trouver la fin du bloc  <font color=\"$bloc_color\">$bloc</font> dans le fichier <font color=\"$file_color\"><b>$file</b></font>";}
         break;

         // 9 le template n'existe pas
         case 9:
             if(TPLN_ERROR_LANG == 'en'){$err_msg = "Template number $file is not an integer";}
             if(TPLN_ERROR_LANG == 'fr'){$err_msg = "Template $file n'est pas un intégral";}
         break;

         // 10 le template n'existe pas
         case 10:
             if(TPLN_ERROR_LANG == 'en'){$err_msg = "Template number $file doesn't exist";}
             if(TPLN_ERROR_LANG == 'fr'){$err_msg = "Template $file n'existe pas";}
         break;

         // 11 le nom du template n'existe pas
         case 11:
             if(TPLN_ERROR_LANG == 'en'){$err_msg = "Template name $file doesn't defined";}
             if(TPLN_ERROR_LANG == 'fr'){$err_msg = "Template $file n'est pas défini";}
         break;


         // 12 DefineTemplate
         case 12:
             if(TPLN_ERROR_LANG == 'en'){$err_msg = 'DefineTemplate() must have an array in parameter';}
             if(TPLN_ERROR_LANG == 'fr'){$err_msg = 'La fonction DefineTemplate() doit avoir un array en parametre';}
         break;

         // 13 CreateFieldVars
         case 13:
             if(TPLN_ERROR_LANG == 'en'){$err_msg = "Your bloc path must have a father <font color=\"$bloc_color\">$bloc</font> in file <font color=\"$file_color\"><b>$file</b></font>";}
             if(TPLN_ERROR_LANG == 'fr'){$err_msg = "Votre chemin de bloc doit avoir un pere  <font color=\"$bloc_color\">$bloc</font> dans le fichier <font color=\"$file_color\"><b>$file</b></font>";}
         break;


    }

    $this->error_msg = "<B>TPLN Error $err_no:</B> $err_msg";
    $this->_OutPutMessage();
 }

function _DBError($err_no,$msg='')
 {
   // 0 problem de connexion
   switch($err_no)
    {
        case 0:
             if(TPLN_ERROR_LANG == 'en'){$err_msg = "Database connection problem ($msg)";}
             if(TPLN_ERROR_LANG == 'fr'){$err_msg = "Problème de connexion à la base ($msg)";}
         break;

        // object $db non existant
        case 0.1:
             if(TPLN_ERROR_LANG == 'en'){$err_msg = 'No connection found';}
             if(TPLN_ERROR_LANG == 'fr'){$err_msg = 'Pas de connection trouvé';}
         break;

        // 1 problem de connexion
        case 1:
             if(TPLN_ERROR_LANG == 'en'){$err_msg = "Database close problem ($msg)";}
             if(TPLN_ERROR_LANG == 'fr'){$err_msg = "Problème de fermeture à la base ($msg)";}
        break;

        // 2 problem MySQL !
        case 2:
             if(TPLN_ERROR_LANG == 'en'){$err_msg = "Query problem ($msg)";}
             if(TPLN_ERROR_LANG == 'fr'){$err_msg = "Probleme de requete ($msg)";}
        break;

        // 2.1 problem avec le changement de dommain
        case 2.1:
             if(TPLN_ERROR_LANG == 'en'){$err_msg = "Change connection index ($msg)";}
             if(TPLN_ERROR_LANG == 'fr'){$err_msg = "Index du changement de connection ($msg)";}
        break;

        // objet requête
        case 2.2:
             if(TPLN_ERROR_LANG == 'en'){$err_msg = 'No query found';}
             if(TPLN_ERROR_LANG == 'fr'){$err_msg = 'Pas de requête trouvée';}
        break;

        // field index number
        case 2.3:
             if(TPLN_ERROR_LANG == 'en'){$err_msg = 'Colonne number,not valide';}
             if(TPLN_ERROR_LANG == 'fr'){$err_msg = 'Colonne de résultats non valide';}
        break;

        // ShowRecords
        // problem dans la construction de la requete
        case 3:
             if(TPLN_ERROR_LANG == 'en'){$err_msg = 'SELECT not found in your query';}
             if(TPLN_ERROR_LANG == 'fr'){$err_msg = 'SELECT non trouvé dans votre requête';}
        break;

        case 4:
             if(TPLN_ERROR_LANG == 'en'){$err_msg = 'FROM not found in your query';}
             if(TPLN_ERROR_LANG == 'fr'){$err_msg = 'FROM non trouvé dans votre requête';}
        break;

        // problem avec le nombre de resultats voulus
        // pas un entier
         case 5:
             if(TPLN_ERROR_LANG == 'en'){$err_msg = 'Showrecords() must have an integer in second parameter';}
             if(TPLN_ERROR_LANG == 'fr'){$err_msg = 'Showrecords() doit avoir un entier en second paramètre';}
        break;
        case 5.1:
             if(TPLN_ERROR_LANG == 'en'){$err_msg = 'Showrecords() must have integer greater than zero in second parameter';}
             if(TPLN_ERROR_LANG == 'fr'){$err_msg = 'Showrecords() doit avoir un entier supérieur à zero en second paramètre';}
        break;

    }

    $this->error_msg = "<B>TPLN DB Error $err_no:</B> $err_msg";
    $this->_OutPutMessage();
 }

// ben alert par email de l'admin
function _MailAlert()
 {

  $err_alert = TPLN_ERROR_ALERT;
  $mail_admin = TPLN_MAIL_ADMIN;

   if(($err_alert == 1) && (!empty($mail_admin)) && isset($_GET['tpln_w']) && $_GET['tpln_w'] != 'adm')
   {
    $request_url_simple = str_replace('?'.$_SERVER['QUERY_STRING'],'',$_SERVER['REQUEST_URI']);
    $url = 'http://'.$_SERVER['HTTP_HOST'].$request_url_simple;
    
    // possede une query string ?
    if(empty($_SERVER['QUERY_STRING']))
    	$url .= '?tpln_w=adm';
    else
    	$url .= $_SERVER['QUERY_STRING'].'&tpln_w=adm';
    

    $subject = '[TPLN] Alert Error';

		$err_msg = strip_tags($this->error_msg);
		$err_msg = str_replace('&lt;','<',$err_msg);
		$err_msg = str_replace('&gt;','>',$err_msg);

    $body = date('[Y-m-d H:i] ')." TPLN has detected an error\n\n";
    $body .= $err_msg.' in '.$_SERVER['SCRIPT_FILENAME']."\n\n\n";
    $body .= "Url $url\n";
    $body .= "===========================================\n";
    $body .= 'TPLN version '.$this->TPLN_version."\n";
    $body .= 'http://tpln.sourceforge.net';

    $from = 'From: TPLN<'.TPLN_MAIL_EXPEDITOR.">\n";
    $from .= 'X-Mailer: PHP/'. phpversion();


        if(@mail(TPLN_MAIL_ADMIN,$subject,$body,$from))
         {
           if(TPLN_ERROR_LANG == 'en'){$msg = "<br><br><hr>An email has been sent to the webmaster <a href=\"mailto:$mail_admin\">$mail_admin</a>";}
           if(TPLN_ERROR_LANG == 'fr'){$msg = "<br><br><hr>Un Email a été envoyé au webmaster <a href=\"mailto:$mail_admin\">$mail_admin</a>";}

           $this->error_msg .= $msg;
         }

   }

 }

// pour la sortie du message
function _OutPutMessage()
 {
   $this->_MailAlert();

   if(!@in_array($this->error_msg,$this->error_signaled))
    {
     echo $this->error_msg;
     echo "<br/>\n";
     $this->error_signaled[] = $this->error_msg;
    }

   // logs des erreurs
   if(TPLN_ERROR_LOGS == 1)
   {
      // on regarde si le fichier existe sinon on le crée
      if(!($fp = @fopen(TPLN_ERROR_LOGS_FILE ,'a+')))
      {
         echo 'Impossible to open/create '.TPLN_ERROR_LOGS_FILE;
      }
      else
      {
         // jour heure,msg
         $txt = date('d-m-j H:i,').$this->error_msg."\n";
         fwrite($fp, $txt);
         fclose($fp);
      }



   }

   if($this->struct_mode == 0)
    {
      exit();
    }
 }




// fonction constructrice de classe
function TPLN()
{
  // regarde si le repertoire par défaut existe
  if(TPLN_DEFAULT_IND == 1)
   {
      if(!is_dir(TPLN_DEFAULT_PATH))
          {

            if(!@mkdir(TPLN_DEFAULT_PATH,0755))
             {
              $this->_Error(7.2);
              return;
             }

           clearstatcache();
          }
   }

}




/********************************************
              General
*********************************************/


function Open($file='',$cached='',$cached_time='')
 {


  $this->f_no++;  // incremante

/** initialisation des types de variables pour EALL ***************************/
  $this->f[$this->f_no]['name'] = NULL;
  $this->f[$this->f_no]['buffer'] = NULL;
  $this->f[$this->f_no]['items'] = array();
  $this->f[$this->f_no]['php_items'] = array();
  $this->f[$this->f_no]['cmd_items'] = array();

  $this->f[$this->f_no]['create_cached_file'] = 0;
  $this->f[$this->f_no]['time_started'] = 0;
  $this->f[$this->f_no]['cache_expire'] = 0;
  $this->f[$this->f_no]['execution_time'] = 0;
  $this->f[$this->f_no]['chrono_started'] = 0;

  $this->f[$this->f_no]['shortcut_blocs'] = array();
  $this->f[$this->f_no]['shortcut_blocs']['all'] = array();
  $this->f[$this->f_no]['shortcut_blocs']['used'] = array();

  $this->f[$this->f_no]['shortcut_blocs']['name'] = NULL;
  $this->f[$this->f_no]['shortcut_blocs']['name']['items'] = array();

  $this->f[$this->f_no]['def_blocs'] = array();

  $this->chrono_type[$this->f_no] = NULL;
/******************************************************************************/

  // ajout du nom de fichiers
  if(empty($file))
  {
     $file = basename($_SERVER['PHP_SELF']);
     // on prend l'extension et on l'efface
     preg_match("/\.([^\.]*$)/", $file, $elts);

     if(count($elts) > 1)
       $file = str_replace($elts[count($elts)-1],TPLN_DEFAULT_EXT,$file);
  }


  // verifie l'existence de l'extension et du répertoire par défault
  if(TPLN_DEFAULT_IND == 1)
  {
     if(!preg_match("/\./",basename($file)))
       {$file .= '.'.TPLN_DEFAULT_EXT;}// met l'extension par defaut s'il ni en a pas

     $file = TPLN_DEFAULT_PATH.$file;// met le repertoire par defaut
  }

  $this->f[$this->f_no]['name'] = $file;

  $time = explode(' ',microtime());
  $this->f[$this->f_no]['time_started'] = time(); // dans pour le cache à la seconde
  $this->f[$this->f_no]['chrono_started'] = $time[1] + $time[0];

  // on définit le chrono
  if(!defined('TPLN_CHRONO_STARTED'))
   {
    $chrono = $time[1] + $time[0];
    define('TPLN_CHRONO_STARTED',$chrono);
   }

   // on regarde si le fichier est en cache
   if(!empty($cached))
    {
      $this->_CacheDirExists();

      if(empty($cached_time)){$cached_time = TPLN_CACHE_TIME;}
      $this->f[$this->f_no]['cache_expire'] = $this->f[$this->f_no]['time_started']+$cached_time; // cache d'expiration

      // on regarde le fichier est encore en cache
      // fichier existe && sa date de creation est <= au temps de cache
      if(file_exists(TPLN_CACHE_DIR.$this->f[$this->f_no]['name']) && $this->_InCachePeriod())
       {
        $this->_GetCachedFile();
        return true;
       }
      else
       {
        $this->f[$this->f_no]['create_cached_file'] = 1;

       }
    }

     $this->f[$this->f_no]['buffer'] = $this->GetFile($this->f[$this->f_no]['name']);

     // remplace les variables avec $
     if(TPLN_PARSE_GLOBALS == 1)
      {
        $this->ParseGlobals();
      }

     // on remplace les fichiers à inclure
     $this->_CaptureIncludeCmd(); // capture des commandes include
     // parsing et évaluation si necessaire
     if(count($this->f[$this->f_no]['cmd_items']) > 0)
     {
        $this->_ParseAllIncludeCmd();
        $this->f[$this->f_no]['cmd_items'] = array(); // on efface
     }

     // on reremplace les $ si contenues dans fichiers inclu
     if(TPLN_PARSE_GLOBALS == 1)
      {
        $this->ParseGlobals();
      }


     $this->f[$this->f_no]['shortcut_blocs']['all'] = $this->_CaptureAllBlocs();
     $this->_EndBlocVerify();
     $this->_DualBlocVerify();

     // Capture des items apres verification des blocs
     $this->f[$this->f_no]['items'] = $this->_CaptureItems();
     $this->_CaptureItemsInEachBloc();

   // debugger
   $this->_StructOpen();

   if($this->f[$this->f_no]['create_cached_file'] == 1){ return false;}
 }
 
function CleanBlocs()
{
        for($i=0; $i < count($this->f[$this->f_no]['shortcut_blocs']['all']); $i++)
        {
               $this->f[$this->f_no]['buffer'] = str_replace("<bloc::{$this->f[$this->f_no]['shortcut_blocs']['all'][$i]}>",'',$this->f[$this->f_no]['buffer']); // bloc avant
               $this->f[$this->f_no]['buffer'] = str_replace("</bloc::{$this->f[$this->f_no]['shortcut_blocs']['all'][$i]}>",'',$this->f[$this->f_no]['buffer']); // bloc apres
        }
        
} 
 

function CreateVirtualTemplate($countain)
 {

  $this->vf_no++; // incremente
  $this->f_no++;  // incremente

/** initialisation des types de variables pour EALL ***************************/
  $this->f[$this->f_no]['name'] = 'Virtual'.$this->vf_no;
  $this->f[$this->f_no]['buffer'] = $countain;
  $this->f[$this->f_no]['items'] = array();
  $this->f[$this->f_no]['php_items'] = array();
  $this->f[$this->f_no]['cmd_items'] = array();

  $this->f[$this->f_no]['create_cached_file'] = 0;
  $this->f[$this->f_no]['time_started'] = 0;
  $this->f[$this->f_no]['cache_expire'] = 0;
  $this->f[$this->f_no]['execution_time'] = 0;
  $this->f[$this->f_no]['chrono_started'] = 0;

  $this->f[$this->f_no]['shortcut_blocs'] = array();
  $this->f[$this->f_no]['shortcut_blocs']['all'] = array();
  $this->f[$this->f_no]['shortcut_blocs']['used'] = array();

  $this->f[$this->f_no]['shortcut_blocs']['name'] = NULL;
  $this->f[$this->f_no]['shortcut_blocs']['name']['items'] = array();

  $this->f[$this->f_no]['def_blocs'] = array();

  $this->chrono_type[$this->f_no] = NULL;
/******************************************************************************/


  $time = explode(' ',microtime());
  $this->f[$this->f_no]['time_started'] = time(); // dans pour le cache à la seconde
  $this->f[$this->f_no]['chrono_started'] = $time[1] + $time[0];

  // on définit le chrono
  if(!defined('TPLN_CHRONO_STARTED'))
   {
    $chrono = $time[1] + $time[0];
    define('TPLN_CHRONO_STARTED',$chrono);
   }


     // remplace les variables avec $
     if(TPLN_PARSE_GLOBALS == 1)
      {
        $this->ParseGlobals();
      }

     // on remplace les fichiers à inclure
     $this->_CaptureIncludeCmd(); // capture des commandes include
     // parsing et évaluation si necessaire
     if(count($this->f[$this->f_no]['cmd_items']) > 0)
     {
        $this->_ParseAllIncludeCmd();
        $this->f[$this->f_no]['cmd_items'] = array(); // on efface
     }

     // on reremplace les $ si contenues dans fichiers inclu
     if(TPLN_PARSE_GLOBALS == 1)
      {
        $this->ParseGlobals();
      }


     $this->f[$this->f_no]['shortcut_blocs']['all'] = $this->_CaptureAllBlocs();
     $this->_EndBlocVerify();
     $this->_DualBlocVerify();

     // Capture des items apres verification des blocs
     $this->f[$this->f_no]['items'] = $this->_CaptureItems();
     $this->_CaptureItemsInEachBloc();

   // debugger
   $this->_StructOpen();

   if($this->f[$this->f_no]['create_cached_file'] == 1){ return false;}
 }




function DirectIOWrite($file='')
{
         $c_id = $this->f_no; // on sauve le numero actuel

         $this->Open($file);
         $this->Write();

         $this->f_no = $c_id;// on remet le numero
}


function DirectIOOutput($file='')
{
         $c_id = $this->f_no; // on sauve le numero actuel

         $this->Open($file);
         $output = $this->OutPut();

         $this->f_no = $c_id;// on remet le numero

         return $output;


}


function DirectIOSave($file='',$path)
{
         $c_id = $this->f_no; // on sauve le numero actuel

         $this->Open($file);
         $this->SaveTemplate($path);

          $this->f_no = $c_id;// on remet le numero

}



function DefineTemplate($arr)
{
  if(!is_array($arr))
   {
     $this->_Error(12);
     return;
   }

   foreach($arr as $key=>$val)
     {
       $this->Open($val);
       $this->def_tpl[count($this->f)-1] = $key;
     }
}



function SetTemplate($key)
{
  $this->ChangeTemplate($key);
}


function ChangeTemplate($key)
{
  if(is_string($key))
  {
    // on prend l'id
     if(!in_array($key,$this->def_tpl,TRUE))
     {
      $this->_Error(11,$key);
      return;
     }

    $key = array_search($key,$this->def_tpl,TRUE);

  }

  if(is_int($key))
  {
    if($key < 0 || $key >= count($this->f))
      {
       $this->_Error(10,$key);
      }
   }

  $this->f_no = $key;
}



function ParseGlobals()
{
  $this->f[$this->f_no]['php_items'] = $this->_CaptureItems('','PHP');

  if(count($this->f[$this->f_no]['php_items']) == 0)
  {
   return;
  }

  foreach($this->f[$this->f_no]['php_items'] as $item)
   {
     // on regarde si la variable est _GET ou _POST ou _SESSION
     if(substr($item,0,1) != '_') // pas de signe $_ au début
      {
        if(!isset($GLOBALS[$item]))
        {
          $GLOBALS[$item] = NULL;
        }

        $replace = $GLOBALS[$item];
      }
      else
      {
       // on prend et on verifie
       $replace = '$'.$item;
       @eval("\$replace = $replace;");
      }

     $item = '$'.$item;
     $item = str_replace('$','\$',$item);
     $item = str_replace('[','\[',$item);
     $item = str_replace(']','\]',$item);
     $this->f[$this->f_no]['buffer'] = $this->_ReplaceItem($item, $replace, $this->f[$this->f_no]['buffer']);
   }
}



function Parse($path,$replace,$functions='')
 {
  // fonction de formatage des données
  $replace = $this->_ApplySpecialFunction($replace,$functions);

  if($this->_IsBlocDesired($path))  // est ce un bloc ?
   {
      $item = $this->_GetItem($path);// on prend l'item
      $fathers_arr = $this->_GetFathers($path,'ARRAY'); // les pères ds un array
      $bloc = $this->_GetFather($path); // on prend le père

      if(!$this->BlocExists($bloc)){
          $this->_Error(2,$this->f[$this->f_no]['name'],$bloc);}

      $this->_ItemVerify($item,$bloc);// verification


      if(!$this->_IsDefined($path)) // le chemin est il enregistré
        {
           $this->_DefineBloc($path); // defini les blocs un par un
        }

      $this->_DirectParseInBloc($fathers_arr,$item,$replace);
   }
  else
  {
     $this->_ItemVerify($path);// verification
     $this->_DirectParseInFile($path, $replace); // parsing direct dans le fichier
  }

}

function _DirectParseInFile($item, $replace)
{
  $this->f[$this->f_no]['buffer'] = $this->_ReplaceItem($item,$replace,$this->f[$this->f_no]['buffer']);
}

function _DirectParseInBloc($fathers_arr,$item,$replace)
{
  $b_ref = &$this->f[$this->f_no]['def_blocs'];

  // on atteint le bloc
  for($i=0;$i<count($fathers_arr);$i++){
      $b_ref = &$b_ref[$fathers_arr[$i]];

      if($i < count($fathers_arr)-1){
          $b_ref = &$b_ref['children'];}}

  // on regarde si il y eu un loop > 0
  $loop_nb = count($b_ref['parsed']);
  $b_ref['parsed'][$loop_nb-1] = $this->_ReplaceItem($item,$replace,$b_ref['parsed'][$loop_nb-1]);
}



function FastParse($path,$functions='')
{

  // Est ce un bloc alors on remplace
  if($this->_IsBlocDesired($path))
   {
      $item =  $this->_GetItem($path);// on prend le dernier item
   }
  else
   {
      $item = $path;
   }

  $this->Parse($path, $GLOBALS[$item], $functions);
}



function ParseDBRow($bloc,$func='')
{
  $this->_PathVerify($bloc);
  $i = 0;
  
  while($row = $this->DBFetchAssoc())
  {
    $keys = @array_keys($row);// prise du mon des clefs
    $i++;

    foreach($keys as $key)
     {
       if($this->ItemExists($key,$bloc))
        {
          if(!empty($func))
              $this->Parse("$bloc.$key",$row[$key],$func);
          else
              $this->Parse("$bloc.$key",$row[$key]);
        }
     }
     $this->Loop($bloc);
  }
  
  // pas d'enregidtrement effacement du bloc
  if($i == 0)$this->eraseBloc($bloc);
  
}

function ParseDBField($bloc,$fields, $func='')
{
  /*echo "<pre>";
  print_r($this->f);
  echo "</pre>"; */

  // verification du chemin
  $this->_PathVerify($bloc);

  // on simule que l'on prend un item
  $last_bloc = $this->_GetItem($bloc);

  // control à faire sur le chemin !
  for($i=0;$i < count($fields); $i++)
  {
     $field_name = $fields[$i];

      if($this->ItemExists($field_name,$last_bloc))
      {
         if(!empty($func))
              $this->Parse("$bloc.$field_name",$field_name,$func);
          else
              $this->Parse("$bloc.$field_name",$field_name);
      }
  }
}



function LoadArrayInBloc($path,$arr)
{
  if(count($arr) == 0)
  {
        $this->EraseBloc($path);
        return;
   }

  foreach($arr as $current_arr)
   {
      $keys = @array_keys($current_arr);// prise des clefs

      foreach($keys as $key)
         $this->Parse("$path.$key", $current_arr[$key]);

       $this->Loop($path);
   }

}



function Loop($path)
{

  // vérification de l'existence du chemin comme defini
  if(!$this->_IsDefined($path,'NOITEM'))
  {
      $this->_Error(4.1,$this->f[$this->f_no]['name'],$path);
      return;
  }

  $fathers_arr = $this->_GetFathers($path,'ARRAY',0);

  // touche le bloc
  $b_ref = &$this->f[$this->f_no]['def_blocs'];

 for($i=0;$i<count($fathers_arr);$i++)
 {
   $b_ref = &$b_ref[$fathers_arr[$i]];

   if($i < count($fathers_arr)-1)
    {
       $b_ref = &$b_ref['children'];
    }
 }

 // toute la génération qui suit est remplacé donc
 if(count($b_ref['children']) > 0)
  {
      //$level = count($fathers_arr)-1;
      $child_blocs = $this->_GetNextGenerationBlocs($fathers_arr);

      // on encapsule les blocs
      $this->_EncapsuleBlocs($fathers_arr,$child_blocs);
  }

 // on ajoute une session parse
 // on incrémente la valeur de loop
 $b_ref['parsed'][] = $b_ref['structure'];
 $b_ref['is_looped'] = 1;
}


function _GetNextGenerationBlocs($bloc_arr)
{
  // touche le bloc
  $b_ref = &$this->f[$this->f_no]['def_blocs'];

 for($i=0;$i<count($bloc_arr);$i++)
 {
   $b_ref = &$b_ref[$bloc_arr[$i]];

   if($i < count($bloc_arr)-1)
    {
       $b_ref = &$b_ref['children'];
    }
 }

 $b_names = array_keys($b_ref['children']);


  return $b_names;
}


function _EncapsuleBlocs($bloc_arr, $child_blocs)
{
  // on encapsule chacun des enfants CAD
  // rétrécissement du bloc s'il a des enfants
  // parsing chez le père du fils parsed
  // remize à zero du bloc fils
  foreach($child_blocs as $children)
  {
     // touche le bloc
     $b_ref = &$this->f[$this->f_no]['def_blocs'];

     for($i=0;$i<count($bloc_arr);$i++)
      {
       $b_ref = &$b_ref[$bloc_arr[$i]];

       if($i == count($bloc_arr)-1)
        {
           $father_parsed = &$b_ref['parsed'][count($b_ref['parsed'])-1]; // c'est le dernier
        }

       $b_ref = &$b_ref['children'];
      }

    $children_all_parsed = &$b_ref[$children]['parsed'];
    $children_structure = &$b_ref[$children]['structure'];
    $children_parsed = '';

  // bloc
   if(count($children_all_parsed) == 1)
    {
       $children_parsed = $children_all_parsed['0'];
    }

   if(count($children_all_parsed) > 1)
   {
         for($l=0; $l<count($children_all_parsed)-1; $l++)
         {
             $children_parsed =  $children_parsed.$children_all_parsed[$l];
         }
      }


    // parsing chez le père
    $father_parsed = $this->_ReplaceBloc($children,$children_parsed,$father_parsed);

    // remize à zero du bloc child
    $children_all_parsed = array($children_structure);
    unset($children_parsed);
  }
}


function EraseItem($item)
{
 $this->Parse($item,'');
}


function EraseBloc($path)
{
  $this->ParseBloc($path,'');

  /*$bloc_arr = $this->_GetFathers($path,'ARRAY',0);
  // à un niveau
  if(empty($type) && count($bloc_arr) == 1)
  {
     $this->ParseBloc($path,"");
     return;
  }

 // pas dans un loop
 // parsing simple chez un pere
 // on regarde si le père existe d'abord
 if(empty($type))
 {
   $n_bloc = array_slice($bloc_arr, 0,count($bloc_arr)-1);
   $n_path = join('.',$n_bloc);


   // regarde si le bloc est défini ?
   if(!$this->_IsDefined($n_path,'NOITEM'))
   {
      $this->_DefineBloc($n_path.'.none');
   }

   // on prend le père parsed !
   $b_ref = &$this->f[$this->f_no]['def_blocs'];

   for($i=0; $i<count($bloc_arr)-2; $i++)
   {
    $b_ref = &$b_ref[$n_bloc[$i]]["children"];
   }

   $father_name = $bloc_arr[count($bloc_arr)-2];
   $last_bloc = $bloc_arr[count($bloc_arr)-1];
   $all_parsed = &$b_ref[$father_name]["parsed"];
   $father_parsed = &$all_parsed[count($all_parsed)-1];

   /*echo "path $path \n";
   echo "last_bloc $last_bloc \n";
   echo "all parsed ";
   var_dump($all_parsed);
   echo "father_name $father_name \n";
   echo "father_parsed $father_parsed \n";

   $father_parsed = $this->_ReplaceBloc($last_bloc,"",$father_parsed);
   /*echo "new father_parsed $father_parsed \n";
   exit;

 }


if($type == 'LOOP')
 {

    if(!$this->_IsDefined($path,'NOITEM'))
    {
      $this->_DefineBloc($path.'.none');
    }

    $b_ref = &$this->f[$this->f_no]['def_blocs'];

    for($i=0;$i<count($bloc_arr);$i++)
    {
      $b_ref = &$b_ref[$bloc_arr[$i]];

      if($i == count($bloc_arr)-2)
      {
        $father_parsed = &$b_ref["parsed"][count($b_ref["parsed"])-1];
        //echo "father_parsed $father_parsed";
        //echo "<hr>";
      }

      if($i == count($bloc_arr)-1)
      {
          $children_name = $bloc_arr[$i];
          //echo "children_name $children_name";
          //echo "<hr>";
      }

      if($i < count($bloc_arr)-1)
      {
            $b_ref = &$b_ref['children'];
      }
    }

    // le bloc n'existe pas
    if(empty($b_ref))
    {
    $this->_Error(5,$this->f[$this->f_no]['name'],$path);
    return;
    }

    // on efface le bloc chez son père
    if(count($bloc_arr) > 1)
    {
      $father_parsed = $this->_ReplaceBloc($children_name,"",$father_parsed);
    }

    $b_ref["parsed"] = array();// on l'efface sa session à zero
 }
*/

}


function GetFile($filename,$blocname='')
{

  if(!$fp = @fopen($filename, 'r')) // ouverture du fichier en lecture
     {
      $this->_Error(0,$filename);
     }

      $filebuffer = @fread($fp,filesize($filename));
      @fclose($fp);
      clearstatcache(); // efface la mémoire tampon

      if(!empty($blocname)) // on veut capturee un bloc
        {
         $filebuffer = $this->_CaptureBloc($blocname,$filebuffer);
        }

 return $filebuffer;
}


function ParseBloc($path,$replace)
{
  $this->_PathVerify($path); // Verification de l'existence des blocs du chemin

  // definition du chemin
  if(!$this->_Isdefined($path,'NOITEM')) $this->_DefineBloc($path.'.none');

  // bloc imbriqué ?
  if(!$this->_IsBlocDesired($path))
  {
     $this->f[$this->f_no]['buffer'] = $this->_ReplaceBloc($path,$replace,$this->f[$this->f_no]['buffer']);
     $bloc_arr[] = $path;
  }
  else
  {
      // on doit aller dans le bloc pour l'éliminer !
      $bloc_arr = $this->_GetFathers($path, 'ARRAY',0);
  }

  // on efface le bloc
  $b_ref = &$this->f[$this->f_no]['def_blocs'];

  // un seul bloc ?
  if(count($bloc_arr) == 1)
  {
     $b_ref = &$b_ref[$bloc_arr[0]];
  }
  else
  {
      // on efface dans le dernier pere le bloc en cours
      for($i=0;$i<count($bloc_arr);$i++)
      {
         $b_ref = &$b_ref[$bloc_arr[$i]];

         if($i == count($bloc_arr)-2)
         {
           $last_father_parsed = &$b_ref['parsed'][count($b_ref['parsed'])-1];
         }

         if($i < count($bloc_arr)-1)
              $b_ref = &$b_ref['children'];

      }

       $last_father_parsed = $this->_ReplaceBloc($bloc_arr[count($bloc_arr)-1],$replace,$last_father_parsed);

  }


  $b_ref['parsed'] = array(); // on efface sa session du dernier bloc !



  /*// est ce un bloc imbriqué
  if($this->_IsBlocDesired($path))
  {
    $last_bloc = $this->_GetItem($path);// on prend le dernier dans comme un item
  }

  if($this->_IsDefined($path,'NOITEM'))
     echo "$path defini !<br>";
  else
      echo "$path non défini !<br>";



  if(!$this->BlocExists($path))
  {
     $this->_Error(2,$this->f[$this->f_no]['name'],$path);
     return;
  }


  $this->f[$this->f_no]['buffer'] = $this->_ReplaceBloc($path,$replace,$this->f[$this->f_no]['buffer']);
  */
}

function ReloadBlocVars($path)
{
 $this->_PathVerify($path);

 $last_bloc = $this->_GetItem($path);
 $this->f[$this->f_no]['shortcut_blocs'][$last_bloc]['items'] = $this->_CaptureItems($last_bloc);

}



function IncludeFile($item,$file)
{
  // verification pour un bloc
  $this->Parse($item,"{#include(\"$file\");}"); // on replace par la commande include
  $this->f[$this->f_no]['cmd_items'][] = $file; // on ajoute à la liste des f_cmd

}


function GetBlocInFile($path)
{
   $this->_PathVerify($path);

   $all_bloc = explode('.',$path);
   $bloc_name =  $all_bloc[count($all_bloc)-1];

   $bloc = $this->_CaptureBloc($bloc_name,$this->f[$this->f_no]['buffer']);
   return $bloc;
}



function Write()
 {
   $this->_Out();
   echo $this->f[$this->f_no]['buffer'];
   $this->_CreateCache(TPLN_CACHE_DIR.$this->f[$this->f_no]['name']);
 }



function Output()
 {
   $this->_Out();
   $this->_CreateCache(TPLN_CACHE_DIR.$this->f[$this->f_no]['name']);
   return $this->f[$this->f_no]['buffer'];
 }


function SaveTemplate($path)
{

  // ajout d'un fonction pour la création de dossier
  $act_dir = '';
   $all_dir = explode('/',$path);

   if(count($all_dir) > 1)
    {
      for($i=0;$i<count($all_dir)-1;$i++)
       {
          if($i > 0){$act_dir .= '/';}
          $act_dir .= $all_dir[$i];

          if(!is_dir($act_dir))
          {

            if(!@mkdir($act_dir,0755))
             {
              $this->_Error(7.1,$act_dir);
              return;
             }

           clearstatcache();
          }
       }
    }

   $output = $this->Output();
   $fp = @fopen($path, 'w'); // ouverture du fichier
   @fwrite($fp,$output);
   @fclose($fp);
   clearstatcache(); // efface la mémoire tampon
}



/*************** additionnal *****************************/
// pour les fonctions spéciales
function _ApplySpecialFunction($replace='', $functions='')
{

 if(empty($functions)){return $replace;}

   // y a t'il une fonction spéciale ?
    $functions = explode('|',$functions);

    foreach($functions as $function_name)
     {
       if($function_name != 'B' && $function_name != 'I' && $function_name != 'U' && !empty($function_name))
        {
         $replace = $function_name($replace);
        }
     }

    // Bold, Italic, Underline
    if(in_array('B',$functions)){$replace = "<b>$replace</b>";}
    if(in_array('I',$functions)){$replace = "<i>$replace</i>";}
    if(in_array('U',$functions)){$replace = "<u>$replace</u>";}


 return $replace;
}


/*********************************************************
                          Blocs
*******************************************************/
// remplace le fils dans le père
function _ParseEncapsuledBlocs($bloc)
{
  // on atteint le dernier bloc
  // on conserve son père (adresse) et son fils (nom, structure)
  // on supprime le dernier membre de bloc
 while(count($bloc) > 1)
  {
    $b_ref = &$this->f[$this->f_no]['def_blocs'];

    for($i=0;$i<count($bloc);$i++)
    {
        $b_ref = &$b_ref[$bloc[$i]];

        if($i == count($bloc) - 2) // capture du dernier père
        {
          $father_parsed = &$b_ref['parsed'][count($b_ref['parsed'])-1];
         /* echo "father_parsed $father_parsed";
          echo "<hr>";  */
        }

        if($i == count($bloc) - 1) // capture du dernier fils
        {
          $children_name = $bloc[$i];
          $children_all_parsed = $b_ref['parsed'];
          $children_parsed = NULL;

          if(count($children_all_parsed) > 1)
          {
             for($j=0;$j<count($children_all_parsed)-1;$j++)
               {
                 $children_parsed .= $children_all_parsed[$j];
               }
          }
          else
          {
             if(count($children_all_parsed) == 1)
                 $children_parsed = $children_all_parsed[0];
          }
        }


      $b_ref = &$b_ref['children'];
    }

  /* echo "children_parsed $children_parsed";
   echo "<hr>";*/

    // on remplace le fils dans le père
    $father_parsed = $this->_ReplaceBloc($children_name,$children_parsed,$father_parsed);

    /*echo "new father $father_parsed";
    echo "<hr>";    */

    // on supprime le dernier bloc
    unset($children_parsed);
    $bloc = array_slice($bloc,0,count($bloc)-1);
  }
}


function _ParseSubBlocs($level, $bloc_arr)
{
 $b_ref = &$this->f[$this->f_no]['def_blocs'];

 foreach($bloc_arr as $cur_bloc)
 {
     $b_ref = &$b_ref[$cur_bloc]['children'];
 }

 if(!is_array($b_ref))
     return;

  $b_names = array_keys($b_ref); // contiens tous les noms de blocs

  foreach($b_names as $bloc)
  {

    if(count($b_ref[$bloc]['children']) > 0)
    {
      $bloc_arr[] = $bloc;
      $this->_ParseSubBlocs($level+1, $bloc_arr);
    }
    else
    {
      // le level est il le même ?
      /*if($level < count($bloc_arr)-1)
       {
         $bloc_arr[count($bloc_arr)-1] = $bloc;
       }
      else
       {
          $bloc_arr[] = $bloc;
       } */

       /*echo "level $level <br><br>";

       echo "old bloc_arr avant le redimesionnement en ajoutant $bloc<br>";
       var_dump($bloc_arr);
       echo "<hr>";
       */
       $bloc_arr = array_slice($bloc_arr,0,$level+1);
       $bloc_arr[] = $bloc;

       /*echo "apres bloc_arr <br>";
       var_dump($bloc_arr); */

       $this->_ParseEncapsuledBlocs($bloc_arr);

    }

  }

}


function _ParseBigFathers()
{
   // on prend les pères parsed que l'on remplace dans le buffer
   $b_ref = &$this->f[$this->f_no]['def_blocs'];

  // on prend les noms du parent BIG
  $b_names = array_keys($b_ref);

  $b_parsed = ''; // initialisation

  foreach($b_names as $bloc)
  {
    if(count($b_ref[$bloc]['parsed']) == 1 && array_key_exists('0',$b_ref[$bloc]['parsed']))
    {
       $b_parsed = $b_ref[$bloc]['parsed'][0];
    }

    // on regarde si le bloc a été en loop
    if(count($b_ref[$bloc]['parsed']) > 1)
    {
      // on ne prend pas la première
      for($i=0;$i<count($b_ref[$bloc]['parsed'])-1;$i++)
          {
             $b_parsed .= $b_ref[$bloc]['parsed'][$i];
           }
    }


    $this->f[$this->f_no]['buffer'] = $this->_ReplaceBloc($bloc,$b_parsed,$this->f[$this->f_no]['buffer']);
    //unset($b_parsed);
    $b_parsed = '';
  }
}


function _GetBigBlocs()
{
  // touche le bloc
  $b_ref = &$this->f[$this->f_no]['def_blocs'];
  $b_names = array_keys($b_ref);
  return $b_names;

}


function _Out()
{

  if(count($this->f[$this->f_no]['def_blocs']) > 0)
  {
     $b_ref = &$this->f[$this->f_no]['def_blocs'];

     // on obtiens les noms des big blocs
     $big_blocs = $this->_GetBigBlocs();

     foreach($big_blocs as $bloc)
     {
       if(count($b_ref[$bloc]['children']) > 0)
        {
          $this->_ParseSubBlocs(0,(array)$bloc);
        }
     }

   $this->_ParseBigFathers(); // va compresser les bloc big et les parse au sein du buffer
  }

   $this->_ParseAllIncludeCmd();
   $this->_ParseChrono();
   $this->_ParseLogo();
   $this->_ParseVersion();
}

/********************************************
              Capture
*********************************************/
// fonction capture item
// et titre que ce d'un bloc
function _CaptureItems($subject='',$type='')
 {

   if(empty($subject))
    {
        $subject = $this->f[$this->f_no]['buffer']; // ds le fichier
        $blocs = $this->f[$this->f_no]['shortcut_blocs']['all'];
    }
   else
    {
     $subject = $this->_CaptureBloc($subject, $this->f[$this->f_no]['buffer']);

     // on capture les blocs du sujet
     $blocs = $this->_CaptureAllBlocs($subject);

    // on les efface
    foreach($blocs as $bloc)
      {
       $subject = $this->_ReplaceBloc($bloc,'',$subject);
      }

    }




    // exclision des espaces etc
  if($type == 'PHP')
   {
     $motif = "/\{\\$([^ ;\*\$\\\,\\n\\t]+)?\}/msU";
   }
  else
   {
      $motif = "/\{([^ ;\.\*\$\\\,\\n\\t]+)?\}/msU";
   }

  $match = preg_match_all($motif,$subject,$tab);

   // on dédoublonne le tableau
   $one = array();

   if(count($tab[1]) > 0)
    {

     $one = array_unique($tab[1]);
    }

   return $one;
 }

// fonction qui capture un bloc sans ses motif
function _CaptureBloc($name,$subject)
 {

  if(empty($name))
    {
     $this->_Error(5,$this->f[$this->f_no]['name']);
     return;
    }

   $motif = "<bloc::$name>(.*)?<\\/bloc::$name>";
   $match = preg_match("/$motif/msU",$subject,$bloc);

   // error
   if(!$match)
    {
     $this->_Error(2,$this->f[$this->f_no]['name'],$name);
     return;
    }

  $bloc = trim($bloc[1])."\n";

  return $bloc;
}

// fonction qui capture tous les blocs du sujet
function _CaptureAllBlocs($subject='')
 {
   if(empty($subject))
     {
       $subject = &$this->f[$this->f_no]['buffer'];
     }

   $motif = "<bloc::([^ ;\.\*\$\\\,\\n\\t]+)?>";
   $match = preg_match_all("/$motif/U",$subject,$blocs);

   return $blocs[1];
}

// capture les items de tous les blocs du fichiers
function _CaptureItemsInEachBloc()
{
  if(count($this->f[$this->f_no]['shortcut_blocs']['all']) == 0) {return;}

 foreach($this->f[$this->f_no]['shortcut_blocs']['all'] as $bloc)
  {
    $this->f[$this->f_no]['shortcut_blocs'][$bloc]['items'] = $this->_CaptureItems($bloc);
  }

}

// preds les blocs du sujet
function _GetAllBlocs($subject)
{
   $motif = "<bloc::([^ ;\.\*\$\\\,\\n\\t]+)?>";
   preg_match_all("/$motif/U",$subject,$blocs);
   return $blocs[1];
}


// capture les instructions include au sein d'un template
function _CaptureIncludeCmd()
 {
   $motif = "\{#include\(([^ ;\*,\\n\\t]+)?\);\}";
   $match = preg_match_all("/$motif/U",$this->f[$this->f_no]['buffer'],$tab);

   if(count($tab[1]) == 0)
    {
     return;
    }

   $one = array_unique($tab[1]);


   $this->f[$this->f_no]['cmd_items'] = $one;

 }

/********************************************
              Verification
*********************************************/


function ItemExists($item_name,$bloc='')
{
  if(empty($bloc))
  {

   if(@in_array($item_name,$this->f[$this->f_no]['items']))
   {
     return true;
   }
   else
    {
      return false;
    }
  }
  else
  {
    if(@in_array($item_name,$this->f[$this->f_no]['shortcut_blocs'][$bloc]['items']))
   {
     return true;
   }
   else
    {
      return false;
    }
  }
}

function _ItemVerify($item,$bloc='')
{

  if(empty($bloc))
  {
  if(!$this->ItemExists($item))
    {
     $this->_Error(1,$this->f[$this->f_no]['name'],'',$item);
     return;
    }
  }
  else
  {
  if(!$this->ItemExists($item,$bloc))
    {
     $this->_Error(1.1,$this->f[$this->f_no]['name'],$bloc,$item);
     return;
    }

  }

}

// verify que tous les blocs d'un chemin existe
function _PathVerify($path)
{
 $tab = @explode('.',$path);
 // on atteint le bloc et on lui ajoute les items
 for($i=0;$i<count($tab);$i++)
 {
     if(!$this->BlocExists($tab[$i]))
     {
       $this->_Error(2,$this->f[$this->f_no]['name'],$tab[$i]);
       return;
     }
 }

}


function BlocExists($bloc_name)
{
   if(@in_array($bloc_name,$this->f[$this->f_no]['shortcut_blocs']['all']))
   {
     return true;
   }
   else
    {
      return false;
    }
}


// verifie l'existence de la fin du bloc
function _EndBlocVerify()
{

  foreach($this->f[$this->f_no]['shortcut_blocs']['all'] as $bloc_name)
  {
   $motif = "/<\\/bloc::$bloc_name>/U";
   if(!preg_match($motif,$this->f[$this->f_no]['buffer']))
    {
     $this->_Error(8,$this->f[$this->f_no]['name'],$bloc_name);
     return;
    }
  }
}


// verifie qu'il n'existe pas deux bloc similaires
function _DualBlocVerify()
{
  if(count($this->f[$this->f_no]['shortcut_blocs']['all']) == 0) {return;}

  $blocs = array();


  foreach($this->f[$this->f_no]['shortcut_blocs']['all'] as $bloc){
      if(!in_array($bloc,$blocs)){
          $blocs[] = $bloc;}
      else{
          $this->_Error(2.1,$this->f[$this->f_no]['name'],$bloc);}
  }

}


/********************************************
                    Parsing
*********************************************/
// item replace
function _ReplaceItem($name,$replace,$subject)
 {

   if(empty($name))
    {
    $this->_Error(6,$this->f[$this->f_no]['name']);
     return;
    }

   $str = preg_replace("/\{$name\}/U",$replace,$subject);
   return $str;
}

// fonction qui replace un bloc sans ses motifs
function _ReplaceBloc($name,$replace,$subject)
 {

  if(empty($name))
   {
    $this->_Error(5,$this->f[$this->f_no]['name']);
    return;
   }

   // c'est pas un tableau
   if(!is_array($name) && !is_array($replace))
    {
     $motif = "<bloc::$name>(.*)?<\\/bloc::$name>";
     $str = @preg_replace("/$motif/msU",$replace,$subject);
    }
    else
    {
     $str = @preg_replace($name,$replace,$subject);
    }
   return $str;
}


function _ParseIncludeCmd($file)
{
  // parsing du nom de fichier
  $file = str_replace(chr(34),' ',$file);
  $file = str_replace(chr(39),' ',$file);
  $file = trim($file);

  $filebuffer = $this->GetFile($file);

  if($this->_IsPhpFile($file))
  {
     $filebuffer = $this->_EvalHtml($filebuffer);
  }

  $file = str_replace('/','\/',$file);
  $motif = "/\{\#include\(\"$file\"\)\;\}|\{\#include\(\'$file\'\)\;\}/";
  $this->f[$this->f_no]['buffer'] = preg_replace($motif,$filebuffer,$this->f[$this->f_no]['buffer']);
}



function _ParseAllIncludeCmd()
{
  if(count($this->f[$this->f_no]['cmd_items']) == 0){return;}

     foreach($this->f[$this->f_no]['cmd_items'] as $filename)
      {
          $this->_ParseIncludeCmd($filename);
      }
}




function _EvalHtml($string)
{

 //$string = implode('', $string);

 ob_start();
 eval('?>' . $string);
 $string = ob_get_contents();
 ob_end_clean();
 return $string;

}



// est ce un fichier php ?
function _IsPhpFile($filename)
{
  $php_file_extension = 'php|phtml|php4|php3'; // extensions de fichiers php

  if(preg_match("/\.($php_file_extension)$/i",basename($filename)))
   {
      return true;
   }
  else
   {
      return false;
   }
}



/****************************************************
                        Bloc
*****************************************************/

function _IsBlocDesired($path)
{
  if(preg_match("/[.]/",$path))
   {
    return true;
   }
  else
   {
    return false;
    }
}

// on vérifie que dans tous les blocs sont définis
function _IsDefined($path,$type='')
{
  if(empty($type))
   {
    $fathers_path = $this->_GetFathers($path); // récupère les pères
   }

  if($type == 'NOITEM')
   {
    $fathers_path = $path; // récupère les pères
   }


  if(@in_array($fathers_path, $this->f[$this->f_no]['shortcut_blocs']['used'],TRUE))
    {
      return true;
    }
  else
    {
      return false;
    }
}

// recupere le texte du bloc


// recupère l'item d'un path
function _GetItem($path)
{
  //if(!preg_match("/[.]/",$path)) {return;}

  $path_arr = explode('.', $path);
  return $path_arr[count($path_arr)-1];
}

// recupère le père donc avant dernier bloc
function _GetFather($path)
{
  //if(!preg_match("/[.]/",$path)) {return;}

  $path_arr = explode('.', $path);
  return $path_arr[count($path_arr)-2];
}


// recupere les pères
function _GetFathers($path, $type='',$with_item=1)
{
  // if(!preg_match("/[.]/",$path)) {return;}
  $path_arr = explode('.', $path);

  if($with_item == 1)
   {
     $fathers_arr = array_slice($path_arr,0,count($path_arr)-1); // tous les noms sauf le dernier
   }
  else
   {
      $fathers_arr = $path_arr;
   }


  if($type == 'ARRAY')
   {
     return $fathers_arr;
   }
   else
   {
    $fathers_path = join('.',$fathers_arr);
    return $fathers_path;
   }
}


function _DefineBloc($path)
{
  $fathers_path = $this->_GetFathers($path); // pas d'item
  $fathers_arr = $this->_GetFathers($path,'ARRAY'); // array

  foreach($fathers_arr as $bloc){
    $cur_arr[] = $bloc;

      if(count($cur_arr) == 1){
          $cur_path = $cur_arr[0];}
      else{
          $cur_path = join('.',$cur_arr);}

      // definition de tous les pères
      if(!$this->_IsDefined($cur_path,'NOITEM')){

          $this->_SavePath($cur_path);
          $a_bloc = $this->_InitialiazeBloc($bloc);

          // stockage
          $b_ref = &$this->f[$this->f_no]['def_blocs'];

          $i = 0;
          foreach($cur_arr as $cur_bloc){
               $b_ref = &$b_ref[$cur_bloc];

               if($i < (count($cur_arr)-1)){
                   $b_ref = &$b_ref['children'];}

               $i++;}

       $b_ref = $a_bloc;}
   }
}


function _InitialiazeBloc($bloc)
{
  $cur['structure'] = $this->_CaptureBloc($bloc,$this->f[$this->f_no]['buffer']);// structure
  $cur['parsed'][] = $cur['structure'];// parsed
  $cur['is_looped'] = 0;
  $cur['children'] = array();


  return $cur;
}


function _SavePath($fathers_path)
{
 $this->f[$this->f_no]['shortcut_blocs']['used'][] = $fathers_path; // on l'enregistre dans les blocs définis
}















/***************** Plug in ***********************************/
/*****************************************************
                      version
*****************************************************/

function _ParseVersion()
{
    if($this->ItemExists('_Version')) // on place le logo
    {
     $this->Parse('_Version',$this->TPLN_version);
    }
}




/*****************************************************
                      logo
*****************************************************/
function _ParseLogo()
{
    if($this->ItemExists('_Logo')) // on place le logo
    {
     $this->Parse('_Logo','<a href="http://tpln.sourceforge.net"><img src="http://tpln.sourceforge.net/logo.gif" border="0" width="83" height="33" alt="Visit HomePage"></a>');
    }
}


/*****************************************************
                      chrono
*****************************************************/


function SetChrono($type='this')
 {
  $this->chrono_type[$this->f_no] = $type;
 }

function _ParseChrono()
{
    if($this->ItemExists('_Chrono')) // on place le chrono
    {
     $this->_GetExecutionTime();
     $this->Parse('_Chrono',$this->f[$this->f_no]['execution_time']);
    }
}

// gestion du chrono
function _GetExecutionTime()
{
 $time = explode(' ',microtime());
 $fin = $time[1] + $time[0];

 // on veut les perf de cette session ou tous
 if($this->chrono_type[$this->f_no] == 'ALL')
  {
   $this->f[$this->f_no]['execution_time'] = intval(10000 * ((double)$fin - (double)TPLN_CHRONO_STARTED)) / 10000;
   }
 else
  {
   $this->f[$this->f_no]['execution_time'] = intval(10000 * ((double)$fin - (double)$this->f[$this->f_no]['chrono_started'])) / 10000;
   }
}

/*****************************************************
                      Cache
*****************************************************/

// retourne la valeur de la première ligne
function _InCachePeriod()
{
  $expire = $this->_GetTime(TPLN_CACHE_DIR.$this->f[$this->f_no]['name']);

  if($expire >= $this->f[$this->f_no]['time_started'])
   {
    return true;
   }
  else
   {
    @unlink(TPLN_CACHE_DIR.$this->f[$this->f_no]['name']); // on efface le fichier
    return false;
   }
}

// prend le temps au dessus du fichier
function _GetTime()
{
 $fp = @fopen(TPLN_CACHE_DIR.$this->f[$this->f_no]['name'], 'r');// ouvre le fichier
 $expire = trim(@fgets($fp,12));
 //$data = fread($fp, filesize($file));
 @fclose($fp);

 clearstatcache();

 return $expire;
}

function _CreateCache()
{
 if(!$this->f[$this->f_no]['create_cached_file'])
  {
   return;
  }
 else
  {
   $cache_file_content = $this->f[$this->f_no]['cache_expire']."\r".$this->f[$this->f_no]['buffer'];

   // ajout d'un fonction pour la création de dossier
   $all_dir = explode('/',$this->f[$this->f_no]['name']);
   if(count($all_dir) > 0)
    {
      $count = count($all_dir)-1; // on veut le dernier
      $act_dir = TPLN_CACHE_DIR;
      for($i=0;$i<$count;$i++)
       {
          $act_dir .= '/'.$all_dir[$i];
          if(!is_dir($act_dir))
          {

            if(!@mkdir($act_dir,0755))
             {
              $this->_Error(7);
              return;
             }

           clearstatcache();
          }

       }
    }

   $fp = @fopen(TPLN_CACHE_DIR.$this->f[$this->f_no]['name'], 'w'); // ouverture du fichier
   $this->f[$this->f_no]['buffer'] = @fwrite($fp,$cache_file_content);
   @fclose($fp);
   clearstatcache(); // efface la mémoire tampon
  }
}

function _GetCachedFile()
{
  $this->f[$this->f_no]['create_cached_file'] = 0;

  $fp = @fopen(TPLN_CACHE_DIR.$this->f[$this->f_no]['name'], 'r');
  $tmp_expire = @fgets($fp,12); // pour la position du pointeur apres le temps
  $this->f[$this->f_no]['buffer'] = @fread($fp,filesize(TPLN_CACHE_DIR.$this->f[$this->f_no]['name']));
  @fclose($fp);
  clearstatcache(); // efface la mémoire tampon
}

// verifie l'exitence du repertoire de cache et le crer ou cas ou
function _CacheDirExists()
{
 // le repertoire existe ?
 if(!is_dir(TPLN_CACHE_DIR))
 {
   if(!@mkdir(TPLN_CACHE_DIR,0755))
    {
      //$this->_AddTraceMsg();
      $this->_Error(7);
      return;
    }
   clearstatcache();
 }
}

}