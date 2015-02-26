<?php

use Codacy\Coverage\Parser\CloverParser;

class CloverParserTest extends PHPUnit_Framework_TestCase
{


    public function testThrowsExceptionOnWrongPath()
    {
        $this->setExpectedException('InvalidArgumentException');
        $p = new CloverParser("/home/foo/bar/baz/m.xml");
    }

    /**
     * Testing against the clover coverage report 'tests/res/clover/clover.xml'
     */
    public function testCanParseCloverXmlWithoutProject()
    {
		$this->_canParseClover('tests/res/clover/clover.xml', "/home/jacke/Desktop/codacy-php");
	}

    /**
     * Testing against the clover coverage report 'tests/res/clover/clover.xml'
     * The test had been made in /home/jacke/Desktop/codacy-php so we need to pass this
     * as 2nd (ootional) parameter. Otherwise the filename will not be correct and test
     * the would file on other machines or in other directories.
     */
	public function testCanParseCloverXmlWithProject()
    {
		$this->_canParseClover('tests/res/clover/clover_without_packages.xml', "/home/jacke/Desktop/codacy-php");
	}

    private function _canParseClover($path, $rootDir)
    {
		$p = new CloverParser($path, $rootDir);
		$report = $p->makeReport();
		$this->assertEquals(38, $report->getTotal());
		$this->assertEquals(5, sizeof($report->getFileReports()));
		
		$parserFileReport = $report->getFileReports()[0];
		$coverageReportFileReport = $report->getFileReports()[1];
		
		$this->assertEquals(33, $parserFileReport->getTotal());
		$this->assertEquals(33, $coverageReportFileReport->getTotal());
		
		$parserFileName = $parserFileReport->getFileName();	
		
		$reportFileName = $coverageReportFileReport->getFileName();

        $lineCoverage = $report->getFileReports()[1]->getLineCoverage();
        $expLineCoverage = array(11 => 1, 12 => 1, 13 => 1, 16 => 1);
        $this->assertEquals($lineCoverage, $expLineCoverage);
				
		$this->assertEquals("src/Codacy/Coverage/Parser/Parser.php", $parserFileName);
		$this->assertEquals("src/Codacy/Coverage/Report/CoverageReport.php", $reportFileName);
	}
}