<?php
require_once __DIR__ . '/../inc/require.php';

class Cliente
{
    protected $cli_id;
    static protected $tabella = 'clienti';
    public $record; // Dati grezzi del record
    public $dati;   // Dati formattati per la presentazione

    function __construct($id)
    {
        $this->cli_id = $id;
        $this->setRecord();
        $this->setDati();
    }

    private function setRecord()
    {
        global $dbo;
        $this->record = $dbo->find(self::$tabella, 'cli_id', $this->cli_id);
    }

    private function setDati()
    {
        $this->dati = $this->record;
    }

    static function getClienti($where = "", $bind = [])
    {
        global $dbo;
        $query = "SELECT * FROM " . self::$tabella . " WHERE 1 $where";
        $dbo->query($query);
        $dbo->execute($bind);
        return $dbo->resultset();
    }
}
?>
