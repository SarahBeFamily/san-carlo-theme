{{--
  Template Name: Accedi
--}}
@php 
	global $current_user;
	$redirect_page = get_page_by_title( 'Area stampa', '', 'page' );
@endphp

@extends('layouts.app')

@section('content')
  @while(have_posts()) @php(the_post())

	<div class="template-container">
		<div class="inner-cont">
			@include('partials.content-page')
			
			<div class="form-accesso vc_row wpb_row vc_row-fluid vc_row-bf-default">
				
				<div class="inner">
					
					{{-- In case of a login error. --}}
					@if ( isset( $_GET['login'] ) && $_GET['login'] == 'failed' )
						<div class="bf_error">
							@if (isset($_GET['reason']) && $_GET['reason'] == 'empty_username')
								<p>{{ _e( 'Warning: Please insert a valid username or password', 'san-carlo-theme' ) }}</p>
							@elseif (isset($_GET['reason']) && $_GET['reason'] == 'empty_password')
								<p>{{ _e( 'Warning: Please insert a password', 'san-carlo-theme' ) }}</p>
							@elseif (isset($_GET['reason']) && $_GET['reason'] == 'incorrect_password')
								<p>{{ _e( 'Warning: Incorrect Password', 'san-carlo-theme' ) }}</p>
							@elseif (isset($_GET['reason']) && ($_GET['reason'] == 'invalid-recaptcha') || isset($_GET['reason']) && ($_GET['reason'] == 'missed-recaptcha'))
								<p>{{ _e( 'Warning: Invalid reCAPTCHA', 'san-carlo-theme' ) }}</p>
							@else 
								<p>{{ _e( 'Authentication error: please retry', 'san-carlo-theme' ) }}</p>
							@endif
						</div>
					@endif

					{{-- If user is already logged in. --}}
					@if ( is_user_logged_in() )

						<p class="bf_logout"> 
							
							{{-- translators: %s è il nome utente --}}
							{!! wp_kses_post( sprintf(__( 'Hello %s,', 'san-carlo-theme' ), $current_user->display_name ) ) !!}
							
							
							{{ __( 'You are already logged in.', 'san-carlo-theme' ) }}

						</p>

						<a id="wp-submit" href="{{ get_permalink( get_option('woocommerce_myaccount_page_id') ) }}?customer-logout" title="Logout">
						{{ _e( 'Logout', 'san-carlo-theme' ) }}
						</a>

					
					{{-- If user is not logged in. --}}
					@else
						
							{{-- Login form arguments. --}}
							{!! wp_login_form( array(
								'echo'           => true,
								'redirect'       => get_permalink($redirect_page->ID), 
								'form_id'        => 'loginform',
								'label_username' => __( 'Email', 'san-carlo-theme' ),
								'label_password' => __( 'Password', 'san-carlo-theme' ),
								'label_remember' => __( 'Remember me', 'san-carlo-theme' ),
								'label_log_in'   => __( 'Login', 'san-carlo-theme' ),
								'id_username'    => 'user_login',
								'id_password'    => 'user_pass',
								'id_remember'    => 'rememberme',
								'id_submit'      => 'wp-submit',
								'remember'       => true,
								'value_username' => NULL,
								'value_remember' => true
							)) !!}

							{{-- <i onclick="showPsw()" class="bf-icon icon-hide-password toggle-password"></i> --}}

							{{-- <label class="show-psw"><input type="checkbox" onclick="showPsw()">
								{{ _e('Mostra Password', 'san-carlo-theme') }}
							</label> --}}
					
							<a id="lostPassword" class="black-link" href="{{ esc_url( wp_lostpassword_url() ) }}" alt="{{ esc_attr_e( 'Lost Password', 'san-carlo-theme' ) }}">
								{{ _e( 'Forgotten Password?', 'san-carlo-theme' ) }}
							</a>

							<p>
								{{ _e('Don\'t you have an account yet?', 'san-carlo-theme') }} <a class="black-link" href="{{home_url( '/user-registration/' )}}">{{ _e('Register', 'san-carlo-theme') }}</a>
							</p>
					@endif
				</div>
            
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
			</div>
		</div>
	</div>

  @endwhile
@endsection