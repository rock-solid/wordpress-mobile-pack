<?php
/*
 * Mobile Smart Switcher Widget - allows manual switching to/from mobile and desktop themes
 */
/*  Copyright 2009 Dan Smart  (email : dan@dansmart.co.uk)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation; either version 2 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

class MobileSmartSwitcherWidget extends WP_Widget {

function MobileSmartSwitcherWidget() {
	parent::WP_Widget(false, $name='Mobile Smart Manual Switcher');
}

/**
 * Displays manual switcher link
 */
function widget($args, $instance) {
	global $mobile_smart;

  // Before widget
	echo $before_widget;

	// Widget title - only display if set
  if ($instance["title"])
  {
    echo $before_title . $instance["title"] . $after_title;
  }

	// Display the switcher link
  $mobile_smart->addSwitcherLink();

  // After widget
	echo $after_widget;
}

/**
 * Form processing... Dead simple.
 */
function update($new_instance, $old_instance) {
    $new_instance["title"] = esc_attr($new_instance["title"]);

	return $new_instance;
}

/**
 * The configuration form.
 */
function form($instance) {
?>
		<p>
			<label for="<?php echo $this->get_field_id("title"); ?>">
				<?php _e( 'Title' ); ?>:
				<input class="widefat" id="<?php echo $this->get_field_id("title"); ?>" name="<?php echo $this->get_field_name("title"); ?>" type="text" value="<?php echo esc_attr($instance["title"]); ?>" />
			</label>
		</p>
<?php
}

}

add_action( 'widgets_init', create_function('', 'return register_widget("MobileSmartSwitcherWidget");') );

?>
