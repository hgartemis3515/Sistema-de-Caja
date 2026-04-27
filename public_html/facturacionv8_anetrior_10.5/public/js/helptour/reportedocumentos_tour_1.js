$(function() {
	iGuider({
		tourID:'anyTourID',							//This string allows you to save data with a unique name about tour progress. 
													//It can be used to save information on the progress of the tour for several users. 
													//Or save the progress of each tour separately
		tourTitle:'Primeros Pasos',					//Tour title
		tourSubtitle:'Guía facturalaya.com',
		startStep:1,								//Step from which the tour begins
		
		overlayClickable:true,						//This parameter enables or disables the click event for overlying layer
		overlayColor:'#000',						//Global color values of the overlay layer.
		overlayOpacity:0.5,							//Global opacity values of the overlay layer.
		
		pagination:true,							//Shows the total number of steps and the current step number
		registerMissingElements:true,				//Shows an absent element in tour map and find this element in DOM.
		textDirection:'ltr',						//Global text direction. (ltr, rtl)
		
		shape:0,									//Global shape of highlighting. 0 - rectangle (default), 1 - circle, 2 - rounded rectangle
		shapeBorderRadius:5,						//Global corner radius of rounded rectangle shape. Only when "shape" value is "2"
		
		width:320,									//Global width of the message block
		
		bgColor:false,								//Global background color of the message block
		titleColor:false,							//Global title color of the message block
		
		modalContentColor:false,					//Global content color of the message block
		modalTypeColor:false,						//Global modal type color of the message block
		
		paginationColor:false,						//Global pagination color of the message block
		timerColor:false,							//Global timer color of the message block
		
		btnColor:false,								//Global buttons color
		btnHoverColor:false,						//Global buttons hover color

		spacing:10,									//Global indent highlighting around the element
		loc:false,									//Global path to the page on which the step should work
		timer:false,								//Global time after which an automatic switching to the next step
		timerType:'line',							//Global timer shape type: 'line' or 'circle'
		keyboard:true,								//Tour control by keyboard buttons. Left - the previous step, right - the next step, Esc - close tour.
		keyboardEvent:false,						//This parameter sets the permission to trigger custom events.

		intro:{										//Default intro settings
			enable:true,							//If set to true, before the tour you will see the introductory slide, which will offer to see a tour. 
			
			title:'Bienvenido a facturalaya.com.',												//Title of introduction dialog
			content:'¿Podemos Ayudarte a Emitir tu Primer Comprobante Electrónico?',					//Content of introduction dialog
			
			cover:'',								//Path to the cover of intro
			overlayColor:false,						//For intro, you can specify the different color values of the overlay layer.
			overlayOpacity:false,					//For intro, you can specify the different opacity values of the overlay layer.
			width:false,							//Width of the intro message block
			
			bgColor:false,							//Background color of the intro message block
			titleColor:false,						//Title color of the intro message block
			
			modalContentColor:false,				//Content color of the intro message block
			modalTypeColor:false,					//Modal type color of the intro message block
			
			btnColor:false,							//Buttons color of the intro message block
			btnHoverColor:false						//Buttons Hover color of the intro message block
		},
		
		continue:{									//Default the continue message settings
			enable:true,							//This parameter add the ability to continue the unfinished tour.    
			
			title:'Deseas Continuar con esta Guía?',											//Title of continue dialog
			content:'Click en "Continuar" para empezar con el paso que te quedaste la última vez.',		//Content of continue dialog
			
			cover:'',								//Path to the cover of continue message    
			overlayColor:false,						//For continue message, you can specify the different color values of the overlay layer.
			overlayOpacity:false,					//For continue message, you can specify the different opacity values of the overlay layer.
			width:false,							//Width of the continue message block
			
			bgColor:false,							//Background color of the continue message block
			titleColor:false,						//Title color of the continue message block
			
			modalContentColor:false,				//Content color of the continue message block
			modalTypeColor:false,					//Modal type color of the continue message block
			
			btnColor:false,							//Buttons color of the continue message block
			btnHoverColor:false						//Buttons Hover color of the continue message block
		},

		tourMap: {									//Default Tour Checklist settings
			enable:true,							//This parameter add the ability view list of steps.
			position:'right',						//Checklist Position 
			clickable:true,							//Specifies the clickability of links in the checklist of steps: true - clickable, false - disabled, or 'ready' - clickable only completed steps and current
			open:false,								//Specifies to show or hide the Checklist at the start of the tour 
			
			bgColor:false,							//Background color of the Checklist
			titleColor:false,						//Title color of the Checklist
			
			btnColor:false,							//Buttons color of the checklist block
			btnHoverColor:false,					//Buttons hover color of the checklist block
			
			itemColor:false,						//Item color of the Checklist
			itemHoverColor:false,					//Item hover color of the Checklist
			itemActiveColor:false,					//Item Active color of the Checklist
			itemActiveBg:false,						//Item Active BG color of the Checklist
			itemNumColor:false,						//Item Number color of the Checklist
			
			checkColor:false,						//Check color of the Checklist
			checkReadyColor:false					//Check Ready color of the Checklist
		},
		
		steps:[{									//Default step settings
			cover:'',								//Path to image file
			title:'Emitir Boleta Electrónica',					//Name of step
			content:'Utiliza este botón emitir una boleta electrónica', //Description of step
			position:'auto',						//Position of message
			target:'#btn_boleta_electronica',   	//Unique Name (<div data-target="uniqueName"></div>) of highlighted element or .className (<div class="className"></div>) or #idValue (<div id="idValue"></div>)
			disable:false,							//Block access to element
			overlayOpacity:0.5,						//For each step, you can specify the different opacity values of the overlay layer.
			overlayColor:'#000',					//For each step, you can specify the different color values of the overlay layer in HEX .
			spacing:10,								//Indent highlighting around the element, px
			shape:0,								//Shape of highlighting (0 - rectangle, 1 - circle, 2 - rounded rectangle)
			shapeBorderRadius:5,					//The corner radius of rounded rectangle shape. Only when "shape" value is "2"
			timer:false,							//The time after which an automatic switching to the next step
			event:'next',							//An event that you need to do to go to the next step
			eventMessage:'Follow the required conditions to continue.',		//Message hint for steps with custom events
			skip: false,							//Step can be skipped if you set parameter "skip" to true.
			nextText:'Next',						//The text in the Next Button
			prevText:'Prev',						//The text in the Prev Button
			trigger:false,							//An event which is generated on the selected element, in the transition from step to step
			stepID:'',								//Unique ID Name. This name is assigned to the "html" tag as "data-g-stepid" attribute (If not specified, the plugin generates it automatically in the form: "step-N")
			waitElementTime:0,						//The parameter "waitElementTime" sets the time (ms) to wait for an item to appear
			loc:false,								//The path to the page on which the step should work
			ready:false,							//This parameter indicates whether the step was completed or not.
			width:320,								//Width of the message block
			
			bgColor:false,							//Background color of the message block
			titleColor:false,						//Title color of the message block
			
			modalContentColor:false,				//Content color of the message block
			
			paginationColor:false,					//Pagination color of the message block
			timerColor:false,						//Timer color of the message block
			
			btnColor:false,							//Buttons color of the message block
			btnHoverColor:false,					//Buttons Hover color of the message block
			
			keyboardEvent:false,					//This parameter sets the permission to simulate user events for step.

			checkNext:{                             //Function in which you can carry out any verification by clicking on the "Next" button. 
				func:function(){return true;},       ////If the function returns True, the step will be switched.
				messageError:'Fulfill all conditions!'  //If the function returns "False", an error message will appear in the message window
			},
			checkPrev:{                             //Function in which you can carry out any verification by clicking on the "Prev" button. 
				func:function(){return true;},
				messageError:'Fulfill all conditions!'  
			},
			before:function(target){},					//Triggered before the start of step
			during:function(target){},					//Triggered after the onset of step
			after:function(target){},						//Triggered After completion of the step, but before proceeding to the next
			delayBefore:0,							//The delay before the element search, ms
			delayAfter:0							//The delay before the transition to the next step, ms
		}, {									//Default step settings
			cover:'',								//Path to image file
			title:'Emitir Factura Electrónica',					//Name of step
			content:'Utiliza este botón emitir una Factura electrónica', //Description of step
			position:'auto',						//Position of message
			target:'#btn_factura_electronica',   	//Unique Name (<div data-target="uniqueName"></div>) of highlighted element or .className (<div class="className"></div>) or #idValue (<div id="idValue"></div>)
			disable:false,							//Block access to element
			overlayOpacity:0.5,						//For each step, you can specify the different opacity values of the overlay layer.
			overlayColor:'#000',					//For each step, you can specify the different color values of the overlay layer in HEX .
			spacing:10,								//Indent highlighting around the element, px
			shape:0,								//Shape of highlighting (0 - rectangle, 1 - circle, 2 - rounded rectangle)
			shapeBorderRadius:5,					//The corner radius of rounded rectangle shape. Only when "shape" value is "2"
			timer:false,							//The time after which an automatic switching to the next step
			event:'next',							//An event that you need to do to go to the next step
			eventMessage:'Follow the required conditions to continue.',		//Message hint for steps with custom events
			skip: false,							//Step can be skipped if you set parameter "skip" to true.
			nextText:'Next',						//The text in the Next Button
			prevText:'Prev',						//The text in the Prev Button
			trigger:false,							//An event which is generated on the selected element, in the transition from step to step
			stepID:'',								//Unique ID Name. This name is assigned to the "html" tag as "data-g-stepid" attribute (If not specified, the plugin generates it automatically in the form: "step-N")
			waitElementTime:0,						//The parameter "waitElementTime" sets the time (ms) to wait for an item to appear
			loc:false,								//The path to the page on which the step should work
			ready:false,							//This parameter indicates whether the step was completed or not.
			width:320,								//Width of the message block
			
			bgColor:false,							//Background color of the message block
			titleColor:false,						//Title color of the message block
			
			modalContentColor:false,				//Content color of the message block
			
			paginationColor:false,					//Pagination color of the message block
			timerColor:false,						//Timer color of the message block
			
			btnColor:false,							//Buttons color of the message block
			btnHoverColor:false,					//Buttons Hover color of the message block
			
			keyboardEvent:false,					//This parameter sets the permission to simulate user events for step.

			checkNext:{                             //Function in which you can carry out any verification by clicking on the "Next" button. 
				func:function(){return true;},       ////If the function returns True, the step will be switched.
				messageError:'Fulfill all conditions!'  //If the function returns "False", an error message will appear in the message window
			},
			checkPrev:{                             //Function in which you can carry out any verification by clicking on the "Prev" button. 
				func:function(){return true;},
				messageError:'Fulfill all conditions!'  
			},
			before:function(target){},					//Triggered before the start of step
			during:function(target){},					//Triggered after the onset of step
			after:function(target){},						//Triggered After completion of the step, but before proceeding to the next
			delayBefore:0,							//The delay before the element search, ms
			delayAfter:0							//The delay before the transition to the next step, ms
		}, {									//Default step settings
			cover:'',								//Path to image file
			title:'Emitir Nota de Crédito Electrónica',					//Name of step
			content:'Utiliza este botón emitir una nota de crédito electrónica', //Description of step
			position:'auto',						//Position of message
			target:'#btn_notacredito_electronica',   	//Unique Name (<div data-target="uniqueName"></div>) of highlighted element or .className (<div class="className"></div>) or #idValue (<div id="idValue"></div>)
			disable:false,							//Block access to element
			overlayOpacity:0.5,						//For each step, you can specify the different opacity values of the overlay layer.
			overlayColor:'#000',					//For each step, you can specify the different color values of the overlay layer in HEX .
			spacing:10,								//Indent highlighting around the element, px
			shape:0,								//Shape of highlighting (0 - rectangle, 1 - circle, 2 - rounded rectangle)
			shapeBorderRadius:5,					//The corner radius of rounded rectangle shape. Only when "shape" value is "2"
			timer:false,							//The time after which an automatic switching to the next step
			event:'next',							//An event that you need to do to go to the next step
			eventMessage:'Follow the required conditions to continue.',		//Message hint for steps with custom events
			skip: false,							//Step can be skipped if you set parameter "skip" to true.
			nextText:'Next',						//The text in the Next Button
			prevText:'Prev',						//The text in the Prev Button
			trigger:false,							//An event which is generated on the selected element, in the transition from step to step
			stepID:'',								//Unique ID Name. This name is assigned to the "html" tag as "data-g-stepid" attribute (If not specified, the plugin generates it automatically in the form: "step-N")
			waitElementTime:0,						//The parameter "waitElementTime" sets the time (ms) to wait for an item to appear
			loc:false,								//The path to the page on which the step should work
			ready:false,							//This parameter indicates whether the step was completed or not.
			width:320,								//Width of the message block
			
			bgColor:false,							//Background color of the message block
			titleColor:false,						//Title color of the message block
			
			modalContentColor:false,				//Content color of the message block
			
			paginationColor:false,					//Pagination color of the message block
			timerColor:false,						//Timer color of the message block
			
			btnColor:false,							//Buttons color of the message block
			btnHoverColor:false,					//Buttons Hover color of the message block
			
			keyboardEvent:false,					//This parameter sets the permission to simulate user events for step.

			checkNext:{                             //Function in which you can carry out any verification by clicking on the "Next" button. 
				func:function(){return true;},       ////If the function returns True, the step will be switched.
				messageError:'Fulfill all conditions!'  //If the function returns "False", an error message will appear in the message window
			},
			checkPrev:{                             //Function in which you can carry out any verification by clicking on the "Prev" button. 
				func:function(){return true;},
				messageError:'Fulfill all conditions!'  
			},
			before:function(target){},					//Triggered before the start of step
			during:function(target){},					//Triggered after the onset of step
			after:function(target){},						//Triggered After completion of the step, but before proceeding to the next
			delayBefore:0,							//The delay before the element search, ms
			delayAfter:0							//The delay before the transition to the next step, ms
		}, {									//Default step settings
			cover:'',								//Path to image file
			title:'Emitir Nota de Débito Electrónica',					//Name of step
			content:'Utiliza este botón emitir una nota de débito electrónica', //Description of step
			position:'auto',						//Position of message
			target:'#btn_notadebito_electronica',   	//Unique Name (<div data-target="uniqueName"></div>) of highlighted element or .className (<div class="className"></div>) or #idValue (<div id="idValue"></div>)
			disable:false,							//Block access to element
			overlayOpacity:0.5,						//For each step, you can specify the different opacity values of the overlay layer.
			overlayColor:'#000',					//For each step, you can specify the different color values of the overlay layer in HEX .
			spacing:10,								//Indent highlighting around the element, px
			shape:0,								//Shape of highlighting (0 - rectangle, 1 - circle, 2 - rounded rectangle)
			shapeBorderRadius:5,					//The corner radius of rounded rectangle shape. Only when "shape" value is "2"
			timer:false,							//The time after which an automatic switching to the next step
			event:'next',							//An event that you need to do to go to the next step
			eventMessage:'Follow the required conditions to continue.',		//Message hint for steps with custom events
			skip: false,							//Step can be skipped if you set parameter "skip" to true.
			nextText:'Next',						//The text in the Next Button
			prevText:'Prev',						//The text in the Prev Button
			trigger:false,							//An event which is generated on the selected element, in the transition from step to step
			stepID:'',								//Unique ID Name. This name is assigned to the "html" tag as "data-g-stepid" attribute (If not specified, the plugin generates it automatically in the form: "step-N")
			waitElementTime:0,						//The parameter "waitElementTime" sets the time (ms) to wait for an item to appear
			loc:false,								//The path to the page on which the step should work
			ready:false,							//This parameter indicates whether the step was completed or not.
			width:320,								//Width of the message block
			
			bgColor:false,							//Background color of the message block
			titleColor:false,						//Title color of the message block
			
			modalContentColor:false,				//Content color of the message block
			
			paginationColor:false,					//Pagination color of the message block
			timerColor:false,						//Timer color of the message block
			
			btnColor:false,							//Buttons color of the message block
			btnHoverColor:false,					//Buttons Hover color of the message block
			
			keyboardEvent:false,					//This parameter sets the permission to simulate user events for step.

			checkNext:{                             //Function in which you can carry out any verification by clicking on the "Next" button. 
				func:function(){return true;},       ////If the function returns True, the step will be switched.
				messageError:'Fulfill all conditions!'  //If the function returns "False", an error message will appear in the message window
			},
			checkPrev:{                             //Function in which you can carry out any verification by clicking on the "Prev" button. 
				func:function(){return true;},
				messageError:'Fulfill all conditions!'  
			},
			before:function(target){},					//Triggered before the start of step
			during:function(target){},					//Triggered after the onset of step
			after:function(target){},						//Triggered After completion of the step, but before proceeding to the next
			delayBefore:0,							//The delay before the element search, ms
			delayAfter:0							//The delay before the transition to the next step, ms
		}],
	
		lang: {										//Default language settings
			cancelTitle: 'Cancelar Guía',				//The title in the cancel tour button
			cancelText: '×',						//The text in the cancel tour button
			hideText: 'Ocultar Indice',				//The text in the hidden tour map button 
			tourMapText:'≡',						//The text in the show tour button
			tourMapTitle: 'Tour Map',				//Title of Tour map button
			nextTextDefault:'Siguiente',					//The text in the Next Button
			prevTextDefault:'Anterior',					//The text in the Prev Button
			endText:'Finalizar',						//The text in the End Tour Button

			contDialogBtnBegin:'Start over',		//Text in the start button of continue dialog 
			contDialogBtnContinue:'Continuar',		//Text in the continue button of continue dialog 
			
			introDialogBtnStart:'Empezar',			//Text in the start button of introduction dialog
			introDialogBtnCancel:'Cancelar',			//Text in the cancel button of introduction dialog
		   
			modalIntroType:'Tour Intro',			//Type Name of intro dialog
			modalContinueType:'Unfinished Tour'		//Type Name of continue dialog
		},
		
		create: function(){},						//Triggered when the iGuider is created
		start: function(){},						//Triggered before first showing the step
		progress: function(data){},					//Triggered together with start any step
		end: function(){},							//Triggered when the tour ended, or was interrupted
		abort: function(){},						//Triggered when the tour aborted
		finish: function(){},						//Triggered when step sequence is over
		play: function(){},							//Triggered when the timer state switches to "play"
		pause: function(){},						//Triggered when the timer state switches to "pause"
		
		modalTemplate:								//Modal window template
		'<div class="gWidget">'+
			'<div class="gCover">[modal-cover]</div>'+
			'<div class="gAction">'+
				'<span class="gType">[modal-type]</span>'+
				'<span class="gBtn">[modal-map]</span>'+
				'<span class="gBtn">[modal-close]</span>'+
				'<div class="gTimer">[modal-timer]</div>'+
			'</div>'+
			'<div class="gScroll">'+
				'<div class="gHeader">[modal-header]</div>'+
				'<div class="gContent">[modal-body]</div>'+
			'</div>'+
			'<div class="gFooter">'+
				'<span class="gPage">'+
					'<span class="gPageVal">[step-value]</span>'+
					'<span class="gPageTotal">[step-total]</span>'+
				'</span>'+
				'<span class="gBtn">[modal-prev]</span>'+
				'<span class="gBtn">[modal-next]</span>'+
				'<span class="gBtn">[modal-cancel]</span>'+
				'<span class="gBtn">[modal-start]</span>'+
				'<span class="gBtn">[modal-begin-first]</span>'+
				'<span class="gBtn">[modal-begin-continue]</span>'+
			'</div>'+
		'</div>',
		
		mapTemplate:								//Tour Map template
		'<div class="g-map-pos">'+
			'<div class="gMapAction">'+
				'<span class="gBtn">[map-toggle]</span>'+
				'<span class="gBtn">[map-hide]</span>'+
			'</div>'+
			'<div class="gMapHeader">[map-header]</div>'+
			'<span class="gPage">'+
				'<span class="gPageVal">[step-value]</span>'+
				'<span class="gPageTotal">[step-total]</span>'+
			'</span>'+
			'<div class="gMapContent">[map-content]</div>'+
			'<div class="gMapBufer"></div>'+
		'</div>',
		
		debug: false								//Display of messages in the console
	});
});