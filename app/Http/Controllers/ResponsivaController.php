<?php

namespace App\Http\Controllers;

use App\Mobile;
use Illuminate\Http\Request;
use PDF;
class ResponsivaController extends Controller{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(){
    }
    public $fecha;
    public $sucursal;
    public $modelo;
    public $serie;
    public $serieBase;
    public $entrega;

    public function createPdf(Request $request){
        /* $this->fecha = $request->fecha;
        $this->sucursal = $request->sucursal;
        $this->modelo = $request->modelo;
        $this->serie = $request->serie;
        $this->serieBase = $request->serieBase;
        $this->entrega = $request->entrega; */
        PDF::AddPage();

        // set cell padding
        PDF::setCellPaddings(1, 1, 1, 1);

        // set cell margins
        PDF::setCellMargins(1, 1, 1, 1);
        // set color for background
        PDF::SetFillColor(255, 255, 255);

        // set font
        PDF::SetFont('', '', 16);
        $header = '<p style="font-size:30px;" align="center">Carta responsiva de equipo RadioComunicación</p><br><br>';
        $asignacion = 'Fechas: __________________<br>Sucursal: __________________<br>Modelo: __________________<br>Serie: __________________<br>Serie Base: __________________<br>Entrega: __________________<br>';
        $devolucion = 'Fechas: __________________<br>Sucursal: __________________<br>Retirada: __________________<br><br>________________________<br>Nombre y firma<br><div style="text-align: left;">Notas de entrega:</div> <br><br><br><hr>';
        $msg = '<br><p align="justify">Por este medio hago constar que he recibido un teléfono móvil propiedad
        de la empresa Grupo Vizcarra. Debido a que es mi herramienta de trabajo, me comprometo 
        a darle el uso correspondiente a las actividades que me competen, y ante cualquier desperfecto, lo notificate.
        <br/>
        En caso de daños o extravío, me hare responsable de la multa que esto conlleve</p>';
        $firma = '<br><br><br><br><br><br><hr><div align="center">Nombre y firma</div>';
        /**
         * $x = 194
         * 
         */
        PDF::Image(__DIR__.'./resources/img/ic_empresa.png', 5, 5, 75, 65, '', '', '', false, 300, '', false);
        PDF::writeHTMLCell(110,'',84,'', $header, $border=0, $ln=1, $fill=0, $reseth=true, $align='', $autopadding=true);
        PDF::writeHTMLCell(90,'','','', $asignacion, $border=0, $ln=0, $fill=0, $reseth=true, $align='R', $autopadding=true);
        PDF::writeHTMLCell(90,'','','', $devolucion, $border=0, $ln=1, $fill=0, $reseth=true, $align='R', $autopadding=true);
        PDF::writeHTMLCell(190,'','','', $msg, $border=0, $ln=1, $fill=0, $reseth=true, $align='', $autopadding=true);
        PDF::writeHTMLCell(190,'','','', $firma, $border=0, $ln=1, $fill=0, $reseth=true, $align='', $autopadding=0);
        $nameFile = time().'.pdf';
        PDF::Output(__DIR__.'/../../../files/'.$nameFile, 'F');
        return response()->json(["archivo" => $nameFile]);
    }
}
