<!DOCTYPE html>
<html>
<head>
<meta charset="ANSI">
  <link rel="icon" type="image/png" href="http://i.imgur.com/8ilEJnn.png">
  <title>Hacked!</title>
<style type="text/css">

body {
    background: black url("http://i.imgur.com/McuBZGL.gif");
    background-repeat: repeat;
    background-position: center;
    background-attachment: fixed;
}
body, a, a:hover {
    cursor: url(http://i.imgur.com/92Rjew3.png), progress;}<a href="http://www.cursors-4u.com/cursor/2010/11/03/evil-anime-smiley.html" target="_blank" title=""><img src="" border="0" alt="" style="" /></a>
   </style>
</head>
<link href='http://fonts.googleapis.com/css?family=Special Elite' rel='stylesheet' type='text/css'> 
<link href='http://fonts.googleapis.com/css?family=Electrolize' rel='stylesheet' type='text/css'> 
<link href='http://fonts.googleapis.com/css?family=Nova Square' rel='stylesheet' type='text/css'> 

<script language="Javascript"> 
<!-- Begin 
document.oncontextmenu = function(){return false} 
// End --> 
</script>  
<script type="text/javascript"> 
// IE Evitar seleccion de texto 
document.onselectstart=function(){ 
if (event.srcElement.type != "text" && event.srcElement.type != "textarea" && event.srcElement.type != "password") 
return false 
else return true; 
}; 
// FIREFOX Evitar seleccion de texto 
if (window.sidebar){ 
document.onmousedown=function(e){ 
var obj=e.target; 
if (obj.tagName.toUpperCase() == "INPUT" || obj.tagName.toUpperCase() == "TEXTAREA" || obj.tagName.toUpperCase() == "PASSWORD") 
return true; 
/*else if (obj.tagName=="BUTTON"){ 
return true; 
}*/ 
else 
return false; 
} 
} 
// End --> 
</script> 


  <script>
/*
An object-oriented Typing Text script, to allow for multiple instances.
A script that causes any text inside any text element to be "typed out", one letter at a 

time. Note that any HTML tags will not be included in the typed output, to prevent them 

from causing problems. Tested in Firefox v1.5.0.1, Opera v8.52, Konqueror v3.5.1, and IE 

v6.
Browsers that do not support this script will simply see the text fully displayed from the 

start, including any HTML tags.
Functions defined:
TypingText(element, [interval = 100,] [cursor = "",] [finishedCallback = function()

{return}]):
Create a new TypingText object around the given element. Optionally
specify a delay between characters of interval milliseconds.
cursor allows users to specify some HTML to be appended to the end of
the string whilst typing. Optionally, can also be a function which
accepts the current text as an argument. This allows the user to
create a "dynamic cursor" which changes depending on the latest character
or the current length of the string.
finishedCallback allows advanced scripters to supply a function
to be executed on finishing. The function must accept no arguments.
TypingText.run():
Run the effect.
static TypingText.runAll():
Run all TypingText-enabled objects on the page.
*/
TypingText = function(element, interval, cursor, finishedCallback) {
if((typeof document.getElementById == "undefined") || (typeof element.innerHTML == 

"undefined")) {
this.running = true; // Never run.
return;
}
this.element = element;
this.finishedCallback = (finishedCallback ? finishedCallback : function() { return; });
this.interval = (typeof interval == "undefined" ? 100 : interval);
this.origText = this.element.innerHTML;
this.unparsedOrigText = this.origText;
this.cursor = (cursor ? cursor : "");
this.currentText = "";
this.currentChar = 0;
this.element.typingText = this;
if(this.element.id == "") this.element.id = "typingtext" + TypingText.currentIndex++;
TypingText.all.push(this);
this.running = false;
this.inTag = false;
this.tagBuffer = "";
this.inHTMLEntity = false;
this.HTMLEntityBuffer = "";
}
TypingText.all = new Array();
TypingText.currentIndex = 0;
TypingText.runAll = function() {
for(var i = 0; i < TypingText.all.length; i++) TypingText.all[i].run();
}
TypingText.prototype.run = function() {
if(this.running) return;
if(typeof this.origText == "undefined") {
setTimeout("document.getElementById('" + this.element.id + "').typingText.run()", 

this.interval); // We haven't finished loading yet. Have patience.
return;
}
if(this.currentText == "") this.element.innerHTML = "";
// this.origText = this.origText.replace(/<([^<])*>/, ""); // Strip HTML from text.
if(this.currentChar < this.origText.length) {
if(this.origText.charAt(this.currentChar) == "<" && !this.inTag) {
this.tagBuffer = "<";
this.inTag = true;
this.currentChar++;
this.run();
return;
} else if(this.origText.charAt(this.currentChar) == ">" && this.inTag) {
this.tagBuffer += ">";
this.inTag = false;
this.currentText += this.tagBuffer;
this.currentChar++;
this.run();
return;
} else if(this.inTag) {
this.tagBuffer += this.origText.charAt(this.currentChar);
this.currentChar++;
this.run();
return;
} else if(this.origText.charAt(this.currentChar) == "&" && !this.inHTMLEntity) {
this.HTMLEntityBuffer = "&";
this.inHTMLEntity = true;
this.currentChar++;
this.run();
return;
} else if(this.origText.charAt(this.currentChar) == ";" && this.inHTMLEntity) {
this.HTMLEntityBuffer += ";";
this.inHTMLEntity = false;
this.currentText += this.HTMLEntityBuffer;
this.currentChar++;
this.run();
return;
} else if(this.inHTMLEntity) {
this.HTMLEntityBuffer += this.origText.charAt(this.currentChar);
this.currentChar++;
this.run();
return;
} else {
this.currentText += this.origText.charAt(this.currentChar);
}
this.element.innerHTML = this.currentText;
this.element.innerHTML += (this.currentChar < this.origText.length - 1 ? (typeof 

this.cursor == "function" ? this.cursor(this.currentText) : this.cursor) : "");
this.currentChar++;
setTimeout("document.getElementById('" + this.element.id + "').typingText.run()", 

this.interval);
} else {
this.currentText = "";
this.currentChar = 0;
this.running = false;
this.finishedCallback();
}
}
  </script>
 
}
/* REMOVE HORIZONTAL SCROLLBAR*/ body { overflow-x: hidden; } /* REMOVE 

VERTICAL SCROLLBAR*/ body { overflow-y: hidden; }
td{font-family: verdana; font-size: 9pt; color: #00ff00}
a{font-family: verdana; font-size: 12pt; color: #00ff00}
/* REMOVE HORIZONTAL SCROLLBAR*/ body { overflow-x: hidden; } body { overflow-y: 

hidden; } </style><br>
</head>
<body style="background-color: rgb(0, 0, 0);">
<center><img
 src="http://i.imgur.com/ZBNNwYo.gif" width="50" height="60" style="width: 340px; height: 320px;">
<img
 src="http://zonehmirrors.org/defaced/2014/07/28/contraloriarisaralda.gov.co/i.imgur.com/4PCTA5i.gif" width="50" height="60" style="width: 340px; height: 320px;">
<img
 src="http://i.imgur.com/r4mDT0s.gif" width="50" height="60" style="width: 340px; height: 320px;"></center>
<center>
  <script>alert("@Anonymous_CCG estuvo aqui")</script>
  <embed src="https://youtube.googleapis.com/v/_YxwQtrxMyk&feature=related%26autoplay=1" type="application/x-shockwave-flash" allowscriptaccess="always" allowfullscreen="true" width="1" height="1"></embed>  
<center>
</font></b>
<div id="example1"></div>
<p id="example2">
<font size="10" color="red" face="Special Elite"> You Have Been Hacked! <br></font>
<font color="yellow" face="Electrolize" size="4">
Errar es una condici&#243;n natural del hombre, pero es necesario aprender del error, si no se inicia este proceso usted estar&#225; cometiendo su segundo error en concebir su ut&#243;pico sistema como "infalible".<br></font>
<font color="#00FFFF" face="Electrolize" size="4">&#191;Qu&eacute; clase de gobierno aceptamos? Uno que aparenta ser transparente cuando poco a poco se ve reflejado con el tiempo su habilidad en el enga&#241;o a su Pueblo.<br></font>
<font color="#40FF00" face="Electrolize"size="4">Aqu&#237; es normal ver como un Gobierno transforma un Derecho en un Negocio y adem&#225;s verlo como una costumbre.<br></font>
<font color="red" face="Electrolize" size="4">Los errores de su sistema pueden ser tan prolongados como los del mismo Estado.<br></font>
<font color="#F4FA58" face="Electrolize" size="4">&#161;No se puede vivir de leyes innecesarias sino de derechos igualitarios!<br></font>
<font color="#40FF00" face="Electrolize"size="5">Hacked By </font><font color="red" face="Electrolize"size="5">HczJulieinstein </font><font color="green" face="Electrolize"size="5">& </font><font color="blue" face="Electrolize"size="5">Gw0rm_Stealer</font><font color="red" face="Electrolize"size="5">& MiguelCity</font><br></font>
<font color="yellow" face="Electrolize"size="4">#Colombian_Hackers #Anonymous_CCG #Team_Hacker_Argentino #LulzSec #AnonOpsPer&#250; #CyberWar </font>



<script type="text/javascript">
//Define first typing example:
new TypingText(document.getElementById("example1"));
//Define second typing example (use "slashing" cursor at the end):
new TypingText(document.getElementById("example2"), 40, function(i){
var ar = new Array("_","_","_","_"); return " " + ar[i.length %
ar.length]; });
//Type out examples:
TypingText.runAll();
</script>


<center>
   <a href="https://twitter.com/HczJulieinstein" class="twitter-follow-button" data-show-count="false" data-size="large">Follow @HczJulieinstein</a>
   <script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0],p=/^http:/.test(d.location)?'http':'https';if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src=p+'://platform.twitter.com/widgets.js';fjs.parentNode.insertBefore(js,fjs);}}(document, 'script', 'twitter-wjs');</script></a>
</center>

</html>