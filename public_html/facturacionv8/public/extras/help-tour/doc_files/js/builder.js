/*
 * jQuery iGuider builder js v 1.1
 *
 * Copyright 2018, Linnik Yura | LI MASS CODE | https://codecanyon.net/user/yurik417
 *
 * Last Update 10.11.2018
 */

$(function(){
	var optFlag = false;
	var themeResult = 'material';	
	
	var preview = $('#myIframe');
	var previewSrc = preview.attr('src');
	var previewUpdateId = function(){};
	var previewUpdate = function(optResult){
		clearTimeout(previewUpdateId);
		var type = false;
		previewUpdateId = setTimeout(function(){
			try {
				eval('var tempObj='+optResult); 
				if($('[data-href="5"]').is('.active')){
					//intro
					type = 'intro';
				}
				if($('[data-href="6"]').is('.active')){
					//continue
					type = 'continue';
				}
				
				if($('[data-href="3"]').is('.active')){
					//continue
					type = $('.builderDisplay').attr('data-step') || '1';
				}

				var importJSON = JSON.stringify(tempObj);
			} catch (e) {
				if (e instanceof SyntaxError) {
					alert('The json is not correct: "'+e.message+'" \n1. Insert only the json object, with no extra code \n2. value should be wrapped in quotes. \n3. Inside the value, only double quotes are allowed \n4. Please fix and try again.')
				}
			}

			var dataObj = {
				opt: importJSON,
				type: type,
				theme: themeResult
			}

			window.frames.myIframe.postMessage(dataObj, '*');
		},300);
		
	};
	
	var updateResult = function(){
		var tab = '    ';
		var opt = {};
		var stepArr = [];	
		
		var parseOpt = function(group,obj){
			$('.builderPar',group).each(function(){
				var parItem = $(this);
				var paramLabel = $('.paramLabel',parItem).text();
				var paramVal = $('.paramControl',parItem).val();
				var paramDef = $('.paramControl',parItem).attr('data-default');
				var paramUniq = $('.paramControl',parItem).attr('data-uniq');
				
				if(paramLabel == 'target'){
					paramVal = paramVal
						.replace(/(^<([^>]+)>)[\W|\w]+/g,'$1')
						.replace(/\son[^"]+="[^"]+"/,'')
				}
				
				paramVal = paramVal
					.replace(/^[\"\']|[\"\']$/g,'')
					.replace(/[\"\']/g,'\~');

				if($('.opt-item:checked').is('.opt-all')){
					optFlag = true;	
				}
				
				if(paramVal !== paramDef || paramUniq || optFlag){
					obj[paramLabel] = paramVal;
				}
			});
		};
		
		$('.builder:not(.builderStep--tpl):not(.builder-result):not(.builderImport)').each(function(){

			var builder = $(this);
			var parname = builder.attr('data-parname');

			if(parname == 'general'){
				parseOpt(builder,opt);
			}else{
				if(parname == 'steps'){
					var stepOpt = {};
					parseOpt(builder,stepOpt);
					stepArr.push(stepOpt);
					opt[parname] = stepArr;
				}else{
					var childOpt = {};
					parseOpt(builder,childOpt);
					if(!$.isEmptyObject(childOpt)) {
						opt[parname] = childOpt;
					}
				}	
			}
		});
		
		$.optResult = JSON.stringify(opt, null, 4);
		$.optResult = $.optResult.replace(/\"/g,'\'').replace(/\~/g,'\"').replace(/'false'/g,'false').replace(/'true'/g,'true');
		
		var connectResult = 
			'\n<script src="http://code.jquery.com/jquery-latest.js"></script>'+
			'\n\n<link rel="stylesheet" href="css/iGuider.css">'+
			'\n<script src="js/jquery.iGuider.js"></script>'+
			'\n\n<link rel="stylesheet" href="themes/'+themeResult+'/iGuider-theme-'+themeResult+'.css">'+
			'\n<script src="themes/'+themeResult+'/iGuider-theme-'+themeResult+'.js"></script>';
		var iniResult = 
			"\n\n<script>"+
			"\nvar opt = "+$.optResult+';'+
			"\n\n$(window).on('load',function(){"+
				"\n"+tab+"iGuider('button',opt);"+
			"\n});"+
			"\n</script>";
		
		var result = connectResult + iniResult;
		$('.resultField').val(result);
		var lines = result.split(/\r*\n/g);
		$('.resultField').attr('rows',lines.length);
		if (typeof(Storage) !== "undefined"){
			localStorage.setItem("itour_options", JSON.stringify(opt));
		}
		
		previewUpdate($.optResult);
	};
	
	var deleteBtn = function(){
		if($('.builderStep').length < 3){
			$('.builderStep').find('.deleteStep').addClass('disable');	
			$('.builderStep').find('.stepUp').addClass('disable');
			$('.builderStep').find('.stepDown').addClass('disable');	
		}else{
			$('.builderStep').find('.deleteStep').removeClass('disable');				
			$('.builderStep').find('.stepUp').removeClass('disable');
			$('.builderStep--tpl').next('.builderStep').find('.stepUp').addClass('disable');
			
			$('.builderStep').find('.stepDown').removeClass('disable');	
			$('.builderStep:last').find('.stepDown').addClass('disable');
		}
		stepNum();
	};
	
	var buildForm = function(json){
		json = json.replace(/\~/g,'\'');

		if (/^[\],:{}\s]*$/.test(json.replace(/\\["\\\/bfnrtu]/g, '@').replace(/"[^"\\\n\r]*"|true|false|null|-?\d+(?:\.\d*)?(?:[eE][+\-]?\d+)?/g, ']').replace(/(?:^|:|,)(?:\s*\[)+/g, ''))) {
			var obj = $.parseJSON( json );	
			
			var stepItemWrap = false;
			var addStepFlag = false;
			var parseParam = function(obj, parentParam,stepItemWrap){
				$.each(obj, function(i, val) {
					var param1 = i;
					var value1 = val;
					var paramSave = parentParam || param1;
					var buildGroupTemp = $('[data-parname="'+paramSave+'"]:not(.builderStep--tpl)');
					var buildGroup = buildGroupTemp.length ? buildGroupTemp : $('.paramWrap');
					if(stepItemWrap) {
						buildGroup = stepItemWrap;	
					}
					
					if(paramSave == 'steps' && typeof value1 == 'object' && !Array.isArray(value1)){
						addStep();
						stepItemWrap = $('.builderStep:last');
					}
					if(typeof value1 == 'object'){
						parseParam(value1,paramSave,stepItemWrap);
					}else{
						var builderPar = $('.paramLabel',buildGroup).filter(function() {
							return $(this).text() === param1;
						}).closest('.builderPar');
						if(typeof value1 == 'string'){
							value1 = value1.replace(/\'/g,'\"')
						}
						$('.paramControl',builderPar).val(value1);	
					}
				});
			}
			parseParam(obj);
		}else{
			alert('The json is not correct: "'+e.message+'" \n1. Insert only the json object, with no extra code \n2. value should be wrapped in quotes. \n3. Inside the value, only double quotes are allowed \n4. Please fix and try again.')
			return false;
		}	
	};

	var stepNum = function(){
		$('.builderStep').not('.builderStep--tpl').each(function(e){
			$(this).attr('data-step',(e+1)).find('h3').text('Step '+(e+1)+' options')
		});	
	};

	var addStep = function(){
		var addWrap = $('.addWrap');
		var stepLast = $('.builderStep--tpl');
		var stepClone = stepLast.clone().removeClass('builderStep--tpl');
		addWrap.before(stepClone);
		addWrap.prev('.builder').not('.builderStep--tpl').find('.setDef').trigger('click');
		stepNum();
	};

	$(document).on('keyup','.builderPar .paramControl',function(){
		setPsevdoNumber($(this));
		setPsevdoBoolean($(this));
		setPsevdoSelect($(this));
		updateResult();
	});
	$(document).on('click','.setDef',function(){
		var builder = $(this).closest('.builder');
		$('.paramControl',builder).each(function(){
			var formControl = $(this);
			formControl.val(formControl.attr('data-default'));
		});
		updateResult();
		setPsevdo();
		return false;
	});
	$(document).on('click','.clearVal',function(){
		var builder = $(this).closest('.builder');
		$('.paramControl',builder).each(function(){
			var formControl = $(this);
			formControl.val('').attr('value','');
		});
		updateResult();
		return false;
	});
	$(document).on('click','.addStep',function(){
		addStep();
		updateResult();
		deleteBtn();
		return false;
	});
	$(document).on('click','.deleteStep:not(.disable)',function(){
		var builder = $(this).closest('.builder').remove();
		updateResult();
		deleteBtn();
		return false;
	});
	$(document).on('click','.stepUp',function(){
		var builder = $(this).closest('.builder');
		var builderPrev = builder.prev('.builder').not('.builderStep--tpl');
		var animVal = -builderPrev.outerHeight();
		if(builderPrev.length){
			builder.animate({opacity:0.5});
			$('.paramWrap').animate({scrollTop:'+='+animVal},function(){
				builder.animate({opacity:1},100);
			});
			builderPrev.before(builder);
			updateResult();
			deleteBtn();
			stepNum();
		}
		
		return false;
	});

	$(document).on('click','.stepDown',function(){
		var builder = $(this).closest('.builder');
		var builderNext = builder.next('.builder').not('.builderStep--tpl');
		var animVal = builderNext.outerHeight();
		if(builderNext.length){
			builder.animate({opacity:0.5});
			$('.paramWrap').animate({scrollTop:'+='+animVal},function(){
				builder.animate({opacity:1},100);
			});
			builderNext.after(builder);
			
			updateResult();
			deleteBtn();
			stepNum();
		}
		return false;
	});

	/*for number*/
	$(document).on('input','[data-number_mid]',function(){
		var number_mid = $(this);
		var builderStep = number_mid.closest('.builder');
		var number_pid = $('[data-number_pid="'+number_mid.attr('data-number_mid')+'"]',builderStep);
		number_pid.val(number_mid.val());
	});
	$(document).on('change','[data-number_mid]',function(){
		updateResult();
	});
	
	/*for boolean*/
	$(document).on('change','[data-boolean_mid]',function(){
		var boolean_mid = $(this);
		var builderStep = boolean_mid.closest('.builder');
		var boolean_pid = $('[data-boolean_pid="'+boolean_mid.attr('data-boolean_mid')+'"]',builderStep);
		var checkedVal = 'false';
		if(boolean_mid.prop('checked')){
			checkedVal = 'true';	
		}
		boolean_pid.val(checkedVal);
		updateResult();
	});
	
	/*for select*/
	$(document).on('change','[data-select_mid]',function(){
		var select_mid = $(this);
		var builderStep = select_mid.closest('.builder');
		var select_pid = $('[data-select_pid="'+select_mid.attr('data-select_mid')+'"]',builderStep);
		select_pid.val(select_mid.val());
		updateResult();
	});
	$('.builderPar .paramControl').each(function(){
		$(this).attr('placeholder',$(this).attr('data-default'));
	});
	
	/*cange builder field*/
	
	$('.builder [data-type="number"]').each(function(i){
		var numberFiled = $(this).attr('data-number_pid',i).attr('readonly','readonly');
		var numberChange = $('<input data-number_mid="'+i+'" type="range" value="'+numberFiled.attr('data-default')+'" max="'+numberFiled.attr('data-max')+'" min="'+numberFiled.attr('data-min')+'" step="'+numberFiled.attr('data-step')+'">');
		numberFiled.after(numberChange);
	});

	$('.builder [data-type="boolean"]').each(function(i){
		var booleanFiled = $(this).attr('data-boolean_pid',i).attr('type','hidden');
		var booleanChange = $('<label class="switch"><input data-boolean_mid="'+i+'" type="checkbox"><div class="slider round"></div></label>');
		booleanFiled.after(booleanChange);
	});
	
	$('.builder [data-type="select"]').each(function(i){
		var selectFiled = $(this).attr('data-select_pid',i).attr('type','hidden');
		var selectChange = $('<select placeholder="'+selectFiled.attr('placeholder')+'" class="" data-select_mid="'+i+'"></select>');
		var selectArray = selectFiled.attr('data-options').split(',');
		for(var i = 0; i < selectArray.length ;i++){
			$('<option>').html(selectArray[i]).appendTo(selectChange);
		}
		selectFiled.after(selectChange);
	});
	
	var setPsevdoNumber = function(el){
		if(el.is('[data-number_pid]')){
			var number_pid = el;
			var builderStep = number_pid.closest('.builder');
			var number_mid = $('[data-number_mid="'+number_pid.attr('data-number_pid')+'"]',builderStep);
			number_mid.val(number_pid.val());
		}
	};
	var setPsevdoBoolean = function(el){
		if(el.is('[data-boolean_pid]')){
			var boolean_pid = el;
			var builderStep = boolean_pid.closest('.builder');
			var boolean_mid = $('[data-boolean_mid="'+boolean_pid.attr('data-boolean_pid')+'"]',builderStep);
			var checkedVal = false;
			if(boolean_pid.val() == 'true'){
				checkedVal = true;	
			}
			boolean_mid.prop('checked',checkedVal);
		}	
	};
	var setPsevdoSelect = function(el){
		if(el.is('[data-select_pid]')){
			var select_pid = el;
			var builderStep = select_pid.closest('.builder');
			var select_mid = $('[data-select_mid="'+select_pid.attr('data-select_pid')+'"]',builderStep);
			select_mid.val(select_pid.val());
		}
	};
	
	var setPsevdo = function(){
		$('.builderPar .paramControl').each(function(){
			setPsevdoNumber($(this));
			setPsevdoBoolean($(this));
			setPsevdoSelect($(this));
		});
	};

	var searchStep = function(){
		if(!$('.builderStep--tpl').next('.builderStep').length){
			addStep();	
		}	
	};
	
	var setDefault = function(){
		$('.setDef').each(function(){
			$(this).trigger('click');	
		});
	};
	
	var getLocalStorage = function(){
		if (typeof(Storage) !== "undefined"){
			var saveOpt = localStorage.getItem("itour_options");

			if(saveOpt){
				setDefault();
				buildForm(saveOpt);	
				searchStep();
				updateResult();
				deleteBtn();	
			}else{
				searchStep();
				setDefault();
				updateResult();
				deleteBtn();	
			}
		}else{
			updateResult();
			deleteBtn();	
		}
	}
	getLocalStorage();
	setPsevdo();

	$(document).on('click','.stepImport',function(){
		var importCode = $('.stepImportCode').val();
		
		try {
			eval('var tempObj='+importCode); 

			var importJSON = JSON.stringify(tempObj);
			if (typeof(Storage) !== "undefined"){
				localStorage.setItem("itour_options", importJSON);
			}
			$('.builderStep').not('.builderStep--tpl').remove();
			getLocalStorage();
		} catch (e) {
			if (e instanceof SyntaxError) {
				alert('The json is not correct: "'+e.message+'" \n1. Insert only the json object, with no extra code \n2. value should be wrapped in quotes. \n3. Inside the value, only double quotes are allowed \n4. Please fix and try again.')
			}
		}
		return false;
	});
	
	$('.builderTab').each(function(){
		var builderTab = $(this);
		var startOpen = 0;
		var builderTabItem = $('.builderTabItem',builderTab);
		var builderTabMenu = $('.builderTabMenu a',builderTab);
		builderTabItem.eq(startOpen).addClass('builderTabItem-active');
		builderTabMenu.eq(startOpen).addClass('active');
		builderTabMenu.on('click',function(){
			var tabMenuItem = $(this);
			if(!tabMenuItem.is('.active')){
				builderTabMenu.removeClass('active').filter(tabMenuItem).addClass('active');
				builderTabItem.removeClass('builderTabItem-active').filter('#'+tabMenuItem.attr('data-href')).addClass('builderTabItem-active');
				
				$('.builderDisplay').removeClass('builderDisplay');
				$('.builderStep:visible:first').addClass('builderDisplay');
				
				
				previewUpdate($.optResult);
			}
			return false;
		});
		
	});

	$('.opt-item').on('change',function(){
		if($('.opt-item:checked').is('.opt-all')){
			optFlag = true;	
		}else{
			optFlag = false;		
		}
		updateResult();
	});
	
	var searchThemeName = function(path){
		var pathArr = path.split('/');	
		return pathArr[pathArr.length - 1].split('.')[0];
	};
	
	$('.theme-item').each(function(){
		var themeItem = $(this);
		var pic = $('.theme-item-pic',themeItem);
		var title = searchThemeName(pic.attr('src'));
		$('.theme-title-text',themeItem).text(title);
	});
	
	$('[name="theme-check"]').on('change',function(){
		var itemWrap = $('[name="theme-check"]:checked').closest('.theme-item');
		var pic = $('.theme-item-pic',itemWrap);
		themeResult = searchThemeName(pic.attr('src'));
		
		updateResult();
	});
	
	$(document).on('mouseenter','.builderStep',function(){
		var builderStep = $(this);
		if(!builderStep.is('.builderDisplay')){
			$('.builderDisplay').removeClass('builderDisplay');
			builderStep.addClass('builderDisplay');
			previewUpdate($.optResult);
		}
	});
	
	$(window).on('resize.builder',function(){
		var vw = $(window).width();
		if(vw < 1000){
			$('html').addClass('b-bad-size');
		}else{
			$('html').removeClass('b-bad-size');
		}
	}).trigger('resize.builder');
	
	
	
	
});