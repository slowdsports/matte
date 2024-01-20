<script src="//ssl.p.jwpcdn.com/player/v/8.24.0/jwplayer.js"></script>
<script>jwplayer.key = 'XSuP4qMl+9tK17QNb+4+th2Pm9AWgMO/cYH8CI0HGGr7bdjo';</script>
<script src="https://cdn.jsdelivr.net/npm/slick-carousel/slick/slick.min.js"></script>
<div class="section mt-2">
    <div class="container">
        <div id="player">Reproductor aquí</div>
    </div>
    <div id="channelsList" class="row">
        <?php
        // Código para consumir y mostrar los canales de televisión del archivo JSON
        $json_url = "https://www.tdtchannels.com/lists/tv.json";
        $json = file_get_contents($json_url);
        $data = json_decode($json, true);

        foreach ($data['countries'] as $country) {
            foreach ($country['ambits'] as $ambit) {
                foreach ($ambit['channels'] as $channel) {
                    $channelName = $channel['name'];
                    $videoUrl = $channel['options'][0]['url'];
                    $channelImg = $channel['logo'];
                     // Obtén las URLs de video del canal
                     $videoUrls = array();
                     foreach ($channel['options'] as $option) {
                         $videoUrls[] = $option['url'];
                     }
                     // Convierte las URLs de video a formato JSON para usar en JavaScript
                    $videoUrlsJson = json_encode($videoUrls);
                    ?>
                    <div class="col-4 col-md-3 col-lg-2 mycard" data-channel-name="<?= $channelName ?>" data-video-urls="<?= $videoUrlsJson ?>">
                        <div class="card product-card liga-card canal-card">
                            <div class="card-body">
                                <center>
                                    <img width="48px" src="<?= $channelImg ?>"
                                        style="background-size: contain; background-repeat: no-repeat;" class="image"
                                        alt="product image" />
                                    <h2 class="title text-center">
                                        <?= $channelName ?>
                                    </h2>
                                </center>
                            </div>
                        </div>
                    </div>
                <?php }
            }
        }
        ?>
    </div>
    <div class="container mt-4">
        <div class="slider">
            <!-- Las tarjetas de los canales se agregarán aquí mediante JavaScript -->
        </div>
    </div>
</div>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        var player = jwplayer('player'); // Inicializar JW Player

        // Manejar el clic en las tarjetas de los canales
        var canalCards = document.querySelectorAll('.canal-card');
        canalCards.forEach(function (card) {
            card.addEventListener('click', function () {
                var channelName = card.getAttribute('data-channel-name');
                var videoUrlsJson = card.getAttribute('data-video-urls');
                var videoUrls = JSON.parse(videoUrlsJson);

                // Cargar el primer video en el reproductor
                player.setup({
                    file: videoUrls[0], // Cargar la primera URL de video como predeterminada
                    width: '100%',
                    height: '400px', // Ajusta la altura según tus necesidades
                    autostart: true,
                });

                // Limpiar el slider
                var slider = document.querySelector('.slider');
                slider.innerHTML = ''; // Limpiar el contenido existente

                // Agregar tarjetas de canales al slider
                for (var i = 0; i < canalCards.length; i++) {
                    var channelCard = canalCards[i];
                    var cardClone = channelCard.cloneNode(true); // Clonar tarjeta
                    cardClone.classList.remove('active'); // Eliminar la clase "active" si existe
                    slider.appendChild(cardClone); // Agregar clon al slider
                }

                // Agregar botones de alternar fuente al slider
                videoUrls.forEach(function(url, index) {
                    var button = document.createElement('button');
                    button.innerText = 'Fuente ' + (index + 1);
                    button.className = 'source-button';
                    button.addEventListener('click', function() {
                        // Cuando se hace clic en un botón de alternar fuente, cargar la URL correspondiente
                        player.load(url);
                    });
                    slider.appendChild(button);
                });
            });
        });
    });
</script>