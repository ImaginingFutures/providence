<?php
/* ----------------------------------------------------------------------
 * app/views/system/preferences_duplication_html.php : 
 * ----------------------------------------------------------------------
 * CollectiveAccess
 * Open-source collections management software
 * ----------------------------------------------------------------------
 *
 * Software by Whirl-i-Gig (http://www.whirl-i-gig.com)
 * Copyright 2011-2016 Whirl-i-Gig
 *
 * For more information visit http://www.CollectiveAccess.org
 *
 * This program is free software; you may redistribute it and/or modify it under
 * the terms of the provided license as published by Whirl-i-Gig
 *
 * CollectiveAccess is distributed in the hope that it will be useful, but
 * WITHOUT ANY WARRANTIES whatsoever, including any implied warranty of 
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  
 *
 * This source code is free and modifiable under the terms of 
 * GNU General Public License. (http://www.gnu.org/copyleft/gpl.html). See
 * the "license.txt" file for details, or visit the CollectiveAccess web site at
 * http://www.CollectiveAccess.org
 *
 * ----------------------------------------------------------------------
 */
 
 	$va_duplicable_tables = array(
		'ca_objects', 'ca_object_lots', 'ca_entities', 'ca_places', 'ca_occurrences', 'ca_collections', 'ca_storage_locations',
		'ca_loans', 'ca_movements', 'ca_lists', 'ca_list_items', 'ca_tours', 'ca_tour_stops', 'ca_sets', 'ca_bundle_displays'
	);
	$vs_current_table = 'ca_'.$this->request->getActionExtra();	// url action extra is table name without "ca_" (eg. places => ca_places)
	if (!in_array($vs_current_table, $va_duplicable_tables)) { $vs_current_table = null; }
	
	$t_user = $this->getVar('t_user');
	$vs_group = $this->getVar('group');
	
 
 ?>
<div class="sectionBox">
<?php
	print $vs_control_box = caFormControlBox(
		caFormSubmitButton($this->request, __CA_NAV_BUTTON_SAVE__, _t("Save"), 'PreferencesForm').' '.
		caNavButton($this->request, __CA_NAV_BUTTON_CANCEL__, _t("Reset"), '', 'system', 'Preferences', $this->request->getAction(), array()), 
		'', 
		''
	);

	$va_group_info = $t_user->getPreferenceGroupInfo($vs_group);
	print "<h1>"._t("Preferences").": "._t($va_group_info['name'])."</h1>\n";
	
	print caFormTag($this->request, 'Save', 'PreferencesForm');
	
	$va_prefs = $t_user->getValidPreferences($vs_group);
	
	
	$o_dm = Datamodel::load();
	print "<div class='preferenceSectionDivider'><!-- empty --></div>\n"; 
	
	//foreach($va_duplicable_tables as $vs_table) {
		if (caTableIsActive($vs_current_table) && $this->request->user->canDoAction('can_duplicate_'.$vs_current_table)) {
			$t_instance = $o_dm->getInstanceByTableName($vs_current_table, true);
			print "<h2>"._t('Settings for %1', $t_instance->getProperty('NAME_PLURAL'))."</h2>";
		
			print "<table width='100%'><tr valign='top'><td width='250'>";
			foreach($va_prefs as $vs_pref) {
				if ($vs_pref == 'duplicate_relationships') { continue; }
				print $t_user->preferenceHtmlFormElement("{$vs_current_table}_{$vs_pref}", null, array());
			}
			print "</td>";
			if (in_array("duplicate_relationships", $va_prefs)) {
				print "<td>".$t_user->preferenceHtmlFormElement("{$vs_current_table}_duplicate_relationships", null, array('useTable' => true, 'numTableColumns' => 3))."</td>";
			}
		
			print "</tr></table>\n";
			print "<div class='preferenceSectionDivider'><!-- empty --></div>\n"; 
		}

?>
		<input type="hidden" name="action" value="<?php print $this->request->getAction(); ?>"/>
	</form>
<?php
	print $vs_control_box;
?>
</div>

	<div class="editorBottomPadding"><!-- empty --></div>