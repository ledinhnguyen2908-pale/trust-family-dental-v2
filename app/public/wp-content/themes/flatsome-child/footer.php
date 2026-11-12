<?php defined('ABSPATH') || exit; ?>
	</div>
	<footer class="tfd-footer">
		<div class="tfd-shell tfd-footer__grid">
			<div class="tfd-footer__brand"><a href="<?php echo esc_url(tfd_home_url()); ?>"><img src="<?php echo esc_url(tfd_asset('images/logo.png')); ?>" width="180" height="80" alt="Trust Family Dental" loading="lazy"></a><p><?php echo esc_html(tfd_copy('footer_body')); ?></p><span class="tfd-footer__signature"><?php echo esc_html(tfd_copy('signature')); ?></span></div>
			<div><h2><?php echo esc_html(tfd_copy('nav_services')); ?></h2><?php foreach(array('emergency','restorative','family','services') as $role): ?><a href="<?php echo esc_url(tfd_page_url($role)); ?>"><?php echo esc_html(tfd_copy('nav_'.$role)); ?></a><?php endforeach; ?></div>
			<div><h2><?php echo esc_html(tfd_copy('patients')); ?></h2><?php foreach(array('payment','about','doctors','faq','contact','appointment') as $role): ?><a href="<?php echo esc_url(tfd_page_url($role)); ?>"><?php echo esc_html(tfd_copy('appointment'===$role?'book':'nav_'.$role)); ?></a><?php endforeach; ?></div>
			<div><h2><?php echo esc_html(tfd_copy('nav_locations')); ?></h2><?php foreach(tfd_get_published_locations() as $location): ?><a href="<?php echo esc_url(get_permalink($location)); ?>"><strong><?php echo esc_html(tfd_get_field('city',$location->ID)); ?></strong><br><?php echo esc_html(tfd_format_location_address($location->ID)); ?></a><?php endforeach; ?><a href="<?php echo esc_url(tfd_page_url('locations')); ?>"><?php echo esc_html(tfd_copy('our_locations')); ?> →</a></div>
		</div>
		<div class="tfd-shell tfd-footer__bottom"><span>© <?php echo esc_html(wp_date('Y')); ?> Trust Family Dental. <?php echo esc_html(tfd_copy('copyright')); ?></span><div><a href="<?php echo esc_url(tfd_page_url('privacy')); ?>"><?php echo esc_html(tfd_copy('privacy')); ?></a><nav class="tfd-languages" aria-label="<?php echo esc_attr(tfd_copy('language_label')); ?>"><?php tfd_languages(); ?></nav></div></div>
	</footer>
</div>
<?php wp_footer(); ?>
</body>
</html>
