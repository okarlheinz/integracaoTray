$(document).ready(function() 
{  

  $("#dados_loja").DataTable({
    columnDefs: [ 
    {
            className: 'control',
            orderable: false,
            targets  : 0
    } ],
    "bProcessing": true,
    "autoWidth": true,
    "order": [],
    "bSort": false,   
    "bInfo":   true,
    "bPaginate": true,
    "scrollCollapse" : true,
    "scrollX": true,
    "searching": true,

    'fnCreatedRow': function (nRow, aData, iDataIndex) {

          
    }
  });  

  $("#produtos_inexistentes").DataTable({
    columnDefs: [ 
    {
            className: 'control',
            orderable: false,
            targets  : 0
    } ],
    "bProcessing": true,
    "autoWidth": true,
    "order": [],
    "bSort": false,   
    "bInfo":   true,
    "bPaginate": true,
    "scrollCollapse" : true,
    "scrollX": true,
    "searching": true,

    'fnCreatedRow': function (nRow, aData, iDataIndex) {

          
    }
  });  

  $("#Pedidos, #Clientes, #Produtos").DataTable({
    columnDefs: [ 
    {
            className: 'control',
            orderable: false,
            targets  : 0
    } ],
    "bProcessing": true,
    "autoWidth": true,
    "order": [],
    "bSort": false,   
    "bInfo":   true,
    "bPaginate": true,
    "scrollCollapse" : true,
    "scrollX": true,
    "searching": true,

    'fnCreatedRow': function (nRow, aData, iDataIndex) {

          
    }
  });  

  $("#notas_Emitidas, .tabela_grid").DataTable({
    columnDefs: [ 
    {
            className: 'control',
            orderable: false,
            targets  : 0
    } ],
    "bProcessing": true,
    "autoWidth": true,
    "order": [],
    "bSort": false,   
    "bInfo":   true,
    "bPaginate": true,
    "scrollCollapse" : true,
    "scrollX": true,
    "searching": true,

    'fnCreatedRow': function (nRow, aData, iDataIndex) {

          
    }
  });  
    
    temporizador();

    // $(window).on("load", function(){
    // // var segundos = 60;
    // // $('#segundo').html(segundos);      
    // // var interval = setInterval(function() {
    // //   segundos--;
    // //   $('#segundo').html(segundos);      

    // //   if (segundos == 0) {
    // //     location.reload();
    // //     clearInterval(interval);
    // //     return false; 
    // //   }
    // // }, 1000);


    //     if($('#enviaEmail').val() == 1)
    //     {   

    //         var m_pse_codigoProduto     = new Array();
    //         var m_pse_descricaoProduto  = new Array();
    //         var m_pse_idFilial          = new Array();
    //         var m_pi_codigoProduto      = new Array();
    //         var m_pi_descricaoProduto   = new Array();
    //         var m_pi_precoVendaProduto  = new Array();
    //         var m_p_orderID             = new Array();
    //         var m_p_CpfCnpjCliente      = new Array();
    //         var m_p_nomeCliente         = new Array();
    //         var m_p_idAcerto            = new Array();
    //         var m_p_idNotaAvulsa        = new Array();
    //         var m_p_idFilial            = new Array();
    //         var m_ne_idNotaAvulsa       = new Array();
    //         var m_ne_idFilial           = new Array();
    //         var m_ne_SERIE_NOTA         = new Array();
    //         var m_ne_numeroNF           = new Array();
    //         var m_ne_CPFCNPJDEST        = new Array();
    //         var m_ne_NOMEDEST           = new Array();
    //         var m_ne_PDF                = new Array();
    //         var m_ne_XML                = new Array();
    //         var m_ne_mensagem           = new Array();

    //         $("input[name='m_pse_codigoProduto[]']").each(function(){
    //             m_pse_codigoProduto.push($(this).val());
    //         })

    //         $("input[name='m_pse_descricaoProduto[]']").each(function(){
    //             m_pse_descricaoProduto.push($(this).val());
    //         })

    //         $("input[name='m_pse_idFilial[]']").each(function(){
    //             m_pse_idFilial.push($(this).val());
    //         })

    //         $("input[name='m_pi_codigoProduto[]']").each(function(){
    //             m_pi_codigoProduto.push($(this).val());
    //         })

    //         $("input[name='m_pi_descricaoProduto[]']").each(function(){
    //             m_pi_descricaoProduto.push($(this).val());
    //         })

    //         $("input[name='m_pi_precoVendaProduto[]']").each(function(){
    //             m_pi_precoVendaProduto.push($(this).val());
    //         })

    //         $("input[name='m_p_orderID[]']").each(function(){
    //             m_p_orderID.push($(this).val());
    //         })

    //         $("input[name='m_p_CpfCnpjCliente[]']").each(function(){
    //             m_p_CpfCnpjCliente.push($(this).val());
    //         })

    //         $("input[name='m_p_nomeCliente[]']").each(function(){
    //             m_p_nomeCliente.push($(this).val());
    //         })

    //         $("input[name='m_p_idAcerto[]']").each(function(){
    //             m_p_idAcerto.push($(this).val());
    //         })

    //         $("input[name='m_p_idNotaAvulsa[]']").each(function(){
    //             m_p_idNotaAvulsa.push($(this).val());
    //         })

    //         $("input[name='m_p_idFilial[]']").each(function(){
    //             m_p_idFilial.push($(this).val());
    //         })

    //         $("input[name='m_ne_idNotaAvulsa[]']").each(function(){
    //             m_ne_idNotaAvulsa.push($(this).val());
    //         })

    //         $("input[name='m_ne_idFilial[]']").each(function(){
    //             m_ne_idFilial.push($(this).val());
    //         })

    //         $("input[name='m_ne_SERIE_NOTA[]']").each(function(){
    //             m_ne_SERIE_NOTA.push($(this).val());
    //         })

    //         $("input[name='m_ne_numeroNF[]']").each(function(){
    //             m_ne_numeroNF.push($(this).val());
    //         })

    //         $("input[name='m_ne_CPFCNPJDEST[]']").each(function(){
    //             m_ne_CPFCNPJDEST.push($(this).val());
    //         })

    //         $("input[name='m_ne_NOMEDEST[]']").each(function(){
    //             m_ne_NOMEDEST.push($(this).val());
    //         })

    //         $("input[name='m_ne_PDF[]']").each(function(){
    //             m_ne_PDF.push($(this).val());
    //         })

    //         $("input[name='m_ne_XML[]']").each(function(){
    //             m_ne_XML.push($(this).val());
    //         })

    //         $("input[name='m_ne_mensagem[]']").each(function(){
    //             m_ne_mensagem.push($(this).val());
    //         })

    //         $("input[name='m_precoVendaProduto[]']").each(function(){        
    //             precoVendaProduto.push($(this).val());
    //         });


    //         $.ajax({

    //           url     : base_url + 'welcome/enviar_email_vtex',              
    //           data    : {   
    //                         m_pse_codigoProduto: m_pse_codigoProduto,
    //                         m_pse_descricaoProduto: m_pse_descricaoProduto,
    //                         m_pse_idFilial: m_pse_idFilial,
    //                         m_pi_codigoProduto: m_pi_codigoProduto,
    //                         m_pi_descricaoProduto: m_pi_descricaoProduto,
    //                         m_pi_precoVendaProduto: m_pi_precoVendaProduto,
    //                         m_p_orderID: m_p_orderID,
    //                         m_p_CpfCnpjCliente: m_p_CpfCnpjCliente,
    //                         m_p_nomeCliente: m_p_nomeCliente,
    //                         m_p_idAcerto: m_p_idAcerto,
    //                         m_p_idNotaAvulsa: m_p_idNotaAvulsa,
    //                         m_p_idFilial: m_p_idFilial,
    //                         m_ne_idNotaAvulsa: m_ne_idNotaAvulsa,
    //                         m_ne_idFilial: m_ne_idFilial,
    //                         m_ne_SERIE_NOTA: m_ne_SERIE_NOTA,
    //                         m_ne_numeroNF: m_ne_numeroNF,
    //                         m_ne_CPFCNPJDEST: m_ne_CPFCNPJDEST,
    //                         m_ne_NOMEDEST: m_ne_NOMEDEST,
    //                         m_ne_PDF: m_ne_PDF,
    //                         m_ne_XML: m_ne_XML,
    //                         m_ne_mensagem: m_ne_mensagem
    //                     },
    //             datatype: 'JSON',
    //             type    : 'post',
    //             success : function(data)
    //             {

    //                 var data = JSON.parse(data);

    //                 if(data == true)
    //                 {
    //                   console.log('Email enviado com sucesso!')
    //                 }
    //                 else
    //                 {

    //                   if(data == 'erro_func')
    //                   {
    //                     console.log('Verifique as configurações do E-mail no Cadastro do Funcionário.')
    //                   }

    //                   if(data == "erro_cliente")
    //                   {
    //                     console.log('E-mail do cliente não cadastrado!')
    //                   }

    //                 }

    //                 return false;
    //             }

    //         });
    //     }
    //     else
    //     {
    //         return false;

    //     }
        

    // });

    $('#btn_start_stop').on('click', function(){

        controlaTempo = $('#controlaTempo').val();
        
        if(controlaTempo == 1)
        {
            $('#controlaTempo').val('0');
            $('#img_tempo').removeAttr('src',base_url+'assets/img/pause_tempo.png');
            $('#img_tempo').attr('src',base_url+'assets/img/play_tempo.png');
            $('.contagem-h,.contagem-m,.contagem-s, #hora, #minuto, #segundo' ).css('color','#999999').css('border-color','#e5000f');
        }
        else
        {
            $('#controlaTempo').val('1');            
            $('#img_tempo').removeAttr('src',base_url+'assets/img/play_tempo.png');
            $('#img_tempo').attr('src',base_url+'assets/img/pause_tempo.png');
            $('.contagem-h,.contagem-m,.contagem-s, #hora, #minuto, #segundo' ).css('color','#1E90FF').css('border-color','#39a02b');
        }

        temporizador();

    });
    $('.hora').html('01'); 
    $('#minuto').html('00')

});

function temporizador()
{
    controlaTempo = $('#controlaTempo').val();

    var segundos = $('#segundo').html(); 

    var interval = setInterval(function() {
        if($('#segundo').html() > 0 )
        {
            segundos--;
        }  
        
         if(controlaTempo == '0')
         {
            clearInterval(interval);
            // $('#segundo').html('0'); 
            return false;
         }
         else
         {
            if (segundos == 0) {   
                $('.segundo').html('30'); 
                segundos = 30
                document.title = "(0) CDS Sistemas - Sincronização Tray"
               // alert('oi');
                location.reload();            
                return false;
            }           
            $('.segundo').html(segundos); 
            document.title = "("+segundos+") CDS Sistemas - Sincronização Tray"
            
         }
        
    }, 1000);

}