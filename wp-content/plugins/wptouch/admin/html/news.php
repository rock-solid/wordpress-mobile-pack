<?php require_once ( ABSPATH . WPINC . '/class-simplepie.php' ); ?>
<?php $content = fetch_feed( 'http://www.wptouch.com/feed/' ); ?>
<?php if ( !is_wp_error( $content ) ) { ?>
	<?php $max_items = $content->get_item_quantity( WPTOUCH_MAX_NEWS_ITEMS ); ?>
	<?php $rss_items = $content->get_items( 0, $max_items ); ?>
	<div class="nano">
		<div class="content">
			<ul>
			<?php foreach( $rss_items as $item ) { ?>
				<li>
					<div class="date"><?php echo $item->get_date( 'F jS, Y' ); ?></div>
					<a href="<?php echo $item->get_permalink(); ?>" target="_new"><?php echo esc_html( $item->get_title() )	; ?></a>
					<span class="desc"><?php echo wptouch_split_string( $item->get_description(), 100 ); ?>&hellip;</span>
				</li>
			<?php } ?>
			</ul>
		</div>
	</div>
<?php } ?>