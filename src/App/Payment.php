<?php

namespace App;

use \cli\Arguments;

class Payment{
	private $fileHandler;
	
	private $timestamp;

	
	public function __construct(){
		$this->timestamp = time();
    }
	
	public function generate(){
		$fileName = 'payments.csv';
		
		$file = $_SERVER['PWD'] . '/' . $fileName;
		
		$headers = ['Month', 'Salary', 'Bonus'];
		$contents = null;
		
		foreach ($this->getMonths() as $month){
			$salaryDate = $this->getSalaryDate($month);
			$bonusDate = $this->getBonusDate($month);
			$contents[] = [date('F', $salaryDate), date('d-m-Y', $salaryDate), date('d-m-Y', $bonusDate)];
		}
		
		$this->writeCsvFile($file, $headers, $contents);
	
		return $fileName;
	}
	
	private function getMonths(){
		$months = null;

        for ($month = date('n', $this->timestamp); $month<=12; $month++) {
            $months[] = $month;
        }
		
		return $months;
    }
	
	private function getSalaryDate($month){
        for ($day = 0; $day < 3; $day++){
            $lastDay = mktime(1, 1, 1, $month+1, -$day);

            if(!$this->isWeekend($lastDay)){
                break;
            }
        }

        return $lastDay;
    }
	
	private function getBonusDate($month, $day = 15){
        $lastDay = mktime(1, 1, 1, $month, $day);
        
		if(!$this->isWeekend($lastDay)) {
            return $lastDay;
        }

        return strtotime('next wednesday', $lastDay);
    }

	private function isWeekend(){
        if(date('N', $this->timestamp) > 5){
            return true;
        }

        return false;
    }
	
	public function writeCsvFile($file, $headers, $contents){
		$fileHandler = fopen($file, "w");
	
		fputcsv($fileHandler, $headers);
		
		foreach($contents as $content){
			fputcsv($fileHandler, $content);
		}
		
		fclose($fileHandler);
	}
}

?>