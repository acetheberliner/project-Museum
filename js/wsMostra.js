const BASE_URL = 'http://localhost/laboratorio/progettoMuseo/api/';
const APIKEY = 'b4st0I5868HdCLAdeotkrSPGdeT1Df9ixpeQpWgD';

document.addEventListener('DOMContentLoaded', () => {
    const btn = document.getElementById('btnMostra');
    const input = document.getElementById('inputId');
    const output = document.getElementById('output');

    btn.addEventListener('click', async () => {
        const id = input.value.trim();

        if (!id) {
            output.innerHTML = '<em class="text-danger">// Inserisci un ID valido</em>';
            return;
        }

        output.innerHTML = '<em>// Caricamento...</em>';

        try {
            const res = await fetch(`${BASE_URL}mostra/${id}`, {
                headers: {
                    'APIKEY': APIKEY,
                    'Content-Type': 'application/json'
                }
            });

            const json = await res.json();

            if (json.errore) {
                output.innerHTML = `<div class="text-danger fw-bold">⚠️ ${json.errore}</div>`;
                return;
            }

            const mostra = json.mostra;
            const opere = json.opere;

            let html = `
                <h5 class="mb-3"><i class="bi bi-bank"></i> <strong>${mostra.mos_nome}</strong></h5>
                <p><strong>Data inizio:</strong> ${mostra.mos_data_inizio}<br>
                   <strong>Data fine:</strong> ${mostra.mos_data_fine}</p>
            `;

            if (opere.length > 0) {
                html += `
                    <h6 class="mt-4 mb-2 fw-bold"><i class="bi bi-palette"></i> Opere in mostra:</h6>
                    <table class="table table-sm table-bordered bg-white">
                        <thead class="table-light">
                            <tr>
                                <th>Titolo</th>
                                <th>Autore</th>
                                <th>Anno</th>
                            </tr>
                        </thead>
                        <tbody>
                            ${opere.map(opera => `
                                <tr>
                                    <td>${opera.ope_titolo}</td>
                                    <td>${opera.ope_autore}</td>
                                    <td>${opera.ope_anno}</td>
                                </tr>
                            `).join('')}
                        </tbody>
                    </table>
                `;
            } else {
                html += `<p class="text-muted">Nessuna opera associata a questa mostra.</p>`;
            }

            output.innerHTML = html;

        } catch (err) {
            output.innerHTML = `<span class="text-danger">// Errore: ${err.message}</span>`;
        }
    });
});
