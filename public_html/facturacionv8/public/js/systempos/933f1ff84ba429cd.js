(l=>{l.fn.switchFacturalaYa=function(a){var e,t,n;if("string"==typeof a)return e=a,t=Array.prototype.slice.call(arguments,1),(n=this.data("switchFacturalaYaInstance"))&&n[e]?n[e].apply(this,t):this;let i=l.extend({value:"",checked:!1,disabled:!1,onChange:null,name:"",id:""},a);return this.each(function(){let c=l(this);var a;c.hasClass("vm_modal_facturalaya_configpv_switch")||(a=c.text().trim(),a=`
                    <label class="vm_modal_facturalaya_configpv_switch">
                        <input type="checkbox" 
                            ${i.checked?"checked":""} 
                            ${i.disabled?"disabled":""}
                            ${i.name?`name="${i.name}"`:""}
                            ${i.id?`id="${i.id}"`:""}
                            data-value="${i.value}">
                        <span class="vm_modal_facturalaya_configpv_slider"></span>
                    </label>
                    <span class="vm_modal_facturalaya_configpv_switch_label">${a}</span>
                `,c.empty().addClass("vm_modal_facturalaya_configpv_option").html(a));let t=c.find('input[type="checkbox"]'),e=c.find(".vm_modal_facturalaya_configpv_switch_label");c.data("switchFacturalaYaInstance",{getValue:function(){return t.data("value")},setValue:function(a){return t.data("value",a),a},isChecked:function(){return t.is(":checked")},setChecked:function(a,e=!0){t.prop("checked",a),e&&t.trigger("change")},setEnabled:function(a){t.prop("disabled",!a),c.toggleClass("is-disabled",!a)},setText:function(a){e.text(a)},getId:function(){return t.attr("id")},setId:function(a){return t.attr("id",a),a},getInput:function(){return t},getState:function(){return{value:this.getValue(),checked:this.isChecked(),id:this.getId(),enabled:!t.prop("disabled"),text:e.text()}}}),t.on("change",function(a){var e=l(this).is(":checked"),t=l(this).data("value"),n=c.find('input[type="checkbox"]').attr("id");c.toggleClass("is-checked",e),"function"==typeof i.onChange&&i.onChange.call(this,{checked:e,value:t,id:n,name:i.name,element:c}),c.trigger("switchFacturalaYa:change",{checked:e,value:t,id:n,name:i.name,element:c})})})}})(jQuery);