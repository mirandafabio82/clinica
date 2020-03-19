
<style>
    #header {
        position: absolute;
        margin-top: 0px;
        right: 15px;
        top: 20px;
    }

    #cabecalho {
        position: absolute;
        top: 260px;
        left: 60px;
        font-family: "Bahnschrift Condensed";
        font-weight: bold;
        font-size: 10px;
        text-align: justify;
        line-height: 1.5;
        color: black;
    }

    span {
        text-decoration: underline;
        text-align: center;
        font-weight: normal;
        font-size: 13px;
        font-family: "Bahnschrift Condensed";
    }

    #pergunta1 {
        height: 750px;
        position: absolute;
        top: 360px;
        left: 58px;
    }

    #pergunta2 {
        height: 950px;
        position: absolute;
        top: 40px;
        left: 58px;
    }
</style>

<div id="header" class="center img">
    <img src="resources/dist/img/cabecalho_anamnese.png">
</div>

<div id="cabecalho">
    <p>NOME:<span><?= $nome ?></span> DATA_NASC.:<span><?= $data_nascimento ?></span>&nbsp;CPF:<span><?= $cpf ?></span><br>
        ENDEREÇO:<span><?= $endereco ?></span> CONVÊNIO: ___________________________<br>
        ESTADO_CIVIL:<span><?= $status_civil ?></span> RELIGIÃO:___________________________&nbsp;PROFISSÃO:<span><?= $profissao ?></span><br>
        TELEFONE:<span><?= $telefone ?></span>WHATSAPP:<span><?= $celular ?></span>&nbsp;EMAIL:&nbsp;____________________________________________<br>
    </p>
</div>

<div id="pergunta1" class="center img">
    <img src="resources/dist/img/anaminese_page_1.png">
</div>

<pagebreak>

<div id="pergunta2">
    <img src="resources/dist/img/anaminese_page_2.png">
</div>
