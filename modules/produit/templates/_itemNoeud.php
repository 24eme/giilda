<a href="<?php echo url_for('produit_modification', array('noeud' => $noeud->getTypeNoeud(), 'hash' => $noeud->getHashForKey())) ?>">
    <?php if($cvo && $cvo->getNoeud()->getTypeNoeud() == $noeud->getTypeNoeud()): ?>
    <strong>
    <?php endif; ?>
    <?php echo ($noeud->getLibelle()) ? $noeud->getLibelle() : 'Défaut' ?>
    <?php if($cvo && $cvo->getNoeud()->getTypeNoeud() == $noeud->getTypeNoeud()): ?>
    </strong>
    <?php endif; ?>
</a>