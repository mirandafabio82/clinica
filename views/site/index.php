<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use kartik\money\MaskMoney;
use kartik\tabs\TabsX;
use kartik\popover\PopoverX;
use yii\bootstrap\Modal;
use yii\widgets\Pjax;
use kartik\select2\Select2;
use app\models\Projeto;

/* @var $this yii\web\View */

$this->title = 'Vital Sorrir';

?>

<style>
  div.scrollmenu {
    overflow: auto;
    white-space: nowrap;
  }

  div.scrollmenu a {
    display: inline-block;
    color: white;
    text-align: center;
    padding: 14px;
    text-decoration: none;
  }

  .endRows {
    background-color: aliceblue;
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

  .select2-results__options {
    background-color: white;
  }
</style>

<?php
$eventos = '';
foreach ($arrayEventos as $key => $evt) {
  $cor = 'blue';
  $eventos .= "{
                        id             : " . 4 . ",
                        title          : '" . 'Teste' . "',
                        start          : '" .'14:00' . "',
                        end            : '" . '15:00' . "',
                        backgroundColor: '" . $cor . "', 
                        borderColor    : '" . $cor . "'
                      },";
}

?>
<div class="box-body no-padding">
<img src="resources/dist/img/fundo_vital_sorrir.png">
</div>