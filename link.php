<?php
/**
 * @author gazbond (gaz@gazbond.co.uk)
 * @copyright Copyright (c) 2014, gazbond.co.uk
 * @license http://opensource.org/licenses/MIT
 */
require_once 'lib/JShrink.php';

/* @var $jqueryUrl string */
$jqueryUrl="";
if(isset($_POST['jQueryUrl'])) $jqueryUrl=$_POST['jQueryUrl'];
/* @var $scraperJsUrl string */
$scraperJsUrl="";
if(isset($_POST['scraperJsUrl'])) $scraperJsUrl=$_POST['scraperJsUrl'];
/* @var $fancyboxJsUrl string */
$fancyboxJsUrl="";
if(isset($_POST['fancyboxJsUrl'])) $fancyboxJsUrl=$_POST['fancyboxJsUrl'];
/* @var $fancyboxCssUrl string */
$fancyboxCssUrl="";
if(isset($_POST['fancyboxCssUrl'])) $fancyboxCssUrl=$_POST['fancyboxCssUrl'];
/* @var $popupUrl string */
$popupUrl="";
if(isset($_POST['popupUrl'])) $popupUrl=$_POST['popupUrl'];

// Capture output start
ob_start();
?>
<script type="text/javascript">
javascript:(function(win,doc,jqVers,fbVers,callback){
    var jq=win.jQuery;
    var head=doc.getElementsByTagName('head')[0];
    loadJq=function(){
        if(!jq||jqVers>jq.fn.jquery){
            jqScript=document.createElement('script');
            jqScript.type='text/javascript';
            jqScript.src='<?php echo $jqueryUrl ?>';
            jqScript.onload=function(){
                console.log('jQuery loaded');
                jq=win.jQuery;
                loadScraper();
            };
            head.appendChild(jqScript);
        }
        else{
            loadScraper();
        }
    };
    loadScraper=function(){
        scrapperScript=document.createElement('script');
        scrapperScript.type='text/javascript';
        scrapperScript.src='<?php echo $scraperJsUrl ?>';
        scrapperScript.onload=function(){
            console.log('Scrapper loaded');
            loadFb();
        };
        head.appendChild(scrapperScript);
    };
    loadFb=function(){
        if(!jq.fancybox||!jq.fancybox.version||fbVers>jq.fancybox.version){
            fbCssLink=doc.createElement('link');
            fbCssLink.type='text/css';
            fbCssLink.media='screen,projection';
            fbCssLink.rel='stylesheet';
            fbCssLink.href='<?php echo $fancyboxCssUrl ?>';
            doc.documentElement.childNodes[0].appendChild(fbCssLink);
            fbJsScript=doc.createElement('script');
            fbJsScript.type='text/javascript';
            fbJsScript.src='<?php echo $fancyboxJsUrl ?>';
            fbJsScript.onload=function(){
                console.log('Fancybox loaded');
                callback(jq);
            };
            head.appendChild(fbJsScript);
        } 
        else callback(jq);
    };
    loadJq(jq);
})(window,document,'1.7.1','2.0.4',function(jq){
    var textarea=document.createElement('textarea');
    textarea.innerHTML=jq.scraper();
    textarea.cols=63;
    textarea.rows=35;
    var para=document.createElement('p');
    para.innerHTML='ScraperResults:';
    para.style.width='500px';
    jq.fancybox({
        content:jq(para),
        type:'html',
        height:645,
        beforeShow:function(){
            jq('.fancybox-inner').append(textarea);
            return true;
        },
        afterClose:function(){
            window.location.reload()
        }
    });
});
</script>
<?php
// Capture output end
$output=ob_get_clean();
// Remove script tags
$output=str_replace("<script type=\"text/javascript\">\n",'',$output);
$output=str_replace("</script>",'',$output);
// Minify
$output=JShrink::minify($output);
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <!-- Always force latest IE rendering engine (even in intranet) & Chrome Frame
        Remove this if you use the .htaccess -->
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
        <title>ScraperLink</title>
        <meta name="description" content="">
        <meta name="author" content="gazbond">
        <meta name="viewport" content="width=device-width; initial-scale=1.0">
        <!-- Replace favicon.ico & apple-touch-icon.png in the root of your domain and delete these references -->
        <link rel="shortcut icon" href="/favicon.ico">
        <link rel="apple-touch-icon" href="/apple-touch-icon.png">
        <style type="text/css">
            form label {
                display: inline-block;
                width: 150px;
            }
        </style>
    </head>
    <body>
        <div>
            <header>
                <h1>ScraperLink</h1>
            </header>
            <a href="<?php echo $output ?>">Save this link as a bookmark</a>
        </div>
    </body>
</html>
