<?php
/**
* Template Name: Accedi
*
* @package WordPress
* @subpackage BF Cerco / Offro Avvocati
* 
*/

// Do not allow directly accessing this file.
if ( ! defined( 'ABSPATH' ) ) {
	exit( 'Direct script access denied.' );
}
?>
<?php get_header(); ?>
<section id="content" <?php Avada()->layout->add_style( 'content_style' ); ?>>
	<?php while ( have_posts() ) : the_post(); ?>
		<div id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
			<?php echo fusion_render_rich_snippets_for_pages(); // WPCS: XSS ok. ?>
			<?php if ( ! post_password_required( $post->ID ) ) : ?>
				
			<?php endif; // Password check. ?>

			<div class="post-content">
				
            <section class="cerco-offro aa_loginForm">
        <?php 
            global $current_user;

            // In case of a login error.
            if ( isset( $_GET['login'] ) && $_GET['login'] == 'failed' ) : ?>
    	            <div class="bf_error">
    		            <p><?php _e( 'Errore autenticazione: si prega si riprovare', 'befamily_plugin' ); ?></p>
    	            </div>
            <?php 
                endif;

            // If user is already logged in.
            if ( is_user_logged_in() ) : ?>

                <div class="bf_logout"> 
                    
                    <?php 
                    echo wp_kses_post(
                        sprintf(
                            __( 'Ciao %s,', 'befamily_plugin' ),
                            $current_user->display_name
                        )
                    );
                    ?>
                    
                    </br>
                    
                    <?php _e( 'Sei già autenticato.', 'befamily_plugin' ); ?>

                </div>

                <a id="wp-submit" href="<?php echo wp_logout_url( home_url() ); ?>" title="Logout">
                    <?php _e( 'Logout', 'befamily_plugin' ); ?>
                </a>

            <?php 
                // If user is not logged in.
                else: 
                
                    // Login form arguments.
                    $args = array(
                        'echo'           => true,
                        'redirect'       => home_url( '/annunci/' ), 
                        'form_id'        => 'loginform',
                        'label_username' => __( 'Email' ),
                        'label_password' => __( 'Password' ),
                        'label_remember' => __( 'Remember Me' ),
                        'label_log_in'   => __( 'Log In' ),
                        'id_username'    => 'user_login',
                        'id_password'    => 'user_pass',
                        'id_remember'    => 'rememberme',
                        'id_submit'      => 'wp-submit',
                        'remember'       => true,
                        'value_username' => NULL,
                        'value_remember' => true
                    ); 
                    
                    // Calling the login form.
                    wp_login_form( $args );

                    echo '<label class="show-psw"><input type="checkbox" onclick="showPsw()">';
                        _e('Mostra Password', 'befamily_plugin');
                    echo '</label>';

                    echo '<a id="register-btn" class="button" href="'. home_url( '/registrati/' ) .'">';
                        _e('Registrati', 'befamily_plugin');
                    echo '</a>';

                ?>
                    <a href="<?php echo esc_url( wp_lostpassword_url() ); ?>" alt="<?php esc_attr_e( 'Lost Password', 'textdomain' ); ?>" style="width: 100%;display: block;text-align: center;">
                        <?php esc_html_e( 'Problemi di accesso? Reimposta la password', 'befamily_plugin' ); ?>
                    </a>
                <style>
                    #loginform input:not(#wp-submit) {
                        font-size: 16px;
                        color: black;
                        letter-spacing:0.5px;
                    }
                    .login-password {margin-bottom: 4em !important}
                    .login-remember label {display: flex;}
                    .show-psw {
                        position: absolute;
                        margin-top: -17em;
                        display: flex;
                        cursor: pointer;
                    }
                    @media screen and (min-width: 500px) and (max-width: 1120px) {
                        #loginform {width:72%;}
                        .show-psw {margin-left: 5em;}
                    }
                    @media screen and (min-width: 1200px) {
                        .show-psw {margin-left: 20em;}
                    }
                </style>
                <script>
                    function showPsw() {
                        var x = document.getElementById("user_pass");
                        if (x.type === "password") {
                            x.type = "text";
                        } else {
                            x.type = "password";
                        }
                    } 
                </script>

    <?php endif; ?> 

	            </section>
				<?php fusion_link_pages(); ?>
			</div>
		</div>
	<?php endwhile; ?>
	<?php wp_reset_postdata(); ?>
</section>
<?php do_action( 'avada_after_content' ); ?>
<?php get_footer();