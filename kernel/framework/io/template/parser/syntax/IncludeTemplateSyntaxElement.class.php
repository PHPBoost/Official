<?php
/*##################################################
 *                    IncludeTemplateSyntaxElement.class.php
 *                            -------------------
 *   begin                : September 05 2010
 *   copyright            : (C) 2010 Loic Rouchon
 *   email                : horn@phpboost.com
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

class IncludeTemplateSyntaxElement extends AbstractTemplateSyntaxElement
{
	private static $subtpl = '$_subtpl';
	
	private $input;
	private $output;
	private $ended = false;

	public static function is_element(StringInputStream $input)
	{
		return $input->assert_next('#\s+INCLUDE\s+');
	}

	public function parse(StringInputStream $input, StringOutputStream $output)
	{
		$this->input = $input;
		$this->output = $output;
		$this->do_parse();
	}

	private function do_parse()
	{
		$matches = array();
		if ($this->input->consume_next('#\s+INCLUDE\s+(?:(?P<block>(?:\w+\.)*\w+)\.)?(?P<name>\w+)\s+#', '', $matches))
		{
            $this->output->write('\';' . "\n");
            $this->write_subtemplate_initialization($matches);
            $this->write_subtemplate_call();
        }
        else
        {
        	throw new TemplateParserException('invalid include template name', $this->input);
        }
	}
    
    private function write_subtemplate_initialization(array $matches)
    {
        $name = $matches['name'];
        $is_in_block = !empty($matches['block']);
        if ($is_in_block)
        {
            $this->write_block_subtemplate_initialization($name, $matches['block']);           
        }
        else
        {
            $this->output->write(self::$subtpl . '=' . TemplateSyntaxElement::DATA . '->get_subtemplate(\'' . $name . '\');');
        }
    }
    
    private function write_block_subtemplate_initialization($name, $block)
    {
    	$blocks = explode('.', $block);
        $block_var = '$_tmp_' . array_pop($blocks) . '[\'subtemplates\']';
        $this->output->write(self::$subtpl . '=' . TemplateSyntaxElement::DATA . '->get_subtemplate_from_list(\'' . $name . '\', ' . $block_var . ');');
    }
    
    private function write_subtemplate_call()
    {
        $this->output->write('if(' . self::$subtpl . ' !== null){' . TemplateSyntaxElement::RESULT . '.=' . self::$subtpl . '->to_string();}' . "\n");
        $this->output->write(TemplateSyntaxElement::RESULT . '.=\'');
    }
}
?>