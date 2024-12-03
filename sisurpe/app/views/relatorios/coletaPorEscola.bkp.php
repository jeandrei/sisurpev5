<?php

die(var_dump($data));
require APPROOT . '/inc/fpdf/fpdf.php'; 



class PDF extends FPDF
{            
            
    // Page header
    function Header()
    {   $currentdate = date("d-m-Y");
        // Logo
        $this->Image(APPROOT . '/views/relatorios/logo.png',10,6,110);
        // Date
        $this->SetFont('Arial','B',10);                 
        $this->Cell(330,10, utf8_decode('Data de impressão:' . formatadata($currentdate)),0,0,'C');                
        // Arial bold 15
        $this->SetFont('Arial','B',15);    
        // Title
        $this->Ln(20);
        // Move to the right                
        $this->Cell(180,0, utf8_decode("Coleta por Escola"),0,0,'C');
        // Line break
        $this->Ln(20);                
    }

    // Page footer
    function Footer()
    {
        // Position at 1.5 cm from bottom
        $this->SetY(-15);
        // Arial italic 8
        $this->SetFont('Arial','I',8);
        // Page number
        $this->Cell(0,10,utf8_decode('Página ').$this->PageNo(),0,0,'C');                
    }
}
           
// Instanciation of inherited class
$pdf = new PDF();
//define o tipo e o tamanho da fonte                                  
$pdf->SetFont('Arial','B',8);
//defino as colunas do relatório
$colunas =array("N","Nome do Aluno","Sexo","Kit Inverno","Kit Verão","Calçado");
//largura das colunas
$larguracoll = array(1 => 5, 2 => 100, 3 => 30, 4 => 20, 5 => 20, 6 => 20);
//tamanho da fonte
$left = 5; 

$pdf->AddPage('L');
$pdf->Ln();

           

if($data['escola']){ 
  $pdf->SetFont('Arial','B',12);
  $pdf->Cell(50, 5, 'ESCOLA: ' . utf8_decode($data['escola']->nome), 0, 1, 'L');

  foreach($data['turmas'] as $row){       
    $pdf->SetFont('Arial','B',12);
    $pdf->Cell(50, 5, 'Turma: ' . utf8_decode($row['turma']), 0, 1, 'L');
    $i=0;
    foreach($colunas as $coluna){
      $i++;
      $pdf->SetFont('Arial','B',7);  
      $pdf->Cell($larguracoll[$i],10,utf8_decode($coluna),1);
    }
    $pdf->Ln();              
  
    if($row['coleta']){
      $pdf->SetFont('Arial','B',8);
      $count = 0;
      foreach($row['coleta'] as $coleta){ 
        //debug($coleta) ;
        $count++;
        $pdf->Cell($larguracoll[1],5,utf8_decode($count),1,0,'C'); 
        $pdf->Cell($larguracoll[2],5,utf8_decode($coleta->coletaNome),1,0,'C'); 
        $pdf->Cell($larguracoll[3],5,utf8_decode($coleta->sexo),1,0,'C');     
        $pdf->Cell($larguracoll[4],5,utf8_decode($coleta->kit_inverno),1,0,'C');
        $pdf->Cell($larguracoll[5],5,utf8_decode($coleta->kit_verao),1,0,'C');
        $pdf->Cell($larguracoll[6],5,utf8_decode($coleta->tam_calcado),1,0,'C');
        $pdf->Ln();                       
      }
    } else {
      $pdf->Cell(50, 50, 'Turma sem Coleta', 0, 1, 'C');
    }

    $pdf->SetFont('Arial','B',7);

    $pdf->Cell(20,5,utf8_decode('Inverno'),1,0,'C');
    $arrayTamanhos = getArrayTamanhos();
    foreach($arrayTamanhos as $tamanho){
      $pdf->Cell(14,5,utf8_decode('('.$tamanho . '): ' . $row['kit_inverno'][$tamanho]),1,0,'C');
    }  
    
    $pdf->Ln();

    $pdf->Cell(20,5,utf8_decode('Verão'),1,0,'C');
    $arrayTamanhos = getArrayTamanhos();
    foreach($arrayTamanhos as $tamanho){
      $pdf->Cell(14,5,utf8_decode('('.$tamanho . '): ' . $row['kit_verao'][$tamanho]),1,0,'C');
    }  
   

    $pdf->Ln();
    
    $pdf->Cell(20,5,utf8_decode('Calçados'),1,0,'C');
    $arrayTamanhos = getTamanhosCalcados();
    foreach($arrayTamanhos as $tamanho){
      $pdf->Cell(12,5,utf8_decode('('.$tamanho . '): ' . $row['tam_calcado'][$tamanho]),1,0,'C');
    }  

    $pdf->AddPage('L');  
    $pdf->Ln();
    $pdf->Cell(50, 15, 'Totais Gerais da Escola', 0, 1, 'L');
    /* totais da escola */
    $pdf->Cell(20,5,utf8_decode('Inverno'),1,0,'C');
    $arrayTamanhos = getArrayTamanhos();
    foreach($arrayTamanhos as $tamanho){  
      $pdf->Cell(14,5,utf8_decode('('.$tamanho . '): ' . $data['result'][0]['totalUniforme']['kit_inverno'][$tamanho]),1,0,'C');
    }  

    $pdf->Ln();

    $pdf->Cell(20,5,utf8_decode('Verao'),1,0,'C');
    $arrayTamanhos = getArrayTamanhos();
    foreach($arrayTamanhos as $tamanho){  
      $pdf->Cell(14,5,utf8_decode('('.$tamanho . '): ' . $data['result'][0]['totalUniforme']['kit_verao'][$tamanho]),1,0,'C');
    }  

    $pdf->Ln();
    $pdf->Ln();
    $pdf->Cell(20,5,utf8_decode('Calçados'),1,0,'C');
    $pdf->Ln();
    $arrayTamanhos = getTamanhosCalcados();
    foreach($arrayTamanhos as $tamanho){  
      $pdf->Cell(13,5,utf8_decode('('.$tamanho . '): ' . $data['result'][0]['totalCalcado']['tam_calcado'][$tamanho]),1,0,'C');
    } 
  }
} else {
  $data['erro'] = "Sem dados para emitir";
  $data['link'] = "/buscadadosescolars";
  $this->view('relatorios/erroAoGerarRelatorio', $data);
}    

if($pdf->Output())
{
    $pdf->Output("Relatorio.pdf",'I');  
}
else {                
    $data['erro'] = $error;
    $this->view('relatorios/erroAoGerarRelatorio',$data);    
}   
           
?>

