<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Clienti</title>
    <link rel="stylesheet" href="../private_area.css">
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans&display=swap" rel="stylesheet">
    <script src="script.js" defer="true"></script>
</head>
<body>
    <header>
        <nav>
            <div id="logo"> 
                <a href="../private_area.php">MariNel Energia <br>Area Riservata</a>
            </div>
            <div id="navbar">
                <a href="clienti.php">Clienti</a>
                <a href="acquisti.php">Acquisti</a>
            </div>
            <div id="menu">
                <div></div>
                <div></div>
                <div></div>
            </div>
        </nav>
    </header>
    <section id="tabs">
        <div id="list">
            <strong>Lista Clienti</strong>
        </div>
        <div id="add">
            <strong>Aggiungi cliente</Strong>
        </div>
        <div id="delete" >
            <strong>Rimuovi cliente</strong>
        </div>
    </section>

    <section id="selection" class="hide">
        <label class="container">Privato
            <input type="checkbox" id="private">
            <span class="checkmark"></span>
        </label>
          
        <label class="container">Azienda
            <input type="checkbox" id="not_private">
            <span class="checkmark"></span>
        </label>
    </section>
    
    <section id="add_section">
        <div id="add_privato" class="hide">
            <form action="add_client_private.php"name="add_client" method="post">
                <div id="names">
                    <div id="name">
                        <div><label for="name">Nome</label></div>
                        <div><input type="text" name="name"></div>
                    </div>
                    <div id="surname">
                        <div><label for="surname">Cognome</label></div>
                        <div><input type="text" name="surname"></div>
                    </div>
                </div>
                <div id="cf">
                    <div><label for="cf">Codice fiscale</label></div>
                    <div><input type="text" name="cf"></div>
                </div>
                <div id="number">
                    <div><label for="number">Numero di telefono</label></div>
                    <div><input type="text" name="number"></div>
                </div>
                <div id="street">
                    <div><label for="street">Via e numero civico</label></div>
                    <div><input type="text" name="street"></div>
                </div>
                <div id="country">
                    <div id="city">
                        <div><label for="city">Città</label></div>
                        <div><input type="text" name="city"></div>
                    </div>
                    <div id="cap">
                        <div><label for="cap">CAP</label></div>
                        <div><input type="text" name="cap"></div>
                    </div>
                </div>
                <div id="email">
                    <div><label for="email">Email</label></div>
                    <div><input type="text" name="email"></div>
                </div>
                <div id="submit_button">
                    <input type="submit" value="Aggiungi cliente" name="submit">
                </div>
            </form>
        </div>
        <div id="add_impresa" class="hide">
            <form action="add_client_not_private.php" name="add_client_not_private" method="post">
                <div id="rag_soc">
                        <div><label for="rag_soc">Ragione sociale</label></div>
                        <div><input type="text" name="rag_soc"></div>
                </div>
                <div id="p_iva">
                    <div><label for="p_iva">Partita iva</label></div>
                    <div><input type="text" name="p_iva"></div>
                </div>
                <div id="number">
                    <div><label for="number">Numero di telefono</label></div>
                    <div><input type="text" name="number"></div>
                </div>
                <div id="street">
                    <div><label for="street">Via e numero civico</label></div>
                    <div><input type="text" name="street"></div>
                </div>
                <div id="country">
                    <div id="city">
                        <div><label for="city">Città</label></div>
                        <div><input type="text" name="city"></div>
                    </div>
                    <div id="cap">
                        <div><label for="cap">CAP</label></div>
                        <div><input type="text" name="cap"></div>
                    </div>
                </div>
                <div id="email">
                    <div><label for="email">Email</label></div>
                    <div><input type="text" name="email"></div>
                </div>
                <div id="submit_button">
                    <input type="submit" value="Aggiungi cliente" name="submit">
                </div>
            </form>
        </div>
    </section>
    <section id="remove" class="hide">
        <div id="remove_client">
            <div>
                <form action="remove_client.php"name="delete_client" method="post" id="delete_form">
                    <strong>Delete by:</strong>
                    <select name="select_delete">
                        <option value="choise">--Seleziona--</option>
                        <option value="cf">Codice Fiscale</option>
                        <option value="username">Username</option>
                        <option value="rag_soc">Ragione sociale</option>
                        <option value="p_iva">Partita iva</option>
                </select>
                <div id="value">
                    <div><input type="text" name="value"></div>
                </div>
                <div id="submit_delete">
                    <input type="submit" value="Rimuovi cliente" name="submit">
                </div>
                </form>
            </div>
        </div>
    </section>
    <table id="private_table" class="hide">
        <thead>
            <tr>
                <th>Id</th>
                <th>Nome</th>
                <th>Cognome</th>
                <th>Codice Fiscale</th>
                <th>Telefono</th>
                <th>Indirizzo</th>
                <th>Username</th>
                <th>Email</th>
            </tr>
        </thead>
        <tbody></tbody>
        <tfoot></tfoot>
    </table>
    <table id="not_private_table" class="hide">
        <thead>
            <tr>
                <th>Id</th>
                <th>Ragione Sociale</th>
                <th>Telefono</th>
                <th>Indirizzo</th>
                <th>Email</th>
                <th>Partita Iva</th>
            </tr>
        </thead>
        <tbody></tbody>
        <tfoot></tfoot>
    </table>
</body>
</html>