<?php
require_once __DIR__ . '/../inc/require.php';

class Utente
{
    protected $ute_id;
    static protected $tabella = 'utenti';
    public $record;
    public $dati;

    function __construct($id)
    {
        $this->ute_id = $id;
        $this->setRecord();
        $this->setDati();
    }

    private function setRecord()
    {
        global $dbo;
        $this->record = $dbo->find(self::$tabella, 'ute_id', $this->ute_id);
    }

    private function setDati()
    {
        $this->dati = $this->record;
    }

    static function getUtenti($where = "", $bind = [])
    {
        global $dbo;
        $query = "SELECT * FROM " . self::$tabella . " WHERE 1 $where";
        $dbo->query($query);
        $dbo->execute($bind);
        return $dbo->resultset();
    }
}
?>
