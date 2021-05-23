<?php

namespace App\Console\Commands;

use App\Models\Data;
use App\Models\Setting;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\URL;

class DarbuParbaude extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'check:jobs';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Checks if jobs done in the past 2 work days';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        // file_put_contents("../../../public/log.txt", date("d.m.Y")." Saka apstradi \n", FILE_APPEND);
        $this->info(date("d.m.Y")." Saka apstradi");
        $firma = Setting::where('name', 'firma')->get();
        $firmas_nosaukums = '';
        if(sizeof($firma) > 0){
            $firmas_nosaukums = $firma[0]->value;
        }
        $dayNum = date("N", strtotime(date("d.m.Y")));
        $nedelasSakums = date("Y-m-d", strtotime((1-$dayNum)." days", strtotime(date("d.m.Y"))));
        $rows = User::where('tips', '!=', 'system_admin')->where('tips', '!=', 'deactive')->get();
        foreach($rows as $rowLiet){
            $this->info($rowLiet->id);
            if(true || ($dayNum != 6 && $dayNum != 7)){
                $ilgumi = Data::where('daritaja_id', $rowLiet->id)->where('datums', $nedelasSakums)->get();
                $arrayIlgumsKopa = array(0,0,0,0,0,0,0);
                foreach($ilgumi as $rowIlgums){
                    $dienas = explode(";", $rowIlgums->ilgums);
                    for($i = 0; $i < 7; $i++){
                        $arrayIlgumsKopa[$i] += explode("=", $dienas[$i])[1];
                    }
                }
                if($dayNum == 2){
                    $pirmaDiena = $arrayIlgumsKopa[0];
                    $nedelasieprieks = date("Y-m-d", strtotime("-7 days", strtotime($nedelasSakums)));
                    $ilgumi = Data::where('daritaja_id', $rowLiet->id)->where('datums', $nedelasieprieks)->get();
                    $arrayIlgumsKopa = array(0,0,0,0,0,0,0);
                    foreach($ilgumi as $rowIlgums){
                        $dienas = explode(";", $rowIlgums->ilgums);
                        for($i = 0; $i < 7; $i++){
                            $arrayIlgumsKopa[$i] += explode("=", $dienas[$i])[1];
                        }
                    }
                    $arrayDati = array($pirmaDiena, $arrayIlgumsKopa[4]);
                }else if($dayNum == 1){
                    $nedelasieprieks = date("Y-m-d", strtotime("-7 days", strtotime(date("d.m.Y"))));
                    $ilgumi = Data::where('daritaja_id', $rowLiet->id)->where('datums', $nedelasieprieks)->get();
                    $arrayIlgumsKopa = array(0,0,0,0,0,0,0);
                    foreach($ilgumi as $rowIlgums){
                        $dienas = explode(";", $rowIlgums->id);
                        for($i = 0; $i < 7; $i++){
                            $arrayIlgumsKopa[$i] += explode("=", $dienas[$i])[1];
                        }
                    }
                    $arrayDati = array($arrayIlgumsKopa[4], $arrayIlgumsKopa[3]);
                }else{
                    $arrayDati = array($arrayIlgumsKopa[$dayNum-2], $arrayIlgumsKopa[$dayNum-3]);
                }
                if($arrayDati[0] == 0 && $arrayDati[1] == 0){
                    $to = $rowLiet->email;
                $subject = "Nav ievadīti dati ".$firmas_nosaukums." stundu uzskaites sistēmā";

                $message = "
                <html>
                <head>
                    <title>Nav ievadīti dati ".$firmas_nosaukums." stundu uzskaites sistēmā</title>
                </head>
                <body>
                    <header id='top_header'>
                        <h2 id='nosaukumsheading'>Stundu uzskaite</h2>
                    </header>
                    <div id='main_div'>
                        <p>
                            Pēdējās divās darba dienās neievadījāt nevienu stundu.<br>
                            Iespējams esat aizmirsis.<br>
                            <a href='".URL::to('/')."'>Uz sistēmu</a>
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

                Mail::html($message, function ($mail) use ($to, $subject) {
                    $mail->to($to)
                        ->subject($subject);
                });
                // file_put_contents("../../../public/log.txt", date("d.m.Y")." ".$rowLiet->vards." ".$rowLiet->uzvards."\n", FILE_APPEND);
                $this->info(date("d.m.Y")." ".$rowLiet->vards." ".$rowLiet->uzvards);
                }
            }
        }
    }
}
