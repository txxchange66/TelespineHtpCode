<script type="text/javascript">
    App.user.stats.goals = [<!completedgoals>, <!totalgoals>];
    App.user.stats.logins = <!totallogins>;
</script>

<!-- Stats: Goals, Logins -->

<div class="box" id="goalsAndStatistics">
    <div class="box-header">
        <h2><i class="fa fa-bar-chart-o"></i>Goals &amp; Statistics </h2>
        <div class="box-icon"> 
                <a href="index.php#" class="btn-setting" onClick="App.showHelp('goalsAndStatistics')"><i class="fa  fa-info"></i></a>
                <a href="index.php#" class="btn-minimize"><i class="fa fa-chevron-up"></i></a>
        </div>
    </div>
    <div class="box-content" style="min-height:400px">
        <div class="circleStats">
            <div class="col-md-6 col-sm-6 col-xs-6 col-lg-6">
                <div class="circleStatsItem blue" id="GoalsInterface">
                        <i class="fa fa-thumbs-up"></i>
                        <span class="plus">+</span>
                        <span class="percent">%</span>
                        <input id="MyGoals" type="text" value="<!goalspercentage>" class="circleChart" />
                </div>
                <div id="goals" class="clearfix text-center" style="color:#777777; letter-spacing:1px; text-transform:capitalize;padding:4px;font-weight:300;cursor:pointer;" rel="popover" data-content="Having goals, and more importantly completing them, is a great medium for motivation. " data-placement="top" data-original-title="What is this for?">Goals Complete</div>
            </div>
            <div class="col-md-6 col-sm-6 col-xs-6 col-lg-6">
                <div id="MyLogins" class="circleStatsItem blue" style="padding-top:26px;font-size:46px;font-weight:300;">1</div>
                <div class="clearfix text-center" style="color:#777777; letter-spacing:1px; text-transform:capitalize;padding:4px;font-weight:300;cursor:pointer;" rel="popover" data-placement="top" data-content="The number of Logins to this application help keep awareness of frequency. This is meant to enourage you to keep on track!" data-original-title="What is this for?">Logins</div>
            </div>
        </div>
        <div class="clearfix"></div>
        <hr>
        <form style="margin-top:8px;" onSubmit="App.addGoal();return false;">
            <div class="form-group">
                <div class="controls">
                  <div class="input-group">
                      <input id="AddGoal" name="AddGoal" class="form-control" size="16" type="text" placeholder="Add a Goal" style="height:40px !important;border-radius:8px 0px 0px 8px;color:#575757 !important;box-shadow:inset 0px 0px 6px rgba(187,187,187,0.5)">
                      <span class="input-group-btn"><button class="btn-primary" type="button" onClick="App.addGoal();$('#AddGoal').focus();" style="height:40px !important;border:none;border-radius:0px 8px 8px 0px;padding:10px 15px;outline: none;">+</button></span>
                  </div>
                </div>
            </div>
        </form>
        <div class="todo" style="border-radius:8px; margin:12px 0px;">
            <ul class="tasks goals" id="GoalsList" data-rel="tooltip">
                <!goals>
            </ul>
        </div>
        <div class="clearfix"></div>
    </div>
</div><!-- /Stats: Goals, Logins -->