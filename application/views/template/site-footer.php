<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<div id="footer">
	<div class="container">
		<div class="row">
			<div class="col-sm-3">
				<h4>Quick Links</h4>
				<ul>
					<li><a href="<?php echo base_url() . 'features'; ?>">Features</a></li>
					<li><a href="<?php echo base_url() . 'sign-up'; ?>">Try Now</a></li>
					<li><a href="<?php echo base_url() . 'pricing'; ?>">Pricing</a></li>
				</ul>
			</div>

			<div class="col-sm-3">
				<h4>Flindle</h4>
				<ul>
					<li><a href="<?php echo base_url() . 'login'; ?>">Login</a></li>
					<li><a href="<?php echo base_url() . 'contact'; ?>">Contact</a></li>
					<li><a href="http://flindle.com/blog">Blog</a></li>
				</ul>
			</div>

			<div class="col-sm-3 social">
				<h4>Social</h4>
				<a href="http://twitter.com/flindle"><img src="<?php echo images_url() . 'social/round/twitter.png'; ?>" alt="Twitter"></a>
				<a href="http://facebook.com/flindle"><img src="<?php echo images_url() . 'social/round/facebook.png'; ?>" alt="Facebook"></a>
				<a href="http://angel.co/flindle"><img src="<?php echo images_url() . 'social/round/angelco.png'; ?>" alt="Angel List"></a>
				<br>
				<a href="http://google.com/+Flindle"><img src="<?php echo images_url() . 'social/round/google.png'; ?>" alt="Google Plus"></a>
				<a href="http://www.linkedin.com/company/flindle"><img src="<?php echo images_url() . 'social/round/linkedin.png'; ?>" alt="LinkedIn"></a>
				<a href="http://flindle.com/blog"><img src="<?php echo images_url() . 'social/round/blog.png'; ?>" alt="Flindle Blog"></a>
			</div>
			<div class="col-sm-3">
				<img src="<?php echo images_url() . 'flindle-transparent-180.png'; ?>" alt="Flindle.com"
					class="center-block">
			</div>
		</div>
		<div class="row copyright">
			<p>Copyright © <?php echo date("Y"); ?> Flindle Limited</p>
		</div>
	</div>
</div><!-- /#footer -->