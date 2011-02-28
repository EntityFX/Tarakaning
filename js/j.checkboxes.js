(function($){
	$.fn.checkboxes=function(){
		this.find('input:first').click(function() {
			var isChecked=this.checked;
			$(this).parents('form').find(":checkbox:gt(0):enabled").each(function() {
				this.checked=isChecked;
			});
		});
	}
})(jQuery);