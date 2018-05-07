<?php
    global $global_view ;
    
    echo '<?xml version="1.0" encoding="UTF-8" ?>' . "\n" ;
    
?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr">
<head>
    <meta charset="UTF-8"/>
    <meta http-equiv="X-UA-Compatible" content="IE=9" />
    <link
        rel="shortcut icon"
        type="image/x-icon"
        href="/style/favicon.ico"/>
    
    <link
        href='http://fonts.googleapis.com/css?family=Special+Elite'
        rel='stylesheet'
        type='text/css'
        />
        
    <title><?php echo $global_view->getTplTitle() ; ?></title>

    <!-- CSS for layout -->

    <link rel="stylesheet"
              media="screen"
              href="/style/css/base.css"
              type="text/css" />

    <!-- <link rel="stylesheet"
          media="screen and (min-width: 960px)"
          href="/style/template/large.css"
          type="text/css" />	 --> 
    
    <link rel="stylesheet" media="screen" href="/style/css/thread.css" type="text/css" />
    <link rel="stylesheet" media="screen" href="/style/css/form.css" type="text/css" />
    <link rel="stylesheet" media="screen" href="/style/css/table.css" type="text/css" />
    
    <!-- Javascript files -->
    <script src="/js/tinymce/tinymce.min.js"></script>
    <script src="/js/main.js"></script>
    
    <?php echo $global_view->getTplMeta() ; ?>

</head>
    <body>
        
        <div id="Header">
            <div class="wrapper">
            
                <div id="Title">
                    <div id="Luchronia"><a href="/"><span>Luchronia</span></a></div>
                    <div id="Session"><?php echo $global_view->getTplSession() ;  ?></div>
                </div>

                <div id="Nav">
                    <div id="GameMenu"><?php echo $global_view->getTplGameMenu() ;  ?></div>
                    <div id="SiteMenu"><?php echo $global_view->getTplSiteMenu() ;  ?></div>
                </div>

                <div class="clear"></div>
            </div>
                
        </div>
        
        <div id="Main">
            
            <?php echo $global_view->getTplContent() ; ?>
        </div>

        <div id="Footer"><?php echo $global_view->getTplFooter() ;   ?></div>
    </body>
</html>
