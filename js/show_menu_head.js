<!--

function mmLoadMenus(showtelespinereportsmenuitem) {

  if (window.mm_menu_0623112859_0) return;

  window.mm_menu_0623112859_0_1 = new Menu("root",140,25,"Verdana, Arial, Helvetica, sans-serif",11,"#006699","#006699","#FFFFFF","#E9F8F8","left","middle",3,0,500,-5,7,true,true,true,0,false,false);
  window.mm_menu_0623112859_0_1.addMenuItem("Best&nbsp;Practices","location='index.php?action=bestPracticesHead'");
 /* window.mm_menu_0623112859_0_1.addMenuItem("Bulletin&nbsp;Board","location='index.php?action=bulletinBoardHead'");*/
  window.mm_menu_0623112859_0_1.addMenuItem("Company&nbsp;Details","location='index.php?action=websiteHead'");
  window.mm_menu_0623112859_0_1.addMenuItem("Referral&nbsp;Reports","location='index.php?action=txReferralReport'");
  window.mm_menu_0623112859_0_1.fontWeight="bold";
  window.mm_menu_0623112859_0_1.hideOnMouseOut=true;
  window.mm_menu_0623112859_0_1.bgColor='#FFFFFF';
  window.mm_menu_0623112859_0_1.menuBorder=1;
  window.mm_menu_0623112859_0_1.menuLiteBgColor='#FFFFFF';
  window.mm_menu_0623112859_0_1.menuBorderBgColor='#CCCCCC';


  window.mm_menu_0623112859_0_2 = new Menu("patient",140,25,"Verdana, Arial, Helvetica, sans-serif",11,"#006699","#006699","#FFFFFF","#E9F8F8","left","middle",3,0,500,-5,7,true,true,true,0,false,false);
  window.mm_menu_0623112859_0_2.addMenuItem("Patient&nbsp;List","location='index.php?action=patientListingHead'");
  window.mm_menu_0623112859_0_2.addMenuItem("Upload&nbsp;New&nbsp;List","location='index.php?action=uploadPatientListHead'");
  window.mm_menu_0623112859_0_2.fontWeight="bold";
  window.mm_menu_0623112859_0_2.hideOnMouseOut=true;
  window.mm_menu_0623112859_0_2.bgColor='#FFFFFF';
  window.mm_menu_0623112859_0_2.menuBorder=1;
  window.mm_menu_0623112859_0_2.menuLiteBgColor='#FFFFFF';
  window.mm_menu_0623112859_0_2.menuBorderBgColor='#CCCCCC';
  
  window.mm_menu_0623112859_0_3 = new Menu("report",120,25,"Verdana, Arial, Helvetica, sans-serif",11,"#006699","#006699","#FFFFFF","#E9F8F8","left","middle",3,0,500,-5,7,true,true,true,0,false,false);
  window.mm_menu_0623112859_0_3.addMenuItem("E&minus;Health&nbsp;Service","location='index.php?action=healthServiceReport'");
  if(showtelespinereportsmenuitem=="yes"){
    window.mm_menu_0623112859_0_3.addMenuItem("Telespine&nbsp;Reports","location='index.php?action=telespinereports'");
  }
  window.mm_menu_0623112859_0_3.fontWeight="bold";
  window.mm_menu_0623112859_0_3.hideOnMouseOut=true;
  window.mm_menu_0623112859_0_3.bgColor='#FFFFFF';
  window.mm_menu_0623112859_0_3.menuBorder=1;
  window.mm_menu_0623112859_0_3.menuLiteBgColor='#FFFFFF';
  window.mm_menu_0623112859_0_3.menuBorderBgColor='#CCCCCC';
  
  window.mm_menu_0623112859_0_4 = new Menu("account_list",120,25,"Verdana, Arial, Helvetica, sans-serif",11,"#006699","#006699","#FFFFFF","#E9F8F8","left","middle",3,0,500,-5,7,true,true,true,0,false,false);
  window.mm_menu_0623112859_0_4.addMenuItem("Clinic&nbsp;List","location='"+locationRedirect+"'");
  window.mm_menu_0623112859_0_4.addMenuItem("Provider&nbsp;List","location='"+userloaction+"'");
  window.mm_menu_0623112859_0_4.addMenuItem("Patient&nbsp;List","location='index.php?action=patientListingHead'");
  window.mm_menu_0623112859_0_4.addMenuItem("New&nbsp;Patient&nbsp;List","location='index.php?action=uploadPatientListHead'");
  window.mm_menu_0623112859_0_4.fontWeight="bold";
  window.mm_menu_0623112859_0_4.hideOnMouseOut=true;
  window.mm_menu_0623112859_0_4.bgColor='#FFFFFF';
  window.mm_menu_0623112859_0_4.menuBorder=1;
  window.mm_menu_0623112859_0_4.menuLiteBgColor='#FFFFFF';
  window.mm_menu_0623112859_0_4.menuBorderBgColor='#CCCCCC';
  window.mm_menu_0623112859_0_5 = new Menu("Customize",110,25,"Verdana, Arial, Helvetica, sans-serif",11,"#006699","#006699","#FFFFFF","#E9F8F8","left","middle",3,0,500,-5,7,true,true,true,0,false,false);
  //if(document.getElementById('showsoap').value==1){
  window.mm_menu_0623112859_0_5.addMenuItem("SOAP","location='index.php?action=account_soap_services'");
  //}
  window.mm_menu_0623112859_0_5.addMenuItem("Billing","location='index.php?action=account_bill_services'");
  window.mm_menu_0623112859_0_5.addMenuItem("EHS&nbsp;Intro&nbsp;Email","location='index.php?action=EHS_introductory_email'");
  window.mm_menu_0623112859_0_5.fontWeight="bold";
  window.mm_menu_0623112859_0_5.hideOnMouseOut=true;
  window.mm_menu_0623112859_0_5.bgColor='#FFFFFF';
  window.mm_menu_0623112859_0_5.menuBorder=1;
  window.mm_menu_0623112859_0_5.menuLiteBgColor='#FFFFFF';
  window.mm_menu_0623112859_0_5.menuBorderBgColor='#CCCCCC';
  
  
  window.mm_menu_0623112859_0_1.writeMenus();
  window.mm_menu_0623112859_0_2.writeMenus();
  window.mm_menu_0623112859_0_3.writeMenus();
  window.mm_menu_0623112859_0_4.writeMenus();
  window.mm_menu_0623112859_0_5.writeMenus();
} // mmLoadMenus()
  // mmLoadMenus()	

//-->
