<?php

class Router

{
    private array $rutasGet = [];
    private array $rutasPost = [];
    

    public function agregarRuta(string $metodo,string $url, string $archivo): void {
        if ($metodo === 'GET') {
            $this->rutasGet[$url] = $archivo;
        } else if ($metodo === 'POST') {
            $this->rutasPost[$url] = $archivo;
        }
    }

    public function encontrarRuta(string $metodo, string $url): ?string {
        if ($metodo === 'GET') {
            return $this->rutasGet[$url] ?? null;
        } else if ($metodo === 'POST') {
            return $this->rutasPost[$url] ?? null;
        }
        return null;
    }
}
