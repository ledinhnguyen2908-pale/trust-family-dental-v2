<?php
/**
 * Single location template.
 *
 * @package Trust_Family_Dental
 */

defined( 'ABSPATH' ) || exit;

get_header();

while ( have_posts() ) :
	the_post();

	$location_id = get_the_ID();
	$short_name  = trim( (string) tfd_get_field( 'location_short_name', $location_id ) );
	$phone       = trim( (string) tfd_get_field( 'location_phone', $location_id ) );
	$email       = sanitize_email( tfd_get_field( 'location_email', $location_id ) );
	$hotline     = tfd_get_global_hotline( $location_id );
	$address     = tfd_format_location_address( $location_id );
	$hours       = tfd_get_opening_hours( $location_id );
	$map_embed   = tfd_normalize_map_embed_url( tfd_get_field( 'google_maps_embed_url', $location_id ) );
	$map_url     = esc_url( tfd_get_field( 'google_maps_url', $location_id ) );
	$gallery     = tfd_get_field( 'facility_gallery', $location_id );
	$doctors     = tfd_get_field( 'location_doctors', $location_id );
	$form        = tfd_get_appointment_form_shortcode();
	?>
	<main id="content" class="content-area page-wrapper tfd-location-page">
		<div class="container section-title-container">
			<header class="tfd-location-header">
				<?php if ( $short_name ) : ?>
					<p class="tfd-location-header__eyebrow"><?php echo esc_html( $short_name ); ?></p>
				<?php endif; ?>
				<h1><?php the_title(); ?></h1>
				<?php if ( has_excerpt() ) : ?>
					<div class="tfd-location-lead"><?php echo wp_kses_post( wpautop( get_the_excerpt() ) ); ?></div>
				<?php endif; ?>
			</header>
		</div>

		<div class="container">
			<div class="row row-large">
				<div class="col large-8 medium-7 small-12 tfd-location-content">
					<?php if ( has_post_thumbnail() ) : ?>
						<?php echo get_the_post_thumbnail( $location_id, 'large', array( 'class' => 'tfd-featured-image', 'loading' => 'eager', 'fetchpriority' => 'high' ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
					<?php endif; ?>

					<div class="entry-content single-page">
						<?php the_content(); ?>
					</div>

					<?php if ( is_array( $gallery ) && $gallery ) : ?>
						<section aria-labelledby="tfd-gallery-title">
							<h2 id="tfd-gallery-title"><?php echo esc_html( tfd_tr( 'facility_gallery' ) ); ?></h2>
							<div class="tfd-gallery">
								<?php foreach ( $gallery as $image ) : ?>
									<?php $image_id = is_array( $image ) ? ( $image['ID'] ?? 0 ) : (int) $image; ?>
									<?php if ( $image_id ) : ?>
										<figure class="tfd-gallery__item">
											<?php echo wp_get_attachment_image( $image_id, 'large', false, array( 'loading' => 'lazy', 'decoding' => 'async' ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
										</figure>
									<?php endif; ?>
								<?php endforeach; ?>
							</div>
						</section>
					<?php endif; ?>

					<section aria-labelledby="tfd-doctors-title">
						<h2 id="tfd-doctors-title"><?php echo esc_html( tfd_tr( 'doctors' ) ); ?></h2>
						<?php if ( is_array( $doctors ) && $doctors ) : ?>
							<div class="tfd-doctor-grid">
								<?php foreach ( $doctors as $doctor ) : ?>
									<?php
									$doctor_id = is_object( $doctor ) ? $doctor->ID : (int) $doctor;
									if(function_exists('pll_get_post')) $doctor_id=pll_get_post($doctor_id,tfd_language());
									elseif(has_filter('wpml_object_id')) $doctor_id=apply_filters('wpml_object_id',$doctor_id,'doctor',false);
									if ( ! $doctor_id || 'publish' !== get_post_status( $doctor_id ) ) {
										continue;
									}
									$specialty = trim( (string) tfd_get_field( 'doctor_specialty', $doctor_id ) );
									?>
									<article class="tfd-doctor-card">
										<a class="tfd-doctor-card__photo" href="<?php echo esc_url( get_permalink( $doctor_id ) ); ?>" aria-label="<?php echo esc_attr( get_the_title( $doctor_id ) ); ?>">
											<?php echo get_the_post_thumbnail( $doctor_id, 'medium', array( 'loading' => 'lazy', 'decoding' => 'async' ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
										</a>
										<div class="tfd-doctor-card__body">
											<h3><a href="<?php echo esc_url( get_permalink( $doctor_id ) ); ?>"><?php echo esc_html( get_the_title( $doctor_id ) ); ?></a></h3>
											<?php if ( $specialty ) : ?>
												<p class="tfd-doctor-card__specialty"><?php echo esc_html( $specialty ); ?></p>
											<?php endif; ?>
										</div>
									</article>
								<?php endforeach; ?>
							</div>
						<?php else : ?>
							<p><?php echo esc_html( tfd_tr( 'no_doctors' ) ); ?></p>
						<?php endif; ?>
					</section>

					<section id="appointment-form" class="tfd-booking-panel" data-current-location="<?php echo esc_attr( get_post_field( 'post_name', $location_id ) ); ?>" aria-labelledby="tfd-booking-title">
						<h2 id="tfd-booking-title"><?php echo esc_html( tfd_tr( 'book_here' ) ); ?></h2>
						<?php if ( $form ) : ?>
							<?php echo do_shortcode( $form ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Administrator-controlled shortcode. ?>
						<?php else : ?>
							<p><?php echo esc_html( tfd_tr( 'form_not_configured' ) ); ?></p>
							<?php if ( $hotline ) : ?>
								<a class="button primary" href="tel:<?php echo esc_attr( tfd_phone_href( $hotline ) ); ?>"><?php echo esc_html( tfd_tr( 'call_hotline' ) ); ?></a>
							<?php endif; ?>
						<?php endif; ?>
					</section>
				</div>

				<aside class="col large-4 medium-5 small-12" aria-label="<?php echo esc_attr( tfd_tr( 'location_details' ) ); ?>">
					<div class="tfd-location-sidebar">
						<section class="box tfd-info-box" aria-labelledby="tfd-location-details-title">
							<h2 id="tfd-location-details-title"><?php echo esc_html( tfd_tr( 'location_details' ) ); ?></h2>
							<ul class="tfd-nap-list">
								<?php if ( $address ) : ?>
									<li><strong><?php echo esc_html( tfd_tr( 'address' ) ); ?></strong><address><?php echo esc_html( $address ); ?></address></li>
								<?php endif; ?>
								<?php if ( $phone ) : ?>
									<li><strong><?php echo esc_html( tfd_tr( 'phone' ) ); ?></strong><a href="tel:<?php echo esc_attr( tfd_phone_href( $phone ) ); ?>"><?php echo esc_html( $phone ); ?></a></li>
								<?php endif; ?>
								<?php if ( $email ) : ?>
									<li><strong><?php echo esc_html( tfd_tr( 'email' ) ); ?></strong><a href="mailto:<?php echo esc_attr( $email ); ?>"><?php echo esc_html( $email ); ?></a></li>
								<?php endif; ?>
							</ul>
							<?php if ( $hotline ) : ?>
								<a class="tfd-hotline-button" href="tel:<?php echo esc_attr( tfd_phone_href( $hotline ) ); ?>"><?php echo esc_html( tfd_tr( 'call_hotline' ) ); ?>: <?php echo esc_html( $hotline ); ?></a>
							<?php endif; ?>
						</section>

						<?php if ( $hours ) : ?>
							<section class="box tfd-info-box" aria-labelledby="tfd-hours-title">
								<h2 id="tfd-hours-title"><?php echo esc_html( tfd_tr( 'opening_hours' ) ); ?></h2>
								<table class="tfd-hours"><tbody>
									<?php foreach ( $hours as $row ) : ?>
										<tr>
											<th scope="row"><?php echo esc_html( tfd_tr( $row['day'] ) ); ?></th>
											<td>
												<?php if ( $row['is_closed'] ) : ?>
													<?php echo esc_html( tfd_tr( 'closed' ) ); ?>
												<?php elseif(!$row['opens'] || !$row['closes']): ?>
													<?php echo esc_html(tfd_copy('hours_unconfirmed')); ?>
												<?php else : ?>
													<?php echo esc_html( tfd_format_time( $row['opens'] ) . ' – ' . tfd_format_time( $row['closes'] ) ); ?>
												<?php endif; ?>
												<?php if ( $row['note'] ) : ?><span class="tfd-hours__note"><?php echo esc_html( $row['note'] ); ?></span><?php endif; ?>
											</td>
										</tr>
									<?php endforeach; ?>
								</tbody></table>
							</section>
						<?php endif; ?>

						<?php if ( $map_embed ) : ?>
							<?php if(!$hours): ?><section class="box tfd-info-box"><h2><?php echo esc_html(tfd_tr('opening_hours')); ?></h2><p><?php echo esc_html(tfd_copy('hours_unconfirmed')); ?></p></section><?php endif; ?>
							<section class="box tfd-info-box" aria-labelledby="tfd-map-title">
								<h2 id="tfd-map-title"><?php echo esc_html( tfd_tr( 'map' ) ); ?></h2>
								<div class="tfd-map-placeholder" data-tfd-map data-map-src="<?php echo esc_attr( $map_embed ); ?>" data-map-title="<?php echo esc_attr( tfd_tr( 'map' ) . ' – ' . get_the_title() ); ?>">
									<button class="tfd-map-placeholder__button" type="button" data-tfd-map-button><?php echo esc_html( tfd_tr( 'view_map' ) ); ?></button>
									<noscript><?php if ( $map_url ) : ?><a href="<?php echo esc_url( $map_url ); ?>" rel="noopener noreferrer"><?php echo esc_html( tfd_tr( 'view_map' ) ); ?></a><?php endif; ?></noscript>
								</div>
								<?php if($map_url): ?><a class="tfd-text-link" href="<?php echo esc_url($map_url); ?>" target="_blank" rel="noopener noreferrer"><?php echo esc_html(tfd_copy('directions')); ?> ↗</a><?php endif; ?>
							</section>
						<?php endif; ?>
					</div>
				</aside>
			</div>
		</div>
	</main>
	<?php
endwhile;

get_footer();
