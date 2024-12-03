<?php

//die(var_dump($data));
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
        $this->Cell(290,10, utf8_decode('Data de impressão:' . formatadata($currentdate)),0,0,'C');                
        // Arial bold 15
        $this->SetFont('Arial','B',15);    
        // Title
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




$pdf->SetFont('Arial','B',12);

if($data['escola']=='Todas'){
  $pdf->SetFont('Arial','B',8);  
  $colunas =array("N","Nome","Escola","Ano"); 
  $larguracoll = array(1 => 5, 2 => 140, 3 => 100, 4 => 35);  
  $left = 5; 
  $pdf->AddPage('L');
  $pdf->Ln();
  $pdf->Cell(50, 5,utf8_decode('Relatório: Servidores que responderam o questionário mas não responderam a especialização'), 0, 1, 'L');
  $pdf->Cell(50, 5, 'ESCOLA: ' . utf8_decode('Todas'), 0, 1, 'L');  
  $i=0;
  //primeira linha da tabela com os campos
  foreach($colunas as $coluna){
    $i++;           
    $pdf->Cell($larguracoll[$i],$left,utf8_decode($coluna),1);    
  }
  $pdf->Ln(); 
  $count=0;
  if($data['result']){
    foreach($data['result'] as $row){ 
      $count++;
      $pdf->Cell($larguracoll[1],5,utf8_decode($count),1,0,'C'); 
      $pdf->Cell($larguracoll[2],5,utf8_decode($row->nome),1,0,'C');     
      $pdf->Cell($larguracoll[3],5,utf8_decode($row->escola),1,0,'C');
      $pdf->Cell($larguracoll[4],5,utf8_decode($row->ano),1,0,'C');
      $pdf->Ln(); 
    } 
  } else {
    $data['erro'] = "Sem dados para emitir";
    $data['link'] = "/buscadadosescolars";
    $this->view('relatorios/erroAoGerarRelatorio', $data);
  }  
} else {

  $pdf->SetFont('Arial','B',8);  
  $colunas =array("N","Nome","Ano");  
  $larguracoll = array(1 => 5, 2 => 150, 3 => 35);  
  $left = 5; 
  $pdf->AddPage('P');
  $pdf->Ln();
  $pdf->Cell(50, 5,utf8_decode('Relatório: Servidores que responderam o questionário mas não responderam a especialização'), 0, 1, 'L');
  $pdf->Cell(50, 5, 'ESCOLA: ' . utf8_decode($data['escola']->nome), 0, 1, 'L'); 
  $i=0;
  //primeira linha da tabela com os campos
  foreach($colunas as $coluna){
    $i++;           
    $pdf->Cell($larguracoll[$i],$left,utf8_decode($coluna),1);    
  }
  $pdf->Ln(); 
  $count=0;
  if($data['result']){
    foreach($data['result'] as $row){ 
      $count++;
      $pdf->Cell($larguracoll[1],5,utf8_decode($count),1,0,'C'); 
      $pdf->Cell($larguracoll[2],5,utf8_decode($row->nome),1,0,'C');     
      $pdf->Cell($larguracoll[3],5,utf8_decode($row->ano),1,0,'C');
      $pdf->Ln(); 
    } 
  } else {
    $data['erro'] = "Sem dados para emitir";
    $data['link'] = "/buscadadosescolars";
    $this->view('relatorios/erroAoGerarRelatorio', $data);
  }   
} 

if($pdf->Output())
{
    $pdf->Output("Relatorio.pdf",'I');  
}
else{                
    echo $data['erro'] = $error;
    $this->view('relatorios/erroAoGerarRelatorio',$data);
    
}   
           
?>

