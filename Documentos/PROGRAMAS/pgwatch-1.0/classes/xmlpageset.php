<?php

class xmlpageset {

	public $pxml;
	public $list;
	public $page_xml;

	public function __construct(&$list){
		$this->list = &$list;
		$pageset = $this->list['set'];
		$filename = "pagesets/{$pageset}.xml";
		if (!file_exists($filename)){
			trigger_error("No xml file exists!", E_USER_ERROR);
		}
		
		$xml_string = file_get_contents($filename);

		libxml_use_internal_errors(true);
		$sxe = simplexml_load_string($xml_string);
		if (!$sxe) {
			echo "Failed loading XML\n";
			foreach(libxml_get_errors() as $error) {
				echo "<br/>", $error->message;
			}
		}
		libxml_use_internal_errors(false);
                    
		$xml = new SimpleXMLElement($xml_string /*, LIBXML_XINCLUDE */);
		$pagesets = $xml->xpath("/pageset");
		if (count($pagesets) == 0) trigger_error("No 'pageset' root element in xml file.", E_USER_ERROR);
		$this->pxml = new pgwatch_xml($xml, $this->list);

		$page = (isset($this->list['page'])) ?$this->list['page'] :"";
		$filter = $page != "" ?"@id='{$page}'" :"@default='true'";
		$x = $this->pxml->sxml->xpath("page[{$filter}]");
		if (count($x) == 0) {
			//trigger_error("No such page in pageset: '{$page}'.", E_USER_ERROR);
			$x = $this->pxml->sxml->xpath("page[@default='true']");
		}
		$this->page_xml = new xmlpage($x[0], $this->pxml->input);
		$this->page_xml->pageset_pxml = $this;
	}

	public function display($for=null){
		$this->pxml->process_children();
		$this->page_xml->pxml->vars = array_merge($this->page_xml->pxml->vars, $this->pxml->vars);
		$this->page_xml->display($for);
	}

}

?>
