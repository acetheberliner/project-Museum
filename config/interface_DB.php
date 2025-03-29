<?php

interface IDatabase {

    // prepara una query SQL
    public function query($sql);

    // associa un valore a un parametro della query
    public function bind($param, $value, $type = null);

    // esegue la query
    public function execute($data = null);

    // restituisce tutti i risultati della query
    public function resultset();
    
    // restituisce una singola riga del risultato
    public function single();
}
