# Disable WooCommerce REST API Authentication

## Description

The "Disable WooCommerce REST API Authentication" plugin for WordPress overrides WooCommerce's capability checks to allow all REST API queries without authentication. This functionality is particularly useful for development environments where you want to test or develop against the WooCommerce REST API without the hassle of authenticating each request. **It is crucial to note that this plugin should never be used in production environments, as it removes critical security checks.**

## Features

- Allows all REST API queries by overriding WooCommerce capability checks.
- Logs REST requests for debugging purposes.
- Automatically authenticates users to bypass authentication processes.
- Disables REST API caching to ensure fresh data on every request.
- Checks if the plugin is being used in a development environment and deactivates itself if not.

## Installation

1. Download the plugin.
2. Upload the plugin files to the `/wp-content/plugins/disable-woocommerce-rest-api-auth` directory, or install the zipped plugin directory through the WordPress plugins screen directly.
3. Activate the plugin through the 'Plugins' screen in WordPress.
4. Note: The plugin will automatically deactivate if it detects that it is not running in a development environment (`WP_DEBUG` is not set to `true`).

## Usage

Once activated, the plugin automatically applies its settings. There is no configuration needed. All WooCommerce REST API requests will bypass the usual authentication checks, and detailed logs of these requests will be available for debugging purposes.

## Important Warning

This plugin is intended **only** for development environments. It disables important security measures provided by WooCommerce and should not be used on a live site. The plugin includes a safety feature that attempts to deactivate itself if it is not in a development environment, but you should always ensure that it is not active on any production sites.

## Author

HKW

## Version

0.1.0