<?php
require 'classes/Catproduct.php';


$categories = array( 11 ,9 ,2 ,33  );


$catproduct = new Catproduct();
$url ='/uploads/Screen Shot 2015-02-20 at 09.55.23.png';
$fil= strrchr($url,'/');
$deb=substr($fil, 0,strrpos($fil,"."));
$ext= strrchr($url,'.');
$filename = $deb.'-234'.$ext;

print_r($filename);
/*
$catproduct = new Catproduct();
$catproduct->catproduitViewIterative(null);

//print_r($catproduct->tabView);

foreach ($catproduct->tabView as $value) {
	$decalage="";
	for ($j=0; $j<($value['level'] * 5); $j++) $decalage .= " ";
	echo $decalage  . $value['label']." ". $value['id'] ." Lev:". $value['level'] . "\n";
}

$catproduct=null;
*/
//$GLOBALS['i']=0;
//$GLOBALS['tabView']=null;


//$tabIter = iterative(null);
//print_r($GLOBALS['tabView']);

/*
function iterative($result){
	if ($GLOBALS['i']==0){
		$catproduct = new Catproduct();
		$result = $catproduct->catproductByParentGet(0);
		$catproduct = null;
		//print_r($result);
	}	
	if (!empty($result)) {
		
		foreach ($result as $value) {
			$decalage = "";
			//for ($j=0; $j<($value['level'] * 5); $j++) $decalage .= " ";
			//echo $decalage. $GLOBALS['i']  . $value['label']." ". $value['id'] ." Lev:". $value['level'] . "\n";
			$GLOBALS['tabView'][$GLOBALS['i']]['label'] = $value['label'];
			$GLOBALS['tabView'][$GLOBALS['i']]['id'] = $value['id'];
			$GLOBALS['tabView'][$GLOBALS['i']]['level'] = $value['level'];
			$catproduct = new Catproduct();
			$result = $catproduct->catproductByParentGet($value['id']);
			$catproduct = null;
			//print_r($result);
			$GLOBALS['i']++;
			iterative($result);
		}
		
	}
}*/