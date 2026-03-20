<?php

/**
 * WordPress Sentry Redis Object Cache Integration
 *
 * @internal This class is not part of the public API and may be removed or changed at any time.
 */
final class WP_Sentry_Redis_Object_Cache_Integration {

    /**
     * Holds the class instance.
     *
     * @var WP_Sentry_Redis_Object_Cache_Integration
     */
    private static $instance;

    /**
     * Get the Sentry admin page instance.
     *
     * @return WP_Sentry_Redis_Object_Cache_Integration
     */
    public static function get_instance(): WP_Sentry_Redis_Object_Cache_Integration {
        return self::$instance ?: self::$instance = new self;
    }

    /**
     * WP_Sentry_Redis_Object_Cache_Integration constructor.
     */
    protected function __construct() {
        add_action( 'redis_object_cache_error', array( $this, 'handle_redis_cache_failure' ), 10, 2 );
    }

    /**
     * Capture and send Redis Object Cache failures to Sentry.
     *
     * @param \Throwable $e The exception that was thrown.
     * @param string $message The exception message.
     * @return void
     */
    public function handle_redis_cache_failure($e, $message ) {
        wp_sentry_safe(
            function ( \Sentry\State\HubInterface $client ) use ( $e ) {
                $client->captureException( $e );
            }
        );
    }

}