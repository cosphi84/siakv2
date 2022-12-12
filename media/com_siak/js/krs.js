//Compose template string
String.prototype.compose = (function (){
    var re = /\{{(.+?)\}}/g;
    return function (o){
            return this.replace(re, function (_, k){
                return typeof o[k] != 'undefined' ? o[k] : '';
            });
        }
    }());

var num = 0;
var ttlSKS = 0;
var mkId = [];

function saveKRSData()
{
    if(confirm("Yakin isian sudah benar?",))
    {
        console.log("OKEE");
    }
}

function jSelectMatakuliah(id, title, catid, object, url, language)
{
    let link = 'index.php?option=com_siak&view=matkuls&format=json';
    let token = jQuery('#token').attr('name');
    var tbody = jQuery('#tblMatakuliah').children('tbody').last();
    var table = tbody.length ? tbody : jQuery('#tblMatakuliah');
    var frmTtlSKS = jQuery("input[id=jform_ttl_sks]");

    var row = '<tr>'+
        '<td class="center">{{num}}</td>'+
        '<td><input type="hidden" name="jform[mk][]" value="{{id}}">{{kode}}</td>'+
        '<td>{{matkul}}</td>'+
        '<td class="center">{{sks}}</td>'+
        '<td class="center"><a href="javascript:void(0);" onclick="deletemk();"><span class="icon-delete"></span></a></td>'+
        '</tr>';
        
    jQuery.ajax({
        beforeSend: function(){
            jQuery('#cover-spin').show(0);
        },
        url: link,
        data: {  [token]: "1",  filter_search: title },
        success: function(result, status, xhr){ 
            if(!mkId.includes(result.data[0].id)){            
                ttlSKS = ttlSKS + Number(result.data[0].sks);     
                result.data[0].num = num;                       
                table.append(row.compose(result.data[0]));
                frmTtlSKS.val(ttlSKS);
                mkId.push(result.data[0].id);
            }
        },
        error: function(){
            jQuery('#cover-spin').hide();
            alert('Ajax Error boy!');
        },
        complete: function(){
            jQuery('#loading').hide(); 
        }
    });
    jQuery('#ModalSelectMKS').modal('hide');
    
    num = num + 1;
    return true;
}


function deletemk(id=null)
{
    if(confirm('Yakin akan menghapus matakuliah ini?')){
        if(id==null)
        {
            removethis();
        }else{
            let token = jQuery('#token').attr('name');
            let url = 'index.php?option=com_siak&task=exec&format=json';
            let payload = {
                model: 'krs',
                method: 'deletemk',
                id: id
            };
            console.log(url);
            
            jQuery.ajax({
                url: url,
                data: {[token]: "1", var: payload},
                success: function(result, status, xhr){
                    //console.log(result);
                    location.reload(); 
                    console.log(mkId);
                    console.log(ttlSKS);
                }
                
            });
            
            //           
        }
    }
}

function removethis()
{
    var tbody = jQuery('#tblMatakuliah').children('tbody').last();
    var frmTtlSKS = jQuery("input[id=jform_ttl_sks]");
    tbody.empty();
    num = 0;
    mkId.splice(0, mkId.length);
    ttlSKS = 0;
    frmTtlSKS.val(ttlSKS);
}

Joomla.submitbutton = function(task)
{
	if (task == '')
	{
		return false;
	}
	else
	{
		var isValid=true;
		var action = task.split('.');
        
        
		

		if (action[1] != 'cancel' && action[1] != 'close')
		{
			var forms = jQuery('form.form-validate');
			for (var i = 0; i < forms.length; i++)
			{
				if (!document.formvalidator.isValid(forms[i]))
				{
					isValid = false;
					break;
				}
			}

            if(ttlSKS < 1)
            {
                isValid = false;
                alert('Matakuliahnya masih Kosong!')
            }
		}		
	
		if (isValid)
		{
			Joomla.submitform(task);
			return true;
		}
		else
		{
			alert(Joomla.JText._('COM_SIAK_FORM_ERROR_UNACCEPTABLE',
			                     'Some values are unacceptable'));
			return false;
		}
	}
}

jQuery('document').ready(function(){
    let sks_val = jQuery("#tblMatakuliah td:nth-child(4)");
    //ttlSKS = Number(jQuery("#jform_ttl_sks").val());
    let id_mks = jQuery("input[type=hidden][class=mkid]");

    id_mks.each(function(key, value){
        mkId.push(value.value);
    });

    sks_val.each(function(){
        ttlSKS = ttlSKS + Number(this.textContent);
    });
    jQuery("#jform_ttl_sks").val(ttlSKS);
});
