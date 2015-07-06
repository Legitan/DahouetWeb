<?php

// src/Dahouet/MainBundle/Controller/selectionEquipageController.php
namespace Dahouet\MainBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Session\Session;
use Dahouet\MainBundle\Modele\DAO\VoilierDAO;
use Dahouet\MainBundle\Modele\DAO\ParticipeDAO;
use Dahouet\MainBundle\Modele\DAO\RegateDAO;
use Dahouet\MainBundle\Modele\DAO\LicencieDAO;

class selectionEquipageController extends Controller {
	public function indexAction() {
		
		// Récupération du service de session
		$session = $this->get ( 'session' );
		$numReg = $session->get('numReg');
		
		// Si on vient de sélectionner le voilier
		if (isset ( $_POST ['selectvoilier'] )) {
			
			// Récupération du numéro de voile du voilier choisi
			$voilier = VoilierDAO::getVoilier($_POST ['selectvoilier']);
			if($voilier!==false){
				$session->set ( 'numvoil', $_POST ['selectvoilier'] );
				//Récupère les participations aux régates pour lesquelles le voilier
				// n'est pas inscrit
				$participe = ParticipeDAO::getParticipation ( $voilier->getNumvoil() );
			}
			
			// 
			
			
			// Récupère les régates à venir
			$regate = RegateDAO::getRegate($numReg);
			// $reg = RegateDAO::getRegate($numreg);--------------------------------------------------->
		}
		// Si aucun équipier n'a été sélectionné
		if (empty ( $_POST ['validequip'] )) {
			// Récupération de la liste des licenciés
			$equipage = LicencieDAO::SelectEquip ();
			$session->set ( 'equipage', $equipage );
			$equipier = array ();
			$session->set ( 'equipier', $equipier );
			// Initialisation du tableau des équipiers choisis
			$listEquip = array ();
			$session->set ( 'listEquip', $listEquip );
		} else {
			// Récupération des variables de session
			$equipage = $session->get ( 'equipage' );
			$listEquip = $session->get ( 'listEquip' );
			
			// Récupération du licencié sélectionné
			$numlic = $_POST ['validequip'];
			$compt = count ( $session->get ( 'equipage' ) );
			for($i = 0; $i < $compt; $i ++) {
				if ($equipage [$i] ['NUMLIC'] == $numlic) {
					$session->set ( 'equipier', $equipage [$i] );
				}
			}
			
			// Ajout de l'équipier dans la liste d'équipage
			$equipier = $session->get ( 'equipier' );
			$compt = count ( $session->get ( 'listEquip' ) );
			$tab1 = array ();
			for($i = 0; $i < $compt; $i ++) {
				array_push ( $tab1, $listEquip [$i] );
			}
			array_push ( $tab1, $equipier );
			$session->set ( 'listEquip', $tab1 );
			
			// On retire l'équipier de la liste
			$compt = count ( $session->get ( 'equipage' ) );
			$tab1 = array ();
			for($i = 0; $i < $compt; $i ++) {
				if ($equipage [$i] == $equipier) {
				} else {
					array_push ( $tab1, $equipage [$i] );
				}
			}
			$session->set ( 'equipage', $tab1 );
			$session->set ( 'equipier', '' );
		}
		
		$listEquip = $session->get ( 'listEquip' );
		$connexion = $session->get ( 'connexion' );
		$equipage = $session->get ( 'equipage' );
		return $this->render ( 'DahouetMainBundle:Main:selectionEquipage.html.twig', array (
				'voilier' => $voilier,
				'idmbr' => $session->get ( 'id' ),
				'nommbr' => $session->get ( 'nommbr' ),
				'connexion' => $connexion,
				'equipage' => $equipage,
				'equipier' => $equipier,
				'listEquip' => $listEquip ,
				'regate' => $regate,
				
		) );
	}
}

