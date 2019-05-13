<?php

class UvozController extends Zend_Controller_Action
{

    public function spisakKupacaAction(){
    	$tipKupacaMapper=new Application_Model_Mapper_TipKupaca();
    	$this->view->tipovi=$tipKupacaMapper->fetchAllToArray();
    }
    
	public function izveziKupceAction(){
		$this->_helper->layout->disableLayout();
    	$this->_helper->viewRenderer->setNoRender();
    	
    	$request = $this->_request;
    	$idTip = $request->getParam('tipKupaca', null);
    	
    	$kupMap = new Mapperext_Kupci();
    	$kupci = $kupMap->kupciPoTipu($idTip, true);
    	
		$this->_helper->layout->disableLayout ();
		$this->_helper->viewRenderer->setNoRender ();


		require_once '../library/phpexcel/PHPExcel/IOFactory.php';
		require_once '../library/phpexcel/PHPExcel.php';

		$idIzvrsitelj = $request->getParam ( 'id_izvrsitelj', null );
			
		// popuna postojeceg dokumenta
		$styleArray = array (
				'borders' => array (
						'allborders' => array (
								'style' => PHPExcel_Style_Border::BORDER_THIN
						)
				),
				'alignment' => array (
						'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
						'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER
				),
				'font' => array (
						'size' => 12
				)
		);
		$styleArray2 = array(
				'borders' => array(
						'allborders' => array(
								'style' => PHPExcel_Style_Border::BORDER_THIN
						)
				),
				'alignment' => array(
						'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
						'vertical'=> PHPExcel_Style_Alignment::VERTICAL_CENTER
				),
				'font'=>array('size'=>10),
				'fill' => array(
						'type' => PHPExcel_Style_Fill::FILL_SOLID,
						'color' => array('rgb' => 'FABF8F')//bledo narandzasta
				)
		);
		
		$objPHPExcel = new PHPExcel ();
		$objPHPExcel->getActiveSheet()->getPageSetup()->setFitToHeight(0);

		$objPHPExcel->getProperties ()->setTitle ( "Spisak kupaca" )->setSubject ( "Spisak-kupaca" )->setCategory ( "Excel-izvestaji" );
		$sheet = $objPHPExcel->setActiveSheetIndex ( 0 );
		$objPHPExcel->getActiveSheet()->getPageSetup()->setOrientation(PHPExcel_Worksheet_PageSetup::ORIENTATION_LANDSCAPE);
		$objPHPExcel->getActiveSheet()->getPageSetup()->setPaperSize(PHPExcel_Worksheet_PageSetup::PAPERSIZE_A4);
		$sheet->setTitle ( 'Spisak kupaca' );
		
		
		$sheet->setCellValue('A1','id_kupca');
		$sheet->getStyle('A1')->applyFromArray($styleArray2);
		$sheet->getStyle('A1')->getFont()->setBold(true);
		$sheet->getStyle("A1")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$sheet->getStyle("A1")->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);


		$sheet->setCellValue('B1','kupac');
		$sheet->getStyle('B1')->applyFromArray($styleArray2);
		$sheet->getStyle("B1")->getAlignment()->setWrapText(true);
		$sheet->getStyle('B1')->getFont()->setBold(true);

		$sheet->setCellValue('C1','broj obrazaca za naplatu');
		$sheet->getStyle('C1')->applyFromArray($styleArray2);
		$sheet->getStyle("C1")->getAlignment()->setWrapText(true);
		$sheet->getStyle('C1')->getFont()->setBold(true);

		$sheet->setCellValue('D1','uvoz predmeta po ceni od 7 dinara');
		$sheet->getStyle('D1')->applyFromArray($styleArray2);
		$sheet->getStyle("D1")->getAlignment()->setWrapText(true);
		$sheet->getStyle('D1')->getFont()->setBold(true);

		$sheet->setCellValue('E1',"uvoz predmeta po ceni od 15 dinara");
		$sheet->getStyle("E1")->getAlignment()->setWrapText(true);
		$sheet->getStyle('E1')->getFont()->setBold(true);
		$sheet->getStyle('E1')->applyFromArray($styleArray2);
		
		$sheet->setCellValue('F1',"uspesno odradjen uvoz");
		$sheet->getStyle("F1")->getAlignment()->setWrapText(true);
		$sheet->getStyle('F1')->getFont()->setBold(true);
		$sheet->getStyle('F1')->applyFromArray($styleArray2);
		
		foreach ($kupci as $red=>$kup){
			$sheet->setCellValue('A'.($red+2),$kup['id']);
			$sheet->getStyle('A'.($red+2))->applyFromArray($styleArray);
			$sheet->getStyle("A".($red+2))->getAlignment()->setWrapText(true);
	
	
			$sheet->setCellValue('B'.($red+2),$kup['value']);
			$sheet->getStyle('B'.($red+2))->applyFromArray($styleArray);
			$sheet->getStyle("B".($red+2))->getAlignment()->setWrapText(true);
			
			$sheet->setCellValue('C'.($red+2),'');
			$sheet->getStyle('C'.($red+2))->applyFromArray($styleArray);
			$sheet->getStyle("C".($red+2))->getAlignment()->setWrapText(true);
	
	
			$sheet->setCellValue('D'.($red+2),'');
			$sheet->getStyle('D'.($red+2))->applyFromArray($styleArray);
			$sheet->getStyle("D".($red+2))->getAlignment()->setWrapText(true);
			
			$sheet->setCellValue('E'.($red+2),'');
			$sheet->getStyle('E'.($red+2))->applyFromArray($styleArray);
			$sheet->getStyle("E".($red+2))->getAlignment()->setWrapText(true);
	
	
			$sheet->setCellValue('F'.($red+2),'');
			$sheet->getStyle('F'.($red+2))->applyFromArray($styleArray);
			$sheet->getStyle("F".($red+2))->getAlignment()->setWrapText(true);
		}
		
		$sheet->getColumnDimension('A')->setWidth(10);
		$sheet->getColumnDimension('B')->setWidth(50);
		$sheet->getColumnDimension('C')->setWidth(15);
		$sheet->getColumnDimension('D')->setWidth(15);
		$sheet->getColumnDimension('E')->setWidth(15);
		$sheet->getColumnDimension('F')->setWidth(15);

		header ( 'Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
		header ( 'Content-Disposition: attachment;filename="Spisak-kupaca.xlsx"' );
		header ( 'Cache-Control: max-age=0' );

			
		$objWriter = PHPExcel_IOFactory::createWriter ( $objPHPExcel, 'Excel2007' );
		$objWriter->save ( 'php://output' );

    }
    
    public function ucitavanjeStavkiAction(){
    	$request = $this->_request;
    	$uspeh = $request->getParam('uspeh', null);
    	$this->view->uspeh = $uspeh;
    }
    
    public function uveziStavkeAction(){
    	$this->_helper->layout->disableLayout();
    	$this->_helper->viewRenderer->setNoRender();
    	$request = $this->_request;
    	$config = new Zend_Config_Ini ( APPLICATION_PATH . '/configs/config.ini', APPLICATION_ENV );
    	
    	$link = APPLICATION_PATH . "/../public/kreiranidoc/";
			
		$adapter = new Zend_File_Transfer_Adapter_Http ();
		$adapter->setDestination ( $link );
		$inputFile = $adapter->getFileName ( 'file', true );
		$adapter->receive();
		
		$mesec = $request->getParam('mesec');
		$godina = $request->getParam('godina');
		
		if ($adapter->isReceived()) {
			require_once '../library/phpexcel/PHPExcel/IOFactory.php';
			require_once '../library/phpexcel/PHPExcel.php';
			
			$inputFileType = PHPExcel_IOFactory::identify($inputFile);
		    $objReader = PHPExcel_IOFactory::createReader($inputFileType);
		    $objReader = $objReader->load($inputFile);
			
		    
		    $sheet = $objReader->getSheet(0); 
			$highestRow = $sheet->getHighestRow(); 
			$highestColumn = $sheet->getHighestColumn();
			
			$podaci = array();
			for ($row = 2; $row <= $highestRow; $row++){ 
				$podaci[$row-2]['id'] = $sheet->getCell('A'.$row)->getValue();
				$podaci[$row-2]['usluge'][]['obrazci'] = $sheet->getCell('C'.$row)->getCalculatedValue();
				$podaci[$row-2]['usluge'][]['uvoz1'] = $sheet->getCell('D'.$row)->getCalculatedValue();
				$podaci[$row-2]['usluge'][]['uvoz2'] = $sheet->getCell('E'.$row)->getCalculatedValue();
			}
			
			
			$crveno = array ('fill' => array ('type' => PHPExcel_Style_Fill::FILL_SOLID, 'startcolor' => array ('rgb' => 'db0000'), 'endcolor' => array ('argb' => 'db0000')));
			$zeleno = array ('fill' => array ('type' => PHPExcel_Style_Fill::FILL_SOLID, 'startcolor' => array ('rgb' => '00db5f'), 'endcolor' => array ('argb' => '00db5f')));
			
			
			$kupMap = new Mapperext_Kupci();
    		$kupci = $kupMap->kupciPoTipu(2, true);
    		$kupciIds = array();
    		foreach ($kupci as $k)
    			$kupciIds[] = $k['id'];
    		
	    	$godinaDo = $godina;
	    	
	    	
	    	switch ($mesec) {
	    			case 1 : $mesecSlova = 'DECEMBAR'; $mesecSlovaDo = 'JANUAR'; $mesecOd = 'NOVEMBAR'; 
	    			break;
	    			case 2 : $mesecSlova = 'JANUAR'; $mesecSlovaDo = 'FEBRUAR'; $mesecOd = 'DECEMBAR';
	    			break;
	    			case 3 : $mesecSlova = 'FEBRUAR'; $mesecSlovaDo = 'MART'; $mesecOd = 'JANUAR';
	    			break;
	    			case 4 : $mesecSlova = 'MART'; $mesecSlovaDo = 'APRIL'; $mesecOd = 'FEBRUAR';
	    			break;
	    			case 5 : $mesecSlova = 'APRIL'; $mesecSlovaDo = 'MAJ'; $mesecOd = 'MART';
	    			break;
	    			case 6 : $mesecSlova = 'MAJ'; $mesecSlovaDo = 'JUN'; $mesecOd = 'APRIL';
	    			break;
	    			case 7 : $mesecSlova = 'JUN'; $mesecSlovaDo = 'JUL'; $mesecOd = 'MAJ';
	    			break;
	    			case 8 : $mesecSlova = 'JUL'; $mesecSlovaDo = 'AVGUST'; $mesecOd = 'JUN';
	    			break;
	    			case 9 : $mesecSlova = 'AVGUST'; $mesecSlovaDo = 'SEPTEMBAR'; $mesecOd = 'JUL';
	    			break;
	    			case 10 : $mesecSlova = 'SEPTEMBAR'; $mesecSlovaDo = 'OKTOBAR'; $mesecOd = 'AVGUST';
	    			break;
	    			case 11 : $mesecSlova = 'OKTOBAR'; $mesecSlovaDo = 'NOVEMBAR'; $mesecOd = 'SEPTEMBAR';
	    			break;
	    			case 12 : $mesecSlova = 'NOVEMBAR'; $mesecSlovaDo = 'DECEMBAR'; $mesecOd = 'OKTOBAR';
	    			break;
	    	}
	    	
// 	    	if ($mesec != '10' || $mesec != '11' || $mesec != '12')
// 	    		$mesec = '0'.$mesec;
	    	
	    	if ($mesec == 1){
				$godinaOd = $godina - 1;
	    	}
	    	else 
	    		$godinaOd = $godina;
	    	
    		
			foreach ($podaci as $rw=>$pod){
				if (!in_array($pod['id'], $kupciIds)) continue;
				$isSetToYes = false;
				foreach ($pod['usluge'] as $u){
	
					if (isset($u['obrazci']) && $u['obrazci']){
						$rnmap = new Mapperext_Racuni();
		    			$racuni = $rnmap->vratiRacunZaKupca($pod['id'], $mesec, $godina);
// 		    			var_dump($racuni); exit;
		    			if ($racuni){
			    			$psModel = new Application_Model_RacunStavka();
			    			$psModel->setIdUsluga($config->idusluga->obrasci);
			    			$psModel->setKolicina($u['obrazci']);
			    			$psModel->setCenaJm($config->cena->obrazac);
			    			$psModel->setIdValuta($config->valuta->obrazac);
			    			$psModel->setIdRacun($racuni['id']);
			    			$psModel->setUnetoDana(date('Y-m-d'));
			    			$psModel->setPdv($config->stopaPdv);
			    			$psModel->setUkupno($config->cena->obrazac * $u['obrazci']*$racuni['kurs']);
			    			$psModel->setUkupnoSaPdv($config->cena->obrazac * $u['obrazci']*$config->pdvSa*$racuni['kurs']);
	
			    			$uslugaModel = new Application_Model_Usluge();
	    					$uslugaModel->find($config->idusluga->obrasci);
			    			$psModel->setNaIme(str_replace(array('${mesec_slovima}','${godina}','${mesec_slovima_od}','${godina_od}','${mesec_slovima_do}','${godina_do}'), 
								array($mesecSlova, $godinaOd, $mesecOd, $godinaOd, $mesecSlova, $godinaDo), $uslugaModel->getNaziv()));
			    			$psModel->save();
			    			$sheet->setCellValue('F'.($rw+2),'DA');
			    			$isSetToYes = true;
			    			$sheet->getStyle ( "A".($rw+2).":F".($rw+2))->applyFromArray ( $zeleno );
		    			}
					}
					elseif (isset($u['uvoz1']) && $u['uvoz1']){
						$rnmap = new Mapperext_Racuni();
		    			$racuni = $rnmap->vratiRacunZaKupca($pod['id'], $mesec, $godina);
		    			if ($racuni){
							$psModel = new Application_Model_RacunStavka();
			    			$psModel->setIdUsluga($config->idusluga->uvoz);
			    			$psModel->setKolicina($u['uvoz1']);
			    			$psModel->setCenaJm($config->cena->uvoz1);
			    			$psModel->setIdValuta($config->valuta->uvoz);
			    			$psModel->setIdRacun($racuni['id']);
			    			$psModel->setUnetoDana(date('Y-m-d'));
			    			$psModel->setPdv($config->stopaPdv);
			    			$psModel->setUkupno($config->cena->uvoz1 * $u['uvoz1']);
			    			$psModel->setUkupnoSaPdv($config->cena->uvoz1 * $u['uvoz1']*$config->pdvSa);
			    			
			    			$uslugaModel = new Application_Model_Usluge();
	    					$uslugaModel->find($config->idusluga->uvoz);
			    			$psModel->setNaIme(str_replace(array('${mesec_slovima}','${godina}','${mesec_slovima_od}','${godina_od}','${mesec_slovima_do}','${godina_do}'), 
								array($mesecSlova, $godinaOd, $mesecOd, $godinaOd, $mesecSlova, $godinaDo), $uslugaModel->getNaziv()));
			    			$psModel->save();
			    			$sheet->setCellValue('F'.($rw+2),'DA');
			    			$isSetToYes = true;
			    			$sheet->getStyle ( "A".($rw+2).":F".($rw+2))->applyFromArray ( $zeleno );
		    			}
					}
					elseif (isset($u['uvoz2']) && $u['uvoz2']){
						$rnmap = new Mapperext_Racuni();
		    			$racuni = $rnmap->vratiRacunZaKupca($pod['id'], $mesec, $godina);
		    			if ($racuni){
							$psModel = new Application_Model_RacunStavka();
			    			$psModel->setIdUsluga($config->idusluga->uvoz);
			    			$psModel->setKolicina($u['uvoz2']);
			    			$psModel->setCenaJm($config->cena->uvoz2);
			    			$psModel->setIdValuta($config->valuta->uvoz);
			    			$psModel->setIdRacun($racuni['id']);
			    			$psModel->setUnetoDana(date('Y-m-d'));
			    			$psModel->setPdv($config->stopaPdv);
			    			$psModel->setUkupno($config->cena->uvoz2 * $u['uvoz2']);
			    			$psModel->setUkupnoSaPdv($config->cena->uvoz2 * $u['uvoz2']*$config->pdvSa);
			    		
			    			$uslugaModel = new Application_Model_Usluge();
	    					$uslugaModel->find($config->idusluga->uvoz);
			    			$psModel->setNaIme(str_replace(array('${mesec_slovima}','${godina}','${mesec_slovima_od}','${godina_od}','${mesec_slovima_do}','${godina_do}'), 
								array($mesecSlova, $godinaOd, $mesecOd, $godinaOd, $mesecSlova, $godinaDo), $uslugaModel->getNaziv()));
			    			$psModel->save();
			    			$sheet->setCellValue('F'.($rw+2),'DA');
			    			$isSetToYes = true;
			    			$sheet->getStyle ( "A".($rw+2).":F".($rw+2))->applyFromArray ( $zeleno );
		    			}
					}
					elseif(!$isSetToYes) {
						$sheet->setCellValue('F'.($rw+2),'NE');
						$sheet->getStyle ( "A".($rw+2).":F".($rw+2))->applyFromArray ( $crveno );
					}
					
				}
			}
			
			$objReader->setActiveSheetIndex ( 0 );
			header ( 'Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet' );
			header ( 'Content-Disposition: attachment;filename="uvoz_stavki_racuna.xlsx"' );
			header ( 'Cache-Control: max-age=0' );
			$objWriter = PHPExcel_IOFactory::createWriter ( $objReader, $inputFileType );
			$objWriter->save ( 'php://output' );
			
			unlink($inputFile); 
			
		}
		
    }


}

