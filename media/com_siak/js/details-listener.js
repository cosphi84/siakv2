(function() {
	"use strict";

    document.addEventListener('DOMContentLoaded', function(){
		
		var elements = document.querySelectorAll('.show-detail');
        

		for(var i = 0, l = elements.length; l>i; i++) {
			
			elements[i].addEventListener('click', function (event) {
				event.preventDefault();
                let params = `scrollbars=no,resizable=no,status=no,location=no,toolbar=no,menubar=no,width=800,height=300,left=100,top=100`;
				let p = event.target.getAttribute('data-p');
				let j = event.target.getAttribute('data-j');
				let s = event.target.getAttribute('data-s');
                let url = 'index.php?option=com_siak&view=paketmks&tmpl=component&layout=detail&filter_prodi='+p+'&filter_semester='+s+'&filter_jurusan='+j;
				
				//var functionName = event.target.getAttribute('data-function');
				//window.parent[functionName](event.target.getAttribute('data-id'), event.target.getAttribute('data-title'), null, null,null, null, null);
                window.open(url, 'DetailPaketMK', params);
                //alert('sid : '+event.target.getAttribute('data-sid'));
			})
		}
	});
})(); 