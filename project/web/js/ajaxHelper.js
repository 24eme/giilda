/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */


var toPost = function(elt)
{
    var result = {}
    $(elt).find('input').each(function()
    {
        if($(this).attr('value')!="") result[$(this).attr('name')] = $(this).attr('value');
    });
    return result;
}

var ajaxifyPost = function(url, section, buttonElt)
{
     $(buttonElt).unbind();
     $(buttonElt).bind('click',function()
        {   
            
            var params = {id : section.id} ;
            $.extend(params, toPost($(section.div)));
            $.post(url,params,
            function(data){
                $(section.div).html(data);
            });
        }); 
}

var ajaxifyGet = function(url, params, buttonElt,eltToReplace)
{
    if(typeof(params)=="string")
    {     
        $(buttonElt).click(function()
        {        
            $.get(url,{id : $(params).val()},
            function(data){
                $(eltToReplace).html(data);
            });
        }); 
    }
    else
    {
        if(typeof(params)=="object"){
            $(buttonElt).click(function()
            {  
                for (var i in params)
                {
                    var fieldExp = new RegExp("^field");
                    if(fieldExp.test(i))
                    {
                    var fieldName = params[i];
                    if(fieldName[0]=='#')
                    {
                        fieldName = fieldName.substr(1,fieldName.length-1); 
                    }
                    var fieldVal = $(params[i]).val();
                    var params2 = {};
                    params2[fieldName] = fieldVal;
                    $.extend(params, params2);
                    delete params[i];
                    $.get(url,params,
                        function(data){
                            $(eltToReplace).html(data);
                        });
                    }
                }
            }); 
        }
    }
}


/*
 *
 */
var ajaxifyAutocompleteGet = function(url,params,eltToReplace)
{
    if(typeof(params)=="string")
    { 
        ajaxifyAutocompleteGetOneP(url,params,eltToReplace);
    }
    else
    {
        for (var i in params) 
        {
            if(i=="autocomplete")
            {
               var autocompleteEltName = params[i];
               delete params.autocomplete;
               ajaxifyAutocompleteGetMultiP(url,params,autocompleteEltName,eltToReplace);
               break;
            }
        }
    }    
}


/*
 * 
 */
var ajaxifyAutocompleteGetOneP = function(url,autocompleteEltName,eltToReplace)
{
    $(autocompleteEltName+' input').live( "autocompleteselect", function(event, ui)
    {         
        $.get(url,{id : ui.item.option.value},
        function(data){
            $(eltToReplace).html(data);
        });
    });               
}

/*
 * 
 */
var ajaxifyAutocompleteGetMultiP = function(url,params,autocompleteEltName,eltToReplace)
{
    $(autocompleteEltName+' input').live( "autocompleteselect", function(event, ui)
    {   
        $.extend(params, {id : ui.item.option.value});
        $.get(url,params,
        function(data){
            $(eltToReplace).html(data);
        });
    });               
}

