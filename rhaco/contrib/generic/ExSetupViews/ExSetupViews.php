<?php
Rhaco::import("network.http.Header");
Rhaco::import("setup.util.SetupView");
Rhaco::import("setup.model.ProjectModel");
Rhaco::import("lang.ArrayUtil");

/**
 * project.xml で指定した設定項目を変更する View
 *
 * @author Naoki
 * @license New BSD License
 * @copyright Copyright 2009- rhaco project. All rights reserved.
 */
class ExSetupViews extends SetupView{
	/**
	 * setup
	 *
	 * @param string[] $keyList
	 * @param string $href
	 * @return HtmlParser
	 */
	function setup($keyList=null,$href=null){
		$projectModel = new ProjectModel();
		$projectModel->start(Rhaco::setuppath("project.xml"));
		$projectModel->setRhacopath(Rhaco::rhacopath());

		if ($this->isPost()) {
			$projectModel->generate($this, false);
			Header::redirect(is_null($href) ? Rhaco::uri() : $href);
		}

		if(!empty($keyList)){
			$formList = array();
			foreach (ArrayUtil::arrays($keyList) as $key) {
				if (array_key_exists($key, $projectModel->formList)) {
					$formList[$key] = $projectModel->formList[$key];
				}
			}
			$projectModel->formList = $formList;
		}

		$this->setVariable(ObjectUtil::objectConvHash($projectModel));

		return $this->parser(FileUtil::exist(Rhaco::templatepath("setup/setup.html")) ? 
								Rhaco::templatepath("setup/setup.html") : 
								Rhaco::lib("arbo/generic/ExSetupViews/resources/templates/setup/setup.html")
							);
	}
}
?>