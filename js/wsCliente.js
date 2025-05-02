const BASE_URL = 'http://localhost/laboratorio/progettoMuseo/api/';
const APIKEY = 'b4st0I5868HdCLAdeotkrSPGdeT1Df9ixpeQpWgD';

document.addEventListener('DOMContentLoaded', () => {
    const idInput = document.getElementById('cli_id');
    const telInput = document.getElementById('cli_tel');
    const output = document.getElementById('output');
    const btn = document.getElementById('btnAggiorna');

    btn.addEventListener('click', async () => {
        const id = idInput.value.trim();
        const tel = telInput.value.trim();

        if (!id || !tel) {
            output.innerHTML = '<span class="text-danger">⚠️ Inserisci tutti i campi richiesti</span>';
            return;
        }

        output.innerHTML = '<em>// Caricamento...</em>';

        try {
            const resGet = await fetch(`${BASE_URL}clienti`, {
                headers: {
                    'APIKEY': APIKEY,
                    'Content-Type': 'application/json'
                }
            });

            const clienti = await resGet.json();
            const cliente = clienti.find(c => c.cli_id == id);

            if (!cliente) {
                output.innerHTML = `<span class="text-danger">❌ Cliente con ID ${id} non trovato.</span>`;
                return;
            }

            const telPrima = cliente.cli_telefono;

            const resPut = await fetch(`${BASE_URL}clienti/${id}`, {
                method: 'PUT',
                headers: {
                    'APIKEY': APIKEY,
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({ cli_telefono: tel })
            });

            const esito = await resPut.json();

            output.innerHTML = `
                <p><strong>✅ ${esito.esito}</strong></p>
                <p><b>Numero precedente:</b> <del class="text-danger">${telPrima}</del><br>
                <b>Numero aggiornato:</b> <span class="text-info">${tel}</span></p>
            `;
        } catch (err) {
            output.innerHTML = `<span class="text-danger">⚠️ Errore: ${err.message}</span>`;
        }
    });
});
