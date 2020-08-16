# Editor server-info per Minecraft-Italia.it

Questo plugin permette di modificare il file `server-info.yml` per gestire le informazioni del server nella lista pubblica di Minecraft-Italia

## Caratteristiche principali:
- Gestione semplificata tramite interfaccia web
- Calcolo automatico delle versioni
- Selezione tra le categorie prefissate
- Gestione semplificata della sezione "staff" e calcolo automatico degli UUID Minecraft
- Color picker per il colore del tema
- Editor markdown completo con preview e supporto a classi e embedding di video Youtube
- Tramite l'editor markdown **è possibile caricare e inserire immagini** tramite la finestra media di wordpress
- Gestione dello storico del contenuto del file server-info in modo semplice, con salvataggio e ripristino

## Utilizzo:
1. Scaricare il plugin dal repo ufficiale (https://github.com/azzlabs/mc-italia-server-info) in formato `.zip`
2. Dal pannello di wordpress, usare la voce di menu Plugin -> Aggiungi nuovo. In alto apparirà il tasto "Carica plugin" e da lì sarà possibile selezionare il file `.zip` scaricato dal repo. Attivare il plugin tramite la pagina di gestione dei plugin di WordPress.
3. Una volta attivato, si potrà accedere tramite il menù "Strumenti" (o "Tools") in una voce chiamata "MC_Italia server info"
4. Da questa interfaccia è possibile cambiare la posizione del file `server-info.yml` se necessario (sconsigliato). Se la cartella di destinazione è scrivibile, apparirà un pulsante "Vai all'editor", tramite il quale si arriverà alla schermata di modifica del file.
5. Da qui, è possibile modificare le informazione e creare/sovrascivere il file premendo il tasto "Salva le modifiche". Inizialmente la schermata di modifica tenterà di leggere un file `server-info.yml` esistente e popolerà i campi, altrimenti rimarranno vuoti.
6. Se dovesse fallire la creazione o lettura del file, significa che la cartella selezionata non esiste, oppure non si hanno i permessi di scrittura (ottenibili tramite i comandi `chown` o `chmod` sulla cartella o file di destinazione).

## Librerie di terze parti usate:
- [Simple MDE](https://simplemde.com/)
- [Symfony YAML parser](https://github.com/symfony/yaml)