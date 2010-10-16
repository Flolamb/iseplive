<?php
/**
 * Fill the directory with the info of the LDAP
 *
 * @example /usr/bin/php -f directory_fill.php
 */

define('CRON_MODE', true);
define('APP_DIR', realpath(realpath(__DIR__).'/../').'/');
define('CF_DIR', realpath(realpath(__DIR__).'/../../confeature/').'/');
define('DATA_DIR', realpath(realpath(__DIR__).'/../../data/').'/');

try{
	
	// Loading Confeature
	require_once CF_DIR.'init.php';
	
	
	/*
	$users = DB::createQuery('users')
		->fields('id', 'phone')
		->where('phone != ""')
		->select();
	foreach($users as $user){
		$phone = $user['phone'];
		$phone = preg_replace('#^(00|\+) ?33#', '0', $phone);
		$phone = preg_replace('#[^0-9]#', '', $phone);
		$phone = substr($phone, 1);
		if(preg_match('#^[0-9]{9}$#', $phone))
			$phone = '0'.$phone[0].' '.$phone[1].$phone[2].' '.$phone[3].$phone[4].' '.$phone[5].$phone[6].' '.$phone[7].$phone[8];
		else
			$phone = '';
		
		DB::createQuery('users')
			->set(array('phone' => $phone))
			->update((int) $user['id']);
	}
	*/
	
	
	// CSV des élèves
	$list = '
"A1","6881","M.","ALBENQUE","Jean-Christophe",22/05/1988,"13 allée de Bréviande","77350","LE MEE SUR SEINE","01 64 52 98 08","06 37 43 53 40"
"A1","05703","M.","ALLOSTERY","Stéphane",11/11/1989,"8 rue du Docteur Ténine","94250","GENTILLY","01 46 16 08 52","06 31 40 51 54"
"A1","05780","M.","ANDRAUD","Gabriel",23/11/1989,"36 avenue Franklin Roosevelt","69130","ECULLY","04 78 33 47 69","06 31 41 81 79"
"A1","6056","M.","ANTOINE","Maxime",04/09/1990,"5 rue Condorcet","92400","COURBEVOIE","01 43 33 82 22","06 50 50 63 36"
"A1","6058","M.","AZIZIAN","Florian",10/10/1990,"12 avenue de Nancy","93140","BONDY","01 48 02 07 20","06 84 09 73 61"
"A1","6135","M.","BACCHI","Pierre",17/02/1991,"9 rue Raphael","92170","VANVES","01 46 45 32 65",
"A1","6059","MLLE","BADOHU","Marine",09/04/1990,"8 rue Carcel","75015","PARIS 15","01 48 58 76 70","06 59 24 38 78"
"A1","6060","M.","BARJON","Axel",04/09/1990,"16 rue Jean-Claude Arnould, Résidence Saint-Jacques","75014","PARIS",,"06 74 63 70 64"
"A1","6884","M.","BAROUX","Martin",08/11/1988,"31 chemin de Duran","47310","AUBIAC","05 53 47 30 53","06 73 52 39 04"
"A1","6176","M.","BARRAU","Maxime",25/11/1990,"57 rue de la Roquette","75011","PARIS","09 62 37 80 36","06 30 93 25 99"
"A1","7019","M.","BASSET","Maxime",21/06/1989,"33 rue du général Leclerc","92130","ISSY LES MOULINEAUX",,"0672491126"
"A1","7009","M.","BASSO","Etienne",13/12/1988,"33 Rue du Général Leclerc","92130","ISSY LES MOULINEAUX",,"0609468291"
"A1","7031","MLLE","BEN FARHAT","Cyrine",27/12/1988,"28 rue Miollis","75015","PARIS",,"0664288399"
"A1","6137","M.","BERTHELEMY","Antoine",07/07/1990,"5 rue du Général de Larminat","75015","PARIS",,"06 50 06 63 35"
"A1","6196","M.","BERTHIAUX","Arthur",07/09/1990,"82 rue Philippe de Girard","75018","PARIS 18","09 53 94 08 07","06 19 08 07 94"
"A1","6995","M.","BESANGER","Matthieu",21/10/1990,"56 avenue Jean Jaurès","92140","CLAMART","0146388324","0622228176"
"A1","7024","M.","BESNIER","Guillaume",05/02/1990,"39 rue saint Lambert","75015","PARIS 15",,"0646229821"
"A1","6770","M.","BEZANGER","Guillaume",13/07/1989,"1 rue Pierre Brossolette","78450","VILLEPREUX","01 30 56 18 29","06 76 66 51 38"
"A1","6197","M.","BIDAN","Guillaume",02/04/1990,"1 boulevard du Roi","78000","VERSAILLES",,"06 89 85 923 5"
"A1","6062","MLLE","BLANC","Roxane",11/07/1990,"1 rue des Gate-Ceps","92210","SAINT-CLOUD","01 47 71 13 60","06 13 34 76 90"
"A1","6198","M.","BLEINC","Pierre-Emmanuel",07/10/1991,"16 Villa des Reines-Claudes","95390","SAINT-PRIX",,"06 73 06 18 42"
"A1","6138","MLLE","BONELLI","Camille",27/11/1988,"20 rue d\'Assas","75006","PARIS",,"06 20 74 70 55"
"A1","7022","M.","BONNET","Frédéric",07/12/1990,"18 rue de Provence","78000","VERSAILLES",,
"A1","6064","MLLE","BOU NADER","Chloé",10/11/1990,"132 rue de l\'Abbé Groult","75015","PARIS","01 40 88 33 41","06 71 95 04 24"
"A1","6769","M.","BOUBTANE","Abdel-Haq",28/04/1987,"8 rue de l\'Empereur Julien","75014","PARIS","01 45 88 80 47","06 01 01 13 96"
"A1","6822","M.","BOUCHET","Rémy",25/06/1990,"69 rue du Faubourg Saint-Martin","75010","PARIS","01 44 52 00 18","06 65 45 31 60"
"A1","6988","M.","BOUSQUET","William",30/12/1990,"7 Rue Cramail","92500","RUEIL MALMAISON","0147510019","0679801343"
"A1","7033","M.","BROIX","Stephen",31/10/1989,"18 rue Weiterstadt","78480","VERNEUIL SUR SEINE","01 39 28 02 01","06 32 31 53 93"
"A1","7017","M.","BUREAU","Julien",28/02/1989,"33 rue Général Leclerc","92136","ISSY LES MOULINEAUX",,"0673307924"
"A1","6341","M.","CAIRE","Romain",22/12/1990,"33 Rue Du general Leclerc","92130","ISSY LES MOULINEAUX",,"0684721798"
"A1","6200","MLLE","CAPPELIER","Chloé",03/12/1990,"22 rue des Halles","75001","PARIS",,"06 88 80 06 94"
"A1","6066","MLLE","CASSE","Alexandra",13/10/1990,"14 avenue du Bois de la Marche","92420","VAUCRESSON","01 47 95 20 12","06 20 05 57 91"
"A1","6067","M.","CASTEL","Julien",05/12/1990,"38 rue Jean lavaud","92260","FONTENAY-AUX-ROSES","01 43 50 47 51","06 64 97 59 47"
"A1","6984","M.","CAUCHEFER","Adrien",07/06/1990,"20 boulevard de Cessole","06100","NICE",,"0619868055"
"A1","05698","MLLE","CECCALDI","Elodie",13/10/1989,"16 ALLEE SISLEY","78560","LE PORT-MARLY","01 39 16 48 56","06 07 02 38 80"
"A1","7020","M.","CERRITO","Samuel",21/04/1990,"44 Rue Frémicourt","75015","PARIS 15",,"0617563692"
"A1","6976","M.","CHACON","Loris",24/08/1990,"6 rue Anatole France","92400","COURBEVOIE",,
"A1","6149","M.","CHARNET","Stéphane",15/11/1990,"17 rue pernety","75014","PARIS 14",,"06 76 68 83 03"
"A1","7027","M.","CHASTELAIN DE BELLEROCHE","Alexandre",28/06/1991,"34 rue de la faisanderie","75116","PARIS 16",,"0672453996"
"A1","05843","M.","CHENE","Pierre-Antoine",21/11/1989,"11 bis rue de mouchy","78000","VERSAILLES","01 30 21 60 17","06 03 75 5982"
"A1","6343","M.","CHIHAB","Ismail",11/09/1989,"9 rue Moulin Bailly","92270","BOIS-COLOMBES",,
"A1","6150","MLLE","CISSE","Coumba",30/07/1990,"12 rue de Puiseux","95380","PUISEUX EN FRANCE","01 34 72 83 29","06 81 07 35 45"
"A1","6151","M.","CLEMENCEAU","Matthieu",05/08/1990,"17 rue Gabrielle d\'Estrées","92170","VANVES","0171160384","06 34 41 92 81"
"A1","6874","M.","CLERC","Clément",21/10/1989,"5 avenue de Cressac","78990","ELANCOURT","01 30 50 08 37","06 64 65 63 60"
"A1","6201","M.","CLERC","Pierre",17/07/1990,"14 rue Béranger","92100","BOULOGNE BILLANCOURT","01 46 05 86 49","06 18 22 82 63"
"A1","6980","M.","COURAUD","Charles",23/09/1991,"32 bis avenue des Courlis","78110","LE VESINET","0139764650","0646721545"
"A1","7007","M.","COURTOIS","Maxime",11/07/1990,"33 avenue du Général Leclerc","92130","ISSY LES MOULINEAUX",,"0632892770"
"A1","6768","M.","COUTO DE LIMA","Michael",18/07/1990,"2 rue Lazare Hoche","92100","BOULOGNE BILLANCOURT","01 46 03 32 55","06 27 77 45 87"
"A1","7005","M.","CRAMPAGNE","Romain",29/01/1990,"13 rue Roland Garros","31200","TOULOUSE",,
"A1","6992","M.","CWIEK","Thibaud",29/11/1990,"11 rue barrault","75013","PARIS 13",,"0611332318"
"A1","6771","M.","DA SILVA DE ALMEIDA","Antonio",26/04/1989,"26 rue des missionnaires","78000","VERSAILLES","0637515331","0637515331"
"A1","6202","M.","DAïDECHE","Julien",12/04/1990,"55 bis avenue Eglé","78600","MAISONS-LAFFITTE",,"06 37 73 46 50"
"A1","6069","M.","DAS","Noël",10/11/1989,"1 rue des Vanneaux","77420","CHAMPS SUR MARNE","01 64 68 65 97","06 62 14 09 37"
"A1","6203","M.","DE CACQUERAY-VALMENIER","Benoît",02/10/1990,"55 avenue de la Motte Picquet","75015","PARIS","01 47 34 36 08","06 15 21 54 63"
"A1","6204","M.","DE LACHEZE-MUREL","Vladimir",29/09/1990,"1 ter rue Collardeau","91450","ETIOLLES","01 69 89 00 45","06 59 41 65 37"
"A1","6346","M.","DE LAFORCADE","Paul",14/05/1990,"133 boulevard du montparnasse","75006","PARIS 06",,"0664634464"
"A1","6347","MLLE","DE LANGLE","Marion",28/07/1990,"102 rue Jules Guesde","92300","LEVALLOIS PERRET","01 47 56 95 71","06 78 37 07 04"
"A1","05840","M.","DE MESNARD","Jean-Baptiste",04/07/1989,"1 avenue des Tourelles","78400","CHATOU","01 30 71 91 61",
"A1","6986","M.","DE ROUZÉ","Geoffroy",21/05/1990,"107 rue Gambetta","51100","REIMS",,
"A1","6205","M.","DE VILLEMANDY DE LA MESNIERE","Nicolas",18/04/1990,"5 rue Scheffer","75016","PARIS","01 47 55 12 52","06 26 04 01 73"
"A1","6071","M.","DEGOUTTE","Adrien",05/02/1991,"2 rue René Panhard","75013","PARIS",,"06 77 93 16 11"
"A1","6206","M.","DELMAS","Emmanuel",14/04/1989,"16 rue Farman","78960","VOISINS LE BRETONNEUX","01 30 96 61 42","06 75 49 61 15"
"A1","6207","M.","DELORME","Quentin",13/11/1990,"29 chemin de sur la ville","74330","NONGLARD",,"06 26 67 58 84"
"A1","6792","M.","DESTIN","Loïck",10/12/1990,"74 rue des haies","75020","PARIS 20",,"06 58 50 05 23"
"A1","6985","M.","DESVERNAY","Charles-Henry",11/06/1989,"14 rue de la Mairie","42600","CHAMPDIEU",,
"A1","6773","M.","DIAKHO","Rahmane",08/06/1990,"5 rue Jacques Decour","95140","GARGES LES GONESSE","01 39 86 58 12","06 78 55 82 22"
"A1","6073","M.","DOITTAU","Augustin",04/03/1990,"74 avenue Fourcault de Pavant","78000","VERSAILLES",,"06 73 05 83 35"
"A1","6074","M.","DORANGE","Louis",19/12/1990,"33 rue de la Résistance","78150","LE CHESNAY","01 39 63 27 28","06 99 50 22 50"
"A1","6824","M.","DOUETTEAU","Quentin",25/12/1987,"36 rue de l\'Alma","92600","ASNIERES-SUR-SEINE","01 47 90 69 59","06 59 53 03 45"
"A1","6209","M.","DU PERIER DE LARSAN","Xavier",28/06/1990,"25 rue Soyer","92200","NEUILLY-SUR-SEINE","01 47 38 34 78","06 61 18 38 13"
"A1","6977","M.","DUBRUEL","Louis",16/08/1990,"108 boulevard Bineau","92200","NEUILLY-SUR-SEINE","0146400278","0627224574"
"A1","6075","M.","DUHEM","Stanislas",02/05/1990,"62 avenue Georges Buffon","78590","NOISY-LE-ROI","01 30 56 52 64","06 36 32 12 04"
"A1","6076","M.","DUHIL DE BENAZE","Loic",09/10/1989,"Bas Couleur","37110","VILLEDOMER",,"06 88 17 35 40"
"A1","7010","M.","DUONG","Steven",20/03/1989,"5 impasse des Hautes Bornes","94200","IVRY-SUR-SEINE","0143908665","0618516371"
"A1","7026","M.","EA","You",12/07/1990,"15 boulevard Paul Cézanne","78280","GUYANCOURT","0161383057","0624725694"
"A1","6349","M.","EECKMAN","Cyrille",22/01/1990,"11 rue Lebouteux","75017","PARIS","0147661513","0650059007"
"A1","7036","M.","ESPIAUBE","Antoine",16/10/1987,"101 rue de Meaux","75019","PARIS",,"0682988146"
"A1","6078","M.","FAISIEN FLINOIS","Jonathan",07/10/1990,"2 rue des Pyrénées","92500","RUEIL-MALMAISON","01 47 08 29 92","06 28 75 70 76"
"A1","6997","M.","FARRENC","Pierre-Adrien",25/09/1989,"68 bis route de Croissy","78110","LE VESINET",,"+33672183355"
"A1","6494","M.","FARSAT","Romain",10/03/1990,"45 rue d\'Avron","75020","PARIS","01 40 09 72 36","06 78 01 54 51"
"A1","6208","M.","FASOLO","Aurélien",20/05/1990,"10 rue des Pyramides","93700","DRANCY","01 48 31 67 52","06 65 37 08 88"
"A1","05768","M.","FAUCHE","Tugdual",16/09/1988,"59 rue Boileau","75016","PARIS","01 42 88 06 25","06 37 61 73 75"
"A1","6153","M.","FAVRE","Cédric",03/10/1989,"6 rue de Madrid","78400","CHATOU","01 30 71 33 05","06 77 38 27 08"
"A1","6210","M.","FENDT","Barthélémy",29/06/1990,"57 bis rue de Joinville","94120","FONTENAY SOUS BOIS","01 48 77 47 41","06 78 25 69 61"
"A1","7025","M.","FERREIRA","Mathieu",10/05/1990,"55 rue Farman","51450","BETHENY","0326020425","0645290029"
"A1","6080","M.","FLON","Valentin",30/05/1990,"68 rue Maurice Bokanowski","92600","ASNIERES-SUR-SEINE",,"06 43 83 98 27"
"A1","6082","M.","FOURNIER","Romain",21/09/1990,"26 rue de Billancourt","92100","BOULOGNE-BILLANCOURT","01 46 03 79 78","06 14 42 74 48"
"A1","7035","M.","GACHADOAT","Alexis",09/08/1988,"179 boulevard de la République","92210","SAINT-CLOUD","01 46 02 32 05","06 47 88 65 46"
"A1","6982","MLLE","GAFFINEL","Charlotte",05/01/1990,"4 rue Chernoviz","75016","PARIS",,
"A1","6237","M.","GAUTARD","Benjamin",16/05/1990,"77 rue Galliéni","92100","BOULOGNE-BILLANCOURT","01 46 05 20 69","06 83 57 43 02"
"A1","6212","M.","GERY","Thibaut",29/03/1990,"19 rue Pasteur","92160","ANTONY","01 46 66 54 06","06 74 07 31 81"
"A1","6084","M.","GIRARD","Olivier",11/03/1991,"42 avenue de Catinat","95210","SAINT-GRATIEN","01 39 64 37 79","06 17 42 18 60"
"A1","7004","M.","GOARANT","Alexis",25/02/1989,"37 avenue du Château","92190","MEUDON","0145078878","0669773148"
"A1","6166","M.","GOTENI","Gatien",21/05/1990,"8 rue Michel de Bourges - A14","75020","PARIS",,"06 25 68 36 18"
"A1","6085","M.","GOUBIER","Adrian",19/03/1991,"37 route de Chéroy","89150","DOMATS","03 86 86 39 67","06 84 04 38 49"
"A1","6314","M.","GROLIER","Maximilien",16/09/1989,"76 rue de Richelieu","75002","PARIS",,"06 72 69 07 01"
"A1","6993","M.","HASSANALY MERALY","Yannick",14/01/1990,"4 rue des Fossés","77000","MELUN","0164879238","0659924435"
"A1","6087","M.","HUMBERT","Antoine",16/03/1990,"58 rue de Courcelles","75008","PARIS","01 42 89 09 69","06 75 75 07 98"
"A1","7028","MLLE","HUSSON","Charlotte",15/05/1989,"24 rue Roger Robereau","78100","ST GERMAIN EN LAYE",,
"A1","6088","M.","IMPINNA","Loris",16/07/1990,"15 rue Claude Monet","78200","MANTES-LA-JOLIE","01 30 94 02 83","0642671011"
"A1","6089","M.","ITZINGER","Thomas",06/04/1990,"15 rue de Patay","75013","PARIS","01 45 85 99 16","06 71 77 17 56"
"A1","7002","MLLE","JABALLI","Sarah",11/05/1990,"7 rue Georges Pitard","93700","DRANCY",,"0661620326"
"A1","6213","M.","JARROIR","Alexis",03/12/1990,"14 rue Dupont des Loges","75007","PARIS",,"06 76 84 33 57"
"A1","05771","M.","JARROSSON","Gabriel",26/07/1990,"22 bis rue de l\'Abbé Glatz","92270","BOIS-COLOMBES","01 42 42 74 68","06 18 16 64 48"
"A1","6981","MLLE","JEAUFFROY","Juliette",26/05/1990,"8 bis rue de l\'Arrivée","75015","PARIS","01 45 44 98 69","06 30 28 42 81"
"A1","6091","M.","KOCH","Alexandre",12/08/1989,"4 rue de l\'Eglise","92420","VAUCRESSON","01 47 01 21 74","06 98 34 07 08"
"A1","7006","M.","KOPPEL","Camille",06/02/1991,"4 rue Brancion","75015","PARIS","0142504559","0607487665"
"A1","7015","M.","KOUNVONGLASY","Eric",29/10/1990,"29 rue de Belleville","75019","PARIS","0951195918","0671554740"
"A1","6092","M.","KURZWEG","Guillaume",11/10/1990,"25 rue Victor Hugo","91210","DRAVEIL","01 69 03 06 40","06 86 21 86 78"
"A1","6093","M.","LALAU","Jean",08/05/1990,"5 rue Schwarberg","77144","MONTEVRAIN","01 64 30 30 15","06 26 43 26 56"
"A1","6094","M.","LAMY","Christophe",20/11/1990,"11 rue Laugier","75017","PARIS","09 50 18 01 75","06 89 95 39 92"
"A1","6991","M.","LASSIEUR","Clément",02/10/1990,"62 rue du Théâtre","75015","PARIS","0145785052","0685476344"
"A1","6797","M.","LE BAILLY","Marc",21/01/1989,"3 villa Robert Lindet","75015","PARIS","01 48 28 02 26","06 82 49 71 14"
"A1","7013","M.","LECOMTE","Louis",18/08/1990,"75 rue Perronet","92200","NEUILLY-SUR-SEINE",,
"A1","6096","M.","LEGEARD","Paul",29/08/1991,"9 rue Daguerre","75014","PARIS",,"06 26 36 31 78"
"A1","6216","M.","LEGROS","Olivier",03/05/1990,"48 rue Rouget de Lisle","92800","PUTEAUX","01 45 06 04 58","06 32 99 13 51"
"A1","6155","M.","LENFANT","Antonin",07/08/1990,"56 bis boulevard Masséna","75013","PARIS","01 53 60 03 69","06 34 95 45 38"
"A1","6217","M.","LEVY","Karim",06/05/1990,"6 rue Claude Matrat","92130","ISSY-LES-MOULINEAUX","01 41 90 90 97","06 63 18 08 70"
"A1","6218","M.","LONGEAULT","Thomas",03/08/1990,"7 avenue Alexandre III","78600","MAISONS-LAFFITTE","01 39 62 15 93","06 33 57 07 65"
"A1","6219","M.","LOREILLE","Pierre",24/08/1989,"57 boulevard Lefebvre","75015","PARIS",,"06 74 91 91 82"
"A1","7030","MLLE","MANCA","Elisabeth",30/12/1991,"11 rue Guillaume Tell","75017","PARIS 17",,"0632322054"
"A1","6356","M.","MARFAING","Arnaud",29/12/1990,"5 ter rue Casteja","92100","BOULOGNE BILLANCOURT","0146214937","0617950761"
"A1","6220","MLLE","MARIUS","Claire",21/05/1989,"54 rue Antoine de Saint Exupéry","78360","MONTESSON","01 30 71 91 75","06 76 03 45 99"
"A1","6097","M.","MARTIN","Théophane",28/01/1989,"132 rue de l\'Abbé Glatz","92270","BOIS-COLOMBES","01 47 60 98 56","06 37 33 13 80"
"A1","6098","M.","MEHEUST","Guillaume",04/01/1990,"94 rue Jean Mermoz","78600","MAISONS-LAFFITTE","01 39 12 32 16","06 29 37 64 85"
"A1","05776","M.","METTENDORFF","Vincent",27/09/1988,"14 rue Courte Pluche","91650","BREUILLET","01 69 94 01 31","06 17 05 25 45"
"A1","7034","M.","MICHENAUD","Alexandre",04/11/1989,"4 bis rue Léon Bobin","78320","LE MESNIL SAINT DENIS","01 34 61 49 45","06 23 29 52 34"
"A1","6812","M.","MIGNOT","Victor",01/01/2010,"31 avenue du Château de Bertin","78400","CHATOU","01 30 71 39 53","06 26 27 68 16"
"A1","05756","M.","MIYARA","Mohamed Yassine",17/12/1989,"15 RUE DE MARSEILLE","75010","PARIS 10",,"0648593420"
"A1","6998","M.","MONDE","Livio",03/11/1990,"2-4 rue Bruneseau","75013","PARIS",,"0648148956"
"A1","6772","M.","MONEIN","Lilian",29/03/1989,"8 clos de la Ferme Châteauneuf","78280","GUYANCOURT","01 30 43 09 45","06 82 12 22 20"
"A1","6222","M.","MONIN","Pierre-André",30/06/1990,"85 rue Louise Michel","78500","SARTROUVILLE","01 39 57 03 47","06 65 19 77 87"
"A1","6156","M.","MORINEAU","Julien",10/10/1989,"2 allée Samuel de Champlain","78560","LE PORT MARLY","01 39 58 15 25","06 72 56 28 76"
"A1","6100","M.","MOURAD","Nicolas",20/05/1989,"10 rue de Belfort","92600","ASNIERES SUR SEINE","01 47 93 15 70","06 08 09 08 61"
"A1","6813","M.","MOUY","Vivien",10/06/1990,"19 rue Michel de l\'Hospital","92130","ISSY-LES-MOULINEAUX","01 46 45 83 89","06 20 01 46 91"
"A1","6979","M.","MOUZOURI","Fouzi",28/08/1990,"29 rue marcel sembat","93350","LE BOURGET","0148365570","0658506397"
"A1","6896","M.","NEF","Daniel",05/09/1988,"16 rue de Bietigheim","94370","SUCY-EN-BRIE","01 45 90 93 61","06 07 21 98 46"
"A1","6158","MLLE","NGATTAI-LAM TOG-NE","Guylène",30/04/1991,"12 rue Emile Reynaud","75019","PARIS",,"06 25 83 54 01"
"A1","7032","MLLE","NGUYEN","Emilie",07/02/1989,"37 avenue Alfred de Musset","78110","LE VESINET",,
"A1","6225","M.","NGUYEN","Gabriel",07/01/1991,"16 rue Pierre Lhomme","92400","COURBEVOIE","01 43 34 21 54","06 88 80 52 65"
"A1","7012","M.","NIKOLIC","Stéphane",03/02/1989,"45 rue Trevet","93300","AUBERVILLIERS","0148390592","06 27 61 01 06"
"A1","7029","MLLE","PAILLER","Juliette",17/04/1989,"38 avenue BUGEAUD","75016","PARIS 16",,"0685415129"
"A1","6105","M.","PANSART","Antoine",24/05/1989,"7 rue Solférino","92700","COLOMBES","01 42 42 36 25","06 26 33 38 17"
"A1","6106","M.","PASSEDROIT","Florent",11/02/1990,"4bis rue Gustave Zédé","75016","PARIS 16",,"06 08 51 99 56"
"A1","6107","M.","PATAILLOT","Rémy",01/04/1990,"8 rue Fronval","78140","VELIZY-VILLACOUBLAY","01 34 65 36 87","06 72 34 03 63"
"A1","6779","M.","PENCREC\'H","Benoît",30/10/1990,"23 bis rue de la Gare","92320","CHATILLON","09 60 08 86 46","06 14 79 80 29"
"A1","6108","M.","PERRIN","Emeric",29/09/1990,"15 allée de la Romance","95800","CERGY","01 34 32 00 07","06 79 59 37 08"
"A1","6224","M.","PETRINKO","Gabriel",17/07/1990,"81 rue Falguière","75015","PARIS 15","0173751059","06 22 18 92 49"
"A1","6109","M.","PHILIPPE","Vincent",07/04/1990,"8 rue Commandant Rene Mouchotte","75015","PARIS 15","02 97 50 77 67","06 68 44 79 22"
"A1","6110","M.","PIERLOT","Romain",01/08/1990,"26 rue du Gué","92500","RUEIL-MALMAISON","01 47 49 72 30","06 76 28 36 90"
"A1","6987","M.","PIFERRER","Lucas",24/05/1989,"33 rue du general leclerc","92130","ISSY LES MOULINEAUX",,"0684899648"
"A1","6159","M.","PORTALIER","Jérôme",05/04/1991,"13 rue de la Duchesse d\'Uzès","78120","RAMBOUILLET","01 34 83 37 45","06 87 86 99 96"
"A1","6227","M.","POZO","Geoffrey",21/04/1990,"46 boulevard de la Marne","94130","NOGENT-SUR-MARNE","01 48 73 54 44","06 74 59 01 56"
"A1","6989","M.","QUACH","Boris",15/12/1989,"33 rue du Général Leclerc","92130","ISSY LES MOULINEAUX",,"06 84 37 80 81"
"A1","6228","M.","RAIMBAULT","Guillaume",25/03/1990,"25 rue des Laisnés","95110","SANNOIS","01 30 25 38 22","06 72 19 25 03"
"A1","6851","M.","REBILLAT","Basile",04/09/1990,"18 allée des Glycines","78410","AUBERGENVILLE","01 30 95 97 18","06 82 39 50 48"
"A1","6167","M.","RESWEBER","Cyril",08/02/1991,"2 rue Saint-Paul","67300","SCHILTIGHEIM","03 88 81 01 66","06 32 33 79 45"
"A1","6229","MLLE","RICHE","Valentine",23/07/1990,"12 rue de l\'Avenir","92260","FONTENAY-AUX-ROSES","01 46 61 61 36","06 29 48 43 08"
"A1","6230","M.","RIOLS","Pierre-Antoine",03/06/1990,"6 rue Saint-Paul","75004","PARIS","01 42 77 12 27","06 86 31 68 27"
"A1","6112","M.","RIVIERE","Hervé",07/05/1990,"12 rue Jules Herbron","78220","VIROFLAY","01 30 24 11 96","0671140209"
"A1","7000","MLLE","ROESCH","Axelle",07/06/1989,"21 avenue de la criolla","92150","SURESNES","0140990877","0633204062"
"A1","6990","M.","ROY","Nicolas",04/07/1990,"41 rue st andré des arts","75006","PARIS 06","0182092617","0632348209"
"A1","6113","MLLE","RUBY","Pétronille",09/12/1990,"53 avenue de Paris","78000","VERSAILLES","01 39 51 69 50","06 67 02 04 72"
"A1","6232","M.","SAN","Joël",15/08/1989,"15 rue Jean Monnet","93420","VILLEPINTE","01 43 84 76 78","06 46 86 36 48"
"A1","7008","M.","SARRAUSTE DE MENTHIERE","Pierre-Alexandre",28/09/1989,"36 avenue du Maréchal Douglas Haïg","78000","VERSAILLES",,"0623073785"
"A1","6160","M.","SEFFAR","Mohamed Karim",27/03/1990,"10 rue du Regard","75006","PARIS","09 53 40 97 30","06 47 40 19 39"
"A1","6358","M.","SENKWENDA","Andrew",29/11/1990,"14 rue des Fenaisons","77700","MAGNY LE HONGRE","0164632799","0626949533"
"A1","6161","M.","SIBILLE","Maxime",12/11/1989,"148 rue castagnary","75015","PARIS 15","0173798508","06 32 63 60 31"
"A1","6115","M.","SIDER","Léo",16/11/1990,"52 rue Sedaine","75011","PARIS",,"06 63 35 89 45"
"A1","6116","M.","SIVAHARY","Samson",20/07/1990,"114 route de Saint Leu","93430","VILLETANEUSE","01 48 27 76 07","06 23 93 77 72"
"A1","6162","M.","SKAF","Dany",12/11/1990,"69 rue d\'Alleray","75015","PARIS","01 42 50 15 20","06 87 56 39 64"
"A1","6978","M.","TAÏEB","Maxime",22/03/1989,"91 place haute","92100","BOULOGNE-BILLANCOURT","0146215270","0672713743"
"A1","7003","MLLE","TANIOS","Joy",24/05/1990,"37 rue de la République","92800","PUTEAUX",,
"A1","6117","M.","TASSIUS","Kévin",21/06/1990,"24 chemin des Mousseaux","91270","VIGNEUX SUR SEINE","01 69 03 29 93","06 66 52 20 62"
"A1","6897","M.","TAULERA","Kevin",01/03/1989,"10 impasse Bonsecours","75011","PARIS","01 43 73 18 08","06 24 06 71 91"
"A1","7001","MLLE","TERRAL","Chloé",06/03/1990,"16 rue bosquet","75007","PARIS 07",,"0674596914"
"A1","08889",,"TESTING","Prénoming",01/01/2010,,,,,
"A1","7016","M.","THENG","Johnny",10/11/1990,"70 rue Jean le Galleu","94200","IVRY SUR SEINE","0146727515","0614696244"
"A1","7021","M.","THOUVENOT","Erwan",26/01/1991,"2 rue du vallon","92160","ANTONY","0142372177","0623350272"
"A1","6118","M.","TOUATI","Gary",10/06/1990,"88 avenue Henri Martin","75116","PARIS",,"06 64 15 08 22"
"A1","05841","M.","TRACHLI","Ilias",12/04/1989,"28 rue ND des Champs","75006","PARIS",,"06 20 67 39 77"
"A1","7018","M.","TRACHSEL","Matthieu",14/08/1990,"8 avenue Affre","91800","BRUNOY",,"0673855052"
"A1","6235","M.","TRORIAL","Pierre-Damien",12/03/1990,"163 rue de Charenton","75012","PARIS","01 43 44 00 43","06 30 37 04 74"
"A1","6119","M.","TRUPEL","Thomas",08/05/1990,"4 allée Diderot","92160","ANTONY","01 56 45 07 34","06 18 91 71 49"
"A1","6120","MLLE","TRUTTMANN","Céline",03/10/1990,"16 allée des Charmes","78340","LES CLAYES SOUS BOIS",,"06 63 31 99 20"
"A1","6121","M.","TUOT","Pierre",25/09/1990,"9 rue des Morillons","75015","PARIS","01 40 43 15 26","06 19 86 92 04"
"A1","6983","MLLE","VANIER","Alexandra",10/12/1990,"4 rue des Mariniers","75014","PARIS",,
"A1","6975","M.","VARILH","Franck",14/12/1990,"92 RUE D\'ESTIENNE D\'ORVES","92500","Rueil-Malmaison","0147499845","0609550607"
"A1","6123","M.","VAZ","Philippe",06/01/1990,"10 rue Bourgeois","95170","DEUIL LA BARRE","01 39 83 88 70",
"A1","05869","M.","VIDEA","Charles",24/05/1989,"20 rue Cécile Vallet","92340","BOURG-LA-REINE","01 46 64 90 35","06 66 98 10 41"
"A1","6996","M.","VILA","Antoine",14/06/1990,"16 rue Edmond Roger Batiment B","75015","PARIS 15",,"0662351880"
"A1","6125","M.","VOIRIN","Adrien",09/04/1990,"2 allée des Ecureuils","78340","LES CLAYES SOUS BOIS","01 30 56 06 32","06 75 48 68 79"
"A1","6126","MLLE","WALLON","Marie",28/02/1990,"15 rue des Acacias","95170","DEUIL LA BARRE","01 34 28 36 50","06 98 27 80 62"
"A1","6999","M.","WANG","Patrick",30/07/1990,"12 place Félix Eboué","75012","PARIS 12","0143411563","0628432419"
"A1","6163","M.","WELSCH","Robin",04/04/1991,"29 rue Victor Hugo","92130","ISSY LES MOULINEAUX",,"06 84 52 74 79"
"A1","7011","M.","WOLFF","Romain",28/02/1989,"42 avenue de segur","75015","PARIS 15",,
"A1","7014","M.","YOU","Rémy",29/07/1989,"31 rue de l\'université","75007","PARIS 07",,"0679192920"
"A1","05875","M.","ZHU","Charles",04/02/1988,"40 Rue Desaix","75015","PARIS 15",,
"A1-DOUBL","6595","M.","MORIN","Michaël",01/11/1987,"33 rue du Général Leclerc","92130","ISSY LES MOULINEAUX",,"06 68 23 03 40"
"A1-SEM","6585","M.","KHALIDI","Amine",26/08/1989,"33 rue du Général Leclerc","92130","ISSY LES MOULINEAUX",,"0617111377"
"A1-SEM","05838","M.","SAAD","Samy",15/07/1989,"18 boulevard Edgar Quinet","75014","PARIS",,"06 98 63 09 20"
"A2","6563","MLLE","ABOU CHAHINE","May",26/02/1990,"171 rue de la Pompe","75116","PARIS","01 45 53 08 52","06 32 66 34 98"
"A2","05521","M.","ADRIAN","Cyril",23/02/1988,"26 rue des Sablons","92140","CLAMART","01 41 08 06 65","06 26 90 15 18"
"A2","05359","M.","ALBISETTI","Nicolas",26/08/1988,"53 avenue de la Ferme des Hezards","78112","FOURQUEUX","01 39 73 32 19","06 71 02 25 05"
"A2","6564","MLLE","ALI ABDEL MAKSOUD","Aya",01/05/1990,"10 rue d\'Orsel","75018","PARIS",,"06 18 77 04 28"
"A2","6565","M.","ANDREELLI","Jérémy",24/06/1988,"Route d\'Yvette - Lieu dit Girouard","78320","LEVIS SAINT NOM","01 39 38 66 56","06 20 00 87 63"
"A2","05718","M.","ARDAILLON","Luc",11/04/1989,"Les Champs","74440","MORILLON","04 50 47 05 73","0687641321"
"A2","05701","M.","AUDE","Kévin",05/09/1989,"1 avenue des Tilleuls","94450","LIMEIL BREVANNES","01 45 99 46 01","06 88 23 85 18"
"A2","05806","MLLE","AUGUSTIN","Mathilde",03/07/1989,"7 sente des Jardins Nouveaux","78510","TRIEL SUR SEINE",,"06 13 19 73 83"
"A2","05846","M.","BARBIER","Guillaume",29/01/1990,"9 rue de la Raffière","78112","FOURQUEUX","01 30 61 16 92",
"A2","05761","M.","BARBIER","Vincent",21/07/1989,"16 rue Jean-Claude Arnould","75014","PARIS",,"06 70 07 75 45"
"A2","6566","M.","BARJERON","Alexandre",25/07/1989,"39 avenue Georges Clémenceau","95160","MONTMORENCY",,"06 72 04 08 34"
"A2","6436","M.","BATAILLE","Nicolas",18/06/1987,"6 bis route de Boran","95270","VIARMES","01 34 68 05 16","06 70 73 70 32"
"A2","6567","M.","BELLAïCHE","Stéphen",07/12/1989,"15 bis avenue Georges Clémenceau","95160","MONTMORENCY","01 39 64 00 77","06 71 16 49 16"
"A2","6568","MLLE","BENALI","Sabrina",13/01/1989,"31 rue Ernest Renan","92190","MEUDON","01 46 23 98 97","06 23 90 09 66"
"A2","05823","M.","BENSOUDA KORAÏCHI","Hamza",10/04/1989,"30 rue Brancion","75015","PARIS","01 71 50 78 66","06 16 20 60 30"
"A2","05714","M.","BERRIER","Julien",12/04/1989,"6 impasse Villebois Mareuil","95240","CORMEILLES EN PARISIS","01 39 97 59 36","06 60 90 65 90"
"A2","6640","M.","BERTRAND","Arnaud",30/01/1989,"5 rue de la Croix des Mortiers","78350","LES LOGES EN JOSAS","01 39 56 12 84","06 79 94 96 03"
"A2","05842","M.","BEYER","Romain",18/11/1989,"15 rue Marcel Lamant","94200","IVRY SUR SEINE","01 46 72 88 58","06 80 24 59 59"
"A2","05286","M.","BOLLENGIER STRAGIER","Vincent",02/11/1983,"73, boulevard Ornano","75018","PARIS",,"06 23 06 48 70"
"A2","05699","M.","BORIN","Maxime",27/12/1989,"43 avenue Reille","75014","PARIS",,"06 89 06 28 38"
"A2","05446","M.","BOU NADER","Fouad",27/04/1988,"132 rue de l\'Abbé Groult","75015","PARIS","01 40 88 33 41","06 29 59 47 70"
"A2","6569","M.","BOUCAUD","Eric",01/04/1988,"48 rue d\'Avron","75020","PARIS","01 43 72 70 43","06 89 58 35 36"
"A2","05717","MLLE","BOUDARD","Mélanie",27/02/1989,"39 rue Daguerre","75014","PARIS 14",,"06 73 52 23 02"
"A2","6570","M.","BOUILLET","Jean-Luc",03/11/1989,"33 rue du Général Leclerc","92130","ISSY LES MOULINEAUX","05 87 70 87 15","06 76 35 29 60"
"A2","05822","M.","BOUILLOT","Adrien",28/07/1989,"62 rue de Corbeil","91090","LISSES","01  64 97 58 32","06 31 34 60 96"
"A2","05712","M.","BOUTMY","Guillaume",02/02/1989,"33 rue Raymond Queneau","92500","RUEIL MALMAISON","01 47 49 66 47","06 33 59 94 94"
"A2","6572","M.","BURIE","David",17/08/1988,"43 rue de la Tourelle","91310","LONGPONT SUR ORGE","01 69 63 80 56","06 26 62 13 08"
"A2","05844","M.","CHARRIER","Alexis",31/10/1989,"65 bis chemin de Cormeilles","78400","CHATOU","01 39 52 80 35","06 50 80 08 82"
"A2","6439","MLLE","CHEN","Xiaole",31/07/1988,"183 rue de Charonne - Studio 202","75011","PARIS",,"06 15 59 77 40"
"A2","6440","M.","CHIU","Rémy",04/10/1988,"36 rue Alphonse Karr - Hall 14","75019","PARIS","01 40 36 60 85","06 09 99 66 92"
"A2","05498","M.","COHET","Pierre-Louis",29/09/1987,"41 bld Saint-Jacques","75014","PARIS",,"06 26 24 52 76"
"A2","05708","M.","COQUELLE","Baptiste",27/10/1988,"12 place jeanne d\'arc","78120","RAMBOUILLET","01 34 83 90 12","06 78 85 42 95"
"A2","05748","MLLE","COSTET","Charlotte",04/01/1990,"28 rue de Vintué","91580","ETRECHY","01 60 80 55 49","06 89 78 69 05"
"A2","05924","M.","CUZIN","Arthur",23/09/1989,"72 rue des Bourguignons","92600","ASNIERES / SEINE","01 47 33 67 92","06 27 67 74 88"
"A2","05868","M.","DADAGLO","Gilchrist",02/05/1990,"22 rue du colonel Pierre Avia","75015","PARIS 15",,"06 20 40 61 35"
"A2","05777","MLLE","DARIANE","Carole",26/07/1989,"62 rue Cramail","92500","RUEIL- MALMAISON","01 47 49 59 02","06 50 91 29 18"
"A2","05724","M.","DAUBRY","Olivier",22/03/1990,"23 rue des Moines","75017","PARIS","0177133706","06 87 24 70 71"
"A2","6441","M.","DAUTRIAT","Fabrice",08/02/1987,"52 rue Caulaincourt","75018","PARIS","01 42 23 03 36","06 32 95 36 58"
"A2","05763","M.","DE CROISOEUIL-CHATEAURENARD","Raphaël",14/12/1989,"58 avenue Théophile Gautier","75016","PARIS","01 42 88 43 67","06 12 28 46 19"
"A2","6643","M.","DE LA BARGE DE CERTEAU","Jean-Michel",11/09/1988,"1 allée Meissonier","92380","GARCHES","01 47 01 23 90","06 62 65 35 88"
"A2","6644","M.","DEGONZAGUE","Florian",15/06/1988,"5 cité Falguière","75015","PARIS",,"06 25 79 45 73"
"A2","6574","M.","DELHOMME","Benoit",16/05/1989,"4 quai Bir-Hakeim","94410","SAINT MAURICE","01 48 83 20 83","06 67 26 72 27"
"A2","05713","MLLE","DELMAS","Charlotte",11/10/1989,"12 rue Lakanal","75015","PARIS",,"06 99 55 54 78"
"A2","05716","MLLE","DELTOMBE","Constance",03/12/1988,"22 rue Vavin","75006","PARIS",,"06 88 11 32 04"
"A2","6442","M.","DESCAMPS","Alexandre",27/06/1989,"20 place de Beaume","78990","ELANCOURT","01 34 82 66  42","06 66 33 53 16"
"A2","6258","M.","DHOBB","Amine",20/12/1988,"33 rue de l\'Ourmel","75015","PARIS","01 42 24 20 62","06 98 94 32 55"
"A2","05767","M.","DIALLO","Alexandre",23/06/1989,"70 boulevard Auguste Blanqui","75013","PARIS",,"06 61 95 71 30"
"A2","05837","M.","DONON","Quentin-Maximilien",23/01/1989,"40 rue Anna Jacquin","92100","BOULOGNE BILLANCOURT","01 46 05 78 93","06 66 61 44 93"
"A2","6645","M.","DUMAS","Guillaume",10/11/1987,"29 bis rue de Verdun","95240","CORMEILLES EN PARISIS","01 34 50 14 93","06 65 47 28 19"
"A2","05778","M.","DUPONT","Baptiste",20/11/1989,"4 parc de la bérengère","92210","SAINT CLOUD","01 46 02 30 76","06 81 43 69 47"
"A2","05787","M.","DURAND","Quentin",19/11/1988,"Bat 114  134 boulevard Brune","75014","PARIS","01 45 45 06 76","0626490432"
"A2","05709","M.","FAHIER","Nicolas",14/11/1989,"4 rue Camille Desmoulins","92300","LEVALLOIS-PERRET","01 47 58 49 18","06 85 99 81 83"
"A2","05435","M.","FARRAH DORWLING-CARTER","Lewis",05/10/1988,"104 rue du Théâtre","75015","PARIS",,"06 73 55 86 37"
"A2","6646","M.","FERNANDES","Alexandre",09/12/1988,"10 rue Jacques Prévert","78480","VERNEUIL SUR SEINE","01 39 28 00 92","06 01 85 96 38"
"A2","6575","M.","FILIPOV","Harry",07/05/1989,"18 rue de la Forme","78420","CARRIERES-SUR-SEINE","01 39 57 97 23","06 27 60 32 03"
"A2","6443","M.","FONDI DE NIORT","Guillaume",19/03/1989,"129 bd de la Reine","78000","VERSAILLES","01 39 02 33 54","06 08 51 87 06"
"A2","05702","M.","FONTAINE","Jean-Baptiste",16/06/1989,"6 square Claude Debussy","75017","PARIS",,"06 33 69 48 36"
"A2","05805","M.","FRAYSSE","Olivier",25/08/1989,"33 rue du Général Leclerc","92130","ISSY LES MOULINEAUX","04 67 84 17 14","06 08 22 20 70"
"A2","05845","M.","FREYSSINIER","François",01/01/1989,"241 rue de la Croix-Nivert","75015","PARIS 15",,"06 89 64 35 26"
"A2","6576","M.","GACHET","Adrien",10/07/1989,"92 rue de l\'Ouest","75014","PARIS",,"06 71 76 26 99"
"A2","05711","M.","GAILLOT-DREVON","Benoît",29/12/1989,"52 rue Rémy Dumoncel","75014","PARIS",,"06 83 66 60 10"
"A2","05530","M.","GALDON","Pierre",10/09/1988,"183 rue du Faubourg Poissonnière","75009","PARIS","01 48 78 29 54","06 61 00 57 78"
"A2","05785","MLLE","GALLET","Emmanuelle",16/10/1989,"149 avenue du Maine","75014","PARIS 14",,"06 16 03 65 39"
"A2","05361","M.","GALLOU","Julien",17/04/1988,"37 rue Rouget de Lisle apt 905","92130","ISSY LES MOULINEAUX",,"06 26 51 33 36"
"A2","6577","M.","GANESAPILLAI","Sushiharan",12/06/1989,"20 square de la Garenne","95500","GONESSE","01 39 85 78 61","06 42 03 94 76"
"A2","6578","MLLE","GARNIER","Marina",04/09/1988,"30 bis rue de la Grande Fontaine","78100","SAINT GERMAIN EN LAYE","01 39 73 67 51","06 20 25 92 91"
"A2","05719","MLLE","GARNIER","Soizic",11/11/1989,"8 sente des Rois","78480","VERNEUIL SUR SEINE","01 39 65 96 27","06 26 32 88 22"
"A2","05790","MLLE","GELABERT","Alissia",23/12/1989,"25 rue Miollis","75015","PARIS","01 42 19 05 68","06 38 93 75 25"
"A2","6579","M.","GHOLEM","Yannis",18/11/1988,"36 rue Verte","94400","VITRY SUR SEINE","01 46 78 46 22","06 98 11 89 54"
"A2","6580","M.","GHULAM","Ajaz",19/04/1989,"1 rue de la Commune de Paris","93450","ILE SAINT DENIS","01 48 09 89 40","06 13 54 93 66"
"A2","05779","M.","GLAS","Adrien",03/04/1989,"8 rue des pyrénées","92500","RUEIL MALMAISON","01 47 08 27 23","06 07 49 87 89"
"A2","6648","M.","GOBILLOT","Matthieu",06/01/1988,"42 rue Hoffmann","92340","BOURG LA REINE","01 49 73 51 04","06 27 39 36 03"
"A2","6317","M.","GODBOUT","Anthony",14/04/1988,"75 rue de prony","75017","PARIS 17",,"06 50 34 89 23"
"A2","6581","M.","GONCALVES","Thibaud",08/02/1989,"33 rue des Terres au Curé","75013","PARIS",,"06 26 31 16 70"
"A2","05769","M.","GUENEE","Xavier",04/01/1988,"178 rue de Javel","75015","PARIS","01 48 28 25 66","06 82 43 98 27"
"A2","6444","M.","GUERITOT","Mathieu",22/05/1989,"55 rue Jean Jaurès - Bât 1","92170","VANVES","01 46 45 44 61","06 33 42 48 86"
"A2","6312","MLLE","GUITARD","Florence",04/03/1989,"25 rue Louis Girard","78140","VELIZY","01 39 46 51 88","06 11 25 29 98"
"A2","05781","MLLE","GUITTARD","Hélène",22/05/1989,"12 rue des Fleurs","91470","LIMOURS","01 64 91 43 64","06 73 47 13 32"
"A2","05826","M.","HALABI","Nader",09/09/1989,"12 rue Marie Galante","92500","RUEIL MALMAISON","01 47 32 16 11","06 48 12 15 21"
"A2","6582","M.","HAMON","Baptiste",08/07/1988,"40 rue de Chabrol","75010","PARIS","01 44 79 05 80","06 62 46 74 17"
"A2","6583","MLLE","HERIT","Céline",18/12/1987,"13 allée des Charmes","92500","RUEIL MALMAISON","09 53 75 90 90","06 68 57 25 37"
"A2","6649","M.","HOUDARD","Josselin",08/12/1988,"8 place Charles Digeon","94160","SAINT-MANDE",,"06 64 44 49 84"
"A2","6718","M.","HUANG","Fan",24/05/1987,"33, avenue Général Leclerc","92130","ISSY LES MOULINEAUX",,
"A2","05819","M.","HUON DE KERMADEC","Edouard",28/01/1990,"79 avenue du Roule","92200","NEUILLY SUR SEINE","01 47 22 50 40","06 60 36 50 66"
"A2","05705","M.","IMBERTI","Alexander",03/10/1989,"5 rue de la Plaine","78630","ORGEVAL","01 39 75 40 59","06 74 75 49 45"
"A2","6584","M.","JARRY","Félix",02/01/1989,"33 rue du Général Leclerc","92130","ISSY LES MOULINEAUX",,"06 28 25 46 95"
"A2","05802","MLLE","JEANNE","Stéphanie",18/01/1989,"8 rue de Mouchy","78000","VERSAILLES","01 39 53 94 46","06 78 79 31 60"
"A2","6650","M.","JESTIN","Thomas",02/10/1988,"9 rue Poliveau","75005","PARIS 05","09 54 10 50 77","06 98 97 58 41"
"A2","6446","M.","JOANNARD-LARDANT","Sébastien",06/03/1988,"16 avenue du Président Franklin Roosevelt","92330","SCEAUX","01 43 50 74 58","06 26 19 46 31"
"A2","6651","M.","KESSAB","Achraf",07/11/1989,"4 rue de Citeaux","75012","PARIS 12",,"06 16 73 00 53"
"A2","6586","MLLE","KIEFFER","Samia",21/08/1988,"4 rue Saint Fargeau","75020","PARIS","01 43 64 72 17","06 09 45 41 27"
"A2","05775","M.","LACOMBE","Martin",18/12/1989,"9 rue Daumier","75016","PARIS","01 45 20 00 22","06 32 95 08 08"
"A2","6587","M.","LAGEDER","Benedikt",15/08/1988,"33 rue du Général Leclerc","92130","ISSY LES MOULINEAUX",,"06 68 51 74 56"
"A2","05523","M.","LAMBERT","Florent",11/09/1988,"2 rue Juliette Dodu","75010","PARIS",,"06 85 29 53 01"
"A2","6588","MLLE","LANIER","Cécile",18/10/1988,"71 avenue Emile Zola","75015","PARIS","01 60 11 54 16","06 60 48 20 77"
"A2","6447","M.","LASSAUT","Benjamin",18/07/1989,"1 C rue de  Buzenval","78420","CARRIERES SUR SEINE","01 39 13 75 84","06 48 13 55 80"
"A2","6589","M.","LAURET","Julien",19/10/1989,"114 rue de Patay","75013","PARIS","02 62 46 16 15","06 92 27 17 59"
"A2","05772","M.","LE BIVIC","Thomas",29/07/1989,"30 rue François Bonvin","75015","PARIS","01 53 86 97 49","06 82 17 73 91"
"A2","05482","M.","LE CLEAC\'H","Vincent",13/10/1989,"21 rue Mayet","75006","PARIS",,"06 80 50 14 34"
"A2","05751","M.","LE GRELLE","Quentin",02/10/1989,"5 avenue de Bellevue","78150","LE CHESNAY","01 39 43 95 57","06 23 16 41 04"
"A2","05880","M.","LE PENVEN","David",10/10/1988,"3 avenue du Grand Veneur","78110","LE VESINET","01 39 76 08 21","06 16 83 68 22"
"A2","05773","M.","LE TANOU","Maxime",25/09/1989,"5 rue Charles Desvergnes","92190","MEUDON","01 46 26 01 54","06 68 11 56 40"
"A2","6590","M.","LE VERGER","Yoann",19/12/1989,"33 rue du Général Leclerc","92130","ISSY LES MOULINEAUX",,"06 78 03 03 67"
"A2","05710","MLLE","LEBEL","Anne",28/09/1989,"16 boulevard Garibaldi","75015","PARIS","01 47 83 53 61","06 76 79 88 24"
"A2","05752","M.","LECLANCHER","Hugo",16/05/1989,"11 rue de la Chênaie","91530","SAINT-CHERON","01 64 56 35 74","06 63 12 18 42"
"A2","05758","M.","LECLERCQ","Vianney",29/12/1989,"56 rue Falguière","75015","PARIS","01 45 38 71 62","06 86 56 07 05"
"A2","6591","M.","LECRON","Patrick",11/11/1987,"14 domaine de Picardie, 6 rue du Général Pershing","78000","VERSAILLES","01 39 66 03 34","06 84 57 61 93"
"A2","6459","M.","LEGAGNEUR","Michaël",27/03/1989,"7 rue de Sion","91000","EVRY","01 60 78 63 46","06 29 65 19 61"
"A2","6464","M.","LEHOUCQ","Thomas",04/01/1988,"15 rue Labelonye","78400","CHATOU","01 39 52 03 92","06 18  22 70 57"
"A2","6652","M.","LEITE VELHO","Yannick",02/10/1988,"55 boulevard Saint-Michel","75016","PARIS",,"06 59 82 31 40"
"A2","6592","M.","LEROUX-LOGRE","Thomas",19/01/1989,"51 rue Monttessuy","91260","JUVISY SUR ORGE","01 69 24 74 87","06 87 18 65 59"
"A2","05496","M.","LETELLIER","Ronan",01/10/1988,"14 rue Liancourt","75014","PARIS 14","01 46 47 61 33","06 98 44 34 18"
"A2","05527","M.","LETOURNEUR","Florent",27/12/1987,"41 rue de Fontenay","94130","NOGENT SUR MARNE","01 48 73 85 61","06 75 78 92 25"
"A2","6593","M.","LEVY","Jérôme",27/04/1988,"267 rue Lecourbe","75015","PARIS","01 45 58 66 64","06 58 66 40 40"
"A2","6715","M.","LI","Zhexin",24/02/1988,"33 avenue Général Leclerc","92130","ISSY LES MOULINEAUX",,
"A2","6708","MLLE","LIU","Fang",12/03/1987,"C.I.U.P - 7R bd Jourdan","75014","PARIS",,
"A2","05706","MLLE","LOESCH","Angélique",14/08/1989,"19 avenue Le Nôtre","92420","VAUCRESSON","01 47 95 14 39","06 88 11 21 18"
"A2","05720","M.","LONG","Anthony",05/08/1988,"8 villa Jacques Prévert","94800","VILLEJUIF","01 43 90 92 39","06 74 18 58 64"
"A2","05799","M.","LOSSING","Nelson",16/02/1988,"65 rue de Javelot  Tour Mexico","75013","PARIS","01 45 84 11 40","06 99 14 54 06"
"A2","05789","M.","LUMBROSO","Théo",22/09/1989,"70 boulevard Auguste Blanqui","75013","PARIS",,"06 88 69 79 79"
"A2","05782","M.","MACHADO","Alexandre",06/01/1989,"46 rue d\'Erevan","92130","ISSY LES MOULINEAUX","01 46 38 85 48","06 76 02 91 72"
"A2","05870","M.","MAKSIMOVIC","Dimitrije",21/03/1989,"34 rue Mathurin Régnier","75015","PARIS",,"06 68 85 55 33"
"A2","6653","M.","MAMODHOUSSEN","Rahim",03/11/1988,"8 rue Jean Daudin","75015","PARIS",,"06 59 70 79 96"
"A2","05774","M.","MARTIN DE COMPREIGNAC","Godefroy",29/01/1989,"27 rue Champ Lagarde","78000","VERSAILLES","01 30 21 77 52","06 11 89 13 84"
"A2","6594","M.","MATRAB","Mohamed",06/04/1986,"33 rue du Général Leclerc","92130","ISSY-LES-MOULINEAUX",,"06 09 41 66 83"
"A2","6318","M.","MERIAUX","Jean-Baptiste",06/09/1989,"53 rue d\'Estienne d\'Orves","94880","NOISEAU","01 45 90 81 12","06 71 43 47 78"
"A2","6558","M.","MERRAN","Yohann",21/01/1988,"4 Rue du Docteur Tuffier","75013","PARIS 13",,"06 27 04 16 95"
"A2","05786","M.","MILLET","Kenzo",12/12/1989,"4 rue Sébastopol","92400","COURBEVOIE","01 43 34 05 87","06 20 55 23 73"
"A2","05797","M.","MONTIGNY","Erik",13/08/1989,"21 rue Barbet de Jouy","75007","PARIS","01 45 51 72 42","06 14 85 21 98"
"A2","05800","M.","MOTTE","Louis",30/11/1988,"69 rue du Gal Leclerc","92270","BOIS COLOMBES","01 41 19 07 83","06 58 04 24 19"
"A2","05818","MLLE","MOUKAIDECHE","Hakima",26/04/1989,"4 rue de citeaux- Chambre 631d","75012","PARIS 12",,"06 25 24 28 73"
"A2","6663","M.","NACIRI GHOUMARI","Nacer",12/05/1987,"47 boulevard du Montparnasse","75006","PARIS","01 45 44 28 54","06 21 84 49 80"
"A2","6596","M.","NAU VALBAK","Gaspard",15/01/1989,"70 rue Victor Hugo","94700","MAISONS-ALFORT","01 43 53 90 89","06 46 41 31 34"
"A2","6597","MLLE","OUZEROUT","Linda",14/03/1988,"16 rue Marius Delcher","94220","CHARENTON LE PONT","01 79 56 63 11","06 85 62 60 95"
"A2","05796","M.","PALAYRET","Ambroise",28/08/1989,"39 bis quai de Grenelle","75015","PARIS",,"06 50 14 95 62"
"A2","6717","M.","PAN","Peng",25/12/1987,"33 avenue Général Leclerc","92130","ISSY LES MOULINEAUX",,
"A2","05848","M.","PAULUS","Maxime",19/06/1989,"33 rue du Général Leclerc","92130","ISSY LES MOULINEAUX",,"06 61 95 75 66"
"A2","05836","M.","PFLEGER","Nathan",24/12/1989,"23 rue Danton","92300","LEVALLOIS PERRET",,"06 50 55 90 85"
"A2","05721","M.","PHANTHAVONG","Denis",19/08/1989,"10 rue des Bleuets","77090","COLLEGIEN","01 60 35 91 91","06 72 44 58 52"
"A2","05754","M.","PIGNAL","Alexis",15/01/1989,"54 rue du Mal Foch","78600","MAISONS LAFITTE","01 34 93 98 55","06 11 25 86 54"
"A2","05454","M.","PIJEAUD","Nicolas",24/12/1988,"21 rue Vincent Van Gogh","95170","DEUIL LA BARRE","01 39 83 74 38","06 88 54 96 68"
"A2","05416","M.","POITE","Guillaume",30/12/1988,"276 boulevard Raspail","75014","PARIS","01 43 35 23 00","06 17 15 77 19"
"A2","05715","M.","POUCHAIN","Guillaume",01/03/1989,"71 avenue Montgolfier","93190","LIVRY GARGAN","01 43 81 90 67","06 61 56 14 92"
"A2","05817","M.","PREVAL","Thibaut",03/08/1989,"6 rue de Maubeuge","75009","PARIS","01 45 26 59 53","06 42 82 03 92"
"A2","6707","MLLE","QUAN","Chao",28/02/1989,"FOYER REILLE - 34 av. Reille","75014","PARIS",,
"A2","05835","MLLE","QUERSONNIER","Caroline",20/06/1989,"16 quai Alphonse Le Gallo","92100","BOULOGNE BILLANCOURT","01 49 09 05 92","06 19 41 37 43"
"A2","05489","M.","RABANT","Stéphane",08/10/1987,"70 rue Jean Jaurès","92170","VANVES","01 46 45 73 16","06 34 04 39 73"
"A2","05820","MLLE","RAJCA","Béatrice",31/08/1989,"93 rue du Ménil","92600","ASNIERES SUR SEINE","01 47 90 14 67","06 17 07 71 68"
"A2","6598","M.","RAKOTONANDRIANINA","Timothée",27/03/1989,"6 allée Nadar","91220","LE PLESSIS PATE","01 60 84 64 27","06 47 49 93 06"
"A2","05762","M.","REQUIN","Benjamin",10/08/1989,"12 rue Halphen","92700","COLOMBES","01 47 84 86 61","06 78 59 45 62"
"A2","05849","M.","REVIDAT","Richard",11/08/1987,"14 rue Franklin","92400","COURBEVOIE","01 47 45 83 86","06 27 00 08 09"
"A2","6599","MLLE","ROOS","Charlotte",27/10/1988,"13 avenue Henri IV","92190","MEUDON","01 46 26 97 52","06 43 36 32 56"
"A2","6655","M.","ROPION","Josselin",16/09/1989,"34 rue Charles Auray - BL 45","93500","PANTIN",,"06 14 39 72 21"
"A2","6289","M.","ROYER DE VERICOURT","Bertrand",01/04/1988,"2 rue Maréchal de Lattre de Tassigny","78000","VERSAILLES","01 39 54 79 25","06 69 44 06 17"
"A2","6661","M.","ROZIER","Jean-Baptiste",11/07/1989,"4 rue Leriche","75015","PARIS",,"06 16 87 30 72"
"A2","05872","M.","RUDIGOZ","Augustin",17/03/1989,"50 avenue victor Cresson","92130","ISSY LES MOULINEAUX",,"06 22 94 69 59"
"A2","05697","MLLE","RUHLA","Lydwine",03/11/1989,"20 T rue de l\'Egalité","91590","CERNY","01 64 57 50 95","06 89 41 55 78"
"A2","05783","M.","SALLE","François-Xavier",18/08/1989,"91 avenue de Saint Cloud","78000","VERSAILLES","01 39 49 48 62","06 85 03 34 28"
"A2","6600","M.","SALLOUA","Jérémie",17/09/1988,"59 rue du Château des Rentiers","75013","PARIS","01 45 86 85 50","06 31 16 70 99"
"A2","6601","M.","SANTCLIMENS","Guillaume",26/11/1989,"1 rue de Valmy - Résidence La Carmagnole Bâtiment C- Logement 024","93120","LA COURNEUVE",,"06 47 47 82 94"
"A2","6602","M.","SANTOS","Matthieu",17/09/1990,"17 rue des Fontenettes","95550","BESSANCOURT","01 39 32 03 13","06 16 87 04 94"
"A2","6603","MLLE","SCHMITT","Anne-Claire",04/07/1989,"16 route d\'Elancourt","78760","JOUARS PONTCHARTRAIN","01 34 89 44 53","06 75 40 71 77"
"A2","6604","M.","SERRE-ALLAUX","Arnaud",26/06/1990,"3 allée Marie Bréchet","92110","CLICHY","01 55 21 76 26","06 78 34 29 11"
"A2","6605","M.","SEVAGEN","Pierre",27/07/1987,"76 rue Pasteur","95390","SAINT-PRIX","01 39 59 43 40","06 14 91 60 06"
"A2","6951","MLLE","SIM","Sylvia",19/02/1979,"36 rue du Gué","92500","RUEIL-MALMAISON","0951361502","06 64 26 44 27"
"A2","05471","M.","SIMONIN","Joachim",13/08/1988,"148 rue de la Convention","75015","PARIS","01 53 95 08 89","06 66 61 86 49"
"A2","05749","MLLE","SLINGUE","Floriane",21/07/1989,"12 rue de la Fontaine a Mulard","75013","PARIS","01 45 80 06 71","06 89 65 06 68"
"A2","6606","M.","SURROOP","Khalid",06/02/1989,"3 rue Haydn","93200","SAINT-DENIS","01 49 71 05 58","06 14 88 92 05"
"A2","6656","M.","TABUY","Matthieu",26/12/1989,"64 rue du Docteur Jean Vaquier","93160","NOISY LE GRAND","01 43 04 64 37","06 30 04 27 05"
"A2","6657","M.","TACHON","Christophe",21/01/1988,"13 boulevard d\'Indochine - Escalier 21","75019","PARIS","01 77 10 25 46","06 86 93 45 39"
"A2","6607","M.","TAÏEB","Olivier",18/06/1988,"15 bis rue Jean Beausire","75004","PARIS","01 42 77 55 33","06 75 10 71 63"
"A2","6608","M.","TAMPE","Florian",19/12/1989,"227 rue Jean Giono, Résidence Les Pugets - Bât C1","06700","SAINT LAURENT DU VAR","04 92 27 02 52","06 50 89 89 37"
"A2","05764","M.","THAK","Elliott",17/01/1989,"30 rue Chappe","93160","NOISY LE GRAND","01 43 04 08 65",
"A2","05492","M.","TIOLLAIS","Romain",24/11/1987,"16 rue de la Glacière","75013","PARIS","01 43 31 75 92","06 14 83 97 65"
"A2","6609","M.","TISON","Matthieu",10/08/1988,"4 rue de l\'Abbé Grégoire","92130","ISSY LES MOULINEAUX",,"06 27 34 14 37"
"A2","05847","MLLE","TOBAGI","Carina",01/10/1989,"1 rue de Rémusat","75016","PARIS","01 40 50 73 90","06 03 31 75 06"
"A2","6461","M.","TOSSANI","Valentin",05/02/1987,"5 avenue Marcel Ramolfo Garnier","91300","MASSY","01 78 85 54 91","06 21 88 87 28"
"A2","6610","M.","TUAN","Jean-Michel",31/03/1988,"40 rue La Bruyère","93420","VILLEPINTE","01 48 61 36 04","06 80 51 71 79"
"A2","6463","M.","VANDAMME","Grégoire",25/04/1984,"2 rue Pierre Brossolette","92130","ISSY LES MOULINEAUX","09 53 35 50 38","06 25 53 00 54"
"A2","05766","MLLE","VAVASSEUR","Alice",06/02/1990,"94 ter rue Edouard Vaillant","92300","LEVALLOIS PERRET","01 47 31 92 25","06 61 04 70 53"
"A2","6613","M.","VICRAY","Oliver",09/12/1988,"109 Avenue Henri Barbusse - Esc C","93120","LA COURNEUVE","01 48 37 40 19","0675848568"
"A2","05874","M.","VIDON","Benjamin",21/06/1988,"16 rue Saint Pierre","92200","NEUILLY SUR SEINE",,"06 62 16 22 63"
"A2","05788","M.","VIEL-GOUARIN","Grégoire",03/04/1989,"50 avenue Victor Cresson","92130","ISSY LES MOULINEAUX",,"06 61 86 76 61"
"A2","05755","M.","WALGER","Dimitri",18/04/1989,"267 rue Lecourbe - RCH Cour Gauche","75015","PARIS",,"06 68 05 93 80"
"A2","6561","M.","WEBER","Guilhem",10/08/1989,"5 allée Maurice Ravel","95210","ST GRATIEN","01 34 17 69 95","06 22 36 49 19"
"A2","6470","M.","WOJCIESZKO","Guillaume",13/09/1988,"6 rue de Laghouat","75018","PARIS",,
"A2","6562","M.","WOLFF","Cyril",30/01/1988,"12 avenue Dubonnet","92400","COURBEVOIE",,"06 88 83 27 79"
"A2","6711","M.","WU","Qin",07/03/1989,"7 bd jourdan CIUP","75014","PARIS"," 01 53 80 78 43","06 61 21 51 31"
"A2","05723","M.","YA","Kévin",02/02/1989,"12 allée Pierre Brossolette","77200","TORCY","09 65 15 34 44","06 67 04 69 54"
"A2","6659","M.","YAHI","Ismael",18/09/1989,"3 allée d\'Auvergne","78170","LA CELLE SAINT CLOUD",,"06 84 08 73 50"
"A2","6462","M.","ZANKPE","Komlan",16/01/1990,"39 avenue de la Belle Gabrielle","94130","NOGENT SUR MARNE","09 53 75 04 32","06 14 24 92 19"
"A2","6615","M.","ZIATT","Reda",08/07/1987,"110 rue du Cherche-Midi","75006","PARIS",,"06 20 04 43 89"
"A2-DOUBL","05388","M.","CHATELUS","Côme",14/04/1989,"57 avenue du Maine","75014","PARIS",,"06 17 93 51 10"
"A2-DOUBL","05098","M.","DAUMAS","Benoit",21/11/1987,"18, rue de Gascogne","78180","MONTIGNY LE BRETONNEUX","01 30 64 19 67","06 33 16 70 06"
"A2-DOUBL","6276","M.","LORTHIOIS","Augustin",04/05/1987,"17 rue du Maréchal Foch","78220","VIROFLAY","01 30 24 41 80","06 82 66 75 16"
"A2-SEM","6000","MLLE","AOUADI","Sakina",02/11/1987,"22 avenue Louis Georgeon","94230","CACHAN",,"06 98 55 73 57"
"A2-SEM","6396","M.","FAN","Long",23/08/1987,"100 av Mal de Lattre de Tassigny","94000","CRETEIL",,"06 27 04 67 86"
"A2-SEM","05925","M.","HMEUN","Qanyingö",26/12/1986,"37 rue Croix Nivert","75015","PARIS",,"06 09 28 05 21"
"A2-SEM","06013","M.","LI","Kun Yi",14/07/1984,"Cité Universitaire - Maison de l\'Inde   7R bd Jourdan","75014","PARIS",,"06 43 67 48 26"
"A2-SEM","6398","M.","LIU","Yang",17/01/1986,"7R bd Jourdan","75014","PARIS",,"06 20 02 55 64"
"A2-SEM","6303","M.","ZAKINE","Rudy",15/02/1988,"62 avenue Maurice Meyer","95500","GONESSE","01 39 87 22 99","06 26 17 10 81"
"A3","05561","MLLE","ABOUHAZIM","Amina",10/12/1986,"24, rue Sainte Geneviève","91120","PALAISEAU","01 60 14 81 95","06 23 77 01 48"
"A3","05145","M.","AGRAPART","Thibault",07/10/1986,"7, rue Cluseret","92150","SURESNES","01 47 72 41 99","06 23 04 72 65"
"A3","04494","M.","AGUIE","Jean-Edouard",06/04/1986,"30 boulevard Barbès","75018","PARIS",,"06 58 86 75 88"
"A3","6239","MLLE","AÏT-SAÏD","Sara",20/11/1988,"39 bis rue Greneta","75012","PARIS",,"006 15 57 61 28"
"A3","05146","MLLE","ALBIN","Anne-Laure",17/02/1987,"19, avenue Leverrier","94100","SAINT MAUR DES FOSSES","01 43 97 95 80","06 63 52 36 52"
"A3","05443","MLLE","ALLARD","Charlotte",03/10/1988,"17 rue Michel Ange","75016","PARIS",,
"A3","05447","M.","ALLOITEAU","Arnaud",17/06/1988,"85 rue Jean Jaurès","92270","BOIS COLOMBES","01 47 81 32 41","06 23 48 20 63"
"A3","6241","MLLE","AMINOU","Raodath",28/10/1989,"Chez Mme ROUFAI - 1 rue des Alouettes","78420","CARRIERES SUR SEINE",,"06 81 81 06 86"
"A3","05380","M.","ASSAF","Bassel",28/02/1988,"158 boulevard de Stalingrad","94200","IVRY SUR SEINE","09 50 60 57 10","06 25 14 71 33"
"A3","04607","M.","ATTANE","Gautier",25/02/1987,"9, rue Jules Valles","75011","PARIS",,"06 27 89 14 46"
"A3","05493","M.","AUGENDRE","Guilhem",30/06/1987,"62 rue Albert Joly","78000","VERSAILLES","01 39 20 03 08","06 26 90 38 81"
"A3","05365","M.","AVELINE","Benoît",12/02/1988,"8 bis avenue Foch","92380","GARCHES",,"06 33 14 50 40"
"A3","6321","M.","BACLET","Jérémy",21/05/1987,"177 bd Murat 1er Etage Porte Gauche","75016","PARIS","01 45 86 69 37","06 37 67 73 03"
"A3","05376","M.","BALMONT","Fabien",22/02/1988,"11 villa Leblanc","92120","MONTROUGE",,"06 10 67 70 20"
"A3","05560","M.","BALTIDE","Harry",22/03/1985,"23 rue Victor Hugo","92130","ISSY LES MOULINEAUX",,"06 20 51 36 74"
"A3","05827","M.","BANCE","Frédéric",08/01/1987,"15 bis rue Marceau","78800","HOUILLES",,"06 84 88 32 27"
"A3","05940","M.","BARBANT","Kévin",27/05/1986,"Chemin de Galance","84120","LA BASTIDONNE","04 90 07 40 97","06 25 94 81 39"
"A3","04514","M.","BARBIER","Fabien",27/05/1986,"3, rue Le Titien","91440","BURES SUR YVETTE","01 64 46 29 96","06 86 23 67 39"
"A3","05562","M.","BARRAKAD","Mehdi",30/11/1986,"107 avenue Félix Faure","75015","PARIS",,"06 17 05 32 33"
"A3","05487","M.","BARTHELEMY","Aymeric",19/09/1988,"12 rue du Capitaine Ferber","92130","ISSY LES MOULINEAUX",,"06 27 07 37 04"
"A3","05634","M.","BAUDRY","Edouard",21/03/1988,"68 boulevard Garibaldi","75015","PARIS","01 43 06 60 87","06 48 03 77 62"
"A3","05397","M.","BEAURAIN","Florian",09/08/1988,"8 allée Courbet","93250","VILLEMONBLE","01 45 28 49 94",
"A3","05791","M.","BEAUVOIS","Romain",30/03/1987,"13 d, chemin de la Creuse Voie","91570","BIEVRES","01 60 19 04 03","06 89 69 62 70"
"A3","05354","M.","BECQUART","Laurent",14/04/1988,"8 rue delambre","75014","PARIS 14",,"06 63 85 15 14"
"A3","04996","M.","BECQUEREAU","Florian",08/06/1987,"33 rue du Général Leclerc","92130","ISSY LES MOULINEAUX",,"06 50 52 78 95"
"A3","05102","M.","BELORGEY","Grégoire",23/02/1988,"5 rue Marié Davy","75014","PARIS","01 42 17 04 38","06 84 60 21 09"
"A3","05458","M.","BEN ABDESSELAM","Nazim",21/07/1988,"4 rue Henri Poincaré","75020","PARIS","01 47 97 46 90","06 85 41 77 31"
"A3","05042","MLLE","BEN TOLILA","Aurélie",31/03/1987,"57 ter rue des Lavoirs","33700","MERIGNAC",,"06 76 19 22 22"
"A3","6242","M.","BENDJEMA","Moustafa",16/01/1988,"12 rue Edouard Lefebvre","78000","VERSAILLES",,"06 70 87 98 00"
"A3","05054","M.","BENHALIMA","Abdessamad",26/03/1990,"43, avenue Anatole France","93250","VILLEMOMBLE","01 48 54 89 04","06 17 74 05 06"
"A3","05147","M.","BENVENISTE-PROFICHET","Bruno",07/06/1987,"Résidence Le Saint-Jacques, 16, rue Jean-Claude Arnould","75014","PARIS",,"06 32 55 44 55"
"A3","05902","M.","BERTHAUX","Paul",15/12/1987,"30, bd A. Gauchet","50300","AVRANCHES","02 33 58 41 18","06 16 16 43 82"
"A3","05914","M.","BIDANI","Nitin",20/12/1987,"106, rue de Charenton","75012","PARIS","01 43 41 85 70","06 65 26 69 90"
"A3","04653","M.","BITTON","Clément",11/03/1984,"16 impasse Deligny","75017","PARIS",,"06 80 21 67 82"
"A3","05055","M.","BLANC","Timothée",27/07/1987,"26, avenue du Commerce","78000","VERSAILLES","01 39 54 30 66","06 64 20 49 40"
"A3","05627","M.","BLANCHET","Yannick",05/11/1986,"1, rue Honoré d\'Estienne d\'Orves","91000","EVRY","01 60 77 70 76","06 61 43 24 10"
"A3","05944","M.","BOCQUET","Fabien",31/10/1987,"4, rue du Néflier","91800","BRUNOY","01 60 46 85 01","06 74 92 42 00"
"A3","05483","M.","BOIVIN","Arnaud",06/10/1988,"18 rue Henri Corvol","94600","CHOISY LE ROI","09 72 95 74 35",
"A3","6243","M.","BOSSE","Samson",03/01/1987,"9 rue Paul Bert","94400","VITRY-SUR-SEINE",,"06 50 96 45 51"
"A3","6244","M.","BOUJNAH","Farouk",08/08/1986,"33 rue du Général Leclerc","92130","ISSY LES MOULINEAUX",,"06 66 62 51 48"
"A3","05402","M.","BOULFROY","Damien",09/11/1988,"5 parvis du Breuil - Appartement 87","92160","ANTONY","01 46 68 35 87","06 79 49 20 79"
"A3","05124","M.","BOUQUET","Arthur",03/05/1987,"1 rue Saint Laurent","60500","CHANTILLY",,"06 85 59 24 08"
"A3","05374","M.","BOUTHERIN","Arnaud",03/03/1988,"17 rue Edouard Manet","78370","PLAISIR","01 78 51 27 89","06 27 25 70 57"
"A3","05501","M.","BOYER","Antoine",19/09/1988,"133 A Quincy Street","11216","BROOKLYN",,"13474810292"
"A3","05058","MLLE","BOYER","Camille",24/02/1988,"3 avenue Jean Jaurès","92120","MONTROUGE","01 49 12 95 41","06 75 10 67 24"
"A3","04495","MLLE","BOYER","Sandra",13/10/1986,"147 bis chemin de la Cote du Change","93370","MONTFERMEIL","09 50 13 64 14","06 63 38 28 67"
"A3","05884","M.","BRAJKOVIC","Jean-Baptiste",25/03/1986,"319 1ère avenue Verdun","H4G 2V6","MONTREAL - QUEBEC","04 42 75 35 02","06 21 30 81 64"
"A3","05093","M.","BRANCHARD","Rudy",23/11/1987,"8, rue des Hirondelles","91210","DRAVEIL","09 50 23 72 82","06 83 26 63 16"
"A3","05566","M.","BREGEON","Matthieu",07/07/1986,"122 allée du Bois de la Moïse","45370","JOUY-LE-POTIER","0041 78 92 73 772","06 75 74 00 80"
"A3","6304","M.","BROVELLI","Victor",23/01/1988,"5 allée Blaise Pascal","78460","CHEVREUSE","01 30 52 35 90","06 74 64 16 14"
"A3","05367","M.","BRUCHE","Vincent",13/08/1988,"144 rue Roger Salengro","93110","ROSNY SOUS BOIS","01 48 94 35 24","06 89 19 40 94"
"A3","05025","M.","BRUNET","Guillaume",27/09/1986,"14 bis, villa de Madrid","92200","NEUILLY SUR SEINE","01 47 38 23 18","06 81 92 15 16"
"A3","05920","M.","BRUNNER","Benjamin",15/12/1986,"30, rue Stanislas Bance","95400","ARNOUVILLE LES GONESSE","01 39 85 75 86","06 85 42 79 10"
"A3","05828","M.","BUJON","Jérémi",22/12/1985,"18 rue du Bois Clair - Bât C2, Résidence Bellevue","91620","NOZAY","01 45 09 61 94","06 60 49 29 06"
"A3","05451","M.","BURY","Yann",21/06/1988,"Chez M. J-P. BURY - BP 3846",,"LIBREVILLE",,"06 66 27 43 21"
"A3","05004","M.","CACCIAGUERRA","Pierre-François",05/05/1986,"12, avenue Roland Garros","78140","VELIZY VILLACOUBLAY","01 34 65 97 31","06 66 09 13 32"
"A3","05391","MLLE","CADART","Elisabeth",20/05/1988,"68 rue du Gouverneur Général Eboué","92130","ISSY LES MOULINEAUX",,"06 98 48 60 63"
"A3","06003","M.","CAI","Jie",13/12/1985,"Les Estudines - Le Magistère, 100 av. de Lattre de Tassigny","94000","CRETEIL",,
"A3","05892","M.","CAMPOS","Jean-Baptiste",02/12/1987,"18B ronde des Cognets","13800","ISTRES",,"06 10 17 16 54"
"A3","05192","M.","CAMUS","Guillaume",08/05/1986,"4, rue des Cèdres","77600","CONCHES SUR GONDOIRE","01 64 02 23 79","06 43 30 23 70"
"A3","06004","M.","CAO","Shuang",05/10/1985,"SCI LXHY - 3 ruelle aux Puits","94800","VILLEJUIF",,"06 50 97 36 85"
"A3","05829","M.","CARISTAN","Aurélien",22/06/1987,"4, rue Maurice Dalesme","95130","FRANCONVILLE","01 34 14 46 71","06 73 72 75 39"
"A3","05930","M.","CARON","Aurélien",19/02/1986,"26, rue Théophile Gautier","77340","PONTAULT COMBAULT","01 60 28 70 65","06 19 09 53 86"
"A3","6245","M.","CASTAIGNE","Jean-Baptiste",16/04/1987,"14 rue Milton","75009","PARIS",,"06 18 41 68 02"
"A3","6169","M.","CASTELLI","Alexandre",10/06/1988,"27 rue Fontaine","93200","SAINT-DENIS","01 42 43 74 92","06 34 18 75 00"
"A3","6246","M.","CHABAA","Achraf",18/03/1988,"5 rue Georges Citerne","75015","PARIS",,"06 50 68 14 88"
"A3","05366","MLLE","CHABAS","Claire",07/03/1988,"6 rue Say","75009","PARIS","09 51 64 61 05","06 88 79 74 77"
"A3","05961","M.","CHALOUM","Gabriel",21/04/1988,"7, rue Héloise","95160","MONTMORENCY","01 39 64 32 28","06 76 61 44 58"
"A3","05099","M.","CHAR","Gabriel",08/07/1987,"4, cité de l\'Alma","75007","PARIS",,"06 80 51 96 85"
"A3","05149","M.","CHARON","Adrien",21/02/1987,"61, rue Henri Richaume","78360","MONTESSON","01 39 52 26 36","06 77 15 62 31"
"A3","05954","M.","CHARPENTIER","Nicolas",18/11/1985,"43 bis, rue du Général de Lacharrière","94000","CRETEIL","01 43 39 56 92","06 68 58 72 50"
"A3","05830","M.","CHAUVEAU","Patrick",19/11/1986,"17 rue Pierre Nicole","75005","PARIS",,"06 98 43 19 11"
"A3","05393","M.","CHAZERAIN","Thomas",19/03/1988,"6 route du Curé","92410","VILLE D\'AVRAY","01 47 09 20 64","06 80 56 94 81"
"A3","06005","M.","CHEN","Zijun",12/10/1984,"SCI LXHY - 3 ruelle aux Puits","94800","VILLEJUIF",,
"A3","04975","MLLE","CHERIFI","Luisa",16/10/1986,"1, rue Ambroise Thomas","95500","GONESSE","01 39 85 93 43","06 72 74 32 69"
"A3","05015","M.","CHEVALIER","Eric",09/07/1986,"12 square Desnouettes","75015","PARIS","01 45 31 57 22","06 25 88 71 42"
"A3","05959","M.","CHEVALIER","Jonathan",28/02/1986,"68, avenue Foch","94700","MAISONS-ALFORT","01 43 75 07 87","06 09 04 75 88"
"A3","6248","MLLE","CHICOT","Charlotte",25/07/1988,"88 Henri Barbusse","92110","CLICHY",,"06 23 57 84 65"
"A3","05150","M.","CHOVET","Bruno",27/07/1987,"47, avenue Henri Ginoux","92120","MONTROUGE","01 46 57 58 25","06 83 95 55 51"
"A3","6250","M.","CLARET DE FLEURIEU","Côme",02/06/1987,"16 rue Gerbert","75015","PARIS","01 42 50 00 04","06 37 75 12 89"
"A3","05831","MLLE","CLATOT","Marine",10/11/1985,"16, rue Pierre Loti","78180","MONTIGNY LE BRETONNEUX","01 30 57 16 51","06 66 70 72 29"
"A3","05120","M.","CLAUDEL","Emmanuel",18/05/1987,"32, rue des Pommerets","92310","SEVRES","01 45 07 22 39","06 24 97 49 35"
"A3","05076","M.","CLEENEWERCK","Vincent",14/10/1986,"108, rue Perthuis","92140","CLAMART","01 46 44 03 62","06 15 90 82 09"
"A3","05792","M.","COBANOGLU","Gabriel",26/11/1987,"9, rue du Château d\'Eau","91130","RIS-ORANGIS","01 69 06 83 59","06 21 57 30 86"
"A3","05932","M.","COGNEVILLE","Pierre",09/08/1987,"18, rue Jacques Boyceau","78000","VERSAILLES","01 39 02 78 52","06 03 78 21 77"
"A3","05494","M.","COHEN","Dan",20/05/1988,"52 avenue Eglé","78600","MAISONS LAFFITTE","01 39 62 92 38","06 87 59 37 03"
"A3","6306","M.","COHEN","Yohan",02/10/1986,"138 rue Castagnary","75015","PARIS","01 45 33 75 61","06 85 39 06 11"
"A3","6251","MLLE","COHEN BENGIO","Ilana",01/07/1988,"2 rue Michel Ange","75116","PARIS","01 42 24 07 04","06 89 09 44 55"
"A3","05072","M.","COMBASTET","Charles",29/04/1987,"5 allée Maintenon","75006","PARIS","01 48 78 44 22","06 60 69 78 05"
"A3","05126","M.","COMBY","Matthieu",17/10/1986,"19 rue Anatole France","78530","BUC","01 39 56 04 89","06 62 60 29 26"
"A3","6252","M.","COMPAGNON","Lilian",24/06/1987,"Les Jardins de la Cathédrale  8 rue André Lalande  Appt A323","91000","EVRY","09 52 03 00 46","06 10 04 40 86"
"A3","05832","M.","CONTESSOT","Adrien",20/08/1986,"4 rue Magellan","91300","MASSY","09 54 80 20 08","06 76 57 79 30"
"A3","05369","MLLE","COTTON DE BENNETOT","Isaure",03/08/1988,"118 rue la Fontaine","75016","PARIS","09 81 78 34 48","06 83 20 14 16"
"A3","05960","M.","COUCHI","Salif",19/01/1987,"40 avenue de la Division Leclerc","92320","CHATILLON","01 41 48 53 90","06 48 60 27 46"
"A3","05528","M.","COURCIER","Lucas",17/03/1987,"13 rue de Mont-Louis","75011","PARIS",,"06 27 26 08 12"
"A3","6319","M.","COURS","Florian",07/01/1988,"25 rue Gassendi - B 166","75014","PARIS","09 52 91 43 53","06 27 26 16 01"
"A3","05441","M.","COURTECUISSE","Arnaud",05/06/1989,"70 rue du Père Corentin","75014","PARIS","01 45 11 52 70","06 83 46 72 84"
"A3","6254","MLLE","COZETTE","Savannah",22/11/1986,"117 rue Vieille du Temple","75003","PARIS","01 42 74 35 71","06 68 48 85 93"
"A3","05358","M.","CRABEIL","Thibault",24/06/1988,"91 rue de la Victoire","75009","PARIS",,"06 33 84 90 94"
"A3","05525","M.","CREACH","Pierre-Antoine",15/10/1988,"8 allée de la Figuerie","44240","LA CHAPELLE SUR ERDRE",,"06 85 37 31 57"
"A3","05910","M.","CROIZAT","Guillaume",28/03/1987,"95 rue Stéphane Déchant","69350","LA MULATIERE","04 78 50 00 56","06 17 99 87 76"
"A3","6255","M.","CUER","Thomas",04/11/1988,"8 rue delambre","75014","PARIS 14",,"06 35 44 45 43"
"A3","05128","M.","D\'HARCOURT","Guillaume",03/11/1986,"14 rue Brémontier","75017","PARIS",,"06 86 06 64 39"
"A3","05572","M.","D\'USSEL","Hervé",20/06/1985,"25, avenue Rapp","75007","PARIS","01 45 50 35 81","06 71 96 28 35"
"A3","05370","M.","DADOUN","David",10/01/1987,"139 avenue d\'Argenteuil","92600","ASNIERES SUR SEINE",,"06 50 37 67 35"
"A3","05057","M.","DAGUET","Christophe",23/03/1987,"156, rue de Tolbiac","75013","PARIS","01 45 80 52 09","06 79 63 18 87"
"A3","05016","M.","DANG","Rémy",03/07/1987,"86 chemin de Vauhallan","91120","PALAISEAU","01 69 31 09 92","06 64 30 42 32"
"A3","05519","M.","DE BROCA","Thibaut",17/07/1988,"72 rue de Billancourt","92100","BOULOGNE BILLANCOURT","01 46 03 42 69","06 30 04 25 49"
"A3","04974","M.","DE CORDOÜE","Jean-Baptiste",21/07/1987,"6, villa Madrid","92200","NEUILLY SUR SEINE","01 46 24 08 82","06 74 77 53 30"
"A3","04531","M.","DE HUBSCH","Erwan",30/10/1986,"64, avenue de la Bourdonnais","75007","PARIS","01 45 56 95 53","06 03 19 04 72"
"A3","04471","M.","DE LA MORINERIE","Pierre",23/01/1986,"60, rue du Général Galliéni","92100","BOULOGNE BILLANCOURT","01 46 21 36 89","06 15 78 90 55"
"A3","05406","M.","DE LACOSTE LAREYMONDIE","Pierre-Alain",04/05/1988,"135 bd Péreire","75017","PARIS","01 40 54 83 07","06 76 97 62 67"
"A3","6256","MLLE","DE SILVA USWATTE LIYANAGE","Araliya",28/12/1987,"40 avenue de la Sablière","94450","LIMEIL BREVANNES","01 45 69 27 54","06 67 82 83 41"
"A3","04513","M.","DE TRUCHIS DE VARENNES","Géraud",02/02/1986,"8, avenue du Louvre","78000","VERSAILLES","01 30 21 82 04","06 27 02 50 76"
"A3","05457","MLLE","DE VILLELE","Laure",25/08/1988,"58 rue de Dunkerque","75009","PARIS",,"06 78 92 18 98"
"A3","05106","M.","DECHRISTE","Jean-Matthieu",18/06/1987,"5 mai Camille du Gast","92600","ASNIERES SUR SEINE","09 51 98 17 20","06 89 81 56 23"
"A3","6257","M.","DELANDRE","Willy",27/11/1988,"35 rue des Meuniers","75012","PARIS","09 52 53 76 88","06 67 99 01 63"
"A3","05908","M.","DELAR","Emmanuel",30/01/1987,"32 rue Rouget de Lisle - Appartement 422","92130","ISSY LES MOULINEAUX",,"06 09 22 41 89"
"A3","04983","M.","DELBECKE","Thomas",25/05/1987,"27 rue Asseline","75014","PARIS","01 43 22 89 07","06"
"A3","05890","MLLE","DELMAS-JALABERT","Jenna",10/03/1987,"Quartier l\'Orée du Bois, 11 rés. les Châteaux Brûloirs","95000","CERGY",,"06 67 36 27 61"
"A3","04985","MLLE","DELVALLEE","Ségolène",28/01/1987,"40 rue Pierre Demours","75017","PARIS",,"06 98 44 77 23"
"A3","05896","M.","DEMENEIX","Guillaume",20/08/1987,"253 rue Saint-Honoré","75001","PARIS",,"06 82 10 87 16"
"A3","6395","MLLE","DENG","Min",06/12/1988,"Centre d\'Accueil International, 9 rue du Moulin Vert","75014","PARIS","01 44 12 58 00","06 19 83 53 96"
"A3","05048","M.","DEPONDT","Maxime",02/08/1987,"29 rue Albert Sarrault","78000","VERSAILLES","01 39 51 29 48","06 88 10 96 87"
"A3","04548","M.","DEREST","Vivien",16/04/1985,"191, boulevard de la République","92210","ST CLOUD","01 46 02 99 50",
"A3","05383","M.","DESTEPHEN","Clément",31/07/1988,"8 rue du Commandant Marchand","94130","NOGENT SUR MARNE","01 48 75 41 05","06 73 61 14 44"
"A3","05479","M.","DI TULLIO","Nicolas",27/06/1988,"35 rue Paul Valéry","75116","PARIS","09 77 57 17 03","06 76 01 83 21"
"A3","05945","M.","DOMERGE","Arnaud",17/06/1988,"15, rue du Poitou","92120","MONTROUGE","01 46 57 04 17","06 26 73 08 37"
"A3","05520","MLLE","DOSIMONT","Camille",26/09/1988,"83 rue de Rennes","75006","PARIS",,"06 29 31 17 53"
"A3","05364","M.","DREYFUS","Jean-Jacob",31/08/1988,"78 rue Raynouard","75016","PARIS","01 45 25 85 48","06 19 09 75 49"
"A3","05512","M.","DRUMEL","Arnaud",15/07/1988,"91 rue de la Victoire","75009","PARIS","09 52 90 60 35","06 42 19 81 55"
"A3","05194","M.","DU BOUCHERON","François",30/06/1986,"16, rue Bois Le Vent","75016","PARIS","01 45 27 34 77","06 07 48 32 18"
"A3","05424","M.","DUCOULOMBIER","Bertrand",27/02/1985,"11 avenue de l\'Amiral Courbet","95600","EAUBONNE","01 34 27 56 48","06 63 88 21 23"
"A3","05950","M.","DUEZ","Cyril",01/12/1987,"69 bis, avenue du Général de Gaulle","92800","PUTEAUX","01 40 90 92 39","06 63 61 37 96"
"A3","05078","M.","DUFAYARD","Adrien",05/10/1987,"8, rue Ernest Cresson","75014","PARIS","01 40 44 51 36","06 70 80 00 44"
"A3","05834","M.","DUONG","Bastien",06/10/1987,"8, avenue des Sablons","91350","GRIGNY 2","01 69 43 01 44","06 21 45 29 04"
"A3","05005","M.","DUPIS","Yann",15/05/1987,"40 impasse de Jurançon","40600","BISCAROSSE","05 58 09 84 41","06 85 17 82 62"
"A3","6132","M.","DUPLAN","Félicien",19/11/1987,"4 avenue Médéric","78110","LE VESINET","01 39 52 88 41","06 33 11 75 51"
"A3","05079","M.","DUPRE","Clément",23/04/1987,"24 rue des 4 Sergents","17000","LA ROCHELLE","05 46 44 14 53","06 79 81 13 68"
"A3","05044","M.","DUPRE","Pierre-Emmanuel",12/04/1987,"3 villa Lantiez","75017","PARIS",,"06 73 18 55 35"
"A3","05415","M.","DUTFOY DE MONT DE BENQUE","Cyrille",08/11/1988,"6 rue Roquépine","75008","PARIS",,"06 32 42 93 85"
"A3","05401","M.","DUTFOY DE MONT DE BENQUE","Henri-Mayeul",29/01/1987,"19 rue Paul Brossard","78220","VIROFLAY","01 39 24 06 96","06 25 72 90 73"
"A3","05453","M.","EL KHALFI","Khalid",01/06/1988,"29 av. du Mal de Lattre de Tassigny","94220","CHARENTON LE PONT",,"06 63 19 26 66"
"A3","05006","M.","EL ROUSS","Nicolas",21/08/1987,"25 bd Galliéni","94130","NOGENT SUR MARNE","01 43 24 22 97","06 29 75 62 57"
"A3","6259","M.","ELEUCH","Raëf",17/04/1988,"7 allée des Lilas","95170","DEUIL LA BARRE","01 39 84 37 93","06 72 11 38 62"
"A3","05080","M.","EMILIEN","Rudy",06/12/1987,"23 rue du Vert Village","95340","PERSAN","01 30 28 00 98","06 12 86 25 25"
"A3","6128","M.","ERDOGAN","Julien",24/09/1987,"1/3 mail Federico Garcia Lorca","93160","NOISY LE GRAND","01 43 05 26 75","06 29 99 17 02"
"A3","05850","M.","ERDOS","Nicolas",06/03/1986,"8 rue du Général de Gaulle","95480","PIERRELAYE",,"06 47 95 17 82"
"A3","05921","M.","ESOPE","Manuel",10/09/1986,"20 bis rue Colonel Pierre Avia - B23","75015","PARIS",,"06 59 76 19 50"
"A3","05894","M.","FABRE","Antoine",28/03/1986,"133 boulevard du Montparnasse","75006","PARIS",,"06 42 91 03 21"
"A3","05919","M.","FARINES","Timothée",28/11/1986,"21 boulevard Barbès","75018","PARIS",,"06 64 31 90 22"
"A3","6260","M.","FELDMANN","Jérémie",11/03/1990,"20 rue d\'Arcueil","75014","PARIS","01 82 09 72 92","06 46 60 07 50"
"A3","6261","M.","FERNANDES","Alexandre",08/04/1988,"7 cour de la Ferme Saint Lazare","75010","PARIS","09 54 24 67 09","06 47 99 98 89"
"A3","05444","M.","FICHET","Pierre",27/06/1988,"13 rue Léon Bobin","78320","LE MESNIL ST DENIS","01 34 61 98 85","06 26 61 16 85"
"A3","05059","M.","FIGUEREO","Vincent",06/06/1987,"54 chemin des Vaux Mourants","91370","VERRIERES LE BUISSON","01 69 20 50 23","06 21 37 22 99"
"A3","05473","MLLE","FINE","Marion",19/09/1988,"10 rue de Rémusat","75016","PARIS","01 42 15 11 30","06 64 20 60 27"
"A3","05578","M.","FRANCOIS","Nicolas",21/07/1985,"4 impasse Jean Beausire","75004","PARIS",,"06 64 10 32 85"
"A3","05929","M.","FROMENT","Amaury",23/01/1986,"5 rue Barye","75017","PARIS",,"06 73 45 75 15"
"A3","05580","M.","FUHR","Christian",14/07/1986,"10 rue du Gymnase","67450","MUNDOLSHEIM",,"06 23 49 62 09"
"A3","6262","M.","GANDILLOT","Etienne",16/06/1989,"1 ter rue du Lycée","92330","SCEAUX","01 40 91 40 91","06 33 80 99 43"
"A3","05400","M.","GARDIN","Aurélien",25/05/1988,"32 rue Médéric","75017","PARIS",,"06 42 62 77 50"
"A3","05941","M.","GAUCHER","Alexis",16/06/1986,"17, rue de Coupières","91190","GIF SUR YVETTE","01 69 28 47 14","06 74 54 40 77"
"A3","05888","M.","GAUDEMER","Julien",29/04/1987,"38A passage des Pinèdes","30900","NIMES",,
"A3","05939","M.","GAUTIER","Alexandre",27/11/1987,"4, rue Jacques de Rome","78480","VERNEUIL SUR SEINE","01 39 65 76 73","06 78 90 25 45"
"A3","05495","MLLE","GAVREL","Adélie",25/10/1988,"10 avenue du Château","92190","MEUDON","01 45 07 82 34","06 87 39 76 82"
"A3","05900","M.","GEBELIN","Luc",12/07/1985,"117 rue de la Croix de Chaintre","49400","SAUMUR",,"06 80 20 34 31"
"A3","6307","MLLE","GHAÏB","Sarra",23/07/1989,"7 rue d\'Alleray","75015","PARIS",,"06 22 70 50 91"
"A3","05082","M.","GHORAYEB","Marc",27/08/1987,"4, rue Robert de Flers","75015","PARIS","09 51 91 56 78","06 86 96 89 53"
"A3","05851","M.","GIBIER","Benjamin",23/10/1987,"7, allée des Acacias","92310","SEVRES","01 45 07 04 93","06 63 82 94 09"
"A3","05007","M.","GOSSET","Alexis",24/06/1987,"36, avenue Charles de Gaulle","92200","NEUILLY SUR SEINE","01 47 22 52 48","06 03 39 59 12"
"A3","05378","M.","GOUBERT","Eric",24/09/1987,"56 rue Labrouste","75015","PARIS","01 48 42 41 87","06 07 50 92 72"
"A3","05913","M.","GRAHAM","Alexandre",16/02/1985,"3, avenue Constant Coquelin","75007","PARIS","09 51 79 79 71","06 73 76 95 18"
"A3","04998","M.","GRIFFE","Benoît",28/08/1986,"8 rue Léon Cognet","75017","PARIS",,"06 09 82 26 53"
"A3","05108","M.","GROUAS","Josselin",02/11/1988,"15, rue Oudinot","75007","PARIS","01 44 49 02 04","06 63 54 42 05"
"A3","05553","M.","GUERMANE","Ayyoub",01/12/1986,"22 rue de Lourmel","75015","PARIS",,"06 10 04 75 70"
"A3","04488","M.","GUERRINI","Olivier",02/08/1986,"2 promenade Venezia","78000","VERSAILLES","01 39 49 09 50","06 98 86 00 50"
"A3","05500","M.","GUICHARD","Nicolas",11/08/1988,"34 rue gabrielle","75018","PARIS 18",,
"A3","6133","M.","GUILBERT","Baptiste",06/02/1988,"3 rue de Saint-Simon","75007","PARIS",,"06 74 78 86 76"
"A3","04530","M.","GUILLEMAUD","Benoît",22/08/1986,"23 bis rue des Charmettes","91190","GIF SUR YVETTE","01 69 82 92 76","06 87 31 67 00"
"A3","04999","M.","GUILLEMET","Julien",23/01/1988,"85, rue du Petit Château","94220","CHARENTON LE PONT","01 43 78 73 84","06 03 29 27 74"
"A3","05377","M.","GUILLOU","Julien",26/12/1987,"129 rue des Landes - Bât C","78400","CHATOU","01 39 52 72 13","06 75 64 01 85"
"A3","05165","M.","GUIOMARD","Thomas",02/04/1987,"68, rue Caumartin","75009","PARIS","01 48 78 94 52","06 98 94 86 44"
"A3","05852","M.","GUNENBEIN","Arnaud",18/07/1986,"19, chemin des Fleurs","93220","GAGNY","01 43 81 66 23","06 19 29 12 79"
"A3","05060","M.","GUNTHER","Guillaume",22/06/1987,"16, Les Clairières Rouges","95000","CERGY","01 30 31 93 91","06 64 17 97 48"
"A3","05394","M.","GUYEN","Thomas",14/09/1988,"15 rue Paul Fort","77330","OZOIR LA FERRIERE","09 70 50 43 83","06 73 05 53 92"
"A3","05411","MLLE","HACHILIF","Narjis",14/02/1987,"62 rue Labrouste","75015","PARIS","01 45 30 22 21","06 62 70 89 31"
"A3","6263","MLLE","HAMON-DUQUENNE","Annelise",04/01/1988,"13 rue des Moulins","93370","MONTFERMEIL","01 43 32 86 37",
"A3","05589","MLLE","HAMOU","Eva",03/10/1985,"Chez Madame Aline HAMOU, 17 bis rue Erlanger","75016","PARIS",,"06 37 60 02 82"
"A3","05853","M.","HARAKAT","Karim",21/01/1986,"33 rue du Général Leclerc","92130","ISSY LES MOULINEAUX","01 45 57 19 01","06 26 44 47 21"
"A3","06006","M.","HE","Wenbo",01/08/1985,"37 rue de Verdun","93120","LA COURNEUVE",,"06 62 57 08 96"
"A3","05952","M.","HENHEN","Walid",05/11/1986,"100, boulevard Circulaire","93420","VILLEPINTE","01 48 61 58 17",
"A3","05008","M.","HENNEBELLE","Thomas",31/08/1987,"23 rue Pernety","75014","PARIS",,"06 25 77 18 94"
"A3","05109","M.","HENNEQUIN","Laurent",09/04/1987,"19, rue du Bois Robert","78210","ST CYR L\'ECOLE","01 30 58 32 14","06 83 14 97 82"
"A3","05155","M.","HERLICQ","Paul",23/05/1987,"21, rue d\'Alembert","92130","ISSY LES MOULINEAUX","01 46 44 35 32","06"
"A3","6129","M.","HEUGEL","Nicolas",21/03/1987,"6 rue Paul Bert","92370","CHAVILLE","01 47 50 10 72","06 98 89 93 14"
"A3","6264","M.","HOMMANI","Ismaël",19/06/1987,"150 rue de Charonne","75011","PARIS","01 40 09 27 84","06 64 78 13 87"
"A3","05129","M.","HOULLIER","Laurent",22/07/1986,"23, rue Foch","94550","CHEVILLY LARUE","01 45 46 98 91","06 70 18 24 75"
"A3","06007","M.","HU","Yuan",10/11/1986,"182 avenue Rouget de L\'Isle - Appt C82","94400","VITRY SUR SEINE",,
"A3","06008","M.","HU","Yuanqing",18/01/1986,"18 square Alboni","75016","PARIS",,"06 35 49 03 93"
"A3","05931","M.","HUA","Trung-Kien",26/07/1987,"28, avenue Maurice de Vlaminck","77680","ROISSY EN BRIE","01 60 29 56 54","06 34 90 55 52"
"A3","6266","MLLE","HUANG","Aurélia",07/02/1988,"6 Square Belsunce","13001","MARSEILLE","04 91 90 19 13","06 21 13 96  76"
"A3","06009","M.","HUANG","Bo",10/06/1986,"182 av. Rouget de l\'Isle - Appt. C82","94400","VITRY SUR SEINE",,"06 36 31 31 69"
"A3","06010","M.","HUANG","Jiaqi",31/03/1986,"37 rue de Verdun","93120","LA COURNEUVE",,
"A3","06012","M.","HUANG","Sunzhi",02/09/1985,"33 rue du Général Leclerc","92130","ISSY LES MOULINEAUX",,"06 36  15 10 75"
"A3","04986","M.","HUBERT","Edouard",02/06/1987,"2 rue Hector Pron","10000","TROYES","03 25 81 28 57","06 26 74 83 37"
"A3","05061","M.","HUREL","Marcouf",27/01/1988,"48, rue de la Santé","75014","PARIS",,"06 19 76 34 83"
"A3","05948","M.","INGRAND","Guillaume",05/03/1987,"11 square des Gaudonnes","92500","RUEIL MALMAISON","01 47 49 28 13","06 68 61 69 12"
"A3","6267","M.","IUNG","Pierre-Edouard",24/05/1988,"bois de saint thibault","89240","CHEVANNES",,"06 73 93 78 14"
"A3","05404","M.","JANUS","Jérémy",14/01/1989,"80 rue de la Roquette","75011","PARIS","01 48 05 28 97","06 70 23 78 12"
"A3","05854","M.","JOLY","Marc",24/10/1984,"40 bis avenue de la Convention","93190","LIVRY-GARGAN","01 45 09 16 71","06 13 95 48 93"
"A3","05855","M.","JONQUIERES","Gabriel",20/08/1987,"10 rue de la Liberté","94100","SAINT-MAUR","01 49 76 05 49","06 50 31 92 99"
"A3","05144","M.","JOUANNEAULT","Jean-Hugues",03/10/1987,"37 avenue Mirabeau","78000","VERSAILLES","01 39 54 72 24","06 75 75 81 56"
"A3","05390","M.","JOUFFREY","Laurent",15/11/1988,"8 rue Saint Saens","91240","ST MICHEL SUR ORGE","01 60 16 45 92","06 62 82 67 03"
"A3","05468","M.","JOUHANNET","Romain",06/08/1985,"5-9 rue Michal","75013","PARIS",,"06 07 73 64 60"
"A3","6268","MLLE","JOYEROT","Edwige",03/01/1989,"37 rue d\'Hauteville","75010","PARIS",,"06 84 02 56 05"
"A3","6269","M.","KADDOURI","Anas",05/11/1988,"10 rue Francois Mouthon - 5ème étage - P","75015","PARIS",,"06 11 03 94 34"
"A3","6270","M.","KALAINATHAN","Duvaragan",04/03/1988,"5 allée Kilian","93270","SEVRAN","01 43 85 06 69","06 06 92 92 94"
"A3","04476","M.","KERVENEC","Mathieu",26/06/1986,"24 avenue Jeanne d\'Arc","92700","COLOMBES","01 47 82 03 60",
"A3","05017","MLLE","KLEIBER","Irène",17/08/1986,"29 rue Viala","75015","PARIS",,"06 33 13 87 55"
"A3","6271","MLLE","KLEPPER","France",02/10/1988,"9 rue des Tamaris","30132","CAISSARGUES",,"06 77 22 27 89"
"A3","05857","M.","KNIDIF","Yacine",06/08/1986,"4, rue des Primevères","95190","GOUSSAINVILLE","01 34 04 83 24","06 10 96 81 98"
"A3","05858","M.","KRID","Riad",20/03/1987,"8, rue du Faubourg Montmartre","75009","PARIS","01 47 70 42 75","06 72 69 54 53"
"A3","05019","M.","L\'HIRONDEL","Clément",13/08/1987,"34 rue Simart","75018","PARIS",,"06 71 92 32 34"
"A3","6320","MLLE","L\'HOTELLERIE","Coralie",08/11/1988,"59 rue Petit","75019","PARIS","01 42 45 88 81","06 87 78 19 12"
"A3","05859","M.","LABEAU","Pierrick",10/03/1987,"Résidence Les Grandes Terres, 8 square Grandchamps","78160","MARLY LE ROI",,"06 24 58 18 87"
"A3","05083","M.","LABRUSSE","Valentin",16/10/1987,"59 avenue de Mazy","44380","PORNICHET",,"06 07 18 58 93"
"A3","05000","M.","LACAZE","Maxime",03/09/1987,"69, rue de la Bièvre","92340","BOURG LA REINE","01 46 65 69 70","06 79 60 29 85"
"A3","05497","M.","LACHAUSSEE","Jean-Baptiste",11/10/1988,"5 avenue du Bas Meudon","92130","ISSY LES MOULINEAUX",,"06 79 28 74 04"
"A3","05355","M.","LANGE","Bruno",04/02/1988,"39 avenue Rapp","75007","PARIS","01 45 79 65 41",
"A3","6272","M.","LAZAAR","Tarek",13/07/1987,"10 rue du Moulin - Bât A - Porte 701","92170","VANVES",,"06 50 28 38 28"
"A3","6732","M.","LAZERGES","Julien",21/03/1981,"27 boulevard Brune","75014","PARIS","01 73 70 17 77","06 08 73 90 52"
"A3","05470","M.","LE BASTARD","Vincent",16/04/1988,"42 ter rue Charles Infroit","78570","ANDRESY","01 39 74 31 38","06 78 40 66 97"
"A3","05488","M.","LE BIHAN","Raphaël",26/04/1988,"7 bis rue Pierre Curie","92130","ISSY LES MOULINEAUX","09 64 49 33 05",
"A3","05172","M.","LE CARBONNIER DE LA MORSANGLIERE","Aymeric",11/03/1986,"99 rue Crevier","76000","ROUEN",,"06 21 72 29 72"
"A3","6134","M.","LE DOZE","Mohammed",29/11/1988,"60 boulevard de Vanves","92320","CHATILLON","01 46 56 06 72","06 98 29 88 11"
"A3","05887","MLLE","LE GALL","Anne",01/11/1987,"22–24 rue du Viaduc","92130","ISSY-LES-MOULINEAUX",,"06 30 16 37 35"
"A3","05009","MLLE","LE GOFF","Nathalie",09/10/1987,"20, rue Borromée","75015","PARIS","01 47 83 23 90","06 70 27 79 66"
"A3","05983","M.","LE GOFF","Pierre",16/12/1986,"14, villa de la Reine","78000","VERSAILLES","01 39 50 04 71","06 68 74 16 12"
"A3","05164","M.","LE NOC","Tangi",05/02/1988,"19 rue Spontini","75016","PARIS",,"06 19 69 71 64"
"A3","6273","M.","LE ROY","Vincent",26/02/1988,"34 rue Simart","75018","PARIS",,"06 07 03 23 97"
"A3","05445","M.","LEBEL","Vincent",10/05/1989,"47 Grande Rue","41290","OUCQUES","0254232007","06 75 30 02 06"
"A3","05918","M.","LEFEBVRE DE PLINVAL","Amaury",14/06/1986,"13, rue de l\'Arc de Triomphe","75017","PARIS","01 42 67 99 14","06 34 64 60 80"
"A3","6136","M.","LEFRANCOIS","Robin",22/09/1988,"11 rue Manessier","94130","NOGENT SUR MARNE","01 48 73 96 42","06 58 59 34 69"
"A3","05049","MLLE","LEKBICH","Naziha",10/05/1987,"76 rue de Paris","93230","ROMAINVILLE",,"06 88 86 74 25"
"A3","05095","M.","LELUC","Louis",11/06/1987,"130, rue de Grenelle","75007","PARIS","01 45 51 24 54","06 69 71 49 40"
"A3","05132","M.","LEMENI","Yoann",27/07/1987,"Résidence de l\'Abreuvoir, 8, rue de Viseu","78160","MARLY LE ROI","01 39 58 38 26","06 74 19 03 37"
"A3","05450","M.","LEMOY","Lucas",19/10/1988,"7 rue toullier","75005","PARIS",,"06 81 24 62 98"
"A3","05448","MLLE","LENGUE EWOULE","Victorine",05/02/1988,"2 rue Bruneseau","75013","PARIS",,"06 63 48 23 69"
"A3","05029","M.","LEON","Matthieu",07/12/1987,"86, rue de l\'Université","75007","PARIS","01 45 56 93 46","06 81 43 35 05"
"A3","05110","M.","LESAGE","Charles",04/10/1987,"16 rue Saint Symphorien","78000","VERSAILLES","01 39 02 27 31",
"A3","6139","M.","LESTRIEZ","Thomas",04/01/1988,"10 rue Pierre Curie","91600","SAVIGNY SUR ORGE","01 69 96 58 04","06 60 16 42 08"
"A3","05111","M.","LETELLIER","Guillaume",13/02/1987,"61, rue La Bruyère","92500","RUEIL MALMAISON","01 47 08 55 22","06 33 34 85 03"
"A3","05558","M.","LIAIGRE","Thomas",27/03/1988,"39 boulevard Saint-Marcel","75013","PARIS 13",,"06 69 02 93 22"
"A3","6274","M.","LIN","Wenyuan",26/06/1986,"99 bd Macdonald","75019","PARIS","01 58 20 00 79","06 09 80 13 71"
"A3","06014","M.","LIN","Yun",16/08/1984,"Chambre 31- Résidence Cosmos F, 25 rue des Hortensias","22300","LANNION","09 65 04 52 93",
"A3","06015","M.","LIN","Zhibo",14/02/1985,"SCI LXHY - 3 ruelle aux Puits","94800","VILLEJUIF",,
"A3","05405","M.","LO","Charly",14/02/1988,"11 rue Sainte Claire Deville","77185","LOGNES","01 60 05 33 35","06 78 17 67 32"
"A3","05955","M.","LOR","Edmond",22/08/1987,"25, square Thomas Edison","94000","CRETEIL","01 43 39 98 48","06 27 36 41 19"
"A3","6275","M.","LORA","Victor",31/05/1988,"10761 wilkins ave","90024","LOS ANGELES",,"3106896616"
"A3","05134","M.","LOT","Aymeric",02/09/1987,"10, boulevard du Roi","78000","VERSAILLES","01 30 21 00 29","06 63 43 54 96"
"A3","05529","M.","LUTEL","Julien",22/10/1988,"1 rue du Coudrier","44700","ORVAULT","01 48 28 01 09","06 80 04 10 95"
"A3","05064","MLLE","LY","Julie",04/03/1987,"52, avenue d\'Italie, BL 4","75013","PARIS","01 77 17 44 03","06 13 69 60 47"
"A3","06017","M.","MA","Ting",30/01/1986,"37 rue de Verdun","93120","LA COURNEUVE","01 43 64 82 70",
"A3","05926","M.","MACKAYA","Harvey-Claude",09/04/1986,"4, rue de Citeaux, Résidence Citeaux","75012","PARIS",,"06 23 23 09 34"
"A3","05860","MLLE","MAFFAT","Isabelle",12/08/1987,"12 rue Caban","45000","ORLEANS",,"06 64 21 02 18"
"A3","05442","MLLE","MAGNE","Delphine",05/01/1988,"22 rue Albert Marquet, La Bretonnière","78960","VOISINS LE BRETONNEUX","01 30 48 02 97","06 43 03 96 11"
"A3","05953","MLLE","MAHDJOUB","Lamia",27/11/1987,"24, rue des Saules","95360","MONTMAGNY","01 77 02 40 04","06 12 59 74 20"
"A3","04490","M.","MALHAIRE","Pierre",24/06/1985,"4 place Saint Paul","78220","VIROFLAY","01 30 24 80 55","06 76 84 35 61"
"A3","05122","M.","MALLET","Boris",28/06/1987,"18 rue Saint Romain","75006","PARIS","01 45 48 13 84","06 85 29 36 28"
"A3","05022","M.","MALLET","Romain",16/10/1986,"36 rue des Cévennes","75015","PARIS","01 73 71 88 30","06 20 87 11 28"
"A3","6308","M.","MANGE","Jean-Baptiste",12/06/1978,"12 parc de Diane","78350","JOUY EN JOSAS","01 39 56 07 53","06 30 04 93 06"
"A3","6140","M.","MARCHE","Stéphane",18/06/1988,"2 bis C rue Robert Schuman","78230","LE PECQ",,"06 88 83 25 40"
"A3","05981","M.","MARCHETTI","David",25/05/1985,"45 bis, rue Pouchet","75017","PARIS",,"06 79 63 24 04"
"A3","05010","M.","MARENDAT","Patrick-André",17/06/1987,"29 rue René Paulin Hippolyte","91150","ETAMPES","01 69 58 53 28","06 66 98 85 93"
"A3","04480","M.","MARIZY","Geoffrey",25/11/1986,"42, rue de Fleury","92140","CLAMART","01 46 39 33 88",
"A3","05903","M.","MARTIN","Jean-Baptiste",27/11/1985,"115 rue du Cherche Midi - Chambre 11","75006","PARIS",,"06 77 79 35 19"
"A3","05135","M.","MATHIAUD","Alexandre",23/09/1987,"Résidence Saint Jacques - Appt 404, 16, rue J-C. Arnould","75014","PARIS",,"06 03 58 27 88"
"A3","05065","MLLE","MATHONNIERE","Laurence",03/03/1987,"6, rue Vincent Van Gogh","78960","VOISINS LE BRETONNEUX","01 30 64 96 03","06"
"A3","6277","M.","MATTENET","Kevin",12/06/1987,"15 allée Verrier","93320","LES PAVILLONS SOUS BOIS","01 48 02 08 80","06 84 23 57 80"
"A3","05407","M.","MEDKOUR","Yani",02/09/1988,"56 rue Donizetti","94400","VITRY SUR SEINE",,"06 87 55 83 90"
"A3","05885","M.","MEHENNI","Yacine",02/02/1988,"6, avenue Henri Prost","95200","SARCELLES","01 34 38 04 53","06 21 54 23 13"
"A3","6278","M.","MICHELI","Laurent",14/11/1988,"386 chemin des Trigands","06640","SAINT JEANNET",,"06 99 65 75 62"
"A3","06018","M.","MIN","Daxi",06/04/1987,"16 rue de Toul","31000","TOULOUSE",,
"A3","6305","M.","MINARD","Christophe",29/03/1988,"1 bis  rue Mornay","75004","PARIS","01 42 77 49 32","06 99 21 75 63"
"A3","05389","M.","MOJAL","Hugo",12/11/1988,"7 allée Mozart","92320","CHATILLON","01 46 57 19 74","06 64 72 89 46"
"A3","05086","M.","MONS","Aurélien",13/03/1987,"21 bis rue du haut des sables","86000","POITIERS",,"06 64 43 74 67"
"A3","05409","M.","MOREAU","Swann",14/11/1988,"31 avenue la Marquise du Deffand","92160","ANTONY","01 40 91 01 31","06 31 47 58 17"
"A3","6279","M.","MORGANT","Sébastien",07/05/1987,"4 avenue du Vert Bois","92410","VILLE D AVRAY","01 79 46 46 58","06 74 11 15 32"
"A3","04456","MLLE","MORSALINE","Lorraine",22/04/1987,"25 rue Marceau","78800","HOUILLES","01 39 14 56 10","06 62 77 75 95"
"A3","6280","MLLE","MOTAÏB","Miriem",20/12/1988,"13 rue de Bougainville","93600","AULNAY SOUS BOIS","01 48 66 06 58","06 69 16 33 42"
"A3","05603","M.","MOUBAMBA","Laury Karin",29/01/1986,"13 RP des Martyrs - Chez M. Kaakour FADI","92200","BAGNEUX","01 46 68 89 80","06 18 13 18 29"
"A3","6281","M.","MOUHAMADALY","Azadaly",27/11/1987,"13 mail Maurice de Fontenay -  Logement 74 - Etage 7 - Porte 1","93120","LA COURNEUVE","01 48 36 04 04","06 25 95 87 99"
"A3","04544","M.","MULLIEZ","Jean-Luc",02/11/1985,"Résidence Le Consul 401, 119, boulevard Brune","75014","PARIS",,"06 20 31 23 11"
"A3","05477","MLLE","NABAIS","Emilie",18/05/1988,"15 bis rue Dareau - Chambre A713","75014","PARIS",,"06 65 64 74 96"
"A3","05480","M.","NAKACHE","Rudy",28/09/1988,"3 impasse du Petit Morin","95500","GONESSE","01 34 07 83 16","06 17 57 83 04"
"A3","05928","M.","NASR","Waël",13/11/1986,"121, rue de la Pompe","75116","PARIS","01 45 53 45 51","06 17 78 11 01"
"A3","6141","M.","NAU","Gabriel",16/08/1988,"8 rue Philibert Delorme","75017","PARIS","08 72 89 22 02","06 50 70 00 06"
"A3","04539","M.","NAVETTE","Etienne",27/11/1986,"39, rue Ste Croix La Bretonnerie","75004","PARIS","01 42 71 26 41",
"A3","05432","M.","NAY","Damien",12/06/1988,"13 rue des Forestiers","94440","MAROLLES EN BRIE","01 45 69 15 81","06 76 15 23 76"
"A3","05923","M.","NGUYEN","Anh",17/05/1987,"Chez Monsieur DAGORN, 101 avenue Victor Hugo","92170","VANVES",,"06 34 41 00 73"
"A3","05895","M.","NGUYEN","Huu Tri",11/10/1985,"10, rue Azam","33800","BORDEAUX","05 56 94 18 87","06 01 94 07 91"
"A3","6142","M.","NGUYEN","Victor",29/07/1988,"6 rue Victor Leray","91130","RIS ORANGIS","01 69 43 36 20","06 47 68 92 75"
"A3","6143","M.","NGUYEN VAN","Frédéric",29/11/1988,"41 rue du Bas Coudray - Appt 105","91100","CORBEIL-ESSONNES",,"06 27 94 22 19"
"A3","05023","MLLE","NICLOU","Christelle",04/03/1986,"93 bis, rue de Paris","92190","MEUDON","01 45 07 81 27","06 76 86 41 97"
"A3","6283","M.","NIYUNTHAN","Sathiyatharmabala",22/02/1987,"83 avenue de Paris","92320","CHATILLON","01 40 84 82 67","06 36 97 58 43"
"A3","05958","M.","NOMARSKI","François",16/09/1987,"49 avenue de la Paix","94260","FRESNES","01 46 68 58 29","06 64 66 93 87"
"A3","05373","M.","ODIENNE","Louis-Alexis",04/12/1988,"103 rue de la Cité Moderne","92160","ANTONY","01 41 13 95 31",
"A3","05136","M.","OGEE","Jean-Baptiste",17/04/1987,"90, rue Claude Bernard","75005","PARIS","01 47 07 10 45","06 88 96 20 54"
"A3","6284","M.","OUAZARI","M\'Hamed",20/02/1986,"9 rue Claude Perrault","94000","CRETEIL","01 42 83 46 83","06 22 86 22 73"
"A3","05114","M.","PAGET","Julien",09/06/1986,"121, boulevard Brune","75014","PARIS","01","06 74 42 73 81"
"A3","6399","M.","PAN","Yunpeng",08/02/1987,"100 av. du Mal de Lattre de Tassigny","94000","CRETEIL",,"06 20 86 44 74"
"A3","6400","M.","PANG","Bowen",05/01/1986,"20 rue des Tanneries","75013","PARIS",,"06 15 58 18 41"
"A3","6144","M.","PANNETIER","Kévin",15/05/1986,"Collège Ronsard, 27 bd du Général Giraud","94100","SAINT MAUR","01 43 39 58 83","06 09 15 38 29"
"A3","6285","MLLE","PAPIAU","Aurélie",27/02/1988,"34 rue Marcel Bonnet","94230","CACHAN","01 82 01 58 25","06 70 49 69 46"
"A3","05490","M.","PARKER","Jérémie",05/03/1987,"14 rue Liancourt","75014","PARIS","01 47 08 64 33","06 16 11 28 60"
"A3","6145","M.","PASQUIER","Thomas",05/08/1986,"8-10 rue Fulton","75013","PARIS 13","01 69 05 93 86","06 81 59 85 83"
"A3","05484","M.","PELLEN","Romain",06/07/1988,"9 bis rue des Potiers, Résidence Les Magnolias","92260","FONTENAY AUX ROSES","01 47 02 97 79","06 48 13 80 04"
"A3","6401","M.","PENG","Shiyu",08/11/1986,"100 av Mal de Lattre de Tassigny","94000","CRETEIL",,"06 11 81 44 91"
"A3","05794","M.","PERRAUDIN","Baptiste",01/04/1987,"7 allée du Bas","76540","THIERGEVILLE",,"06 25 04 04 42"
"A3","04453","M.","PETITEAU","Jean-Baptiste",28/01/1986,"23 rue des Champs","95460","EZANVILLE","01 39 35 09 63","06 77 18 41 39"
"A3","05555","M.","PHOU","Nicolas",19/03/1987,"13, rue Saint Exupéry","93360","NEUILLY PLAISANCE","01 43 00 62 00","06 13 75 15 84"
"A3","05013","M.","PICARD","Pierre-Adrien",28/01/1987,"91, rue d\'Alésia","75014","PARIS","01 45 43 15 36","06 89 44 54 47"
"A3","05449","M.","PIEL","Alban",21/03/1988,"88 bis bd de la Tour Maubourg","75007","PARIS","01 45 51 64 06",
"A3","05982","MLLE","PIRARD","Edith",29/09/1987,"8 rue Antoine Thomas","94200","IVRY SUR SEINE","09 51 80 42 42","06 33 07 26 88"
"A3","6146","M.","PLANCHE","Mathieu",02/09/1987,"2A allée du Rouge Gorge","91570","BIEVRES","01 69 85 52 00","06 29 11 56 13"
"A3","05363","M.","POINSOT","Anthony",07/06/1989,"3 allée Alphonse Quizet","93310","LE PRE ST GERVAIS","01 48 44 88 10","06 33 37 68 74"
"A3","05557","M.","POIRAUD","Stanislas",20/03/1988,"39 rue daguerre","75014","PARIS 14",,"06 67 20 06 63"
"A3","05522","M.","PONS","Alexandre",25/06/1988,"10 rue Dulac","75015","PARIS",,"06 33 62 18 96"
"A3","05862","M.","PONTVIANNE","François",16/06/1987,"30, rue Nationale","75013","PARIS","01 53 61 95 50","06 23 66 19 83"
"A3","05413","M.","POTIER","Martin",01/03/1988,"13 rue Gandon","75013","PARIS",,"06 19 11 07 03"
"A3","05414","MLLE","POTUS","Claire",29/09/1989,"68 rue du Gouverneur Général Eboué","92130","ISSY LES MOULINEAUX",,"06 48 62 06 56"
"A3","05066","M.","POUGET","Florent",27/09/1987,"33 rue des Saules","78250","HARDRICOURT","01 34 74 99 07","06 10 34 49 07"
"A3","05936","M.","POULNAIS","Ronan",08/12/1987,"1 bis rue des Chapeliers","44000","NANTES",,"06 66 64 39 85"
"A3","6286","M.","PROUET-ROBIN","Laurent",30/04/1989,"122 rue de vaugirard","75006","PARIS 06",,"+336 59 38 57 41"
"A3","05882","MLLE","QARROUM","Sophia",18/02/1987,"13 rue Villedo","75001","PARIS",,"06 70 24 45 51"
"A3","05137","MLLE","RADENAC","Isabelle",28/06/1987,"46 rue Ampère","75017","PARIS","01 42 67 52 01","06 88 63 66 75"
"A3","05026","M.","RAGHAI","Charafeddine",16/02/1987,"OHLE Le St Jacques, 16, rue Jean-Claude Arnould","75014","PARIS",,"06 21 29 29 90"
"A3","05863","MLLE","RANAIVOSOA","Sylvie",12/11/1987,"49, rue Gaston Maurer","95870","BEZONS","01 30 25 37 76","06 50 53 58 89"
"A3","05375","M.","RATTEZ","Clément",29/10/1988,"23 rue Claude Terrasse appartement 72A4","75016","PARIS 16",,"06 32 18 44 78"
"A3","05395","M.","REAMA","Adam",20/09/1988,"17 ter rue de la Station","93160","NOISY LE GRAND","01 43 65 30 62","06 63 75 84 81"
"A3","05440","M.","REBLEWSKI","Glenn",14/06/1988,"10 rue de Roussigny","91470","LES MOLIERES","01 60 12 26 90","06 76 54 07 98"
"A3","6287","M.","REMY","Jean",28/10/1987,"5 place du 18 Juin 1940","75006","PARIS 06","01 45 66 90 64","06 79 87 49 34"
"A3","6288","MLLE","REZIG-ELMARHOUNE","Nawel",21/12/1988,"67 rue Edouard Branly","93100","MONTREUIL","01 49 88 91 04","06 22 89 72 72"
"A3","05067","M.","RIBAUD","Clément",11/12/1987,"52 rue du Moulin Vert","75014","PARIS",,"0631082819"
"A3","05864","M.","RIBOURDOUILLE","Pierre",27/02/1985,"18 allée de la Cascade","95240","CORMEILLES-EN-PARISIS","01 39 97 28 39","06 33 57 03 08"
"A3","05901","M.","RICHEBE","Ghislain",28/12/1987,"6 rue de Solférino","75007","PARIS","01 45 51 83 21","06 70 40 10 04"
"A3","05899","M.","RIOUX","Pierre",05/08/1987,"4, rue du Champ d\'Amour","45130","MEUNG SUR LOIRE","02 38 44 72 30","06 73 09 80 61"
"A3","05379","M.","RIQUET","Benjamin",08/04/1987,"7 bis rue Raynouard","75006","PARIS",,"06 27 19 31 95"
"A3","05907","M.","ROCAS","Nicolas",07/01/1987,"9 rue Armand Moisant","75015","PARIS",,"06 42 52 98 60"
"A3","05472","M.","ROPITAL","Guillaume",25/11/1988,"3 rue Montaigne","78180","MONTIGNY LE BRETONEUX","01 30 43 56 39","06 88 94 55 77"
"A3","05886","M.","ROUGEVIN-BAVILLE","Vincent",31/03/1985,"28, rue Letellier","75015","PARIS",,"06 12 08 13 05"
"A3","05469","M.","ROUSSEAU","Hubert",09/06/1988,"12 rue du Capitaine Ferber -Appt D34","92130","ISSY LES MOULINEAUX",,"06 69 47 49 13"
"A3","05906","M.","ROUSSEAU","Romain",12/04/1987,"10 impasse Robiquet","75006","PARIS",,"06 45 47 56 01"
"A3","05898","M.","ROUSSEAU","Sébastien",03/10/1985,"33 rue du Général Leclerc","92130","ISSY LES MOULINEAUX",,"06 76 66 85 25"
"A3","05372","MLLE","ROUYER","Marion",09/10/1988,"8 rue Saint-Saens","75015","PARIS","0175574287","06 83 40 36 13"
"A3","05865","M.","ROWE","Lloyd",27/05/1986,"30 rue des Biches","27000","EVREUX","02 32 28 32 49","06 09 32 77 14"
"A3","05904","MLLE","RUYFFELAERE","Camille",28/08/1985,"180 avenue de Paris","92320","CHATILLON",,"06 33 10 45 24"
"A3","04132","M.","SAADANI HASSANI","Jaafar",09/03/1985,"8, rue François Mouthon","75015","PARIS",,"06 68 22 30 07"
"A3","6290","M.","SAÏD MOHAMED","Issam",22/04/1987,"2 avenue Jean Lebas","93140","BONDY","01 48 48 42 81","06 36 87 22 04"
"A3","6310","MLLE","SAÏDANI","Halima",16/03/1987,"8 rue de Montyon","75009","PARIS","01 45 23 25 82","06 66 04 92 75"
"A3","6291","M.","SAINT-JOLY","Jonathan",03/12/1988,"8 rue Léon Cogniet","75017","PARIS 17",,"06 34 63 35 87"
"A3","04486","M.","SAP","Olivier",20/06/1984,"21, rue Ste Croix de la Bretonnerie","75004","PARIS","01 40 27 91 17","06 09 24 35 44"
"A3","05116","M.","SAYAVONGSA","Joseph",22/03/1987,"1 passage Alexandre Moret","77600","BUSSY ST GEORGES","01 64 77 05 91","06 32 19 63 62"
"A3","05002","M.","SCHINDLER","Antoine",22/04/1986,"6, résidence Petite Place","78000","VERSAILLES","01 39 20 97 30","06"
"A3","05615","M.","SCRIVE","Charles-Antoine",16/09/1986,"14, rue Dupont des Loges","75007","PARIS",,"06 76 63 05 21"
"A3","05937","M.","SEHIL","Samir",28/04/1986,"7, rue Pierre de Ronsard","78200","MANTES LA JOLIE","01 30 94 24 38","06 09 79 42 41"
"A3","05524","M.","SEIFERT","Clément",18/03/1989,"80 rue Perthuis","92140","CLAMART","01 46 26 58 08","06 70 03 13 82"
"A3","05893","M.","SERRES","Bertrand",18/03/1987,"3 rue des Labours","31320","CASTANET TOLOSAN",,"06 74 05 91 99"
"A3","05866","M.","SILVA","José",13/12/1987,"29, rue du Muguet","93420","VILLEPINTE","01 48 61 83 47","06 23 07 71 60"
"A3","05526","M.","SIMONIN","Kévin",29/03/1986,"34, rue St Dominique, Escalier C, 1er étage, Porte 2","75007","PARIS",,"06 79 63 52 30"
"A3","05399","M.","SISSOKO","Djibril",05/09/1988,"153 rue Championnet","75018","PARIS","01 42 54 37 51","06 10 75 46 35"
"A3","6293","M.","SIVET","Rodolphe",03/02/1987,"26 rue de la Sablière","75014","PARIS",,"06 70 19 73 02"
"A3","05616","M.","SONAFOUO FONTSA","Dan De Grace",04/01/1987,"36 rue Ernest Renan","92130","ISSY LES MOULINEAUX",,"06 10 99 36 41"
"A3","05159","M.","SOULLIE","Arnaud",30/04/1987,"19 rue de Lourmel","75015","PARIS",,"06 15 25 31 02"
"A3","05003","M.","STAVAUX","Edouard",15/08/1987,"53 ter, boulevard Picpus","75012","PARIS",,"06 15 79 07 95"
"A3","03757","M.","STEPHANOVITCH","Franck",13/03/1984,"15 bis, rue Ernest Cognacq","92300","LEVALLOIS PERRET","01 47 57 37 35","06 63 45 37 35"
"A3","05947","M.","SUAU-LAPEYRONNIE","Thomas",02/07/1985,"58, avenue du Mont Valérien","92500","RUEIL MALMAISON","01 47 49 45 04","06 32 27 13 71"
"A3","04510","M.","SWAR","Toufik",18/05/1986,"14 rue Camille Pelletan","92120","MONTROUGE",,"06 89 57 00 32"
"A3","05795","M.","TABKA","Sami",23/03/1987,"5, rue des Maréchaux","78580","MAULE","01 34 75 14 84","06 64 49 57 24"
"A3","05956","MLLE","TABTI","Catya",22/06/1987,"8, bd de la Marne","94130","NOGENT SUR MARNE","01 48 73 31 60","06 64 89 97 11"
"A3","05118","M.","TAHERALY","Zohair",28/08/1987,"111, avenue de Paris, Appartement 161","92320","CHATILLON","01 46 55 86 59","06 10 90 31 63"
"A3","6294","M.","TANDONNET","Charles",18/01/1988,"24 rue Alexandre Lange","78000","VERSAILLES","01 39 54 71 25","06 64 24 41 14"
"A3","6295","M.","TAT","David",30/06/1987,"33 rue du Docteur Fleming","93600","AULNAY SOUS BOIS","01 43 83 39 89","06 06 57 74 13"
"A3","05911","M.","TAVERNIER","Alexandre",18/04/1987,"47 Boulevard Saint Germain","75005","PARIS","01 46 33 56 59","06 69 00 37 53"
"A3","05138","M.","TAZI","Ali",22/05/1987,"88, boulevard de Port Royal","75005","PARIS",,"06 11 12 14 70"
"A3","6296","M.","TER SCHIPHORST","Julien",26/07/1988,"14 rue d\'amsterdam","75009","PARIS 09",,"06 78 30 46 06"
"A3","8888","M.","TESTDEUXMILLEDIX","Albin",01/01/2010,"rue de l\'élève de la virgule","94120","FONTENAY SOUS BOIS","01+","063-89-+"
"A3","05455","MLLE","TEULER","Anne-Cécile",30/12/1988,"85 rue de Rennes","75006","PARIS",,"06 23 83 13 72"
"A3","05915","M.","TEZENAS DU MONTCEL","Nicolas",05/04/1986,"7 avenue Frémiet","75016","PARIS","09 53 45 60 92","06 98 40 70 35"
"A3","05360","M.","THAVAGUNASEELAN","Sakthes",30/10/1988,"7 rue Becquet","93150","LE BLANC MESNIL","01 43 84 69 85",
"A3","6147","M.","THEVAENDIRARAJA","Naveenan",25/06/1988,"6 rue Emile Zola","93400","ST OUEN","01 40 10 94 20","06 23 32 17 15"
"A3","05943","M.","THOMAS","Alexandre",16/12/1986,"32 chemin des Claies","95320","SAINT LEU LA FORET","01 30 40 73 19","06 83 82 27 09"
"A3","05160","M.","TOURNIGAND","Emmanuel",07/07/1987,"25 rue Galliéni","92600","ASNIERES-SUR-SEINE",,"06 75 26 40 51"
"A3","04224","M.","TRAUT","Nicolas",03/09/1984,"32, rue de la Ferme","92200","NEUILLY SUR SEINE","01 47 47 57 75","06 64 24 20 62"
"A3","05024","M.","TREGOUET","Tanguy",01/04/1986,"14, rue Rouget de Lisle","78100","ST GERMAIN EN LAYE","01 30 61 52 88","06 98 82 73 54"
"A3","6148","M.","TROHEL","Guillaume",28/11/1988,"27 rue Georges Viard","78700","CONFLANS SAINTE HONORINE","01 39 77 33 74","06 79 91 09 81"
"A3","05456","M.","TRUFFERT","Thibault",18/05/1988,"3 villa Coeur de Vey","75014","PARIS 14","0145411242","06 71 85 06 44"
"A3","05439","M.","TUARZE","Vianney",26/10/1988,"48 bd du Montparnasse","75015","PARIS","01 43 37 13 99","06 60 60 98 77"
"A3","6297","M.","TUTALA","David",14/10/1987,"8 rue Delambre","75014","PARIS 14","04 94 69 96 48","06 59 81 61 65"
"A3","05867","M.","VALDENAIRE","Guillaume",07/08/1986,"57 avenue de Tournaisis","78990","ELANCOURT","01 34 82 86 03","06 15 64 22 74"
"A3","05088","M.","VALENTIN","Hugues",08/06/1987,"7 rue Decamps","75116","PARIS","01 47 27 23 38","06 64 03 93 00"
"A3","05216","MLLE","VALLEJO","Maïté",29/06/1985,"67 rue Charles Frérot","94250","GENTILLY",,"06 30 00 43 56"
"A3","6298","M.","VAN SANTEN","Hugo",23/05/1988,"51 avenue Marcelin berthelot chambre 804","92320","CHATILLON",,"06 06 72 68 36"
"A3","05485","M.","VARILLON","Thomas",25/07/1988,"100 rue de Reims","94700","MAISONS ALFORT","09 54 56 51 89","06 35 59 37 15"
"A3","05139","M.","VERGNOT","Paul",23/07/1987,"5 square Vincent Scotto","85300","CHALLANS",,"0052 133 10 40 16 73"
"A3","6299","M.","VIGNOLLES","Matthias",17/04/1986,"37 avenue du Général Michel Bizot","75012","PARIS","01 40 04 92 02","06 08 35 89 21"
"A3","6302","M.","VILLEMAIN","Jean-Baptiste",21/05/1988,"54 rue de Montreuil","78000","VERSAILLES","01 39 02 07 55","06 42 18 29 66"
"A3","05362","M.","VIZARD","William",11/01/1987,"11 rue de la Quintinie","75015","PARIS","01 43 06 50 40","06 71 92 04 83"
"A3","05438","M.","WAGNER","David",08/11/1988,"61 rue de Bondy","93250","VILLEMOMBLE","01 48 55 75 09","+49 152 4 69 91 29"
"A3","05053","M.","WAMBRE","Alexandre",27/04/1987,"11 quai Anatole France","75007","PARIS",,"06 33 07 58 69"
"A3","06020","M.","WANG","ChuFeng",11/05/1986,"Les Estudines Paris Ivry, 4, rue Jean Jacques Rousseau","94200","IVRY SUR SEINE",,
"A3","05461","M.","WANG","François",13/01/1989,"12 place Felix Eboue","75012","PARIS 12","01 48 30 20 31","06 29 43 44 22"
"A3","05690","M.","WANG","Yu",21/10/1984,"17 rue Pierre Nicole","75005","PARIS",,"06 48 70 16 44"
"A3","6402","MLLE","XIANG","Chen",27/11/1987,"65 rue Saint Didier","75016","PARIS",,"06 58 34 60 06"
"A3","06022","M.","XIE","Jian",20/09/1983,"3 avenue de Choisy","75013","PARIS",,
"A3","06023","M.","XIONG","Jiannan",08/01/1987,"33 rue du Général Leclerc - Ch. 312","92130","ISSY LES MOULINEAUX",,"06 36 31 31 33"
"A3","05625","M.","YALE BALUME","Stéphane",30/05/1986,"41 rue Tournefort, Résidence Concordia","75005","PARIS",,"06 13 92 71 40"
"A3","6403","M.","YI","Wei",28/01/1987,"161 boulevard Edouard Vaillant","93300","AUBERVILLIERS",,"06 27 46 67 52"
"A3","05162","M.","YITH","Pascal",19/12/1986,"Résidence du Parc - Studio 115 - Etage 1- 15 rue du Séminaire de Conflans","94220","CHARENTON LE PONT",,"06 26 37 97 67"
"A3","6404","M.","YU","Cailiang",02/06/1986,"100 av de Lattre de Tassigny","94000","CRETEIL",,"06 28 62 38 61"
"A3","6405","M.","ZHANG","Jun",11/10/1986,"40 bis rue Violet","75015","PARIS",,"06 21 75 80 87"
"A3","6406","M.","ZHAO","Wenlei",16/07/1987,"86 rue des Blancs Murs","94400","VITRY-SUR-SEINE",,"0634993028"
"A3","06024","MLLE","ZHOU","Yunke",05/12/1985,"Chambre 30 - Résidence Cosmos F, 25 rue des Hortensias","22300","LANNION",,
"A3","05938","M.","ZLITNI","Nidal",07/01/1987,"5 allée d\'Anjou","78200","MAGNANVILLE","01 34 77 11 21","06 99 25 20 54"
"A3","6301","MLLE","ZNIBER","Samya",29/02/1988,"85 bis rue de Charenton, Appartement 1er gauche","75012","PARIS",,"06 19 66 59 23"
"A3-DOUBL","04633","M.","KORHANI","Bilal",24/09/1986,"1 impasse Barbier","92110","CLICHY","01 55 90 65 17",
"A3-SEM","05220","M.","DENYSENKO","Iwan",11/12/1984,"30, boulevard Barbès","75018","PARIS",,"06 65 32 55 74"
"A3-SEM","05594","M.","LALAHY","Leong",08/08/1985,"135 bd Danielle Casanova","13014","MARSEILLE",,"06 98 12 91 37"
"A3-SEM","05597","M.","LE PIVAIN","Jacques",07/06/1984,"48 rue Victor Hugo","92700","COLOMBES",,"06 31 74 76 80"
"A3-SEM","04540","M.","SOUDANT","Matthieu",13/09/1986,"33 rue de Constantinople","75008","PARIS","01 46 68 89 70",
"AC","6416","M.","BINOCHE MOREAU","Jean-Alexandre",30/06/1986,"15 rue René Aperré","92700","COLOMBES",,"06 33 13 66 94"
"AC","6740","M.","CASADO BLANCO","Antonio",10/11/1987,"8 rue Legouvé","75010","PARIS",,
"AC","6430","MLLE","CASADO HUERTAS","Cristina",12/05/1985,"10-12 rue Yitzhak Rabin","94270","LE KREMLIN BICETRE",,
"AC","6705","MLLE","CHIA","Miao",14/09/1988,"Résidence René-Dubos, 10-12 rue Yitzhak Rabin","94270","LE KREMLIN BICETRE","01 42 11 20 00",
"AC","6745","MLLE","DIDEKOVA","Zuzana",06/11/1982,"Association Reille - 34 avenue Reille","75014","PARIS","01 43 13 12 11",
"AC","6429","MLLE","FREYRE CARRILLO","Laura",18/11/1986,"Hôtel  BVJ - 44 rue des Bernardins","75005","PARIS",,"34690001039"
"AC","6738","MLLE","GALLEGO QUEVEDO","Lucia",25/11/1986,"31-35 rue Falguière - Immeuble Le Dôme","75015","PARIS",,
"AC","6425","M.","GARCIA GALLARDO","Javier",03/04/1985,"9 rue du Moulin Vert  - Chambre 215","75014","PARIS",,
"AC","6737","M.","GARCIA ORTIZ","Teodoro",25/11/1987,"Résidence Saint-Jacques - Studio 619    16 rue Jean-Claude Arnould","75014","PARIS",,
"AC","6415","M.","GEVIN","Jean-Brice",02/01/1986,"11 d rue des Sèvres","75006","PARIS",,"06 73 61 14 35"
"AC","6731","MLLE","GHISLOTI DUARTE DE SOUZA","Renata",14/08/1987,"212 rue de Tolbaic - Chambre 703","75013","PARIS",,
"AC","6798","M.","GIBERT","Antoine",01/01/2010,"94 rue Lafayette","75010","PARIS",,"06 85 97 97 15"
"AC","6428","M.","GONZALEZ","Javier",09/05/1986,"33 rue du Général Leclerc","92130","ISSY LES MOULINEAUX",,"34666920240"
"AC","6766","M.","GOZALO ZARZOSA","Enrique",11/05/1987,"Chez Mademoiselle Lucia GALLEGO, 31-35 rue Falguière - Immeuble Le Dôme","75015","PARIS",,
"AC","6618","MLLE","GUPTA","Anju",13/08/1984,"Cité Internationale Universit. Paris    Maison Inde - Ch 205 - 7 R bd Jourdan","75014","PARIS",,
"AC","6426","M.","HERRERA GARZA","Irvin",24/08/1988,"9 rue du Moulin Vert","75014","PARIS",,
"AC","6417","M.","HOLLANDE","Arnaud",16/09/1987,"6 rue Duquesne","83000","TOULON",,"06 99 26 16 09"
"AC","6617","MLLE","HRAZIIA","H.",14/03/1984,"Cité Internationale Universit. Paris    Maison Inde - Ch 205 - 7 R bd Jourdan","75014","PARIS",,
"AC","6736","M.","HUANG","I-Shu",16/09/1985,,,,,
"AC","6419","M.","KARAGOZ","Ali",26/06/1985,"142 rue Haxo","75019","PARIS",,"06 50 01 24 58"
"AC","6733","M.","KRIZ","Tomas",01/07/1980,"33 rue du Général Leclerrc","92130","ISSY-LES-MOULINEAUX",,
"AC","6743","M.","LEAL DIAZ","Jesus",22/02/1988,"95 rue Jean-Pierre Timbaud","75011","PARIS",,
"AC","6420","M.","LEFAUCHEUX","Corentin",30/10/1986,"93 boulevard Henri Barbusse","91210","DRAVEIL",,"06 34 02 88 58"
"AC","6418","M.","LINCOLN","Benoît",01/01/2009,"14 rue de la Glacière","83000","TOULON",,"06 23 69 47 98"
"AC","6761","M.","LOPEZ RODRIGUEZ","Pedro",20/09/1984,"49 bis rue du Borrégo","75020","PARIS",,"06 49 18 63 28"
"AC","6414","M.","MANSCOUR","Hadrien",21/09/1987,"Mas d\'en casenove route du paloll à la s","66400","CERET",,"06 87 39 74 61"
"AC","6742","M.","MARCON","Petr",08/02/1985,"33 rue du Général Leclerc","92130","ISSY-LES-MOULINEAUX",,"420 724 774 733"
"AC","6431","MLLE","MARTIN ASENSIO","Maria Luisa",13/04/1985,"10-12 rue Yitzhak Rabin","94270","LE KREMLIN BICETRE",,
"AC","6720","M.","MURGA GONZALEZ","Mauricio",15/02/1989,"33 rue du Général Leclerc - Chambre 324","92130","ISSY LES MOULINEAUX",,
"AC","6800","M.","NEDOUNDJEJIANE","Sakthivel",01/01/2010,"5- rue Stockholm","94510","LA QUEUE-EN-BRIE",,
"AC","6765","MLLE","NIETO GONZALEZ","Noelia",05/10/1988,"95 rue Jean-Pierre Timbaud","75011","PARIS",,
"AC","6734","M.","NUGRAHA BUDI","Thomas",05/06/1991,"Résidence René Dubos, 10-12 rue Yitzhak Rabin","94270","LE KREMLIN BICETRE","01 42 11 21 43","06 43 55 99 97"
"AC","6421","MLLE","OLALDE","Maider",23/02/1984,"9 rue du Moulin Vert - Chambre 213","75014","PARIS",,
"AC","6741","M.","OTTO","Adam",04/08/1989,"33 rue du Général Leclerc","92130","ISSY LES MOULINEAUX",,
"AC","6739","M.","PEREZ HERNANDEZ","Josu",10/07/1985,"9 rue du Moulin Vert","75014","PARIS",,
"AC","6801","M.","PONSARD","Jean-Jack",05/06/1986,"33 rue du Général Leclerc, Séminaire St Sulpice - Chambre 227","92130","ISSY-LES-MOULINEAUX",,"06 86 15 92 11"
"AC","6410","M.","POULARD","Thomas",05/09/1985,"47 bis rue de Lourmel","75015","PARIS",,"06 21 89 46 01"
"AC","6719","MLLE","QUINTANA PANDO","Maria",18/04/1988,"Résidence Dubos, 10/12 rue Yizhak Robin","94270","LE KREMLIN BICETRE","01 42 11 20 00",
"AC","6735","M.","RENALDO","Hendry",27/07/1990,"Résidence René Dubos, 10-12 rue Yitzhak Rabin","94270","LE KREMLIN BICETRE",,"06 43 80 05 63"
"AC","6427","MLLE","RIOS ARREGUIN","Rosalia Del Carmen",16/07/1989,"31-35 rue Falguière","75015","PARIS",,"06 29 60 91 73"
"AC","6486","M.","RODRIGUEZ VERA","Eduardo",27/03/1986,"33 rue du Général Leclerc","92130","ISSY LES MOULINEAUX",,
"AC","6744","M.","SEDOV","Boris",28/02/1988,,,,,
"AC","6424","M.","STEPANOV","Alexander",16/11/1987,,,,,
"AC","6799","M.","TIRROLONI","Quentin",30/09/1987,"35 rue Saint-Fiacre - Résidence 3 - Appt","77100","MEAUX",,"06 26 39 37 50"
"AC","6710","M.","VASHISHTHA","Vinay",10/03/1988,"Maison de l\'Inde - 7 R bd Jourdan","75014","PARIS",,
"AC","6709","M.","ZHANGUZIN","Daulet",25/02/1989,"Résidence René-Dubos, 10-12 rue Yitzkak Rabin","94270","LE KREMLIN BICETRE",,
"CESURE-A2-A3","6240","M.","AMEIL","Sébastien",08/05/1987,"107 rue de Lille","75007","PARIS 07",,"06 58 25 58 58"
"CESURE-A2-A3","05433","M.","BARBIER","Guillaume",29/12/1988,"3 allée des Vergers","78100","ST GERMAIN EN LAYE","01 39 73 86 92","06 30 10 16 74"
"CESURE-A2-A3","05408","M.","BAUDRON","Marc",24/05/1989,"39 Grande Rue","77580","VILLIERS SUR MORIN","01 64 63 07 72","06 12 16 35 60"
"CESURE-A2-A3","05412","M.","BOURGOIS","Romain",28/07/1988,"155 bd de la Reine - 3ème étage","78000","VERSAILLES","01 39 51 71 90","06 29 45 02 03"
"CESURE-A2-A3","05478","M.","BRAY","Pierre-Loïc",01/10/1988,"8 rue Paul Doumer","78140","VELIZY VILLACOUBLAY","01 34 65 34 62","06 71 96 15 44"
"CESURE-A2-A3","6265","M.","HONORINE","Mickael",03/05/1988,"23 rue Lecocq","94250","GENTILLY","09 54 09 04 92","06 76 54 04 21"
"CESURE-A2-A3","05434","M.","SENOUCI","Omrane",06/07/1987,"2 rue Honoré","93500","PANTIN","08 72 10 41 69","06 27 05 62 20"
"CESURE-A2-A3","05824","M.","VIGNAL","Benjamin",07/08/1987,"4 place du Moustier","92210","SAINT CLOUD","01 49 11 02 62","06 37 41 79 98"
"FEDP","7056","M.","CHENG","Long",26/01/1988,"Séminaire Saint Sulpice, 33 rue du Général Leclerc","92130","ISSY LES MOULINEAUX",,
"FEDP","7057","MLLE","RAO","Ni",02/06/1988,"Résidence Dubos- 10/12 Yitzhak Rabin","94270","KREMLIN BICETRE",,
"M1","6962","M.","ABDULLAHI","Nasiru",07/07/1977,"Séminaire Saint Sulpice - 33 rue général Leclerc","92110","Issy les Moulineaux",,
"M1","6633","M.","AGRAWAL","Amit",31/10/1983,"Saint Sulpice, 33 rue du Gén. Leclerc","92110","ISSY LES MOULINEAUX",,
"M1","6333","M.","AHMED","Laiq",29/11/1979,"14 bis, rue Jules Genovesi","93200","SAINT DENIS",,
"M1","6631","MLLE","AL HOUQANI","Mouza",14/12/1986,"Studélites Le Capitole - 10 rue Fulton","75013","PARIS",,
"M1","6632","MLLE","AL REJAIBI","Haila",07/12/1986,"Studélites Le Capitole - 10 rue Fulton","75013","PARIS",,
"M1","6184","M.","ALMANSOORI","Ali",01/01/1979,"6, rue Carpeaux","92400","COURBEVOIE",,
"M1","7062","MME","AMARESAN","Hemalatha",10/06/1986,,,"PARIS",,
"M1","6620","M.","ANANTHAKUMAR","Sai Prasadh",09/03/1988,"Maison de l\'Inde - 7R bd Jourdan","75014","PARIS",,
"M1","6178","M.","BALDASSAR CASPAR","Balario",09/06/1983,"11 impasse Saillanfait","94700","MAISONS ALFORT",,
"M1","6960","MLLE","BAÑUELOS","Paola",09/08/1982,"100 avenue du Maréchal de Lattre, de Tassigny - Appt B305","94000","CRETEIL",,
"M1","6330","MLLE","BARSKY SANCHEZ","Irina",12/06/1981,"11, rue du Général du Larminat","75015","PARIS",,
"M1","6181","M.","BEKBAUOV","Darkhan",06/06/1985,"10-12, rue Reynaud","75019","PARIS",,
"M1","6627","M.","BHAGWATE","Rohan",18/07/1986,"Maison de l\'Inde - 7R bd Jourdan","75014","PARIS",,
"M1","6187","M.","BHASKARA PANTHULA","Naga Ravi Teja",11/03/1987,"Maison de l\'Inde, 7R bd Jourdan, Chambre 309","75014","PARIS",,
"M1","04664","M.","BIRJANDY","Babak",08/03/1981,"22 - 24, rue de Belfort","92400","COURBEVOIE",,
"M1","6971","MME","BOOPATHIRAJ","Monika",21/12/1986,,,"PARIS",,
"M1","7041","M.","DESHPANDE","Ameya",18/06/1985,"Maison de l\'Inde -  7R bd jourdan","75014","PARIS",,
"M1","6660","M.","DEVENDIRAN","Arun Kumar",18/06/1980,"52, rue du Château","92340","BOURG LA REINE",,
"M1","6170","MLLE","DJEGANAYAGUY","Kalaiarasi",13/10/1986,"1, rue de Copenhague","78990","ELANCOURT",,
"M1","6963","M.","DOGRA","Ripul",12/01/1988,"Centre d\'Accueil International, 9 rue du moulin vert","75014","PARIS",,
"M1","6619","MLLE","EGUEZ DEL POZO","Maria Belen",28/05/1981,"Foyer Reille - 34 avenue Reille","75014","PARIS",,
"M1","6335","M.","GADUGU","Gibith Kumar",14/10/1984,"C/O Naneen Yalamanchili, 12, rue Roli","75014","PARIS",,
"M1","6964","M.","GAMBINA","Mariano",31/12/1983,"Couvent Saint Jacques, 20 rue des tanneries","75014","PARIS",,
"M1","6188","MLLE","GOYAL","Swati",03/10/1984,"Maison de l\'Inde, 7R bd Jourdan","75014","PARIS",,
"M1","6185","MLLE","GROVER","Deepti",17/06/1983,"11, rue Eugène Pottier","93700","DRANCY",,
"M1","6965","MLLE","GUTIERREZ VARGAS","Zaida",01/08/1985,"100 avenue du Maréchal de Lattre, de Tassigny - Appt B305","94000","CRETEIL",,
"M1","6182","M.","IRUAFEMI","Imonrunu",13/01/1974,"CIUP - 6, avenue René Fonck","75019","PARIS",,
"M1","05313","M.","KAKARALA","Bhargav",21/02/1979,"Résidence Etudiante Berges de Seine, 6, allées de l\'Europe - Apt 614","92110","CLICHY",,
"M1","7061","MME","KAMAGHE","Juliana",12/06/1983,"Foyer Reille - 34 avenue Reille","75014","PARIS",,
"M1","6623","M.","KANI","Michael",28/04/1980,"Résidence Rollin, n°8181 - 14 rue Rollin","75005","PARIS",,
"M1","6959","M.","KASHINATH GANAPATHY","Kashinath Ganapathy",17/10/1988,"Maison de l\'Inde - 7R bd jourdan","75014","PARIS",,
"M1","6958","M.","KHAN","Yasir",07/05/1985,"Maison de l\'Inde -  7R bd jourdan","75014","PARIS",,
"M1","6175","M.","KHOSRAVI","Ebrahim",14/04/1976,"146, rue de Charenton","75012","PARIS",,
"M1","6331","M.","KUMAR","Vivek",20/08/1984,"Studéa Atalante, 6, avenue de Bois de l\'Abbé","35000","RENNES",,
"M1","6625","M.","LAL","Suvansh",18/12/1986,"Maison de l\'Inde - 7R bd Jourdan","75014","PARIS",,
"M1","7043","M.","LOGANATHAN","SP",08/12/1980,"4 avenue  Elise Deroche","93350","LE BOURGET",,
"M1","6629","M.","LUNA VAZQUEZ","Sergio",14/07/1987,"Couvent St Jacques, 20 rue des Tanneries","75013","PARIS",,
"M1","6638","M.","MANDOLKAR","Chidanand",14/05/1986,"Maison de l\'Inde - 7R bd Jourdan","75014","PARIS",,
"M1","05317","M.","MOHAMMED","Dastagir",08/04/1981,"Résidence Etudiante Berges de Seine, 6, allées de l\'Europe - Apt 614","92110","CLICHY",,
"M1","6183","M.","MONTANI","Antonio",14/05/1983,"CAI 9, rue du Moulin Vert","75014","PARIS",,
"M1","7039","M.","MORE","Pushkar",29/10/1986,"Maison de l\'Inde - 7R bd jourdan","75014","PARIS",,
"M1","6177","M.","MYSORE SREENIVASA","Mallikarjuna",17/09/1983,"Maison de l\'Inde, 7R bd Jourdan, Chambre 202","75014","PARIS",,
"M1","6624","M.","NAGALUR","Subraveti Prithvi Raj",29/08/1987,"Maison de l\'Inde - 7R bd Jourdan","75014","PARIS",,
"M1","6972","M.","NARRA","Abhishek",27/03/1988,"Centre d\'Accueil International, 9 rue du moulin vert","75014","PARIS",,
"M1","05971","M.","NEELA","Srinivas",16/11/1983,"Résidence Lila, appt B122, 6, avenue René Fonck","75019","PARIS",,
"M1","05984","M.","PAJANINADANE","Balamurugan",08/05/1986,"CIUP - Maison de l\'Inde, 7R, boulevard Jourdan","75014","PARIS",,
"M1","6180","M.","PAJANINADANE","Manikandhan",30/12/1982,"14, rue de la Fosse Popine","91200","ATHIS MONS",,
"M1","6635","MME","PANCHAL","Sonali",12/09/1986,"6, allée de l\'Europe","92130","CLICHY",,
"M1","05321","M.","PATEL","Bhupendrakumar",18/07/1978,"6, rue des Pommiers","93500","PANTIN",,
"M1","6955","MLLE","PATEL","Kinjalben",03/01/1988,,,"PARIS",,
"M1","6967","M.","PEREZ","Luis",06/11/1984,"Séminaire Saint Sulpice - 33 rue général Leclerc","92110","Issy les Moulineaux",,
"M1","05978","M.","PIDIKITI","Venkata Praneel",18/08/1985,"CIUP - Maison de l\'Inde, 7R, boulevard Jourdan","75019","PARIS",,
"M1","6966","MLLE","POZOS","Elizabeth",08/08/1981,"11 rue du Regard","75006","PARIS",,
"M1","7064","M.","PULI","Phani Kumar",19/07/1985,,,"PARIS",,
"M1","6332","M.","RADJAMANICAME","Radja Radjesvarane",15/07/1983,"C073, Résidence Chambord, 91, rue Abelard","77100","MEAUX",,
"M1","6634","M.","RADJASSEGARANE","Vasanth Vijayaraj",20/07/1982,"14, rue Chateaubriand","91600","SAVIGNY/ORGE",,
"M1","6969","M.","RAJASEKARAN","Karthik",07/07/1988,"C/O M. Maurice Maguendirane, 5D rue Neuve","77127","LIEUSAINT",,
"M1","6626","M.","RAMNANI","Vikas",05/01/1985,"Maison de l\'Inde, 7R bd Jourdan","75014","PARIS",,
"M1","6968","MLLE","REZAZADEH JOODI","Yasaman",31/03/1986,"Centre d\'Accueil International, 9 rue du moulin vert","75014","PARIS",,
"M1","6186","M.","RISTANOVIC","Ognjen",31/07/1983,"CAI 9, rue du Moulin Vert","75014","PARIS",,
"M1","05812","MME","ROJMALA","Minaben",16/05/1986,"98, avenue de Versailles","75016","PARIS",,
"M1","6191","M.","RONDON","Alejandro",16/01/1985,"33, rue du Général Leclerc","92130","ISSY LES MOULINEAUX",,
"M1","6622","M.","ROW","Ashwin",14/09/1984,"Saint Sulpice, 33 rue du Gén. Leclerc","92110","ISSY LES MOULINEAUX",,
"M1","6621","M.","SAINI","Reetesh",15/04/1984,"Maison de l\'Inde - 7R bd Jourdan","75014","PARIS",,
"M1","6953","M.","SASI","Athul",17/02/1984,"Centre d\'Accueil International, 9 rue du moulin vert","75014","PARIS",,
"M1","6636","MLLE","SATTIANADIN","Dhivviya",10/02/1988,"1 rue Jules Massenet","78330","FONTENAY LE FLEURY",,
"M1","6954","M.","SIKKA","Karan",24/07/1987,"Maison de l\'Inde - 7R bd jourdan","75014","PARIS",,
"M1","6190","M.","SILVA BYTTON","Christian",05/05/1979,"20, rue des Tanneries","75013","PARIS",,
"M1","6628","M.","SINGHAL","Harshraj",20/07/1987,"Maison de l\'Inde - 7R bd Jourdan","75014","PARIS",,
"M1","7040","M.","SINHA","Pratyush",25/11/1984,"Maison de l\'Inde - 7R bd jourdan","75014","PARIS",,
"M1","7038","M.","SINNAS","Rohit",23/08/1986,"Maison de l\'Inde - 7R bd jourdan","75014","PARIS",,
"M1","6172","M.","SONI","Saurabh",09/05/1982,"Maison de l\'Inde, 7R bd Jourdan, Chambre 408","75014","PARIS",,
"M1","6957","M.","TRIDARMAWAN","Yudarwin",03/07/1973,"56, rue Jean-Pierre Timbaud","92400","COURBEVOIE",,
"M1","6173","M.","VAKHSHOORZADEH","Seyed Mahdeen",20/02/1982,"CAI 9, rue du Moulin Vert","75014","PARIS",,
"M1","6189","MLLE","VALIPOUR GOUDARZI","Saba",14/10/1984,"9, rue Alasseur","75015","PARIS",,
"M1","6637","M.","VEDELER","Christian",18/02/1975,"236, avenue Victor Hugo","92140","CLAMART",,
"M1","05986","M.","VENKATTARAMAN","Jayanthan",17/09/1984,"C/O M. Ilangovane, apt. 123, 8, allée Edouard Branly","77420","CHAMPS SUR MARNE",,
"M1","6956","M.","VIJAYARANGAM","Ilamvazudhi",08/02/1988,"Maison de l\'Inde -  7R bd jourdan","75014","PARIS",,
"M1","7037","M.","YENIPELA","Venkata",14/09/1985,"Maison de l\'Inde - 7R bd jourdan","75014","PARIS",,
"M1","6961","M.","ZAMBRANA MEMBREÑO","Allan Illich",26/01/1979,"5 rue Vavin","75006","PARIS",,
"M2","6725","MLLE","DAWARE","Rajshree Subhash",12/05/1985,"Résidence René Dubos, 10/12 rue Yitzhak Rabin","94270","KREMLIN BICETRE",,
"M2","6729","M.","DONGARE","Mandar Madhav",30/06/1987,"Résidence ISEP, 33 avenue Général Leclerc","92130","ISSY les MOULINEAUX",,
"M2","6721","MLLE","GATE","Samruddhi",11/05/1988,"Résidence REILLE, 34 av. Reille","75014","PARIS",,
"M2","6722","MLLE","GOLWALKAR","Gargi Uday",25/02/1987,"CIUP - Maison de l\'Inde, 7R bd Jourdan","75014","PARIS",,
"M2","6726","M.","JADHAV","Manoj Sarjerao",09/09/1987,"Résidence ISEP, 33 avenue Général Leclerc","92130","ISSY les MOULINEAUX",,
"M2","6730","MLLE","KATAWATE","Swapnali Madhukar",20/04/1987,"Résidence René Dubos, 10/12 rue Yitzhak Rabin","94270","KREMLIN BICETRE",,
"M2","6724","M.","KUNJIR","Pratik Arun",14/06/1988,"Résidence ISEP, 33 avenue Général Leclerc","92130","ISSY les MOULINEAUX",,
"M2","6727","M.","MORAJKAR","Mandar Shashikant",03/04/1985,"Résidence ISEP, 33 avenue Général Leclerc","92130","ISSY les MOULINEAUX",,
"M2","6728","M.","PHADTARE","Ajit",14/05/1988,"Résidence ISEP, 33 avenue Général Leclerc","92130","ISSY les MOULINEAUX",,
"M2","6723","M.","SORTE","Abhay Ramdas",26/11/1987,"Résidence René Dubos, 10/12 rue Yitzhak Rabin","94270","KREMLIN BICETRE",,
"MS","7055","MME","ADHARI","Aicha",02/12/1980,"220, rue Julian Grimau","94400","VITRY SUR SEINE",,"06 67 31 47 31"
"MS","6676","M.","AUGE","Jean-François",01/09/2009,,,,,
"MS","6697","MME","BONNET-EUCHIN","Florence",13/11/1967,"3 villa Robert Lindet","75015","PARIS",,
"MS","7059","M.","BOUROUFFALA","Morad",29/12/1980,"18, rue Grande","84120","PERTUIS",,"06 59 93 22 09"
"MS","7054","MLLE","BRIERE","Sandrine",21/03/1975,"50, rue de la République - Bat B","93230","ROMAINVILLE",,"06 08 05 69 55"
"MS","6849","M.","BRUNO","Denis",04/01/1968,"4, rue Saint-André","76000","ROUEN",,"06 60 75 76 94"
"MS","6704","M.","BUREAU","Yves",04/08/1946,"50, rue du Vieux Chemin de Pont","60300","SENLIS",,"0608516012"
"MS","7053","M.","CAMPEAUX","Dominique",10/10/1964,"41, rue du Breuil","91360","EPINAY SUR ORGE",,"06 75 54 62 51"
"MS","7060","M.","CARTOT","Sévérine",20/03/1972,"11, rue des Récollets","78000","VERSAILLES",,"06 10 26 88 07"
"MS","6695","M.","CHARIKANE","Eric",21/12/1966,"57, Bld Pasteur","94360","BRY SUR MARNE",,"0670732728"
"MS","6759","MME","CHATELLIER","Sophia",23/09/1965,"27, sentier des Pendants","94500","CHAMPIGNY SUR MARNE",,"06.68.65.45.71"
"MS","6673","M.","CHERA","Lahoussaine",01/09/2009,,,,,
"MS","6703","MME","COHEN","Yael",21/06/1968,"168n rue de Javel","75015","PARIS",,
"MS","6758","M.","CONDETTE","Christophe",21/09/1959,"3, rue Paillet","75005","PARIS",,"06.32.41.72.33"
"MS","7052","MME","CRUTEL","Annica",28/10/1962,"3, allée Monet","91940","LES ULIS",,"06 77 16 10 62"
"MS","6757","M.","DAGER","Patrice",02/01/1972,"15, rue Garnier Pages","94100","ST MAUR DES FOSSES",,"06.79.83.09.55"
"MS","6698","MME","DANIEL","Marie-Pierre",11/05/1968,"1, rue Porh Er Bleye - Penmern","56870","BADEN",,
"MS","7063","MLLE","DAOUDA","Safiatou",21/11/1987,"227, rue de Brément - Apt 129","93130","NOISY LE SEC",,"06 18 33 07 39"
"MS","6674","M.","DEFRANCE","Nicolas",01/09/2009,,,,,
"MS","6756","M.","DEGRYSE","Stéphane",26/02/1964,"Lieu dit ""La Gandraie""","44420","SUCE SUR ERDRE",,"06.50.28.06.89"
"MS","6755","M.","DESBROUSSES","Luc",02/05/1968,"14, rue Corbon","75006","PARIS",,"06.60.14.18.13"
"MS","6699","MME","DESHAYES","Mireille",26/02/1964,"19, rue d\'Hérivaux","60580","COYE LA FORET",,"0618751925"
"MS","7051","MLLE","DILE","Corinne",15/07/1961,"11, villa du Bel Air","75012","PARIS","02 96 14 01 90",
"MS","6377","MME","DIOMANDE","Inssata",04/04/1977,"2, rue Gervex, Hall 20","75017","PARIS",,"06 60 78 26 93"
"MS","6754","MLLE","EL OUFI","Fatima-Zahra",12/09/1984,"28, rue Neuve des Bouchers","18000","BOURGES",,"06.23.12.83.54"
"MS","6672","M.","FANTINI","Marc",01/01/2009,,,,,
"MS","6688","MME","FARGIER","Aurélie",20/02/1975,"30, rue Henry Gorjus - BAT B","69004","LYON",,"0609308986"
"MS","6689","M.","FONDRAZ","Bertrand",29/09/1968,,,,,
"MS","6700","M.","GESNEL","Philippe",02/05/1958,"38, rue Gabrielle",,"CHARENTON",,"0675096223"
"MS","6752","M.","GUILLAUMOND DUTOUR","William",14/01/1984,"41, rue des trois frères","75018","PARIS",,"06.87.62.15.63"
"MS","6753","M.","GUILMAIN","André",12/08/1955,"1 bis, avenue Eglé","78600","MAISONS LAFFITTE",,"06.85.88.93.89"
"MS","6681","M.","HUGUENIN","Nicolas",01/09/2009,,,,,
"MS","05591","M.","HUGUENIN","Nicolas",01/08/1986,"Chez Monsieur Bordes, 34 rue Claude Decaen","75012","PARIS",,"06 73 22 11 61"
"MS","6847","MLLE","HUGUIN","Estelle",14/07/1982,"64, avenue de la Marne","92120","MONTROUGE",,"06 62 81 99 51"
"MS","6836","M.","HULOT","Bernard-Alain",22/01/1960,"3, allée des Framboisiers","95410","GROSLAY","01 39 83 54 89",
"MS","6694","MME","ILLIANO","Elisabeth",22/04/1973,"8, rue de Gex","01210","FERNEY VOLTAIRE",,"0616813605"
"MS","6848","MME","KALIMBADJAN","Mireille",15/05/1956,"58, avenue Auguste Gaudon, Les Aygalades","13006","MARSEILLE",,"06 22 68 36 48"
"MS","6751","M.","KIKOUNGA","Mellon",07/01/1970,"28, rue Alexandre PRACHAY","95300","PONTOISE",,"06.65.62.82.57"
"MS","6696","M.","LACHAUD","Eric",10/04/1967,"93, rue du Poteau","75018","PARIS",,"0663124651"
"MS","7050","M.","LAMOURY","Philippe",29/01/1964,"3, Bd Pierre Mendes France","77600","BUSSY ST GEORGES",,"06 30 09 71 92"
"MS","7049","M.","LEBOISSELIER","Nicolas",12/01/1984,"13, rue Julien Perin","92160","ANTONY",,"06 66 25 03 01"
"MS","6677","M.","LEFEBVRE","Luc-Bernard",01/09/2009,,,,,
"MS","6687","MME","LEPAGE","Anny",22/02/1975,,,,,
"MS","6750","M.","MAUPOUET","Eric",14/06/1966,"2B, rue de l\'Orme","45190","BEAUGENCY",,"06.81.94.23.78"
"MS","6749","M.","MILOUNGOU","Sekou",02/09/1961,"80, avenue du 8 mai 1945, Appt 2064","93330","NEUILLY SUR MARNE",,"06.80.87.85.99"
"MS","7058","M.","NDJANDJA SANDJO","Jacob",31/12/1989,,,"YOUNDE - CAMEROUN",,
"MS","6748","M.","NGOMA","Jimmy-Christel",05/05/1967,"28, rue Alexandre PRACHAY","95300","PONTOISE",,"06.59.98.29.25"
"MS","6839","MLLE","NGUEPDJOP","Liliane Junie",31/03/1984,"Chez M. Kenang Jean, 1, allée Camille Pissarro","95200","SARCELLES",,"06 12 22 06 92"
"MS","6846","MME","NGUYEN","Danielle",22/11/1958,"13, sentier Defait","94230","CACHAN",,"06 84 64 45 11"
"MS","6837","M.","NTAMAG MAHOP","Lucien Jurior",15/03/1978,"53 ter, rue Champs Lagarde","78000","VERSAILLES",,"06 35 39 97 06"
"MS","6845","M.","OLSEN","Michael",31/05/1967,"28, rue du Colonel Rozanoff","75012","PARIS",,
"MS","6838","M.","OUALILI","Aderlkader",28/11/1965,"2, avenue Henri Charon, Apt 340-Bat T23","91270","VIGNEUX SUR SEINE",,"06.60.79.64.24"
"MS","6747","MME","OUDET","Géraldine",26/06/1988,"1, rue de Colmar","92400","COURBEVOIE",,"06.09.93.52.86"
"MS","6808","MME","PAIX","Christine",22/02/1968,"37, rue de l\'Echiquier","75010","PARIS",,
"MS","6806","M.","PAPA","Abdou",13/05/1960,"3, rue Toulouse Lautrec","91000","EVRY",,"06.80.67.37.13"
"MS","6764","M.","PHILIPPE","Bernard",24/05/1956,"35, allée du Bois Carré","91190","GIF SUR YVETTE","01.49.75.17.53",
"MS","7065","M.","PICARD","Laurent",11/08/1965,"29, rue Tandon","75019","PARIS",,"06 65 78 16 09"
"MS","6686","MME","PIENS","Alice",14/06/1960,"11, rue paul Painlevé","33600","PESSAC",,"0617068473"
"MS","6805","MME","PINARD","Nadia",29/08/1958,"Le Creux","38840","ST HILAIRE DU ROSIER",,"06.81.28.47.29"
"MS","7066","M.","POURADIER DUTEIL","Patrick",31/07/1952,"4, avenue de la Porte de Villiers","92200","NEUILLY SUR SEINE",,"06 23 18 11 91"
"MS","6678","M.","PRINTEMPS","Kenny",01/09/2009,,,,,
"MS","7048","M.","QUENTEL","Etienne",16/11/1971,"199, Bld Voltaire","92600","ASNIERES SUR SEINE",,"06 88 16 66 85"
"MS","7047","M.","QUIGNOT","Hubert",23/04/1964,"42, rue de Paris","77 700","MAGNY LE HONGRE","01 40 29 63 74",
"MS","6746","MLLE","RAJAONA","Diane",11/12/1978,"1 Chemin du Tertre Pommier","92350","LE PLESSIS ROBINSON",,"06.99.91.95.01"
"MS","6701","M.","REGNAULT","Vincent",05/02/1969,"20, route des Ifs","76400","EPREVILLE",,"0609930474"
"MS","6675","MME","ROUSSELET","Elisabeth",01/09/2009,,,,,
"MS","7046","M.","SEROTTE","Jean-Marc",01/01/1979,"PK5, route d\'attila Cabassou","97300","CAYENNE",,"06 94 23 79 46"
"MS","6835",,"SIMIC","Isabelle",25/02/1974,"24, rue Jean Mermoz","78620","L\'ETANG LA VILLE",,"06.80.37.83.77"
"MS","7045","M.","SITHAPATHI","Narayankumar",15/08/1975,"70, rue de Bagnolet","75020","PARIS",,
"MS","6691","MME","THIBAULT","Catherine",29/07/1964,"35, rue Saint Paul","75004","PARIS",,"0620012568"
"MS","6690","MME","THONAT","Carole",06/02/1975,"6, rue Michel de Bourges","75020","PARIS",,"0685029202"
"MS","6763","MME","TIBAH","Anissa",11/02/1972,"37, rue de Pissefontaine","78540","CHANTELOUP",,"06.89.46.28.07"
"MS","6760","MLLE","VALLY","Michèle",10/04/1958,"16, rue du Dr Decorse","94410","ST MAURICE",,"06.11.41.10.33"
"MS","7044","M.","VICENTE","Laurent",24/11/1971,"6, Impasse Georges Pompidou","31140","SAINT ALBAN","05 61 74 40 12",
"MS","06021","M.","WANG","Yikai",31/05/1986,"20 rue des Tanneries","75013","PARIS",,
"MS","6683",,"YEWAWA","Claude-Marius",01/09/2009,,,,,
"P1","6802","M.","ABOU CHAKRA","Dany",12/11/1992,"21 rue Jules Ferry","92400","COURBEVOIE","01 46 67 96 23","06 64 77 58 19"
"P1","6947","M.","ABTOUT","Samy",01/06/1991,"17 rue Caillaux","75013","PARIS","0153611199","0659764892"
"P1","6818","MLLE","BASTARD","Marie-Céline",01/01/2010,"3 rue La Pérouse - Résidence George V","78150","LE CHESNAY","0139554127","0683584519"
"P1","6780","M.","BEHAREL","Matthieu",25/08/1992,"76 rue du Docteur Le Savoureux","92290","CHATENAY MALABRY","0146608759","0678156403"
"P1","6890","M.","BELLITY","Mathieu",08/10/1991,"15 rue de la Rochefoucauld","92100","BOULOGNE BILLANCOURT",,"06 66 44 53 88"
"P1","6902","M.","BENNEOUALA","Yanis",05/06/1991,"8 rue Notre-Dame des Champs","75006","PARIS",,
"P1","6781","MLLE","BENOIT","Eva",29/12/1992,"1 rue Gervex","75017","PARIS","0143800982","0661511095"
"P1","6863","M.","BERGER","Thibault",01/03/1992,"Appartement 104 - 2 bd Saint Denis","92400","COURBEVOIE",,
"P1","6929","MLLE","BERTRAND","Ariane",14/02/1993,"16 bis rue des Combattants","92370","CHAVILLE","0141155701","0642369495"
"P1","6923","MLLE","BESAGNI","Jessica",01/09/1992,"14 rue de Champigny","94430","CHENNEVIERES SUR MARNE","0145760016","0685550547"
"P1","6893","MLLE","BONTEMPS","Marie-Thérèse",26/06/1992,"29 rue du Champart","78700","CONFLANS-STE-HONORINE",,
"P1","6859","M.","BOULBEN","Emilien",02/07/1991,"87 avenue René Panhard","94320","THIAIS","0148534556","0660939810"
"P1","6815","M.","BOUTINON","Hugo",23/09/1992,"33 Avenue du général leclerc - Saint sulpice","92130","ISSY LES MOULINEAUX",,
"P1","6869","M.","BURBAN","Paul",08/04/1992,"2 rue Lemoine","92100","BOULOGNE BILLANCOURT","0146058117","06 35 13 81 58"
"P1","6937","M.","CALIXTE","Julien",25/07/1992,"72 Rue Claude Decaen Esc A Appt 22","75012","PARIS 12","0175573274","0634175682"
"P1","6832","M.","CARLANDER","Laurent",21/09/1992,"40 rue Marius Aufan","92300","LEVALLOIS PERRET","0147590453","0664893881"
"P1","6932","M.","CHARBONNELLE","Luc",30/07/1992,"12 rue du Bocage","91400","ORSAY","01 60 10 94 04","06 33 21 32 89"
"P1","6803","M.","CHEGARAY","Baptiste",01/01/2010,"8 rue des Abesses","75018","PARIS",,
"P1","6864","M.","CHEVILLOTTE","Florent",30/05/1992,"33 Avenue du Général Leclerc","92130","ISSY LES MOULINEAUX",,"0669973799"
"P1","6804","M.","CHHOR","Steven",30/09/1991,"17 allée Alphonse Daudet","94800","VILLEJUIF","0146785712","0623688037"
"P1","6926","M.","CORDELLE","Louis",29/10/1992,"4 avenue Foch","92420","VAUCRESSON","0147015714","0699306352"
"P1","6924","MLLE","CROAS","Valentine",12/07/1993,"40 avenue Blanche de Castille","78300","POISSY","0130741668","0672206679"
"P1","6821","M.","CUINAT","Pierre",19/09/1992,"38 rue Montaigne","92800","PUTEAUX",,
"P1","6878","M.","DAHER","Saïd",22/01/1992,"33 rue du general leclerc seminaire st sulpice","92130","ISSY LES MOULINEAUX",,"0625488162"
"P1","6865","M.","DANIEL","Nicolas",25/05/1992,"7 villa Honoré Gabriel Riqueti","75015","PARIS","0145756716","0676277751"
"P1","6905","M.","DELEPINE","Victor",26/08/1993,"10 rue des Grandes Terres","92500","RUEIL MALMAISON","0147515258","0631935438"
"P1","6819","M.","DELON","Jean-Baptiste",21/01/1992,"8 allée des Sycomores","92700","COLOMBES","0147699116","0610012593"
"P1","6938","M.","DESCHAMPS","Colin",28/06/1992,"93 rue de Chateaubriand","78180","MONTIGNY-LE-BRETONNEUX",,
"P1","6795","M.","DESCHAMPS","Mikaël",01/01/2010,"3 avenue Joséphine","92500","RUEIL MALMAISON",,
"P1","6816","M.","DESCHATEAUX","Alexandre",12/09/1992,"9 rue de Montaigu","78240","CHAMBOURCY","0146621995","0623841383"
"P1","6789","M.","DHIVER","Aurélien",10/12/1992,"395 rue de Vaugirard","75015","PARIS",,
"P1","6866","M.","DRONIOU","Mickaël",22/07/1992,"23 rue du Bel Air","77500","CHELLES","0170043885","0689364299"
"P1","6879","MLLE","DUMONT","Ayako",09/12/1992,"28 rue Piat","75020","PARIS 20",,"0640123131"
"P1","6786","M.","ESSELIN","Vincent",21/09/1993,"23 avenue des Quatre Chemins","92330","SCEAUX",,
"P1","6853","M.","EXERTIER","Eliott",14/12/1992,"12 rue du Cottage","92410","VILLE D\'AVRAY","0147099760","0689763910"
"P1","6817","M.","FANNIERE","Arnaud",20/09/1992,"55 rue du Vieil Orme","78120","RAMBOUILLET",,"0689673683"
"P1","6841","M.","FARDON","Shady",10/04/1992,"01 BP 3678",,"ABIDJAN",,
"P1","6858","M.","FERRANDINI","Thomas",01/04/1992,"4 villa du Bois des Folies","91080","COURCOURONNES","0160869410","0668106870"
"P1","6898","MLLE","FLAURAUD","Capucine",14/05/1992,"1 clos de la Salle","78600","LE MESNIL LE ROI",,
"P1","6933","M.","FLAUW","Jérôme",05/11/1992,"24 rue jouffroy d\'abbans","75017","PARIS 17","0153170243","0669423890"
"P1","6843","M.","GENISSEL","Paolo",16/04/1992,"22 rue Michelet","78500","SARTROUVILLE","0139130071","0659353560"
"P1","6917","M.","GOMBAUD-SAINTONGE","Alexandre",03/07/1992,"95 avenue du Général Leclerc","75014","PARIS 14","0145416465","0668369141"
"P1","6946","MLLE","GORWOOD","Audrey",02/07/1992,"30 rue Raymond Marcheron","92170","VANVES","0141088515","0670927973"
"P1","6939","M.","GUILLAUD","Augustin",21/10/1992,"490 chemin de Grivoton","13590","MEYREUIL",,
"P1","6903","M.","GUITOGER","Florian",19/08/1992,"2 rue de Montcient","95500","GONESSE","01 39 87 40 12","06 69 12 46 47"
"P1","6870","MLLE","HENG","Christelle",26/08/1992,"51 rue Pierre Marcel","94250","GENTILLY",,"0637062804"
"P1","6909","M.","HENTGES","Mathieu",13/11/1992,"15 avenue de la Cristallerie","92310","SEVRES","01450679460","0625426655"
"P1","6872","MLLE","HOVINE","Clotilde",12/10/1992,"85 rue de Renne","75006","PARIS 06",,"0671944887"
"P1","6940","M.","HYPOLITE","Romain",04/09/1991,"9 allée Bernadotte","92330","SCEAUX","0140919507","0625850743"
"P1","6840","M.","JEZEQUEL","Kevin",03/04/1992,"29 rue des Vaussourds","92500","RUEIL MALMAISON",,
"P1","6894","M.","JOUBERT","Alexandre",24/11/1992,"8 allée Claude Debussy","95130","LE PLESSIS BOUCHARD",,
"P1","6910","M.","JOURNEL","Tristan",11/09/1992,"171 rue Diderot","94300","VINCENNES",,
"P1","6867","M.","JUMEAU","Jérémy",07/12/1992,"49 rue du Lt Cel de Montbrison","92500","RUEIL MALMAISON",,
"P1","6833","MLLE","KEREDINE","Soraya",15/10/1991,"25 rue de Penthièvre","75008","PARIS 08",,"06 78 95 92 98"
"P1","6871","MLLE","KOEHL","Marianne",04/03/1993,"97 avenue du Général de Gaulle","91550","PARAY VIEILLE POSTE","0169847813","0698512696"
"P1","6941","M.","LE CHATELIER","Raphaël",19/03/1992,"37 rue du Docteur Soubise","92260","FONTENAY AUX ROSES","0146839646","0674200947"
"P1","6787","M.","LEFEVRE","Clément",22/04/1992,"9 rue Jobbé Duval","75015","PARIS","0145314978","0686732683"
"P1","6922","M.","LEFORT","Thomas",11/04/1992,"10 square Delambre","75014","PARIS","01 43 22 56 64","06 61 55 13 52"
"P1","6842","M.","LEGER","Raphaël",15/11/1992,"11 rue du Château","95320","SAINT LEU LA FORET",,
"P1","6950","M.","LEGRAND","Guillaume",03/07/1991,"5 avenue de l\'Ile de France","92160","ANTONY","0146608705","0630534395"
"P1","6920","M.","LESAGE","Pierre-Louis",22/12/1992,"13 rue Verlaine","54000","NANCY",,"0631105994"
"P1","6931","M.","LOPEZ","Lucien",28/02/1992,"21 rue Branly","92700","COLOMBES","0147843534","0614745458"
"P1","6906","MLLE","MAHIOU","Meriem",21/12/1991,"MAITRE ALBERT 13","75005","PARIS","0177100097","0669238308"
"P1","6820","M.","MARTIN","Alexis",21/05/1992,"23 rue du Lavoir","95410","GROSLAY","0134177374","0669331681"
"P1","6928","M.","MATHIEX-FORTUNET","Paul",24/11/1992,"1 rue Beethoven","75016","PARIS","0145031809","0689154974"
"P1","6912","M.","MAUREL","Pierre",17/05/1991,"6 villa des Chambards","92270","BOIS COLOMBES","01 47 80 79 56","06  98 09 49 12"
"P1","6790","MLLE","MESKO","Julie",26/01/1993,"16 rue Jean-Claude Arnoud","75014","PARIS 14",,"0659004026"
"P1","6904","M.","MICHALON","Fabien",29/04/1992,"80 rue de Bellevue","92700","COLOMBES","01 41 19 98 50","06 69 38 44 84"
"P1","6952","M.","MONROCHE","John",25/12/1991,"67 rue Claude Bernard","75005","PARIS",,
"P1","6943","M.","MONTANARI","Pierre-Loris",15/12/1992,"75 rue Michel Ange","75016","PARIS",,
"P1","6857","M.","MORERE","Nicolas",20/07/1992,"33 RUE DU GENERAL LECLERX","92130","ISSY LES MOULINEAUX",,"0678651422"
"P1","6861","M.","MOUVILLIAT","Jean-Emmanuel",23/01/1990,"19 rue Jean Leclaire","75017","PARIS","0142264013","0677141394"
"P1","6830","M.","MULSANT","Pierre",24/04/1992,"11 rue George Bernard Shaw","75015","PARIS",,"06 72 64 41 06"
"P1","6782","M.","NAHED","Yann",07/09/1992,"28 allée du Hameau Fleuri","78590","NOISY LE ROI",,
"P1","6783","M.","ONG","You-Long",28/02/1992,"35 rue du Bois des Antonins","91800","BOUSSY ST ANTOINE","0169002969","0614340052"
"P1","6831","M.","PAJOT","Adrien",04/12/1992,"16 rue du Loup Pendu","91570","BIEVRES",,"0627794743"
"P1","6900","M.","PAPON","Vincent",01/12/1992,"27 rue Ledion","75014","PARIS",,"06 58 66 35 89"
"P1","6911","M.","PERRIN","Thomas",16/04/1992,"15 allée de la Romance","95800","CERGY","0134320007","0608529027"
"P1","6899","M.","PESCHEL","Alexandre",20/04/1991,"2 Rue Ernest Tissot","92210","SAINT-CLOUD",,"06 82 22 79 46"
"P1","6791","M.","PETTMANN","Thomas",14/02/1992,"29 rue d\'Alèsia","75014","PARIS 14",,"0607355763"
"P1","6913","M.","PEUVREL","Corentin",05/05/1992,"16 rue Jean Claude Arnould","75014","PARIS 14",,"0614975341"
"P1","6854","M.","POIRSON","Baudouin",18/09/1992,"66 rue Albert Joly","78000","VERSAILLES","0139020530","0684864333"
"P1","6877","M.","RAJOT","Jonathan",09/10/1992,"28 rue Paul Bert","92370","CHAVILLE","0147096197","0680753473"
"P1","6930","M.","RECOURSE","Quentin",13/01/1992,"24C rue de Presles","75015","PARIS 15","0142199675","0677674027"
"P1","6856","M.","RIVOAL","Xavier",28/06/1992,"176 avenue Jean Jaurès","92140","CLAMART",,"0684510934"
"P1","6918","M.","ROBBE","Marc-Alexandre",07/07/1992,"78 boulevard Exelmans","75016","PARIS","09 54 93 16 68","06 37 72 04 39"
"P1","6927","M.","ROBERT","Franck",16/10/1992,"31 rue Emeriau","75015","PARIS",,
"P1","6901","M.","ROGER","Nicolas",17/05/1991,"18 rue des Quatre Vents","75006","PARIS","09 51 37 39 00","06 74 10 50 80"
"P1","6809","MLLE","ROLLAND","Audrey",09/08/1992,"3 avenue du 11 novembre 1918","92190","MEUDON","0145340334","0670155627"
"P1","6868","M.","SAVARIT","Adrien",16/11/1992,"26 rue Jacques Dulud","92200","NEUILLY SUR SEINE",,"0629717245"
"P1","6919","M.","SCEMAMA","Dan",08/05/1991,"4 rue isabey","75016","PARIS 16",,"06 85 52 28 33"
"P1","6862","M.","SEBBAN","Franck",19/02/1992,"80 boulevard Flandrin","75016","PARIS","0147042973","0678292766"
"P1","6855","M.","SECHERESSE","Clément",16/11/1992,"2 rue Pierre Poli","92130","ISSY LES MOULINEAUX","01 46 62 93 04","06 37 03 77 76"
"P1","6916","M.","TATINCLAUX","Kévin",09/03/1992,"79 rue du Docteur Vaillant","93160","NOISY-LE-GRAND",,
"P1","6948","M.","THOMAS","Aimery",23/06/1993,"25 rue de Roussigny","91470","LES MOLIERES",,
"P1","6944","M.","TISSINIE","Pierre",22/11/1992,"21 rue Chevalier","95160","MONTMORENCY","0134122978","0681262862"
"P1","6891","MLLE","TOURON","Claire",25/06/1992,"15 rue Bartholdi","92100","BOULOGNE BILLANCOURT","0170195066","0629712782"
"P1","6794","M.","TRAGNEE","William",11/12/1992,"14 Rue raymond Croland","92260","FONTENAY AUX ROSES","0981004241","0617928502"
"P1","6788","M.","TRUPEL","Sébastien",15/04/1992,"4 allée Diderot","92160","ANTONY","01 56 45 07 34","06 15 34 07 73"
"P1","6892","M.","UZAN","Michael",01/09/1992,"118 boulevard Malesherbes","75017","PARIS","01 40 16 44 03","06 71 53 28 47"
"P1","6852","M.","VALLEE","Eliot",30/09/1992,"102 rue Truffaut","75017","PARIS",,"06 17 57 04 83"
"P1","6810","M.","VERCIER","Hadrien",16/09/1992,"60 rue Dombasle","75015","PARIS",,
"P1","6942","M.","VU","Jean-François",28/08/1991,"13 allée du Bois Moreau","94440","VILLECRESNES","0145954295","0614516602"
"P1","6784","M.","WANG","Christian",16/09/1991,"22 rue Mendes des Carmes","93000","BOBIGNY","0148323352","0609212486"
"P1","6785","M.","ZHANG","Thierry",11/03/1992,"10 rue Alfred Sisley","93420","VILLEPINTE","0148616769",
"P1-DOUBL","6560","M.","HANTZEN","Kevin",10/07/1991,"19 rue Bridaine","75017","PARIS","01 72 60 78 42","06 28 67 74 69"
"P1-DOUBL","6509","MLLE","MARICHAL","Amélie",18/08/1991,"105 rue Rouget de Lisle","92150","SURESNES","01 47 28 41 82",
"P1-DOUBL","6485","M.","RENAUD","Octavio",02/10/1991,"59 rue du Moulin Vert","75014","PARIS","01 45 39 71 95","06 68 28 46 81"
"P1-DOUBL","6451","M.","SANFINS","Cyril",23/06/1991,"72 rue de la Colonie","75013","PARIS","09 50 88 75 13","06 35 50 46 79"
"P2","6546","M.","ABOU FAISSAL","Sébastien",26/10/1991,"26 rue des Acquevilles","92150","SURESNES",,
"P2","6476","M.","BARDA","Emre",23/04/1991,"62 rue de Bellevue","92100","BOULOGNE BILLANCOURT",,"0675794900"
"P2","6827","MLLE","BATTAGLINI","Marie-Caroline",28/07/1991,"29 boulevard du Roi","78000","VERSAILLES",,"06 72 24 90 44"
"P2","6484","MLLE","BELETEAU","Louise",21/02/1991,"12 cité Dupetit Thouars","75003","PARIS","09 71 40 91 42","06 13 70 48 77"
"P2","6538","M.","BERRADA","Mohamed",05/04/1991,"38 avenue Bugeaud","75016","PARIS",,"06 58 69 87 01"
"P2","6061","M.","BERTRAN DE BALANDA","Augustin",27/01/1991,"36 avenue Théophile Gauthier","75016","PARIS","02 40 89 54 48",
"P2","6528","M.","BONTE","Thomas",18/10/1991,"38 avenue de l\'Abreuvoir","78170","LA CELLE ST CLOUD","0139583584","0659012438"
"P2","6063","M.","BORDELAIS","Virgile",08/10/1990,"22 rue Desbordes-Valmore","75116","PARIS",,"06 65 72 68 40"
"P2","6524","M.","BORTENLANGER","Sébastien",22/11/1991,"5 avenue Petit Gout","92700","COLOMBES","01 47 84 35 17","06 71 33 49 16"
"P2","6492","M.","BRASSARD","Alexandre",27/01/1990,"10 allée des Troenes","91470","FORGES LES BAINS","01 64 91 20 43","06 19 65 20 94"
"P2","6553","M.","BRAY","Gaëtan",07/06/1991,"8 rue Paul Doumer","78140","VELIZY VILLACOUBLAY","01 34 65 34 62","06 37 96 88 11"
"P2","6549","M.","BRUNEAU","Antonin",08/04/1991,"7 bis rue de Chaâlis","77400","THORIGNY SUR MARNE","01 60 07 58 22","06 32 32 73 20"
"P2","6065","MLLE","CAMALACANNANE","Sandhyni",19/09/1990,"36 rue Roland Toutain","95100","ARGENTEUIL",,"06 62 9 55 37"
"P2","6478","M.","CASADEPAX-SOULET","Jean-Baptiste",16/07/1991,"3 rue Larrey","75005","PARIS",,"06 76 26 78 76"
"P2","6505","M.","CHARBONNIER","Alexandre",14/05/1991,"33 rue Victor Hugo","93500","PANTIN",,
"P2","6516","M.","CHARRON","Vincent",12/02/1991,"5 rue Maryse Hilsz","92300","LEVALLOIS PERRET","0147392072","0672921808"
"P2","6453","M.","CHEVALIER","Florent",22/02/1991,"56 rue Cambronne","75015","PARIS","01 56 58 29 30","06 13 69 37 85"
"P2","6477","M.","CONESSA","Mickaël",21/01/1991,"17 avenue Division Leclerc","92160","ANTONY","01 46 66 11 78","06 63 25 73 13"
"P2","6544","MLLE","CORNET","Manon",15/02/1991,"4 rue Steffen","92600","ASNIERES SUR SEINE","01 43 33 26 33","06 82 96 25 56"
"P2","6539","MLLE","COUTILLARD","Charlotte",08/08/1991,"4 avenue du Général Leclerc","75014","PARIS","0174647847","06 67 09 95 75"
"P2","6450","MLLE","CROIXMARIE","Fanny",24/08/1991,"68 boulevard Auguste Blanqui","75013","PARIS","01 43 37 10 69","06 63 39 12 74"
"P2","6448","MLLE","DACCORSO","Alexandra",14/11/1991,"28 rue de la Sablière","75014","PARIS",,"06 73 52 68 97"
"P2","6491","M.","DE BOISSET","Pierre",09/12/1991,"33 rue du Général Leclerc","92130","ISSY LES MOULINEAUX",,
"P2","6468","M.","DE DROUAS","Cyril",14/09/1991,"1 rue Jean Marie Duclos","69005","LYON",,
"P2","6559","M.","DECARPENTRY","Edouard",21/05/1991,"21 bis rue Louis Pasteur","92100","BOULOGNE BILLANCOURT","01 46 84 00 80","06 74 37 50 26"
"P2","6521","M.","DEGIOVANNI","Jean-Baptiste",17/03/1991,"14 rue James Linard","78220","VIROFLAY","01 30 24 30 33","06 62 28 39 94"
"P2","6473","M.","DELMER","Jonathan",23/01/1991,"34 rue de la Campagnarde","91120","PALAISEAU","01 69 31 11 39","06 38 69 63 19"
"P2","6432","M.","DEMOLINS","Arnaud",13/11/1991,"12 rue Laval","92210","SAINT-CLOUD",,
"P2","6466","M.","DESSAIGNES","Benoît",09/11/1990,"12 bld André Malraux","78480","VERNEUIL-SUR-SEINE","01 39 71 68 94","06 66 18 24 43"
"P2","6495","M.","DIGONNET","William",31/10/1991,"31 rue Montagne de la Fage","75015","PARIS","01 72 34 66 23","06 65 27 20 48"
"P2","6527","M.","DIOT","Vincent",10/03/1990,"145 avenue André Maginot","94400","VITRY SUR SEINE","01 47 18 09 41","06 83 94 05 06"
"P2","6512","M.","DULAC ROORYCK","Pierre-Henri",31/07/1991,"33 rue du Général Leclerc","92130","ISSY LES MOULINEAUX",,"0686942252"
"P2","6079","MLLE","FARES","Pamela",05/05/1990,"21 boulevard de Grenelle","75015","PARIS","01 72 38 17 54","06 83 67 71 08"
"P2","6540","MLLE","FERNANDES DE SOUSA","Sophie",27/04/1991,"3 avenue des Cerisiers","91200","ATHIS MONS","01 69 84 77 98","06 73 84 65 57"
"P2","6547","M.","GARNIER","Alexandre",01/07/1991,"30 bis rue de la Grande Fontaire","78100","ST GERMAIN EN LAYE","01 39 73 67 51","06 23 35 94 27"
"P2","6510","M.","GAULT","Léopold",27/08/1990,"20 rue Auguste Perret","75013","PARIS","01 75 50 65 96","06 74 27 82 05"
"P2","6508","M.","GESLIN","Baptiste",31/12/1991,"5 rue Charles d\'Orléans","91540","MENNECY","01 64 57 37 44","06 18 32 40 12"
"P2","6514","M.","GLAIZOT-KARUEL DE MEREY","Jean-Laurent",08/03/1991,"4 rue Fabre d\'Eglantine","75012","PARIS","01 44 74 60 46","06 76 21 49 09"
"P2","6554","M.","GOMES COUTINHO","David",21/05/1991,"228 rue de Courcelles","75017","PARIS","01 46 22 26 39","06 58 16 93 29"
"P2","6507","M.","GUERIN","Florian",21/03/1991,"5 allée du Cocher","91370","VERRIERES LE BUISSON","01 69 30 34 90","06 76 12 46 85"
"P2","6662","M.","HAENTJENS","Nils",09/07/1991,"29 avenue Sainte Lucie","92600","ASNIERES","01 40 80 07 61","06 18 35 41 00"
"P2","6556","M.","HAGHGOU","Pejman",25/08/1992,"225 avenue du Bois de Verrières","92160","ANTONY","01 46 83 41 61","06 13 47 22 71"
"P2","6555","M.","HERBY","Mathias",08/01/1990,"20 rue de Neuilly","92110","CLICHY","01 40 87 02 93","06 59 28 08 03"
"P2","6517","M.","ISNARD","Charles",19/04/1991,"7 rue Gaston Lebeau","94320","THIAIS","01 48 84 73 30","06 43 86 99 19"
"P2","6545","M.","KERRAND","Wenceslas",28/02/1991,"16 rue Cortambert","75116","PARIS","01 45 04 68 08","06 61 72 87 86"
"P2","6488","M.","LACROIX","Guillaume",30/12/1991,"12 allée Corot","78170","LA CELLE ST CLOUD","01 78 64 48 12","06 72 43 67 35"
"P2","6095","M.","LAURENCON","Adrien",30/05/1990,"80 boulevard Rodin","92130","ISSY LES MOULINEAUX","01 47 36 17 32","06 73 31 93 53"
"P2","6537","M.","LE DUC","Romain",02/12/1991,"11 rue Mignard","75116","PARIS","01 45 03 12 90","06 71 35 61 19"
"P2","6541","M.","LE TEXIER","Pierre",07/04/1991,"25 bis rue Kilford","92400","COURBEVOIE","01 47 88 09 38","06 25 35 69 29"
"P2","6355","M.","LEGROS","Adrien",12/07/1990,"Lycée Collège St Augustin, Résidence ISEN ISEP, 56 rue Jean Jacques Kieffer","57230","BITCHE",,
"P2","6472","M.","LEROY","Quentin",10/12/1990,"62 rue des Gabillons","78290","CROISSY SUR SEINE","01 39 76 78 06","06 98 56 86 84"
"P2","6534","M.","LESAGE","Geoffroy",25/04/1991,"11 rue Foury","92310","SEVRES","01 45 07 93 65","06 60 22 89 13"
"P2","6475","M.","LOAS","Alexandre",25/08/1991,"49 avenue Auguste Renoir","78160","MARLY LE ROI","01 39 58 28 82","06 72 08 40 74"
"P2","6435","M.","MARCK","Alexandre",29/03/1991,"29 rue Jean Bart","92400","COURBEVOIE","01 47 89 41 09","06 80 41 53 52"
"P2","6498","M.","MARTINETTO","Antoine",19/06/1990,"2 square Mozart","75016","PARIS","01 42 24 95 61","06 21 64 02 86"
"P2","6479","M.","MONNET","Félix",12/07/1991,"25 rue Georges Appay","92150","SURESNES","01 41 18 90 41","06 59 78 03 86"
"P2","6552","M.","MOREL","Aliaume",30/04/1991,"23 rue du Plateau","91430","IGNY","01 69 85 55 77","06 86 61 80 68"
"P2","6664","M.","MUREZ","Nicolas",11/05/1991,"18 allée de Chartres","91370","VERRIERES LE BUISSON","01 64 47 09 98","06 32 77 46 64"
"P2","6102","MLLE","NICODEME","Claire",17/09/1990,"2 rue Louis Blanc","92190","MEUDON","01 46 23 10 33","06 88 29 68 80"
"P2","6480","MLLE","NUNES DE ALMEIDA","Andréa",27/03/1991,"6 rue de la Fraternité","92130","ISSY LES MOULINEAUX","01 46 38 73 59","06 82 64 27 98"
"P2","6551","M.","PARIS","Maximilian",19/08/1991,"62 rue de Ponthieu","75008","PARIS",,"06 59 15 60 30"
"P2","6548","M.","PAUL-DAUPHIN","François",16/06/1991,"34 rue de la Pompe","75116","PARIS","01 45 03 49 54","06 21 91 86 55"
"P2","6550","M.","PAULUS","Romain",15/10/1992,"33 rue Général Leclerc","92130","ISSY LES MOULINEAUX",,
"P2","6536","M.","PEREZ","Etienne",21/01/1991,"4 rue Jeanne","92140","CLAMART","01 46 45 19 55","06 18 09 10 09"
"P2","6434","M.","PESTEL","Timothée",29/05/1991,"7 avenue  Le Corbeiller","92190","MEUDON","01 46 26 58 92","06 59 69 74 12"
"P2","6515","M.","PIALOT","Baptiste",19/03/1991,"1 quater avenue Lesage","78600","MAISONS-LAFFITTE","01 39 62 40 49","06 22 30 49 50"
"P2","6496","MLLE","PINETTE","Claire",24/10/1991,"35 rue St Pétersbourg","75008","PARIS","09 52 71 66 86","06 03 27 02 68"
"P2","6499","M.","PREVAL","Enguerrand",21/08/1991,"6 rue de Maubeuge","75009","PARIS","01 45 26 59 53","06 72 14 58 97"
"P2","6111","MLLE","PRUDHOMME","Margaux",17/09/1990,"5 rue Rigaud","92200","NEUILLY SUR SEINE",,"06 63 36 52 19"
"P2","6522","M.","PUHARRE","Thomas",14/07/1991,"19 passage Marquis de la Londe","78000","VERSAILLES","01 39 50 14 27","06 75 84 83 90"
"P2","6449","M.","QUISPE","Guillaume",01/03/1991,"32 bis boulevard du Roi","78000","VERSAILLES","01 30 21 30 02","06 16 75 47 37"
"P2","6542","M.","RICHARD","Benjamin",11/06/1991,"16 rue du Moulin de Pierre","92130","ISSY LES MOULINEAUX","01 46 44 56 21","06 24 81 49 42"
"P2","6506","M.","RIEUBLANC","Tony",17/08/1990,"37 boulevard d\'Inkermann","92200","NEUILLY-SUR-SEINE","01 47 38 23  71","06 29 80 83 68"
"P2","6433","M.","RIOULT DE NEUVILLE","Grégoire",20/04/1991,"87 rue de Monceau","75008","PARIS","01 42 93 39 10","06 29 84 22 02"
"P2","6543","MLLE","ROMAIN","Caroline",30/04/1992,"10 rue Edmond Valentin","75007","PARIS","01 47 05 20 28","06 60 38 63 63"
"P2","6526","MLLE","ROUX-DESSARPS DE SEZE","Zoé",21/05/1991,"187 boulevard Murat","75016","PARIS","01 71 20 29 49","06 72 32 92 97"
"P2","6518","MLLE","ROUZE","Charlotte",26/01/1991,"19 rue Danielle Casanova","92500","RUEIL MALMAISON","01 47 14 92 91","06 71 15 48 13"
"P2","6114","M.","SAVATIER","Marc",21/10/1990,"33 rue du Général Leclerc","92130","ISSY LES MOULINEAUX",,"06 42 33 71 25"
"P2","6520","M.","SCHERRER","Wandrille",11/05/1991,"37 rue Jean Mermoz","92380","GARCHES",,
"P2","6481","M.","SHIHATA","Fadi",08/06/1991,"18 rue Crépré","75015","PARIS","01 45 67 11 48","0642221780"
"P2","6489","M.","SIMON","Anthony",14/02/1991,"178 boulevard Berthier","75017","PARIS",,"06 26 79 21 89"
"P2","6483","M.","SIMON","Yoann",17/10/1991,"41 impasse Villaret Joyeuse","78800","HOUILLES","01 39 14 40 65","06 80 25 58 44"
"P2","6525","M.","VAN YEN","Edouard",26/08/1991,"78 rue de Buzenval","92210","SAINT-CLOUD","01 49 11 12 95","06 74 88 28 44"
"P2","6124","MLLE","VIGNAUD","Anne-Clémence",15/01/1991,"12 rue Hoche","92240","MALAKOFF","01 49 65 01 22","06 21 43 14 75"
"P2","6482","MLLE","WETS","Anne-Sophie",04/06/1991,"32 avenue Georges Clémenceau","92330","SCEAUX","01 46 60 78 31","06 98 00 75 19"
"P2","6458","MLLE","ZANOLLI","Géraldine",02/10/1991,"7 rue d\'alençon","75015","PARIS 15","01 47 34 47 17","06 66 89 27 78"
"P2","6511","MLLE","ZHAO","Sonia",02/08/1991,"31 bis boulevard Saint Martin","75003","PARIS","01 42 78 70 91","06 46 74 23 94"
"P2-DOUBL","6070","M.","DE SAINT LAON","Foulques",24/10/1989,"203 rue de Vaugirard","75015","PARIS","01 43 06 57 16","06 69 01 39 74"
"P2-DOUBL","6309","M.","DE SEZE","Armand",05/05/1990,"207 rue de Vaugirard","75015","PARIS","01 43 06 01 87",
"P2-DOUBL","6072","M.","DOISON","Raphaël",29/06/1991,"12 villa des Nymphéas","75020","PARIS","01 40 30 47 26","06 37 11 63 78"
"P2-DOUBL","6211","M.","FRANGUIADAKIS","Jonathan",19/12/1989,"25 rue Marcel Sembat","93350","LE BOURGET","01 48 37 21 73","06 79 94 08 88"
"P2-DOUBL","6083","MLLE","GILLARD","Manon",11/03/1990,"6 clos de la Pommeraie","78750","MAREIL-MARLY","01 39 16 73 08","06 04 43 60 72"
"P2-DOUBL","6086","M.","HUDEC","Boris",30/08/1990,"96 rue Anatole france","92290","CHATENAY-MALABRY","01 46 60 23 16","06 31 38 78 56"
"P2-DOUBL","6099","M.","MICHEL","Guillaume",16/09/1990,"34 rue Jacques Doré","94430","CHENNEVIERES-SUR-MARNE","01 45 76 91 41","06 89 41 73 88"
"P2-DOUBL","6231","M.","ROZANC","Marc",17/10/1990,"28 rue Paul Doumer","78120","RAMBOUILLET","01 34 83 02 92","06 59 53 66 98"
"P2-DOUBL","6233","MLLE","SEFRAOUI","Mouna",20/08/1990,"85 bd Pasteur C314","75015","PARIS",,"06 22 34 95 98"
"P2-DOUBL","6122","M.","UNG","Edouard",08/02/1990,"26 rue des Plantes","75014","PARIS",,"06 03 25 38 21"
"P2-DOUBL","6236","M.","VIDAL","William",21/10/1990,"8 rue pierre Larousse","75014","PARIS 14","01 45 93 00 91","06 28 55 32 80"
';

	
	$list = explode("\n", trim($list));
	foreach($list as $list_){
		preg_match_all('#(""|".*(?<!")"|[^,"]+)(?:,|$)#U', trim($list_), $matches);
		$matches = $matches[1];
		foreach($matches as &$match){
			if($match[0] == '"')
				$match = substr($match, 1, -1);
		}
		$student_number = (int) $matches[1];
		$promo = $matches[0];
		$lastname = $matches[3];
		$firstname = $matches[4];
		$birthday = $matches[5];
		$address = $matches[6];
		$zipcode = $matches[7];
		$city = ucwords(strtolower($matches[8]));
		$phone = $matches[9];
		$cellphone = $matches[10];
		
		$lastname = ucwords(str_replace('-', ' ', strtolower($lastname)));
		$firstname = str_replace(' ', '-', ucwords(str_replace('-', ' ', $firstname)));
		if(preg_match('#^([0-9]{2})/([0-9]{2})/([0-9]{4})$#', $birthday, $birthday))
			$birthday = $birthday[3].'-'.$birthday[2].'-'.$birthday[1];
		else
			$birthday = null;
		
		if(strlen($phone) == 10)
			$phone = $phone[0].$phone[1].' '.$phone[2].$phone[3].' '.$phone[4].$phone[5].' '.$phone[6].$phone[7].' '.$phone[8].$phone[9];
		if(strlen($cellphone) == 10)
			$cellphone = $cellphone[0].$cellphone[1].' '.$cellphone[2].$cellphone[3].' '.$cellphone[4].$cellphone[5].' '.$cellphone[6].$cellphone[7].' '.$cellphone[8].$cellphone[9];
		
		//echo 'n°'.$student_number.' - '.$firstname.' '.$lastname.' - né le '.$birthday.' - '.$address.', '.$zipcode.', '.$city.' - '.$phone.' - '.$cellphone."\n";
		//continue;
		
		$students = DB::createQuery('students')
			->fields('username', 'firstname', 'lastname')
			->where(array(
				'student_number' => $student_number
			))
			->select();
		try {
			if(!isset($students[0]))
				throw new Exception('Student n°'.$student_number.' ('.$firstname.' '.$lastname.') not found!');
			$student = $students[0];
			//if($student['firstname'] != $firstname && $student['lastname'] != $lastname)
			//	throw new Exception('Student n°'.$student_number.' : different names : '.$firstname.' '.$lastname.' != '.$student['firstname'].' '.$student['lastname']);
			
			if($promo == 'CESURE-A2-A3'){
				DB::createQuery('students')
					->set('cesure', 1)
					->where(array('student_number' => $student_number))
					->update();
			}
			
			$users = DB::select('
				SELECT 1
				FROM users
				WHERE username = '.DB::quote($student['username']).'
			');
			$db_query = DB::createQuery('users')
				->set(array(
					'address'		=> $address,
					'zipcode'		=> $zipcode,
					'city'			=> $city,
					'cellphone'		=> $cellphone,
					'phone'			=> $phone
				));
			if(isset($birthday))
				$db_query->set('birthday', $birthday);
			if(!isset($users[0])){
				echo 'Creating user "'.$student['username'].'"'."\n";
				$db_query->set(array('username' => $student['username']))->insert();
			}else{
				$db_query->where(array('username' => $student['username']))->update();
			}
		}catch(Exception $e){
			echo $e->getMessage()."\n";
		}
	}
	
	
	
	/*
	$users = DB::select('
		SELECT o.login, o.mail_perso, o.msn_perso, o.adresse, o.portable, o.telephone, o.naissance
		FROM old_users o
		INNER JOIN students s ON s.username = o.login
	');
	
	foreach($users as $user){
		try {
			DB::createQuery('users')
				->set(array(
					'username'	=> $user['login'],
					'mail'		=> $user['mail_perso'],
					'msn'		=> $user['msn_perso'],
					'address'	=> $user['adresse'],
					'phone'		=> $user['portable']=='' ? $user['telephone'] : $user['portable'],
					'birthday'	=> $user['naissance']
				))
				->insert();
		}catch(Exception $e){
			echo $e->getMessage()."\n";
		}
	}
	*/
	
	
}catch(Exception $e){
	echo $e->getMessage();
}
