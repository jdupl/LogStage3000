<?php
# Auteur Justin Duplessis 2014
# Publié sous la licence GPL v3.
# Voir le fichier LICENSE à la base du projet pour la licence ou <http://www.gnu.org/licenses/>.

# Script générateur de la base markdown du site.
# Écrit dans le dossier markdown les fichiers semaine1.txt, semaine2.txt etc.

echo "LogStage3000 Copyright (C) 2014 Justin Duplessis
Ce programme vient SANS ABSOLUMENT AUCUNE GARANTIE. 
Ceci est un logiciel libre et vous êtes invité à le redistribuer
suivant certaines conditions; Consulter la licence pour plus d'informations.\n";

include 'constantes.php';
date_default_timezone_set('UTC');
setlocale(LC_TIME, "fr"); # Nous voulons les jours de la semaine et les mois en français.
$date_depart = new DateTimeImmutable(DATE_DEBUT_STAGE);

mkdir(CHEMIN_MARKDOWN, 0777, true);

for ($i = 1; $i <= NB_SEMAINES; $i++) {

	if ( file_exists(getFicSemaine($i)) ) {
		echo "La semaine $i a déjà été générée !\n";
	} else {
		$nb_jours_depuis_debut = ($i - 1 )* 7;
		$interval = "P{$nb_jours_depuis_debut}D";
		$date_debut_semaine = $date_depart->add( new DateInterval($interval) ); # date du lundi
		$date_fin_semaine = $date_debut_semaine->add( new DateInterval('P4D') ); # date du vendredi
		$str = genereSemaine($date_debut_semaine, $date_fin_semaine, $i);
		file_put_contents(getFicSemaine($i), $str);
		echo "La semaine $i a été générée.\n";
	}
	
}

function getFicSemaine($semaineNo){
	$fic = CHEMIN_MARKDOWN;
	return "{$fic}semaine{$semaineNo}.txt";
}

function genereSemaine($dateDebut, $dateFin, $numeroSemaine) {

	$nom_site = NOM_DU_SITE;
	$dates_str = [];
	
	for ($j = 0; $j < 5; $j++) {
		$interval = "P{$j}D";
		$d = $dateDebut->add( new DateInterval($interval) );
		$dates_str[] = utf8_encode( strftime("%#d %B %Y", $d->getTimestamp()) );
	}
	
	$str = <<< EOT
# $nom_site

### Semaine $numeroSemaine

#### $dates_str[0] - $dates_str[4]

---

EOT;

	foreach($dates_str as $date_str) {
		$str.= <<< EOT

## $date_str

EOT;

	}
	$str .= <<< EOT

---

EOT;
	$prec = $numeroSemaine - 1;
	$suiv = $numeroSemaine + 1;
	
	if ($prec > 1) {
		$str .= <<< EOT

[Semaine précédente](semaine{$prec}.html)

EOT;
	}
	
	if($suiv <= NB_SEMAINES) {
		$str .= <<< EOT

[Semaine suivante](semaine{$suiv}.html)

EOT;
	}
	
	$str .= <<< EOT
	
[Retour à la page d'acceuil](index.html)

---
	
EOT;

	return $str;
}
?>