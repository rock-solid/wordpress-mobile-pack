		<div id="search">
			<form method="get" id="searchform" action="<?php bloginfo( 'home' ); ?>/">
			<fieldset>
				<input type="text" size="14" value="<?php the_search_query(); ?>" name="s" id="s" />
				<input type="submit" id="searchsubmit" value="Search" />
			</fieldset>
			</form>
		</div>