<?php
	class bbcodes
	{
		protected $db;
		protected $parser;
		
		public function init()
		{
			global $M;
			$this->M = $M;
			
			$this->db = $this->M['mysql']->receiveDataBase();
			
			// Include the parser.
			require_once($_SERVER['DOCUMENT_ROOT'] . '/JBBCode/Parser.php');
		
			// Set Up The Parser.
			$this->parser = new JBBCode\Parser();
			$this->parser->addCodeDefinitionSet(new JBBCode\DefaultCodeDefinitionSet());
			
			// Custom BBCodes.
			$this->customBBCodes();
		}
		
		public function parseText($text='')
		{
			$this->parser->parse($text);
			
			return $this->parser->getAsHtml();
		}
		
		private function customBBCodes()
		{
			// ...
			
			// Custom Definitions
			$definitions = scandir($_SERVER['DOCUMENT_ROOT'] . '/modules/bbcodes/');
			
			foreach ($definitions as $definition)
			{
				if (!strstr($definition, '.php'))
				{
					continue;
				}
				
				require_once($_SERVER['DOCUMENT_ROOT'] . '/modules/bbcodes/' . $definition);
				$className = basename($definition, '.php');

				$temp = new $className;
				$this->parser->addCodeDefinition($temp);
				
			}
		}
	}
?>