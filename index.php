<?php
// IS RECEIVED SHORTCUT
if(isset($_GET['q'])){

	// VARIABLE
	$shortcut = htmlspecialchars($_GET['q']);

	// IS A SHORTCUT ?
	$bdd = new PDO('mysql:host=localhost;dbname=bitly;charset=utf8', 'root', '');
	$req =$bdd->prepare('SELECT COUNT(*) AS x FROM links WHERE shortcut = ?');
	$req->execute(array($shortcut));

	while($result = $req->fetch()){

		if($result['x'] != 1){
			header('location: ../RaccourcisseurURL/?error=true&message=Adresse url non connue');
			exit();
		}

	}

	// REDIRECTION
	$req = $bdd->prepare('SELECT * FROM links WHERE shortcut = ?');
	$req->execute(array($shortcut));

	while($result = $req->fetch()){

		header('location: '.$result['url']);
		exit();

	}

}

// IS SENDING A FORM
if(isset($_POST['url'])) {

	// VARIABLE
	$url = $_POST['url'];

	// VERIFICATION
	if(!filter_var($url, FILTER_VALIDATE_URL)) {
		// PAS UN LIEN
		header('location: ../RaccourcisseurURL/?error=true&message=Adresse url non valide');
		exit();
	}

	// SHORTCUT
	$shortcut = crypt($url, rand());

	// HAS BEEN ALREADY SEND ?
	$bdd = new PDO('mysql:host=localhost;dbname=Bitly;charset=utf8', 'root', '');
	$req = $bdd->prepare('SELECT COUNT(*) AS x FROM links WHERE url = ?');
	$req->execute(array($url));

	while($result = $req->fetch()){

		if($result['x'] != 0){
			header('location: ../RaccourcisseurURL/?error=true&message=Adresse déjà raccourcie');
			exit();
		}

	}

	// SENDING
	$req = $bdd->prepare('INSERT INTO links(url, shortcut) VALUES(?, ?)');
	$req->execute(array($url, $shortcut));

	header('location: ../RaccourcisseurURL/?short='.$shortcut);
	exit();

}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Raccourcisseur d'URL</title>
    <link rel="stylesheet" type="text/css" href="design/default.css">
    <link rel="icon" type="image/jpg" href="pictures/favico.png">
</head>
<body>
    <section id="hello">
        <div class="container">
            <header>
                <img id="logohead" src="pictures/logo.png" alt="Logo Bitly">
            </header>
            <h1>Une URL longue ? Raccourcissez-la !</h1>
            <h2>Largement meilleur et plus court que les autres.</h2>
            <form method="POST">
                <input type="url" name="url" placeholder="Collez un lien à raccourcir">
                <input type="submit" value="Raccourcir">
            </form>

            <?php if(isset($_GET['error']) && isset($_GET['message'])) { ?>
					<div class="center">
						<div id="result">
							<b><?php echo htmlspecialchars($_GET['message']); ?></b>
						</div>
					</div>
				<?php } else if(isset($_GET['short'])) { ?>
					<div class="center">
						<div id="result">
							<b>URL RACCOURCIE : </b>
							http://localhost/RaccourcisseurURL/?q=<?php echo htmlspecialchars($_GET['short']); ?>
						</div>
					</div>
				<?php } ?>

        </div>
    </section>
    <section id="brands">
        <div class="container">
            <h3>Ces marques qui nous font confiance</h3>
            <img src="pictures/1.png" alt="Logo Entrepeneur Magazine's" class="picture">
            <img src="pictures/2.png" alt="Logo Kaiser Permanente" class="picture">
            <img src="pictures/3.png" alt="Logo PBS" class="picture">
            <img src="pictures/4.png" alt="Logo Montage" class="picture">
        </div>
    </section>
    <section>
        <footer>
            <img id="logofoot" src="pictures/logo2.png" alt="Logo Bitly">
            <p>2021 &copy; Bitly</p>
            <p><a href="#">Contact</a> - <a href="#">A propos</a></p>
        </footer>
    </section>
</body>
</html>