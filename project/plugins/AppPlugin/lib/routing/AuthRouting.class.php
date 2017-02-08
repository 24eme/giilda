<?php

class AuthRouting {

    /**
     * Listens to the routing.load_configuration event.
     *
     * @param sfEvent An sfEvent instance
     * @static
     */
    static public function listenToRoutingLoadConfigurationEvent(sfEvent $event) {

        $r = $event->getSubject();

        $r->prependRoute('auth_login_no_cas', new sfRoute('/login_no_cas', array('module' => 'auth', 'action' => 'login')));
        $r->prependRoute('auth_logout', new sfRoute('/logout', array('module' => 'auth', 'action' => 'logout')));
        $r->prependRoute('auth_deconnexion_usurpation', new sfRoute('/deconnexion_usurpation', array('module' => 'auth', 'action' => 'deconnexionUsurpation')));

    }
}
