<script type="text/javascript">
    App.user.oswestery.scores = <!oswestryscores>;
</script>

<!-- Functional Score -->
<div class="box" id="functionalScore">
    <div class="box-header">
        <h2><i class="fa fa-bar-chart-o"></i>Functional Score</h2>
        <div class="box-icon">  
            <a href="#" class="btn-setting" onClick="App.showHelp('functionalScore')"><i class="fa  fa-info"></i></a>
            <a href="#" class="btn-minimize"><i class="fa fa-chevron-up"></i></a> </div>
    </div>
    <div class="box-content" style="min-height:400px">
        <div class="col-lg-6 col-sm-6 col-xs-6 col-xxs-6">
            <div class="smallstat box-highlight text-center" style="margin-bottom:8px;clear:both;cursor:pointer;" rel="popover" data-content="The functional score is a way of identifying where you are in terms of back health. The lower the score the better. Scores are set by the answers you provide during surveys." data-placement="top" data-original-title="What is this?">
                <span style="font-size:36px;" id="currentFunctionalScore"></span>
            </div>
        </div>
        <div class="col-lg-6 col-sm-6 col-xs-6 col-xxs-6">
            <div class="smallstat red-highlight text-center" style="margin-bottom:8px;clear:both">
                <span style="font-size:36px" id="currectPain" rel="popover" data-content="The pain score is a way of identifying where you are in terms of back health as well. The lower the score the better. Scores are set by the answers you provide during surveys. There are four surveys during the eight week program." data-placement="top" data-original-title="What is this?"></span>
            </div>
        </div>
        <div class="col-lg-12 col-sm-12 col-xs-12 col-xxs-12">
            <div class="p-score-key"></div> <div class="f-label">Pain Level</div>
            <div class="f-score-key"></div> <div class="f-label">Functional Score</div>
            <div class="clearfix"></div>
            <div class="func-score">
                <div class="func-score-inner">
                    <div class="f-bar-box">
                        <div class="f-bar f-score f-score-1" style="top:100%"></div>
                        <div class="f-bar p-score p-score-1" style="top:100%"></div>
                        <span class="f-bar-1"></span>
                    </div>
                    <div class="f-bar-box">
                        <div class="f-bar f-score f-score-2" style="top:100%"></div>
                        <div class="f-bar p-score p-score-2" style="top:100%"></div>
                        <span class="f-bar-2"></span>
                    </div>
                    <div class="f-bar-box">
                        <div class="f-bar f-score f-score-3" style="top:100%"></div>
                        <div class="f-bar p-score p-score-3" style="top:100%"></div>
                        <span class="f-bar-3"></span>
                    </div>
                    <div class="f-bar-box">
                        <div class="f-bar f-score f-score-4" style="top:100%"></div>
                        <div class="f-bar p-score p-score-4" style="top:100%"></div>
                        <span class="f-bar-4"></span>
                    </div>
                </div>
            </div>
            <div class="clearfix"></div>
            <div>
                <div class="func-score-inner" style="padding-left:30px;">
                    <div class="f-bar-box-bottom">Survey 1</div>
                    <div class="f-bar-box-bottom">Survey 2</div>
                    <div class="f-bar-box-bottom">Survey 3</div>
                    <div class="f-bar-box-bottom">Survey 4</div>
                </div>
            </div>
        </div>
        <div class="clearfix"></div>
    </div>
</div>