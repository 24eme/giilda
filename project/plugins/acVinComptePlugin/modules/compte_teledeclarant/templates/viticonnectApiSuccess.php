<cas:viticonnect_entities>
<cas:viticonnect_entities_number><?php echo $entities_number; ?></cas:viticonnect_entities_number>
<?php foreach($entities as $k => $a): $i = 0; ?>
<?php foreach($a as $v) : $i++ ; if ($v): ?>
<cas:viticonnect_entity_<?php echo $i; ?>_<?php echo $k; ?>><?php echo $v ?></cas:viticonnect_entity_<?php echo $i; ?>_<?php echo $k; ?>>
<?php endif; endforeach; ?>
<?php endforeach; ?>
<?php foreach($entities as $k => $a): $a = array_filter($a->getRawValue()); if (count($a)):  ?>
<cas:viticonnect_entities_all_<?php echo $k; ?>><?php echo implode('|', $a); ?></cas:viticonnect_entities_all_<?php echo $k; ?>>
<?php endif; endforeach; ?>
</cas:viticonnect_entities>