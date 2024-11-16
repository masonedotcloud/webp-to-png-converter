# WebP to PNG Converter

## Descrizione

**WebP to PNG Converter** è uno script PHP che consente di caricare file immagine in formato WebP e convertirli automaticamente in formato PNG. Dopo la conversione, le immagini possono essere scaricate singolarmente o come un archivio ZIP contenente tutte le immagini convertite.

Questo progetto è ideale per chi desidera convertire facilmente immagini WebP in PNG tramite un'interfaccia web semplice da usare.

## Funzionalità

- **Caricamento multiplo** di file WebP da una sola volta.
- **Conversione automatica** da WebP a PNG.
- **Download singolo** delle immagini convertite.
- **Generazione di un archivio ZIP** contenente tutte le immagini convertite.
- **Gestione degli errori** per una corretta gestione di file non validi o configurazioni errate.
- **Pulizia automatica** delle cartelle di upload scadute dopo 15 minuti.

## Prerequisiti

Per eseguire questo progetto, è necessario avere un server web con supporto PHP. Le funzionalità di caricamento e conversione delle immagini richiedono PHP 5.4 o superiore.

## Installazione

1. **Clona il repository:**
   Apri il terminale e usa il comando git per clonare il repository nella tua cartella di destinazione:
   ```bash
   git clone https://github.com/masonedotcloud/webp-to-png-converter.git
   ```

2. **Configura il server:**
   Carica i file del progetto sul tuo server web che supporta PHP. Puoi usare Apache o Nginx.

3. **Modifica i permessi della cartella di upload:**
   Assicurati che la cartella `uploaded-images` abbia i permessi di scrittura per il server web. Esegui questo comando dalla root del progetto:
   ```bash
   chmod -R 777 uploaded-images/
   ```

4. **Configura `.htaccess` (se usi Apache):**
   Il file `.htaccess` incluso nel repository è configurato per gestire il reindirizzamento delle richieste al file `index.php`. Assicurati che il modulo `mod_rewrite` sia attivo nel tuo server Apache.

5. **Accesso all'interfaccia web:**
   Vai nel tuo browser e accedi al percorso dove hai caricato il progetto. Ad esempio:
   ```
   http://tuo-dominio.com/webp-to-png-converter/
   ```

## Utilizzo

1. **Carica i file:**
   Una volta sulla pagina web, usa il form di caricamento per selezionare uno o più file in formato WebP dal tuo computer.

2. **Conversione e download:**
   Dopo aver caricato i file, lo script convertirà automaticamente le immagini WebP in PNG. Verranno forniti dei link per scaricare le immagini convertite singolarmente, oppure potrai scaricare un archivio ZIP contenente tutte le immagini convertite.

3. **Pulizia delle immagini scadute:**
   Dopo 15 minuti dalla creazione, le cartelle contenenti immagini convertite vengono automaticamente eliminate dal sistema.

## File e Cartelle

- `uploaded-images/`: Cartella dove vengono caricate e salvate le immagini convertite.
- `index.php`: Il file principale che gestisce il caricamento, la conversione e il download delle immagini.
- `.htaccess`: Configurazione per Apache per riscrivere gli URL.
- `README.md`: Questo file, che spiega come usare il progetto.

## Limitazioni

- Il numero massimo di file che è possibile caricare è **50**.
- La dimensione massima totale dei file caricati è **50 MB**.
- Supporta solo file **WebP** da convertire in formato **PNG**.

## Licenza

Questo progetto è distribuito sotto la Licenza MIT - vedi il file [LICENSE](LICENSE) per ulteriori dettagli.


## Autore

Questo progetto è stato creato da [alessandromasone](https://github.com/alessandromasone).
