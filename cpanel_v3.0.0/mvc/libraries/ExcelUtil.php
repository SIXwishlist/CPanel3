<?php

/*
 *
 */

include_once BASE_DIR.'/mvc/libraries/excel/PHPExcel.php';

/**
 * Description of ExcelUtil
 *
 * @author Arak
 */

class ExcelUtil {

    public static function export_to_excel($data, $headers=null, $filename=null){

        $status = 0;

        try{

            if( ! isset($filename) ) {
                $filename = "file.xls";
            }

            if( ! isset($headers) ) {
                $headers = array();
            }

            $phpExcel = new PHPExcel();
            $phpExcel->getActiveSheet()->setTitle("My Sheet");

            $phpExcel->setActiveSheetIndex(0);
            header("Content-Type: application/vnd.ms-excel");
            header("Content-Disposition: attachment; filename=\"$filename\"");
            header("Cache-Control: max-age=0");
            
            $worksheet = $phpExcel->getActiveSheet();
            $worksheet->fromArray( $headers, "", 'A1' );
            $worksheet->fromArray( $data,    "", 'A2' );

            $objWriter = PHPExcel_IOFactory::createWriter($phpExcel, "Excel5");
            $objWriter->save("php://output");

            exit();
            
        } catch (Exception $e) {
            throw new CustomException( 'Error in : exporting to excel', $e );//from php 5.3 no need to custum
        }

        return $status;
    }

    
    public static function import_from_excel($inputFileName=null){

        $array = array();
        
        try{

            //  Read your Excel workbook
            try {
                
                $inputFileType = PHPExcel_IOFactory::identify($inputFileName);
                $objReader     = PHPExcel_IOFactory::createReader($inputFileType);
                
                $objPHPExcel   = $objReader->load($inputFileName);
                
            } catch(Exception $e) {
                die('Error loading file "'.pathinfo($inputFileName,PATHINFO_BASENAME).'": '.$e->getMessage());
            }

            //  Get worksheet dimensions
            $sheet         = $objPHPExcel->getSheet(0); 
            $highestRow    = $sheet->getHighestRow(); 
            $highestColumn = $sheet->getHighestColumn();

            //  Loop through each row of the worksheet in turn
            for ($row = 1; $row <= $highestRow; $row++){ 
                //  Read a row of data into an array
                $rowData = $sheet->rangeToArray('A' . $row . ':' . $highestColumn . $row,
                                                NULL,
                                                TRUE,
                                                FALSE);

                //  Insert row data array into your array
                $array[] = $rowData[0];
            }
            
        } catch (Exception $e) {
            throw new CustomException( 'Error in : importing to excel', $e );//from php 5.3 no need to custum
        }

        return $array;
    }
}

?>