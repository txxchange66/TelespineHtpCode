/* Oswestery - Example */

function Survey(survey){
	this.interval=1;
	this.scores = survey /* as Array[] */;
	this.calculateScores = function(){
		var len = this.scores.length;
		var finalScore = 0;
		for(var i=0; i<len; i++){
			var score = new ts_Score();
			var vals = this.scores[i].split("'");
			score.val = vals[0];
			score.factorial = vals[1];
			score.weight = vals[2];
			finalScore += score.calulate();
		}
		return Math.round(finalScore*100/100);
	}
}
function ts_Score(){
	this.id="";
	this.val=1.0;
	this.factorial=0.3;
	this.weight=1.0;
	this.calculate = function(){
		return Math.round((this.val/this.weight * 100) + this.factorial);
	}
}
var survey_props = {
	survey: ['2:1:2','2:1.1:1.3','1:1:2.4','2:1:2','2:1.1:1.3','1:1:2.4','2:1:2','2:1.1:1.3','1:1:2.4','2:1.1:1.3']	
}