<?php
/** Single doctor template. @package Trust_Family_Dental */

defined( 'ABSPATH' ) || exit;
get_header();

while ( have_posts() ) :
	the_post();
	$doctor_id  = get_the_ID();
	$specialty  = trim( (string) tfd_get_field( 'doctor_specialty', $doctor_id ) );
	$credentials = trim( (string) tfd_get_field( 'doctor_credentials', $doctor_id ) );
	$experience = absint( tfd_get_field( 'doctor_experience', $doctor_id ) );
	?>
	<main id="content" class="content-area page-wrapper tfd-doctor-profile">
		<div class="container">
			<div class="row row-large align-middle">
				<div class="col large-4 medium-5 small-12 tfd-doctor-profile__photo">
					<?php if ( has_post_thumbnail() ) : ?>
						<?php the_post_thumbnail( 'large', array( 'loading' => 'eager', 'fetchpriority' => 'high' ) ); ?>
					<?php endif; ?>
				</div>
				<div class="col large-8 medium-7 small-12">
					<h1><?php the_title(); ?></h1>
					<?php if ( $specialty ) : ?><p class="tfd-doctor-profile__specialty"><?php echo esc_html( $specialty ); ?></p><?php endif; ?>
					<?php if ( $experience || $credentials ) : ?>
						<div class="tfd-doctor-profile__meta">
							<?php if ( $experience ) : ?><p><strong><?php echo esc_html( tfd_tr( 'experience' ) ); ?>:</strong> <?php echo esc_html( $experience . ' ' . tfd_tr( 'years' ) ); ?></p><?php endif; ?>
							<?php if ( $credentials ) : ?><p><strong><?php echo esc_html( tfd_tr( 'credentials' ) ); ?>:</strong><br><?php echo nl2br( esc_html( $credentials ) ); ?></p><?php endif; ?>
						</div>
					<?php endif; ?>
					<div class="entry-content single-page"><?php the_content(); ?></div>
				</div>
			</div>
		</div>
	</main>
	<?php
endwhile;

get_footer();
