<?php
/*##################################################
 *                             categories_management.class.php
 *                            -------------------
 *   begin                : February 06, 2008
 *   copyright          : (C) 2008 Beno�t Sautel
 *   email                : ben.popeye@phpboost.com
 *
 *   
###################################################
 *
 *   This program is free software; you can redistribute it and/or modify
 *   it under the terms of the GNU General Public License as published by
 *   the Free Software Foundation; either version 2 of the License, or
 *   (at your option) any later version.
 * 
 *  This program is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  You should have received a copy of the GNU General Public License
 *  along with this program; if not, write to the Free Software
 *  Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
 *
###################################################*/

/*
This class allows you to manage easily categories for your modules.
It's as generic as possible, if you want to complete some actions to specialize them for you module, 
you can create a new class inheritating of it in which you call its methods using the syntax 
parent::method().
/!\ Warning : /!\
- Your DB table must respect some rules :
	* You must have an integer attribute whose name is id and which represents the identifier of each category. It must be a primary key.
	* You also must have an integer attribute named id_parent which represents the identifier of the parent category (it will be 0 if its parent category is the root of the tree).
	* To maintain order, you must have a field containing the rank of the category which be an integer named c_order.
- In this class the user are supposed to be an administrator, no checking of his auth is done.
- To be correctly displayed, you must supply to functions a variable extracted from a file cache. Use the Cache class to build your file cache. Your variable must be an array in which keys are categories identifiers, an values are still arrays which are as this :
	* key id_parent containing the id_parent field of the database
	* key name containing the name of the category
You can also have other fields such as auth level, description, visible, that class won't modify them.
- To display the list of categories and actions you can do on them, you may want to customize it. For that you must build an array that you will give to Set_displaying_configuration() containing your choices :
	* Key 'xmlhttprequest_file' which corresponds to the name of the file which will treat the AJAX requests. We usually call it xmlhttprequest.php.
	* Key 'url' which represents the url of the category (it won't display any link up to categories if you don't give this field). Its structure is the following :
		# key 'unrewrited' => string containing unrewrited urls (let %d where you want to display the category identifier)
		# Key administration_file_name which represents the file which allows you to update category
		# rewrited url (optionnal) 'rewrited' => string containing rewrited urls (let %d where you want to display the category identifier and %s the category name if you need it) 
*/

$config = array(
	'xmlhttprequest_file' => 'xmlhttprequest.php',
	'administration_file_name' => 'admin_faq_cats.php',
	'url' => array(
		'unrewrited' => '../news/news.php?id=%d',
		'rewrited' => '../news-%d+%s.php'),
);
	


define('ERROR_UNKNOWN_MOTION', 1);
define('ERROR_CAT_IS_AT_TOP', 2);
define('ERROR_CAT_IS_AT_BOTTOM', 4);
define('CATEGORY_DOES_NOT_EXIST', 8);
define('NEW_PARENT_CATEGORY_DOES_NOT_EXIST', 16);
define('DISPLAYING_CONFIGURATION_NOT_SET', 32);

class CategoriesManagement
{
    ## Public methods ##
    function CategoriesManagement($table, $cache_file_name)
    {
        $this->table = $table;
        $this->cache_file_name = $cache_file_name;
    }
    
    //Method which moves a category
    function Move_category($id, $way)
    {
        global $sql, $cache;
        $this->clean_error();
		
        if( in_array(array('up', 'down'), $way )
        {
			$cat_info = $sql->query_array($this->table, "c_order", "id_parent", "WHERE id = '" . $id . "'", __LINE__, __FILE__);
			
			//Checking that category exists
			if( empty($cat_info['c_order']) )
            {
				$this->add_error(CATEGORY_DOES_NOT_EXIST);
				return false;
            }
                
            if( $way == 'down' )
            {
                //Query which allows us to check if we don't want to move down the downest category
                $max_order = $sql->query("SELECT MAX(c_order) FROM " . PREFIX . $this->table . " WHERE id_parent = '" . $cat_info['id_parent'] . "'", __LINE__, __FILE__);
                if( $cat_info['c_order'] < $max_order )
                {
                    //Switching category with that which is upper
                    //Updating other category
                    $sql->query_inject("UPDATE " . PREFIX . $this->table . " SET c_order = c_order - 1 WHERE id_parent = '" . $cat_info['id_parent'] . "' AND c_order = '" . ($cat_info['c_order'] + 1) . "'", __LINE__, __FILE__);
                    //Updating current category
                    $sql->query_inject("UPDATE " . PREFIX . $this->table . " SET c_order = c_order + 1 WHERE id = '" . $id . "'", __LINE__, __FILE__);
                    //Regeneration of the cache file of the module
                    $cache->generate_module_file($this->cache_file_name);
                    
                    return true;
                }
                else
                {
                    $this->add_error(ERROR_CAT_IS_AT_BOTTOM);
                    return false;
                }
            }
            else
            {
                if( $cat_info['c_order'] > 1 )
                {
                    //Switching category with that which is upper
                    //Updating other category
                    $sql->query_inject("UPDATE " . PREFIX . $this->table . " SET c_order = c_order + 1 WHERE id_parent = '" . $cat_info['id_parent'] . "' AND c_order = '" . ($cat_info['c_order'] - 1) . "'", __LINE__, __FILE__);
                    //Updating current category
                    $sql->query_inject("UPDATE " . PREFIX . $this->table . " SET c_order = c_order - 1 WHERE id = '" . $id . "'", __LINE__, __FILE__);
                    //Regeneration of the cache file of the module
                    $cache->generate_module_file($this->cache_file_name);
                    
                    return true;
                }
                else
                {
                    $this->add_error(ERROR_CAT_IS_AT_TOP);
                    return false;
                }
            }
        }
        else
        {
            $this->add_error(ERROR_UNKNOWN_MOTION);
            return false;
        }
    }
    
    //Method which allows to move a category from its position to another category
    //You can choose its position in the new category, otherwise it will be placed at the end
    function Move_category_into_another_category($id, $new_id_cat, $position = 0)
    {
        global $sql, $cache;
        $this->clean_error();
		
        //Checking that both current category and new category exist and importing necessary information
        $max_new_cat_order = $sql->query("SELECT MAX(c_order) FROM " . PREFIX . $this->table . " WHERE id_parent = '" . $new_id_cat . "'", __LINE__, __FILE__);		
        $cat_info = $sql->query_array($this->table, "c_order", "id_parent", "WHERE id = '" . $id . "'", __LINE__, __FILE__);
		
        if( $max_new_cat_order > 0 && $cat_info['c_order'] > 0 )
        {
			//Default : inserting at the end of the list
			if( $position < 0 || $position > $max_new_cat_order )
			{
				//Moving the category $id
				$sql->query_inject("UPDATE " . PREFIX . $this->table . " SET id_parent = '" . $new_id_cat . "', c_order = '" . $max_new_cat_order . "' WHERE id = '" . $id . "'", __LINE__, __FILE__);
				//Updating ex category
				$sql->query_inject("UPDATE " . PREFIX . $this->table . " SET c_order = c_order - 1 WHERE id_parent = '" . $cat_info['id_parent'] . "' AND c_order > '" . $cat_info['c_order'] . "'", __LINE__, __FILE__);
			}
			//Inserting at a precise position
			else
			{
				//Preparing the new parent category to receive a category at this position
				$sql->query_inject("UPDATE " . PREFIX . $this->table . " SET c_order = c_order + 1 WHERE id_parent = '" . $new_id_cat . "' AND c_order >= '" . $cat_info['c_order'] . "'", __LINE__, __FILE__);
				//Moving the category $id
				$sql->query_inject("UPDATE " . PREFIX . $this->table . " SET id_parent = '" . $new_id_cat . "', c_order = '" . $postion . "' WHERE id = '" . $id . "'", __LINE__, __FILE__);
				//Updating ex category
				$sql->query_inject("UPDATE " . PREFIX . $this->table . " SET c_order = c_order - 1 WHERE id_parent = '" . $cat_info['id_parent'] . "' AND c_order > '" . $cat_info['c_order'] . "'", __LINE__, __FILE__);
			}
			
			//Regeneration of the cache file of the module
			$cache->generate_module_file($this->cache_file_name);
			
			return true;
        }
        else
        {
			if( empty($max_new_cat_order) )
				$this->add_error(NEW_PARENT_CATEGORY_DOES_NOT_EXIST);
			if( ($cat_info['c_order']) )
				$this->add_error(CATEGORY_DOES_NOT_EXIST);
				
            return false;
        }
    }

    //Deleting a category
    function Delete_category($id)
    {
        global $sql, $cache;
        $this->clean_error();
        
        $cat_order = $sql->query("SELECT c_order FROM " . PREFIX . $this->table . " WHERE id = '" . $id . "'", __LINE__, __FILE__);
        
        //Checking that category exists
        if( empty($cat_order) )
        {
            $this->add_error(CATEGORY_DOES_NOT_EXIST);
            return false;
        }
        
        //Deleting the category
        $sql->query_inject("DELETE FROM " . PREFIX . $this->table . " WHERE id = '" . $id . "'", __LINE__, __FILE__);
        
        
        //Decrementing all following categories
        $sql->query_inject("UPDATE " . PREFIX . $this->table . " SET c_order = c_order - 1 WHERE id_parent = '". $cat_infos['id_parent'] . "'", __LINE__, __FILE__);
        
        //Regeneration of the cache file
        $cache->generate_module_file($this->cache_file_name);
        
        return true;
    }
    
	//Method which sets the displaying configuration
	function Set_displaying_configuration($config)
	{
		//Respect du standard � v�rifier
		$this->config_display = $config;
	}
	
	//Method which builds the list of categories and links to makes operations to administrate them (delete, move, add...), it's return string is ready to be displayed
	//This method doesn't allow you tu use templates, it's not so important because you are in the administration panel
	function Build_administration_list()
	{
		$this->clean_error();
		//If displaying configuration hasn't bee already set
		if( empty($displaying_configuration) )
		{
			$this->add_error(CATEGORY_DOES_NOT_EXIST);
            return false;
		}
		
		//Let's display
		$string = '';
		//AJAX functions
		echo '
		<script type="text/javascript">
		<!--
		
		//D�placement d\'une cat�gorie en Ajax
		function ajax_move_cat(id, direction)
		{
			var xhr_object = null;
			direction = direction == \'up\' ? \'up\' : \'down\';
			var filename = \'' . $this->config_display['xmlhttprequest_file'] . '?id_\' + direction + \'=\' + id;
			var data = null;
			
			if(window.XMLHttpRequest) // Firefox
			   xhr_object = new XMLHttpRequest();
			else if(window.ActiveXObject) // Internet Explorer
			   xhr_object = new ActiveXObject("Microsoft.XMLHTTP");
			else // XMLHttpRequest non support� par le navigateur
				return;
			
			document.getElementById(\'l\' + divid).innerHTML = \'<img src="../templates/' . $CONFIG['theme'] . '/images/loading_mini.gif" alt="" style="vertical-align:middle;" />\';
			
			xhr_object.open("POST", filename, true);
			xhr_object.onreadystatechange = function() 
			{
				if( xhr_object.readyState == 4 && xhr_object.responseText != \'\' )
					move_cat(id, direction);
			}
			document.getElementById(\'l\' + divid).innerHTML = \'\';
			xhr_object.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
			xhr_object.send(null);
		}
		
		//D�placement graphique de la cat�gorie
		function move_cat(id, direction)
		{
			//On enregistre le contenu de toutes les cat�gories fr�res de la cat�gorie demand�e
			var categories = new array();
			for ( var i = 0; i < num_cats; i++ )
			{
				if( categories_list[i] == id )
				{
					
				}
			}
		}
		
		function Confirm() {
			return confirm("{L_CONFIRM_DEL}");
		}
		-->
		</script>
		';
		//Categories list
		$this->create_cat_administration($string, 0, 0, $cache_var);
	}
	
    //Method for users who want to know what was the error
    function Check_error($error)
    {
        return (bool)($this->errors ^ $error);
    }
    

    ## Private methods ##
	//Recursive method allowing to display the administration panel of a category and its daughters
	function create_cat_administration(&$string, $id_cat, $level, &$cache_var)
	{
		global $CONFIG, $LANG;
		
		$id_categories = array_keys($cache_var);
		$num_cats = count($id_categories);
		// Browsing categories
		for( $i = 0; $i < $num_cats; $i++ )
		{
			$id = $id_categories[$i];
			$values = $cache_var[$id];
			//If this category is in the category $id_cat
			if( $id != 0 && $values['id_parent'] == $id_cat )
			{
				$string .= '
				<span id="c' . $id . '">
					<div style="margin-left:' . ($level * 50) . 'px;">
						<div class="row3">
							<span style="float:left;">
								&nbsp;&nbsp;<img src="../templates/' . $CONFIG['theme'] . '/images/upload/folder.png" alt="" style="vertical-align:middle" />
							&nbsp;' .
							(!empty($this->display_config['url']) ? '<a href="' . transid(sprintf($this->display_config['url']['unrewrited'], $id), !empty($this->display_config['url']['rewrited']) ? sprintf($this->display_config['url']['rewrited'], $id, url_encore_rewrite($values['name'])) : ''))
							. '</span>
							</span>
							<span style="float:right;">
								<span id="l' . $id . '"></span>';
								
								//If it's not the first of the category we can make it going downer
								if( $values['order'] > 1 )
								{
									$string .= '
								<script type="text/javascript">
								<!--
								document.write(\'<a href="javascript:XMLHttpRequest_get_parent(\'' . $id . '\', \'up\');"><img src="../templates/' . $CONFIG['theme'] . '/images/top.png" alt="" class="valign_middle" /></a>\');
								-->
								</script>
								<noscript><a href="' . $this->xmlhttprequest_file . '.php?id=' . $id . '&amp;move=up"><img src="../templates/' . $CONFIG['theme'] . '/images/top.png" alt="" class="valign_middle" /></a></noscript>';
								}
								
								//If it's not the last of the category we can make it going upper
								if( $i != $num_cats  - 1 && $cache_var[$id_categories[$i + 1]]['id_parent'] == $id_cat )
								{
									$string .= '
								<script type="text/javascript">
								<!--
								document.write(\'<a href="javascript:XMLHttpRequest_get_parent(\'' . $id . '\', \'down\');"><img src="../templates/' . $CONFIG['theme'] . '/images/bottom.png" alt="" class="valign_middle" /></a>\');
								-->
								</script>										
								<noscript><a href="' . $this->display_config['administration_file_name']) . '?id=' . $id . '&amp;move=down"><img src="../templates/' . $CONFIG['theme'] . '/images/bottom.png" alt="" class="valign_middle" /></a></noscript>';
								}
								
								echo '
								<a href="' . $this->display_config['administration_file_name']) . '?id=' . $id . '"><img src="../templates/' . $CONFIG['theme'] . '/images/' . $CONFIG['lang'] . '/edit.png" alt="" class="valign_middle" /></a> <a href="' . $this->display_config['administration_file_name']) . '?del=' . $id . '" onclick="javascript:return Confirm();"><img src="../templates/' . $CONFIG['theme'] . '/images/' . $CONFIG['lang'] . '/delete.png" alt="" class="valign_middle" /></a>&nbsp;&nbsp;
							</span>&nbsp;
						</div>	
					</div>
				</span>';
				
				//We call the function for its daughter categories
				$this->create_cat_administration($string, $id, $level + 1, $cache_var);
			}
		}
			

	}
    //Method which adds an error bit to current status
    function add_error($error)
    {
        $this->errors |= $error;
    }
    
    //Method which cleans error status
    function clean_error($error = 0)
    {
        if( $error != 0 )
		{
			$this->errors &= (~$error);
		}
		else
		{
			$this->errors = 0;
		}
    }
    
    ## Private attributes ##
    //The table of the DB in which are saved categories
    var $table = '';
    //Name of the cache file cirresponding to the module
    var $cache_file_name = '';
    //Last error
    var $errors = 0;
	//Displaying configuration
	var $display_config = array();    
}

?>
