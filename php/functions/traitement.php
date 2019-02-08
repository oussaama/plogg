<?php
header("Content-Type: application/json");
//variables
$dateDebut = date_create($_POST['startDate']);
$dateFin = date_create($_POST['endDate']);
$totalMax = $_POST['total'];
$totalMin = $_POST['total'] * 0.99;
$baseline = $_POST['baseline'];
$jourNon = 0;
$random = 0;
$quantity = intval(100 / $baseline);

//marge de temps
$TempsRestant = date_diff($dateDebut, $dateFin);

//objet date debut
$ObjectDeb = new DateTime($dateDebut->format('Y-m-d'));

//tableau de retour (resultat)
$arrangeDate = [];

//remplissage les dates dans tableau
for ($i = 0; $i <= $TempsRestant->d; $i++) {
    //si la premiere element inseret le date début sinon augmenter chaque fois par jour et inserer
    if ($i == 0) {
        $ObjectDeb->add(new DateInterval('P0D'));
    } else {
        $ObjectDeb->add(new DateInterval('P1D'));
    }
    //ajouter le date bien formé dans element date du tableau
    $arrangeDate[$i]['date'] = $ObjectDeb->format('Y-m-d');
    //test si weekend ajouter 0 dans element value ou null dans cas contraire
    if (isTodayWeekend($ObjectDeb)) {
        $arrangeDate[$i]['value'] = number_format(0, 2, '.', '');
    } else {
        $arrangeDate[$i]['value'] = null;
        $jourNon++;
    }
}

//remplisage le tableau par de valeur aléatoire
for ($i = 0; $i < count($arrangeDate); $i++) {
    //si le baseline est saturé
    if ($baseline == 100) {
        if ($arrangeDate[$i]['value'] == null) {
            $arrangeDate[$i]['value'] = number_format($totalMax / $jourNon, 2, '.', '');
        }
    } else {
        if ($arrangeDate[$i]['value'] == null) {
            //parcour de n eme fois selon le degrée de aléatoire
            for ($j = 0; $j < $quantity; $j++) {
                //si la derniére element de tableau 
                if ($i == count($arrangeDate)-1) {
                    $random = rand($totalMin * 100, $totalMax * 100) / 100;   
                } else {
                    $random = rand(1, $totalMax * 100) / 100;
                }
            }
            //remplisage la valeur final d'aléatoire dans element value du tableau
            $arrangeDate[$i]['value'] = $random;
            if($random<0){
                $arrangeDate[$i]['value'] = 0.01;
                $t=$i;
                while($arrangeDate[$t]['value'] < 0.01){
                    $t++;
                }
                $arrangeDate[$t]['value'] -= 0.01;
            }
            $totalMax -= $random;
            $totalMin -= $random;
        }
    }
}

//function test si le jour est un weekday return false else true 
function isTodayWeekend($ObjectDeb)
{
    return $ObjectDeb->format('N') > 5;
}

//envoyer le resulat sous format JSON
echo json_encode($arrangeDate);