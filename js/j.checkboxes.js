/**
* @autor Solopiy Artem
* 
*
*/

(function($){
	$.fn.checkboxes=function(param)
	{
		var settings=$.extend({
			element: null, //Internal element for edit
			onText: "+", //Default text for select
			offText: "-", //Default text for deselect
			titleOn: "Select all", //Default title for select
			titleOff: "Deselect all", //Default title for deselect
			defaultSelection: false //Default selection for all elements
		},param||{});
		if (settings.element==null)
		{
			var selectButton=this.find('input:checkbox:first').val((settings.defaultSelection)?settings.offText:settings.onText)
				.attr("title",(settings.defaultSelection)?settings.titleOff:settings.titleOn);
		}
		else
		{
			var selectButton=$(settings.element).html(settings.defaultSelection?settings.titleOff:settings.titleOn);
			selectButton.checked=false;
		}
		$(selectButton).parents('form').find(":checkbox:enabled").attr("checked",settings.defaultSelection);
		$(selectButton).bind("click",settings,
			function(e) 
			{
				var isChecked=null;
				if (e.target.tagName=="A")
				{
					if (e.target.innerHTML==settings.titleOn)
					{
						e.target.innerHTML=settings.titleOff;
						isChecked=true;
					}
					else
					{
						e.target.innerHTML=settings.titleOn;
						isChecked=false;
					}
				}
				else
				{
					if (e.target.type!="checkbox")
					{
						if (e.target.value==settings.onText)
						{
							e.target.title=settings.titleOff;
							e.target.value=settings.offText;
							isChecked=true;
						}
						else
						{
							e.target.title=settings.titleOn;
							e.target.value=settings.onText;
							isChecked=false;
						}
					}
					else
					{
						isChecked=e.target.checked;
					}
				}
				$(this).parents("form").find(":checkbox:enabled").attr("checked",isChecked);
			}
		);
	}
})(jQuery);