<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <link rel="stylesheet" type="text/css" href="<?php base_url()?>assets/css/pestanas.css"/>
    <script type="text/javascript" src="<?php base_url()?>assets/js/cambiarPestana.js"></script>
    <script type="text/javascript" src="<?php base_url()?>assets/js/jquery-2.1.3.js"></script>
    <title></title>
</head>
<body onload="javascript:cambiarPestana(pestanas,pestana1);">
    <div class="contenedor">
        <div id="pestanas">
            <ul id=lista>
                <li id="pestana1"><a href='javascript:cambiarPestana(pestanas,pestana1);'>HTML</a></li>
                <li id="pestana2"><a href='javascript:cambiarPestana(pestanas,pestana2);'>CSS</a></li>
                <li id="pestana3"><a href='javascript:cambiarPestana(pestanas,pestana3);'>3</a></li>
                <li id="pestana4"><a href='javascript:cambiarPestana(pestanas,pestana4);'>4</a></li>
                <li id="pestana5"><a href='javascript:cambiarPestana(pestanas,pestana5);'>5</a></li>              
            </ul>
        </div>        
 
        <div id="contenidopestanas">
            <div id="cpestana1">
                Contenido de la pestaña 1
            </div>
            <div id="cpestana2">
                Contenido de la pestaña 2
            </div>
            <div id="cpestana3">
                Contenido de la pestaña 3
            </div>
            <div id="cpestana4">
                Contenido de la pestaña 4
            </div>
            <div id="cpestana5">
                Contenido de la pestaña 5
            </div>
    	</div>
    </div>
</body>
</html>