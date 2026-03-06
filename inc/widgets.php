<?php
/**
 * Widget registration
 *
 * @package censkills-theme
 */

/**
 * Register widget area.
 *
 * @link https://developer.wordpress.org/themes/functionality/sidebars/#registering-a-sidebar
 */
function censkills_theme_widgets_init() {
	register_sidebar(
		array(
			'name'          => esc_html__( 'Sidebar', 'censkills-theme' ),
			'id'            => 'sidebar-1',
			'description'   => esc_html__( 'Add widgets here.', 'censkills-theme' ),
			'before_widget' => '<section id="%1$s" class="widget %2$s">',
			'after_widget'  => '</section>',
			'before_title'  => '<h2 class="widget-title">',
			'after_title'   => '</h2>',
		)
	);

	register_sidebar(
		array(
			'name'          => esc_html__( 'Front Page Banners', 'censkills-theme' ),
			'id'            => 'front-page-banners',
			'description'   => esc_html__( 'Add banners here to display on the front page.', 'censkills-theme' ),
			'before_widget' => '<div id="%1$s" class="swiper-slide hero-section widget %2$s">',
			'after_widget'  => '</div>',
			'before_title'  => '<h2 class="screen-reader-text">',
			'after_title'   => '</h2>',
		)
	);

	register_widget( 'CenSkills_Banner_Widget' );
}
add_action( 'widgets_init', 'censkills_theme_widgets_init' );

/**
 * Register Banner Widget
 */
class CenSkills_Banner_Widget extends WP_Widget {

	public function __construct() {
		parent::__construct(
			'censkills_banner_widget',
			esc_html__( 'CenSkills Banner', 'censkills-theme' ),
			array( 'description' => esc_html__( 'Add a hero banner with title, subtitle, image, and buttons.', 'censkills-theme' ) )
		);
	}

	public function widget( $args, $instance ) {
		echo $args['before_widget'];

		$title = ! empty( $instance['title'] ) ? $instance['title'] : '';
		$subtitle = ! empty( $instance['subtitle'] ) ? $instance['subtitle'] : '';
		$image_url = ! empty( $instance['image_url'] ) ? $instance['image_url'] : '';
		$btn1_text = ! empty( $instance['btn1_text'] ) ? $instance['btn1_text'] : '';
		$btn1_url = ! empty( $instance['btn1_url'] ) ? $instance['btn1_url'] : '';
		$btn2_text = ! empty( $instance['btn2_text'] ) ? $instance['btn2_text'] : '';
		$btn2_url = ! empty( $instance['btn2_url'] ) ? $instance['btn2_url'] : '';

		?>
		<div class="hero-image" style="background-image: url('<?php echo esc_url( $image_url ); ?>');">
		</div>
		<div class="hero-content text-center">
			<?php if ( $title ) : ?>
				<h2 class="hero-title text-white"><?php echo esc_html( $title ); ?></h2>
			<?php endif; ?>
			<?php if ( $subtitle ) : ?>
				<p class="hero-subtitle text-white"><?php echo esc_html( $subtitle ); ?></p>
			<?php endif; ?>
			<div class="flex justify-center gap-md mt-lg">
				<?php if ( $btn1_text && $btn1_url ) : ?>
					<a href="<?php echo esc_url( $btn1_url ); ?>" class="btn btn-primary" style="background-color: white; color: black;"><?php echo esc_html( $btn1_text ); ?></a>
				<?php endif; ?>
				<?php if ( $btn2_text && $btn2_url ) : ?>
					<a href="<?php echo esc_url( $btn2_url ); ?>" class="btn btn-outline" style="border-color: white; color: white;"><?php echo esc_html( $btn2_text ); ?></a>
				<?php endif; ?>
			</div>
		</div>
		<?php

		echo $args['after_widget'];
	}

	public function form( $instance ) {
		$title = ! empty( $instance['title'] ) ? $instance['title'] : esc_html__( 'THƯƠNG HIỆU THỜI TRANG CHẤT LƯỢNG', 'censkills-theme' );
		$subtitle = ! empty( $instance['subtitle'] ) ? $instance['subtitle'] : esc_html__( 'Trải nghiệm mua sắm tuyệt vời cùng CenSkills. Sản xuất tại Việt Nam.', 'censkills-theme' );
		$image_url = ! empty( $instance['image_url'] ) ? $instance['image_url'] : 'https://placehold.co/1920x800/000000/ffffff?text=CenSkills+Banner';
		$btn1_text = ! empty( $instance['btn1_text'] ) ? $instance['btn1_text'] : esc_html__( 'Mua Ngay', 'censkills-theme' );
		$btn1_url = ! empty( $instance['btn1_url'] ) ? $instance['btn1_url'] : '#';
		$btn2_text = ! empty( $instance['btn2_text'] ) ? $instance['btn2_text'] : esc_html__( 'Khám Phá', 'censkills-theme' );
		$btn2_url = ! empty( $instance['btn2_url'] ) ? $instance['btn2_url'] : '#';
		?>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php esc_attr_e( 'Title:', 'censkills-theme' ); ?></label>
			<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>">
		</p>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'subtitle' ) ); ?>"><?php esc_attr_e( 'Subtitle:', 'censkills-theme' ); ?></label>
			<textarea class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'subtitle' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'subtitle' ) ); ?>"><?php echo esc_textarea( $subtitle ); ?></textarea>
		</p>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'image_url' ) ); ?>"><?php esc_attr_e( 'Image URL:', 'censkills-theme' ); ?></label>
			<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'image_url' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'image_url' ) ); ?>" type="text" value="<?php echo esc_attr( $image_url ); ?>">
		</p>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'btn1_text' ) ); ?>"><?php esc_attr_e( 'Button 1 Text:', 'censkills-theme' ); ?></label>
			<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'btn1_text' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'btn1_text' ) ); ?>" type="text" value="<?php echo esc_attr( $btn1_text ); ?>">
		</p>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'btn1_url' ) ); ?>"><?php esc_attr_e( 'Button 1 URL:', 'censkills-theme' ); ?></label>
			<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'btn1_url' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'btn1_url' ) ); ?>" type="text" value="<?php echo esc_attr( $btn1_url ); ?>">
		</p>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'btn2_text' ) ); ?>"><?php esc_attr_e( 'Button 2 Text:', 'censkills-theme' ); ?></label>
			<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'btn2_text' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'btn2_text' ) ); ?>" type="text" value="<?php echo esc_attr( $btn2_text ); ?>">
		</p>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'btn2_url' ) ); ?>"><?php esc_attr_e( 'Button 2 URL:', 'censkills-theme' ); ?></label>
			<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'btn2_url' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'btn2_url' ) ); ?>" type="text" value="<?php echo esc_attr( $btn2_url ); ?>">
		</p>
		<?php
	}

	public function update( $new_instance, $old_instance ) {
		$instance = array();
		$instance['title']     = ( ! empty( $new_instance['title'] ) ) ? sanitize_text_field( $new_instance['title'] ) : '';
		$instance['subtitle']  = ( ! empty( $new_instance['subtitle'] ) ) ? sanitize_textarea_field( $new_instance['subtitle'] ) : '';
		$instance['image_url'] = ( ! empty( $new_instance['image_url'] ) ) ? esc_url_raw( $new_instance['image_url'] ) : '';
		$instance['btn1_text'] = ( ! empty( $new_instance['btn1_text'] ) ) ? sanitize_text_field( $new_instance['btn1_text'] ) : '';
		$instance['btn1_url']  = ( ! empty( $new_instance['btn1_url'] ) ) ? esc_url_raw( $new_instance['btn1_url'] ) : '';
		$instance['btn2_text'] = ( ! empty( $new_instance['btn2_text'] ) ) ? sanitize_text_field( $new_instance['btn2_text'] ) : '';
		$instance['btn2_url']  = ( ! empty( $new_instance['btn2_url'] ) ) ? esc_url_raw( $new_instance['btn2_url'] ) : '';
		return $instance;
	}
}
