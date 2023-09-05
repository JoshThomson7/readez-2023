	<?php do_action('before_footer'); ?>

	<footer role="contentinfo">
		<div class="max__width">
			<div class="footer__signup">
				<div id="mc_embed_signup" class="footer__signup__form">
					<h3>Stay up to date</h3>

					<iframe data-w-type="embedded" frameborder="0" scrolling="no" marginheight="0" marginwidth="0" src="https://jr29.mjt.lu/wgt/jr29/np7/form?c=c837c14c" width="100%" style="height: 0;"></iframe>
					<script type="text/javascript" src="https://app.mailjet.com/pas-nc-embedded-v1.js"></script>

					<!-- <form action="#" method="post" id="mc-embedded-subscribe-form" name="mc-embedded-subscribe-form" class="validate" target="_blank" novalidate>
						<div class="form__row">
							<div class="form__field">
								<input type="email" value="" placeholder="Email address" name="EMAIL" class="required email" id="mce-EMAIL">
							</div>

							<div class="form__field submit">
								<button type="submit" value="Subscribe" name="subscribe" id="mc-embedded-subscribe">Sign up</button>
							</div>
						</div>
					</form> -->

					<div class="form__row gdpr">
						<small>We are GDPR compliant and respect your privacy. You can opt-out at any time after signing up.</small>
					</div>
				</div><!-- footer__signup__form -->

			</div><!-- footer__signup -->
		</div>

		<div class="footer__menus">
			<div class="max__width">
				
				<?php
				while (have_rows('footer_menus', 'options')) : the_row();

					$footer_menu = get_sub_field('footer_menu');
				?>
					<article class="footer__menu">
						<?php if ($footer_menu) : ?>
							<h5><?php echo $footer_menu->name; ?> <i class="fas fa-chevron-down"></i></h5>
							<?php wp_nav_menu(array('menu' => $footer_menu->name, 'container' => false)); ?>
						<?php endif; ?>
					</article>

				<?php endwhile; ?>

				<article class="footer__details">
					<ul>
						<li>
							<i class="fa-light fa-location-dot"></i>
							Read EZ<br>
							131a Dixons Hill Road<br>
							Herts<br>
							AL9 7DW
						</li>
						<li><i class="fa-light fa-phone"></i> 01707 414 700</li>
					</ul>
				</article>

				<article class="footer__details">
					<img src="<?php echo esc_url(get_stylesheet_directory_uri().'/img/ISO-Inverted.png'); ?>" alt="<?php bloginfo('name'); ?>"/>
				</article>
			</div>
		</div>

		<div class="subfooter">
			<div class="max__width">

				<div class="subfooter--left">
					<small>&copy; <?php bloginfo('name') ?> <?php echo date('Y'); ?></small>
				</div><!-- subfooter--left -->

				<div class="subfooter--right">
					<small><a href="https://thomson-website-solutions.com/" target="_blank">Powered by Thomson Website Solutions</a></small>
				</div><!-- subfooter--left -->

			</div><!-- max__width -->
		</div><!-- subfooter -->
	</footer>

	<div class="spotlight-search">
		<div class="spotlight-search--content">
			<a href="#" class="spotlight-close"><i class="fal fa-times"></i></a>

			<h2>Search Read EZ</h2>
			<form action="<?php echo esc_url(home_url()); ?>">
				<input type="text" name="s" placeholder="ie. Overlays" />
				<button type="submit" class="button primary"><i class="fal fa-search"></i></button>
			</form>
		</div>
	</div>

	</div><!-- #page -->

	<?php wp_footer(); ?>
	
	</body>

	</html>