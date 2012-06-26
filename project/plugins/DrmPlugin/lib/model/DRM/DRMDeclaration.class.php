<?php
/**
 * Model for DRMDeclaration
 *
 */

class DRMDeclaration extends BaseDRMDeclaration {

	public function getChildrenNode() {

		return $this->certifications;
	}

}