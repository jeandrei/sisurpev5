<?php
//debug($data);

require APPROOT . '/inc/fpdf/fpdf.php'; 

class PDF extends FPDF
{
        // Page header
        function Header()
        {   $currentdate = date("d-m-Y");                
            $this->SetFont('Arial','B',10); 
            $this->Cell(120);
            $this->Cell(260,10, utf8_decode('Data de emissão: ' . $currentdate),0,0,'C');                
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
            $this->Cell(0,10,utf8_decode('Penha/SC '),0,0,'C');                
        }

        function BasicTable($header, $data)
        {
          // Header
          foreach($header as $col)
              $this->Cell(40,7,$col,1);
          $this->Ln();
          // Data
          foreach($data as $row)
          {
              foreach($row as $col)
                  $this->Cell(40,6,$col,1);
              $this->Ln();
          }
        }

}

          /* se o usuário não tem presença no curso nem carrego o certificado */
          if(!$data['presencas']){
            die('Você não tem presença neste curso!');
          }




          $total_ch = 0;
          if($data['temas']){            
            /* Pego a carga horária total do curso somando todas as ch dos temas caso não tenha temas defino a carga horária como zero */            
            foreach($data['temas'] as $row){              
              $total_ch = $total_ch + $row->carga_horaria;
            }  
          } else {
            die('Este curso foi criado sem temas e carga horária. Entre em contato com a secretaria de educação para  correção.');
          }


          /* pego a frequência do usuário*/
          $frequencia = 0;
          if($data['presencas']){                  
            foreach($data['presencas'] as $row){
              $frequencia = $frequencia + $row->carga_horaria_tema;
            }
            $cargaHorariaPresenca = $frequencia;
            /* faço a regra de 3 para ter o total da frequência em relação ao total da carga horária */           
            $frequencia = ($frequencia * 100)/$total_ch;
          }

            
          $pdf = new PDF();     
          //$pdf->Image(APPROOT . '/views/relatorios/logo.png',10,6,110);             
          
          /* Aqui é a imagem 
              Para alterar o modelo, na pasta relatorios eu salvei um 
              powerpoint. é só abrir alterar e salvar como jpg
              por fim substituir a imagem certificado.jpg da pasta relatorios              
          */
          
          if($data['curso']->certificado == ''){
            $image =  CERTPADRAO;
          } else {
            $image = $data['curso']->certificado;
          }
          
         
                    
          $pdf->SetFont('Arial','B',8);
          $pdf->AddPage('L');              
          $pdf->Image($image,0,0,297,210);                  
          $pdf->SetTextColor(14,63,160);  
          $pdf->SetFont('Arial','B',20);           
          $pdf->Ln(); 
          $pdf->MultiCell(0,30,'CERTIFICAMOS QUE',0,'C');
          $pdf->AddFont('Birthstone','','Birthstone-Regular.php');
          $pdf->SetFont('Birthstone','',41);  
          $pdf->SetTextColor(14,63,160);                        
          $pdf->MultiCell(270,0,utf8_decode($data['usuario']->name),0,'C');
          $pdf->SetFont('Arial','',20);    
          
          $pdf->MultiCell(270,10,utf8_decode('
          Portador do CPF: ' . $data['usuario']->cpf . ' participou como aluno(a) do curso de ' . strtoupper($data['curso']->nome_curso) . ', realizada em Penha/SC, no período de ' . formatadata($data['curso']->data_inicio) . ' a ' . formatadata($data['curso']->data_termino). ', com carga horária de ' . $total_ch . ' Horas.'),0, false); 
          
          
          /* PÁGINA 02 */
          $pdf->AddPage('L');   


          /* Se tiver temas cadastrados */
          if($data['temas']){

            /* título da pagina */
            $pdf->MultiCell(0,10,'TEMAS',0,'C');
          
            $pdf->SetFont('Arial','B',12);
           
            $total_ch = 0;
            foreach($data['temas'] as $row){             
              $text  = "<Tema:> " . $row->tema . "\n";
              $text  .= "<Formador:> " . $row->formador . "\n";
              $text  .= "<Carga Horária:> " . $row->carga_horaria . " Horas\n\n";
              //ESSA FUNÇÃO WRITE TEXT EU ACHEI NA INTERNET. ESTÁ EM INC,FPDF,FPDF.PHP              
              $pdf->WriteText(utf8_decode(mb_strtoupper($text)));
              $total_ch = $total_ch + $row->carga_horaria;
            }
            

            //***********PRESENÇA****** */
              $frequencia = 0;
              if($data['presencas']){                  
                foreach($data['presencas'] as $row){
                  $frequencia = $frequencia + $row->carga_horaria_tema;
                }
                /* faço a regra de 3 para ter o total da frequência em relação ao total da carga horária */
                $frequencia = ($frequencia * 100)/$total_ch;
              }
              
            /**************** */
           
            $pdf->Ln();           
            $pdf->MultiCell(270,10,utf8_decode('Total Carga Horária do Curso: '. $total_ch . ' Horas, Frequência: ' . number_format($frequencia, 2, '.', '') . '%'),'T', 'C', false); 
            $pdf->MultiCell(270,10,utf8_decode('Carga Horária Presente: ' . $cargaHorariaPresenca . ' Horas'),'B', 'C', false); 
          } else {
            /* se não tiver temas imprimo só o total da carga horária do curso */
            $pdf->Ln(); 
            $pdf->Cell(270,18,utf8_decode('Total Carga Horária do Curso: '. $data['curso']->carga_horaria . ' Horas, Frequência: ' . number_format($frequencia, 2, '.', '') . '%'), 1,0, 'C');           
           
          }
       
          
          $pdf->Ln(); 
          $pdf->Ln();           
          $logo_path = $data['qrCode'];
          $pdf->Image($logo_path.'?ext=.png',10,180,20,20);

          $pdf->Output("Relatorio.pdf",'I');  
                    
?>



