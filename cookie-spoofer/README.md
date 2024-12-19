# Cookie Spoofer

**Version:** 1.0.0  
**Author:** HKW ([hkw.io](https://hkw.io))  

## Description

Cookie Spoofer is a lightweight WordPress plugin designed to set a fake "logged-in" cookie. This cookie helps bypass WPEngine's caching mechanisms for certain users or pages, allowing dynamic content to display properly.

## Features

- Sets a fake "logged-in" cookie if no such cookie exists.
- Automatically expires the cookie after one hour.
- Includes a function to manually remove the fake cookie if needed.

## Installation

1. Download the `cookie-spoofer` folder or create a new folder in your plugins directory with this code.
2. Upload the plugin to your WordPress installation under `/wp-content/plugins/`.
3. Activate the plugin through the WordPress Admin Dashboard under **Plugins**.

## Usage

- The plugin runs automatically after activation.
- Hooks into WordPress' `init` action to check for and set a fake logged-in cookie if one does not exist.

### Functions

- **`maybe_set_user_cookie()`**  
  Checks if a logged-in cookie exists and sets one if it doesn’t.
  
- **`remove_user_cookie()`**  
  Removes the fake logged-in cookie by setting its expiration in the past.

- **`set_fake_user_logged_in_cookie($expire)`**  
  Generates and sets a new fake logged-in cookie with a specified expiration time.

- **`has_logged_in_user_cookie()`**  
  Checks if a WordPress "logged-in" cookie exists in the current session.

## Notes

- This plugin is specifically designed for environments like WPEngine where caching can interfere with dynamic content.
- Use with caution as it bypasses caching, potentially impacting performance on high-traffic sites.
