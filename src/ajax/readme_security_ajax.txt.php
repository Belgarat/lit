IMPORTANTE

Quando si costruisce un file ajax e si vuole che questo venga eseguito solo da utenti registrati e loggati,
includere il file verify_auth.php ad inizio pagina dopo il session_start.

Questo verifica che la variabile di sessione $_SESSION["ID"] sia inizializzata e quindi solo in quel caso 
consente l'accesso allo script, se la condizione non si verifica fa un redirect istantaneo alla
homepage.
