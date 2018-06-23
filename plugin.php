<?php
/*
Plugin Name: Login Override plugin
Plugin URI: http://renesejling.dk
Description: Use custom ajax action to override login
Version: 1.0.0
Author: René Sejling
Author URI: http://renesejling.dk
*/


class Login_Override {
	public function __construct(){
		add_action('wp_ajax_login_override', [ $this, 'login_override' ] );
		add_action('wp_ajax_nopriv_login_override', [ $this, 'login_override' ] );
	}

	public function login_override(){
		if( empty( $_POST['username'] ) || empty( $_POST['password'] ) ) {
			$this->form();
		} else {
			$username = $_POST['username'];
			$password = $_POST['password'];
			$user = get_user_by( 'login', $username );
			if( $user && wp_check_password( $password, $user->data->user_pass, $user->ID ) ) {
				wp_set_current_user( $user->ID, $username );
				wp_set_auth_cookie( $user->ID );
				do_action( 'wp_login', $username );
	?>
		<p>You have successfully logged in. Click <a href="<?php echo admin_url(); ?>"><?php echo admin_url(); ?></a> to continue</p>
	<?php 
			} else {
				$this->form();
			}
		}
		wp_die();
	}
	
	private function form() {
?>
<div>
<form method="post" action="<?php echo admin_url('admin-ajax.php?action=login_override'); ?>">
<input type="text" name="username" placeholder="Username"/><br/>
<input type="password" name="password" placeholder="Password"/><br/>
<input type="submit" value="Login"/>
</form>
</div>
<?php
	}
}

new Login_Override();
