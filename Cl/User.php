<?php
class Cl_User
{
	/**
	 * @var will going contain database connection
	 */
	protected $_con;
	
	/**
	 * it will initalize DBclass
	 */
	public function __construct()
	{
		$db = new Cl_DBclass();
		$this->_con = $db->con;
	}
	
	/**
	 * this will handles user registration process
	 * @param array $data
	 * @return boolean true or false based success 
	 */
	public function registration( array $data )
	{
		if( !empty( $data ) ){
			// Trim all the incoming data:
			$trimmed_data = array_map('trim', $data);
			// escape variables for security
			$name = pg_escape_string( $this->_con, $trimmed_data['name'] );
			$password = pg_escape_string( $this->_con, $trimmed_data['password'] );
			$cpassword = pg_escape_string( $this->_con, $trimmed_data['confirm_password'] );
			// Check for an email address:
			if (filter_var( $trimmed_data['email'], FILTER_VALIDATE_EMAIL)) {
				$email = pg_escape_string( $this->_con, $trimmed_data['email']);
			} else {
				throw new Exception( "Please enter a valid email address!");
			}
			if((!$name) || (!$email) || (!$password) || (!$cpassword) ) {
				throw new Exception( FIELDS_MISSING );
			}
			if ($password !== $cpassword) {
				throw new Exception( PASSWORD_NOT_MATCH );
			}
			$password = md5( $password );
			$query = "INSERT INTO users (name, email, password) VALUES ('$name', '$email', '$password')";
			if(pg_query($this->_con, $query)){
				pg_close($this->_con);
				return true;
			};
		} else{
			throw new Exception( USER_REGISTRATION_FAIL );
		}
	}
	/**
	 * This method will handle user login process
	 * @param array $data
	 * @return boolean true or false based on success or failure
	 */
	public function login( array $data )
	{
		$_SESSION['logged_in'] = false;
		if( !empty( $data ) ){
			
			// Trim all the incoming data:
			$trimmed_data = array_map('trim', $data);
			
			// escape variables for security
			$email = pg_escape_string( $this->_con,  $trimmed_data['email'] );
			$password = pg_escape_string( $this->_con,  $trimmed_data['password'] );
				
			if((!$email) || (!$password) ) {
				throw new Exception( LOGIN_FIELDS_MISSING );
			}
			$password = md5( $password );
			$query = "SELECT user_id, name, email, created FROM users where email = '$email' and password = '$password' ";
			$result = pg_query($this->_con, $query);
			$data = pg_fetch_array($result,0);
			$count = pg_num_rows($result);
			pg_close($this->_con);
			if( $count == 1){
				$_SESSION = $data;
				$_SESSION['logged_in'] = true;
				return true;
			}else{
				throw new Exception( LOGIN_FAIL );
			}
		} else{
			throw new Exception( LOGIN_FIELDS_MISSING );
		}
	}

	/**
	 * This method will handle google login
	 * @param array $data
	 * @throws Exception
	 * @return boolean true or false based on success or failure
	 */
	public function google_login( array $data ) 
	{
		if( !empty( $data ) ){
			// Trim all the incoming data:
			$trimmed_data = array_map('trim', $data);
		}
		
		// escape variables for security
		$name = pg_escape_string( $this->_con, $trimmed_data['name'] );
		$email = pg_escape_string( $this->_con, $trimmed_data['email'] );
		$social_id = pg_escape_string( $this->_con, $trimmed_data['id'] );
		$picture = pg_escape_string( $this->_con, $trimmed_data['picture'] );
		$query = "SELECT user_id, name, email, created FROM users where email = '$email' and social_id = '$social_id' ";
		$result = pg_query($this->_con, $query);
		$data = pg_fetch_array($result,0);
		$count = pg_num_rows($result);
		if( $count == 1){
			$_SESSION = $data;
			$_SESSION['logged_in'] = true;
			return true;
		}else{
				
			$query = "INSERT INTO users (user_id, name, email, social_id, picture) VALUES (NULL, '$name', '$email', '$social_id', '$picture')";
			if(pg_query($this->_con, $query));
			$query = "SELECT user_id, name, email, created FROM users where email = '$email' and social_id = '$social_id' ";
			$result = pg_query($this->_con, $query);
			$data = pg_fetch_array($result,0);
			$count = pg_num_rows($result);
			if( $count == 1){
				$_SESSION = $data;
				$_SESSION['logged_in'] = true;
				return true;
			}else{
				throw new Exception( LOGIN_FAIL );
			}
		}
	}
	
	/**
	 * This will shows account information and handles password change
	 * @param array $data
	 * @throws Exception
	 * @return boolean
	 */
	
	public function account( array $data )
	{
		if( !empty( $data ) ){
			// Trim all the incoming data:
			$trimmed_data = array_map('trim', $data);
			
			// escape variables for security
			$password = pg_escape_string( $this->_con, $trimmed_data['password'] );
			$cpassword = $trimmed_data['confirm_password'];
			$user_id = pg_escape_string( $this->_con, $trimmed_data['user_id'] );
			
			if((!$password) || (!$cpassword) ) {
				throw new Exception( FIELDS_MISSING );
			}
			if ($password !== $cpassword) {
				throw new Exception( PASSWORD_NOT_MATCH );
			}
			$password = md5( $password );
			$query = "UPDATE users SET password = '$password' WHERE user_id = '$user_id'";
			if(pg_query($this->_con, $query)){
				pg_close($this->_con);
				return true;
			}
		} else{
			throw new Exception( FIELDS_MISSING );
		}
	}
	
	/**
	 * This handle sign out process
	 */
	public function logout()
	{
		session_unset();
		session_destroy();
		header('Location: index.php');
	}
	
	/**
	 * This reset the current password and send new password to mail
	 * @param array $data
	 * @throws Exception
	 * @return boolean
	 */
	public function forgetPassword( array $data )
	{
		if( !empty( $data ) ){
			
			// escape variables for security
			$email = pg_escape_string( $this->_con, trim( $data['email'] ) );
			
			if((!$email) ) {
				throw new Exception( FIELDS_MISSING );
			}
			$password = $this->randomPassword();
			$password1 = md5( $password );
			$query = "UPDATE users SET password = '$password1' WHERE email = '$email'";
			if(pg_query($this->_con, $query)){
				pg_close($this->_con);
				$to = $email;
				$subject = "Recuperación de contraseña";
				$txt = "Su nueva clave: ".$password;
				$headers = "From: admin@sierramar.es" . "\r\n" .
						"CC: juanky.moral@sierramar.es";
					
				//mail($to,$subject,$txt,$headers);
				return true;
			}
		} else{
			throw new Exception( FIELDS_MISSING );
		}
	}
	
	/**
	 * This will generate random password
	 * @return string
	 */
	
	private function randomPassword() {
		$alphabet = "abcdefghijklmnopqrstuwxyzABCDEFGHIJKLMNOPQRSTUWXYZ0123456789";
		$pass = array(); //remember to declare $pass as an array
		$alphaLength = strlen($alphabet) - 1; //put the length -1 in cache
		for ($i = 0; $i < 8; $i++) {
			$n = rand(0, $alphaLength);
			$pass[] = $alphabet[$n];
		}
		return implode($pass); //turn the array into a string
	}
}
