<?php
/*##################################################
 *                            common.php
 *                            -------------------
 *   begin                : August 1, 2013
 *   copyright            : (C) 2013 Julien BRISWALTER
 *   email                : julienseth78@phpboost.com
 *
 *  
 ###################################################
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 * 
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
 *
 ###################################################*/


 ####################################################
 #						English						#
 ####################################################
 
//Title 
$lang['module_title'] = 'Contact';
$lang['module_config_title'] = 'Contact module configuration';

//Contact form
$lang['contact.form.message'] = 'Message';
$lang['contact.tracking_number'] = 'tracking number';
$lang['contact.acknowledgment_title'] = 'Confirmation';
$lang['contact.acknowledgment'] = 'Your message has been successfully sent.

';

//Admin
$lang['admin.config.title'] = 'Form title';
$lang['admin.config.informations_bloc'] = 'Informations bloc';
$lang['admin.config.informations_enabled'] = 'Display the informations bloc';
$lang['admin.config.informations_content'] = 'Informations bloc content';
$lang['admin.config.informations.explain'] = 'This bloc permits to display informations (i.e. a contact phone number) on the left, top, right or bottom the contact form.';
$lang['admin.config.informations_position'] = 'Informations bloc position';
$lang['admin.config.informations.position_left'] = 'Left';
$lang['admin.config.informations.position_top'] = 'Top';
$lang['admin.config.informations.position_right'] = 'Right';
$lang['admin.config.informations.position_bottom'] = 'Bottom';
$lang['admin.config.tracking_number_enabled'] = 'Generate a tracking number for each email sent';
$lang['admin.config.date_in_date_in_tracking_number_enabled'] = 'Display day date in the tracking number';
$lang['admin.config.date_in_date_in_tracking_number_enabled.explain'] = 'Allows to generate a tracking number like <b>yyyymmdd-number</b>';
$lang['admin.config.sender_acknowledgment_enabled'] = 'Send an acknowledgment';
$lang['admin.authorizations.read']  = 'Permission to display the contact form';
$lang['admin.authorizations.display_field']  = 'Permission to display the field';

//Fields
$lang['admin.fields.manage'] = 'Fields management';
$lang['admin.fields.manage.page_title'] = 'Contact module form fields management';
$lang['admin.fields.title.add_field'] = 'Add a new field';
$lang['admin.fields.title.add_field.page_title'] = 'Add a new field in the contact form';
$lang['admin.fields.title.edit_field'] = 'Field edition';
$lang['admin.fields.title.edit_field.page_title'] = 'Field edition in the contact form';

//Field
$lang['field.possible_values.email'] = 'Mail address(es)';
$lang['field.possible_values.email.explain'] = 'It is possible to put more than one mail address separated by a semi-colon';
$lang['field.possible_values.recipient'] = 'Recipient(s)';
$lang['field.possible_values.recipient.explain'] = 'The mail will ve sent to the selected recipient(s) if the recipients field is not displayed';

//Messages
$lang['message.field_name_already_used'] = 'The entered field name is already used!';
$lang['message.success_mail'] = 'Thank you, your e-mail has been sent successfully.';
$lang['message.acknowledgment'] = 'You should receive a confirmation email in a few minutes.';
$lang['message.error_mail'] = 'Sorry, your e-mail couldn\'t be sent.';
?>
