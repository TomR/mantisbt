<?php
# MantisBT - A PHP based bugtracking system

# MantisBT is free software: you can redistribute it and/or modify
# it under the terms of the GNU General Public License as published by
# the Free Software Foundation, either version 2 of the License, or
# (at your option) any later version.
#
# MantisBT is distributed in the hope that it will be useful,
# but WITHOUT ANY WARRANTY; without even the implied warranty of
# MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
# GNU General Public License for more details.
#
# You should have received a copy of the GNU General Public License
# along with MantisBT.  If not, see <http://www.gnu.org/licenses/>.

/**
 * Check login then redirect to main_page.php or to login_page.php
 *
 * @package MantisBT
 * @copyright Copyright (C) 2000 - 2002  Kenzaburo Ito - kenito@300baud.org
 * @copyright Copyright (C) 2002 - 2012  MantisBT Team - mantisbt-dev@lists.sourceforge.net
 * @link http://www.mantisbt.org
 *
 * @uses core.php
 * @uses authentication_api.php
 * @uses config_api.php
 * @uses constant_inc.php
 * @uses gpc_api.php
 * @uses print_api.php
 * @uses session_api.php
 * @uses string_api.php
 */

/**
 * MantisBT Core API's
 */
require_once( 'core.php' );
require_api( 'authentication_api.php' );
require_api( 'config_api.php' );
require_api( 'constant_inc.php' );
require_api( 'gpc_api.php' );
require_api( 'print_api.php' );
require_api( 'session_api.php' );
require_api( 'string_api.php' );

$f_username		= gpc_get_string( 'username', '' );
$f_password		= gpc_get_string( 'password', '' );
$f_perm_login	= gpc_get_bool( 'perm_login' );
$t_return		= string_url( string_sanitize_url( gpc_get_string( 'return', config_get( 'default_home_page' ) ) ) );
$f_from			= gpc_get_string( 'from', '' );
$f_secure_session = gpc_get_bool( 'secure_session', false );

$f_username = auth_prepare_username($f_username);
$f_password = auth_prepare_password($f_password);

gpc_set_cookie( config_get_global( 'cookie_prefix' ) . '_secure_session', $f_secure_session ? '1' : '0' );

if ( auth_attempt_login( $f_username, $f_password, $f_perm_login ) ) {
	session_set( 'secure_session', $f_secure_session );

	$t_redirect_url = 'login_cookie_test.php?return=' . $t_return;

} else {
	$t_redirect_url = 'login_page.php?return=' . $t_return .
		'&error=1&username=' . urlencode( $f_username ) .
		'&perm_login=' . ( $f_perm_login ? 1 : 0 ) .
		'&secure_session=' . ( $f_secure_session ? 1 : 0 );

	if ( HTTP_AUTH == config_get( 'login_method' ) ) {
		auth_http_prompt();
		exit;
	}
}

print_header_redirect( $t_redirect_url );
