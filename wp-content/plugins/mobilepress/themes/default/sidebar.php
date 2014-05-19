		<?php if ( mopr_get_option( 'show_pages' ) ): ?>
		<div id="heading">
			<h2>Pages</h2>
		</div>

		<ul id="list">
			<?php wp_list_pages( 'title_li=' ); ?>
		</ul>
		<?php endif; ?>

		<?php if ( mopr_get_option( 'show_categories' ) ): ?>
		<div id="heading">
			<h2>Categories</h2>
		</div>

		<ul id="list">
			<?php wp_list_categories( 'title_li=' ); ?>
		</ul>
		<?php endif; ?>