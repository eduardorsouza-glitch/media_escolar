```php
<?php

require_once __DIR__ . '/../Model/Boletim.php';

class BoletimController
{
    public function index()
    {
        return Boletim::listar();
    }

    public function store()
    {
        return Boletim::cadastrar();
    }

    public function show($id)
    {
        return Boletim::buscar($id);
    }

    public function update($id)
    {
        return Boletim::atualizar($id);
    }

    public function delete($id)
    {
        return Boletim::excluir($id);
    }
}
```
