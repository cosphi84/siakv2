
Joomla.hitungNilai = function(item){
    
    var bobotNIlai = Joomla.getOptions('bobotNilai');
    
    let id = item.id.split('_')[2];
    let rawNilai = {};
    let nilaiAkhir;
    let angkaMutu;
    let hurufMutu;

    
    for(let i = 0; i<bobotNIlai.length; i++)
    {
        let key = bobotNIlai[i].title.toLowerCase();
        let bobot = Number(bobotNIlai[i].bobot) / 100;
        let nilai = jQuery('#jform_'+bobotNIlai[i].title.toLowerCase()+'_'+id).val();
        if(nilai != null)
        {
            rawNilai[key] = Number(nilai) * bobot;
            
        }else{
            rawNilai[key] = 0;
        }
    }
    
    nilaiAkhir = sum(rawNilai);

    if(75.00 <= nilaiAkhir && nilaiAkhir <= 100.00){
            angkaMutu = 4;
            hurufMutu = 'A';
    }else if(65.00 <= nilaiAkhir && nilaiAkhir <= 74.99 ){
            angkaMutu = 3;
            hurufMutu = 'B';
    }else if(50.00 <= nilaiAkhir && nilaiAkhir <= 64.99){
            angkaMutu = 2;
            hurufMutu = 'C';
    }else if(35.00 <= nilaiAkhir && nilaiAkhir <= 49.99){
            angkaMutu = 1;
            hurufMutu = 'D';
    }else{
            angkaMutu = 0;
            hurufMutu = 'E';
    }
    jQuery('#td_nilai_akhir_'+id).text(nilaiAkhir);
    jQuery('#td_angka_mutu_'+id).text(angkaMutu);
    jQuery('#td_huruf_mutu_'+id).text(hurufMutu); 

    jQuery('#jform_nilai_akhir_'+id).val(nilaiAkhir);
    jQuery('#jform_nilai_mutu_'+id).val(angkaMutu);
    jQuery('#jform_nilai_angka_'+id).val(hurufMutu); 
    
    console.log(jQuery('#jform_nilai_akhir_'+id).val());
    
}

function sum( obj ) {
    var sum = 0;
    for( var el in obj ) {
      if( obj.hasOwnProperty( el ) ) {
        sum += parseFloat( obj[el] );
      }
    }
    return sum;
  }