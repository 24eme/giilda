<h1>La liste des tags</h1>
<div class="row"><?php

foreach ($facets as $type => $ftype) {
  if (!count($ftype['buckets'])) {
    continue;
  }
  echo "<div class='col-sm-3 col-xs-12'><h2>$type</h2>";
  foreach ($ftype['buckets'] as $f) {
    $targs = array();
    $targs['tags'] = $type.':'.$f['key'];
    echo '<a class="list-group-item list-group-item-xs" href="'.url_for('compte_search', $targs).'">'.str_replace('_', ' ', $f['key']).'<span class="badge" style="position: absolute; right: 10px;">'.$f['doc_count'].'</span></a>';
    //echo "<a href=''>".$f['key']."</a>";
  }
  echo "</div>";
}
?></div>
