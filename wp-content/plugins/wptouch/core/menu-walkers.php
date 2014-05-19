<?php

function wptouch_menu_walker_get_classes( $item, $has_icon = true ) {
	$clear_classes = array( 'menu-item' );

	if ( isset( $item->classes ) ) {
		foreach( $item->classes as $key => $value ) {
			if ( is_string ( $value ) && strlen( $value ) ) {
				if ( !in_array( $value, $clear_classes ) ) {
					$clear_classes[] = $value;
				}
			}
		}
	}

	if ( !$has_icon ) {
		$clear_classes[] = 'no-icon';
	}

	return implode( ' ', apply_filters( 'wptouch_menu_item_classes', $clear_classes, $item ) );
}

function wptouch_menu_walker_the_classes( $classes ) {
	echo wptouch_menu_walker_get_classes( $classes );
}

class WPtouchProNavMenuWalker extends Walker_Nav_Menu {}

class WPtouchProMainNavMenuWalker extends WPtouchProNavMenuWalker {
	var $last_item;
	var $skipping_item;
	var $pending_levels;
	var $show_menu_icons;

	function __construct( $show_menu_icons = true ) {
		$this->show_menu_icons = $show_menu_icons;
	}

	function output_last_item( &$output ) {
		if ( $this->last_item->object == 'custom' || $this->last_item->object == 'category' ) {
			$link = $this->last_item->url;
		} else {
			$link = get_permalink( $this->last_item->object_id );
		}

		$target = '';
		if ( $this->last_item->target == '_blank' ) {
			$target = ' target="_blank"';
		}

 		$output .= '<a href="' . $link . '" class="title"' . $target . '>' . $this->last_item->title . '</a>';
 		$this->last_item = false;
	}

	function start_lvl( &$output, $depth=0, $args=array() ) {
 		if ( $this->last_item ) {
 			$this->output_last_item( $output );
 		}

	 	$output .= '<ul>';
	 }

	function end_lvl( &$output, $depth=0, $args=array() ) {
		$output .= '</ul>';
	}

	function start_el( &$output, $item, $depth=0, $args=array(), $current_object_id = 0 ) {
		$this->skipping_item = wptouch_menu_is_disabled( $item->ID );

		if ( !$this->skipping_item ) {
			$output .= '<li class="' . wptouch_menu_walker_get_classes( $item, $this->show_menu_icons ) . '">';

			if ( $this->show_menu_icons ) {
				$output .= '<img src="' . wptouch_get_menu_icon( $item->ID ) . '" alt="menu-icon" />';
			}

			$this->last_item = $item;
		}
	}

 	function end_el( &$output, $item, $depth=0, $args=array() ) {
 		if ( !$this->skipping_item ) {
 			if ( $this->last_item ) {
				$this->output_last_item( $output );
 			}

 			$output .= "</li>";
 		}
 	}
}

class WPtouchProPageWalker extends Walker_Page {}

class WPtouchProMainPageMenuWalker extends WPtouchProPageWalker {
	var $last_item;
	var $skipping_item;
	var $show_menu_icons;

	function __construct( $show_menu_icons = true ) {
		$this->show_menu_icons = $show_menu_icons;
	}

	function output_last_item( &$output ) {
		$output .= '<a href="' . get_permalink( $this->last_item->ID ) . '" class="title">' . $this->last_item->post_title . '</a>';
		$this->last_item = false;
	}

	function start_lvl( &$output, $depth=0, $args=array() ) {
 		if ( $this->last_item ) {
 			$this->output_last_item( $output );
 		}

	 	$output .= '<ul>';
	 }

	function end_lvl( &$output, $depth=0, $args=array() ) {
		$output .= '</ul>';
	}

	function start_el( &$output, $item, $depth=0, $args=array(), $current_object_id = 0 ) {
		$this->skipping_item = wptouch_menu_is_disabled( $item->ID );

		if ( !$this->skipping_item ) {
			$output .= '<li class="' . wptouch_menu_walker_get_classes( $item, $this->show_menu_icons ) . '">';

			if ( $this->show_menu_icons ) {
				$output .= '<img src="' . wptouch_get_menu_icon( $item->ID ) . '" alt="menu-icon" />';
			}

			$this->last_item = $item;
		}
	}

 	function end_el( &$output, $item, $depth=0, $args=array() ) {
  		if ( !$this->skipping_item ) {
	 		if ( $this->last_item ) {
	 			$this->output_last_item( $output );
	 		}

	 		$output .= "</li>";
	 	}
 	}
}

class WPtouchProAdminNavMenuWalker extends WPtouchProNavMenuWalker  {
	var $last_item;

	function start_lvl( &$output, $depth=0, $args=array() ) {
 		if ( $this->last_item ) {
 			$output .= '<a href="#" class="expand title">' . $this->last_item->title . '</a>';
 			$this->last_item = false;
 		}

	 	$output .= '<ul>';
	 }

	function end_lvl( &$output, $depth=0, $args=array() ) {
		$output .= '</ul>';
	}

	function start_el( &$output, $item, $depth=0, $args=array(), $current_object_id = 0 ) {
		$output .= '<li class="' . wptouch_menu_walker_get_classes( $item ) . '">';
		$output .= '<div class="drop-target" data-object-id="' . $item->ID . '">';
		$output .= '<img src="' . wptouch_get_menu_icon( $item->ID ) . '" alt="menu-icon" />';
		$output .= '</div>';

		$output .= '<div class="menu-enable">';
		$output .= '<input class="checkbox" type="checkbox" data-object-id="' . $item->ID . '"';

		if ( !wptouch_menu_is_disabled( $item->ID ) ) $output .= " checked ";
		$output .= '/></div>';

		$this->last_item = $item;
	}

 	function end_el( &$output, $item, $depth=0, $args=array() ) {
 		if ( $this->last_item ) {
 			$output .= '<span class="title">' . $item->title . '</span>';
 			$this->last_item = false;
 		}

 		$output .= "</li>";
 	}
}

class WPtouchProAdminPageMenuWalker extends WPtouchProPageWalker {
	var $last_item;

	function start_lvl( &$output, $depth=0, $args=array() ) {
 		if ( $this->last_item ) {
 			$output .= '<a href="#" class="expand title">' . $this->last_item->post_title . '</a>';
 			$this->last_item = false;
 		}

	 	$output .= '<ul>';
	 }

	function end_lvl( &$output, $depth=0, $args=array() ) {
		$output .= '</ul>';
	}

	function start_el( &$output, $item, $depth=0, $args=array(), $current_object_id = 0 ) {
		$output .= '<li class="' . wptouch_menu_walker_get_classes( $item ) . '">';
		$output .= '<div class="drop-target" data-object-id="' . $item->ID . '">';
		$output .= '<img src="' . wptouch_get_menu_icon( $item->ID ) . '" alt="menu-icon" />';
		$output .= '</div>';

		$output .= '<div class="menu-enable">';
		$output .= '<input class="checkbox" type="checkbox" data-object-id="' . $item->ID . '"';

		if ( !wptouch_menu_is_disabled( $item->ID ) ) $output .= " checked ";
		$output .= '/></div>';

		$this->last_item = $item;
	}

 	function end_el( &$output, $item, $depth=0, $args=array() ) {
 		if ( $this->last_item ) {
 			$output .= '<span class="title">' . $item->post_title . '</span>';
 			$this->last_item = false;
 		}

 		$output .= "</li>";
 	}
}