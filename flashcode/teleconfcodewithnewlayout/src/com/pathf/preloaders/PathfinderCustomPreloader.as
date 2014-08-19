package com.pathf.preloaders
{
    import flash.display.DisplayObject;
    import flash.display.GradientType;
    import flash.display.Sprite;
    import flash.filters.DropShadowFilter;
    import flash.geom.Matrix;
    import flash.text.TextField;
    import flash.text.TextFormat;
    
    public class PathfinderCustomPreloader extends com.pathf.preloaders.PreloaderDisplayBase
    {
       // [Embed("Assets/Pathfinder_Logo_Blue.png") ]
       // [Bindable] public var Logo:Class;  

        private var t:TextField;
        private var tLabel:TextField;
        private var f:DropShadowFilter=new DropShadowFilter(2,45,0x000000,0.5)
        private var pathfLogo:DisplayObject;
        private var bar:Sprite=new Sprite();
        private var barFrame:Sprite;
        private var mainColor:uint=0x666666;
        
        public function PathfinderCustomPreloader()
        {
            super();
        }
        
        // This is called when the preloader has been created as a child on the stage.
        //  Put all real initialization here.
        override public function initialize():void
        {
            super.initialize();
            
            clear();  // clear here, rather than in draw(), to speed up the drawing
            
            var indent:int = 20;
            var height:int = 20;
            
            //creates all visual elements
            createAssets();
        }
        //this is our "animation" bit
        override protected function draw():void
        {
            t.text = int(_fractionLoaded*100).toString()+"%";
            tLabel.text = "Loading... ";
            //make objects below follow loading progress
            //positions are completely arbitrary
            //d tells us the x value of where the loading bar is at
            if(isNaN(_fractionLoaded) || _fractionLoaded.toString()=="Infinity")
            {
            	_fractionLoaded = 1;
            }
            var d:Number=barFrame.x + barFrame.width * _fractionLoaded
            t.x = d - t.width - 25;
            tLabel.x = d - tLabel.width - 35;
            //pathfLogo.x = d - pathfLogo.width;
            bar.graphics.beginFill(mainColor,1)
            bar.graphics.drawRoundRectComplex(0,0,bar.width * _fractionLoaded,15,12,0,0,12);
            bar.graphics.endFill();
        }
        
        protected function createAssets():void
        {
            //create the logo
           /*  pathfLogo = new Logo();
            pathfLogo.y = stageHeight/2 - pathfLogo.height*1.5;
            pathfLogo.filters = [f];
            addChild(pathfLogo); */
            
            //craate bar
            bar = new Sprite();
             bar.graphics.drawRoundRectComplex(0,0,400,15,12,0,0,12);
            bar.x = stageWidth/2 - bar.width/2;
            bar.y = stageHeight/2 - bar.height/2;
            bar.filters = [f];
            addChild(bar);
            
            //create bar frame
            barFrame = new Sprite();
            barFrame.graphics.lineStyle(2,0xFFFFFF,1)
            barFrame.graphics.drawRoundRectComplex(0,0,400,15,12,0,0,12);
            barFrame.graphics.endFill();
            barFrame.x = stageWidth/2 - barFrame.width/2;
            barFrame.y = stageHeight/2 - barFrame.height/2;
            barFrame.filters = [f];
            addChild(barFrame);
            
            //create text field to show percentage of loading
            t = new TextField()
            t.y = barFrame.y-27;
            t.filters=[f];
            addChild(t);
                        
            tLabel = new TextField()
            tLabel.y = barFrame.y-27;
            tLabel.filters=[f];
            addChild(tLabel);
            //we can format our text
            var s:TextFormat=new TextFormat("Verdana",null,0xFFFFFF,null,null,null,null,null,"right");
            t.defaultTextFormat=s;
            
            var sLabel:TextFormat=new TextFormat("Verdana",null,0xFFFFFF,null,null,null,null,null,"left");
            tLabel.defaultTextFormat=sLabel;
        }
        
        protected function clear():void
        {    
            // Draw gradient background
            var b:Sprite = new Sprite;
             var matrix:Matrix =  new Matrix();
            matrix.createGradientBox(stageWidth, stageHeight, Math.PI/2);   
            b.graphics.beginGradientFill(GradientType.LINEAR,   
                                        [mainColor, 0x000000],             
                                        [1,1],                           
                                        [0,255],
                                        matrix
                                        );
            b.graphics.drawRect(0, 0, stageWidth, stageHeight);
            b.graphics.endFill(); 
            addChild(b);
        }

    }        
}

