<?php
define('FPDF_FONTPATH','font/');
require('fpdf.php');

class PDF extends FPDF
{
//Page header
function Header()
{
   $pa=$_SESSION['header_info'];
    
   
    //Title
        
        //Position at 1.5 cm from top
        $this->SetY(2);
        //Arial italic 8
        $this->SetFont('Arial','',8);
        //Page number
        $this->Cell(0,10,'Page '.$this->PageNo().'',0,1,'R');
        
        //Arial bold 15
        $this->SetFont('Arial','B',9);
        
        $this->Cell(26,10,"Patient's Name: " ,0,0,'L',0);
        $this->SetFont('Arial','',9);
    	$this->Cell(115,10,$pa['pname'],0,0,'L',0);
        $this->SetFont('Arial','B',9);
    	$this->Cell(27,10,"Date Of Birth: ",0,0,'L');
        $this->SetFont('Arial','',9);
        $this->Cell(70,10,$pa['pdob'],0,1,'L');
        //Line break
        $this->SetFont('Arial','B',9);
    	$this->Cell(26,10,"Doctor's Name: ",0,0,'L');
        $this->SetFont('Arial','',9);
        $this->Cell(115,10,$pa['dname'],0,0,'L');
        $this->SetFont('Arial','B',9);
    	$this->Cell(27,10,"Date Of Service: ",0,0,'L');
        $this->SetFont('Arial','',9);
    	$this->Cell(70,10,$pa['dos'],0,1,'L');
    	
        $this->SetFont('Arial','B',9);
    	$this->Cell(26,10,"Clinic Name: ",0,0,'L');
        $this->SetFont('Arial','',9);
    	$this->Cell(115,10,$pa['cname'],0,0,'L');
        $this->SetFont('Arial','B',9);	
    	$this->Cell(27,10,"Clinic Tel No.: ",0,0,'L');
        $this->SetFont('Arial','',9);
        $this->Cell(70,10,$pa['ctel'],0,1,'L');		
        
        $this->SetFont('Arial','B',9);
    	$this->Cell(26,10,"Clinic Address: ",0,0,'L');
        $this->SetFont('Arial','',9);
    	//$this->Cell(0,10,$pa['caddress'],0,1,'L');
        $this->MultiCell(0,10,html_entity_decode($pa['caddress'],ENT_QUOTES, "UTF-8"),'0','L');
        $this->Cell(0,15,'',0,1,'L');
    
    
}

//Page footer
function Footer()
{
    
}
}


?>