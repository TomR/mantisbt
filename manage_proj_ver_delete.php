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
 * @package MantisBT
 * @copyright Copyright (C) 2000 - 2002  Kenzaburo Ito - kenito@300baud.org
 * @copyright Copyright (C) 2002 - 2012  MantisBT Team - mantisbt-dev@lists.sourceforge.net
 * @link http://www.mantisbt.org
 *
 * @uses core.php
 * @uses access_api.php
 * @uses authentication_api.php
 * @uses config_api.php
 * @uses form_api.php
 * @uses gpc_api.php
 * @uses helper_api.php
 * @uses html_api.php
 * @uses lang_api.php
 * @uses print_api.php
 * @uses version_api.php
 */

/**
 * MantisBT Core API's
 */
require_once( 'core.php' );
require_api( 'access_api.php' );
require_api( 'authentication_api.php' );
require_api( 'config_api.php' );
require_api( 'form_api.php' );
require_api( 'gpc_api.php' );
require_api( 'helper_api.php' );
require_api( 'html_api.php' );
require_api( 'lang_api.php' );
require_api( 'print_api.php' );
require_api( 'version_api.php' );

form_security_validate( 'manage_proj_ver_delete' );

auth_reauthenticate();

$f_version_id = gpc_get_int( 'version_id' );

$t_version_info = version_get( $f_version_id );
$t_redirect_url = 'manage_proj_edit_page.php?project_id=' . $t_version_info->project_id;

access_ensure_project_level( config_get( 'manage_project_threshold' ), $t_version_info->project_id );

# Confirm with the user
helper_ensure_confirmed( lang_get( 'version_delete_sure' ) .
	'<br/>' . lang_get( 'version_label' ) . lang_get( 'word_separator' ) . $t_version_info->version,
	lang_get( 'delete_version_button' ) );

version_remove( $f_version_id );

form_security_purge( 'manage_proj_ver_delete' );

html_page_top( null, $t_redirect_url );
?>
<br />
<div>
<?php
echo lang_get( 'operation_successful' ).'<br />';
print_bracket_link( $t_redirect_url, lang_get( 'proceed' ) );
?>
</div>

<?php
html_page_bottom();
