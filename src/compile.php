<?php
# Auteur Justin Duplessis 2014
# Publié sous la licence GPL v3
# Voir le fichier LICENSE à la base du projet pour la licence ou <http://www.gnu.org/licenses/>.

# Script générateur de la base markdown du site.
# Écrit dans le dossier markdown les fichiers semaine1.txt, semaine2.txt etc.

include 'constantes.php';

echo sprintf("LogStage3000 %s Copyright (C) 2014 Justin Duplessis
Script générateur de la base markdown
Ce programme vient SANS ABSOLUMENT AUCUNE GARANTIE.
Ceci est un logiciel libre et vous êtes invité à le redistribuer en
suivant certaines conditions; Consulter la licence pour plus d'informations.\n", VERSION);

require_once CHEMIN_LIB_MARKDOWN;

if( ! is_dir(CHEMIN_MARKDOWN) ) {
    mkdir(CHEMIN_MARKDOWN, 0777, true);
    echo sprintf("Dossier '%s' manquant créé !\n", realpath(CHEMIN_MARKDOWN));
}

if( ! is_dir(CHEMIN_HTML) ) {
    mkdir(CHEMIN_HTML, 0777, true);
    echo sprintf("Dossier '%s' manquant créé !\n", realpath(CHEMIN_HTML));
}

echo sprintf("Dossier source des fichiers markdown à interpréter:\n '%s' \n", realpath(CHEMIN_MARKDOWN));
echo sprintf("Dossier HTML de sortie:\n '%s' \n", realpath(CHEMIN_HTML));
echo "Analyse du dossier source... ";

# Les fichiers sources seront tout ce qui finit en .txt dans le dossier markdown
$fichiers_sources = glob(sprintf("%s*.txt", CHEMIN_MARKDOWN));
# Tri "naturel" des fichiers afin d'ordonner les semaines 1, 2, ..., 15 en ordre décimal
natsort($fichiers_sources);

echo "Complété !\n";
echo "Voici la liste des fichiers sources à traiter:\n";

foreach ($fichiers_sources as $fic) {
    echo "$fic\n";
}
echo "Génération d'un index à partir des sources\n";
echo "Génération d'une page intégrale à partir des sources\n";

echo "\nEst-ce correct ? [O\\n]?";
$user_conf = fgets(STDIN);
if (strlen($user_conf) > 2 && strlen(stristr($user_conf, "n")) > 0) {
    echo "Annulé par l'utilisateur !";
    exit(0);
}

$tout_markdown = "";

echo "Génération des headers et footers html... ";
$header_html = getHtmlHeader();
$footer_html = getHtmlFooter();
echo "Complété!";

echo "Traitement des fichiers markdown sources\n";

foreach ($fichiers_sources as $fic) {
    $fic_base = basename($fic, ".txt");
    echo "Traitement de $fic_base... ";
    $markdown = file_get_contents($fic);
    $tmp = explode("---", $markdown);
    if (count($tmp) === 5) {
        $tout_markdown .= $tmp[2];
    }
    $html_body = Michelf\Markdown::defaultTransform($markdown);
    $fic_sortie = sprintf("%s%s.html", CHEMIN_HTML, $fic_base);
    file_put_contents( $fic_sortie, "{$header_html}{$html_body}{$footer_html}");
    echo "Complété!\n";
}

echo "Génération de l'index du site... ";
$index_body = genereIndexBody();
$fic_sortie = sprintf("%sindex.html", CHEMIN_HTML);
file_put_contents( $fic_sortie, "{$header_html}{$index_body}{$footer_html}");
echo "Complété!\n";

echo "Génération de la page intégrale du site... ";
#$tout_body = Michelf\Markdown::defaultTransform($tout_markdown);
$tout_body = genereToutBody($tout_markdown);
$fic_sortie = sprintf("%stout.html", CHEMIN_HTML);
file_put_contents( $fic_sortie, "{$header_html}{$tout_body}{$footer_html}");
echo "Complété!\n";

function getHtmlHeader() {

    $html = sprintf(
'<!DOCTYPE html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
%s
</head>
<div class="container">'
    , getCss() );
    return $html;
}

function getHtmlFooter() {

    date_default_timezone_set('America/Montreal');
    $maintenant = (new Datetime('now'))->format('Y-m-d à H:i:s');

    $html = sprintf(
'
<p>Site généré le %s avec <a href="https://github.com/drfoliberg/LogStage3000"> LogStage3000 %s </a></p>
<p>Fait avec <a href="http://daringfireball.net/projects/markdown/" >Markdown</a> et <a href="http://getbootstrap.com/">Bootstrap</a> </p>
</div>'
    ,$maintenant, VERSION);
    return $html;
}

function getCss() {
    $html_css = '';
    if (CHEMIN_CSS !== false) {
        $html_css = sprintf('<link href="%s" rel="stylesheet" type="text/css">', CHEMIN_CSS);
    }
    return $html_css;
}

function genereIndexBody() {
    $markdown = sprintf(
"# %s

## %s

---

%s

---

## Navigation
", NOM_DU_SITE, NOM_DU_STAGIAIRE, MESSAGE_ACCEUIL);

    for ($i = 1; $i <= NB_SEMAINES; $i++) {
        $markdown .= 
"* [Semaine {$i}](semaine{$i}.html)
";
    }
    $markdown .= 
"
#### [Toutes les semaines](tout.html)

---
";
    $html_body = Michelf\Markdown::defaultTransform($markdown);
    return $html_body;
    
}

function genereToutBody($md_body) {
    $markdown = sprintf(
"# %s

## Toutes les semaines

---

[Retour à la page d'acceuil](index.html)

---

%s

---

[Haut de page](#)

[Retour à la page d'acceuil](index.html)

---

", NOM_DU_SITE, $md_body );
    $html_body = Michelf\Markdown::defaultTransform($markdown);
    return $html_body;
    
}

?>
