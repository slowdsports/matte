<?php
if (isset($country) && $country == "ES" || strpos($timezone, "rope")) {
    $proxy = "https://slowdus.herokuapp.com/";
    $_SESSION['message'] = "Estás usando proxy en " . $timezone . ". Si crees que es un error, contáctanos mediante el chat";
    $_SESSION['color'] = "28a745";
} else {
    $proxy = "";
    $_SESSION['message'] = "No estás usando proxy en " . $timezone . ". Si crees que es un error, contáctanos mediante el chat";
    $_SESSION['color'] = "28a745";
}
?>
<div class="section full mt-2 mb-2">
    <h2>Star+ <small>Eventos Programados</small></h2>
    <div class="wide-block pt-2 pb-2">
        <div id="eventos" class="row">
<script src="inc/eventos/horario.js?v"></script>
<script>
    <?php include_once("https://maindota2.co/json/datos.json"); ?>
    document.addEventListener("DOMContentLoaded", function () {
        guardaHorario();
                    var jsonUrl = "https://maindota2.co/json/datos.json";
                    var eventosContainer = document.getElementById("eventos");

                    function compararStatus(a, b) {
                        var statusOrden = {
                            "EN VIVO": 0,
                            "FINALIZADO": 2
                        };
                        if (a.status in statusOrden && b.status in statusOrden) {
                            return statusOrden[a.status] - statusOrden[b.status];
                        } else if (a.status in statusOrden) {
                            return -1;
                        } else if (b.status in statusOrden) {
                            return 1;
                        } else {
                            return 0;
                        }
                    }

                    function procesarURL(url) {
                        if (url.trim() === "#") {
                            return url;
                        }

                        var parteDespreciable = "/embed/eventos/?r=";
                        var urlSinParteDespreciable = url.replace(parteDespreciable, "");
                        var desencriptada = atob(urlSinParteDespreciable);
                        if (/https:\/\/\S*\/star_jwp\.html\?get=/.test(desencriptada)) {
                            desencriptada = desencriptada.replace(/https:\/\/\S*\/star_jwp\.html\?get=/, "");
                        } else if (/^https:\/\/cdn\.sfndeportes\.net\/star_wspp\?get=/.test(desencriptada)) {
                            desencriptada = desencriptada.replace(/^https:\/\/cdn\.sfndeportes\.net\/star_wspp\?get=/, "");
                        }

                        return desencriptada;
                    }

                    function encriptarContenido(eventoUrl) {
                        if (eventoUrl.trim() !== "#") {
                            var partes = eventoUrl.split("&");
                            var urlEncriptada = partes
                                .map(function (part) {
                                    if (part.startsWith("key=") || part.startsWith("key2=") || part.startsWith("img=")) {
                                        var igualIndex = part.indexOf("=");
                                        var clave = part.substring(0, igualIndex + 1);
                                        var valor = part.substring(igualIndex + 1);

                                        if (clave === "img=") {
                                            return clave + valor;
                                        } else {
                                            return clave + btoa(valor);
                                        }
                                    }
                                    return part;
                                })
                                .join("&");

                            var partesURL = urlEncriptada.split("&");
                            var proxy = "<?= $proxy ?>";
                            var primeraParte = partesURL[0];
                            var primeraParteEncriptada = btoa(proxy + primeraParte);
                            urlEncriptada = urlEncriptada.replace(primeraParte, primeraParteEncriptada);
                            return urlEncriptada;
                        }
                        return eventoUrl;
                    }

                    function crearTarjeta(evento) {
                        var card = document.createElement("div");
                        card.className = "col-6 col-sm-4 col-md-3 evento";
                        var eventoUrl = procesarURL(evento.url);
                        var urlEncriptada = encriptarContenido(eventoUrl);

                        var contenidoExtra = "";
                        var contenidoIcon = "";
                        var contenidoFlash = "";
                        var contenidoColor = "";

                        if (evento.status === "EN VIVO") {
                            contenidoExtra = 'En Vivo';
                            contenidoIcon = "ellipse";
                            contenidoFlash = "faa-flash animated";
                            contenidoColor = "light live-text";
                        } else if (evento.status === "FINALIZADO") {
                            contenidoExtra = 'Finalizado';
                            contenidoIcon = "time-outline";
                            contenidoColor = "primary";
                        } else {
                            contenidoExtra = `${evento.status}`;
                            contenidoIcon = "hourglass-outline";
                            contenidoColor = "success";
                        }

                        card.innerHTML = `
                        <a href="?p=tv&r=${urlEncriptada}&title=${evento.title}" aria-label="${evento.league}">
                            <div class="card product-card">
                                <div class="card-body">
                                    <img src="https://prod-ripcut-delivery.disney-plus.net/v1/variant/star/${evento.img}/scale?width=900&aspectRatio=1.78&format=jpeg" class="image" alt="${evento.league}">
                                    <h2 class="title">
                                        ${evento.title}
                                    </h2>
                                    <p class="text">
                                        ${evento.league}
                                    </p>
                                    <a href="?p=tv&r=${urlEncriptada}&title=${evento.title}" aria-label="${evento.league}" class="btn btn-sm btn-${contenidoColor} btn-block">
                                        <ion-icon class='${contenidoFlash}' name='${contenidoIcon}'></ion-icon>
                                        ${contenidoExtra}
                                    </a>
                                </div>
                            </div>
                        </a>`;

                        return card;
                    }

                    fetch(jsonUrl)
                        .then((response) => response.json())
                        .then((data) => {
                            data.sort(compararStatus);
                            data.forEach((evento) => {
                                var tarjeta = crearTarjeta(evento);
                                eventosContainer.appendChild(tarjeta);
                            });
                        })
                        .catch((error) => {
                            console.error("Error al obtener el JSON:", error);
                        });
                });
            </script>
        </div>
    </div>
</div>
