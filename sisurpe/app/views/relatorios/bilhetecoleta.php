<?php
//debug($data);
//die(var_dump($data['bilheteFolha']));
require APPROOT . '/inc/fpdf/fpdf.php'; 



class PDF extends FPDF
{  
    
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

$pdf->AddPage('P');

$tamanhosUniformes = getArrayTamanhos();
$tamanhosCalcados = getTamanhosCalcados();
$linhas = getLinhas();
$newPage = 0;
if($data['result']){  
  foreach($data['result'] as $row){ 
    if($newPage == $data['bilheteFolha']){
      $newPage=0;
      $pdf->AddPage('P');
    }
    $newPage++;
    $pdf->SetFont('Arial','B',10);   
    $pdf->Cell(50, 5, 'ESCOLA: ' . utf8_decode($data['escola']), 0, 1, 'L');           
    $pdf->Cell(50, 5, 'Turma: ' . utf8_decode($row['turma']), 0, 1, 'L');
    $pdf->Cell(50, 5, 'Turno: ' . utf8_decode($row['turno']), 0, 1, 'L');
    $pdf->Cell(50, 5, 'Aluno: ' . utf8_decode($row['nome']), 0, 1, 'L');
    $pdf->MultiCell(190,5,utf8_decode($data['texto']),false);
    
    $pdf->SetFont('Arial','B',8); 
    
    $pdf->Cell(50, 5, utf8_decode('Tamanho do Kit de inverno: '), 0, 1, 'L');
    foreach($tamanhosUniformes as $tamanho){
      $pdf->Cell(9,5,utf8_decode($tamanho),1,0,'C');
    }
    $pdf->Ln();  
    $pdf->Cell(50, 5, utf8_decode('Tamanho do Kit de Verão: '), 0, 1, 'L');
    foreach($tamanhosUniformes as $tamanho){
      $pdf->Cell(9,5,utf8_decode($tamanho),1,0,'C');
    }  
    $pdf->Ln();  
    $pdf->Cell(50, 5, utf8_decode('Tamanho do Calçado: '), 0, 1, 'L');
    foreach($tamanhosCalcados as $tamanho){
      $pdf->Cell(9,5,utf8_decode($tamanho),1,0,'C');
    }
    $pdf->Ln();  
    $pdf->Cell(50, 5, utf8_decode('Transporte Escolar - Linhas que o aluno utiliza: '), 0, 1, 'L');
    $i=0;
    foreach($linhas as $linha){
      if($i == 7){
        $pdf->Ln(); 
        $i = 0;
      }
      $pdf->Cell(25,5,utf8_decode($linha),1,0,'C');
      $i++;
    }    
    $i++;
    $pdf->Ln(); 
    $pdf->Ln();
    $pdf->Cell(50, 5, utf8_decode('Nome do responável pelo preenchimento: ___________________________________________________.Assinatura_________________________'), 0, 1, 'L');    
    $pdf->Ln();   
  }
} else {
  $data['erro'] = "Sem dados para emitir";
  $data['link'] = "/geradordebilhetes";
  $this->view('relatorios/erroAoGerarRelatorio', $data);
}   

$pdf->Output("bilhetes.pdf", "D");

?>

