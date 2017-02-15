<?php

/**
 * statistique actions.
 *
 * @package    declarvin
 * @subpackage statistique
 * @author     Your name here
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class statistiqueActions extends sfActions {

    
	public function executeIndex(sfWebRequest $request) {
		
	}
	
	protected function getFields($key, $value)
	{
		if (!isset($value['properties'])) {
			return array($key => $key);
		}
		$result = array();
		foreach ($value['properties'] as $subkey => $subvalue) {
			$result = array_merge($result, $this->getFields($key.'.'.$subkey, $subvalue));
		}
		return $result;
	}
	
	public function executeStatsStatistiques(sfWebRequest $request) 
	{
		$this->statistiquesConfig = sfConfig::get('app_statistiques_stats');
		if (!$this->statistiquesConfig) {
			throw new sfException('No configuration set for statistiques type stats');
		}
		
		$this->form = new StatistiqueStatsFilterForm($this->statistiquesConfig);
		
		if (!$request->isMethod(sfWebRequest::POST)) {
			return sfView::SUCCESS;
		}
		$this->form->bind($request->getParameter($this->form->getName()));
		if ($this->form->isValid()) {
			$values = $this->form->getValues();
			if (!isset($this->statistiquesConfig['statistiques'][$values['statistiques']]['aggregation'])) {
				throw new sfException('No aggregation set for statistiques '.$values['statistiques']);
			}
			$result = $this->getAggsResult($this->statistiquesConfig['statistiques'][$values['statistiques']]['index'], $this->form->processFilters(), array($values['statistiques'] => $this->statistiquesConfig['statistiques'][$values['statistiques']]['aggregation']));
			$csvResult = $this->getAggsResultCsv($values['statistiques'], $result);
			if ($this->form->canPeriodeCompare()) {
				$resultLastPeriode = $this->getAggsResult($this->statistiquesConfig['statistiques'][$values['statistiques']]['index'], $this->form->processFilters($this->form->getValuesLastPeriode()), array($values['statistiques'] => $this->statistiquesConfig['statistiques'][$values['statistiques']]['aggregation']));
				$csvResultLastPeriode = $this->getAggsResultCsv($values['statistiques'], $resultLastPeriode);
				$nbKeys = $this->getNbKeys($values['statistiques']);
				$csvResult = $this->getAggsResultCompareCsv($values['statistiques'], $this->getCsvToArray($csvResultLastPeriode,$nbKeys), $this->getCsvToArray($csvResult,$nbKeys));
			}
			return $this->renderCsv($csvResult, 'statistiques_'.$values['statistiques']);
		}
	}
	
	protected function getNbKeys($type)
	{
		if ($type == 'exportations' || $type == 'sorties_appellation') {
			return 2;
		} elseif ($type == 'sorties_categorie') {
			return 3;
		}
	}
	
	protected function getAggsResult($index, $filters, $agg)
	{		
		$index = acElasticaManager::getType($index);
		$params = ($filters)? array('aggs' => $agg, 'query' => $filters) : array('aggs' => $agg);
		$elasticaQuery = new acElasticaQuery();
		$elasticaQuery->setSize(0);
		$elasticaQuery->setParams($params);
		//print_r(json_encode($elasticaQuery->toArray()));exit;
		return $index->search($elasticaQuery)->getFacets();
	}
	
	protected function getAggsResultCsv($type, $result)
	{
		//print_r($result);exit;
		$appellations = $this->getLibelles('appellation');
		$categories = EtablissementFamilles::getFamilles();
		$familles = EtablissementFamilles::getFamilles();
		$couleurs = array('blanc' => 'Blanc', 'rose' => 'Rosé', 'rouge' => 'Rouge');
		
		if ($type == 'exportations') {
			$csv = 'Appellation;Pays;Blanc;Rosé;Rouge;TOTAL'."\n";
			foreach ($result[$type]['agg_page']['buckets'] as $appellation) {
				$appellationLibelle = $appellations[strtoupper($appellation['key'])];
				$totalBlanc =  $this->formatNumber($appellation['total_blanc']['value']);
				$totalRose =  $this->formatNumber($appellation['total_rose']['value']);
				$totalRouge =  $this->formatNumber($appellation['total_rouge']['value']);
				$totalTotal =  $this->formatNumber($appellation['total_total']['value']);
				foreach ($appellation['agg_line']['buckets'] as $pays) {
					$paysLibelle = $pays['key'];
					$blanc = $this->formatNumber($pays['blanc']['agg_column']['value']);
					$rose = $this->formatNumber($pays['rose']['agg_column']['value']);
					$rouge = $this->formatNumber($pays['rouge']['agg_column']['value']);
					$total = $this->formatNumber($pays['total']['agg_column']['value']);
					if (!$blanc && !$rose && !$rouge) {
						continue;
					}
					$csv .= $appellationLibelle.';'.$paysLibelle.';'.$blanc.';'.$rose.';'.$rouge.';'.$total."\n";
				}
				$csv .= $appellationLibelle.';TOTAL;'.$totalBlanc.';'.$totalRose.';'.$totalRouge.';'.$totalTotal."\n";
			}
		} elseif ($type == 'stocks') {
			$csv = 'Appellation;Catégorie;Stock initial;Stock actuel;TOTAL MVT'."\n";
			foreach ($result[$type]['agg_page']['buckets'] as $appellation) {
				$appellationLibelle = $appellation['key'];
				$totalStockInitial =  $this->formatNumber($appellation['total_stock_initial']['value']);
				$totalStockFinal =  $this->formatNumber($appellation['total_stock_final']['value']);
				$totalTotal =  $this->formatNumber($appellation['total_total']['value']);
				foreach ($appellation['agg_line']['buckets'] as $categorie) {
					$categorieLibelle = $familles[$categorie['key']];
					$stockInitial = $this->formatNumber($categorie['stock_initial']['agg_column']['value']);
					$stockFinal = $this->formatNumber($categorie['stock_final']['agg_column']['value']);
					$totalMvt = $this->formatNumber($categorie['total']['value']);
					$csv .= $appellationLibelle.';'.$categorieLibelle.';'.$stockInitial.';'.$stockFinal.';'.$totalMvt."\n";
				}
				$csv .= $appellationLibelle.';TOTAL;'.$totalStockInitial.';'.$totalStockFinal.';'.$totalTotal."\n";
			}
		} elseif ($type == 'sorties_categorie') {
			$csv = 'Catégorie;Appellation;Couleur;France;Export;Négoce;TOTAL'."\n";
			foreach ($result[$type]['agg_page']['buckets'] as $categorie) {
			$categorieLibelle = $categories[strtoupper($categorie['key'])];
			foreach ($categorie['agg_page']['buckets'] as $appellation) {
				$appellationLibelle = $appellations[strtoupper($appellation['key'])];
				$totalFrance =  $this->formatNumber($appellation['total_france']['value']);
				$totalExport =  $this->formatNumber($appellation['total_export']['value']);
				$totalNegoce =  $this->formatNumber($appellation['total_negoce']['value']);
				$totalTotal =  $this->formatNumber($appellation['total_total']['value']);
				foreach ($appellation['agg_line']['buckets'] as $couleur) {
					$couleurLibelle = $couleurs[$couleur['key']];
					$france = $this->formatNumber($couleur['france']['agg_column']['value']);
					$export = $this->formatNumber($couleur['export']['agg_column']['value']);
					$negoce = $this->formatNumber($couleur['negoce']['agg_column']['value']);
					$total = $this->formatNumber($couleur['total']['agg_column']['value']);
					if (!$france && !$export && !$negoce) {
						continue;
					}
					$csv .= $categorieLibelle.';'.$appellationLibelle.';'.$couleurLibelle.';'.$france.';'.$export.';'.$negoce.';'.$total."\n";
				}
				$csv .= $categorieLibelle.';'.$appellationLibelle.';TOTAL;'.$totalFrance.';'.$totalExport.';'.$totalNegoce.';'.$totalTotal."\n";
			}
			}
		} elseif ($type == 'sorties_appellation') {
			$csv = 'Appellation;Couleur;France;Export;Négoce;TOTAL'."\n";
			foreach ($result[$type]['agg_page']['buckets'] as $appellation) {
					$appellationLibelle = $appellations[strtoupper($appellation['key'])];
					$totalFrance =  $this->formatNumber($appellation['total_france']['value']);
					$totalExport =  $this->formatNumber($appellation['total_export']['value']);
					$totalNegoce =  $this->formatNumber($appellation['total_negoce']['value']);
					$totalTotal =  $this->formatNumber($appellation['total_total']['value']);
					foreach ($appellation['agg_line']['buckets'] as $couleur) {
						$couleurLibelle = $couleurs[$couleur['key']];
						$france = $this->formatNumber($couleur['france']['agg_column']['value']);
						$export = $this->formatNumber($couleur['export']['agg_column']['value']);
						$negoce = $this->formatNumber($couleur['negoce']['value']);
						$total = $this->formatNumber($couleur['total']['value']);
						if (!$france && !$export && !$negoce) {
							continue;
						}
						$csv .= $appellationLibelle.';'.$couleurLibelle.';'.$france.';'.$export.';'.$negoce.';'.$total."\n";
					}
					$csv .= $appellationLibelle.';TOTAL;'.$totalFrance.';'.$totalExport.';'.$totalNegoce.';'.$totalTotal."\n";
			}
		}
		return $csv;
	}
	
	protected function getAggsResultCompareCsv($type, $lastPariode, $current)
	{
		$result = array();
		$csv = '';
		if ($type == 'exportations') {
			$result[] = array('Appellation','Pays','Blanc N-1','Blanc N','Blanc %','Rosé N-1','Rosé N','Rosé %','Rouge N-1','Rouge N','Rouge %','TOTAL N-1','TOTAL N','TOTAL %');
			$currentKeys = array_keys($current);
			$currentPartKeys = array();
			foreach ($current as $key => $values) {
				$tabKey = explode('/', $key);
				if (!in_array($tabKey[0], $currentPartKeys)) {
					$currentPartKeys[] = $tabKey[0];
				}
				if ($tabKey[1] == 'TOTAL') {
					foreach ($lastPariode as $subkey => $subvalues) {
						$subtabKey = explode('/', $subkey);
						if ($subtabKey[0] == $tabKey[0] && !in_array($subkey, $currentKeys)) {
							$result[] = array($subtabKey[0], $subtabKey[1], $subvalues[0], null, $this->getEvol($subvalues[0], 0), $subvalues[1], null, $this->getEvol($subvalues[1], 0), $subvalues[2], null, $this->getEvol($subvalues[2], 0), $subvalues[3], null, $this->getEvol($subvalues[3], 0));
						}
					}
				}
				if (isset($lastPariode[$key])) {
					$result[] = array($tabKey[0], $tabKey[1], $lastPariode[$key][0], $values[0], $this->getEvol($lastPariode[$key][0], $values[0]), $lastPariode[$key][1], $values[1], $this->getEvol($lastPariode[$key][1], $values[1]), $lastPariode[$key][2], $values[2], $this->getEvol($lastPariode[$key][2], $values[2]), $lastPariode[$key][3], $values[3], $this->getEvol($lastPariode[$key][3], $values[3]));
				} else {
					$result[] = array($tabKey[0], $tabKey[1], null, $values[0], $this->getEvol(0, $values[0]), null, $values[1], $this->getEvol(0, $values[1]), null, $values[2], $this->getEvol(0, $values[2]), null, $values[3], $this->getEvol(0, $values[3]));					
				}
			}
			foreach ($lastPariode as $key => $values) {
				$tabKey = explode('/', $key);
				if (!in_array($tabKey[0], $currentPartKeys)) {
					$result[] = array($tabKey[0], $tabKey[1], $values[0], null, $this->getEvol($values[0], 0), $values[1], null, $this->getEvol($values[1], 0), $values[2], null, $this->getEvol($values[2], 0), $values[3], null, $this->getEvol($values[3], 0));
				}
			}
		} elseif ($type == 'sorties_categorie') {
			$result[] = array('Catégorie','Appellation','Couleur','France N-1','France N','France %','Export N-1','Export N','Export %','Négoce N-1','Négoce N','Négoce %','TOTAL N-1','TOTAL N','TOTAL %');
			$currentKeys = array_keys($current);
			$currentPartKeys = array();
			foreach ($current as $key => $values) {
				$tabKey = explode('/', $key);
				if (!in_array($tabKey[0].'/'.$tabKey[1], $currentPartKeys)) {
					$currentPartKeys[] = $tabKey[0].'/'.$tabKey[1];
				}
				if ($tabKey[2] == 'TOTAL') {
					foreach ($lastPariode as $subkey => $subvalues) {
						$subtabKey = explode('/', $subkey);
						if ($subtabKey[0].'/'.$subtabKey[1] == $tabKey[0].'/'.$tabKey[1] && !in_array($subkey, $currentKeys)) {
							$result[] = array($subtabKey[0], $subtabKey[1], $subtabKey[2], $subvalues[0], null, $this->getEvol($subvalues[0], 0), $subvalues[1], null, $this->getEvol($subvalues[1], 0), $subvalues[2], null, $this->getEvol($subvalues[2], 0), $subvalues[3], null, $this->getEvol($subvalues[3], 0));
						}
					}
				}
				if (isset($lastPariode[$key])) {
					$result[] = array($tabKey[0], $tabKey[1], $tabKey[2], $lastPariode[$key][0], $values[0], $this->getEvol($lastPariode[$key][0], $values[0]), $lastPariode[$key][1], $values[1], $this->getEvol($lastPariode[$key][1], $values[1]), $lastPariode[$key][2], $values[2], $this->getEvol($lastPariode[$key][2], $values[2]), $lastPariode[$key][3], $values[3], $this->getEvol($lastPariode[$key][3], $values[3]));
				} else {
					$result[] = array($tabKey[0], $tabKey[1], $tabKey[2], null, $values[0], $this->getEvol(0, $values[0]), null, $values[1], $this->getEvol(0, $values[1]), null, $values[2], $this->getEvol(0, $values[2]), null, $values[3], $this->getEvol(0, $values[3]));
				}
			}
			foreach ($lastPariode as $key => $values) {
				$tabKey = explode('/', $key);
				if (!in_array($tabKey[0].'/'.$tabKey[1], $currentPartKeys)) {
					$result[] = array($tabKey[0], $tabKey[1], $tabKey[2], $values[0], null, $this->getEvol($values[0], 0), $values[1], null, $this->getEvol($values[1], 0), $values[2], null, $this->getEvol($values[2], 0), $values[3], null, $this->getEvol($values[3], 0));
				}
			}
		} elseif ($type == 'sorties_appellation') {
			$result[] = array('Appellation','Couleur','France N-1','France N','France %','Export N-1','Export N','Export %','Négoce N-1','Négoce N','Négoce %','TOTAL N-1','TOTAL N','TOTAL %');
			$currentKeys = array_keys($current);
			$currentPartKeys = array();
			foreach ($current as $key => $values) {
				$tabKey = explode('/', $key);
				if (!in_array($tabKey[0], $currentPartKeys)) {
					$currentPartKeys[] = $tabKey[0];
				}
				if ($tabKey[1] == 'TOTAL') {
					foreach ($lastPariode as $subkey => $subvalues) {
						$subtabKey = explode('/', $subkey);
						if ($subtabKey[0] == $tabKey[0] && !in_array($subkey, $currentKeys)) {
							$result[] = array($subtabKey[0], $subtabKey[1], $subvalues[0], null, $this->getEvol($subvalues[0], 0), $subvalues[1], null, $this->getEvol($subvalues[1], 0), $subvalues[2], null, $this->getEvol($subvalues[2], 0), $subvalues[3], null, $this->getEvol($subvalues[3], 0));
						}
					}
				}
				if (isset($lastPariode[$key])) {
					$result[] = array($tabKey[0], $tabKey[1], $lastPariode[$key][0], $values[0], $this->getEvol($lastPariode[$key][0], $values[0]), $lastPariode[$key][1], $values[1], $this->getEvol($lastPariode[$key][1], $values[1]), $lastPariode[$key][2], $values[2], $this->getEvol($lastPariode[$key][2], $values[2]), $lastPariode[$key][3], $values[3], $this->getEvol($lastPariode[$key][3], $values[3]));
				} else {
					$result[] = array($tabKey[0], $tabKey[1], null, $values[0], $this->getEvol(0, $values[0]), null, $values[1], $this->getEvol(0, $values[1]), null, $values[2], $this->getEvol(0, $values[2]), null, $values[3], $this->getEvol(0, $values[3]));
				}
			}
			foreach ($lastPariode as $key => $values) {
				$tabKey = explode('/', $key);
				if (!in_array($tabKey[0], $currentPartKeys)) {
					$result[] = array($tabKey[0], $tabKey[1], $values[0], null, $this->getEvol($values[0], 0), $values[1], null, $this->getEvol($values[1], 0), $values[2], null, $this->getEvol($values[2], 0), $values[3], null, $this->getEvol($values[3], 0));
				}
			}
		}
		
		foreach ($result as $line) {
			$csv .= implode(';', $line);
			$csv .= "\n";
		}
		return $csv;
	}
	
	protected function getEvol($last, $current)
	{
		$last = str_replace(',', '.', $last);
		$current = str_replace(',', '.', $current);
		return ($last > 0)? $this->formatNumber(round((($current - $last) / $last) * 100)) : null;
	}

	protected function getCsvToArray($csv, $nbKeys)
	{
		$lines = explode(PHP_EOL, $csv);
		$array = array();
		foreach ($lines as $line) {
			$subArray = str_getcsv($line, ';');
			$key = '';
			for ($i=0; $i<$nbKeys; $i++) {
				if (isset($subArray[$i])) {
					$key .= ($key)? '/'.$subArray[$i] : $subArray[$i];
					unset($subArray[$i]);
				} else {
					$key = null;
					break;
				}
			}
			if ($key) {
				$array[$key] = array_values($subArray);
			}
		}
		array_shift($array);
		return $array;
	}
	
	protected function getLibelles($noeud) {
        $libelles = array();
        $items = ConfigurationClient::getCurrent()->declaration->getKeys($noeud);

        foreach($items as $key => $item) {
            $libelles[$key] = $item->getLibelle();
        }

        return $libelles;
    }	
    protected function formatNumber($number) {
    	return ($number && $number != 0)? number_format($number, 2, ',', '') : null;
    }
	
    public function executeDrmStatistiques(sfWebRequest $request) {
        $this->page = $request->getParameter('p', 1);
        $this->statistiquesConfig = sfConfig::get('app_statistiques_drm');
        if (!$this->statistiquesConfig) {
            throw new sfException('No configuration set for elasticsearch type drm');
        }
        
        $index = acElasticaManager::getType($this->statistiquesConfig['elasticsearch_type']);
        
        $this->fields = array();
        $mapping = $index->getMapping()[acElasticaManager::getClient()->dbname]['mappings'][$this->statistiquesConfig['elasticsearch_type']]['properties']['doc']['properties'];

        foreach ($mapping as $propertie => $value) {
        	$key = 'doc.'.$propertie;
        	$this->fields = array_merge($this->fields, $this->getFields($key, $value));
        }

        $this->form = new StatistiqueDRMFilterForm($this->fields);
        $this->query = $this->form->getDefaultQuery();
        $this->collapseIn = false;
        $this->filters = array();
        
        if ($request->hasParameter($this->form->getName())) {
        	$this->form->bind($request->getParameter($this->form->getName()));
        	if ($this->form->isValid()) {
        		if ($q = $this->form->getQuery()) {
        			$this->query = $q;
        			$this->collapseIn = $this->form->getCollapseIn();
        			$this->filters = $this->form->getParameters();
        		}
        	}
        }
        
        $elasticaQuery = new acElasticaQuery();
        $elasticaQuery->setQuery($this->query);
        $elasticaQuery->setLimit($this->statistiquesConfig['nb_resultat']);
        $elasticaQuery->setFrom(($this->page - 1) * $this->statistiquesConfig['nb_resultat']);
        $result = $index->search($elasticaQuery);
        $this->hits = $result->getResults();
        $this->nbHits = $result->getTotalHits();
        $this->nbPage = ceil($this->nbHits / $this->statistiquesConfig['nb_resultat']);
    }

    public function executeVracStatistiques(sfWebRequest $request) {
        $this->page = $request->getParameter('p', 1);
        $this->statistiquesConfig = sfConfig::get('app_statistiques_vrac');
        if (!$this->statistiquesConfig) {
            throw new sfException('No configuration set for elasticsearch type vrac');
        }

        $index = $index = acElasticaManager::getType($this->statistiquesConfig['elasticsearch_type']);
        
        $this->fields = array();
        $mapping = $index->getMapping()[acElasticaManager::getClient()->dbname]['mappings'][$this->statistiquesConfig['elasticsearch_type']]['properties']['doc']['properties'];
        foreach ($mapping as $propertie => $value) {
        	$key = 'doc.'.$propertie;
        	$this->fields = array_merge($this->fields, $this->getFields($key, $value));
        }
        
        $this->form = new StatistiqueVracFilterForm($this->fields);
        $this->query = $this->form->getDefaultQuery();
        $this->collapseIn = false;
        $this->filters = array();
        
        if ($request->hasParameter($this->form->getName())) {
            $this->form->bind($request->getParameter($this->form->getName()));
            if ($this->form->isValid()) {
                if ($q = $this->form->getQuery()) {
                    $this->query = $q;
        			$this->collapseIn = $this->form->getCollapseIn();
        			$this->filters = $this->form->getParameters();
                }
            }
        }
        
        $elasticaQuery = new acElasticaQuery();
        $elasticaQuery->setQuery($this->query);


        $elasticaQuery->setLimit($this->statistiquesConfig['nb_resultat']);
        $elasticaQuery->setFrom(($this->page - 1) * $this->statistiquesConfig['nb_resultat']);
        $result = $index->search($elasticaQuery);
        $this->hits = $result->getResults();
        $this->nbHits = $result->getTotalHits();
        $this->nbPage = ceil($this->nbHits / $this->statistiquesConfig['nb_resultat']);
    }

    public function executeDrmCsvStatistiques(sfWebRequest $request) {
    	ini_set('memory_limit', '2048M');
    	set_time_limit(0);
        $this->statistiquesConfig = sfConfig::get('app_statistiques_drm');
        if (!$this->statistiquesConfig) {
            throw new sfException('No configuration set for elasticsearch type drm');
        }

        $index = $index = acElasticaManager::getType($this->statistiquesConfig['elasticsearch_type']);
        
        $this->fields = array();
        $mapping = $index->getMapping()[acElasticaManager::getClient()->dbname]['mappings'][$this->statistiquesConfig['elasticsearch_type']]['properties']['doc']['properties'];

        foreach ($mapping as $propertie => $value) {
        	$key = 'doc.'.$propertie;
        	$this->fields = array_merge($this->fields, $this->getFields($key, $value));
        }
        
        $this->form = new StatistiqueDRMFilterForm($this->fields);
        $this->query = $this->form->getDefaultQuery();
        
        if ($request->hasParameter($this->form->getName())) {
            $this->form->bind($request->getParameter($this->form->getName()));
            if ($this->form->isValid()) {
                if ($q = $this->form->getQuery()) {
                    $this->query = $q;
                }
            }
        }
        
        $elasticaQuery = new acElasticaQuery();
        $elasticaQuery->setQuery($this->query);
        $elasticaQuery->setLimit(5000);
        $result = $index->search($elasticaQuery);
        $hits = $result->getResults();
        /**
         * 
         */
        
        $csv_file = '';
        $csv_file .= "#Periode;Date saisie;Declarant id;Declarant nom;Total debut mois;Total entrees;Total recolte;Total sorties;Total facturable;Total;";
        $csv_file .= "\n";
        foreach ($hits as $hit):
	        $item = $hit->getData();
	        $csv_file .= $item['doc']['periode'];
	        $csv_file .= ";";
	        $csv_file .= $item['doc']['valide']['date_saisie'];
	        $csv_file .= ";";
	        $csv_file .= $item['doc']['identifiant'];
	        $csv_file .= ";";
	        $csv_file .= $item['doc']['declarant']['nom'];
	        $csv_file .= ";";
	        $csv_file .= $item['doc']['declaration']['total_debut_mois'];
	        $csv_file .= ";";
	        $csv_file .= $item['doc']['declaration']['total_entrees'];
	        $csv_file .= ";";
	        $csv_file .= $item['doc']['declaration']['total_recolte'];
	        $csv_file .= ";";
	        $csv_file .= $item['doc']['declaration']['total_sorties'];
	        $csv_file .= ";";
	        $csv_file .= $item['doc']['declaration']['total_facturable'];
	        $csv_file .= ";";
	        $csv_file .= $item['doc']['declaration']['total'];
	        $csv_file .= ";";
	        $csv_file .= "\n";
        endforeach;
        return $this->renderCsv($csv_file, 'drm');
    }

    public function executeVracCsvStatistiques(sfWebRequest $request) {
    	ini_set('memory_limit', '2048M');
    	set_time_limit(0);
        $this->statistiquesConfig = sfConfig::get('app_statistiques_vrac');
        if (!$this->statistiquesConfig) {
            throw new sfException('No configuration set for elasticsearch type vrac');
        }

        $index = $index = acElasticaManager::getType($this->statistiquesConfig['elasticsearch_type']);
        
        $this->fields = array();
        $mapping = $index->getMapping()[acElasticaManager::getClient()->dbname]['mappings'][$this->statistiquesConfig['elasticsearch_type']]['properties']['doc']['properties'];
        foreach ($mapping as $propertie => $value) {
        	$key = 'doc.'.$propertie;
        	$this->fields = array_merge($this->fields, $this->getFields($key, $value));
        }
        
        $this->form = new StatistiqueVracFilterForm($this->fields);
        $this->query = $this->form->getDefaultQuery();
        
        if ($request->hasParameter($this->form->getName())) {
            $this->form->bind($request->getParameter($this->form->getName()));
            if ($this->form->isValid()) {
                if ($q = $this->form->getQuery()) {
                    $this->query = $q;
                }
            }
        }
        $elasticaQuery = new acElasticaQuery();
        $elasticaQuery->setQuery($this->query);
        $elasticaQuery->setLimit(5000);
        $result = $index->search($elasticaQuery);
        $hits = $result->getResults();
        $csv_file = '';
        $csv_file .= "#Statut;Type transaction;Num. archive;Num. contrat;Teledeclare;Date signature;Date saisie;Vendeur id;Vendeur nom;Acheteur id;Acheteur nom;Representant id;Representant nom;Courtier id;Courtier nom;Produit;Millesime;Volume propose;Volume enleve;Prix initial unitaire;";
        $csv_file .= "\n";
        foreach ($hits as $hit):
        	$item = $hit->getData();
        	$csv_file .= $item['doc']['valide']['statut'];
        	$csv_file .= ";";
        	$csv_file .= $item['doc']['type_transaction'];
        	$csv_file .= ";";
        	$csv_file .= $item['doc']['numero_archive'];
        	$csv_file .= ";";
        	$csv_file .= $item['doc']['numero_contrat'];
        	$csv_file .= ";";
        	$csv_file .= $item['doc']['teledeclare'];
        	$csv_file .= ";";
        	$csv_file .= $item['doc']['date_signature'];
        	$csv_file .= ";";
        	$csv_file .= $item['doc']['valide']['date_saisie'];
        	$csv_file .= ";";
        	$csv_file .= $item['doc']['vendeur_identifiant'];
        	$csv_file .= ";";
        	$csv_file .= $item['doc']['vendeur']['nom'];
        	$csv_file .= ";";
        	$csv_file .= $item['doc']['acheteur_identifiant'];
        	$csv_file .= ";";
        	$csv_file .= $item['doc']['acheteur']['nom'];
        	$csv_file .= ";";
        	if ($item['doc']['representant_identifiant'] != $item['doc']['vendeur_identifiant']) {
	        	$csv_file .= $item['doc']['representant_identifiant'];
        		$csv_file .= ";";
	        	$csv_file .= $item['doc']['representant']['nom'];
        	} else {
	        	$csv_file .= null;
        		$csv_file .= ";";
	        	$csv_file .= null;
        		
        	}
        	$csv_file .= ";";
        	$csv_file .= $item['doc']['mandataire_identifiant'];
        	$csv_file .= ";";
        	$csv_file .= $item['doc']['mandataire']['nom'];
        	$csv_file .= ";";
        	$produit = ($item['doc']['type_transaction'] == VracClient::TYPE_TRANSACTION_VIN_VRAC || $item['doc']['type_transaction'] == VracClient::TYPE_TRANSACTION_VIN_BOUTEILLE)? $item['doc']['produit_libelle'] : $item['doc']['cepage_libelle'];
        	$millesime = $item['doc']['millesime'] ? $item['doc']['millesime'] : 'nm';
        	$csv_file .= $millesime;
        	$csv_file .= ";";
        	$csv_file .= $item['doc']['volume_propose'];
        	$csv_file .= ";";
        	$csv_file .= $item['doc']['volume_enleve'];
        	$csv_file .= ";";
        	$csv_file .= $item['doc']['prix_initial_unitaire'];
        	$csv_file .= ";";
        	$csv_file .= "\n";
        endforeach;
        return $this->renderCsv($csv_file, 'vrac');
    }


    protected function renderCsv($csv_file, $type)
    {
    	$this->setLayout(false);
    	$dateTime = new DateTime();
    	$date = $dateTime->format('c');
    	$this->response->setContentType('text/csv');
    	$this->response->setHttpHeader('md5', md5($csv_file));
    	$this->response->setHttpHeader('Content-Disposition', "attachment; filename=stats_".$type."_".$date.".csv");
    	$this->response->setHttpHeader('LastDocDate', $date);
    	$this->response->setHttpHeader('Last-Modified', date('r', strtotime($date)));
    	return $this->renderText($csv_file);
    }

}
