





function addOption(selectbox,text,value,checkedState )
{
                var optn = document.createElement("OPTION");
                optn.text = text;
                optn.value = value;
                
                if(checkedState==text) {
                                optn.selected = 'selected';
                }
                selectbox.options.add(optn);
}

function toggleState(t)
{
                //alert(t);
	
	if(document.getElementById('clinic_state')){
		//alert("dsfhkgb");
                
                if( document.getElementById('country').value=='US')
                {              
                                document.getElementById('clinic_state').options.length = 0
                                addOption(document.getElementById('clinic_state'),"Choose State...",'','');
                                usstateArray={AK : 'Alaska', AL : 'Alabama',AR : 'Arkansas', AZ : 'Arizona', CA : 'California', CO : 'Colorado', CT : 'Connecticut', DC : 'District of Columbia', DE : 'Delaware', FL : 'Florida', GA : 'Georgia', HI : 'Hawaii', IA : 'Iowa', ID : 'Idaho', IL : 'Illinois', IN : 'Indiana', KS : 'Kansas', KY : 'Kentucky', LA : 'Louisiana', MA : 'Massachusetts', MD : 'Maryland', ME : 'Maine', MI : 'Michigan', MN : 'Minnesota', MO : 'Missouri', MS : 'Mississippi', MT : 'Montana', NC : 'North Carolina', ND : 'North Dakota', NE : 'Nebraska', NH : 'New Hampshire', NJ : 'New Jersey', NM : 'New Mexico', NV : 'Nevada', NY : 'New York', OH : 'Ohio', OK : 'Oklahoma', OR : 'Oregon', PA : 'Pennsylvania', RI : 'Rhode Island', SC : 'South Carolina', SD : 'South Dakota', TN : 'Tennessee', TX : 'Texas', UT : 'Utah', VA : 'Virginia', VT : 'Vermont', WA : 'Washington', WI : 'Wisconsin', WV : 'West Virginia', WY : 'Wyoming'};
                                for(var prop in usstateArray){
                                                statevalue=usstateArray[prop];
                                                addOption(document.getElementById('clinic_state'),statevalue,prop,t );
                                }                                              
                }
                 else if( document.getElementById('country').value=='CAN')
                {              
                               
                                document.getElementById('clinic_state').options.length = 0
                                addOption(document.getElementById('clinic_state'),"Choose Province...",'','' );
                                canadastateArray={AB:'Alberta',BC:'British Columbia',MB:'Manitoba',NB:'New Brunswick',NL:'New Foundland',NT:'Northwest Territories',NS:'Nova Scotia',NU:'Nunavut',ON:'Ontario',PE:'Prince Edward Island',QC:'Quebec',SK:'Saskatchewan',YT:'Yukon Territories'};
                               // for(i=0;i<canadastateArray.length;i++) {
                               for(var prop in canadastateArray){
                                                statevalue=canadastateArray[prop];
                                                addOption(document.getElementById('clinic_state'),statevalue,prop, t);
                                }                                              
                                
                                
                }
                else
                {
                                document.getElementById('clinic_state').style.display='none';
                                
                }  
                
                
	}
	if(document.getElementById('state')){
		//alert("dsfhkgb");
                
                if( document.getElementById('country').value=='US')
                {              
                                document.getElementById('state').options.length = 0
                                addOption(document.getElementById('state'),"Choose State...",'','');
                                usstateArray={AK : 'Alaska', AL : 'Alabama',AR : 'Arkansas', AZ : 'Arizona', CA : 'California', CO : 'Colorado', CT : 'Connecticut', DC : 'District of Columbia', DE : 'Delaware', FL : 'Florida', GA : 'Georgia', HI : 'Hawaii', IA : 'Iowa', ID : 'Idaho', IL : 'Illinois', IN : 'Indiana', KS : 'Kansas', KY : 'Kentucky', LA : 'Louisiana', MA : 'Massachusetts', MD : 'Maryland', ME : 'Maine', MI : 'Michigan', MN : 'Minnesota', MO : 'Missouri', MS : 'Mississippi', MT : 'Montana', NC : 'North Carolina', ND : 'North Dakota', NE : 'Nebraska', NH : 'New Hampshire', NJ : 'New Jersey', NM : 'New Mexico', NV : 'Nevada', NY : 'New York', OH : 'Ohio', OK : 'Oklahoma', OR : 'Oregon', PA : 'Pennsylvania', RI : 'Rhode Island', SC : 'South Carolina', SD : 'South Dakota', TN : 'Tennessee', TX : 'Texas', UT : 'Utah', VA : 'Virginia', VT : 'Vermont', WA : 'Washington', WI : 'Wisconsin', WV : 'West Virginia', WY : 'Wyoming'};
                                for(var prop in usstateArray){
                                                statevalue=usstateArray[prop];
                                                addOption(document.getElementById('state'),statevalue,prop,t );
                                }                                              
                }
                 else if( document.getElementById('country').value=='CAN')
                {              
                               
                                document.getElementById('state').options.length = 0
                                addOption(document.getElementById('state'),"Choose Province...",'','' );
                                canadastateArray={AB:'Alberta',BC:'British Columbia',MB:'Manitoba',NB:'New Brunswick',NL:'New Foundland',NT:'Northwest Territories',NS:'Nova Scotia',NU:'Nunavut',ON:'Ontario',PE:'Prince Edward Island',QC:'Quebec',SK:'Saskatchewan',YT:'Yukon Territories'};
                               // for(i=0;i<canadastateArray.length;i++) {
                               for(var prop in canadastateArray){
                                                statevalue=canadastateArray[prop];
                                                addOption(document.getElementById('state'),statevalue,prop, t);
                                }                                              
                                
                                
                }
                else
                {
                                document.getElementById('state').style.display='none';
                                
                }  
                
                
	}
	
	
}

