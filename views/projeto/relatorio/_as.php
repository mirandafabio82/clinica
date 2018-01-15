<style>
table, th, td {
    border: 1px solid black;
    border-collapse: collapse;
}
th, td {
    
    text-align: left;    
}
</style>

<img src="resources/dist/img/logo_hcn.png" alt="User Image" style="width: 8em">
<img src="resources/dist/img/logo_clientes/brasken_logo.png" alt="User Image" style="float: right;width: 10em">
<div style="margin-top:-3em;font-size: 15pt;font-family: arial; font-weight: bold" line-height= "2em" align="center">AUTORIZAÇÃO </div>
<div style="margin-bottom:1em;font-size: 15pt;font-family: arial; font-weight: bold" line-height= "2em" align="center">DE SERVIÇO (AS) </div>


<table border="1" align="center" width="100%">
<tbody>
      <tr style="background-color: #d3d3d3;">
            <td style="font-size: 8pt" align="center">Número da AS</td> 
            <td style="font-size: 8pt" align="center">Nº Projetista</td> 
            <td style="font-size: 8pt" align="center">PJ</td> 
            <td style="font-size: 8pt" align="center">CC</td> 
            <td style="font-size: 8pt" align="center">DATA</td>       
            <td style="font-size: 8pt" align="center">ASS</td>
            <td style="font-size: 8pt" align="center">REVISÃO</td>    
      </tr>
      <tr>
            <td style="font-size: 8pt" align="center"><?=$projeto->proposta ?></td>
            <td style="font-size: 8pt" align="center"></td>
            <td style="font-size: 8pt" align="center"><?=$projeto->nome ?></td>
            <td style="font-size: 8pt" align="center"></td>
            <td style="font-size: 8pt" align="center"><?=$projeto->data_proposta ?></td>
            <td style="font-size: 8pt" align="center">HCN</td>
            <td style="font-size: 8pt" align="center"><?=$projeto->rev_proposta ?></td>            
      </tr>
       <tr style="background-color: #d3d3d3;">
            <td style="font-size: 8pt" align="center" colspan="7">Projeto</td>                        
      </tr>
      <tr>
            <td style="font-size: 8pt" align="center" colspan="7"><?=$projeto->descricao ?></td>                        
      </tr>
      <tr style="background-color: #d3d3d3;">
            <td style="font-size: 8pt" align="center" colspan="3">ÓRGÃO SOLICITANTE</td> 
            <td style="font-size: 8pt" align="center" colspan="2">CONTRATO Nº</td>                       
            <td style="font-size: 8pt" align="center" colspan="2">UN</td>
      </tr>
      <tr >
            <td style="font-size: 8pt" align="center" colspan="3">DEP</td> 
            <td style="font-size: 8pt" align="center" colspan="2">MUDAR</td>                       
            <td style="font-size: 8pt" align="center" colspan="2"><?=$projeto->site?></td>
      </tr>
      </tbody>
      </table>

      <table border="1" align="center" width="100%" style="margin-top: 0.5em">
	  <tbody>
      <tr style="background-color: #d3d3d3;">
            <td style="font-size: 8pt" align="center" colspan="7">DESCRIÇÃO RESUMIDA DOS SERVIÇOS</td>                        
      </tr>
      <tr>
            <td style="font-size: 8pt" align="center" colspan="7">MUDAR</td>                        
      </tr>
      </tbody>
      </table>
      
      <table border="1" align="center" width="100%" style="margin-top: 0.5em">
	  <tbody>
      <tr style="background-color: #d3d3d3;">
            <td style="font-size: 8pt" align="center" colspan="2">MODALIDADE</td> 
            <td style="font-size: 8pt" align="center" colspan="2">SERVIÇO</td>                       
            <td style="font-size: 8pt" align="center" colspan="3">VALOR TOTAL</td>
      </tr>
      <tr>
            <td style="font-size: 8pt" align="center" colspan="2">ADMINISTRAÇÃO PREÇO GLOBAL</td> 
            <td style="font-size: 8pt" align="center" colspan="2"><input type="checkbox" name="vehicle" value="Bike">BÁSICO <input type="checkbox" name="vehicle" value="Bike">DETALHAMENTO  <input type="checkbox" name="vehicle" value="Bike">CONFIGURAÇÃO</td>                       
            <td style="font-size: 8pt" align="center" colspan="3"><?=$projeto->valor_proposta ?> (Valor por extenso)</td>
      </tr>
      </tbody>
      </table>
      
      <table border="1" align="center" width="100%" style="margin-top: 0.5em">
	  <tbody>
      <tr style="background-color: #d3d3d3;">
            <td style="font-size: 8pt" align="center" colspan="7">DETALHAMENTO DOS CUSTOS</td>                        
      </tr>
      <tr style="background-color: #d3d3d3;">
            <td style="font-size: 8pt" >Categoria Profissional</td>  
            <td style="font-size: 8pt" align="center" >EE</td>
            <td style="font-size: 8pt" align="center" >ES</td>
            <td style="font-size: 8pt" align="center" >EP</td>
            <td style="font-size: 8pt" align="center" >EJ</td> 
            <td style="font-size: 8pt" align="center" >TP</td>    
            <td style="font-size: 8pt" align="center" colspan="2">TOTAL</td>                   
      </tr>
      
      <tr style="background-color: #d3d3d3;">
            <td style="font-size: 8pt" >PU Médios (R$)</td>  
            <td style="font-size: 8pt" align="center" ><?=$tipo_executante[4]['valor_hora'] ?></td>
            <td style="font-size: 8pt" align="center" ><?=$tipo_executante[3]['valor_hora'] ?></td>
            <td style="font-size: 8pt" align="center" ><?=$tipo_executante[2]['valor_hora'] ?></td>
            <td style="font-size: 8pt" align="center" ><?=$tipo_executante[1]['valor_hora'] ?></td> 
            <td style="font-size: 8pt" align="center" ><?=$tipo_executante[0]['valor_hora'] ?></td>    
            <td style="font-size: 8pt" align="center" >H/h</td> 
            <td style="font-size: 8pt" align="center" >R$</td>                  
      </tr>
      <tr>
            <td style="font-size: 8pt" >PROCESSO</td>  
            <td style="font-size: 8pt" align="center" ><?=$tipo_executante[4]['valor_hora'] ?></td>
            <td style="font-size: 8pt" align="center" ><?=$tipo_executante[3]['valor_hora'] ?></td>
            <td style="font-size: 8pt" align="center" ><?=$tipo_executante[2]['valor_hora'] ?></td>
            <td style="font-size: 8pt" align="center" ><?=$tipo_executante[1]['valor_hora'] ?></td> 
            <td style="font-size: 8pt" align="center" ><?=$tipo_executante[0]['valor_hora'] ?></td>    
            <td style="font-size: 8pt" align="center" >H/h</td> 
            <td style="font-size: 8pt" align="center" ></td>                  
      </tr>
      <tr>
            <td style="font-size: 8pt" >TUBULAÇÃO</td>  
            <td style="font-size: 8pt" align="center" ></td>
            <td style="font-size: 8pt" align="center" ></td>
            <td style="font-size: 8pt" align="center" ></td>
            <td style="font-size: 8pt" align="center" ></td> 
            <td style="font-size: 8pt" align="center" ></td>    
            <td style="font-size: 8pt" align="center" ></td> 
            <td style="font-size: 8pt" align="center" ></td>                  
      </tr>
      <tr>
            <td style="font-size: 8pt" >CIVIL/METÁLICA</td>  
            <td style="font-size: 8pt" align="center" ></td>
            <td style="font-size: 8pt" align="center" ></td>
            <td style="font-size: 8pt" align="center" ></td>
            <td style="font-size: 8pt" align="center" ></td> 
            <td style="font-size: 8pt" align="center" ></td>    
            <td style="font-size: 8pt" align="center" ></td> 
            <td style="font-size: 8pt" align="center" ></td>                     
      </tr>
      <tr>
            <td style="font-size: 8pt" >ELÉTRICA</td>  
            <td style="font-size: 8pt" align="center" ></td>
            <td style="font-size: 8pt" align="center" ></td>
            <td style="font-size: 8pt" align="center" ></td>
            <td style="font-size: 8pt" align="center" ></td> 
            <td style="font-size: 8pt" align="center" ></td>    
            <td style="font-size: 8pt" align="center" ></td> 
            <td style="font-size: 8pt" align="center" ></td>              
      </tr>
      <tr>
            <td style="font-size: 8pt" >INSTRUMENTAÇÃO</td>  
            <td style="font-size: 8pt" align="center" ><?=$tipo_executante[4]['valor_hora'] ?></td>
            <td style="font-size: 8pt" align="center" ><?=$tipo_executante[3]['valor_hora'] ?></td>
            <td style="font-size: 8pt" align="center" ><?=$tipo_executante[2]['valor_hora'] ?></td>
            <td style="font-size: 8pt" align="center" ><?=$tipo_executante[1]['valor_hora'] ?></td> 
            <td style="font-size: 8pt" align="center" ><?=$tipo_executante[0]['valor_hora'] ?></td>    
            <td style="font-size: 8pt" align="center" >H/h</td> 
            <td style="font-size: 8pt" align="center" ></td>                  
      </tr>
      <tr>
            <td style="font-size: 8pt" >AUTOMAÇÃO</td>  
            <td style="font-size: 8pt" align="center" ><?=$tipo_executante[4]['valor_hora'] ?></td>
            <td style="font-size: 8pt" align="center" ><?=$tipo_executante[3]['valor_hora'] ?></td>
            <td style="font-size: 8pt" align="center" ><?=$tipo_executante[2]['valor_hora'] ?></td>
            <td style="font-size: 8pt" align="center" ><?=$tipo_executante[1]['valor_hora'] ?></td> 
            <td style="font-size: 8pt" align="center" ><?=$tipo_executante[0]['valor_hora'] ?></td>    
            <td style="font-size: 8pt" align="center" >H/h</td> 
            <td style="font-size: 8pt" align="center" ></td>                  
      </tr>
      <tr>
            <td style="font-size: 8pt" >CALDEIRARIA</td>  
            <td style="font-size: 8pt" align="center" ></td>
            <td style="font-size: 8pt" align="center" ></td>
            <td style="font-size: 8pt" align="center" ></td>
            <td style="font-size: 8pt" align="center" ></td> 
            <td style="font-size: 8pt" align="center" ></td>    
            <td style="font-size: 8pt" align="center" ></td> 
            <td style="font-size: 8pt" align="center" ></td>              
      </tr>
      <tr>
            <td style="font-size: 8pt" >ROTATIVOS</td>  
            <td style="font-size: 8pt" align="center" ></td>
            <td style="font-size: 8pt" align="center" ></td>
            <td style="font-size: 8pt" align="center" ></td>
            <td style="font-size: 8pt" align="center" ></td> 
            <td style="font-size: 8pt" align="center" ></td>    
            <td style="font-size: 8pt" align="center" ></td> 
            <td style="font-size: 8pt" align="center" ></td>              
      </tr>
      <tr style="background-color: #d3d3d3;"> 
            <td style="font-size: 8pt" >TOTAL DISCIPLINAS</td>  
            <td style="font-size: 8pt" align="center" ></td>
            <td style="font-size: 8pt" align="center" ></td>
            <td style="font-size: 8pt" align="center" ></td>
            <td style="font-size: 8pt" align="center" ></td> 
            <td style="font-size: 8pt" align="center" ></td>    
            <td style="font-size: 8pt" align="center" ></td> 
            <td style="font-size: 8pt" align="center" ></td>              
      </tr>
      <tr> 
            <td style="font-size: 8pt" colspan="6">SUB-CONTRATAÇÃO</td>              
            <td style="font-size: 8pt" align="center" ></td>              
      </tr>
      <tr style="background-color: #d3d3d3;"> 
            <td style="font-size: 8pt" colspan="7">SUBTOTAL</td>              
            <td style="font-size: 8pt" align="center" ></td>              
      </tr>
      
	</tbody>
      </table>
      
      <table border="1" align="center" width="100%" style="margin-top: 0.5em">
	  <tbody>
	<tr style="background-color: #d3d3d3;">
            <td style="font-size: 8pt" align="center" colspan="10">CUSTOS DIVERSOS</td>            
     </tr>
     <tr style="background-color: #d3d3d3;">
            <td style="font-size: 8pt" align="center" colspan="2">Viagens</td> 
            <td style="font-size: 8pt" align="center" colspan="8"></td>
            <td style="font-size: 8pt" align="center" colspan="2">-</td>           
     </tr>
     <tr style="background-color: #d3d3d3;">
            <td style="font-size: 8pt" align="center" colspan="2">Viagens</td> 
            <td style="font-size: 8pt" align="center" colspan="8"></td>
            <td style="font-size: 8pt" align="center" colspan="2">-</td>           
     </tr>
     <tr style="background-color: #d3d3d3;">
            <td style="font-size: 8pt" align="center" colspan="2">Viagens</td> 
            <td style="font-size: 8pt" align="center" colspan="8"></td>
            <td style="font-size: 8pt" align="center" colspan="2">-</td>           
     </tr>
     <tr style="background-color: #d3d3d3;">
            <td style="font-size: 8pt" align="center" colspan="2">Viagens</td> 
            <td style="font-size: 8pt" align="center" colspan="8"></td>
            <td style="font-size: 8pt" align="center" colspan="2">-</td>           
     </tr>
	</tbody>
      </table>
      
      <table border="1" align="center" width="100%" style="margin-top: 0.5em">
	  <tbody>
	<tbody>
	<tr style="background-color: #d3d3d3;">
            <td style="font-size: 8pt" colspan="10">DOCUMENTOS QUE SOLICITARAM A MUDANÇA DE OBJETO:</td>            
     </tr>
     <tr>
            <td style="font-size: 8pt" align="center">E-MAIL</td> 
            <td style="font-size: 8pt" align="center">DR-____</td>
            <td style="font-size: 8pt" align="center">NR-____</td> 
            <td style="font-size: 8pt" align="center">CT-____</td>        
            <td style="font-size: 8pt" align="center">OUTROS</td>  
     </tr>
	</tbody>
      </table>
      
      <table border="1" align="center" width="100%" style="margin-top: 0.5em">
	  <tbody>
	<tr style="background-color: #d3d3d3;">
            <td style="font-size: 8pt" align="center">Orçamento do Empreendimento (Devido esta M.O.)</td>
            <td style="font-size: 8pt" align="center">Alterações no Cronograma</td>            
     </tr>
     <tr>
            <td style="font-size: 8pt" >Sujeito a Acréscimo</td> 
            <td style="font-size: 8pt" >Prazos não sofrem Alterações</td>            
     </tr>
     <tr>
            <td style="font-size: 8pt" >Sujeito a Reduções</td> 
            <td style="font-size: 8pt" >Prazos Parciais Alterados - Prazo Final Mantido</td>            
     </tr>
     <tr>
            <td style="font-size: 8pt" >Esta M.O. não interfere no Budget</td> 
            <td style="font-size: 8pt" >Prazos Final Alterado</td>            
     </tr>
	</tbody>
      </table>
      
      <table border="1" align="center" width="100%" style="margin-top: 0.5em">
	  <tbody>
	<tr>
            <td style="font-size: 8pt" colspan="10">ANEXOS</td>            
     </tr>
	<tr>
            <td style="font-size: 8pt" >LDP</td>
            <td style="font-size: 8pt" >Hh POR ATIVIDADE</td>            
     </tr>
     <tr>
            <td style="font-size: 8pt" >CRONOGRAMA</td> 
            <td style="font-size: 8pt" >ASC (AUTORIZAÇÃO DE SUBCONTRATAÇÃO)</td>            
     </tr>
     <tr>
            <td style="font-size: 8pt" >PLANO DE PROJETO</td> 
            <td style="font-size: 8pt" >OUTRO</td>            
     </tr>
     <tr>
            <td style="font-size: 8pt" >GSS Nº </td> 
            <td style="font-size: 8pt" ></td>            
     </tr>
	</tbody>
      </table>
      
      <table border="1" align="center" width="100%" style="margin-top: 0.5em">
	  <tbody>
	<tr>
            <td style="font-size: 8pt" colspan="10">DIAGRAMA DE REDE</td>            
     </tr>
	<tr>
            <td style="font-size: 8pt" >PROCESSO: </td>
            <td style="font-size: 8pt" >INSTRUMENTAÇÃO: </td>            
     </tr>
     <tr>
            <td style="font-size: 8pt" >TUBULAÇÃO: </td> 
            <td style="font-size: 8pt" >AUTOMAÇÃO: </td>            
     </tr>
     <tr>
            <td style="font-size: 8pt" >CIVIL / METÁLICA: </td> 
            <td style="font-size: 8pt" >CALDEIRARIA: </td>            
     </tr>
     <tr>
            <td style="font-size: 8pt" >ELÉTRICA: </td> 
            <td style="font-size: 8pt" >ROTATIVOS: </td>            
     </tr>
	</tbody>
      </table>
      
      <table border="1" align="center" width="100%" style="margin-top: 0.5em">
	  <tbody>
	<tr>
            <td style="font-size: 8pt" >NOTAS GERAIS</td>            
     </tr>
     <tr>
            <td style="font-size: 8pt" >DDASDAS</td>            
     </tr>
	</tbody>
      </table>
      
      <table border="1" align="center" width="100%" style="margin-top: 0.5em">
	  <tbody>
	<tr>
        <td style="font-size: 8pt" align="center">ASSINATURA</td>     
        <td style="font-size: 8pt" align="center">DATA</td> 
        <td style="font-size: 8pt" align="center">ASSINATURA</td>     
        <td style="font-size: 8pt" align="center">DATA</td>        
     </tr>
     <tr>
            <td style="font-size: 8pt" colspan="2" align="center">____________________________________</td>    
            <td style="font-size: 8pt" colspan="2" align="center">____________________________________</td>        
     </tr>
     <tr>
            <td style="font-size: 8pt" colspan="2" align="center">Coordenação Projetista</td> 
            <td style="font-size: 8pt" colspan="2" align="center">Coordenador do Projeto-BRASKEM</td>            
     </tr>
</tbody>
</table>
	