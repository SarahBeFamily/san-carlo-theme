@php
/**
 * Email Footer
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/emails/email-footer.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates\Emails
 * @version 7.4.0
 */

defined( 'ABSPATH' ) || exit;
// apply_filters( 'woocommerce_email_footer_text', get_option( 'woocommerce_email_footer_text' ) )
@endphp

																		</div>
																	</td>
																</tr>
															</table>
														<!-- End Content -->
														</td>
													</tr>
												</table>
												<!-- End Body -->
											</td>
										</tr>
									</table>
								</td>
							</tr>
							<tr>
								<td align="center" valign="top">
									<!-- Footer -->
										<table border="0" cellpadding="10" cellspacing="0" width="100%" id="footer">
											<tbody style="width: 100%;">
											<tr style="width: 100%; display: flex; justify-content: space-between;">
												<td valign="top" style="width: 55%;">
													<table border="0" cellpadding="10" cellspacing="0" width="100%">
														<tr>
															<td colspan="2" valign="middle">
																<h3>Fondazione <br>
																	Teatro San Carlo</h3>
															</td>
														</tr>
													</table>
													<table border="0" cellpadding="10" cellspacing="0" width="100%">					
														<tr class="menuu">
															<a href="{{ get_site_url() }}/privacy-policy/">Privacy</a> | <a href="{{ get_site_url() }}/cookie-policy/">Cookie Policy</a> | <a href="{{ get_site_url() }}/copyright/">Copyright</a>
														</tr>
													</table>
												</td>

												<td valign="top">
													<table border="0" cellpadding="10" cellspacing="0" width="100%" style="margin-bottom: 25px;">
														<tr class="info">
															<td colspan="2" valign="middle" style="color: white;">
																@option('info_footer')
															</td>
														</tr>
													</table>

													<table border="0" cellpadding="10" cellspacing="0" width="100%">
														<tr class="social-wrap">
															<!--[if (gte mso 9)|(IE)]><br><br><br><![endif]-->
															@options('social', 'options')
															<a href="@sub('link')" class="social @sub('tipo_social')">
																<img src="{{ get_home_url() }}/wp-content/uploads/icone/@sub('tipo_social').png" alt="@sub('tipo_social')" width="30" height="30">
															</a>
															@endoptions
														</tr>
													</table>
												</td>
											</tr>
											</tbody>
										</table>
									<!-- End Footer -->
								</td>
							</tr>
						</table>
					</div>
				</td>
				<td><!-- Deliberately empty to support consistent sizing and layout across multiple email clients. --></td>
			</tr>	
		</table>
	</body>
</html>

