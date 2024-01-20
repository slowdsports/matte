<!-- Recomendados Slider -->
<div class="section full mt-3 mb-3">
    <div class="container">
        <h2>Star+</h2>
    </div>
    <div class="carousel-multiple owl-carousel owl-theme">
        <?php
        //$json_url = 'https://corsproxy.io/?https://elcdn.realintic.online/eventos.json';
        $json_url = "http://127.0.0.1/silicon/v3/inc/componentes/agenda/data.json";
        $json_content = file_get_contents($json_url);
        $data = json_decode($json_content, true);
        if ($data !== null) {
            function compareStatus($a, $b)
            {
                $statusOrder = array("EN VIVO", "00:00", "FINALIZADO");
                $aStatusIndex = array_search($a['status'], $statusOrder);
                $bStatusIndex = array_search($b['status'], $statusOrder);
                return $aStatusIndex - $bStatusIndex;
            }
            usort($data, 'compareStatus');
            // Itera a través de los eventos y muestra la información
            foreach ($data as $evento) {
                $url = $evento['url'];
                ($url !== "#" ? $url = base64_decode($url) : "");
                $pattern = '/.*\?get=/';
                $newUrl = preg_replace($pattern, '', $url);
                $urlParts = explode('&img=', $newUrl);
                $params = array();
                parse_str($urlParts[1], $params);
                $proxy = "https://slowdus.herokuapp.com/";
                $get = base64_encode($proxy . $urlParts[0]);
                $img = $urlParts[1];
                $key = base64_encode($params['key']);
                $key2 = base64_encode($params['key2']);
                $status = $evento['status'];
                // Horario
                ?>
                <a href="?p=tv&r=<?= $get ?>&img=<?= $img ?>&key=<?= $key ?>&key2=<?= $key2 ?>">
                    <div class="card">
                        <img src="https://prod-ripcut-delivery.disney-plus.net/v1/variant/star/<?= $evento['img'] ?>/scale?width=900&aspectRatio=1.78&format=jpeg"
                            class="card-img-top" alt="image">
                        <div class="card-body pt-2">
                            <h6 class="card-subtitle">
                                <?= $evento['league'] ?>
                            </h6>
                            <h4 class="mb-0">
                                <?= $evento['title'] ?>
                            </h4>
                            <p class="card-text">
                                <?= $status ?>
                                <script>
                                    // Hora del evento del JSON en formato HH:mm
                                    var horaEventoJSON = '<?=$status?>';
                                    console.log(horaEventoJSON)
                                    if (horaEventoJSON == Date()) {
                                        // Crear un objeto Date con la hora del evento en la zona horaria UTC
                                        var horaEventoUTC = new Date('2000-01-01T' + horaEventoJSON + 'Z');
                                        // Obtener la zona horaria del usuario (por ejemplo, UTC-6 para Honduras)
                                        var zonaHorariaUsuario = 'America/Tegucigalpa'; // UTC-6
                                        // Convertir la hora del evento a la zona horaria del usuario
                                        var opciones = { timeZone: zonaHorariaUsuario, hour12: false, hour: '2-digit', minute: '2-digit' };
                                        var horaEventoUsuario = horaEventoUTC.toLocaleTimeString(undefined, opciones);
                                        // Mostrar la hora del evento para el usuario en su zona horaria local
                                        console.log('Hora del evento para el usuario: ' + horaEventoUsuario);
                                    }

                                </script>
                            </p>
                        </div>
                    </div>
                </a>
                <?php
            }
        } else {
            // Si la decodificación falla, muestra un mensaje de error
            echo 'Error al obtener los eventos.';
        }
        ?>
    </div>
</div>
<!-- End Slider -->