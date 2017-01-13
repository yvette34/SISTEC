<table border="1">
    <tr><td colspan="2" align="center">SUBCOORDINACIÓN DE INFORMATICA</td></tr>
    <tr><td colspan="2" align="center">Reporte de Servicio y/o Falla</td></tr>
    <tr><td align="left">Folio:{{ $s->folio }}</td><td align="right">Fecha: {{ $s->fecha_reporte }}</td></tr>
</table>
<table border="1">
    <tr><td align="center" colspan="4">DATOS DEL SOLICITANTE (LLENAR ÁREA SOLICITANTE)</td></tr>
    <tr><td colspan="1" align="right">Nombre de quien reporta: </td><td colspan="3" align="center">{{ User::find($s->id_usuario)->nombres  }} {{ User::find($s->id_usuario)->apellidos }}</td></tr>
    <tr><td colspan="1" align="right">Area: </td><td colspan="3" align="center">{{ Areas::find(User::find($s->id_usuario)->area->id)->nombre }}</td></tr>
    <tr><td colspan="1" align="right">Usuario del equipo: </td><td colspan="3" align="center">{{ User::find($s->usuario_equipo)->nombres  }} {{ User::find($s->usuario_equipo)->apellidos }}</td></tr>
    <tr><td colspan="1" align="right">Servicio o Falla Reportado: </td><td colspan="3" align="center">{{ $s->falla_reportada }}</td></tr>
</table>
<table border="1">
    <tr>
        <td colspan="" align="center">Asistencia Tecnica<br>Problemas que presenta el equipo:</td>
        <td colspan="" align="center">Revisión y/o Mantenimiento <br> Dispositivos con problemas:</td>
        <td colspan="" align="center">Instalar / Reinstalar Programas <br> Programas a instalar:</td>
    </tr>
    <tr>
        <td>
            <?php 
            foreach ($s->asistencias as $asistencia) {
                ?>
                * {{ $asistencia->problema }} <br>
                <?php } ?>
        </td>
        <td>
            <?php 
            foreach ($s->revisiones as $revision) {
                ?>
                * {{ $revision->dispositivo }} <br>
                <?php } ?>
        </td>
        <td>
            <?php 
            foreach ($s->programas as $programa) {
                ?>
                * {{ $programa->programa }} <br>
                <?php } ?>
        </td>
    </tr>
</table>
<table border="1">
    <tr><td colspan="8" align="center">DATOS DEL EQUIPO</td></tr>
    <tr>
        <td>No.Inventario</td><td colspan="3">{{ Equipo::find($s->id_equipo)->no_inventario }}</td>
        <td>Sistema Operativo</td><td colspan="3">{{ Equipo::find($s->id_equipo)->caracteristicas[0]->so }}</td>
    </tr>
    <tr>
        <td>Marca</td><td colspan="3">{{ Equipo::find($s->id_equipo)->marca }}</td>
        <td>RAM</td><td colspan="3">{{ Equipo::find($s->id_equipo)->caracteristicas[0]->ram }} GB</td>
    </tr>
    <tr>
        <td>Modelo</td><td colspan="3">{{ Equipo::find($s->id_equipo)->modelo }}</td>
        <td>Disco Duro</td><td colspan="3">{{ Equipo::find($s->id_equipo)->caracteristicas[0]->disco_duro }} GB</td>
    </tr>
    <tr>
        <td>Descripción</td><td colspan="7">{{ Equipo::find($s->id_equipo)->descripcion }}</td>
    </tr>
</table>
<table border="1">
    <tr><td align="center">DIAGNOSTICO</td></tr>
    <tr><td>{{ $s->diagnostico }}</td></tr>
</table>
<table border="1">
    <tr><td colspan="4" align="center">REFACCIONES</td></tr>
    <tr><td colspan="1">Cantidad</td><td colspan="3">Descripción</td></tr>
    <?php
    foreach ($s->refacciones as $refaccion) {
        ?>
            <tr><td colspan="1">{{ $refaccion->cantidad }}</td><td colspan="3">{{ $refaccion->descripcion }}</td></tr>
        <?php
    }
    ?>
</table>
<table border="1">
    <tr><td colspan="2" align="center">DESCRIPCIÓN DE LA SOLUCION:</td></tr>
    <tr><td>Fecha Solución:</td><td>Descripción</td></tr>
    <tr><td>{{ $s->soluciones[0]->fecha }}</td><td rowspan="5">{{ $s->soluciones[0]->descripcion }}</td></tr>
    <tr><td>Trabajo Real(H:M:S):</td></tr>
    <tr><td>{{ $s->soluciones[0]->trabajo_real }}</td></tr>
    <tr><td>Estatus del reporte:</td></tr>
    <?php
    $estado = "";
    switch ($s->estado) {
        case '0':
            $estado = "Por Atender";
            break;
        case '1':
            $estado = "En Reparación";
            break;
        case '2':
            $estado = "Terminado";
            break;
        case '3':
            $estado = "En Espera de Refacciones";
            break;
    }
    ?>
    <tr><td>{{ $estado }}</td></tr>
</table>
<p><strong>NOTA: </strong>La subcoordinación de informatica no se hace responsable de; si hay perdida de información a causa de algún servicio de mantenimiento preventivo o correctivo, por virus o fallas de hardware.</p>
<p align="center"><strong><u>RECUERDA QUE RESPALDAR LA INFORMACIÓN DEL EQUIPO ES RESPONSABILIDAD DEL USUARIO</u></strong></p>
<table>
    <tr>
        <td align="center">
            <p>___________________________________________</p>
            <p>USUARIO DEL EQUIPO</p>
            <p>(Nombre y Firma)</p>
        </td>
        <td align="center">
            <p>___________________________________________</p>
            <p>SERVICIO REALIZADO POR</p>
            <p>(Nombre y Firma)</p>
        </td>
    </tr>
</table>
<?php
/*
	//	Requerimos la calse Fpdf
use Maxxscho\LaravelTcpdf\LaravelTcpdf as baseTcpdf;
	//	Creamos nuestra clase personlaizada para asigaar header y footer
class PdfServicio extends baseTcpdf{
	public function Header() {
        // Logo
        $image_file = public_path().'\img\he_membretada.png';
        $this->Image($image_file, 10, 10, 15, '', 'JPG', '', 'T', false, 300, '', false, false, 0, false, false, false);
        // Set font
        $this->SetFont('helvetica', 'B', 20);
        // Title
        $this->Cell(0, 15, '<< TCPDF Example 003 >>', 0, false, 'C', 0, '', 0, false, 'M', 'M');
    }

    // Page footer
    public function Footer() {
        // Position at 15 mm from bottom
        $this->SetY(-15);
        // Set font
        $this->SetFont('helvetica', 'I', 8);
        // Page number
        $this->Cell(0, 10, 'Page '.$this->getAliasNumPage().'/'.$this->getAliasNbPages(), 0, false, 'C', 0, '', 0, false, 'T', 'M');
    }
}
// create new PDF document
$pdf = new PdfServicio(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

// set document information
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('Nicola Asuni');
$pdf->SetTitle('TCPDF Example 003');
$pdf->SetSubject('TCPDF Tutorial');
$pdf->SetKeywords('TCPDF, PDF, example, test, guide');

// set default header data
$pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, PDF_HEADER_TITLE, PDF_HEADER_STRING);

// set header and footer fonts
$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

// set default monospaced font
$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

// set margins
$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

// set auto page breaks
$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

// set image scale factor
$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

// set some language-dependent strings (optional)
if (@file_exists(dirname(__FILE__).'/lang/eng.php')) {
    require_once(dirname(__FILE__).'/lang/eng.php');
    $pdf->setLanguageArray($l);
}

// ---------------------------------------------------------

// set font
$pdf->SetFont('times', 'BI', 12);

// add a page
$pdf->AddPage();

// set some text to print
$txt = "
TCPDF Example 003

Custom page header and footer are defined by extending the TCPDF class and overriding the Header() and Footer() methods.
";

// print a block of text using Write()
$pdf->Write(0, $txt, '', 0, 'C', true, 0, false, false, 0);

// ---------------------------------------------------------

//Close and output PDF document
$pdf->Output('example_003.pdf', 'I');
*/
?>