<?php


class Wphetic_FormGenerator {
	public static function createForm() {
		ob_start();
		?>

        <form action="<?= admin_url( 'admin-post.php' ); ?>" method="post">
            <label for="message">Votre Message</label><br/>
            <textarea name="message" id="message"></textarea><br/>

            <!-- Le fameux champs d'action -->
            <input type="hidden" name="action" value="wphetic_form"/>

            <!-- Crée les champs de nonce et de referer -->
			<?php wp_nonce_field( 'random_action', 'random_nonce' ); ?>

            <input type="submit" value="Envoyer"/>

        </form>

		<?php
		return ob_get_clean();
	}

	public static function handleForm() {
		if ( ! wp_verify_nonce( $_POST['random_nonce'], 'random_action' ) ) {
			die( 'Nonce invalide' );
		}
		$message = $_POST['message'];
		wp_redirect( $_POST['_wp_http_referer'] . "?message=" . $message );
		exit();
	}

	public static function displayLoginForm() {
		ob_start();

		wp_login_form( [ 'form_id' => 'wphetic_login_form' ] );
		?>
        <script>
            jQuery(() => {
                jQuery('#wphetic_login_form').addClass('p-5 border rounded m-auto w-75');
                jQuery('#wphetic_login_form .login-username')
                    .addClass('form-floating mb-3')
                    .html('<input type="text" class="form-control" id="floatingInput" placeholder="name@example.com" name="log"><label for="floatingInput">Username</label>')
                    .before('<h4 class="mb-3">Connectez vous</h4>');
                jQuery('#wphetic_login_form .login-password')
                    .addClass('form-floating mb-3')
                    .html('<input type="password" class="form-control" id="floatingPassword" placeholder="Password" name="pwd"><label for="floatingPassword">Password</label>');
                jQuery('#wphetic_login_form .login-remember')
                    .addClass('mb-3 form-check')
                    .html('<input type="checkbox" class="form-check-input" id="exampleCheck1" name="rememberme"><label class="form-check-label" for="exampleCheck1">Remember me</label>');
                jQuery('#wphetic_login_form .login-submit').addClass('mb-0');
                jQuery('#wphetic_login_form #wp-submit').addClass('btn btn-primary');
            });
        </script>

        <!--<form action="<?/*= home_url( 'wp-login.php' ); */ ?>" method="post" class="p-5 border rounded m-auto w-75">
            <h4 class="mb-3">Connectez vous</h4>
            <div class="form-floating mb-3">
                <input type="text" class="form-control" id="floatingInput" placeholder="name@example.com" name="log">
                <label for="floatingInput">Username</label>
            </div>
            <div class="form-floating mb-3">
                <input type="password" class="form-control" id="floatingPassword" placeholder="Password" name="pwd">
                <label for="floatingPassword">Password</label>
            </div>
            <div class="mb-3 form-check">
                <input type="checkbox" class="form-check-input" id="exampleCheck1" name="rememberme">
                <label class="form-check-label" for="exampleCheck1">Remember me</label>
            </div>
            <button type="submit" class="btn btn-primary" name="wp-submit">Submit</button>

            <input type="hidden" name="redirect_to" value="/blog"/>
        </form>-->


		<?php

		return ob_get_clean();
	}
}