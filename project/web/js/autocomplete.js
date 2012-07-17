(function($)
{

	var normalize = function(term) {
		var accentMap = { "à": "a", "á": "a", "ã": "a", "ä": "a", "å": "a", "â":"a", "é": "e", "è": "e", "ê": "e", "ë": "e", "ì":"i", "í":"i", "î":"i", "ï":"i", "ð": "o", "ò": "o", "ó": "o", "ô": "o", "õ": "o", "ö":"o", "ù": "u", "ú": "u", "û": "u", "ü": "u", "ý": "y", "ÿ":"y", "ç": "c", "À": "A", "Á": "A", "Â": "A", "Ã": "A", "Ä": "A", "Å": "A", "Ç": "C", "È": "E", "É": "E", "Ê": "E", "Ë": "E", "Ì" : "I", "Í": "I", "Î": "I", "Ï": "I", "Ò":"O", "Ó":"O", "Ô":"O", "Õ":"O", "Ö":"O", "Ù":"U", "Ú":"U", "Û":"U", "Ü":"U", "Ý":"Y" };
		var ret = "";
		for ( var i = 0; i < term.length; i++ ) {
			ret += accentMap[ term.charAt(i) ] || term.charAt(i);
		}
		return ret;
	};

	var search = function(text, term, hightlight) {
		if (!hightlight) {
			hightlight = "<strong>%term%</strong>";
		}
		var reg = new RegExp("[ ]+", "g");
		text = normalize(text);
		term = normalize(term);
		var words_text = text.split(reg);
		var words_term = term.split(reg);
		var text_final = text;
		for(wterm in words_term) {
			var matcher = new RegExp(normalize(words_term[wterm]), "i" );
			var find = false;
			for(wtext in words_text) {
				text_current = words_text[wtext];
			    text_final = text_final.replace(new RegExp("(?![^&;]+;)(?!<[^<>]*)(" +
								 		words_term[wterm] +
										")(?![^<>]*>)(?![^&;]+;)", "i"
										), hightlight.replace("%term%", "$1"));
				if(matcher.test(text_current)) {
					find = true;
					delete words_text[wtext];
					break;
				}


				//delete words_text[wtext];
			}
			if(!find) {
				return false;
			}
		}

		for(wtext in words_text) {
			//text_final = text_final + " " + words_text[wtext];
		}

		return text_final;
	}

	$.widget("ui.combobox", {
		_create: function() {
			var self = this,
			select = this.element.hide(),
			selected = select.children( ":selected" ),
			value = selected.val() ? selected.text() : "";
			
			var newValueOption = $('<option class="new_value" value=""></option>');
			select.append(newValueOption);
			
			var newValueAllowed  = select.hasClass('permissif');
			
			var input = this.input = $( "<input type='text'>" )
			.insertAfter( select )
			.val( value )
			.autocomplete({
				delay: 0,
				minLength: 0,
				source: function( request, response ) {
					response( select.children("option").map(function() {
						var text = $(this).text();
						var text_highlight = search(text, request.term);
						if (this.value && (!request.term || text_highlight != false))
							return {
								label: text_highlight,
								value: text,
								option: this
							};
						}) );
				},
				select: function( event, ui ) {
					ui.item.option.selected = true;
					self._trigger( "selected", event, {
						item: ui.item.option
					});
					$(this).val(ui.item.value.replace(new RegExp("[ ]*\\(.+\\)[ ]*"), ""));
					return false;
				},
				change: function( event, ui ) {
					if ( !ui.item ) {
						var matcher = new RegExp( "^" + $.ui.autocomplete.escapeRegex( $(this).val() ) + "$", "i" ),
						valid = false;
						select.children( "option" ).each(function() {
							if ( $( this ).text().match( matcher ) ) {
								this.selected = valid = true;
								return false;
							}
						});
						if ( !valid ) {
							
							select.val('');
							
							// remove invalid value, as it didn't match anything
							if(newValueAllowed)
							{
								var newValue = $( this ).val();
								
								select.find(':selected').removeAttr('selected');
								newValueOption.attr('selected','selected').val(newValue); //.text(newValue);
							}
							else
							{
								$( this ).val( "" );
								input.data( "autocomplete" ).term = "";
							}
									return false;
								}
							}
						}
					});
					//.addClass( "ui-widget ui-widget-content ui-corner-left" );

					input.data( "autocomplete" )._renderItem = function( ul, item ) {
						return $( "<li></li>" )
						.data( "item.autocomplete", item )
						.append( "<a>" + item.label.replace('(', '<span style="font-size: 10px; color: #aaa" class="code">').replace(')', '</span>') + "</a>" )
						.appendTo( ul );
					};

					this.button = $( "<button type='button'></button>" )
					.attr( "tabIndex", -1 )
					.attr( "title", "Voir toute la liste" )
					.insertAfter( input )
					.button({
						icons: {
							primary: "ui-icon-triangle-1-s"
						},
						text: false
					})
					.removeClass( "ui-corner-all" )
					.addClass( "ui-corner-right ui-button-icon" )
					.click(function() {
						// close if already visible
						if ( input.autocomplete( "widget" ).is( ":visible" ) ) {
							input.autocomplete( "close" );
							return;
						}

						// work around a bug (likely same cause as #5265)
						$( this ).blur();

						// pass empty string as value to search for, displaying all results
						input.autocomplete( "search", "" );
						input.focus();
					});
				},

				destroy: function() {
					this.input.remove();
					this.button.remove();
					this.element.show();
					$.Widget.prototype.destroy.call( this );
				}
			});
})(jQuery);