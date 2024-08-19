/*
  Leaflet.AwesomeMarkers, a plugin that adds colorful iconic markers for Leaflet, based on the Font Awesome icons
  (c) 2012-2013, Lennard Voogdt

  http://leafletjs.com
  https://github.com/lvoogdt
*/

/*global L*/

(function (window, document, undefined) {
    "use strict";
    /*
     * Leaflet.AwesomeMarkers assumes that you have already included the Leaflet library.
     */

    L.BootstrapMarkers = {};

    L.BootstrapMarkers.version = '2.0.1';

    L.BootstrapMarkers.Icon = L.DivIcon.extend({
        options: {
            className: 'marker',
            color: 'white',
            size: ''
        },

        createIcon: function () {
            var div = document.createElement('div'),
                options = this.options;

            div.innerHTML = "<span class='marker-inner-bg'></span><span class='marker-inner glyphicon glyphicon-map-marker' style='color: "+options.color+"'></span></span>";

            this._setIconStyles(div);

            if(options.size) {
                div.className = div.className + ' marker-' + options.size; 
            }

            return div;
        },
    });
        
    L.BootstrapMarkers.icon = function (options) {
        return new L.BootstrapMarkers.Icon(options);
    };

}(this, document));



