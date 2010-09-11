<?php
/*##################################################
 *                    TextTemplateSyntaxElement.class.php
 *                            -------------------
 *   begin                : July 08 2010
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

class TextTemplateSyntaxElement extends AbstractTemplateSyntaxElement
{
	/**
	 * @var StringInputStream
	 */
	private $input;
	/**
	 * @var StringOutputStream
	 */
	private $output;
	private $ended = false;
	private $escaped = false;

	public function parse(StringInputStream $input, StringOutputStream $output)
	{
		$this->input = $input;
		$this->output = $output;
		$this->do_parse();
	}

	private function do_parse()
	{
		while ($this->input->has_next() && !$this->ended)
		{
			$current = $this->input->next();
			if (!$this->escaped)
			{
				$this->process_char($current);
			}
			else
			{
				$this->process_escaped_char($current);
			}
		}
	}

	private function process_char($char)
	{
		if ($char == '\\')
		{
			$this->escaped = true;
		}
		elseif (!$this->escaped && 
		((($char == '$' || $char == '#') && $this->input->assert_next('\{')) ||
		($char == '{' && $this->input->assert_next('(?:\w+\.)*\w+\}')) ||
		($char == '#'&& $this->input->assert_next('[\s]')) ||
        ($char == '<' && $this->input->assert_next('\?php'))))
		{
			$this->input->move(-1);
			$this->ended = true;
		}
		else
		{
			if ($char == '\'')
			{
				$this->write('\\');
			}
			$this->write($char);
		}
	}

	private function process_escaped_char($char)
	{
		if (!in_array($char, array('\\', '{', '}', '#')))
		{
            $this->write('\\');
		}
		$this->escaped = false;
		$this->write($char);
	}

	private function write($char)
	{
		$this->output->write($char);
	}
}
?>