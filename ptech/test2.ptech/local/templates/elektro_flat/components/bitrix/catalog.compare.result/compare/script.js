BX.namespace("BX.Iblock.Catalog");

BX.Iblock.Catalog.CompareClass = (function()
{
	var CompareClass = function(wrapObjId)
	{
		this.wrapObjId = wrapObjId;
	};

	CompareClass.prototype.MakeAjaxAction = function(url)
	{
		BX.showWait(BX(this.wrapObjId));
		BX.ajax.post(
			url,
			{
				ajax_action: 'Y'
			},
			BX.proxy(function(result)
			{
	                        //console.log('aaa');
				BX.closeWait();
				BX(this.wrapObjId).innerHTML = result;
				$('select').styler({
				  selectSearch: true
				});
							
        		}, this)
		);
	};

	return CompareClass;
})();


