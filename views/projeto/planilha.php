<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <title></title>

    <style>
        .right {
            float: right;
        }

        #exportButton {
            float: left;
        }
    </style>
    <!--Required scripts-->
    <script src="http://code.jquery.com/jquery-1.11.3.min.js"></script>
    <!-- External files for exporting -->
    <script src="https://www.igniteui.com/js/external/FileSaver.js"></script>
    <script src="https://www.igniteui.com/js/external/Blob.js"></script>

    <script type="text/javascript" src="http://cdn-na.infragistics.com/igniteui/2017.2/latest/js/infragistics.core.js"></script>

    <script type="text/javascript" src="http://cdn-na.infragistics.com/igniteui/2017.2/latest/js/modules/infragistics.ext_core.js"></script>
    <script type="text/javascript" src="http://cdn-na.infragistics.com/igniteui/2017.2/latest/js/modules/infragistics.ext_collections.js"></script>
    <script type="text/javascript" src="http://cdn-na.infragistics.com/igniteui/2017.2/latest/js/modules/infragistics.ext_text.js"></script>
    <script type="text/javascript" src="http://cdn-na.infragistics.com/igniteui/2017.2/latest/js/modules/infragistics.ext_io.js"></script>
    <script type="text/javascript" src="http://cdn-na.infragistics.com/igniteui/2017.2/latest/js/modules/infragistics.ext_ui.js"></script>
    <script type="text/javascript" src="http://cdn-na.infragistics.com/igniteui/2017.2/latest/js/modules/infragistics.documents.core_core.js"></script>
    <script type="text/javascript" src="http://cdn-na.infragistics.com/igniteui/2017.2/latest/js/modules/infragistics.ext_collectionsextended.js"></script>
    <script type="text/javascript" src="http://cdn-na.infragistics.com/igniteui/2017.2/latest/js/modules/infragistics.excel_core.js"></script>
    <script type="text/javascript" src="http://cdn-na.infragistics.com/igniteui/2017.2/latest/js/modules/infragistics.ext_threading.js"></script>
    <script type="text/javascript" src="http://cdn-na.infragistics.com/igniteui/2017.2/latest/js/modules/infragistics.ext_web.js"></script>
    <script type="text/javascript" src="http://cdn-na.infragistics.com/igniteui/2017.2/latest/js/modules/infragistics.xml.js"></script>
    <script type="text/javascript" src="http://cdn-na.infragistics.com/igniteui/2017.2/latest/js/modules/infragistics.documents.core_openxml.js"></script>
    <script type="text/javascript" src="http://cdn-na.infragistics.com/igniteui/2017.2/latest/js/modules/infragistics.excel_serialization_openxml.js"></script>
</head>
<body>
    <button id="exportButton" onclick="createFormattingWorkbook()">Create File</button>
    <br />
    <img alt="Result in Excel" src="https://www.igniteui.com/images/samples/client-side-excel-library/excel-formatting.png" />
    <script>

        function createFormattingWorkbook() {

            var workbook = new $.ig.excel.Workbook($.ig.excel.WorkbookFormat.excel2007);
            var sheet = workbook.worksheets().add('CAPA');
            // sheet.columns(0).setWidth(96, $.ig.excel.WorksheetColumnWidthUnit.pixel);
            // sheet.columns(4).setWidth(80, $.ig.excel.WorksheetColumnWidthUnit.pixel);
            // sheet.columns(6).setWidth(96, $.ig.excel.WorksheetColumnWidthUnit.pixel);

            // Add merged regions for regions A1:D2 and E1:G2
            // var mergedCellA1D2 = sheet.mergedCellsRegions().add(0, 0, 1, 3);
            // var mergedCellE1G2 = sheet.mergedCellsRegions().add(0, 4, 15, 5);

            var mergedCellA16J16 = sheet.mergedCellsRegions().add(15, 0, 15, 9);
            mergedCellA16J16.value('Autorização de Serviço');
            mergedCellA16J16.cellFormat().font().height(14 * 40);
            mergedCellA16J16.cellFormat().font().bold(true);
            mergedCellA16J16.cellFormat().alignment($.ig.excel.HorizontalCellAlignment.center);

            var mergedCellA19J19 = sheet.mergedCellsRegions().add(18, 0, 18, 9);
            mergedCellA19J19.value('HCN Nº $projeto->proposta');
            mergedCellA19J19.cellFormat().font().height(14 * 20);
            mergedCellA19J19.cellFormat().font().bold(true);
            mergedCellA19J19.cellFormat().alignment($.ig.excel.HorizontalCellAlignment.center);

            var mergedCellA19J19 = sheet.mergedCellsRegions().add(23, 0, 26, 9);
            mergedCellA19J19.value('$projeto->descricao');
            mergedCellA19J19.cellFormat().font().height(14 * 50);
            mergedCellA19J19.cellFormat().alignment($.ig.excel.HorizontalCellAlignment.center);

            sheet.getCell('A49').value('REV.');
            sheet.getCell('B49').value('DATA');
            var mergedCellC49I49 = sheet.mergedCellsRegions().add(48, 2, 48, 8);
            mergedCellC49I49.value('Descrição');            
            mergedCellC49I49.cellFormat().alignment($.ig.excel.HorizontalCellAlignment.center);
            sheet.getCell('J49').value('POR');
            sheet.getCell('J49').cellFormat().font().bold(true);
            // Add two large headers in merged cells above the data
            // mergedCellA1D2.value('Acme, Inc.');

            // mergedCellA1D2.cellFormat().alignment($.ig.excel.HorizontalCellAlignment.center);
            // mergedCellA1D2.cellFormat().fill($.ig.excel.CellFill.createSolidFill('#ED7D31'));
            // mergedCellA1D2.cellFormat().font().colorInfo(new $.ig.excel.WorkbookColorInfo($.ig.excel.WorkbookThemeColorType.light1));
            // mergedCellA1D2.cellFormat().font().height(16 * 20);

            // mergedCellE1G2.value('Invoice #32039');
            // mergedCellE1G2.cellFormat().alignment($.ig.excel.HorizontalCellAlignment.center);
            // mergedCellE1G2.cellFormat().fill($.ig.excel.CellFill.createSolidFill('#FFC000'));
            // mergedCellE1G2.cellFormat().font().colorInfo(new $.ig.excel.WorkbookColorInfo($.ig.excel.WorkbookThemeColorType.light1));
            // mergedCellE1G2.cellFormat().font().height(16 * 20);

            // Format some rows and columns that should have similar formatting so we don't have to set it on individual cells.
            // sheet.rows(2).cellFormat().font().bold(true);
            // sheet.columns(4).cellFormat().formatString('$#,##0.00_);[Red]($#,##0.00)');
            // sheet.columns(6).cellFormat().formatString('$#,##0.00_);[Red]($#,##0.00)');

            // Add a light color fill to all cells in the A3:G17 region to visually separate it from the rest of the sheet. We can iterate
            // all cells in the regions by getting an enumerator for the region and enumerating each item.
            var light1Fill = $.ig.excel.CellFill.createSolidFill(new $.ig.excel.WorkbookColorInfo($.ig.excel.WorkbookThemeColorType.light1));
            // var cells = sheet.getRegion('A3:G17').getEnumerator();
            // while (cells.moveNext()) {
            //     cells.current().cellFormat().fill(light1Fill);
            // }

            // Populate the sheet with data
            

            /*sheet.getCell('D19').value('Nº HCN');
            sheet.getCell('E19').value('$projeto->proposta');
            sheet.getCell('E3').value('Cost/Unit');
            sheet.getCell('G3').value('Total');

            sheet.getCell('A4').value(new Date('12/22/2014'));
            sheet.getCell('B4').value('Garage Door');
            sheet.getCell('D4').value(1);
            sheet.getCell('E4').value(1875);
            sheet.getCell('G4').applyFormula('=D4*E4');

            sheet.getCell('A5').value(new Date('12/22/2014'));
            sheet.getCell('B5').value('Trim');
            sheet.getCell('D5').value(3);
            sheet.getCell('E5').value(27.95);
            sheet.getCell('G5').applyFormula('=D5*E5');

            sheet.getCell('A6').value(new Date('12/22/2014'));
            sheet.getCell('B6').value('Install/Labor');
            sheet.getCell('D6').value(8);
            sheet.getCell('E6').value(85);
            sheet.getCell('G6').applyFormula('=D6*E6');

            // Add a grand total which is bold and larger than the rest of the text to call attention to it.
            sheet.getCell('E17').value('GRAND TOTAL');
            sheet.getCell('E17').cellFormat().font().height(14 * 20);
            sheet.getCell('E17').cellFormat().font().bold(true);

            sheet.getCell('G17').applyFormula('=SUM(G4:G16)');
            sheet.getCell('G17').cellFormat().font().height(14 * 20);
            sheet.getCell('G17').cellFormat().font().bold(true);
			*/
            var sheet2 = workbook.worksheets().add('AS');
            var mergedCellA2D4 = sheet2.mergedCellsRegions().add(1, 0, 3, 3);
            mergedCellA2D4.value('LOGO');
            mergedCellA2D4.cellFormat().alignment($.ig.excel.HorizontalCellAlignment.center);

            var mergedCellL2Q4 = sheet2.mergedCellsRegions().add(1, 4, 3, 7);
            mergedCellL2Q4.value('Autorização de Serviço (AS)');
            mergedCellL2Q4.cellFormat().font().height(14 * 20);
            mergedCellL2Q4.cellFormat().alignment($.ig.excel.HorizontalCellAlignment.center);

            var mergedCellR2U4 = sheet2.mergedCellsRegions().add(1, 8, 3, 11);
            mergedCellR2U4.value('LOGO');
            mergedCellR2U4.cellFormat().alignment($.ig.excel.HorizontalCellAlignment.center);

            var mergedCellR2U4 = sheet2.mergedCellsRegions().add(4, 0, 4, 1);
            mergedCellR2U4.value('Número da AS');
            mergedCellR2U4.cellFormat().fill($.ig.excel.CellFill.createSolidFill('#808080'));
            mergedCellR2U4.cellFormat().font().colorInfo(new $.ig.excel.WorkbookColorInfo($.ig.excel.WorkbookThemeColorType.light1));

            var mergedCellR2U4 = sheet2.mergedCellsRegions().add(4, 2, 4, 3);
            mergedCellR2U4.value('Nº Projetista');
            mergedCellR2U4.cellFormat().fill($.ig.excel.CellFill.createSolidFill('#808080'));
            mergedCellR2U4.cellFormat().font().colorInfo(new $.ig.excel.WorkbookColorInfo($.ig.excel.WorkbookThemeColorType.light1));

            var mergedCellV2X4 = sheet2.mergedCellsRegions().add(4, 4, 4, 5);
            mergedCellV2X4.value('PJ');
            mergedCellV2X4.cellFormat().fill($.ig.excel.CellFill.createSolidFill('#808080'));
            mergedCellV2X4.cellFormat().font().colorInfo(new $.ig.excel.WorkbookColorInfo($.ig.excel.WorkbookThemeColorType.light1));

            var mergedCellX2X4 = sheet2.mergedCellsRegions().add(4, 6, 4, 7);
            mergedCellX2X4.value('CC');
            mergedCellX2X4.cellFormat().fill($.ig.excel.CellFill.createSolidFill('#808080'));
            mergedCellX2X4.cellFormat().font().colorInfo(new $.ig.excel.WorkbookColorInfo($.ig.excel.WorkbookThemeColorType.light1));

            var mergedCellY2Y4 = sheet2.mergedCellsRegions().add(4, 8, 4, 9);
            mergedCellY2Y4.value('DATA');
            mergedCellY2Y4.cellFormat().fill($.ig.excel.CellFill.createSolidFill('#808080'));
            mergedCellY2Y4.cellFormat().font().colorInfo(new $.ig.excel.WorkbookColorInfo($.ig.excel.WorkbookThemeColorType.light1));

            sheet2.getCell('K5').value('ASS');
            sheet2.getCell('K5').cellFormat().font().colorInfo(new $.ig.excel.WorkbookColorInfo($.ig.excel.WorkbookThemeColorType.light1));
            sheet2.getCell('K5').cellFormat().fill($.ig.excel.CellFill.createSolidFill('#808080'));

            sheet2.getCell('L5').value('REVISÃO');
            sheet2.getCell('L5').cellFormat().font().colorInfo(new $.ig.excel.WorkbookColorInfo($.ig.excel.WorkbookThemeColorType.light1));
            sheet2.getCell('L5').cellFormat().fill($.ig.excel.CellFill.createSolidFill('#808080'));

            var sheet3 = workbook.worksheets().add('PROCESSO');
            var anexo1 = sheet3.mergedCellsRegions().add(1, 0, 1, 16);
            anexo1.value('ANEXO I');
            anexo1.cellFormat().font().height(14 * 20);
            anexo1.cellFormat().alignment($.ig.excel.HorizontalCellAlignment.center);
            anexo1.cellFormat().font().bold(true);

            var sub1 = sheet3.mergedCellsRegions().add(2, 0, 2, 16);
            sub1.value('ESTIMATIVA DE CUSTO POR ESPECIALIDADE');
            sub1.cellFormat().font().height(14 * 20);
            sub1.cellFormat().alignment($.ig.excel.HorizontalCellAlignment.center);
            sub1.cellFormat().font().bold(true);

            var proc1 = sheet3.mergedCellsRegions().add(3, 0, 3, 16);
            proc1.value('PROCESSO');
            proc1.cellFormat().font().height(14 * 20);
            proc1.cellFormat().alignment($.ig.excel.HorizontalCellAlignment.center);
            proc1.cellFormat().font().bold(true);

            var proc1 = sheet3.mergedCellsRegions().add(4, 0, 4, 16);
            proc1.value('$projeto->descricao');
            proc1.cellFormat().font().height(14 * 20);
            proc1.cellFormat().alignment($.ig.excel.HorizontalCellAlignment.center);
            proc1.cellFormat().font().bold(true);

            sheet3.getCell('O6').value('$projeto->nome');
            sheet3.getCell('O6').cellFormat().font().bold(true);
            sheet3.getCell('O7').value('Planta: $projeto->planta'); 
            sheet3.getCell('O7').cellFormat().font().bold(true);           

            sheet3.getCell('B9').value('ITEM');
            sheet3.getCell('B9').cellFormat().alignment($.ig.excel.HorizontalCellAlignment.center);
            sheet3.getCell('B9').cellFormat().font().colorInfo(new $.ig.excel.WorkbookColorInfo($.ig.excel.WorkbookThemeColorType.light1));           
            sheet3.getCell('B9').cellFormat().fill($.ig.excel.CellFill.createSolidFill('#808080'));

            sheet3.getCell('B8').cellFormat().fill($.ig.excel.CellFill.createSolidFill('#808080'));  

            sheet3.getCell('H9').value('QTD.');
            sheet3.getCell('H9').cellFormat().alignment($.ig.excel.HorizontalCellAlignment.center);
            sheet3.getCell('H9').cellFormat().font().colorInfo(new $.ig.excel.WorkbookColorInfo($.ig.excel.WorkbookThemeColorType.light1));           
            sheet3.getCell('H9').cellFormat().fill($.ig.excel.CellFill.createSolidFill('#808080'));
            
            sheet3.getCell('H8').cellFormat().fill($.ig.excel.CellFill.createSolidFill('#808080'));  

            sheet3.getCell('I9').value('FOR.');
            sheet3.getCell('I9').cellFormat().alignment($.ig.excel.HorizontalCellAlignment.center);
            sheet3.getCell('I9').cellFormat().font().colorInfo(new $.ig.excel.WorkbookColorInfo($.ig.excel.WorkbookThemeColorType.light1));           
            sheet3.getCell('I9').cellFormat().fill($.ig.excel.CellFill.createSolidFill('#808080'));
            
            sheet3.getCell('I8').cellFormat().fill($.ig.excel.CellFill.createSolidFill('#808080'));         

            var descProc = sheet3.mergedCellsRegions().add(7, 2, 8, 6);
            descProc.value('DESCRIÇÃO');
            descProc.cellFormat().fill($.ig.excel.CellFill.createSolidFill('#808080'));
            descProc.cellFormat().font().colorInfo(new $.ig.excel.WorkbookColorInfo($.ig.excel.WorkbookThemeColorType.light1));
            descProc.cellFormat().alignment($.ig.excel.HorizontalCellAlignment.center);

            var descProc = sheet3.mergedCellsRegions().add(7, 9, 7, 16);
            descProc.value('DISTRIBUIÇÃO POR CATEGORIA');
            descProc.cellFormat().fill($.ig.excel.CellFill.createSolidFill('#808080'));
            descProc.cellFormat().font().colorInfo(new $.ig.excel.WorkbookColorInfo($.ig.excel.WorkbookThemeColorType.light1));
            descProc.cellFormat().alignment($.ig.excel.HorizontalCellAlignment.center);

            sheet3.getCell('J9').value('EE');
           	sheet3.getCell('J9').cellFormat().font().colorInfo(new $.ig.excel.WorkbookColorInfo($.ig.excel.WorkbookThemeColorType.light1));           
            sheet3.getCell('J9').cellFormat().fill($.ig.excel.CellFill.createSolidFill('#808080'));

            sheet3.getCell('K9').value('ES');
           	sheet3.getCell('K9').cellFormat().font().colorInfo(new $.ig.excel.WorkbookColorInfo($.ig.excel.WorkbookThemeColorType.light1));           
            sheet3.getCell('K9').cellFormat().fill($.ig.excel.CellFill.createSolidFill('#808080'));

            sheet3.getCell('L9').value('EP');
           	sheet3.getCell('L9').cellFormat().font().colorInfo(new $.ig.excel.WorkbookColorInfo($.ig.excel.WorkbookThemeColorType.light1));           
            sheet3.getCell('L9').cellFormat().fill($.ig.excel.CellFill.createSolidFill('#808080'));

            sheet3.getCell('M9').value('EJ');
           	sheet3.getCell('M9').cellFormat().font().colorInfo(new $.ig.excel.WorkbookColorInfo($.ig.excel.WorkbookThemeColorType.light1));           
            sheet3.getCell('M9').cellFormat().fill($.ig.excel.CellFill.createSolidFill('#808080'));

            sheet3.getCell('N9').value('TP');
           	sheet3.getCell('N9').cellFormat().font().colorInfo(new $.ig.excel.WorkbookColorInfo($.ig.excel.WorkbookThemeColorType.light1));           
            sheet3.getCell('N9').cellFormat().fill($.ig.excel.CellFill.createSolidFill('#808080'));

            sheet3.getCell('O9').cellFormat().fill($.ig.excel.CellFill.createSolidFill('#808080'));
            sheet3.getCell('O10').cellFormat().fill($.ig.excel.CellFill.createSolidFill('#808080'));
            sheet3.getCell('O11').cellFormat().fill($.ig.excel.CellFill.createSolidFill('#808080'));
            

            // Save the workbook
            saveWorkbook(workbook, "Formatting.xlsx");
        }

        function saveWorkbook(workbook, name) {
            workbook.save({ type: 'blob' }, function (data) {
                saveAs(data, name);
            }, function (error) {
                alert('Error exporting: : ' + error);
            });
        }

    </script>
</body>
</html>