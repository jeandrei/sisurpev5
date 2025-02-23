<?php
	// para tr sessão tem que ter session_start();
	session_start();

	// Flash message helper
	// EXAMPLE - flash('register_success', 'You are now registered', 'alert alert-danger');
	/// DISPLAY IN VIEW - echo flash('register_success');
	function flash($name = '', $message = '', $class = 'success'){
    if(!empty($name)){
        if(!empty($message) && empty($_SESSION[$name])){
            if(!empty($_SESSION[$name])){
                unset($_SESSION[$name]);
            }

            if(!empty($_SESSION[$name. '_class'])){
                unset($_SESSION[$name. '_class']);
            }

            $_SESSION[$name] = $message;
            $_SESSION[$name. '_class'] = $class;
        } elseif(empty($message) && !empty($_SESSION[$name])){
          $class = !empty($_SESSION[$name. '_class']) ? $_SESSION[$name. '_class'] : '';
          //echo '<div class="'.$class.'" id="msg-flash">'.$_SESSION[$name].'</div>';
          echo '<script>createNotification("'.$_SESSION[$name].'", "'.$class.'")</script>';                
          unset($_SESSION[$name]);
          unset($_SESSION[$name. '_class']);
        }
    }
  }

	function isLoggedIn(){
		if(isset($_SESSION[SE . '_user_id'])){       
			return true;
		} else {
			return false;
		}
	}

	function isAdmin(){
		if((isset($_SESSION[SE . '_user_type'])) && ($_SESSION[SE . '_user_type']) == 'admin'){
			return true;
		} else {
			return false;
		}
	}	
?>