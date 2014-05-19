<?php

class WPtouchArrayIterator {
	var $array;
	var $cur_pos;
	var $count;
	var $cur_key;

	function WPtouchArrayIterator( $a ) {
		$this->array = $a;
		$this->cur_pos = 0;
		$this->count = count( $a );
		$this->cur_key = false;

		if ( is_array( $this->array ) ) {
			@reset( $this->array );
		}

	}

	function rewind() {
		$this->cur_pos = 0;
	}

	function have_items() {
		$has_items = ( $this->cur_pos < $this->count );
		if ( !$has_items ) {
			// force a reset after returning false
			$this->cur_pos = 0;

			if ( is_array( $this->array ) ) {
				reset( $this->array );
			}

		}

		return $has_items;
	}

	function the_item() {
		if ( $this->cur_pos == 0 ) {
			$item = current( $this->array );
			$this->cur_key = key( $this->array );
		} else {
			$item = next( $this->array );
			$this->cur_key = key( $this->array );
		}

		$this->cur_pos++;

		return $item;
	}

	function current_position() {
		return $this->cur_pos;
	}

	function the_key() {
		return $this->cur_key;
	}
}