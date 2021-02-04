<?php
// include_once("../db_conx.php");
// include_once("../lapaURL.php");
$db_conx = mysqli_connect('localhost', 'root', '', 'su');
$dayNum = date("N", strtotime(date("d.m.Y")));
$nedelasSakums = date("Y-m-d", strtotime((1-$dayNum)." days", strtotime(date("d.m.Y"))));
$sql = "SELECT email, id, vards, uzvards FROM users WHERE tips='admin' OR tips='user' ORDER BY id ASC";
$query = mysqli_query($db_conx, $sql);
while($rowLiet = mysqli_fetch_row($query)){
    //echo $rowLiet[1]."\n";
    if($dayNum != 6 && $dayNum != 7){
        $sqlDati = "SELECT ilgums FROM data WHERE daritaja_id=".$rowLiet[1]." AND datums='".$nedelasSakums."'";
        $queryDati = mysqli_query($db_conx, $sqlDati);
        $arrayIlgumsKopa = array(0,0,0,0,0,0,0);
        while($rowIlgums = mysqli_fetch_row($queryDati)){
            $dienas = explode(";", $rowIlgums[0]);
            for($i = 0; $i < 7; $i++){
                $arrayIlgumsKopa[$i] += explode("=", $dienas[$i])[1];
            }
        }
        if($dayNum == 2){
            $pirmaDiena = $arrayIlgumsKopa[0];
            $nedelasieprieks = date("Y-m-d", strtotime("-7 days", strtotime(date("d.m.Y"))));
            $sqlDati = "SELECT ilgums FROM data WHERE daritaja_id=".$rowLiet[1]." AND datums='".$nedelasieprieks."'";
            $queryDati = mysqli_query($db_conx, $sqlDati);
            $arrayIlgumsKopa = array(0,0,0,0,0,0,0);
            while($rowIlgums = mysqli_fetch_row($queryDati)){
                $dienas = explode(";", $rowIlgums[0]);
                for($i = 0; $i < 7; $i++){
                    $arrayIlgumsKopa[$i] += explode("=", $dienas[$i])[1];
                }
            }
            $arrayDati = array($pirmaDiena, $arrayIlgumsKopa[4]);
        }else if($dayNum == 1){
            $nedelasieprieks = date("Y-m-d", strtotime("-7 days", strtotime(date("d.m.Y"))));
            $sqlDati = "SELECT ilgums FROM data WHERE daritaja_id=".$rowLiet[1]." AND datums='".$nedelasieprieks."'";
            $queryDati = mysqli_query($db_conx, $sqlDati);
            $arrayIlgumsKopa = array(0,0,0,0,0,0,0);
            while($rowIlgums = mysqli_fetch_row($queryDati)){
                $dienas = explode(";", $rowIlgums[0]);
                for($i = 0; $i < 7; $i++){
                    $arrayIlgumsKopa[$i] += explode("=", $dienas[$i])[1];
                }
            }
            $arrayDati = array($arrayIlgumsKopa[4], $arrayIlgumsKopa[3]);
        }else{
            $arrayDati = array($arrayIlgumsKopa[$dayNum-2], $arrayIlgumsKopa[$dayNum-3]);
        }
        if($arrayDati[0] == 0 && $arrayDati[1] == 0){
            // echo "<p>".$rowLiet[1]."</p>";
            $to = $rowLiet[0];
        $subject = "Nav ievadīti dati Sitera stundu uzskaites sistēmā";

        $message = "
        <html>
        <head>
            <title>Nav ievadīti dati Sitera stundu uzskaites sistēmā</title>
        </head>
        <body>
            <header id='top_header'>
                    <img src='http:://su.sitera.lv/logo.jpg' style='float:left;' height='75px'><h2 id='nosaukumsheading'>Stundu uzskaite</h2>
            </header>
            <div id='main_div'>
                <p>
                    Pēdējās divās darba dienās neievadījāt nevienu stundu.<br>
                    Iespējams esat aizmirsis.<br>
					<a href='http://su.sitera.lv/'>su.sitera.lv</a>
                </p>
            </div>
        </body>
        <style>
                *{
                    margin: 0;
                    padding: 0;
                }
                #top_header{
                    height: 75px;
                    background-color: #f89627;
                }
                #nosaukumsheading{
                    float:left;
                    margin-left:20px;
                    position: relative;
                    top:25px;
                }
                #main_div{
                    width:70%;
                    margin:50px auto;
                }
                P{
                    text-indent: 20px;
                }
            </style>
    </html>
        ";


        // Always set content-type when sending HTML email
        $headers = "MIME-Version: 1.0" . "\r\n";
        $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";

        // More headers
        $headers .= 'From: <no-reply@sitera.lv>' . "\r\n";
        /*$headers .= 'Cc: myboss@example.com' . "\r\n";*/
        mail($to,$subject,$message,$headers);
    //     file_put_contents("log.txt", date("d.m.Y")." ".$rowLiet[2]." ".$rowLiet[3]."\n", FILE_APPEND);
        }
    }
}

?>
