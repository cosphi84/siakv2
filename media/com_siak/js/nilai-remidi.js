var elemenNilai = JSON.parse(Joomla.getOptions('com_siak'));

jQuery(document).ready(function(){
    jQuery("#jform_nilai_akhir_remid").on("input", function(){
        var nilai = Number(this.value);
        var frmNilaiMutu = jQuery('#jform_nilai_remid_mutu');
        var frmNilaiHuruf = jQuery('#jform_nilai_remid_angka');
        var nilaiAngka = 0;
        var nilaiHuruf = "E";
       
        for(let x in elemenNilai){
            if( (nilai <= Number(elemenNilai[x].atas)) && (nilai >= Number(elemenNilai[x].bawah)) )
            {
                nilaiHuruf = x;
                break;
            }
            
        
        }       

        switch(nilaiHuruf)
        {
            case 'A':
                nilaiAngka = 4;
                break;
                
            case "B":
                nilaiAngka = 3;
                break;

            case "C":
                nilaiAngka = 2;
                break;
            case "D":
                nilaiAngka = 1;
                break;
            
        }
        
        frmNilaiHuruf.prop("readonly", false);
        frmNilaiHuruf.val(nilaiHuruf);
        frmNilaiHuruf.prop("readonly", true);
        frmNilaiMutu.prop("readonly", false);
        frmNilaiMutu.val(nilaiAngka);
        frmNilaiMutu.prop("readonly", true);
    });
});

