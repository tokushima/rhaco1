jQuery.extend(jQuery.expr[':'],{containsIgnoreCase:"(a.textContent||a.innerText||jQuery(a).text ()||'').toLowerCase().indexOf((m[3]||'').toLowerCase())>=0"});jQuery.fn.filterOptions=function(chars){var stock=[];this.each(function(i){if($(this).is("select")){if(!$(this).attr("id"))$(this).attr("id","_s"+i);stock.push({clone:$(this).clone(true),id:$(this).attr("id")})}});this.each(function(i){if($(this).is("input")&&$(this).attr("type")=="text"){if(!$(this).attr("id"))$(this).attr("id","_i"+i);$(this).bind('keyup',function(){filter(this)})}});var filter=function(el){var query=$(el).val();if(query&&(query.length>=chars)){$.each(stock,function(i){jQuery('#'+stock[i].id+' > :not(:containsIgnoreCase('+query+'))').remove()})}else{$.each(stock,function(i){jQuery('#'+stock[i].id).replaceWith(stock[i].clone.clone(true))})}}}