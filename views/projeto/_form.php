<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\grid\GridView;
use yii\widgets\Pjax;
use yii\helpers\ArrayHelper;
use app\models\Escopo;
use app\models\Atividademodelo;
use yii\helpers\Url;
use kartik\money\MaskMoney;
use kartik\tabs\TabsX;
use kartik\popover\PopoverX;
use yii\bootstrap\Modal;
/* @var $this yii\web\View */
/* @var $model app\models\Projeto */
/* @var $form yii\widgets\ActiveForm */
?>
<style>
.table-bordered > tbody > tr > td{
  padding-top: 3px !important;
  padding-bottom: 3px !important;
}

.table-striped > tbody > tr:nth-of-type(odd){
  background-color: #b6b6b6 !important;
}

.summary{
  display: none;
}

input[type=number]::-webkit-inner-spin-button, 
input[type=number]::-webkit-outer-spin-button { 
    -webkit-appearance: none;
    -moz-appearance: none;
    appearance: none;
    margin: 0; 
}
.autocomplete {
  /*the container must be positioned relative:*/
  position: relative;
  display: inline-block;
}
.autocomplete-items {
  position: absolute;
  border: 1px solid #d4d4d4;
  border-bottom: none;
  border-top: none;
  z-index: 99;
  /*position the autocomplete items to be the same width as the container:*/
  top: 100%;
  left: 0;
  right: 0;
}
.autocomplete-items div {
  padding: 10px;
  cursor: pointer;
  background-color: #fff; 
  border-bottom: 1px solid #d4d4d4; 
}
.autocomplete-items div:hover {
  /*when hovering an item:*/
  background-color: #e9e9e9; 
}
.autocomplete-active {
  /*when navigating through the items using the arrow keys:*/
  background-color: DodgerBlue !important; 
  color: #ffffff; 
}
.barra-btn{
  display:block;
  position:fixed;
  width:100%;
  bottom:0vh;
  left:0;
  background:#62727b;
  text-align:center;
  padding: 0px 0;
  z-index: 99;
}

.btn-barra {
  background-color: #62727b; 
  border-color: #62727b;
  color:white;
  -webkit-transition-duration: 0.4s; /* Safari */
  transition-duration: 0.4s;
}
.btn-barra:hover {
    background-color: white; /* Green */
    color: white;
}
</style>


<?php 

$scroll='';
if(!$model->isNewRecord){
  $scroll = 'window.scrollTo(0, 1200);';
}

$fileName = '';
if(!$model->isNewRecord){ 
  $fileName = '/web/uploaded-files/'.$model->id.'/'.$model->proposta;  
}

$this->registerJs('

  $( document ).ready(function() {
    '.$scroll.'

    $("#enviarEmail").click(function (e) {
      $("#loading").show(); // show the gif image when ajax starts
      var projeto_id = window.location.href.split("id=")[1];
        $.ajax({ 
          url: "index.php?r=projeto/enviaremail",
          data: {remetentes: $("#remetente").val(), assunto: $("#assunto").val(), corpoEmail: $("#corpoEmail").val(), nomeArquivo: "'.$fileName.'", projeto_id: projeto_id},
          type: "POST",
          success: function(response){
           $("#loading").hide();
           $("#close_modal").click(); 
           alert("Email enviado com sucesso!");
           console.log(response);
           location.reload();
         },
         error: function(request, status, error){
          alert(request.responseText);
        }
      });
    });

    $("#salvarNota").click(function (e) {
      $("#loading_nota").show(); // show the gif image when ajax starts
      var projeto_id = window.location.href.split("id=")[1];
      var nota_geral = $("#projeto-nota_geral").val();
        $.ajax({ 
          url: "index.php?r=projeto/salvarnotasgerais",
          data: {nota_geral: nota_geral, projeto_id: projeto_id},
          type: "POST",
          success: function(response){
           $("#loading_nota").hide();
           $("#close_modal").click(); 
           alert("Nota salva com sucesso!");
           console.log(response);           
         },
         error: function(request, status, error){
          alert(request.responseText);
        }
      });
    });

    $("input").removeClass("form-control");
    
    $("input[id^=\'projeto\']").addClass("form-control");
    $("#remetente").addClass("form-control");
    $("#assunto").addClass("form-control");
    $("#projeto-as_aprovada").removeClass("form-control");
    $(".np_autocomplete").addClass("form-control");

    $(".messages-inline").text("");

    //se tiver marcado como Projeto
    if($("#projeto-tipo")[0].children[1].children[0].checked){
      $("#disciplinas-div").prop("hidden", "hidden");
      $("#desc_resumida_div").css("margin-top","0px");
    }
  });

  $("input[name*=tipo]").change(function(ev){
    if($("#projeto-tipo")[0].children[1].children[0].checked){
      $("#disciplinas-div").prop("hidden", "hidden");
      $("#desc_resumida_div").css("margin-top","0px");
    }
    else{
      $("#disciplinas-div").prop("hidden", "");
      $("#desc_resumida_div").css("margin-top","-4px");
    }
  });

  $("#Automação_checkbox").change(function(ev){
    if($(this).prop("checked")){
      $("#disciplina_Automação_div").removeAttr("hidden");
    }else{
      $("#disciplina_Automação_div").attr("hidden", "hidden");
    }
  });

  $("#Processo_checkbox").change(function(ev){
    if($(this).prop("checked")){
      $("#disciplina_Processo_div").removeAttr("hidden");
    }else{
      $("#disciplina_Processo_div").attr("hidden", "hidden");
    }
  });

  $("#Instrumentação_checkbox").change(function(ev){
    if($(this).prop("checked")){
      $("#disciplina_Instrumentação_div").removeAttr("hidden");
    }else{
      $("#disciplina_Instrumentação_div").attr("hidden", "hidden");
    }
  });

  /*$("#Diagrama_checkbox").change(function(ev){
    if($(this).prop("checked")){
      $("#disciplina_Diagrama_div").removeAttr("hidden");
    }else{
      $("#disciplina_Diagrama_div").attr("hidden", "hidden");
    }
  });*/


  $("#projeto-cliente_id").change(function(ev){
    var id = $(this).val();    
    $.ajax({ 
      url: "index.php?r=projeto/preencheformcliente",
      data: {id: id},
      type: "POST",
      success: function(response){
       console.log(response);
       var resposta = $.parseJSON(response);
       $("#projeto-uf").val(resposta["uf"]);
       $("#projeto-municipio").val(resposta["cidade"]);
       $("#projeto-cnpj").val(resposta["cnpj"]);
       $("#projeto-codigo").val(resposta["codigo"]);
       $("#projeto-site").val(resposta["site"]);
     },
     error: function(){
      console.log("failure");
    }
  });

  $.ajax({ 
    url: "index.php?r=projeto/preenchepreenchecontatos",
    data: {id: id},
    type: "POST",
    success: function(response){
     var resposta = $.parseJSON(response);
     console.log(resposta);
     var myOptions = resposta;

     $("#projeto-contato_id").children("option:not(:first)").remove();
     var mySelect = $("#projeto-contato_id");
     $.each(myOptions, function(val, text) {
      mySelect.append(
      $("<option></option>").val(text["id"]).html(text["nome"])
      );
    });
  },
  error: function(){
    console.log("failure");
  }
});
});

$("#projeto-contato_id").change(function(ev){
  var id = $(this).val();    
  $.ajax({ 
    url: "index.php?r=projeto/preencheformcontato",
    data: {id: id},
    type: "POST",
    success: function(response){
     console.log(response);
     var resposta = $.parseJSON(response);
     $("#projeto-tratamento").val(resposta["tratamento"]);
     $("#projeto-setor").val(resposta["setor"]);
     $("#projeto-fone_contato").val(resposta["telefone"]);
     $("#projeto-celular").val(resposta["celular"]);
     $("#projeto-email").val(resposta["email"]);
     $("#projeto-contato").val(resposta["contato"]);
   },
   error: function(){
    console.log("failure");
  }
});
});

$(".ld-create").click(function(ev){              
    $.get("index.php?r=ldpreliminar/create",function(data){      
            $("#modal").modal("show").find("#modalContent").html(data);
        });
      
  });

  count_prio = 0;
  $(".nao-prioritarios").click(function(ev){              
    if(count_prio==0){
      $("#nao-prioritarios_div").removeAttr("hidden"); 
      $("#div_save_avulsas_btn").removeAttr("hidden"); 
         
      count_prio=1;
    }
    else{
      $("#nao-prioritarios_div").attr("hidden", "hidden");
      $("#div_save_avulsas_btn").attr("hidden", "hidden");
      count_prio=0;
    }
  });
  

/*$("#projeto-site").change(function(ev){
  var id = $(this).val();    
  $.ajax({ 
    url: "index.php?r=projeto/preencheformsite",
    data: {id: id},
    type: "POST",
    success: function(response){

     var resposta = $.parseJSON(response);
     console.log(resposta);
     var myOptions = resposta;

     $("#projeto-planta").children("option:not(:first)").remove();
     var mySelect = $("#projeto-planta");
     $.each(myOptions, function(val, text) {
      mySelect.append(
      $("<option></option>").val(text["id"]).html(text["nome"])
      );
    });
  },
  error: function(){
    console.log("failure");
  }
});
});*/

$("#projeto-qtd_dias").focusout(function() {
    dias = this.value;
    idExecutante = $("[name=\"ProjetoExecutante[0]\"]")[0].value;

    $.ajax({ 
    url: "index.php?r=projeto/preenchekm",
    data:{idExecutante: idExecutante},
    type: "POST",
    success: function(response){
     var resposta = $.parseJSON(response);     
     var qtd_km = resposta["qtd_km_dia"] * dias;
     var valor_km = qtd_km * resposta["vl_km"];


     
     $("#projeto-qtd_km").val(qtd_km);
     $("#projeto-vl_km").val(valor_km);
     $("#projeto-vl_km-disp").val(valor_km ).trigger("mask.maskMoney");

  },
  error: function(){
    console.log("failure");
  }
});

});

var hora_anterior = 0;
$(".horas").focusin(function() {
  hora_anterior = $(this).val();
  if(hora_anterior==""){
    hora_anterior = 0;
  }
});

$(".horas").focusout(function() {
  id = this.className.split("[")[1];
  id = id.split("]")[0];

  tipo = this.id.split("_")[1];
  tipo = tipo.split("-")[0];

  disc = this.id.split("-")[1];
  disc = disc.split("-")[0];

  horas_ee = isNaN(parseInt($("#horas_ee-"+disc+"-"+id).val())) ? 0 : parseInt($("#horas_ee-"+disc+"-"+id).val());
  horas_es = isNaN(parseInt($("#horas_es-"+disc+"-"+id).val())) ? 0 : parseInt($("#horas_es-"+disc+"-"+id).val());
  horas_ep = isNaN(parseInt($("#horas_ep-"+disc+"-"+id).val())) ? 0 : parseInt($("#horas_ep-"+disc+"-"+id).val());
  horas_ej = isNaN(parseInt($("#horas_ej-"+disc+"-"+id).val())) ? 0 : parseInt($("#horas_ej-"+disc+"-"+id).val());
  horas_tp = isNaN(parseInt($("#horas_tp-"+disc+"-"+id).val())) ? 0 : parseInt($("#horas_tp-"+disc+"-"+id).val());

  
  

  var totalHoras = horas_ee + horas_es + horas_ep + horas_ej + horas_tp;
  var totalHorasGeral = horas_ee + horas_es + horas_ep + horas_ej + horas_tp;
  
    var tds = document.getElementsByTagName("td");
    for (var i = 0; i<tds.length; i++) {      
      if (tds[i].className == "total-td["+id+"]") {
        // tds[i].html(totalHoras);
        tds[i].innerHTML = totalHoras;
      }
    }

    if($(this).val()!=""){
  
      var tds = document.getElementsByTagName("th");
      for (var i = 0; i<tds.length; i++) {      
        if(tipo=="ee"){
          if(disc==1){
            if (tds[i].className == "sub-a-tot-ee-entregavel") {  
              tds[i].innerHTML = parseInt($(this).val()) + parseInt(tds[i].innerHTML) + hora_anterior;
            }  
             if (tds[i].className == "sub-a-tot-ee") {  
              tds[i].innerHTML = parseInt($(this).val()) + parseInt(tds[i].innerHTML) - parseInt(hora_anterior);
            } 
            if (tds[i].className == "full-a-tot-ee") {  
              tds[i].innerHTML = parseInt($(this).val()) + parseInt(tds[i].innerHTML) - parseInt(hora_anterior);
            }  
          }
          if(disc==2){
            if (tds[i].className == "sub-p-tot-ee-entregavel") {  
              tds[i].innerHTML = parseInt($(this).val()) + parseInt(tds[i].innerHTML) - parseInt(hora_anterior);
            }  
             if (tds[i].className == "sub-p-tot-ee") {  
              tds[i].innerHTML = parseInt($(this).val()) + parseInt(tds[i].innerHTML) - parseInt(hora_anterior);
            } 
            if (tds[i].className == "full-p-tot-ee") {  
              tds[i].innerHTML = parseInt($(this).val()) + parseInt(tds[i].innerHTML) - parseInt(hora_anterior);
            }  
          }
          if(disc==3){
            if (tds[i].className == "sub-i-tot-ee-entregavel") {  
              tds[i].innerHTML = parseInt($(this).val()) + parseInt(tds[i].innerHTML) - parseInt(hora_anterior);
            }  
             if (tds[i].className == "sub-i-tot-ee") {  
              tds[i].innerHTML = parseInt($(this).val()) + parseInt(tds[i].innerHTML) - parseInt(hora_anterior);
            } 
            if (tds[i].className == "full-i-tot-ee") {  
              tds[i].innerHTML = parseInt($(this).val()) + parseInt(tds[i].innerHTML) - parseInt(hora_anterior);
            }  
          }
        }
        if(tipo=="es"){
          if(disc==1){
            if (tds[i].className == "sub-a-tot-es-entregavel") {  
              tds[i].innerHTML = parseInt($(this).val()) + parseInt(tds[i].innerHTML) - parseInt(hora_anterior);
            } 
             if (tds[i].className == "sub-a-tot-es") {  
              tds[i].innerHTML = parseInt($(this).val()) + parseInt(tds[i].innerHTML) - parseInt(hora_anterior);
            } 
            if (tds[i].className == "full-a-tot-es") {  
              tds[i].innerHTML = parseInt($(this).val()) + parseInt(tds[i].innerHTML) - parseInt(hora_anterior);
            }   
          }
          if(disc==2){
            if (tds[i].className == "sub-p-tot-es-entregavel") {  
              tds[i].innerHTML = parseInt($(this).val()) + parseInt(tds[i].innerHTML) - parseInt(hora_anterior);
            } 
             if (tds[i].className == "sub-p-tot-es") {  
              tds[i].innerHTML = parseInt($(this).val()) + parseInt(tds[i].innerHTML) - parseInt(hora_anterior);
            } 
            if (tds[i].className == "full-p-tot-es") {  
              tds[i].innerHTML = parseInt($(this).val()) + parseInt(tds[i].innerHTML) - parseInt(hora_anterior);
            }   
          }
          if(disc==3){
            if (tds[i].className == "sub-i-tot-es-entregavel") {  
              tds[i].innerHTML = parseInt($(this).val()) + parseInt(tds[i].innerHTML) - parseInt(hora_anterior);
            } 
             if (tds[i].className == "sub-i-tot-es") {  
              tds[i].innerHTML = parseInt($(this).val()) + parseInt(tds[i].innerHTML) - parseInt(hora_anterior);
            } 
            if (tds[i].className == "full-i-tot-es") {  
              tds[i].innerHTML = parseInt($(this).val()) + parseInt(tds[i].innerHTML) - parseInt(hora_anterior);
            }   
          }
        }
        if(tipo=="ep"){
          if(disc==1){
            if (tds[i].className == "sub-a-tot-ep-entregavel") {  
              tds[i].innerHTML = parseInt($(this).val()) + parseInt(tds[i].innerHTML) - parseInt(hora_anterior);
            } 
             if (tds[i].className == "sub-a-tot-ep") {  
              tds[i].innerHTML = parseInt($(this).val()) + parseInt(tds[i].innerHTML) - parseInt(hora_anterior);
            } 
            if (tds[i].className == "full-a-tot-ep") {  
              tds[i].innerHTML = parseInt($(this).val()) + parseInt(tds[i].innerHTML) - parseInt(hora_anterior);
            }   
          }
          if(disc==2){
            if (tds[i].className == "sub-p-tot-ep-entregavel") {  
              tds[i].innerHTML = parseInt($(this).val()) + parseInt(tds[i].innerHTML) - parseInt(hora_anterior);
            } 
             if (tds[i].className == "sub-p-tot-ep") {  
              tds[i].innerHTML = parseInt($(this).val()) + parseInt(tds[i].innerHTML) - parseInt(hora_anterior);
            } 
            if (tds[i].className == "full-p-tot-ep") {  
              tds[i].innerHTML = parseInt($(this).val()) + parseInt(tds[i].innerHTML) - parseInt(hora_anterior);
            }   
          }
          if(disc==3){
            if (tds[i].className == "sub-i-tot-ep-entregavel") {  
              tds[i].innerHTML = parseInt($(this).val()) + parseInt(tds[i].innerHTML) - parseInt(hora_anterior);
            } 
             if (tds[i].className == "sub-i-tot-ep") {  
              tds[i].innerHTML = parseInt($(this).val()) + parseInt(tds[i].innerHTML) - parseInt(hora_anterior);
            } 
            if (tds[i].className == "full-i-tot-ep") {  
              tds[i].innerHTML = parseInt($(this).val()) + parseInt(tds[i].innerHTML) - parseInt(hora_anterior);
            }   
          }
        }
        if(tipo=="ej"){
          if(disc==1){
            if (tds[i].className == "sub-a-tot-ej-entregavel") {  
              tds[i].innerHTML = parseInt($(this).val()) + parseInt(tds[i].innerHTML) - parseInt(hora_anterior);
            }  
            if (tds[i].className == "sub-a-tot-ej") {  
              tds[i].innerHTML = parseInt($(this).val()) + parseInt(tds[i].innerHTML) - parseInt(hora_anterior);
            } 
            if (tds[i].className == "full-a-tot-ej") {  
              tds[i].innerHTML = parseInt($(this).val()) + parseInt(tds[i].innerHTML) - parseInt(hora_anterior);
            }   
          }
          if(disc==2){
            if (tds[i].className == "sub-p-tot-ej-entregavel") {  
              tds[i].innerHTML = parseInt($(this).val()) + parseInt(tds[i].innerHTML) - parseInt(hora_anterior);
            }  
            if (tds[i].className == "sub-p-tot-ej") {  
              tds[i].innerHTML = parseInt($(this).val()) + parseInt(tds[i].innerHTML) - parseInt(hora_anterior);
            } 
            if (tds[i].className == "full-p-tot-ej") {  
              tds[i].innerHTML = parseInt($(this).val()) + parseInt(tds[i].innerHTML) - parseInt(hora_anterior);
            }   
          }
          if(disc==3){
            if (tds[i].className == "sub-i-tot-ej-entregavel") {  
              tds[i].innerHTML = parseInt($(this).val()) + parseInt(tds[i].innerHTML) - parseInt(hora_anterior);
            }  
            if (tds[i].className == "sub-i-tot-ej") {  
              tds[i].innerHTML = parseInt($(this).val()) + parseInt(tds[i].innerHTML) - parseInt(hora_anterior);
            } 
            if (tds[i].className == "full-i-tot-ej") {  
              tds[i].innerHTML = parseInt($(this).val()) + parseInt(tds[i].innerHTML) - parseInt(hora_anterior);
            }   
          }
        }
        if(tipo=="tp"){
          if(disc==1){
            if (tds[i].className == "sub-a-tot-tp-entregavel") {  
              tds[i].innerHTML = parseInt($(this).val()) + parseInt(tds[i].innerHTML) - parseInt(hora_anterior);
            }  
            if (tds[i].className == "sub-a-tot-tp") {  
              tds[i].innerHTML = parseInt($(this).val()) + parseInt(tds[i].innerHTML) - parseInt(hora_anterior);
            } 
            if (tds[i].className == "full-a-tot-tp") {  
              tds[i].innerHTML = parseInt($(this).val()) + parseInt(tds[i].innerHTML) - parseInt(hora_anterior);
            }  
          }
          if(disc==2){
            if (tds[i].className == "sub-p-tot-tp-entregavel") {  
              tds[i].innerHTML = parseInt($(this).val()) + parseInt(tds[i].innerHTML) - parseInt(hora_anterior);
            }  
            if (tds[i].className == "sub-p-tot-tp") {  
              tds[i].innerHTML = parseInt($(this).val()) + parseInt(tds[i].innerHTML) - parseInt(hora_anterior);
            } 
            if (tds[i].className == "full-p-tot-tp") {  
              tds[i].innerHTML = parseInt($(this).val()) + parseInt(tds[i].innerHTML) - parseInt(hora_anterior);
            }  
          }
          if(disc==3){
            if (tds[i].className == "sub-i-tot-tp-entregavel") {  
              tds[i].innerHTML = parseInt($(this).val()) + parseInt(tds[i].innerHTML) - parseInt(hora_anterior);
            }  
            if (tds[i].className == "sub-i-tot-tp") {  
              tds[i].innerHTML = parseInt($(this).val()) + parseInt(tds[i].innerHTML) - parseInt(hora_anterior);
            } 
            if (tds[i].className == "full-i-tot-tp") {  
              tds[i].innerHTML = parseInt($(this).val()) + parseInt(tds[i].innerHTML) - parseInt(hora_anterior);
            }  
          }
        }
      }
    }

});

$("#add-np").click(function(ev){   
  count_np = 0;
  $( ".np_autocomplete" ).each(function() {
    count_np++;
  });
  //div do autocomplete
  var div_np = $("#autocomplete_div_0");
  console.log(div_np);
  var clone = $(div_np).clone();
  clone.appendTo("#nao-prioritarios_div");
  clone.attr("id","autocomplete_div_"+count_np);
  $(clone.children()[0]).attr("id","autocomplete_"+count_np);
  $(clone.children()[0]).attr("name","np["+count_np+"]");
  $(clone.children()[0]).attr("value","");
  $(clone.children()[1]).attr("id","remove-np["+count_np+"]");

  autocomplete(document.getElementById("autocomplete_"+(count_np)), nprioritarios);

  $(".remove-np").click(function(ev){            
      var id = this.id;       
      id = id.split("[")[1].split("]")[0];
      $("#autocomplete_div_"+id).remove();
      count_np--;
      
  });

});

$(".remove-np").click(function(ev){            
      var id = this.id;       
      id = id.split("[")[1].split("]")[0];      
      $("#autocomplete_div_"+id).remove();
        count_np--;
      
  });

$( ".saveEscopo" ).click(function() {
  $( "#w1" ).submit();
});

 count=1;
$("#add-executante").click(function(ev){   
  count = 0;
  $( ".executante_dropdown" ).each(function() {
    count++;
  });
    
    var div = $(".drop-exec").children()[0];
    $(div).clone().appendTo(".drop-exec");
    $(div).attr("id","exec_div-"+count);
     
    $(div.children[0].children[1]).attr("name","ProjetoExecutante["+count+"]");
    $(div.children[1]).attr("id","remove-executante["+count+"]");
    console.log($(div.children[1]));
    count++;

    $(".remove-exec").click(function(ev){            
      var id = this.id;       
      id = id.split("[")[1].split("]")[0];      
      if(id!=0)
        $("#exec_div-"+id).remove();
    });
  });
  
$(".remove-exec").click(function(ev){            
      var id = this.id;       
      id = id.split("[")[1].split("]")[0];      
      if(id!=0)
        $("#exec_div-"+id).remove();
    });

 $(".icon-delete-atividade").click(function(e){
      id = this.id.split("_")[2];

      $.ajax({ 
          url: "index.php?r=projeto/deleteatividade",
          data: {id: id},
          type: "POST",
          success: function(response){
           if(response=="success"){
              console.log(response);
              $("#tabela-escopo")[0].deleteRow($("#delete_atividade_"+id).parent().parent()[0].rowIndex);
           }
           else{
              alert("algum erro ocorreu");
           }
           
         },
         error: function(){
          console.log("failure");
        }
      });
      
  });

  $("#btn_save_avulsas").click(function(e){
    var count_novas1 = 0;
    var count_novas2 = 0;
    $( ".np_autocomplete" ).each(function() {
      count_novas1++;
    });

    $( ".np_autocomplete" ).each(function() {
        var nome = this.value;
        var projeto_id = window.location.href.split("id=")[1];
        var disciplina = 0;
        if($("#aut_radio").prop("checked")){
          disciplina = 1;
        }
        else if($("#proc_radio").prop("checked")){
          disciplina = 2
        }
        else{
          disciplina = 3;
        }
        $.ajax({ 
          url: "index.php?r=projeto/addatividadeavulsa",
          data: {nome: nome, projeto_id: projeto_id, disciplina: disciplina},
          type: "POST",
          success: function(response){
           if(response=="success"){
             count_novas2++;
             if(count_novas2 == count_novas1){
              location.reload();
             } 
           }
           else{
              alert("algum erro ocorreu");
           }           
         },
         error: function(){
          console.log("failure");
          }
        });
    });

  });

');
?>
<?php
$this->registerJs("
    $('.poptp').change(function(){
      var linha = this.name.split(']')[0];
      linha = linha.split('[')[1];
      console.log(linha);
     $(\"#escopotp-\"+linha).val($(this).val());
      console.log(this.name);
    });

    $('.popej').change(function(){
      var linha = this.name.split(']')[0];
      linha = linha.split('[')[1];
      console.log(linha);
     $(\"#escopoej-\"+linha).val($(this).val());
      console.log(this.name);
    });

    $('.popep').change(function(){
      var linha = this.name.split(']')[0];
      linha = linha.split('[')[1];
      console.log(linha);
     $(\"#escopoep-\"+linha).val($(this).val());
      console.log(this.name);
    });

    $('.popes').change(function(){
      var linha = this.name.split(']')[0];
      linha = linha.split('[')[1];
      console.log(linha);
     $(\"#escopoes-\"+linha).val($(this).val());
      console.log(this.name);
    });

    $('.popee').change(function(){
      var linha = this.name.split(']')[0];
      linha = linha.split('[')[1];
      console.log(linha);
     $(\"#escopoee-\"+linha).val($(this).val());
      console.log(this.name);
    });

    $('#w0 td').click(function (e) {
        var id = $(this).closest('tr').attr('data-key');
        if(id != null){
          if(e.target == this)
              location.href = '" . Url::to(['projeto/update']) . "&id='+id;
        }
    });

    $('#w185 td').click(function (e) {
      var id = $(this).closest('tr').attr('data-key');
        if(id != null){
          if(e.target == this)
              $.get('index.php?r=ldpreliminar/update&id='+id,function(data){      
                  $('#modal').modal('show').find('#modalContent').html(data);
              });
        }
        
    });

");
?>
<!-- mask so funciona com isso -->
<?php $this->head() ?>

<?php 
Modal::begin(['header' => '<h4>LD-Preliminar</h4>', 'id' => 'modal', 'size' => 'modal-lg',]);
  echo '<div id="modalContent"></div>';
  Modal::end();
  ?>
<style>
  .form-group{
    margin-bottom: 0;

  }

  .help-block {    
     margin-bottom: 0; 
  }

  .control-label{
    font-size:10px;
  }

#tabela-escopo {
    font-family: "Trebuchet MS", Arial, Helvetica, sans-serif;
    border-collapse: collapse;
    width: 100%;
}

#tabela-escopo td, #tabela-escopo th {
    border: 1px solid #ddd;
    padding-left: 8px;
}

#tabela-escopo tr:nth-child(even){background-color: #f2f2f2;}

#tabela-escopo tr:hover {background-color: #ddd;}

#tabela-escopo th {
    padding-top: 12px;
    padding-bottom: 12px;
    text-align: left;
    background-color: #337ab7;
    color: white;
}

/*thead {   
    display:table;
   
    width:100%;
}
tbody {
    height:350px;
    overflow:auto;
    overflow-x:hidden;
    display:block;
    
}
*/

<?php 
$visible = 'hidden = "hidden"';
if(isset(\Yii::$app->authManager->getRolesByUser(Yii::$app->user->getId())['admin'])){ 
  $visible = '';
 } ?>

</style>
<div class="box box-primary">
    <div class="box-header with-border">
      <div class="row">   
          <div class="col-md-12"> 
          <div class="col-md-12" style="overflow: auto;overflow-y: hidden;Height:?">
          <div style="background-color: #337ab7;color:white;padding: 10px"><i class="fa fa-folder-open"></i> Projetos </div>
<div style="margin-bottom:1em;margin-top: 1em">
    <?= Html::a('Mostrar Todos', ['/projeto/create', 'pagination' => true], ['class'=>'btn btn-primary grid-button']) ?>
</div>
          <?= GridView::widget([
            'dataProvider' => $dataProvider,
            'filterModel' => $searchModel,
            // 'pjax' => true,        
            'options' => ['style' => 'font-size:12px;'], 
            'rowOptions' => function ($model, $key, $index, $grid) {
                    return [
                        'style' => "cursor: pointer",
                        
                    ];
                },               
            // 'hover' => true,            
            'columns' => [
            // ['class' => 'yii\grid\SerialColumn'],

            // 'id',
            [
              'class' => 'yii\grid\ActionColumn',
              'template' => '{delete}',    
              'contentOptions' => ['style' => 'width:2em;  min-width:2em;'],
            ],
            [
              'attribute' => 'status', 
              'format' => 'raw',
              'contentOptions' => ['style' => 'width:11em;  min-width:8em;'],
              'value' => function ($data) {

                $status = Yii::$app->db->createCommand('SELECT status, cor FROM projeto_status WHERE id='.$data->status)->queryOne();

                return '<span style="color:'.$status['cor'].' "><i class="fa fa-circle" aria-hidden="true"></i> '.$status['status'].'</span>';

              }, 
            ],
            [
            'attribute' => 'nome', 
            'contentOptions' => ['style' => 'width:16em;  min-width:16em;'],
            ],
            [
            'attribute' => 'site', 
            'contentOptions' => ['style' => 'width:8em;  min-width:8em;'],
            ],
            'descricao'
            /*[
              'attribute' => 'status',      
              'class' => 'kartik\grid\EditableColumn',        
              'format' => 'raw',
              'contentOptions' => ['style' => 'width:8em;  min-width:8em;'],
              'value' => function ($data) {

                $status = Yii::$app->db->createCommand('SELECT status, cor FROM projeto_status WHERE id='.$data->status)->queryOne();

                return '<span style="color:'.$status['cor'].' "><i class="fa fa-circle" aria-hidden="true"></i> '.$status['status'].'</span>';

              },

              'editableOptions' => [
              'inputType' => \kartik\editable\Editable::INPUT_DROPDOWN_LIST,
              'data' => $listStatus                
              ]
            ],*/
            /*[
            'attribute' => 'cliente_id',   
            'format' => 'raw',
            'contentOptions' => ['style' => 'width:10em;'],
            'value' => function ($data) {
               $nome = '';
              if(isset($data->cliente_id) && !empty($data->cliente_id))
                $nome = Yii::$app->db->createCommand('SELECT nome FROM cliente WHERE id='.$data->cliente_id)->queryScalar();


              return $nome;

            },
            ],
            [
            'attribute' => 'contato_id',              
            'format' => 'raw',
            'contentOptions' => ['style' => 'width:10em;  min-width:10em;'],
            'value' => function ($data) {
              $nome = '';
              if(isset($data->contato_id) && !empty($data->contato_id))
                $nome = Yii::$app->db->createCommand('SELECT nome FROM user WHERE id='.$data->contato_id)->queryScalar();                

              return $nome;

            },
            ],
            // 'descricao',
            'codigo',            
            'municipio',
            [
            'attribute' => 'uf',  
            'contentOptions' => ['style' => 'width:1em;  min-width:1em;'],
            ],*/
            
            // 'tratamento',
            // 'contato',
            // 'setor',
            /*[
            'attribute' => 'fone_contato',
            'format' => 'raw',
            'contentOptions' => ['style' => 'width:8em;  min-width:8em;'],
            ],
            [
            'attribute' => 'celular',
            'format' => 'raw',
            'contentOptions' => ['style' => 'width:8em;  min-width:8em;'],
            ],
            'email:email',*/
           
            ],
            ]); ?>
            
          
            </div>
            </div>

            </div>
            </div>
            
          </div>
<div class="projeto-form">

  <?php $form = ActiveForm::begin(); ?>
  <div class="form-group">
    <!-- <?//= Html::a('<i class="glyphicon glyphicon-plus"></i> Novo Projeto', ['create'], ['class' => 'btn btn-success']); ?> -->
    <?= Html::submitButton($model->isNewRecord ? 'Cadastrar' : 'Atualizar', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
  </div>
  <div class="box box-primary">
    <div class="box-header with-border">
    <!-- <div style="border:1px solid black;padding: 2px; margin-bottom: 1em"> -->
   <!--  <div class="col-md-4">
        <b> Executantes </b>
    </div>     -->
    <!-- <br> -->
       <div class="row">    
    <?php 

        $existeExecutante = '';

        $executantes = Yii::$app->db->createCommand('SELECT usuario_id, nome FROM executante JOIN user ON user.id=executante.usuario_id')->queryAll();

        $listData=ArrayHelper::map($executantes,'usuario_id','nome');
        
        /*if(!$model->isNewRecord){
          $existeExecutante = Yii::$app->db->createCommand('SELECT executante_id FROM projeto_executante WHERE executante_id='.$key.' AND projeto_id='.$model->id)->queryScalar();
        }*/
      ?>     
      
      <a style="margin-left: 1em" id="add-executante"> <i class="fa fa-plus-square-o fa-2x"></i></a>
      <div class="row drop-exec" style="margin-bottom: 1em;margin-left: 1em">
      <?php if($model->isNewRecord){ ?>
        <div class="col-md-3" id="exec_div-0">       
          <?= $form->field($model, 'executante_id')->dropDownList($listData, ['prompt'=>'Selecione um executante', 'name'=>'ProjetoExecutante[0]', 'class' => 'executante_dropdown form-control ']); ?>
          <a class="remove-exec" id="remove-executante[0]"> <i class="fa fa-ban" id="remove-executante[0]"></i></a>
        </div>
      <?php } else{ 
          $myExecutantes = Yii::$app->db->createCommand('SELECT executante_id FROM projeto_executante WHERE projeto_id='.$model->id)->queryAll();

          foreach ($myExecutantes as $key => $myExec){
        ?>
        <div class="col-md-3" id="exec_div-<?=$key?>">       
          <?= $form->field($model, 'executante_id')->dropDownList($listData, ['prompt'=>'Selecione um executante' , 'class' => 'executante_dropdown form-control', 'name'=>'ProjetoExecutante['.$key.']', 'options'=>array($myExec['executante_id']=>array('selected'=>'selected'))]); ?>
          <a class="remove-exec" id="remove-executante[<?=$key?>]"> <i class="fa fa-ban" id="remove-executante[<?=$key?>]"></i></a>
        </div>

      <?php }} ?>
       
      </div>
    
      </div>
      

      <!-- </div> -->
      <div class="row"> 
        <div class="col-md-2" style="width: 18em"> 
          <?= $form->field($model, 'tipo')->radioList(array('A'=>'AS Autorização de Serviço','P'=>'Proposta')); ?>        
        </div>
      
        <div class="col-md-2"> 
          <?= $form->field($model, 'nome')->textInput(['maxlength' => true]) ?>  
        </div>
        <div class="col-md-3">
          <?= $form->field($model, 'descricao')->textarea(['maxlength' => true]) ?>
        </div>

        <div class="col-md-4" id="disciplinas-div">
        
        <br>
        
        <fieldset>
           <legend>Disciplinas</legend>
          <?php 
            foreach ($listDisciplina as $key => $disciplina) { 
              if($disciplina=="Diagrama"){
                continue;
              }


          $existeDisciplina = '';
          if(!$model->isNewRecord)
            $existeDisciplina = Yii::$app->db->createCommand('SELECT escopopadrao_id FROM atividademodelo JOIN escopo ON escopo.atividademodelo_id=atividademodelo.id WHERE escopopadrao_id='.$key.' AND projeto_id='.$model->id)->queryScalar();
          ?>
          
          
           <label id="<?=$disciplina?>_checkbox"> <?=$disciplina?> </label>  
           
           <?php if(!$model->isNewRecord){ ?>
           <br>
            <div style="margin-left: 1em" id="disciplina_<?=$disciplina?>_div">
            <?php } else{?>
            <br>
            <div style="margin-left: 1em" id="disciplina_<?=$disciplina?>_div" >
            <?php } ?>
           <?php     

            foreach ($listEscopo as $key2 => $escopo) { 
              if($disciplina=="Processo" || $disciplina=="Instrumentação"){
                if($key2==4 || $key2==5){
                  continue;
                }
              }
              $existeEscopo = '';
              if(!$model->isNewRecord){
                $existeEscopo = Yii::$app->db->createCommand('SELECT escopopadrao_id FROM atividademodelo JOIN escopo ON escopo.atividademodelo_id=atividademodelo.id WHERE escopopadrao_id='.$key2.' AND projeto_id='.$model->id.' AND disciplina_id='.$key)->queryScalar();
              }

              ?>
              <?php if(!empty($existeEscopo)){ ?>
                <input type="checkbox" name="Escopos[<?=$disciplina."][".$key2?>]" value="<?= $key2?>" checked="1"><label><?= $escopo ?></label>
              <?php } else{ ?>
                <input type="checkbox" name="Escopos[<?=$disciplina."][".$key2?>]" value="<?= $key2?>"><label for=""><?= $escopo ?></label>
              <?php } ?>
            <?php } ?>
            </div>
        <?php } ?>
          </fieldset>
        </div>
        <div class="col-md-7" style="margin-top: -4em;" id="desc_resumida_div">
        <?= $form->field($model, 'desc_resumida')->textarea(['maxlength' => true]) ?>

      </div>
      </div>
      <div class="row">
        <div class="col-md-3">
          <?= $form->field($model, 'cliente_id')->dropDownList($listClientes,['prompt'=>'Selecione um Cliente']) ?>
        </div>

        <div class="col-md-1">
          <?= $form->field($model, 'codigo')->textInput(['maxlength' => true]) ?>   
        </div>

        <div class="col-md-1">
          <?= $form->field($model, 'site')->textInput(['maxlength' => true]) ?>
        </div>

        <div class="col-md-1">
          
          <?= $form->field($model, 'planta')->textInput(['maxlength' => true]) ?>
        </div>

        <div class="col-md-3" >
          <div class="col-md-4" >
            <?= $form->field($model, 'uf')->textInput(['maxlength' => true]) ?>
          </div>
          <div class="col-md-7">
            <?= $form->field($model, 'municipio')->textInput(['maxlength' => true]) ?>
          </div>
        </div>
        
        <div class="col-md-2">
          <?= $form->field($model, 'cnpj')->textInput(['maxlength' => true]) ?>
        </div>

      </div>
      <div class="row">
       <div class="col-md-3">
        <?php if($model->isNewRecord){ ?>
        <?= $form->field($model, 'contato_id')->dropDownList(['prompt'=>'Selecione um Contato']) ?>
        <?php } else{ ?>
        <?= $form->field($model, 'contato_id')->dropDownList($listContatos,['prompt'=>'Selecione um Contato']) ?>
        <?php } ?>
      </div>      

      <div class="col-md-4">
        <div class="col-md-4" style="padding-left: 0px;">
          <?= $form->field($model, 'tratamento')->textInput(['maxlength' => true]) ?>
        </div>
        <div class="col-md-4">
          <?= $form->field($model, 'contato')->textInput(['maxlength' => true]) ?>
        </div>
        <div class="col-md-4">
          <?= $form->field($model, 'setor')->textInput(['maxlength' => true]) ?>
        </div>
      </div>
      
      <div class="col-md-2">
        <?= $form->field($model, 'fone_contato')->textInput(['maxlength' => true]) ?>
      </div>
      <div class="col-md-2">
        <?= $form->field($model, 'celular')->textInput(['maxlength' => true]) ?>
      </div>
    </div>

    <div class="row">
     <div class="col-md-3">
      <?= $form->field($model, 'email')->textInput(['maxlength' => true]) ?>
    </div>
    <div class="col-md-1" >
      <div class="col-md-12" >
        <?= $form->field($model, 'documentos')->textInput(['maxlength' => true]) ?>
      </div>
    </div>
    <div class="col-md-3">
      <?= $form->field($model, 'proposta')->textInput(['maxlength' => true]) ?>        
    </div>
    <div class="col-md-1">
        <div class="col-md-12">
          <?= $form->field($model, 'rev_proposta')->textInput(['maxlength' => true]) ?>
        </div>
    </div>
    <div class="col-md-4">
      <div class="col-md-4">
          <?= $form->field($model, 'data_proposta')->widget(\yii\widgets\MaskedInput::className(), [
          'mask' => '99/99/9999',
          ])->textInput() ?>
        </div>
        <div class="col-md-3">
          <?= $form->field($model, 'qtd_dias')->textInput() ?>
        </div>
        <div class="col-md-3">
          <?= $form->field($model, 'qtd_km')->textInput() ?>
        </div>
        
      </div>
      <!-- <div class="col-md-1">
        <?//= $form->field($model, 'qtd_hh')->textInput(['style'=>'width:4em']) ?>
      </div>
      <div class="col-md-1">
        <?//= $form->field($model, 'vl_hh')->textInput(['maxlength' => true,'style'=>'width:6em']) ?>
      </div>
      <div class="col-md-2">
        <?//= $form->field($model, 'total_horas')->textInput(['maxlength' => true]) ?>
      </div>
    </div> -->
</div>
    
      <div class="row">
      
        <div class="col-md-7">
          <div class="col-md-3">
            <?= $form->field($model, 'vl_km')->textInput()->textInput(['maxlength' => true])->widget(MaskMoney::classname(), [
                'pluginOptions' => [
                    'prefix' => 'R$ ',
                    'thousands' => '.',
                    'decimal' => ',',
                    // 'suffix' => ' ¢',
                    'allowNegative' => false

                ]
            ]); ?>
          </div>
          <div class="col-md-3" style="padding-left: 0px;" <?=$visible?>>
              <?= $form->field($model, 'valor_proposta')->textInput(['maxlength' => true])->widget(MaskMoney::classname(), [
                'pluginOptions' => [
                    'prefix' => 'R$ ',
                    'thousands' => '.',
                    'decimal' => ',',
                    // 'suffix' => ' ¢',
                    'allowNegative' => false

                ]
            ]); ?>
          </div>
          <div class="col-md-3" <?=$visible?>>
              <?= $form->field($model, 'valor_consumido')->textInput(['maxlength' => true])->widget(MaskMoney::classname(), [
                'pluginOptions' => [
                    'prefix' => 'R$ ',
                    'thousands' => '.',
                    'decimal' => ',',
                    // 'suffix' => ' ¢',
                    'allowNegative' => false

                ]
            ]); ?>
          </div>
          <div class="col-md-3" <?=$visible?>>
            <?= $form->field($model, 'valor_saldo')->textInput(['maxlength' => true])->widget(MaskMoney::classname(), [
              'pluginOptions' => [
                  'prefix' => 'R$ ',
                  'thousands' => '.',
                  'decimal' => ',',
                  // 'suffix' => ' ¢',
                  'allowNegative' => false

              ]
            ]); ?>
          </div>          
      </div>    
        <div class="col-md-4">
            <?= $form->field($model, 'pendencia')->textarea(['maxlength' => true]) ?>
        </div>
      </div>

      <div class="row">
        <div class="col-md-2">
          <?= $form->field($model, 'status')->dropDownList($listStatus) ?>
        </div>
        <div class="col-md-2">
              <?= $form->field($model, 'data_entrega')->widget(\yii\widgets\MaskedInput::className(), [
                'mask' => '99/99/9999',
              ]) ?>
        </div>
        <div class="col-md-1">
          <?= $form->field($model, 'data_pendencia')->widget(\yii\widgets\MaskedInput::className(), [
          'mask' => '99/99/9999',])->textInput(['maxlength' => true]) ?>
        </div>
        <div class="col-md-1">
          <?= $form->field($model, 'total_horas')->textInput(['maxlength' => true]) ?>
        </div>
        <div class="col-md-2">
            <?= $form->field($model, 'contrato')->textInput(['maxlength' => true]) ?>
          </div>
          <div class="col-md-1" <?=$visible?>>
          <?php if($model->isNewRecord){ ?>
            <?= $form->field($model, 'perc_coord_adm')->textInput(['maxlength' => true, 'value'=>15]) ?>
            <?php } else{ ?>
            <?= $form->field($model, 'perc_coord_adm')->textInput(['maxlength' => true]) ?>
            <?php } ?>
          </div>
          <div class="col-md-2">
             <?= $form->field($model, 'as_aprovada')->checkbox(); ?>
          </div>
      </div>
         

    
   <!-- <div class="row">
     <div class="col-md-12"> 
       <h4>Fatura</h4>
          <div class="col-md-2">
            <?//= $form->field($model, 'cliente_fatura')->textInput(['maxlength' => true]) ?>
          </div>
          <div class="col-md-2">
            <?//= $form->field($model, 'site_fatura')->textInput(['maxlength' => true]) ?>
          </div>           

          <div class="col-md-2">
            <?//= $form->field($model, 'uf_fatura')->textInput(['maxlength' => true]) ?>
          </div>
          <div class="col-md-2">
            <?//= $form->field($model, 'municipio_fatura')->textInput(['maxlength' => true]) ?>
          </div>
          <div class="col-md-2">
            <?//= $form->field($model, 'cnpj_fatura')->textInput(['maxlength' => true]) ?>
          </div>
        
      </div></div> -->
      

      <?php 
          if(!$model->isNewRecord){
            $nao_prioritarios = Yii::$app->db->createCommand('SELECT atividademodelo.id id, escopo.id escopo_id, escopo.nome nome FROM atividademodelo  JOIN escopo ON escopo.atividademodelo_id=atividademodelo.id WHERE isPrioritaria=0 AND projeto_id='.$model->id.' ORDER BY escopo_id')->queryAll();
            $np_id_increment = 0;

      ?>
      
      
        <!-- <//?php foreach ($nao_prioritarios as $key => $np) { ?>--> 
          <?php 
          //if(!$model->isNewRecord){
            //$npExists = Yii::$app->db->createCommand('SELECT id FROM escopo WHERE atividademodelo_id='.$np['id'].' AND projeto_id='.$model->id)->queryScalar();
            //if(!empty($npExists)){ ?>    

            <!-- <div class="autocomplete col-md-3" style="width:300px;" id="autocomplete_div_<?= $np_id_increment ?>">
              <input id="autocomplete_<?= $np_id_increment ?>" type="text" name="np[<?= $np_id_increment ?>]" value="<//?= $np['nome'] ?>" class="np_autocomplete">
              <a class="remove-np" id="remove-np[<?= $np_id_increment ?>]"> <i class="fa fa-ban" ></i></a>
            </div>        -->        
           <?php // $np_id_increment++; }  ?>
           
           <?php //} ?>
            
         <?php //} ?>  

          <?php } ?>

        <?php  if(!$model->isNewRecord){ ?>
          <div ><p><a class="btn btn-success nao-prioritarios"><i class="fa fa-plus"></i> Atividades Avulsas</a></p></div>

          <div id="nao-prioritarios_div" style="margin-bottom: 1em" hidden>   
           <input type="radio" name="disc_radio" value="auto" checked="checked" id="aut_radio"> Automação
          <input type="radio" name="disc_radio" value="proc" id="proc_radio"> Processo
          <input type="radio" name="disc_radio" value="inst" id="inst_radio"> Instrumentação<br>
            <a style="margin-left: 1em" id="add-np"> <i class="fa fa-plus-square-o fa-2x"></i></a><br>          
          
          <div class="autocomplete col-md-3" style="width:300px;" id="autocomplete_div_0">
            <input class="np_autocomplete" id="autocomplete_0" type="text" name="np[0]" placeholder="Digite uma atividade">
            <a class="remove-np" id="remove-np[0]"> <i class="fa fa-ban" ></i></a>
          </div>         

         <?php  } ?>
        </div>
      </div>
      <div class="row" id="div_save_avulsas_btn" hidden>
          <div class="col-md-3"><p><a class="btn btn-primary" id="btn_save_avulsas"><i class="fa fa-save"></i> Salvar Avulsas</a></p></div>
        </div>

        <div class="barra-btn" >
          <?php if($model->isNewRecord){ ?>
            <?= Html::submitButton($model->isNewRecord ? '<i class="fa fa-floppy-o" aria-hidden="true"></i> Cadastrar' : 'Atualizar', ['class' => $model->isNewRecord ? 'btn btn-barra' : 'btn btn-barra']) ?>
          <?php } ?>
          <?php if(!$model->isNewRecord){ ?>
           <div> <label style="color: white"> <?= $model->nome ?> </label></div>
            <?= Html::submitButton('<i class="fa fa-floppy-o" aria-hidden="true"></i> Salvar', ['class' =>'btn btn-barra saveEscopo']) ?>
            <button type="button" class="btn btn-barra"  data-toggle="modal" data-target="#emailModal"><i class="fa fa-envelope-o" aria-hidden="true"></i> Email</button>
             <button type="button" class="btn btn-barra"  data-toggle="modal" data-target="#notaModal"><i class="fa fa-sticky-note-o" aria-hidden="true"></i> Notas Gerais</button>
            <?php if($model->tipo=="A"){ ?>
                <?= Html::a('<span class="btn-label"><i class="fa fa-file-pdf-o" aria-hidden="true"></i> Visualizar AS</span>', ['gerarrelatorio', 'id' => $model->id], ['class' => 'btn btn-barra', 'target'=>'_blank']) ?>
            <?php } ?>
            <?php if($model->tipo=="P"){ ?>
                <?= Html::a('<span class="btn-label"><i class="fa fa-file-pdf-o" aria-hidden="true"></i> Visualizar Proposta</span>', ['gerarrelatorio', 'id' => $model->id], ['class' => 'btn btn-barra', 'target'=>'_blank']) ?>
            <?php } ?>

          <?php } ?>
          
        </div>

      
      <?php if(!$model->isNewRecord){ ?>
            
                <div status="align:center" style="margin-top: 1em;">
                  <img src="resources/dist/img/status/status_<?= $model->status_geral - 1 ?>.png" style="width: 100%">
                </div>
      <?php }?>

      </div>
      </div>
      

      <?php
      if(!$model->isNewRecord){ ?>
     <!-- escopo -->
     <div class="box box-primary">
    <div class="box-header with-border">
     <div class="form-group">
       <?php $form2 = ActiveForm::begin(); ?>
       <!-- <//?= Html::submitButton('Salvar Escopo', ['class' =>'btn btn-primary saveEscopo']) ?> -->
     </div>


     <a href="#" id="escopos"></a>
    <?php 
    $descricao ='';
    $disciplina = '';
    $horas_tp = '';
    $exe_tp_id = '';
    $horas_ej = '';

    $header = ' 
    <table style="width:100%" id="tabela-escopo">
    <col width="600">
    <thead>
        <tr>
          <th width="5em" style="text-align:center;">Descrição</th>
          <th>Qtd</>
          <th>FOR</>
          <th colspan="10" style="text-align:center;">Horas</th>
        </tr>
        <tr>
          <th style="width:4.3em;"></th>
          <th style="width:4.3em;"></th>
          <th style="width:4.3em;"></th>
          <th style="width:4.3em;">EE</th>
          <th style="width:4.3em;">ES</th>
          <th style="width:4.3em;">EP</th>
          <th style="width:4.3em;">EJ</th>
          <th style="width:4.3em;">TP</th>
          <th style="width:4.3em;"></th>
          <th style="width:4.3em;"></th>
          <th style="width:4.3em;"></th>
          <th style="width:4.3em;"></th>
          <th style="width:4.3em;">Total</th>
        </tr>
        </thead> ';

    $bodyA = '';
    $bodyP = '';
    $bodyI = '';
    $bodyD = '';
    $exe_tp ='';
    $exe_ej ='';
    $exe_ep ='';
    $exe_es ='';
    $exe_ee ='';
    $separaEntregavel = 0;
    $total_a_EE = 0;
    $total_a_ES = 0;
    $total_a_EP = 0;
    $total_a_EJ = 0;
    $total_a_TP = 0;
    $total_p_EE = 0;
    $total_p_ES = 0;
    $total_p_EP = 0;
    $total_p_EJ = 0;
    $total_p_TP = 0;
    $total_i_EE = 0;
    $total_i_ES = 0;
    $total_i_EP = 0;
    $total_i_EJ = 0;
    $total_i_TP = 0;

    $esc_conceitualA = '';
    $esc_basicoA = '';
    $esc_detalhamentoA = '';
    $esc_configuracaoA = '';
    $esc_servicoA = '';

    $esc_conceitualP = '';
    $esc_basicoP = '';
    $esc_detalhamentoP = '';
    $esc_configuracaoP = '';

    $esc_conceitualI = '';
    $esc_basicoI = '';
    $esc_detalhamentoI = '';
    $esc_configuracaoI = '';

    foreach ($escopoArray as $key => $esc) { 
        $escopoModel =  Escopo::findOne($esc['id']);  
        $atividadeModel =  AtividadeModelo::findOne($escopoModel->atividademodelo_id);

        $executantes_tp = Yii::$app->db->createCommand('SELECT executante.usuario_id as exec_id, nome FROM executante JOIN executante_tipo ON executante_tipo.executante_id=executante.usuario_id JOIN tipo_executante ON executante_tipo.tipo_id=tipo_executante.id JOIN user ON user.id=executante.usuario_id JOIN projeto_executante ON projeto_executante.executante_id=executante.usuario_id JOIN executante_disciplina ON executante.usuario_id=executante_disciplina.executante_id WHERE disciplina_id = '.$atividadeModel->disciplina_id.' AND  tipo_executante.id =1 AND projeto_executante.projeto_id='.$model->id)->queryAll();
        $listExecutantes_tp = ArrayHelper::map($executantes_tp,'exec_id','nome'); 

        $executantes_ej = Yii::$app->db->createCommand('SELECT executante.usuario_id as exec_id, nome FROM executante JOIN executante_tipo ON executante_tipo.executante_id=executante.usuario_id JOIN tipo_executante ON executante_tipo.tipo_id=tipo_executante.id JOIN user ON user.id=executante.usuario_id JOIN projeto_executante ON projeto_executante.executante_id=executante.usuario_id JOIN executante_disciplina ON executante.usuario_id=executante_disciplina.executante_id  WHERE disciplina_id = '.$atividadeModel->disciplina_id.' AND  tipo_executante.id =2 AND projeto_executante.projeto_id='.$model->id)->queryAll();
        $listExecutantes_ej = ArrayHelper::map($executantes_ej,'exec_id','nome'); 

        $executantes_ep = Yii::$app->db->createCommand('SELECT executante.usuario_id as exec_id, nome FROM executante JOIN executante_tipo ON executante_tipo.executante_id=executante.usuario_id JOIN tipo_executante ON executante_tipo.tipo_id=tipo_executante.id JOIN user ON user.id=executante.usuario_id JOIN projeto_executante ON projeto_executante.executante_id=executante.usuario_id JOIN executante_disciplina ON executante.usuario_id=executante_disciplina.executante_id WHERE disciplina_id = '.$atividadeModel->disciplina_id.' AND tipo_executante.id =3 AND projeto_executante.projeto_id='.$model->id)->queryAll();
        $listExecutantes_ep = ArrayHelper::map($executantes_ep,'exec_id','nome');

        $executantes_es = Yii::$app->db->createCommand('SELECT executante.usuario_id as exec_id, nome FROM executante JOIN executante_tipo ON executante_tipo.executante_id=executante.usuario_id JOIN tipo_executante ON executante_tipo.tipo_id=tipo_executante.id JOIN user ON user.id=executante.usuario_id JOIN projeto_executante ON projeto_executante.executante_id=executante.usuario_id JOIN executante_disciplina ON executante.usuario_id=executante_disciplina.executante_id WHERE disciplina_id = '.$atividadeModel->disciplina_id.' AND tipo_executante.id =4 AND projeto_executante.projeto_id='.$model->id)->queryAll();
        $listExecutantes_es = ArrayHelper::map($executantes_es,'exec_id','nome'); 

        $executantes_ee = Yii::$app->db->createCommand('SELECT executante.usuario_id as exec_id, nome FROM executante JOIN executante_tipo ON executante_tipo.executante_id=executante.usuario_id JOIN tipo_executante ON executante_tipo.tipo_id=tipo_executante.id JOIN user ON user.id=executante.usuario_id JOIN projeto_executante ON projeto_executante.executante_id=executante.usuario_id JOIN executante_disciplina ON executante.usuario_id=executante_disciplina.executante_id WHERE disciplina_id = '.$atividadeModel->disciplina_id.' AND tipo_executante.id =5 AND projeto_executante.projeto_id='.$model->id)->queryAll();
        $listExecutantes_ee = ArrayHelper::map($executantes_ee,'exec_id','nome');

        
        if(!empty($exe_tp))
          $exe_tp = Yii::$app->db->createCommand('SELECT nome FROM executante WHERE id='.$esc['exe_tp_id'])->queryScalar();
        if(!empty($exe_ej))
          $exe_ej = Yii::$app->db->createCommand('SELECT nome FROM executante WHERE id='.$esc['exe_ej_id'])->queryScalar();
        if(!empty($exe_ep))
          $exe_ep = Yii::$app->db->createCommand('SELECT nome FROM executante WHERE id='.$esc['exe_ep_id'])->queryScalar();
        if(!empty($exe_es))
          $exe_es = Yii::$app->db->createCommand('SELECT nome FROM executante WHERE id='.$esc['exe_es_id'])->queryScalar();
        if(!empty($exe_ee))
          $exe_ee = Yii::$app->db->createCommand('SELECT nome FROM executante WHERE id='.$esc['exe_ee_id'])->queryScalar();
        
        $escopo_padrao_id = Yii::$app->db->createCommand('SELECT escopopadrao_id FROM atividademodelo WHERE id='.$esc['atividademodelo_id'])->queryScalar();

        $isEntregavel = Yii::$app->db->createCommand('SELECT isEntregavel FROM atividademodelo WHERE id='.$esc['atividademodelo_id'])->queryScalar();

        

       //==============================COLUNAS========================================================
      
      $col_delete = '<a class="icon-delete-atividade" id="delete_atividade_'.$esc["id"].'" style="margin-right: 1em;"><i class="fa fa-trash" aria-hidden="true"></i></a>'; 

      $descricao = '<tr><td style="font-size: 10px">'.$esc['descricao'].'</td>';

      $descricao_entregavel = '<tr id="row_'.$key.'"><td style="font-size: 10px">'.$col_delete.$esc['descricao'].'</td>'; 

      
      $disciplina = '<td style="font-size: 10px">'.Yii::$app->db->createCommand('SELECT disciplina.nome FROM disciplina JOIN atividademodelo ON atividademodelo.disciplina_id=disciplina.id WHERE atividademodelo.id='.$esc['atividademodelo_id'])->queryScalar().'</td>';

      $isEntregavel = Yii::$app->db->createCommand('SELECT isEntregavel FROM atividademodelo WHERE atividademodelo.id='.$esc['atividademodelo_id'])->queryScalar();
      $qtd='<td style="font-size: 10px; padding: 1px; background-color: #337ab7"></td>';
      $for='<td style="font-size: 10px; padding: 1px; background-color: #337ab7"></td>';

      $separador = 0;
      if($isEntregavel){
          if($separaEntregavel==0){
            $separador = 1;
            $separaEntregavel = 1;
            
          }
          
        $qtd = '<td style="font-size: 10px; padding: 1px;">'.$form2->field($escopoModel, 'qtd')->textInput(['style'=>' width:4em', 'name' => 'Escopo['.$esc["id"].'][qtd]', 'type' => 'number', 'min' => '0'])->label(false).'</td>'; 
        $for = '<td style="font-size: 10px; padding: 1px;">'.$form2->field($escopoModel, 'for')->textInput(['style'=>' width:4em', 'name' => 'Escopo['.$esc["id"].'][for]', 'min' => '0'])->label(false).'</td>';

        $separaEntregavel++;
      }
      
      $contentTP = '<p class="text-justify">' .$form2->field($escopoModel, 'exe_tp_id')->dropDownList($listExecutantes_tp,['name' => 'Escopo['.$esc["id"].'][exe_tp_id]', 'value'=>$esc['exe_tp_id'], 'class'=> 'form-control poptp'])->label(false) .'</p>';
      $popTP = PopoverX::widget([
        'placement' => PopoverX::ALIGN_TOP,
        'content' => $contentTP,
        'toggleButton' => ['label'=>'<i class="fa fa-caret-up" aria-hidden="true"></i>', 'class'=>'btn btn-default', 'style'=>'padding:1px'],
      ]);

      $contentEJ = '<p class="text-justify">' .$form2->field($escopoModel, 'exe_ej_id')->dropDownList($listExecutantes_ej,['name' => 'Escopo['.$esc["id"].'][exe_ej_id]', 'value'=>$esc['exe_ej_id'], 'class'=> 'form-control popej'])->label(false) .'</p>';
      $popEJ = PopoverX::widget([
        'placement' => PopoverX::ALIGN_TOP,
        'content' => $contentEJ,
        'toggleButton' => ['label'=>'<i class="fa fa-caret-up" aria-hidden="true"></i>', 'class'=>'btn btn-default', 'style'=>'padding:1px'],
      ]);

      $contentEP = '<p class="text-justify">' .$form2->field($escopoModel, 'exe_ep_id')->dropDownList($listExecutantes_ep,['name' => 'Escopo['.$esc["id"].'][exe_ep_id]', 'value'=>$esc['exe_ep_id'], 'class'=> 'form-control popep'])->label(false) .'</p>';
      $popEP = PopoverX::widget([
        'placement' => PopoverX::ALIGN_TOP,
        'content' => $contentEP,
        'toggleButton' => ['label'=>'<i class="fa fa-caret-up" aria-hidden="true"></i>', 'class'=>'btn btn-default', 'style'=>'padding:1px'],
      ]);

      $contentES = '<p class="text-justify">' .$form2->field($escopoModel, 'exe_es_id')->dropDownList($listExecutantes_es,['name' => 'Escopo['.$esc["id"].'][exe_es_id]', 'value'=>$esc['exe_es_id'], 'class'=> 'form-control popes'])->label(false) .'</p>';
      $popES = PopoverX::widget([
        'placement' => PopoverX::ALIGN_TOP,
        'content' => $contentES,
        'toggleButton' => ['label'=>'<i class="fa fa-caret-up" aria-hidden="true"></i>', 'class'=>'btn btn-default', 'style'=>'padding:1px'],
      ]);

      $contentEE = '<p class="text-justify">' .$form2->field($escopoModel, 'exe_ee_id')->dropDownList($listExecutantes_ee,['name' => 'Escopo['.$esc["id"].'][exe_ee_id]', 'value'=>$esc['exe_ee_id'], 'class'=> 'form-control popee'])->label(false) .'</p>';
      $popEE = PopoverX::widget([
        'placement' => PopoverX::ALIGN_TOP,
        'content' => $contentEE,
        'toggleButton' => ['label'=>'<i class="fa fa-caret-up" aria-hidden="true"></i>', 'class'=>'btn btn-default', 'style'=>'padding:1px'],
      ]);

       $disciplina_id = Yii::$app->db->createCommand('SELECT disciplina_id FROM atividademodelo WHERE id='.$esc['atividademodelo_id'])->queryScalar();

      $horas_tp = '<td style="font-size: 10px; padding: 1px;"><div class="row"><div class="col-md-8">'.$form2->field($escopoModel, 'horas_tp')->textInput(['style'=>'width:4em', 'name' => 'Escopo['.$esc["id"].'][horas_tp]', 'type' => 'number', 'class' => 'Escopo['.$esc["id"].'][horas_tp] horas', 'id' => 'horas_tp-'.$disciplina_id.'-'.$esc["id"], 'min' => '0'])->label(false).'</div><div class="col-md-4" style="padding-left:1px;margin-left:-1em">'.$popTP.'</div></td>'; 

      echo $form2->field($escopoModel, 'exe_tp_id')->textInput(['style'=>'width:4em;', 'name' => 'Escopo['.$esc["id"].'][exe_tp_id]','id' => 'escopotp-'.$esc["id"], 'type' => 'number','hidden'=>'hidden'])->label(false);

      $horas_ej = '<td style="font-size: 10px; padding: 1px;"><div class="row"><div class="col-md-8">'.$form2->field($escopoModel, 'horas_ej')->textInput(['style'=>'width:4em', 'name' =>'Escopo['.$esc["id"].'][horas_ej]', 'type' => 'number', 'class' => 'Escopo['.$esc["id"].'][horas_ej] horas', 'id' => 'horas_ej-'.$disciplina_id.'-'.$esc["id"], 'min' => '0'])->label(false).'</div><div class="col-md-4" style="padding-left:1px;margin-left:-1em">'.$popEJ.'</div></td>'; 

      echo $form2->field($escopoModel, 'exe_ej_id')->textInput(['style'=>'width:4em;', 'name' => 'Escopo['.$esc["id"].'][exe_ej_id]','id' => 'escopoej-'.$esc["id"], 'type' => 'number', 'hidden'=>'hidden'])->label(false);

      $horas_ep = '<td style="font-size: 10px; padding: 1px;"><div class="row"><div class="col-md-8">'.$form2->field($escopoModel, 'horas_ep')->textInput(['style'=>'width:4em', 'name' =>'Escopo['.$esc["id"].'][horas_ep]', 'type' => 'number', 'class' => 'Escopo['.$esc["id"].'][horas_ep] horas', 'id' => 'horas_ep-'.$disciplina_id.'-'.$esc["id"], 'min' => '0'])->label(false).'</div><div class="col-md-4" style="padding-left:1px;margin-left:-1em">'.$popEP.'</div></td>';

      echo $form2->field($escopoModel, 'exe_ep_id')->textInput(['style'=>'width:4em;', 'name' => 'Escopo['.$esc["id"].'][exe_ep_id]','id' => 'escopoep-'.$esc["id"], 'type' => 'number', 'hidden'=>'hidden'])->label(false);

      $horas_es = '<td style="font-size: 10px; padding: 1px;"><div class="row"><div class="col-md-8">'.$form2->field($escopoModel, 'horas_es')->textInput(['style'=>'width:4em', 'name' =>'Escopo['.$esc["id"].'][horas_es]', 'type' => 'number', 'class' => 'Escopo['.$esc["id"].'][horas_es] horas', 'id' => 'horas_es-'.$disciplina_id.'-'.$esc["id"], 'min' => '0'])->label(false).'</div><div class="col-md-4" style="padding-left:1px;margin-left:-1em">'.$popES.'</div></td>';

      echo $form2->field($escopoModel, 'exe_es_id')->textInput(['style'=>'width:4em;', 'name' => 'Escopo['.$esc["id"].'][exe_es_id]','id' => 'escopoes-'.$esc["id"], 'type' => 'number', 'hidden'=>'hidden'])->label(false);

      $horas_ee = '<td style="font-size: 10px; padding: 1px;"><div class="row"><div class="col-md-8">'.$form2->field($escopoModel, 'horas_ee')->textInput(['style'=>'width:4em', 'name' =>'Escopo['.$esc["id"].'][horas_ee]', 'type' => 'number', 'class' => 'Escopo['.$esc["id"].'][horas_ee] horas', 'id' => 'horas_ee-'.$disciplina_id.'-'.$esc["id"], 'min' => '0'])->label(false).'</div><div class="col-md-4" style="padding-left:1px;margin-left:-1em">'.$popEE.'</div></td>';

      echo $form2->field($escopoModel, 'exe_ee_id')->textInput(['style'=>'width:4em;', 'name' => 'Escopo['.$esc["id"].'][exe_ee_id]','id' => 'escopoee-'.$esc["id"], 'type' => 'number', 'hidden'=>'hidden'])->label(false);
      


      $total = $esc["horas_tp"]+$esc["horas_ej"]+$esc["horas_ep"]+$esc["horas_es"]+$esc["horas_ee"];
      $total = '<td class="total-td['.$esc['id'].']" style="font-size: 12px">'.$total.'</div>';

      
      if($disciplina_id == 1){    
        $CeA_EE =  $total_a_EE * 0.1;
        $CeA_ES =  $total_a_ES * 0.1;
        $CeA_EP =  $total_a_EP * 0.1;
        $CeA_EJ =  $total_a_EJ * 0.1;
        $CeA_TP =  $total_a_TP * 0.1;

        $total_a_EE = $esc["horas_ee"] + $total_a_EE;
        $total_a_ES = $esc["horas_es"] + $total_a_ES;
        $total_a_EP = $esc["horas_ep"] + $total_a_EP;
        $total_a_EJ = $esc["horas_ej"] + $total_a_EJ;
        $total_a_TP = $esc["horas_tp"] + $total_a_TP;


        
          if($descricao=='<tr><td style="font-size: 10px">Coordenação e Administração</td>'){
            // Yii::$app->db->createCommand('UPDATE escopo SET horas_ee='.$CeA_EE.', horas_es='.$CeA_ES.', horas_ep='.$CeA_EP.', horas_ej='.$CeA_EJ.', horas_tp='.$CeA_TP.' WHERE nome="Coordenação e Administração" AND atividademodelo_id=13 AND projeto_id='.$model->id)->execute();

            $bodyA .=  $descricao.' '.$qtd.' '.$for.' '.$horas_ee.' '.$horas_es.' '.$horas_ep.' '.$horas_ej.' '.$horas_tp.'<td></td><td></td><td></td><td></td> '.$total.'<tr><th>SUBTOTAL ATIVIDADES GERAIS DE PROJETO</th><th></th><th></th><th class="sub-a-tot-ee">'.$total_a_EE.'</th><th class="sub-a-tot-es">'.$total_a_ES.'</th><th class="sub-a-tot-ep">'.$total_a_EP.'</th><th class="sub-a-tot-ej">'.$total_a_EJ.'</th><th class="sub-a-tot-tp">'.$total_a_TP.'</th><th></th><th></th><th></th><th></th><th></th></tr><tr>
          <th style="width:4.3em;"></th><th style="width:4.3em;"></th><th style="width:4.3em;"></th><th style="width:4.3em;">EE</th><th style="width:4.3em;">ES</th><th style="width:4.3em;">EP</th><th style="width:4.3em;">EJ</th><th style="width:4.3em;">TP</th><th style="width:4.3em;"></th><th style="width:4.3em;"></th><th style="width:4.3em;"></th><th style="width:4.3em;"></th><th style="width:4.3em;">Total</th></tr>';  
          }
          else{
              if($isEntregavel){
                if($escopo_padrao_id==1){
                  $esc_conceitualA.= ' '.$descricao_entregavel.' '.$qtd.' '.$for.' '.$horas_ee.' '.$horas_es.' '.$horas_ep.' '.$horas_ej.' '.$horas_tp.'<td></td><td></td><td></td><td></td> '.$total;
                }
                else if($escopo_padrao_id==2){
                  $esc_basicoA.= ' '.$descricao_entregavel.' '.$qtd.' '.$for.' '.$horas_ee.' '.$horas_es.' '.$horas_ep.' '.$horas_ej.' '.$horas_tp.'<td></td><td></td><td></td><td></td> '.$total;
                }
                else if($escopo_padrao_id==3){
                 $esc_detalhamentoA.= ' '.$descricao_entregavel.' '.$qtd.' '.$for.' '.$horas_ee.' '.$horas_es.' '.$horas_ep.' '.$horas_ej.' '.$horas_tp.'<td></td><td></td><td></td><td></td> '.$total;
                }
                else if($escopo_padrao_id==4){
                  $esc_configuracaoA.= ' '.$descricao_entregavel.' '.$qtd.' '.$for.' '.$horas_ee.' '.$horas_es.' '.$horas_ep.' '.$horas_ej.' '.$horas_tp.'<td></td><td></td><td></td><td></td> '.$total;
                }
                else{
                  $esc_servicoA.= ' '.$descricao_entregavel.' '.$qtd.' '.$for.' '.$horas_ee.' '.$horas_es.' '.$horas_ep.' '.$horas_ej.' '.$horas_tp.'<td></td><td></td><td></td><td></td> '.$total;
                }
              }
              else{
                  $bodyA .= $descricao.' '.$qtd.' '.$for.' '.$horas_ee.' '.$horas_es.' '.$horas_ep.' '.$horas_ej.' '.$horas_tp.'<td></td><td></td><td></td><td></td> '.$total;
              }
              $bodyD .= $descricao.' '.$qtd.' '.$for.' '.$horas_ee.' '.$horas_es.' '.$horas_ep.' '.$horas_ej.' '.$horas_tp.'<td></td><td></td><td></td><td></td> '.$total;
          }
      }
      if($disciplina_id == 2){
        $total_p_EE = $esc["horas_ee"] + $total_p_EE;
        $total_p_ES = $esc["horas_es"] + $total_p_ES;
        $total_p_EP = $esc["horas_ep"] + $total_p_EP;
        $total_p_EJ = $esc["horas_ej"] + $total_p_EJ;
        $total_p_TP = $esc["horas_tp"] + $total_p_TP;
        
        if($descricao=='<tr><td style="font-size: 10px">Coordenação e Administração</td>'){
            $bodyP .=  $descricao.' '.$qtd.' '.$for.' '.$horas_ee.' '.$horas_es.' '.$horas_ep.' '.$horas_ej.' '.$horas_tp.'<td></td><td></td><td></td><td></td> '.$total.'<tr><th>SUBTOTAL ATIVIDADES GERAIS DE PROJETO</th><th></th><th></th><th class="sub-p-tot-ee">'.$total_p_EE.'</th><th class="sub-p-tot-es">'.$total_p_ES.'</th><th class="sub-p-tot-ep">'.$total_p_EP.'</th><th class="sub-p-tot-ej">'.$total_p_EJ.'</th><th class="sub-p-tot-tp">'.$total_p_TP.'</th><th></th><th></th><th></th><th></th><th></th></tr><tr>
          <th style="width:4.3em;"></th><th style="width:4.3em;"></th><th style="width:4.3em;"></th><th style="width:4.3em;">EE</th><th style="width:4.3em;">ES</th><th style="width:4.3em;">EP</th><th style="width:4.3em;">EJ</th><th style="width:4.3em;">TP</th><th style="width:4.3em;"></th><th style="width:4.3em;"></th><th style="width:4.3em;"></th><th style="width:4.3em;"></th><th style="width:4.3em;">Total</th></tr>';  
          }
          else{
            if($isEntregavel){
              if($escopo_padrao_id==1){
                $esc_conceitualP.= ' '.$descricao.' '.$qtd.' '.$for.' '.$horas_ee.' '.$horas_es.' '.$horas_ep.' '.$horas_ej.' '.$horas_tp.'<td></td><td></td><td></td><td></td> '.$total;
              }
             else if($escopo_padrao_id==2){
                $esc_basicoP.= ' '.$descricao.' '.$qtd.' '.$for.' '.$horas_ee.' '.$horas_es.' '.$horas_ep.' '.$horas_ej.' '.$horas_tp.'<td></td><td></td><td></td><td></td> '.$total;
              }
              else if($escopo_padrao_id==3){
               $esc_detalhamentoP.= ' '.$descricao.' '.$qtd.' '.$for.' '.$horas_ee.' '.$horas_es.' '.$horas_ep.' '.$horas_ej.' '.$horas_tp.'<td></td><td></td><td></td><td></td> '.$total;
              }
              else {
                $esc_configuracaoP.= ' '.$descricao.' '.$qtd.' '.$for.' '.$horas_ee.' '.$horas_es.' '.$horas_ep.' '.$horas_ej.' '.$horas_tp.'<td></td><td></td><td></td><td></td> '.$total;
              }
            }
              else{
                  $bodyP .= $descricao.' '.$qtd.' '.$for.' '.$horas_ee.' '.$horas_es.' '.$horas_ep.' '.$horas_ej.' '.$horas_tp.'<td></td><td></td><td></td><td></td> '.$total;
              }
              $bodyD .= $descricao.' '.$qtd.' '.$for.' '.$horas_ee.' '.$horas_es.' '.$horas_ep.' '.$horas_ej.' '.$horas_tp.'<td></td><td></td><td></td><td></td> '.$total;
          }
      }
      if($disciplina_id == 3){
        $total_i_EE = $esc["horas_ee"] + $total_i_EE;
        $total_i_ES = $esc["horas_es"] + $total_i_ES;
        $total_i_EP = $esc["horas_ep"] + $total_i_EP;
        $total_i_EJ = $esc["horas_ej"] + $total_i_EJ;
        $total_i_TP = $esc["horas_tp"] + $total_i_TP;
               
        if($descricao=='<tr><td style="font-size: 10px">Coordenação e Administração</td>'){
            $bodyI .=  $descricao.' '.$qtd.' '.$for.' '.$horas_ee.' '.$horas_es.' '.$horas_ep.' '.$horas_ej.' '.$horas_tp.'<td></td><td></td><td></td><td></td> '.$total.'<tr><th>SUBTOTAL ATIVIDADES GERAIS DE PROJETO</th><th></th><th></th><th class="sub-i-tot-ee">'.$total_i_EE.'</th><th class="sub-i-tot-es">'.$total_i_ES.'</th><th class="sub-i-tot-ep">'.$total_i_EP.'</th><th class="sub-i-tot-ej">'.$total_i_EJ.'</th><th class="sub-i-tot-tp">'.$total_i_TP.'</th><th></th><th></th><th></th><th></th><th></th></tr><tr>
          <th style="width:4.3em;"></th><th style="width:4.3em;"></th><th style="width:4.3em;"></th><th style="width:4.3em;">EE</th><th style="width:4.3em;">ES</th><th style="width:4.3em;">EP</th><th style="width:4.3em;">EJ</th><th style="width:4.3em;">TP</th><th style="width:4.3em;"></th><th style="width:4.3em;"></th><th style="width:4.3em;"></th><th style="width:4.3em;"></th><th style="width:4.3em;">Total</th></tr>';  
          }
          else{
              if($isEntregavel){
                if($escopo_padrao_id==1){
                  $esc_conceitualI.= ' '.$descricao.' '.$qtd.' '.$for.' '.$horas_ee.' '.$horas_es.' '.$horas_ep.' '.$horas_ej.' '.$horas_tp.'<td></td><td></td><td></td><td></td> '.$total;
                }
               else if($escopo_padrao_id==2){
                  $esc_basicoI.= ' '.$descricao.' '.$qtd.' '.$for.' '.$horas_ee.' '.$horas_es.' '.$horas_ep.' '.$horas_ej.' '.$horas_tp.'<td></td><td></td><td></td><td></td> '.$total;
                }
                else if($escopo_padrao_id==3){
                 $esc_detalhamentoI.= ' '.$descricao.' '.$qtd.' '.$for.' '.$horas_ee.' '.$horas_es.' '.$horas_ep.' '.$horas_ej.' '.$horas_tp.'<td></td><td></td><td></td><td></td> '.$total;
                }
                else{
                  $esc_configuracaoI.= ' '.$descricao.' '.$qtd.' '.$for.' '.$horas_ee.' '.$horas_es.' '.$horas_ep.' '.$horas_ej.' '.$horas_tp.'<td></td><td></td><td></td><td></td> '.$total;
                }
              }
              else{
                  $bodyI .= $descricao.' '.$qtd.' '.$for.' '.$horas_ee.' '.$horas_es.' '.$horas_ep.' '.$horas_ej.' '.$horas_tp.'<td></td><td></td><td></td><td></td> '.$total;
              }
              $bodyD .= $descricao.' '.$qtd.' '.$for.' '.$horas_ee.' '.$horas_es.' '.$horas_ep.' '.$horas_ej.' '.$horas_tp.'<td></td><td></td><td></td><td></td> '.$total;
          }
      } 
      if($disciplina_id == 4){
        $total_i_EE = $esc["horas_ee"] + $total_i_EE;
        $total_i_ES = $esc["horas_es"] + $total_i_ES;
        $total_i_EP = $esc["horas_ep"] + $total_i_EP;
        $total_i_EJ = $esc["horas_ej"] + $total_i_EJ;
        $total_i_TP = $esc["horas_tp"] + $total_i_TP;
               
        if($descricao=='<tr><td style="font-size: 10px">Coordenação e Administração</td>'){
            $bodyD .=  $descricao.' '.$qtd.' '.$for.' '.$horas_ee.' '.$horas_es.' '.$horas_ep.' '.$horas_ej.' '.$horas_tp.'<td></td><td></td><td></td><td></td> '.$total.'<tr><th>SUBTOTAL ATIVIDADES GERAIS DE PROJETO</th><th></th><th></th><th class="sub-d-tot-ee">'.$total_i_EE.'</th><th class="sub-d-tot-es">'.$total_i_ES.'</th><th class="sub-d-tot-ep">'.$total_i_EP.'</th><th class="sub-d-tot-ej">'.$total_i_EJ.'</th><th class="sub-d-tot-tp">'.$total_i_TP.'</th><th></th><th></th><th></th><th></th><th></th></tr><tr>
          <th style="width:4.3em;"></th><th style="width:4.3em;"></th><th style="width:4.3em;"></th><th style="width:4.3em;">EE</th><th style="width:4.3em;">ES</th><th style="width:4.3em;">EP</th><th style="width:4.3em;">EJ</th><th style="width:4.3em;">TP</th><th style="width:4.3em;"></th><th style="width:4.3em;"></th><th style="width:4.3em;"></th><th style="width:4.3em;"></th><th style="width:4.3em;">Total</th></tr>';  
          }
          else{
             $bodyD .=  $descricao.' '.$qtd.' '.$for.' '.$horas_ee.' '.$horas_es.' '.$horas_ep.' '.$horas_ej.' '.$horas_tp.'<td></td><td></td><td></td><td></td> '.$total;
          }
      } 

          
 } 
    if(!empty($esc_conceitualA)) 
      $bodyA .= '<tr style="background: aquamarine;"><td>CONCEITUAL</td><td></td><td></td><td>EE<td>ES</td><td>EP</td><td>EJ</td><td>TP</td><td></td><td></td><td></td><td></td><td>Total</td></tr>'.$esc_conceitualA;
    if(!empty($esc_basicoA)) 
      $bodyA .= '<tr style="background: aquamarine;"><td>BÁSICO</td><td></td><td></td><td>EE<td>ES</td><td>EP</td><td>EJ</td><td>TP</td><td></td><td></td><td></td><td></td><td>Total</td></tr>'.$esc_basicoA;
    if(!empty($esc_detalhamentoA))
      $bodyA .= '<tr style="background: aquamarine;"><td>DETALHAMENTO</td><td></td><td></td><td>EE<td>ES</td><td>EP</td><td>EJ</td><td>TP</td><td></td><td></td><td></td><td></td><td>Total</td></tr>'.$esc_detalhamentoA;
    if(!empty($esc_configuracaoA))
      $bodyA .= '<tr style="background: aquamarine;"><td>CONFIGURAÇÃO</td><td></td><td></td><td>EE<td>ES</td><td>EP</td><td>EJ</td><td>TP</td><td></td><td></td><td></td><td></td><td>Total</td></tr>'.$esc_configuracaoA;
    if(!empty($esc_servicoA))
      $bodyA .= '<tr style="background: aquamarine;"><td>SERVIÇO</td><td></td><td></td><td>EE<td>ES</td><td>EP</td><td>EJ</td><td>TP</td><td></td><td></td><td></td><td></td><td>Total</td></tr>'.$esc_servicoA;

    if(!empty($esc_conceitualP))
      $bodyP .= '<tr style="background: aquamarine;"><td>CONCEITUAL</td><td></td><td></td><td>EE<td>ES</td><td>EP</td><td>EJ</td><td>TP</td><td></td><td></td><td></td><td></td><td>Total</td></tr>'.$esc_conceitualP;
    if(!empty($esc_basicoP))
      $bodyP .= '<tr style="background: aquamarine;"><td>BÁSICO</td><td></td><td></td><td>EE<td>ES</td><td>EP</td><td>EJ</td><td>TP</td><td></td><td></td><td></td><td></td><td>Total</td></tr>'.$esc_basicoP;
    if(!empty($esc_detalhamentoP))
      $bodyP .= '<tr style="background: aquamarine;"><td>DETALHAMENTO</td><td></td><td></td><td>EE<td>ES</td><td>EP</td><td>EJ</td><td>TP</td><td></td><td></td><td></td><td></td><td>Total</td></tr>'.$esc_detalhamentoP;
    if(!empty($esc_configuracaoP))
      $bodyP .= '<tr style="background: aquamarine;"><td>CONFIGURAÇÃO</td><td></td><td></td><td>EE<td>ES</td><td>EP</td><td>EJ</td><td>TP</td><td></td><td></td><td></td><td></td><td>Total</td></tr>'.$esc_configuracaoP;

    if(!empty($esc_conceitualI))
      $bodyI .= '<tr style="background: aquamarine;"><td>CONCEITUAL</td><td></td><td></td><td>EE<td>ES</td><td>EP</td><td>EJ</td><td>TP</td><td></td><td></td><td></td><td></td><td>Total</td></tr>'.$esc_conceitualI;
    if(!empty($esc_basicoI))
      $bodyI .= '<tr style="background: aquamarine;"><td>BÁSICO</td><td></td><td></td><td>EE<td>ES</td><td>EP</td><td>EJ</td><td>TP</td><td></td><td></td><td></td><td></td><td>Total</td></tr>'.$esc_basicoI;
    if(!empty($esc_detalhamentoI))
      $bodyI .= '<tr style="background: aquamarine;"><td>DETALHAMENTO</td><td></td><td></td><td>EE<td>ES</td><td>EP</td><td>EJ</td><td>TP</td><td></td><td></td><td></td><td></td><td>Total</td></tr>'.$esc_detalhamentoI;
    if(!empty($esc_configuracaoI))
      $bodyI .= '<tr style="background: aquamarine;"><td>CONFIGURAÇÃO</td><td></td><td></td><td>EE<td>ES</td><td>EP</td><td>EJ</td><td>TP</td><td></td><td></td><td></td><td></td><td>Total</td></tr>'.$esc_configuracaoI;


    $entr = Yii::$app->db->createCommand('SELECT SUM(horas_ee) entr_horas_a_ee,SUM(horas_es) entr_horas_a_es,SUM(horas_ep) entr_horas_a_ep,SUM(horas_ej) entr_horas_a_ej,SUM(horas_tp) entr_horas_a_tp FROM escopo JOIN atividademodelo ON escopo.atividademodelo_id=atividademodelo.id WHERE projeto_id='.$model->id.' AND isEntregavel=1 AND disciplina_id=1')->queryOne();

    $full = Yii::$app->db->createCommand('SELECT SUM(horas_ee) entr_horas_a_ee,SUM(horas_es) entr_horas_a_es,SUM(horas_ep) entr_horas_a_ep,SUM(horas_ej) entr_horas_a_ej,SUM(horas_tp) entr_horas_a_tp FROM escopo JOIN atividademodelo ON escopo.atividademodelo_id=atividademodelo.id WHERE disciplina_id=1 AND projeto_id='.$model->id)->queryOne();

    if(!isset($full['entr_horas_a_ee'])) $full['entr_horas_a_ee'] = 0; 
    if(!isset($full['entr_horas_a_es'])) $full['entr_horas_a_es'] = 0; 
    if(!isset($full['entr_horas_a_ep'])) $full['entr_horas_a_ep'] = 0; 
    if(!isset($full['entr_horas_a_ej'])) $full['entr_horas_a_ej'] = 0; 
    if(!isset($full['entr_horas_a_tp'])) $full['entr_horas_a_tp'] = 0; 

    if(!isset($entr['entr_horas_a_ee'])) $entr['entr_horas_a_ee'] = 0;
    if(!isset($entr['entr_horas_a_es'])) $entr['entr_horas_a_es'] = 0;
    if(!isset($entr['entr_horas_a_ep'])) $entr['entr_horas_a_ep'] = 0;
    if(!isset($entr['entr_horas_a_ej'])) $entr['entr_horas_a_ej'] = 0;
    if(!isset($entr['entr_horas_a_tp'])) $entr['entr_horas_a_tp'] = 0;
    
    $bodyAOld = $bodyA;
    $bodyA = $bodyA.'<tr><th>SUBTOTAL ATIVIDADES GERAIS DE PROJETO</th><th></th><th></th><th class="sub-a-tot-ee-entregavel">'.$entr['entr_horas_a_ee'].'</th><th class="sub-a-tot-es-entregavel">'.$entr['entr_horas_a_es'].'</th><th class="sub-a-tot-ep-entregavel">'.$entr['entr_horas_a_ep'].'</th><th class="sub-a-tot-ej-entregavel">'.$entr['entr_horas_a_ej'].'</th><th class="sub-a-tot-tp-entregavel">'.$entr['entr_horas_a_tp'].'</th><th></th><th></th><th></th><th></th><th></th></tr>
    <tr><th>HH TOTAL DE ENGENHARIA</th><th></th><th></th><th class="full-a-tot-ee">'.$full['entr_horas_a_ee'].'</th><th class="full-a-tot-es">'.$full['entr_horas_a_es'].'</th><th class="full-a-tot-ep">'.$full['entr_horas_a_ep'].'</th><th class="full-a-tot-ej">'.$full['entr_horas_a_ej'].'</th><th class="full-a-tot-tp">'.$full['entr_horas_a_tp'].'</th><th></th><th></th><th></th><th></th><th></th></tr>';

    $entr = Yii::$app->db->createCommand('SELECT SUM(horas_ee) entr_horas_p_ee,SUM(horas_es) entr_horas_p_es,SUM(horas_ep) entr_horas_p_ep,SUM(horas_ej) entr_horas_p_ej,SUM(horas_tp) entr_horas_p_tp FROM escopo JOIN atividademodelo ON escopo.atividademodelo_id=atividademodelo.id WHERE projeto_id='.$model->id.' AND isEntregavel=1 AND disciplina_id=2')->queryOne();

    $full = Yii::$app->db->createCommand('SELECT SUM(horas_ee) entr_horas_p_ee,SUM(horas_es) entr_horas_p_es,SUM(horas_ep) entr_horas_p_ep,SUM(horas_ej) entr_horas_p_ej,SUM(horas_tp) entr_horas_p_tp FROM escopo JOIN atividademodelo ON escopo.atividademodelo_id=atividademodelo.id WHERE disciplina_id=2 AND projeto_id='.$model->id)->queryOne();

    if(!isset($full['entr_horas_p_ee'])) $full['entr_horas_p_ee'] = 0;
    if(!isset($full['entr_horas_p_es'])) $full['entr_horas_p_es'] = 0;
    if(!isset($full['entr_horas_p_ep'])) $full['entr_horas_p_ep'] = 0;
    if(!isset($full['entr_horas_p_ej'])) $full['entr_horas_p_ej'] = 0;
    if(!isset($full['entr_horas_p_tp'])) $full['entr_horas_p_tp'] = 0;

    if(!isset($entr['entr_horas_p_ee'])) $entr['entr_horas_p_ee'] = 0;
    if(!isset($entr['entr_horas_p_es'])) $entr['entr_horas_p_es'] = 0;
    if(!isset($entr['entr_horas_p_ep'])) $entr['entr_horas_p_ep'] = 0;
    if(!isset($entr['entr_horas_p_ej'])) $entr['entr_horas_p_ej'] = 0;
    if(!isset($entr['entr_horas_p_tp'])) $entr['entr_horas_p_tp'] = 0;

    $bodyPOld = $bodyP;
    $bodyP = $bodyP.'<tr><th>SUBTOTAL ATIVIDADES GERAIS DE PROJETO</th><th></th><th></th><th>'.$entr['entr_horas_p_ee'].'</th><th>'.$entr['entr_horas_p_es'].'</th><th>'.$entr['entr_horas_p_ep'].'</th><th>'.$entr['entr_horas_p_ej'].'</th><th>'.$entr['entr_horas_p_tp'].'</th><th></th><th></th><th></th><th></th><th></th></tr>
    <tr><th>HH TOTAL DE ENGENHARIA</th><th></th><th></th><th class="full-p-tot-ee">'.$full['entr_horas_p_ee'].'</th><th class="full-p-tot-es">'.$full['entr_horas_p_es'].'</th><th class="full-p-tot-ep">'.$full['entr_horas_p_ep'].'</th><th class="full-p-tot-ej">'.$full['entr_horas_p_ej'].'</th><th class="full-p-tot-tp">'.$full['entr_horas_p_tp'].'</th><th></th><th></th><th></th><th></th><th></th></tr>';

    $entr = Yii::$app->db->createCommand('SELECT SUM(horas_ee) entr_horas_i_ee,SUM(horas_es) entr_horas_i_es,SUM(horas_ep) entr_horas_i_ep,SUM(horas_ej) entr_horas_i_ej,SUM(horas_tp) entr_horas_i_tp FROM escopo JOIN atividademodelo ON escopo.atividademodelo_id=atividademodelo.id WHERE projeto_id='.$model->id.' AND isEntregavel=1 AND disciplina_id=3')->queryOne();

    $full = Yii::$app->db->createCommand('SELECT SUM(horas_ee) entr_horas_i_ee,SUM(horas_es) entr_horas_i_es,SUM(horas_ep) entr_horas_i_ep,SUM(horas_ej) entr_horas_i_ej,SUM(horas_tp) entr_horas_i_tp FROM escopo JOIN atividademodelo ON escopo.atividademodelo_id=atividademodelo.id WHERE disciplina_id=3 AND projeto_id='.$model->id)->queryOne();

    if(!isset($full['entr_horas_i_ee'])) $full['entr_horas_i_ee'] = 0;
    if(!isset($full['entr_horas_i_es'])) $full['entr_horas_i_es'] = 0;
    if(!isset($full['entr_horas_i_ep'])) $full['entr_horas_i_ep'] = 0;
    if(!isset($full['entr_horas_i_ej'])) $full['entr_horas_i_ej'] = 0;
    if(!isset($full['entr_horas_i_tp'])) $full['entr_horas_i_tp'] = 0;

    if(!isset($entr['entr_horas_i_ee'])) $entr['entr_horas_i_ee'] = 0;
    if(!isset($entr['entr_horas_i_es'])) $entr['entr_horas_i_es'] = 0;
    if(!isset($entr['entr_horas_i_ep'])) $entr['entr_horas_i_ep'] = 0;
    if(!isset($entr['entr_horas_i_ej'])) $entr['entr_horas_i_ej'] = 0;
    if(!isset($entr['entr_horas_i_tp'])) $entr['entr_horas_i_tp'] = 0;

    $bodyIOld = $bodyI;
    $bodyI = $bodyI.'<tr><th>SUBTOTAL ATIVIDADES GERAIS DE PROJETO</th><th></th><th></th><th>'.$entr['entr_horas_i_ee'].'</th><th>'.$entr['entr_horas_i_es'].'</th><th>'.$entr['entr_horas_i_ep'].'</th><th>'.$entr['entr_horas_i_ej'].'</th><th>'.$entr['entr_horas_i_tp'].'</th><th></th><th></th><th></th><th></th><th></th></tr>
    <tr><th>HH TOTAL DE ENGENHARIA</th><th></th><th></th><th class="full-i-tot-ee">'.$full['entr_horas_i_ee'].'</th><th class="full-i-tot-es">'.$full['entr_horas_i_es'].'</th><th class="full-i-tot-ep">'.$full['entr_horas_i_ep'].'</th><th class="full-i-tot-ej">'.$full['entr_horas_i_ej'].'</th><th class="full-i-tot-tp">'.$full['entr_horas_i_tp'].'</th><th></th><th></th><th></th><th></th><th></th></tr>';

    $entr = Yii::$app->db->createCommand('SELECT SUM(horas_ee) entr_horas_d_ee,SUM(horas_es) entr_horas_d_es,SUM(horas_ep) entr_horas_d_ep,SUM(horas_ej) entr_horas_d_ej,SUM(horas_tp) entr_horas_d_tp FROM escopo JOIN atividademodelo ON escopo.atividademodelo_id=atividademodelo.id WHERE projeto_id='.$model->id.' AND isEntregavel=1 AND disciplina_id=3')->queryOne();

    $full = Yii::$app->db->createCommand('SELECT SUM(horas_ee) entr_horas_d_ee,SUM(horas_es) entr_horas_d_es,SUM(horas_ep) entr_horas_d_ep,SUM(horas_ej) entr_horas_d_ej,SUM(horas_tp) entr_horas_d_tp FROM escopo JOIN atividademodelo ON escopo.atividademodelo_id=atividademodelo.id WHERE disciplina_id=3 AND projeto_id='.$model->id)->queryOne();

    if(!isset($full['entr_horas_d_ee'])) $full['entr_horas_d_ee'] = 0;
    if(!isset($full['entr_horas_d_es'])) $full['entr_horas_d_es'] = 0;
    if(!isset($full['entr_horas_d_ep'])) $full['entr_horas_d_ep'] = 0;
    if(!isset($full['entr_horas_d_ej'])) $full['entr_horas_d_ej'] = 0;
    if(!isset($full['entr_horas_d_tp'])) $full['entr_horas_d_tp'] = 0;

    if(!isset($entr['entr_horas_d_ee'])) $entr['entr_horas_d_ee'] = 0;
    if(!isset($entr['entr_horas_d_es'])) $entr['entr_horas_d_es'] = 0;
    if(!isset($entr['entr_horas_d_ep'])) $entr['entr_horas_d_ep'] = 0;
    if(!isset($entr['entr_horas_d_ej'])) $entr['entr_horas_d_ej'] = 0;
    if(!isset($entr['entr_horas_d_tp'])) $entr['entr_horas_d_tp'] = 0;

    $bodyDOld = $bodyD;
    

    $automacao = '<div style="height: 50em; overflow-y: scroll;">'.$header.''.$bodyA.'</table></div>';
    $processo = '<div style="height: 50em; overflow-y: scroll;">'.$header.''.$bodyP.'</table></div>';
    $instrumentacao = '<div style="height: 50em; overflow-y: scroll;">'.$header.''.$bodyI.'</table></div>';
    $atividade = '<div style="height: 50em; overflow-y: scroll;">'.$header.''.$bodyD.'</table></div>';
    // $diagrama = '<div style="height: 50em; overflow-y: scroll;">'.$header.''.$bodyD.'</table></div>';
    $as = '<div>'. $form2->field($model, "nota_geral")->textArea() .'</div>';
    $resumo = '<div>'. $form2->field($model, "resumo_escopo")->textArea() .'</div>
                <div>'. $form2->field($model, "resumo_exclusoes")->textArea() .'</div>
                <div>'. $form2->field($model, "resumo_premissas")->textArea() .'</div>
                <div>'. $form2->field($model, "resumo_restricoes")->textArea() .'</div>
                <div>'. $form2->field($model, "resumo_normas")->textArea() .'</div>
                <div>'. $form2->field($model, "resumo_documentos")->textArea() .'</div>
                <div>'. $form2->field($model, "resumo_itens")->textArea() .'</div>
                <div>'. $form2->field($model, "resumo_prazo")->textArea() .'</div>
                <div>'. $form2->field($model, "resumo_observacoes")->textArea() .'</div>
    ';

    $ld_preliminar = '<p>'.
        '<a class="btn btn-success ld-create">Novo</a>'.
    '</p>'.GridView::widget([
        'dataProvider' => $ldPreliminarDataProvider,
            'filterModel' => $ldPreliminarSearchModel,
            // 'pjax' => true,        
            'options' => ['style' => 'font-size:12px;'],                
            // 'hover' => true,            
        'columns' => [
            [
              'class' => 'yii\grid\ActionColumn',
              'template' => '{delete}',    
              'contentOptions' => ['style' => 'width:5em;  min-width:5em;'],
              'buttons' => [
                'delete' => function ($url, $model) {
                  $url = str_replace('projeto','ldpreliminar', $url);
                    return Html::a('<span class="glyphicon glyphicon-trash"></span>', $url, [
                            'title' => Yii::t('app', 'lead-delete'),
                    ]);
                },
            ]
            ],
            
            'id',
            'hcn',
            'cliente',
            'titulo',
        ],
    ]);

   
    if(!empty($bodyAOld))
      $visibleA = true;
    else
      $visibleA = false;
    if(!empty($bodyPOld))
      $visibleP = true;
    else
      $visibleP = false;
    if(!empty($bodyIOld))
      $visibleI = true;
    else
      $visibleI = false;
    if(!empty($bodyDOld))
      $visibleD = true;
    else
      $visibleD = false;


$items = [
[
    'label'=>'Automação',
    'content'=>$automacao,
    'active'=>true,
    'visible' => $model->tipo=="A" ? $visibleA : false
],
[
    'label'=>'Processo',
    'content'=>$processo,   
    'visible' => $model->tipo=="A" ? $visibleP : false   
],
[
    'label'=>'Instrumentação',
    'content'=>$instrumentacao, 
    'visible' => $model->tipo=="A" ? $visibleI : false
],
[
    'label'=>'Atividade',
    'content'=> $atividade, 
    'visible' => $model->tipo=="P" ? true : false      
],
/*[
    'label'=>'AS',
    'content'=>$as,        
],*/
[
    'label'=>'Resumo',
    'content'=>$resumo,        
],
[
    'label'=>'LD-Preliminar',
    'content'=>$ld_preliminar,        
],
/*[
    'label'=>'<i class="glyphicon glyphicon-king"></i> Disabled',
    'headerOptions' => ['class'=>'disabled']
],*/
]; 
?>
<div class="col-md-12">      

<?php
echo TabsX::widget([
    'items'=>$items,
    'position'=>TabsX::POS_ABOVE,
    'encodeLabels'=>false
]);
 ?>    
 
  </div>
  </div>
</div>
<?php ActiveForm::end(); ?>
      <?php } ?>
    <?php ActiveForm::end(); ?>

   </div>
  </div>
</div>
</div>
    
<?php if(!$model->isNewRecord){ ?>

<!-- Modal -->
<div id="emailModal" class="modal fade" role="dialog" style="z-index: 999999999">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
          <div  class="col-md-12" align="center">  
            <img style="z-index: 999999999" src="resources/dist/img/loading.gif" type="hidden" name="loading" id="loading" value="" width="64px" hidden/>        
          </div> 
        <h4 class="modal-title">Email</h4>
      </div>

      <div class="modal-body">

        <label>Destinatário(s)</label>
        <br>
        <input type="text" id="remetente" name="remetente" class="form-control" value="helder010161@uol.com.br">
        <br>

        <label>Assunto</label>
        <br>
        <input type="text" id="assunto" name="assunto" class="form-control" value="HCN - AS: <?= $model->nome ?>">
        <br>
       
        <label>Corpo do Email</label>
        <br>
        <textarea rows="15" cols="100" id="corpoEmail" name="corpoEmail" class="form-control">        
<?= Yii::$app->db->createCommand('SELECT email FROM user WHERE id='.$model->contato_id)->queryScalar() ?>  
Bom dia, <?= $model->contato ?>!

Segue nossa AS para:
<?= $model->desc_resumida ?> para o <?= $model->nome ?>.
 

Estamos à disposição para mais esclarecimentos.


Atenciosamente,

Hélder Câmara
HCN Automação
71 98867-3010 (Vivo)
71 99295-5214 (Tim)
        </textarea>
        
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal" id="close_modal">Fechar</button>
        <button type="button" class="btn btn-success" id="enviarEmail" >Enviar</button>
      </div>
    </div>

  </div>
</div>
  <?php } ?>
<div id="notaModal" class="modal fade" role="dialog" style="z-index: 999999999">
  <div class="modal-dialog" style="width:80%">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
          <div  class="col-md-12" align="center">  
            <img style="z-index: 999999999" src="resources/dist/img/loading.gif" type="hidden" name="loading" id="loading_nota" value="" width="64px" hidden/>        
          </div> 
        <h4 class="modal-title">Notas Gerais</h4>
      </div>
      <div class="modal-body">             
        <?= $form->field($model, 'nota_geral')->textarea(["rows"=>"25"])->label(false) ?>      
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal" id="close_modal_nota">Fechar</button>
        <button type="button" class="btn btn-success" id="salvarNota" >Salvar</button>
      </div>
    </div>

  </div>
</div>


<?php 
  
 $nPrioritarios = '';
 $nao_prioritarios_array = Yii::$app->db->createCommand('SELECT * FROM atividademodelo')->queryAll();

foreach ($nao_prioritarios_array as $key => $np) {  
    $nPrioritarios .= '"'.$np['nome'].'", ';
} 


?>





<script>
function autocomplete(inp, arr) {
  /*the autocomplete function takes two arguments,
  the text field element and an array of possible autocompleted values:*/
  var currentFocus;

  count_np = 0;
  $( ".np_autocomplete" ).each(function() {
    count_np++;
  });
  /*execute a function when someone writes in the text field:*/
  inp.addEventListener("input", function(e) {
      var a, b, i, val = this.value;
      /*close any already open lists of autocompleted values*/
      closeAllLists();
      if (!val) { return false;}
      currentFocus = -1;
      /*create a DIV element that will contain the items (values):*/
      a = document.createElement("DIV");
      a.setAttribute("id", this.id + "autocomplete-list");
      a.setAttribute("class", "autocomplete-items");
      /*append the DIV element as a child of the autocomplete container:*/
      this.parentNode.appendChild(a);
      /*for each item in the array...*/
      for (i = 0; i < arr.length; i++) {
        /*check if the item starts with the same letters as the text field value:*/
        if (arr[i].substr(0, val.length).toUpperCase() == val.toUpperCase()) {
          /*create a DIV element for each matching element:*/
          b = document.createElement("DIV");
          /*make the matching letters bold:*/
          b.innerHTML = "<strong>" + arr[i].substr(0, val.length) + "</strong>";
          b.innerHTML += arr[i].substr(val.length);
          /*insert a input field that will hold the current array item's value:*/
          b.innerHTML += "<input type='hidden' value='" + arr[i] + "'>";
          /*execute a function when someone clicks on the item value (DIV element):*/
          b.addEventListener("click", function(e) {
              /*insert the value for the autocomplete text field:*/
              inp.value = this.getElementsByTagName("input")[0].value;
              /*close the list of autocompleted values,
              (or any other open lists of autocompleted values:*/
              closeAllLists();
          });
          a.appendChild(b);
        }
      }
  });
  /*execute a function presses a key on the keyboard:*/
  inp.addEventListener("keydown", function(e) {
      var x = document.getElementById(this.id + "autocomplete-list");
      if (x) x = x.getElementsByTagName("div");
      if (e.keyCode == 40) {
        /*If the arrow DOWN key is pressed,
        increase the currentFocus variable:*/
        currentFocus++;
        /*and and make the current item more visible:*/
        addActive(x);
      } else if (e.keyCode == 38) { //up
        /*If the arrow UP key is pressed,
        decrease the currentFocus variable:*/
        currentFocus--;
        /*and and make the current item more visible:*/
        addActive(x);
      } else if (e.keyCode == 13) {
        /*If the ENTER key is pressed, prevent the form from being submitted,*/
        e.preventDefault();
        if (currentFocus > -1) {
          /*and simulate a click on the "active" item:*/
          if (x) x[currentFocus].click();
        }
      }
  });
  function addActive(x) {
    /*a function to classify an item as "active":*/
    if (!x) return false;
    /*start by removing the "active" class on all items:*/
    removeActive(x);
    if (currentFocus >= x.length) currentFocus = 0;
    if (currentFocus < 0) currentFocus = (x.length - 1);
    /*add class "autocomplete-active":*/
    x[currentFocus].classList.add("autocomplete-active");
  }
  function removeActive(x) {
    /*a function to remove the "active" class from all autocomplete items:*/
    for (var i = 0; i < x.length; i++) {
      x[i].classList.remove("autocomplete-active");
    }
  }
  function closeAllLists(elmnt) {
    /*close all autocomplete lists in the document,
    except the one passed as an argument:*/
    var x = document.getElementsByClassName("autocomplete-items");
    for (var i = 0; i < x.length; i++) {
      if (elmnt != x[i] && elmnt != inp) {
        x[i].parentNode.removeChild(x[i]);
      }
    }
  }
  /*execute a function when someone clicks in the document:*/
  document.addEventListener("click", function (e) {
      closeAllLists(e.target);
      });
}

/*An array containing all the country names in the world:*/
var nprioritarios = [<?= $nPrioritarios ?>];

/*initiate the autocomplete function on the "myInput" element, and pass along the countries array as possible autocomplete values:*/
var i = 0;

<?php if(!$model->isNewRecord){ ?>
  for(i = 0; i < <?= $np_id_increment ?>; i++){
    autocomplete(document.getElementById("autocomplete_"+i), nprioritarios);
  }
  if(<?= $np_id_increment ?> == 0){
    autocomplete(document.getElementById("autocomplete_0"), nprioritarios);
  }
<?php } ?>

</script>
