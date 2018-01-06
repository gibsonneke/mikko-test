<?php

namespace App;

/**
 * Payment is used to generate the salary and bonus payment dates for the remaining months of the current year
 */
class Payment{
	private $filename;
	private $fileHandler;
	private $timestamp;
	
	/**
     * Constructor
     */
	public function __construct($filename){
		$this->filename = $filename;
		$this->timestamp = time();
    }
	
	/**
     * Generate the csv file
     */
	public function generate(){
		$file = $_SERVER['PWD'] . '/' . $this->filename;
		
		$headers = ['Month', 'Salary', 'Bonus'];
		$contents = [];
		
		foreach ($this->getMonths() as $month){
			$salaryDate = $this->getSalaryDate($month);
			$bonusDate = $this->getBonusDate($month);
			$contents[] = [date('F', $salaryDate), date('d-m-Y', $salaryDate), date('d-m-Y', $bonusDate)];
		}
		
		$this->writeCsvFile($file, $headers, $contents);
	
		return $this->filename;
	}
	
	/**
     * Get the remaining months of the current year
     */
	private function getMonths(){
		$months = [];

        for ($month = date('n', $this->timestamp); $month<=12; $month++) {
            $months[] = $month;
        }
		
		return $months;
    }
	
	/**
     * Get the salary date
     */
	private function getSalaryDate($month){
		for ($day = 0; $day > -3; $day--) {
			$lastDay = mktime(0, 0, 0, $month+1, $day); 
			
			if(!$this->isWeekend($lastDay)){
				break;
			}
		}
		
		return $lastDay;
	}

	/**
     * Get the bonus date
     */ 
	private function getBonusDate($month, $day = 15){
        $middleDay = mktime(0, 0, 0, $month, $day);
        
		if(!$this->isWeekend($middleDay)) {
            return $middleDay;
        }

        return strtotime('next wednesday', $middleDay);
    }

	/**
     * Check if a give date is in the weekend or not
     */
	private function isWeekend($timestamp){
        if(date('N', $timestamp) > 5){
            return true;
        }

        return false;
    }
	
	/**
     * Open the file and write the actual content to the file
     */
	public function writeCsvFile($file, $headers, $contents){
		$fileHandler = fopen($file, "w");
		
		if(fputcsv($fileHandler, $headers) === false){
            throw new \Exception('Unable to write to file ' . $this->filename);
        }

		foreach($contents as $content){
			if(fputcsv($fileHandler, $content) === false){
				throw new \Exception('Unable to write to file ' . $this->filename);
			}
		}
		
		fclose($fileHandler);
	}
}

?>