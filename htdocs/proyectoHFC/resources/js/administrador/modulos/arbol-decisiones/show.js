import peticiones from './peticiones.js'
$(function(){

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    peticiones.cargaDetallesRamas()
     
    let tablaHead = $('.tableFixHead').find('thead th')
    $('.tableFixHead').on('scroll', function() {
        // console.log("ejecutando"+this.scrollTop); 
        tablaHead.css('transform', 'translateY('+ this.scrollTop +'px)'); 
    }); 

     
    $("body").on("click",".return_listaDecisionesTab", function(){
            
        peticiones.redirectTabs($('#listaDecisionArbolTab')) 
    })

     //Maximizar

     $(".maxi_tab").click(function(){
       
        $("#tabsArbolDecisiones").toggleClass("fullscreen");
  
        if ($("#tabsArbolDecisiones").hasClass("fullscreen")) {
          console.log("tiene la clase full ")
           $(".maxi_tab").html('<i class="icofont-close-line-squared-alt"></i>') 
        }else{
          console.log("no tiene la clase full ")
          $(".maxi_tab").html('<i class="icofont-maximize"></i>')  
        } 
   
    })

 

})