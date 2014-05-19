<?php $menus = wps_get_menus(); ?>

<div class="wps-admin-option-group" id="wps-admin-menu">
	
	<div class="wps-admin-left-column metabox-holder">
		
		<p class="wps-admin-input-checkbox wps-admin-menu-enable">
			<input type="hidden" name="enable_menu" value="0"/>
			<input type="checkbox" name="enable_menu" id="enable-menu" value="1" <?php echo wps_checkbox_text( 'enable_menu' ) ?>/><label for="enable-menu">Enable drop-down pages/links menu.</label>
		</p>
		
		<!-- change the menu -->
		<div id="menu-options" class="postbox">
			<h3><span>Available Menus</span></h3>
			<div class="inside">
				<p>To change the mobile menu, select whether you'd like to use a current menu or a create a new one.</p>
				<p>
					<select id="site-menu" style="width:100%;">
						<option value=""></option>
						<option value="new">New Custom Menu</option>
						<?php foreach( $menus as $menu ) :  ?>
						
						<option value="<?php echo $menu->term_id; ?>"><?php echo $menu->name ?></option>
						
						<?php endforeach; ?>						
					</select>
				</p>
				
				<p class="button-controls">
					<span class="add-to-menu">
						<input type="submit" class="button-primary submit-add-to-menu" value="Update Menu" id="select-menu">
					</span>
				</p>
			</div>
		</div>
		
		<!-- add a custom link -->
		<div id="menu-add-customlinks" class="postbox">
			
			<h3><span>Custom Links</span></h3>
			<div class="inside">
				<div class="customlinkdiv" id="customlinkdiv">
					<p id="menu-item-url-wrap">
						<label class="howto" for="custom-menu-item-url">
							<span>URL</span>
							<input id="custom-menu-item-url" type="text" class="code menu-item-textbox" value="http://">
						</label>
					</p>
		
					<p id="menu-item-name-wrap">
						<label class="howto" for="custom-menu-item-name">
							<span>Label</span>
							<input id="custom-menu-item-name" type="text" class="regular-text menu-item-textbox">
						</label>
					</p>
		
					<p class="button-controls">
						<span class="add-to-menu">
							<input type="submit" class="button-secondary submit-add-to-menu" value="Add to Menu" id="add-customlink">
						</span>
					</p>
				</div>
			</div>
		</div>
		
		<!-- add a wordpress page -->
		<div id="menu-add-pages" class="postbox">
			
			<h3><span>Pages</span></h3>
			<div class="inside">
				<div class="posttypediv">
					<ul id="site-pages" class="categorychecklist">
						<?php foreach( wps_get_pages() as $wps_page ) : ?>
							<li><label class="menu-item-title"><input type="checkbox" class="menu-item-checkbox" value="<?php echo $wps_page['page_id']; ?>" data-title="<?php echo $wps_page['page_title']; ?>" data-guid="<?php echo $wps_page['guid']; ?>"> <?php echo $wps_page['page_title']; ?></label></li>
						<?php endforeach; ?>
					</ul>
					
					<p class="button-controls">
						<span class="add-to-menu">
							<input type="submit" class="button-secondary submit-add-to-menu" value="Add to Menu" id="add-pages">
						</span>
					</p>
				</div>
			</div>			
		</div>
		
		<!-- add a category -->
		<div id="menu-add-categories" class="postbox">
			
			<h3><span>Categories</span></h3>
			<div class="inside">
				<div class="posttypediv">
					<ul id="site-categories" class="categorychecklist">
						<?php foreach( wps_get_categories() as $category ) : ?>
							<li><label class="menu-item-title"><input type="checkbox" class="menu-item-checkbox" value="<?php echo $category['category_id']; ?>" data-title="<?php echo $category['category_title']; ?>" data-guid="<?php echo $category['link']; ?>"> <?php echo $category['category_title']; ?></label></li>
						<?php endforeach; ?>
					</ul>
					
					<p class="button-controls">
						<span class="add-to-menu">
							<input type="submit" class="button-secondary submit-add-to-menu" value="Add to Menu" id="add-categories">
						</span>
					</p>
				</div>
			</div>			
		</div>
		
	</div>

	<div class="wps-admin-right-column">
		
		<div class="wps-admin-menu-message <?php echo wps_get_option( 'enable_menu' ) ? 'hidden' : null; ?>" id="wps-menu-disabled-message">
			Menus aren't enabled, to enable them click the checkbox to the left.
		</div>
		
		<ul class="menu ui-sortable wps-admin-menu-links nav-menus-php <?php echo ! wps_get_option( 'enable_menu' ) ? 'hidden' : null; ?>" id="wps-edit-menu">
			
			<?php $i = 0; foreach( wps_get_menu_links() as $wps_menu_item ): ?>
			
			<li class="menu-item menu-item-edit-inactive">
				<dl class="menu-item-bar">
					<dt class="menu-item-handle">
						<span class="item-title"><?php echo $wps_menu_item['title'] ?></span>
						<span class="item-controls">
							<a class="item-edit" title="Edit Menu Item" href="#">Edit Menu Item</a>
						</span>
					</dt>
				</dl>
	
				<div class="menu-item-settings" style="display: none;">
					<p class="description description-thin">
						<label>Label<br>
							<input type="text" name="menu_links[<?php echo $i ?>][title]" class="widefat edit-menu-item-title" value="<?php echo $wps_menu_item['title'] ?>">
						</label>
					</p>

					<div class="menu-item-actions description-wide submitbox">
						<a class="item-delete submitdelete deletion" href="#">Remove</a> <span class="meta-sep">
					</div>
	
					<input type="hidden" name="menu_links[<?php echo $i ?>][url]" value="<?php echo $wps_menu_item['url'] ?>" />
					<input type="hidden" name="menu_links[<?php echo $i ?>][icon]" value="<?php echo $wps_menu_item['icon'] ?>" />
				</div><!-- .menu-item-settings-->
			</li>
			
			<?php $i++; endforeach; ?>
			
		</ul>
		
	</div>

</div>