<?php

class Router

{
    private array $rutas = [];

    public function agregarRuta(string $url, string $archivoVista): void
    {
        $this->rutas[$url] = $archivoVista;
    }

    public function encontrarRuta(string $rutaSolicitada): ?string
    {
        return $this->rutas[$rutaSolicitada] ?? null;
    }
}
