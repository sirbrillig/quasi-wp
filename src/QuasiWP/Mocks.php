<?php
namespace QuasiWP;

class Mocks{
	public function mock_wp_misc() {
		\Spies\mock_function( 'get_posts' )->and_return( [] );
		\Spies\mock_function( 'wp_get_nav_menus' )->and_return( [] );
		\Spies\mock_function( 'get_terms' )->and_return( [] );
		\Spies\mock_function( 'wp_get_nav_menu_object' )->and_return( false );
		\Spies\mock_function( 'wp_get_nav_menu_items' )->and_return( false );
		\Spies\mock_function( 'wp_get_object_terms' )->and_return( [] );
		\Spies\mock_function( 'untrailingslashit' )->and_return( \Spies\passed_arg( 0 ) );
		\Spies\mock_function( 'wpcom_is_theme_demo_site' )->and_return( true );
		mock_functions( [
			'a8c_irc',
			'add_post_meta',
			'current_user_can',
			'current_user_can_for_blog',
			'get_blog_details',
			'get_current_user_id',
			'get_lang_code_by_id',
			'get_post_meta',
			'get_the_post_thumbnail_url',
			'graylist_flag',
			'is_automattic',
			'is_automattician',
			'is_jetpack_site',
			'queue_async_job',
			'set_post_thumbnail',
			'tracks_record_event',
			'update_post_meta',
			'wp_create_nav_menu',
			'wp_delete_post',
			'wp_delete_nav_menu',
			'get_blog_id_from_url',
			'wp_get_current_user',
			'wp_get_theme',
			'wp_insert_post',
			'wp_update_nav_menu_item',
			'wpcom_get_blog_owner',
			'wpcom_get_theme_demo_site',
			'xmpp_message',
		] );
	}

	public function mock_wp_utilities() {
		\Spies\mock_function( 'is_wp_error' )->and_return( function( $value ) {
			return ( $value instanceof WP_Error );
		} );

		\Spies\mock_function( 'require_lib' )->and_return( function( $filename ) {
			return $filename;
		} );

		\Spies\mock_function( 'wp_parse_str' )->and_return( function( $string, &$array ) {
			parse_str( $string, $array );
			if ( get_magic_quotes_gpc() )
				$array = stripslashes_deep( $array );
			$array = apply_filters( 'wp_parse_str', $array );
		} );

		\Spies\mock_function( 'wp_parse_args' )->and_return( function( $args, $defaults = '' ) {
			if ( is_object( $args ) )
				$r = get_object_vars( $args );
			elseif ( is_array( $args ) )
				$r =& $args;
			else
				wp_parse_str( $args, $r );
			if ( is_array( $defaults ) )
				return array_merge( $defaults, $r );
			return $r;
		} );
	}

	public function mock_wp_translation() {
		\Spies\mock_function( '_x' )->and_return( function( $text, $context, $domain = 'default' ) {
			$context;
			return $text;
		} );

		\Spies\mock_function( '__' )->and_return( function( $text, $domain = 'default' ) {
			return _x( $text, '', $domain );
		} );
	}

	public function mock_wp_themes() {
		\Spies\mock_function( 'get_stylesheet' )->and_return( function() {
			return get_option( 'stylesheet' );
		} );
		\Spies\mock_function( 'switch_theme' )->and_return( function( $slug ) {
			return update_option( 'stylesheet', $slug );
		} );
	}

	public function mock_wp_theme_mods() {
		\Spies\mock_function( 'get_theme_mods' )->and_return( function() {
			$option = 'theme_mods_' . get_stylesheet();
			return get_option( $option );
		} );
		\Spies\mock_function( 'get_theme_mod' )->and_return( function( $key, $default = null ) {
			$mods = get_theme_mods();
			if ( isset( $mods[ $key ] ) ) {
				return $mods[ $key ];
			}
			return $default;
		} );
		\Spies\mock_function( 'set_theme_mod' )->and_return( function( $key, $value ) use ( &$mock_theme_mods ) {
			$option = 'theme_mods_' . get_stylesheet();
			$mods = get_theme_mods();
			$mods[ $key ] = $value;
			update_option( $option, $mods );
		} );
		\Spies\mock_function( 'remove_theme_mod' )->and_return( function( $key ) use ( &$mock_theme_mods ) {
			$option = 'theme_mods_' . get_stylesheet();
			$mods = get_theme_mods();
			if ( ! isset( $mods[ $key ] ) ) {
				return;
			}
			unset( $mods[ $key ] );
			update_option( $option, $mods );
		} );
	}

	public function mock_wp_blog_stickers() {
		$mock_stickers = [];
		\Spies\mock_function( 'add_blog_sticker' )->and_return( function( $key ) use ( &$mock_stickers ) {
			if ( ! isset( $mock_stickers[ get_current_blog_id() ] ) ) {
				$mock_stickers[ get_current_blog_id() ] = [];
			}
			$mock_stickers[ get_current_blog_id() ][ $key ] = true;
		} );
		\Spies\mock_function( 'remove_blog_sticker' )->and_return( function( $key ) use ( &$mock_stickers ) {
			if ( ! isset( $mock_stickers[ get_current_blog_id() ] ) ) {
				$mock_stickers[ get_current_blog_id() ] = [];
			}
			unset( $mock_stickers[ get_current_blog_id() ][ $key ] );
		} );
		\Spies\mock_function( 'has_blog_sticker' )->and_return( function( $key ) use ( &$mock_stickers ) {
			return isset( $mock_stickers[ get_current_blog_id() ][ $key ] );
		} );
	}

	public function mock_wp_filters() {
		$mock_filters = [];
		\Spies\mock_function( 'add_filter' )->and_return( function( $hook, $handler, $priority = 10, $accepted_args = 1 ) use ( &$mock_filters ) {
			if ( ! isset( $mock_filters[ $hook ] ) ) {
				$mock_filters[ $hook ] = [];
			}
			$mock_filters[ $hook ][] = [ 'function_to_add' => $handler, 'priority' => $priority, 'accepted_args' => $accepted_args ];
		} );

		\Spies\mock_function( 'has_filter' )->and_return( function( $hook ) use ( &$mock_filters ) {
			return isset( $mock_filters[ $hook ] );
		} );
		\Spies\mock_function( 'remove_filter' )->and_return( function( $hook, $function_to_remove, $priority = 10 ) use ( &$mock_filters ) {
			if ( ! isset( $mock_filters[ $hook ] ) ) {
				return;
			}
			$mock_filters[ $hook ] = array_reduce( $mock_filters[ $hook ], function( $keep, $filter ) use ( &$function_to_remove ) {
				if ( $filter['function_to_add'] !== $function_to_remove ) {
					$keep[] = $filter;
				}
				return $keep;
			} );
		} );
		\Spies\mock_function( 'apply_filters' )->and_return( function( $hook, $value ) use ( &$mock_filters ) {
			if ( ! isset( $mock_filters[ $hook ] ) || ! is_array( $mock_filters[ $hook ] ) ) {
				return $value;
			}
			return array_reduce( $mock_filters[ $hook ], function( $prev, $filter ) {
				return call_user_func( $filter['function_to_add'], $prev );
			}, $value );
		} );
	}

	public function mock_wp_actions() {
		$mock_actions = [];
		\Spies\mock_function( 'add_action' )->and_return( function( $hook, $handler, $priority = 10, $accepted_args = 1 ) use ( &$mock_actions ) {
			if ( ! isset( $mock_actions[ $hook ] ) ) {
				$mock_actions[ $hook ] = [];
			}
			$mock_actions[ $hook ][] = [ 'function_to_add' => $handler, 'priority' => $priority, 'accepted_args' => $accepted_args ];
		} );

		\Spies\mock_function( 'has_action' )->and_return( function( $hook ) use ( &$mock_actions ) {
			return isset( $mock_actions[ $hook ] );
		} );
		\Spies\mock_function( 'remove_action' )->and_return( function( $hook, $function_to_remove, $priority = 10 ) use ( &$mock_actions ) {
			if ( ! isset( $mock_actions[ $hook ] ) ) {
				return;
			}
			$mock_actions[ $hook ] = array_reduce( $mock_actions[ $hook ], function( $keep, $action ) use ( &$function_to_remove ) {
				if ( $action['function_to_add'] !== $function_to_remove ) {
					$keep[] = $action;
				}
				return $keep;
			} );
		} );
		\Spies\mock_function( 'do_action' );
	}

	public function mock_wp_blog_switch() {
		$mock_blogs = array( 0 );
		\Spies\mock_function( 'switch_to_blog' )->and_return( function( $blog_id ) use ( &$mock_blogs ) {
			array_unshift( $mock_blogs, $blog_id );
		} );
		\Spies\mock_function( 'restore_current_blog' )->and_return( function() use ( &$mock_blogs ) {
			array_shift( $mock_blogs );
		} );
		\Spies\mock_function( 'get_current_blog_id' )->and_return( function() use ( &$mock_blogs ) {
			return $mock_blogs[0];
		} );
	}

	public function mock_wpdb() {
		global $wpdb;
		$wpdb = \Spies\mock_object()->and_ignore_missing();
		$wpdb->posts = 'posts';
		$wpdb->postmeta = 'postmeta';
	}

	public function mock_current_time() {
		\Spies\mock_function( 'current_time' )->and_return( function( $type ) {
			return date( $type );
		} );
		\Spies\mock_function( 'current_time' )->with( 'timestamp', \Spies\any() )->and_return( function() {
			return time();
		} );
		\Spies\mock_function( 'current_time' )->with( 'mysql', \Spies\any() )->and_return( function() {
			return gmdate( 'Y-m-d H:i:s' );
		} );
	}

	public function mock_wp_shortcodes() {
		$shortcodes = [];
		\Spies\mock_function( 'add_shortcode' )->and_return( function( $tag, $handler ) use ( &$shortcodes ) {
			$shortcodes[ $tag ] = $handler;
		} );
		\Spies\mock_function( 'shortcode_exists' )->and_return( function( $tag ) use ( &$shortcodes ) {
			return array_key_exists( $tag, $shortcodes );
		} );
		\Spies\mock_function( 'shortcode_atts' )->when_called->will_return( function( $pairs, $atts ) {
			$atts = ( array ) $atts;
			$out = array();
			foreach ( $pairs as $name => $default ) {
				if ( array_key_exists( $name, $atts ) )
					$out[ $name ] = $atts[ $name ];
				else
					$out[ $name ] = $default;
			}
			return $out;
		} );
	}
}
