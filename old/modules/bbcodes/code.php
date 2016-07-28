<?php

	class code extends JBBCode\CodeDefinition 
	{
		protected $db;
		
		public function __construct()
		{
			global $M;
			$this->M = $M;
			
			$this->db = $this->M['mysql']->receiveDataBase();
			
			parent::__construct();
			$this->setTagName("code");
		}
	 
		public function asHtml(JBBCode\ElementNode $el)
		{
			$content = "";
			
			foreach($el->getChildren() as $child)
			{
				$content .= $child->getAsBBCode();
			}
			
			return '<div class="bbcode-code">' . $content . '</div>';
		}
	 
	 
	}
?>