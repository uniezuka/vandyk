<?php

/**
 * The goal of this file is to allow developers a location
 * where they can overwrite core procedural functions and
 * replace them with their own. This file is loaded during
 * the bootstrap process and is called during the framework's
 * execution.
 *
 * This can be looked at as a `master helper` file that is
 * loaded early on, and may also contain additional functions
 * that you'd like to use throughout your entire application
 *
 * @see: https://codeigniter4.github.io/CodeIgniter4/
 */
if (! function_exists('is_logged_in')) {
    function is_logged_in()
    {
        // Ensure the session is loaded
        if (session_status() === PHP_SESSION_NONE && ENVIRONMENT !== 'testing') {
            session(); // @codeCoverageIgnore
        }

        return session()->get('isLoggedIn');
    }
}

if (! function_exists('is_admin')) {
    function is_admin()
    {
        // Ensure the session is loaded
        if (session_status() === PHP_SESSION_NONE && ENVIRONMENT !== 'testing') {
            session(); // @codeCoverageIgnore
        }

        return session()->get('isAdmin');
    }
}