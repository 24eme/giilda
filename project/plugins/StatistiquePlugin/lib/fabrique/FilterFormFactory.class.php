<?php
class FilterFormFactory 
{
	public static function create($type, $interpro) 
	{
		$form = null;
		switch ($type){
			case 'drm':
				$form = new StatistiqueDRMFilterForm($interpro);
				break;
			case 'vrac':
				$form = new StatistiqueVracFilterForm($interpro);
				break;
			case 'daids':
				$form = new StatistiqueDAIDSFilterForm($interpro);
				break;
			default:
				throw new sfException ('Fabrique : Type "'.$type.'" non gérée');
		}
		return $form;
	}
}
