<?php 
  function imprimeuf($ufsec) {
    $arrayEstados = array(
      'AC',
      'AL',
      'AM',
      'AP',
      'AC',
      'BA',
      'CE',
      'DF',
      'ES',
      'GO',
      'MA',
      'MT',
      'MS',
      'MG',
      'PA',
      'PB',
      'PR',
      'PE',
      'PE',
      'PI',
      'RJ',
      'RN',
      'RN',
      'RO',
      'RS',
      'RR',
      'SC',
      'SE',
      'SP',
      'TO' 
      );  
      foreach($arrayEstados as $uf){ 
        //iduf tem que ser passada pelo post
        if($uf == $ufsec){
          $html .= '<option selected value="'.$uf.'" '.'>'.$uf.'</option>';
        }
        else{
        $html .='<option value="'.$uf.'" '.'>'.$uf.'</option>';           

      }

    }
    return $html;
  }

  function getTamanhosCalcados() {
    $arrayTamanhos = array(
      '22',
      '23',
      '24',
      '25',
      '26',
      '27',
      '28',
      '29',
      '30',
      '31',
      '32',
      '33',
      '34',
      '35',
      '36',
      '37',
      '38',
      '39',
      '40',
      '41',
      '42',
      '43'
    );
    return $arrayTamanhos;
  }

  function getTipoInstituicoes() {
    $data = array(
      'Pública',
      'Privada'
    );
    return $data;
  }

  function getMaiorEscolaridade($escolaridade) {
    switch ($escolaridade) {
      case 'nao_concluiu':
          return "Não concluiu o EF";
          break;
      case 'e_fundamental':
          return "Ensino Fundamental";
          break;
      case 'e_medio':
          return "Ensino Médio";
          break;
      case 'e_superior':
        return "Ensino Superior";
        break;
    }  
  }

  function getTipoEnsinoMedio($em) {
    switch ($em) {
      case 'geral':
          return "Formação Geral";
          break;
      case 'normal':
          return "Modalidade normal (magistério)";
          break;
      case 'c_tecnico':
          return "Curso técnico";
          break;
      case 'm_indigena':
        return "Magistério indígena - modalidade normal";
        break;
    }  
  }

  function getArrayTamanhos() {
    $arrayTamanhos = array(
      'P',
      'M',
      'G',
      'GG',
      'XGG',
      '1',
      '2',
      '4',
      '6',
      '8',
      '10',
      '12',
      '14',
      '16'    
    );
    return $arrayTamanhos;
  }

  function imptamanhounif($tamanhosec) {
    $html = '';
    $arrayTamanhos = getArrayTamanhos();

    foreach($arrayTamanhos as $tamanho){ 
      //idtamanho tem que ser passada pelo post
      if($tamanho == $tamanhosec){
        $html .= '<option selected value="'.$tamanho.'" '.'>'.$tamanho.'</option>';
      } else{
        $html .='<option value="'.$tamanho.'" '.'>'.$tamanho.'</option>';    
      }  
    }
    return $html;
  }


    function imptamanhocalc($tamanhosec) {
      $html = '';
      $arrayTamanhos = getTamanhosCalcados();
      foreach($arrayTamanhos as $tamanho){ 
        //idtamanho tem que ser passada pelo post
        if($tamanho == $tamanhosec){
          $html .= '<option selected value="'.$tamanho.'" '.'>'.$tamanho.'</option>';
        } else {
          $html .='<option value="'.$tamanho.'" '.'>'.$tamanho.'</option>'; 
        }  
      }
      return $html;
    }

    function getLinhas() {
      $arrayLinhas = array(
        'NÃO UTILIZA',
        'LINHA 01',
        'LINHA 03',
        'LINHA 05',
        'LINHA 06',
        'LINHA 08', 
        'LINHA 09', 
        'LINHA 14', 
        'LINHA 15', 
        'LINHA 18', 
        'LINHA 19', 
        'ROTA 02',
        'ROTA 07',
        'ROTA 14',
        'ROTA 17',
        'ROTA 17A',
        'ROTA 20-18A',
        'ROTA VAN'  
      );
      return $arrayLinhas;
    }

    function imptlinhastransporte($linhasec) {
      $html = '';
      $arrayLinhas =  getLinhas();
        foreach($arrayLinhas as $linha){ 
          //idtamanho tem que ser passada pelo post
          if($linha == $linhasec){
            $html .= '<option selected value="'.$linha.'" '.'>'.$linha.'</option>';
          } else {
            $html .='<option value="'.$linha.'" '.'>'.$linha.'</option>'; 
          }
        }
      return $html;
    }

    function validaCPF($cpf) { 
      // Extrai somente os números
      $cpf = preg_replace( '/[^0-9]/is', '', $cpf );    
      // Verifica se foi informado todos os digitos corretamente
      if (strlen($cpf) != 11) {
        return false;
      }
      // Verifica se foi informada uma sequência de digitos repetidos. Ex: 111.111.111-11
      if (preg_match('/(\d)\1{10}/', $cpf)) {
        return false;
      }
      // Faz o calculo para validar o CPF
      for ($t = 9; $t < 11; $t++) {
        for ($d = 0, $c = 0; $c < $t; $c++) {          
            $d += $cpf[$c] * (($t + 1) - $c);
        }
        $d = ((10 * $d) % 11) % 10;      
        if ($cpf[$c] != $d) {
            return false;
        }
      }
      return true;
    }

    function validacelular($celular) {
      if (preg_match('/(\(?\d{2}\)?) ?9?\d{4}-?\d{4}/', $celular)) {
        return true;
      } else {
        return false;
      }
    }

    function validanascimento($data) {
      $formatado = date('Y-m-d',strtotime($data));
      $ano = date('Y', strtotime($formatado));
      $mes = date('m', strtotime($formatado));
      $dia = date('d', strtotime($formatado));
      $anominimo = date('Y', strtotime('-5 year'));
      if ( !checkdate( $mes , $dia , $ano )                   // se a data for inválida
          || $ano < $anominimo                                // ou o ano menor que a data mínima
          || mktime( 0, 0, 0, $mes, $dia, $ano ) > time() )  // ou a data passar de hoje
      {
        return false;
      } else {
        return true;
      }
    }

  function html($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data, ENT_QUOTES, 'UTF-8');
    return $data;
  }

  function htmlout($text) {
    echo html($text);
  }

  function getData($name) {
    switch ($name) {
      case !isset($name):
        return '';
        break;
      case empty($name):
        return '';
        break;				
      case NULL:
          return '';
          break;      
      case is_string($name): 
          return html($name);
          break;
      default:
          return $name;
    }			
  } 

  function get($name) {
    return isset($_GET[$name]) ? html($_GET[$name]) : '';
  }
  
  function post($name) {
    return isset($_POST[$name]) ? html($_POST[$name]) : '';
  }
  
  function getPost($name) {
    return $this->get($name) ? $this->get($name) : $this->post($name);
  }  

  function validaData($data) {
    if(empty($data)){
      return false;
    }
    // se a data for menor que a data atual retorna falso
    if($data < date("Y-m-d")){
      return false;
    }
    $tempDate = explode('-', $data);
    if(checkdate($tempDate[1], $tempDate[2], $tempDate[0])){
      return true;
    } else {
      return false;
    }  
  }

  function formatadata($data) {  
    $result = date('d/m/Y', strtotime($data));    
    return $result;
  }

  function validaemail($email) {
    if(filter_var($email, FILTER_VALIDATE_EMAIL)){
      return true;
    } else {
      return false;
    } 
  }

  function RandomPassword($length = 6) {
    $chars = "0123456789bcdfghjkmnpqrstvwxyzBCDFGHJKLMNPQRSTVWXYZ";
    return substr(str_shuffle($chars),0,$length);
  }

  //função para verificar conteúdo de dados
  function debug($data){
    echo("<pre>");
    print_r($data);
    echo("</pre>");
    die();
  }

  function retornaClasseFase($fase){
    switch ($fase){
      case 'ABERTO': 
        return "badge badge-success";
        break;
      case 'FECHADO':      
        return "badge badge-danger";
        break;
      case 'CANCELADO';     
        return "badge badge-warning";
        break;
        case 'CERTIFICADO';      
        return "badge badge-primary";
        break;
      case 'ARQUIVADO';      
        return "badge badge-secondary";
        break;
    }
  }

  /*
  Função de upload de arquivo para uma pasta
  $file = upload($data = [
                  'file' => 'file_post', id do input
                  'path' => 'uploads/diplomas/', diretorio onde quer salvar
                  'extensions' => ["jpeg","jpg","png"], extenções permitidas
                  'maxsize' => 2097152 tamanho máximo permitido
                  'name' => 'Teste' name não é obrigatório se não colocar fica o nome original
                ]);  
  para o tamanho basta multiplicar em megas duas vezes por 1024 exemplo 2mb * 1024 * 1024 = 2097152
  $file = $this->fusercursossupModel->upload('file_post',"uploads/diplomas/",["jpeg","jpg","png"],2097152);
  */
  function upload($arr) {
    $extensionstxt = '';   
    $file = $arr['file'];
    $path = $arr['path'];
    $allowedsize = $arr['maxsize'];
    $extensions = isset($arr['extensions']) ? $arr['extensions'] : NULL;
    $name = isset($arr['name']) ? $arr['name'] : NULL;
    if(isset($_FILES[$file])){
        $errors= array();
        $file_name = $_FILES[$file]['name'];
        $file_size = $_FILES[$file]['size'];
        $file_tmp = $_FILES[$file]['tmp_name'];
        $file_type = $_FILES[$file]['type'];
        $tempname = explode('.',$_FILES[$file]['name']);
        $file_ext = strtolower(end($tempname));  
        //monto o texto para a mensagem quando o usuário selecionar um tipo não permitido
        if(in_array($file_ext,$extensions)=== false){
            foreach($extensions as $key => $extention) {                        
                if($key === count($extensions)-1){
                    $extensionstxt .= ' ou ' . $extention;    
                } else if($key > 0) {
                    $extensionstxt .= ', ' . $extention;
                } else {
                    $extensionstxt .= $extention;
                }                      
            }
            $errors[]="Extenção não permitida, por favor selecione um arquivo do tipo " . $extensionstxt;
        }      
        if($file_size > $allowedsize){
            $sizemb = ($allowedsize / 1024) / 1024;
            $errors[]="O arquivo excede o tamanho permitido de ".$sizemb." MB";
        }
        if($errors){
          $data = [
            'sucess' => false,                   
            'errors' => $errors
          ];                 
        } else {          
            $pathinfo = pathinfo($_FILES[$file]['name']);
            //base é apenas o nome do arquivo sem extenção
            $base = $pathinfo["filename"];
            //aqui tiro caracteres indesejados                
            //$base = preg_replace("/[^\w-]/", "_", $base);
            //aqui monto o nome do arquivo com extenção  
            if(!$name){
              $filename = $base . "." . $pathinfo["extension"];
            } else {
              $filename = $name . "." . $pathinfo["extension"];
            }
            $i = 1;
            /*verifico se já existe um arquivo com esse nome se existir coloco um número 
            (1) sequencial para identificar o arquivo*/
            while (file_exists($path.$filename)){
                $filename = $base . "($i).". $pathinfo["extension"];
                $i++;
            }
            if(move_uploaded_file($file_tmp,$path.$filename)){
                $data = [
                    'sucess' => true,                            
                    'file' => $path.$filename
                ];                    
            } else {
              $data = [
                'sucess' => false,                            
                'errors' => 'Falha ao tentar fazer o upload do arquivo'
              ]; 
            }
        }                  
      }
      return ($data);
  }

  function download($path) {
    if(isset($path)) {
      //Read the filename
      $filename = $path;
      //Check the file exists or not
      if(file_exists($filename)) {
        //Define header information
        header('Content-Description: File Transfer');
        header('Content-Type: application/octet-stream');
        header("Cache-Control: no-cache, must-revalidate");
        header("Expires: 0");
        header('Content-Disposition: attachment; filename="'.basename($filename).'"');
        header('Content-Length: ' . filesize($filename));
        header('Pragma: public');
        //Clear system output buffer
        flush();
        //Read the size of the file
        readfile($filename);
        //Terminate from the script
        die();
      } else {
        $data = [
          'sucess' => false,                            
          'errors' => 'Arquivo inexistente'
        ]; 
      }
    } else {
      $data = [
        'sucess' => false,                            
        'errors' => 'Arquivo não definido'
      ]; 
    }
    return $data;
  }

  function removeFile($path){
    if(!unlink($path)){
      return false;
    } else {
      return true;
    }
  }

  //$_SESSION[SE.'user_permit'] vem do controller users
  //debug($_SESSION[SE.'user_permit']); 
  function getPermission($table,$action){ 
    if(isset($_SESSION[SE.'_user_permit'][$table]) && $_SESSION[SE.'_user_permit'][$table][$action] == 's'){     
      return true;
    } else {      
      return false;
    }
  }
  
?>