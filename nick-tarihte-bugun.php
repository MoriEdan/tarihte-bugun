<?php 
/*
Plugin Name: Tarihte Bugün Widgeti
Plugin URI:  https://github.com/theaikoss/tarihte-bugun
Description: Tarihte Bugün olan olayları gösteren Widget eklentisi
Version:     1.0.0
Author:      TheAikoss
Author URI:  https://github.com/theaikoss
*/
	
	// Style ve script dosyalarını dahil etmek için filter
	add_filter('wp_enqueue_scripts','tarihteBugun_scripts',1);
	add_filter('wp_enqueue_scripts','tarihteBugun_styles');

	// Script dahil etme fonksiyonu
	function tarihteBugun_scripts(){
		wp_enqueue_script('bx-slider-script',plugins_url('assets/js/jquery.bxslider.min.js',__FILE__),array('jquery'),'',true);
	}

	// Style dahil etme fonksiyonu
	function tarihtebugun_styles(){
		wp_enqueue_style('bx-slider-style',plugins_url('assets/css/jquery.bxslider.css',__FILE__),false);
	}

	// Widget Sınıfı
	class TheAikoss_TarihteBugun extends WP_Widget{

		// Kurucu Method
		public function __construct(){
			parent::__construct(
				'tarihteBugun',
				__('Tarihte Bugün'), 
				array('description' => __('Tarihte bugün olan önemli olayları gösterir.'))
				);
		}

		// Frontend Methodu
		public function widget($args, $instance){ 
		
			// XML Oluşturma ve 
			$data = simplexml_load_file("http://www.tarihtebugun.gen.tr/rss.asp");
			$items = $data->channel->item; ?>
			
			<div class="widget">
				<h2 class="widget-title"><?php echo $instance['title']; ?></h2>
				<ul class="theaikoss-itemList">
				<?php foreach ($items as $item) { ?>
					<li class="theaikoss-item"><?php echo $item->title; ?></li>
				<?php } ?>		
				</ul>
			</div>
			<script type="text/javascript">
			jQuery(document).ready(function(){
				jQuery('.theaikoss-itemList').bxSlider({
					mode:"vertical",
					pager:false,
					ticker:true,
					tickerHover:true,
					speed:<?php echo $instance['hiz']; ?>0,
					useCSS:false
				});
			});

			</script>
		<?php }

		// Backend Form
		public function form($instance){
			$title = ! empty( $instance['title'] ) ? $instance['title'] : __( ' ' );
			$hiz = ! empty( $instance['hiz'] ) ? $instance['hiz'] : __( '9500' ); ?>
			<p>
				<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Başlık:' ); ?></label> 
				<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>">
			</p>
			<p>
				<label for="<?php echo $this->get_field_id('hiz'); ?>"><?php _e('Kayma Hızı: '); ?></label>
				<input class="widefat" id="<?php echo $this->get_field_id('hiz'); ?>" name="<?php echo $this->get_field_name('hiz'); ?>" type="text" value="<?php echo esc_attr( $hiz ); ?>">
			</p>
		<?php }

	}

	// Widget Register Fonksiyonu
	function register_tarihteBugun(){
		register_widget('TheAikoss_TarihteBugun');
	}

	// Widget Register Action Fonksiyonu
	add_action('widgets_init','register_tarihteBugun');
