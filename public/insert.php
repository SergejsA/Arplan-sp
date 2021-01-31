<?php

$db_conx = mysqli_connect("localhost", "u506865002_stundas", "Stundas10", "u506865002_stundas");

$string = "(1, 'PSKUS', 'closed', 1, 'Statika;Aprēķini;Modelis;KMD;Būvprojekta rasējumi;Ekspertīze;Administrēšana un sapulces;Koordinēšana;Printēšana;Citi'),
(2, 'Dūņu iela 3', 'closed', 1, 'Statika;Aprēķini;Modelis;KMD;Būvprojekta rasējumi;Ekspertīze;Administrēšana un sapulces;Koordinēšana;Printēšana;Citi'),
(3, 'AZUR', 'closed', 1, 'Statika;Aprēķini;Modelis;KMD;Būvprojekta rasējumi;Ekspertīze;Administrēšana un sapulces;Koordinēšana;Printēšana;Citi'),
(4, 'ALFA fasāde', 'closed', 1, 'Statika;Aprēķini;Modelis;KMD;Būvprojekta rasējumi;Ekspertīze;Administrēšana un sapulce;Koordinēšana;Printēšana;Citi'),
(5, 'BIM1', 'closed', 6, 'Statistika;Braucieni;Analīze;Dokumentu izstrāde;Apmācības;Intervijas'),
(6, 'Maskavas 464', 'active', 1, 'Statika;Aprēķini;Modelis;KMD;Būvprojekta rasējumi;Ekspertīze;Administrēšana un sapulce;Koordinēšana;Printēšana;Autoruzraudzība;Citi'),
(7, 'LF Bolderāja', 'active', 1, 'Statika;Aprēķini;Modelis;KMD;Būvprojekta rasējumi;Ekspertīze;Administrēšana un sapulce;Koordinēšana;Printēšana;Citi'),
(8, 'Ekspertīzes', 'active', 1, 'Statika;Aprēķini;Modelis;KMD;Būvprojekta rasējumi;Ekspertīze;Administrēšana un sapulce;Koordinēšana;Printēšana;Citi'),
(9, 'Icotton', 'closed', 1, 'Statika;Aprēķini;Modelis;KMD;Būvprojekta rasējumi;Ekspertīze;Administrēšana un sapulce;Koordinēšana;Printēšana;Citi'),
(10, 'Liedags_Ventspils', 'closed', 1, 'Statika;Aprēķini;Modelis;KMD;Būvprojekta rasējumi;Ekspertīze;Administrēšana un sapulce;Koordinēšana;Printēšana;Citi'),
(11, 'Nojume', 'closed', 1, 'Statika;Aprēķini;Modelis;KMD;Būvprojekta rasējumi;Ekspertīze;Administrēšana un sapulce;Koordinēšana;Printēšana;Citi'),
(12, 'ARDALA', 'closed', 1, 'Statika;Aprēķini;Modelis;KMD;Būvprojekta rasējumi;Ekspertīze;Administrēšana un sapulce;Koordinēšana;Printēšana;Citi'),
(13, 'TOP_Sigulda', 'closed', 1, 'Statika;Aprēķini;Modelis;KMD;Būvprojekta rasējumi;Ekspertīze;Administrēšana un sapulce;Koordinēšana;Printēšana;Citi'),
(14, 'DWG apmacibas', 'closed', 6, 'Apmācības;Dokumenti;Brauciens'),
(15, 'GRASSHOPPER', 'active', 6, 'Koda izstrāde'),
(16, 'Aizteres 2', 'closed', 1, 'Statika;Aprēķini;Modelis;KMD;Būvprojekta rasējumi;Ekspertīze;Administrēšana un sapulce;Koordinēšana;Printēšana;Citi'),
(17, 'GCB', 'closed', 6, 'Prototips;Biznesa plāns'),
(18, 'MORPHO', 'active', 6, 'Biznesa plāns;Informācijas izpēte;Dokumenti;Sistēmas testēšana;Mārketings;Produkta izstrāde;Sistēmas administrēšana'),
(19, 'Tyreso_DR', 'closed', 1, 'Statika;Aprēķini;Modelis;KMD;Būvprojekta rasējumi;Ekspertīze;Administrēšana un sapulce;Koordinēšana;Printēšana;Citi'),
(20, 'LAIMA', 'closed', 1, 'Statika;Aprēķini;Modelis;KMD;Būvprojekta rasējumi;Ekspertīze;Administrēšana un sapulce;Koordinēšana;Printēšana;Citi;Autoruzraudzība'),
(21, 'DTF_RTU', 'active', 1, 'Statika;Aprēķini;Modelis;KMD;Būvprojekta rasējumi;Ekspertīze;Administrēšana un sapulce;Koordinēšana;Printēšana;Citi;Autoruzraudzība'),
(22, 'iCotton', 'active', 1, 'Statika;Aprēķini;Modelis;KMD;Būvprojekta rasējumi;Ekspertīze;Administrēšana un sapulce;Koordinēšana;Printēšana;Citi'),
(23, 'Stokker_T_celtnis', 'closed', 1, 'Statika;Aprēķini;Modelis;KMD;Būvprojekta rasējumi;Ekspertīze;Administrēšana un sapulce;Koordinēšana;Printēšana;Citi'),
(24, 'Broma Brandstation', 'closed', 1, 'Statika;Aprēķini;Modelis;KMD;Būvprojekta rasējumi;Ekspertīze;Administrēšana un sapulce;Koordinēšana;Printēšana;Citi'),
(25, 'ANNA', 'active', 1, 'Statika;Aprēķini;Modelis;KMD;Būvprojekta rasējumi;Ekspertīze;Administrēšana un sapulce;Koordinēšana;Printēšana;Autoruzraudzība;Citi'),
(26, 'Malkas žāvētava', 'closed', 1, 'Statika;Aprēķini;Modelis;KMD;Būvprojekta rasējumi;Ekspertīze;Administrēšana un sapulce;Koordinēšana;Printēšana;Autoruzraudzība;Citi'),
(27, 'Water ', 'closed', 1, 'Statika;Aprēķini;Modelis;KMD;Būvprojekta rasējumi;Ekspertīze;Administrēšana un sapulce;Koordinēšana;Printēšana;Autoruzraudzība;Citi'),
(28, 'Dzelzavas 129', 'active', 1, 'Statika;Aprēķini;Modelis;GH modelis;KMD;Būvprojekta rasējumi;Ekspertīze;Administrēšana un sapulce;Koordinēšana;Printēšana;Autoruzraudzība;Citi'),
(29, 'MODUĻI', 'closed', 6, 'Statika;Aprēķini;Modelis;KMD;Būvprojekta rasējumi;Ekspertīze;Administrēšana un sapulce;Koordinēšana;Printēšana;Autoruzraudzība;Citi;GH modelis'),
(30, 'Kāpnes un laukums DR', 'closed', 6, 'Statika;Aprēķini;Modelis;KMD;Būvprojekta rasējumi;Ekspertīze;Administrēšana un sapulce;Koordinēšana;Printēšana;Autoruzraudzība;Citi;GH modelis'),
(31, 'Laumas piebūve 2019', 'closed', 6, 'Statika;Aprēķini;Modelis;KMD;Būvprojekta rasējumi;Ekspertīze;Administrēšana un sapulce;Koordinēšana;Printēšana;Autoruzraudzība;Citi;GH modelis'),
(32, 'Aitu krematorija  ', 'closed', 1, 'Statika;Aprēķini;Modelis;KMD;Būvprojekta rasējumi;Ekspertīze;Administrēšana un sapulce;Koordinēšana;Printēšana;Autoruzraudzība;Citi;GH modelis'),
(33, 'Pāvilostas kāpnes', 'closed', 6, 'Statika;Aprēķini;Modelis;KMD;Būvprojekta rasējumi;Ekspertīze;Administrēšana un sapulce;Koordinēšana;Printēšana;Autoruzraudzība;Citi;GH modelis'),
(34, 'Durbes iela 2', 'active', 1, 'Statika;Aprēķini;Modelis;KMD;Būvprojekta rasējumi;Ekspertīze;Administrēšana un sapulce;Koordinēšana;Printēšana;Autoruzraudzība;Citi;GH modelis'),
(35, 'Mežvidi_Pumac', 'active', 1, 'Statika;Aprēķini;Modelis;KMD;Būvprojekta rasējumi;Ekspertīze;Administrēšana un sapulce;Koordinēšana;Printēšana;Autoruzraudzība;Citi;GH modelis'),
(36, 'Meldru iela 8', 'active', 6, 'Statika;Aprēķini;Modelis;KMD;Būvprojekta rasējumi;Ekspertīze;Administrēšana un sapulce;Koordinēšana;Printēšana;Autoruzraudzība;Citi;GH modelis'),
(37, 'VENTSPILS SLIMNĪCA', 'active', 6, 'BEP dokuments;Kolīziju pārbaudes;Konsultācijas;Sapulces;'),
(38, 'Katlakalna 4b', 'active', 1, 'Statika;Aprēķini;Modelis;KMD;Būvprojekta rasējumi;Ekspertīze;Administrēšana un sapulce;Koordinēšana;Printēšana;Autoruzraudzība;Citi;GH modelis'),
(39, 'RAUŠI', 'active', 6, 'Statika;Aprēķini;Modelis;KMD;Būvprojekta rasējumi;Ekspertīze;Administrēšana un sapulce;Koordinēšana;Printēšana;Autoruzraudzība;Citi;GH modelis'),
(40, 'ALŪKSNES VISTAS', 'active', 6, 'Statika;Aprēķini;Modelis;KMD;Būvprojekta rasējumi;Ekspertīze;Administrēšana un sapulce;Koordinēšana;Printēšana;Autoruzraudzība;Citi;GH modelis'),
(41, 'Ulbrokas barības nol.', 'active', 1, 'Statika;Aprēķini;Modelis;KMD;Būvprojekta rasējumi;Ekspertīze;Administrēšana un sapulce;Koordinēšana;Printēšana;Autoruzraudzība;Citi;GH modelis'),
(43, 'TURAIDAS IELA 24', 'active', 6, 'Statika;Aprēķini;Modelis;KMD;Būvprojekta rasējumi;Ekspertīze;Administrēšana un sapulce;Koordinēšana;Printēšana;Autoruzraudzība;Citi;GH modelis');";


$a = explode("\n", $string);
foreach($a as $row){
    echo var_dump($row);
    $dati = explode(",", substr($row, 1, strlen($row)));
    $id = $dati[0];
    $nosaukums = trim($dati[1]);
    $stat = trim($dati[2]);
    $pievinotaja_id = trim($dati[3]);
    $darbi = trim($dati[4]);
    $sql = "INSERT INTO projects (id, nosaukums, stat, pievienotaja_id, darbi, created_at, updated_at) VALUES ($id, '".$nosaukums."', '".$stat."', $pievinotaja_id, '".$darbi."', null, null)";
    echo var_dump($sql)."\n";
    $query = mysqli_query($db_conx, $sql);
    echo $query;
}


?>
