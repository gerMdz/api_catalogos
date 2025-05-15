<?php

namespace App\Service;

use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Throwable;

class FotoService
{
    private HttpClientInterface $client;
    private ImageManager $imgManager;
    private string $secret;
    private string $cachePath;
    private string $fotoUrl;

    public function __construct(HttpClientInterface $client, string $fotoSecret, string $cachePath, string $fotoUrl)
    {
        $this->client = $client;
        $this->imgManager = new ImageManager(Driver::class);
        $this->secret = $fotoSecret;
        $this->cachePath = rtrim($cachePath, '/');
        $this->fotoUrl = $fotoUrl;
    }

    /**
     * Devuelve el path a la imagen optimizada. Si no existe, la descarga, redimensiona y guarda.
     */
    public function obtenerOptimizada(string $pathPhoto): ?string
    {
        $nombreArchivo = pathinfo($pathPhoto, PATHINFO_FILENAME) . '.jpg';
        $destino = "{$this->cachePath}/{$nombreArchivo}";

        if (file_exists($destino)) {
            return $destino;
        }

        // Descargar imagen original desde censo.sitio
        $url = $this->fotoUrl . urlencode($pathPhoto) . "?token={$this->secret}";

        try {
            $response = $this->client->request('GET', $url);
            if ($response->getStatusCode() !== 200) {
                return null;
            }

            // Guardar temporalmente el archivo original
            $temp = tempnam(sys_get_temp_dir(), 'foto_');
            file_put_contents($temp, $response->getContent());

            // Leer, escalar proporcionalmente y guardar
            $image = $this->imgManager->read($temp)
                ->scaleDown(width: 256, height: 256)
                ->toJpeg(90);

            $image->save($destino);
            unlink($temp);

            return $destino;
        } catch (Throwable $e) {
            return null;
        }
    }
}
