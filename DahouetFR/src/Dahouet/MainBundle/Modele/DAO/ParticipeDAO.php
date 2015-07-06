<?php

// src/Dahouet/MainBundble/Modele/DAO/ParticipeDAO

namespace Dahouet\MainBundle\Modele\DAO;

include_once('Connect.php');
use Dahouet\MainBundle\Modele\Metier\Participe;
use PDO;

class ParticipeDAO {
	
	/**
	 * 
	 * @param int $numvoil
	 * @return Participe
	 */
 public static function getParticipation ($numvoil) {
        $pdo = Connect::ConnectBDD();
        $sql = "select * from participe where NUMVOIL != '$numvoil'";
        $result = $pdo->query($sql);
    	$result->setFetchMode(PDO::FETCH_OBJ);
    	$ligne = $result->fetch();
    	if ($result->rowCount()==0){
    		$participe = false;
    	}else{
    		$participe = new Participe($ligne->CODEPAR,$ligne->NUMVOIL,	$ligne->NUMREG,$ligne->NUMLICSKI,$ligne->STATUS,$ligne->DATEINSC,$ligne->TPSREEL,$ligne->CODEDEC,$ligne->PLACE,$ligne->TPSCOMP,$ligne->HEURARR,$ligne->NUMPORT);
    	}
    	$result->closeCursor(); // on ferme le curseur des résultats
    	return $participe;
    }
}