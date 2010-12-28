<?php
/**
 * Create the images of the directory's pages
 *
 * @example /usr/bin/php -f directory_paper.php
 */

define('CLI_MODE', true);
define('APP_DIR', realpath(dirname(__FILE__).'/../').'/');
define('CF_DIR', realpath(dirname(__FILE__).'/../../confeature/').'/');
define('DATA_DIR', realpath(dirname(__FILE__).'/../../data/').'/');

try{
	
	// Loading Confeature
	require_once CF_DIR.'init.php';
	
	$avatars_tmp_path = DATA_DIR.Config::DIR_DATA_TMP.'/avatars';
	$annuaire_tmp_path = DATA_DIR.Config::DIR_DATA_TMP.'/annuaire';
	if(!is_dir($avatars_tmp_path))
		throw new Exception($avatars_tmp_path.' not found!');
	if(!is_dir($annuaire_tmp_path))
		throw new Exception($annuaire_tmp_path.' not found!');
	
	$students = DB::select('
		SELECT s.student_number, s.firstname, s.lastname, s.promo, s.cesure, u.*
		FROM students s
		LEFT JOIN users u ON u.username = s.username
		WHERE promo IN ("2011", "2012", "2013", "2014", "2015")
		ORDER BY s.lastname ASC
	');
	
	
	
	
	
	// Emails
	/*
	require_once APP_DIR.'classes/swiftmailer/swift_required.php';
	foreach($students as $student){
		$have_photo = false;
		$original_path = $avatars_tmp_path.'/'.$student['student_number'].'.jpg';
		$photo_path = $annuaire_tmp_path.'/photos/'.$student['student_number'].'.jpg';
		if(file_exists($photo_path)){
			$have_photo = true;
		}else if(file_exists($original_path)){
			$img = new Image();
			$img->load($original_path);
			$img->thumb($photo_width, 400);
			$img->save($photo_path);
			unset($img);
			$have_photo = true;
		}
		
		try {
			$transport = Swift_SmtpTransport::newInstance('*****************', 25);
			$mailer = Swift_Mailer::newInstance($transport);
			
			$to = array($student['username'].'@isep.fr');
			if($student['mail'] != '')
				$to[] = $student['mail'];
			if($student['msn'] != '')
				$to[] = $student['msn'];
			
			$mail = Swift_Message::newInstance()
				->setFrom(array('****************' => 'Annuaire ISEP'))
				->setTo($to)
				->setReplyTo(array('*****************' => 'Annuaire ISEP'))
				->setSubject('Tes informations dans l\'annuaire de l\'ISEP')
				->setBody('Bonjour '.$student['firstname'].',

Je suis en train de préparer l\'annuaire papiers des élèves de l\'ISEP.'.(
	$have_photo ? '' : '
Il me manque ta photo ! Pourrais-tu m\'envoyer au plus vite une photo d\'identité de toi scannée ?'
).'
Voici les informations que j\'ai sur toi dans ma base de données :

- Prénom : '.$student['firstname'].'
- Nom : '.$student['lastname'].'
- Promo : '.$student['promo'].($student['cesure']=='1' ? ' (césure)' : '').'
- Numéro d\'étudiant : '.$student['student_number'].'
- Login ISEP : '.$student['username'].'
- Mail : '.$student['mail'].'
- MSN : '.$student['msn'].'
- Jabber (Gmail / Gtalk) : '.$student['jabber'].'
- Tél (mobile) : '.$student['cellphone'].'
- Tél (fixe) : '.$student['phone'].'
- Date de naissance : '.date('d/m/Y', strtotime($student['birthday'])).'
- Adresse : '.$student['address'].
	(isset($student['zipcode']) || $student['city']!=''
		?	', '.
			(isset($student['zipcode']) ? $student['zipcode'] : '').
			($student['city']!='' ? (isset($student['zipcode']) ? ' ' : '').$student['city'] : '')
		: ''
	).'

Si une de ces informations est manquante ou inexacte, peux-tu me les corriger par retour de mail stp ?'.(
	$have_photo ? '
Je te joins également la photo de toi qui sera affichée. Si ce n\'est pas ta photo (une erreur peut arriver...), merci de bien vouloir m\'envoyer une photo d\'identité au plus vite !' : ''
).'

Merci et à bientôt ;-)

--
Godefroy de Compreignac
BDE Aerodynamic
', 'text/plain');
			
			if($have_photo){
				$mail->attach(Swift_Attachment::fromPath($photo_path, 'image/jpeg'));
			}
			
			$mailer->send($mail);
			echo implode(', ', $to)." OK\n";
			ob_flush();
			
		}catch(Exception $e){
			echo $e->getMessage()."\n";
		}
	}
	exit;
	*/
	
	
	
	$promos = array();
	$birthdays = array();
	
	foreach($students as $student){
		try {
			//throw new Exception($student['promo'].' - '.$student['lastname']);
			
			if(!isset($promos[(int) $student['promo']]))
				$promos[(int) $student['promo']] = array();
			
			$promos[(int) $student['promo']][] = $student;
			
			if(isset($student['birthday']) && $student['birthday'] != '0000-00-00'){
				$birthdays[] = array(
					'birthday'	=> $student['birthday'],
					'date_sort'	=> (int) ('1'.substr($student['birthday'], 5, 2).substr($student['birthday'], 8, 2)),
					'name'	=> $student['firstname'].' '.$student['lastname']
				);
			}
			
		}catch(Exception $e){
			echo $e->getMessage()."\n";
		}

	}
	
	function birthdays_sort($a, $b){
		return $a['date_sort'] < $b['date_sort'] ? -1 : 1;
	}
	uasort($birthdays, 'birthdays_sort');
	
	$promos_names = array(
		2015	=> 'P1',
		2014	=> 'P2',
		2013	=> 'A1',
		2012	=> 'A2',
		2011	=> 'A3'
	);
	
	
	
	// http://www.php.net/manual/fr/function.imagettftext.php#81833
	define('ALIGN_LEFT', 'left');
	define('ALIGN_CENTER', 'center');
	define('ALIGN_RIGHT', 'right');
	function imagettftextbox(&$image, $size, $angle, $left, $top, $color, $font, $text, $max_width, $align=ALIGN_LEFT){
			$text_lines = explode("\n", $text); // Supports manual line breaks!
			
			$lines = array();
			$line_widths = array();
			
			$largest_line_height = 0;
			
			foreach($text_lines as $block){
				$current_line = ''; // Reset current line
				$words = explode(' ', $block); // Split the text into an array of single words
				$first_word = TRUE;
				$last_width = 0;
				
				for($i = 0; $i < count($words); $i++){
					$item = $words[$i];
					$dimensions = imagettfbbox($size, $angle, $font, $current_line . ($first_word ? '' : ' ') . $item);
					$line_width = $dimensions[2] - $dimensions[0];
					$line_height = $dimensions[1] - $dimensions[7];
					
					if($line_height > $largest_line_height) $largest_line_height = $line_height;
					
					if($line_width > $max_width && !$first_word){
						$lines[] = $current_line;
						
						$line_widths[] = $last_width ? $last_width : $line_width;
						
						/*if($i == count($words))
						{
							continue;
						}*/
						
						$current_line = $item;
					}else{
						$current_line .= ($first_word ? '' : ' ') . $item;
					}
					
					if($i == count($words) - 1){
						$lines[] = $current_line;
						$line_widths[] = $line_width;
					}
					
					$last_width = $line_width;
					$first_word = FALSE;
				}
				
				if($current_line)
					$current_line = $item;
			}
			
			$i = 0;
			foreach($lines as $line){
				if($align == ALIGN_CENTER)
					$left_offset = ($max_width - $line_widths[$i]) / 2;
				elseif($align == ALIGN_RIGHT)
					$left_offset = ($max_width - $line_widths[$i]);
				imagettftext($image, $size, $angle, $left + $left_offset, $top + $largest_line_height + ($largest_line_height * $i), $color, $font, $line);
				$i++;
			}
			
			return $largest_line_height * count($lines);
	}
	
	
	// Pages paires / impaires
	$page_odd = $annuaire_tmp_path.'/page_impaire.png';
	$page_even = $annuaire_tmp_path.'/page_paire.png';
	
	// Polices de caractères
	$font_daft = $annuaire_tmp_path.'/daft_font.ttf';
	$font_arista = $annuaire_tmp_path.'/arista.ttf';
	$font_arista_light = $annuaire_tmp_path.'/arista_light.ttf';
	$font_harabara = $annuaire_tmp_path.'/harabara.ttf';
	$space_age = $annuaire_tmp_path.'/space_age.ttf';
	
	// Icônes
	$icon_mail = imagecreatefrompng($annuaire_tmp_path.'/email.png');
	$icon_phone = imagecreatefrompng($annuaire_tmp_path.'/phone.png');
	$icon_cellphone = imagecreatefrompng($annuaire_tmp_path.'/cellphone3.png');
	$icon_address = imagecreatefrompng($annuaire_tmp_path.'/address2.png');
	$icon_birthday = imagecreatefrompng($annuaire_tmp_path.'/birthday.png');
	
	
	
	
	// Répertoire
	/*
	$students_per_page = 45;
	$base_x = 220;
	$base_y = 360;
	
	$starting_page = 1;
	
	$page_n = $starting_page;
	$even_odd = $page_n%2;
	$i = 0;
	$letter = '';
	
	foreach($students as $student){
		try {
			
			$i++;
			
			if($i == 1){
				$page = imagecreatefrompng(($page_n+$even_odd)%2 == 0 ? $page_even : $page_odd);
				$color_name = imagecolorallocate($page, 0, 0, 0);
				$color_letter = imagecolorallocate($page, 103, 255, 247);
				$color_page = imagecolorallocate($page, 255, 14, 255);
				$color_bg = imagecolorallocate($page, 255, 255, 255);
				
				// Page title
				imagettftextbox($page, 70, 0, 165, 217, $color_page, $space_age, 'Repertoire', 1490, ALIGN_CENTER);
				
				// Page number
				imagettftextbox($page, 50, 0, ($page_n+$even_odd)%2 == 0 ? 270 : 1400, 2305, $color_page, $space_age, $page_n, 160, ALIGN_CENTER);
				
				$add_y = 0;
			}
			
			// bool imagecopyresampled ( resource $dst_image , resource $src_image , int $dst_x , int $dst_y , int $src_x , int $src_y , int $dst_w , int $dst_h , int $src_w , int $src_h )
			// array imagettftext ( resource $image , float $size , float $angle , int $x , int $y , int $color , string $fontfile , string $text )
			// bool imageline ( resource $image , int $x1 , int $y1 , int $x2 , int $y2 , int $color )
			
			// Letter
			if($letter != strtoupper($student['lastname'][0])){
				$letter = strtoupper($student['lastname'][0]);
				imagettftext($page, 70, 0, $base_x+40, $base_y+$add_y+17, $color_letter, $space_age, $letter);
			}
			
			// Name
			$name = $student['lastname'].' '.$student['firstname'];
			//if($name == 'Sarrauste de Menthiere Pierre-Alexandre')
			//	$name = 'Sarrauste de Menthiere P-A';
			$promo = 'Promo '.$student['promo'].' - '.$promos_names[(int) $student['promo']];
			$bounds = imagettftext($page, 28, 0, $base_x+200, $base_y+$add_y, $color_name, $font_arista, $name);
			imagettftext($page, 28, 0, $base_x+950, $base_y+$add_y, $color_name, $font_arista_light, $promo);
			imagesetstyle($page, array($color_bg, $color_bg, $color_bg, $color_bg, $color_name, $color_name));
			imageline($page, $bounds[2], $bounds[3]-5, $base_x+940, $bounds[3]-5, IMG_COLOR_STYLED);
			$add_y += 43;
			
			if($i==$students_per_page){
				// Saving...
				$page_file = 'page-'.$page_n.'-repertoire.png';
				imagepng($page, $annuaire_tmp_path.'/pages/'.$page_file);
				echo $page_file." OK\n";
				ob_flush();
				
				// Next page
				$i = 0;
				$page_n++;
			}
			
		}catch(Exception $e){
			echo $e->getMessage()."\n";
		}
	}
	*/
	
	
	
	
	
	
	
	// Anniversaires
	/*
	$students_per_page = 45;
	$base_x = 220;
	$base_y = 360;
	
	$starting_page = 1;
	
	$page_n = $starting_page;
	$even_odd = $page_n%2;
	$i = 0;
	$month = 0;
	
	foreach($birthdays as $data){
		try {
			
			$i++;
			
			if($i == 1){
				$page = imagecreatefrompng(($page_n+$even_odd)%2 == 0 ? $page_even : $page_odd);
				$color_name = imagecolorallocate($page, 0, 0, 0);
				$color_month = imagecolorallocate($page, 103, 255, 247);
				$color_page = imagecolorallocate($page, 255, 14, 255);
				$color_bg = imagecolorallocate($page, 255, 255, 255);
				
				// Page title
				imagettftextbox($page, 70, 0, 165, 217, $color_page, $space_age, 'Anniversaires', 1490, ALIGN_CENTER);
				
				// Page number
				imagettftextbox($page, 50, 0, ($page_n+$even_odd)%2 == 0 ? 270 : 1400, 2305, $color_page, $space_age, $page_n, 160, ALIGN_CENTER);
				
				$add_y = 0;
			}
			
			// bool imagecopyresampled ( resource $dst_image , resource $src_image , int $dst_x , int $dst_y , int $src_x , int $src_y , int $dst_w , int $dst_h , int $src_w , int $src_h )
			// array imagettftext ( resource $image , float $size , float $angle , int $x , int $y , int $color , string $fontfile , string $text )
			// bool imageline ( resource $image , int $x1 , int $y1 , int $x2 , int $y2 , int $color )
			
			
			$time = strtotime($data['birthday']);
			
			// Month
			$month_ = (int) date('n', $time);
			if($month != $month_){
				$month = $month_;
				imagettftext($page, $month==9 ? 35 : 40, 0, $base_x, $base_y+$add_y+5, $color_month, $space_age, Date::getMonthByNum((int) $month));
			}
			
			// Name
			imagettftext($page, 28, 0, $base_x+400, $base_y+$add_y, $color_name, $font_arista_light, date('d/m/Y', $time));
			imagettftext($page, 28, 0, $base_x+650, $base_y+$add_y, $color_name, $font_arista, $data['name']);
			$add_y += 43;
			
			if($i==$students_per_page){
				// Saving...
				$page_file = 'page-'.$page_n.'-anniversaires.png';
				imagepng($page, $annuaire_tmp_path.'/pages/'.$page_file);
				echo $page_file." OK\n";
				ob_flush();
				
				// Next page
				$i = 0;
				$page_n++;
			}
			
		}catch(Exception $e){
			echo $e->getMessage()."\n";
		}
	}
	*/
	
	
	
	
	
	
	// Pages des élèves avec photos
	
	$photo_width = 250;
	$photo_height = 333;
	
	$students_per_page = 8;
	$base_x = 215;
	$base_y = 370;
	$diff_x = 700;
	$diff_y = 500;
	
	//$students_per_page = 10;
	//$base_x = 200;
	//$base_y = 280;
	//$diff_x = 730;
	//$diff_y = 415;
	
	$photo_default_path = $annuaire_tmp_path.'/photo.jpg';
	
	$starting_pages = array(
		2015	=> 1,
		2014	=> 1,
		2013	=> 1,
		2012	=> 1,
		2011	=> 1
	);
	
	foreach($promos as $promo_number => $promo){
		$page_n = $starting_pages[$promo_number];
		$even_odd = $page_n%2;
		$i = 0;
		
		foreach($promo as $student){
			try {
				//throw new Exception($student['promo'].' - '.$student['lastname']);
				
				$original_path = $avatars_tmp_path.'/'.$student['student_number'].'.jpg';
				$photo_path = $annuaire_tmp_path.'/photos/'.$student['student_number'].'.jpg';
				if(!file_exists($photo_path)){
					if(file_exists($original_path)){
						$img = new Image();
						$img->load($original_path);
						$img->thumb($photo_width, 400);
						$img->save($photo_path);
						unset($img);
					}else if(!isset($student['address']) && !isset($student['phone']) && !isset($student['cellphone']) && !isset($student['birthday'])){
						continue;
					}
				}
				
				
				$i++;
				$add_x = (1-$i%2) * $diff_x;
				$add_y = (ceil($i/2)-1) * $diff_y;
				$add_detail_x = 270;
				$add_detail_y = 0;
				
				if($i == 1){
					$page = imagecreatefrompng(($page_n+$even_odd)%2 == 0 ? $page_even : $page_odd);
					$color_name = imagecolorallocate($page, 0, 0, 73);
					$color_details = imagecolorallocate($page, 0, 0, 0);
					$color_page = imagecolorallocate($page, 255, 14, 255);
					
					// Page title
					imagettftextbox($page, 70, 0, 165, 217, $color_page, $space_age, 'Promo '.$promo_number.' - '.$promos_names[$promo_number], 1490, ALIGN_CENTER);
					
					// Page number
					imagettftextbox($page, 50, 0, ($page_n+$even_odd)%2 == 0 ? 270 : 1400, 2305, $color_page, $space_age, $page_n, 160, ALIGN_CENTER);
				}
				
				// bool imagecopy ( resource $dst_im , resource $src_im , int $dst_x , int $dst_y , int $src_x , int $src_y , int $src_w , int $src_h )
				// bool imagecopyresampled ( resource $dst_image , resource $src_image , int $dst_x , int $dst_y , int $src_x , int $src_y , int $dst_w , int $dst_h , int $src_w , int $src_h )
				// array imagettftext ( resource $image , float $size , float $angle , int $x , int $y , int $color , string $fontfile , string $text )
				
				// Photo
				$photo = imagecreatefromjpeg(file_exists($photo_path) ? $photo_path : $photo_default_path);
				imagecopy($page, $photo, $base_x+$add_x, $base_y+$add_y, 0, 0, $photo_width, $photo_height);
				
				// Name
				$name = $student['firstname'].' '.$student['lastname'];
				$height = imagettftextbox($page, 30, 0, $base_x+$add_x+$add_detail_x, $base_y+$add_y+$add_detail_y+7, $color_name, $font_arista, $name, 390);
				$add_detail_y += $height+45;
				
				// Birthday
				if($student['birthday'] != '0000-00-00'){
					imagecopy($page, $icon_birthday, $base_x+$add_x+$add_detail_x, $base_y+$add_y+$add_detail_y, 0, 0, 48, 48);
					$height = imagettftextbox($page, 25, 0, $base_x+$add_x+$add_detail_x+60, $base_y+$add_y+$add_detail_y+10, $color_details, $font_arista_light, date('d/m/Y', strtotime($student['birthday'])), 370);
					$add_detail_y += $height+30;
				}
				
				// Email
				$mail = '';
				if($student['mail'] != '' && !preg_match('#@isep\.fr$#', $student['mail']))
					$mail = $student['mail'];
				else if($student['msn'] != '' && !preg_match('#@isep\.fr$#', $student['msn']))
					$mail = $student['msn'];
				if($mail != ''){
					imagecopy($page, $icon_mail, $base_x+$add_x+$add_detail_x, $base_y+$add_y+$add_detail_y, 0, 0, 48, 48);
					$height = imagettftextbox($page, 25, 0, $base_x+$add_x+$add_detail_x+60, $base_y+$add_y+$add_detail_y+10, $color_details, $font_arista_light, $mail, 370);
					$add_detail_y += $height+30;
				}
					
				// Email ISEP
				imagecopy($page, $icon_mail, $base_x+$add_x+$add_detail_x, $base_y+$add_y+$add_detail_y, 0, 0, 48, 48);
				$height = imagettftextbox($page, 25, 0, $base_x+$add_x+$add_detail_x+60, $base_y+$add_y+$add_detail_y+10, $color_details, $font_arista_light, $student['username'].'@isep.fr', 370);
				$add_detail_y += $height+30;
				
				// Cellphone
				if($student['cellphone'] != ''){
					imagecopy($page, $icon_cellphone, $base_x+$add_x+$add_detail_x, $base_y+$add_y+$add_detail_y, 0, 0, 48, 48);
					$height = imagettftextbox($page, 25, 0, $base_x+$add_x+$add_detail_x+60, $base_y+$add_y+$add_detail_y+10, $color_details, $font_arista_light, $student['cellphone'], 370);
					$add_detail_y += $height+30;
				}
				
				// Phone
				if($student['phone'] != ''){
					imagecopy($page, $icon_phone, $base_x+$add_x+$add_detail_x, $base_y+$add_y+$add_detail_y, 0, 0, 48, 48);
					$height = imagettftextbox($page, 25, 0, $base_x+$add_x+$add_detail_x+60, $base_y+$add_y+$add_detail_y+10, $color_details, $font_arista_light, $student['phone'], 370);
					$add_detail_y += $height+30;
				}
				
				// Address
				if($student['address'] != ''){
					$address = $student['address'].
						(isset($student['zipcode']) || $student['city']!=''
							?	"\n".
								(isset($student['zipcode']) ? $student['zipcode'] : '').
								($student['city']!='' ? (isset($student['zipcode']) ? ' ' : '').$student['city'] : '')
							: ''
						);
					$address = str_replace(array(', ', ' - '), "\n", $address);
					imagecopy($page, $icon_address, $base_x+$add_x+$add_detail_x, $base_y+$add_y+$add_detail_y, 0, 0, 48, 48);
					//imagettftext($page, 27, 0, $base_x+$add_x+$add_detail_x+60, $base_y+$add_y+$add_detail_y+40, $color_details, $font_arista_light, $address);
					$height = imagettftextbox($page, 25, 0, $base_x+$add_x+$add_detail_x+60, $base_y+$add_y+$add_detail_y+10, $color_details, $font_arista_light, $address, 370);
				}
				
				if($i==$students_per_page){
					// Saving...
					$page_file = 'page-'.$page_n.'-promo'.$promo_number.'.png';
					imagepng($page, $annuaire_tmp_path.'/pages/'.$page_file);
					echo $page_file." OK\n";
					ob_flush();
					
					// Next page
					$i = 0;
					$page_n++;
				}
				
			}catch(Exception $e){
				echo $e->getMessage()."\n";
			}
		}
		
	}
	
	
	
	
	
}catch(Exception $e){
	echo $e->getMessage();
}
