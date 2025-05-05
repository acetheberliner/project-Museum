# 🖼️ Project Museum

---

🌐 **Sito online**: [http://projectmuseum.infinityfreeapp.com/index.php](http://projectmuseum.infinityfreeapp.com/index.php)

---

## ✅ Funzionalità Implementate
Il sistema offre un set completo e organizzato di funzionalità, pensate per garantire un'esperienza utente fluida e una gestione efficace dei contenuti museali.

Di seguito una panoramica dettagliata:
🧭 Interfaccia e Navigazione
•	Dashboard dinamica con riepilogo dei dati principali (conteggio clienti, mostre, opere)
•	Sidebar contestuale persistente, visibile in tutte le pagine, per accesso rapido alle funzionalità
•	Interfaccia responsive realizzata con Bootstrap 5, ottimizzata per dispositivi mobili e tablet

🗂️ Gestione dei Dati (CRUD)
Sistema completo di gestione (Create, Read, Update, Delete) per le seguenti entità:
•	👥 Clienti: anagrafica con nome, email e numero di telefono
•	🖼️ Opere: titolo, autore, anno e descrizione dell’opera
•	🎪 Mostre: nome mostra, data inizio/fine e collegamento a opere esposte
•	🛡️ Utenti: accesso riservato agli admin per gestione credenziali e ruoli

Tutte le operazioni CRUD sono accompagnate da:
•	Form validati lato client
•	Messaggi di conferma/successo
•	Comandi per modifica rapida, eliminazione sicura (con conferma) e inserimento

🔍 Ricerca, Filtri e Navigazione Dati
•	Campo di ricerca live su nome/titolo per ciascuna entità
•	Filtro testuale per affinare i risultati senza ricaricare la pagina
•	Paginazione automatica per caricare e visualizzare grandi volumi di dati in modo efficiente (con offset e limit configurabili)

🔐 Sicurezza e Autenticazione
•	Sistema di login con sessione PHP per accesso protetto
•	Gestione ruoli tramite campo ute_ruolo (admin / user)
•	Restrizione automatica dell’accesso alle sezioni di amministrazione e ai webservice riservati
•	Salvataggio delle password hashate in SHA-256

🌐 Web Services REST
•	API RESTful per accesso remoto ai dati (descritti nel dettaglio nel capitolo dedicato)
•	Interfacce separate per testare i webservice (wsCliente.php, wsMostra.php)
•	Autenticazione API tramite APIKEY e header personalizzati

Tutte le funzionalità sono state testate in ambiente locale e remoto, ove possibile, garantendo robustezza, usabilità e coerenza dei dati.

