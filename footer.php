	<?php do_action('before_footer'); ?>

	<?php
		$logos = get_field('footer_logos', 'option');
	?>
	<div class="flexible__content">
		<section class="fc-layout fc_carousel_images">
			<div class="fc-layout-container">
				<div class="max__width">
					<div class="carousel_images footer-logos">
						<?php
							foreach($logos as $logo):
						?>
							<div class="carousel_image">
								<img src="<?php echo $logo['image']; ?>" />
							</div><!--  -->
						<?php endforeach; ?>
					</div><!-- carousel_images -->
				</div><!-- max__width -->
			</div><!-- fc-layout-container -->
		</section>
	</div>

	<footer role="contentinfo">
		<div class="max__width">
			<div class="footer__signup">
				<div id="mc_embed_signup" class="footer__signup__form">
					<h3>Stay up to date</h3>

					<form action="#" method="post" id="mc-embedded-subscribe-form" name="mc-embedded-subscribe-form" class="validate" target="_blank" novalidate>
						<div class="form__row">
							<div class="form__field">
								<input type="email" value="" placeholder="Email address" name="EMAIL" class="required email" id="mce-EMAIL">
							</div><!-- form__field -->

							<div class="form__field submit">
								<button type="submit" value="Subscribe" name="subscribe" id="mc-embedded-subscribe">Sign up</button>
							</div><!-- form__field -->
						</div><!-- form__row -->
					</form>

					<div class="form__row gdpr">
						<small>We are GDPR compliant and respect your privacy. You can unsubscribe at any time.</small>
					</div>
				</div><!-- footer__signup__form -->

				<div class="footer__social">
					<ul>
						<li>
							<a href="https://www.instagram.com/theliteracycompany/" target="_blank">
								<?php echo file_get_contents(FL1_PATH . '/img/svgs/instagram.svg'); ?>
							</a>
						</li>
					</ul>
				</div>
			</div><!-- footer__signup -->
		</div>

		<div class="footer__menus">
			<div class="max__width">
				<article>
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