<style type="text/css">
    #texto {
        font-family: "Arial";
        font-size: 20px;
        line-height: 2.5;
        text-align: justify;
        padding-left: 350px;
        padding-right: 350px;
    }
</style>

<div>
    <img src="resources/dist/img/forma_atestado.png" width="100%">
</div>

<div style="padding-bottom: 50px">
    <p align="center"><img src="resources/dist/img/logo_vitalsorrir_menu.png" align="center" width="150"></p>
    <p style="text-align: center; font-size: 24px; font-family: Arial, Helvetica, sans-serif">
        <b>ATESTADO DE COMPARECIMENTO</b>
    </p>

    <div id="texto">

        <form action="" method="submit">
            <p>Atesto que o Sr.(a) <?= $nome ?>, portador(a) do RG <?= $rg ?>, compareceu a esta clínica no período de _______ às ________ horas do dia <?= $data ?>.</p>
            <br>
            <p>Salvador, __ de __________ de ____.</p>
        </form>
    </div>

    <div class="row" style="display: table; margin-top: 20px">
        <div class="column" style="width: 40%; float: left; align-items:center; display:flex;">
            <img src="resources/dist/img/endereco.png" width="30">
            <p> Av. Sete de Setembro, 880, Ed. Poty, Sala 201 A, Dois de Julho, CEP 40060-001, Salvador, Bahia</p>
        </div>
    </div>
</div>