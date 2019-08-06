<?php

class Bpjs
{
	var $mode	= 1;
	var $uid    = "x"; //ex: 12873 jangan lupa isikan di sini ya 
	var $secret = "x"; //ex: jangan lupa isikan ya1rs2hs3
	var $url  	= "https://dvlp.bpjs-kesehatan.go.id/VClaim-rest/";
	var $inacbg_url = "";


	function __construct()
	{
		// $this->uid = Yii::app()->user->getState('bpjs_uid');
		// $this->secret = Yii::app()->user->getState('bpjs_secret');
		// $this->url = Yii::app()->user->getState('bpjs_host');
		// $this->inacbg_url = Yii::app()->user->getState('bpjs_inacbg_path');
	}

	function output($content)
	{
		echo $content;
	}

	private function HashBPJS($args = '')
	{
		$uid = $this->uid;
		date_default_timezone_set('UTC');
		$timestmp = strval(time() - strtotime('1970-01-01 00:00:00'));
		$str = $uid . "&" . $timestmp;
		$secret = $this->secret;
		$hasher = base64_encode(hash_hmac('sha256', utf8_encode($str), utf8_encode($secret), TRUE)); //signature;
		return array($uid, $timestmp, $hasher);
	}

	private function request($url, $hashsignature, $uid, $timestmp, $method = '', $myvars = '', $contentType = null)
	{
		$session = curl_init($url);
		$arrheader =  array(
			'X-cons-id: ' . $uid,
			'X-timestamp: ' . $timestmp,
			'X-signature: ' . $hashsignature,
			// 'Accept: application/json',
			// 'Content-Type: Application/x-www-form-urlencoded',
			//'Content-Type: application/xml; charset=utf-8',
		);

		if (!empty($contentType)) {
			array_push($arrheader, $contentType);
		} else {
			array_push($arrheader, 'Content-Type: application/xml; charset=utf-8');
		}
		// var_dump($url);

		curl_setopt($session, CURLOPT_URL, $url);
		curl_setopt($session, CURLOPT_HTTPHEADER, $arrheader);
		curl_setopt($session, CURLOPT_VERBOSE, true);

		switch ($method) {
			case 'POST':
				curl_setopt($session, CURLOPT_POST, true);
				curl_setopt($session, CURLOPT_POSTFIELDS, $myvars);
				break;
			case 'PUT':
				curl_setopt($session, CURLOPT_CUSTOMREQUEST, "PUT");
				curl_setopt($session, CURLOPT_POSTFIELDS, $myvars);
				break;
			case 'DELETE':
				curl_setopt($session, CURLOPT_CUSTOMREQUEST, "DELETE");
				curl_setopt($session, CURLOPT_POSTFIELDS, $myvars);
				break;
		}

		curl_setopt($session, CURLOPT_RETURNTRANSFER, TRUE);
		// var_dump($session);
		$response = curl_exec($session);
		return $response;
	}

	function identity_magic()
	{
		list($uid, $timestmp, $hashsignature) = $this->HashBPJS();

		echo 'Server: ' . $this->url . '<br>';
		echo 'x-cons-id: ' . $uid . '<br>';
		echo 'x-timestamp: ' . $timestmp . '<br>';
		echo 'x-signature: ' . $hashsignature . '<br>';
		echo 'Accept: application/json' . '<br>';
		echo 'Content-Type: application/xml; charset=utf-8' . '<br>';
	}

	function help()
	{
		$url = $this->url . '/help';
		$session = curl_init($url);
		curl_setopt($session, CURLOPT_URL, $url);
		curl_setopt($session, CURLOPT_VERBOSE, true);
		curl_setopt($session, CURLOPT_RETURNTRANSFER, TRUE);
		$response = curl_exec($session);
		return $response;
	}

	// Referensi Diagnosa New	
	function referensi_diagnosa($query)
	{
		list($uid, $timestmp, $hashsignature) = $this->HashBPJS();
		$completeUrl = $this->url . '/referensi/diagnosa/' . $query;
		return $this->request($completeUrl, $hashsignature, $uid, $timestmp);
	}

	// Referensi Poliklinik New
	function referensi_poli($query)
	{
		// var_dump($query);
		list($uid, $timestmp, $hashsignature) = $this->HashBPJS();
		$completeUrl = $this->url . '/referensi/poli/' . $query;
		return $this->request($completeUrl, $hashsignature, $uid, $timestmp);
	}

	// Referensi Faskes New
	function referensi_fasilitas($nama, $jenisFaskes = 1)
	{
		list($uid, $timestmp, $hashsignature) = $this->HashBPJS();
		$completeUrl = $this->url . '/referensi/faskes/' . $nama . '/' . $jenisFaskes;
		return $this->request($completeUrl, $hashsignature, $uid, $timestmp);
	}

	// Referensi Dokter DPJP New // jnsPelayanan [ 1, Rawat inap 2, Rawat jalan] tglPelayanan = yyyy-mm-dd kdSpesialis = [INT, SAR, MAT]
	function referensi_dokter_dpjp($jnsPelayanan, $tglPelayanan, $spesialis)
	{
		list($uid, $timestmp, $hashsignature) = $this->HashBPJS();
		$completeUrl = $this->url . '/referensi/dokter/pelayanan/' . $jnsPelayanan . '/tglPelayanan' . '/' . $tglPelayanan . '/Spesialis' . '/' . $spesialis;
		return $this->request($completeUrl, $hashsignature, $uid, $timestmp);
	}

	// Referensi Propinsi New
	function referensi_propinsi()
	{
		list($uid, $timestmp, $hashsignature) = $this->HashBPJS();
		$completeUrl = $this->url . '/referensi/propinsi';
		return $this->request($completeUrl, $hashsignature, $uid, $timestmp);
	}

	// Referensi kabupaten New
	function referensi_kabupaten($kdPropinsi)
	{
		list($uid, $timestmp, $hashsignature) = $this->HashBPJS();
		$completeUrl = $this->url . '/referensi/kabupaten/propinsi/' . $kdPropinsi;
		return $this->request($completeUrl, $hashsignature, $uid, $timestmp);
	}

	// Referensi Propinsi New
	function referensi_kecamatan($kdKabupaten)
	{
		list($uid, $timestmp, $hashsignature) = $this->HashBPJS();
		$completeUrl = $this->url . '/referensi/kecamatan/kabupaten/' . $kdKabupaten;
		return $this->request($completeUrl, $hashsignature, $uid, $timestmp);
	}

	// endpoint baru pencarian hak kelas by nomer kartu
	function search_kartu($noKartu)
	{
		list($uid, $timestmp, $hashsignature) = $this->HashBPJS();
		$completeUrl = $this->url . '/peserta/nokartu/' . $noKartu. '/tglSEP'.'/'. date('Y-m-d');
		//echo $completeUrl; die;
		$dat = CJSON::decode($this->request($completeUrl, $hashsignature, $uid, $timestmp));
		if ($dat['metadata']['code'] == 200) {
			$k = KelaspelayananM::model()->findByAttributes(array(
				'kelasbpjs_id' => $dat['response']['peserta']['hakKelas']['kode'],
			));
			$dat['response']['peserta']['hakKelas']['kode'] = $k->kelaspelayanan_id;
		}
		return CJSON::encode($dat); //$this->request($completeUrl, $hashsignature, $uid, $timestmp);
	}

	// endpoint baru pencari hak kelas new
	function search_nik($query)
	{
		list($uid, $timestmp, $hashsignature) = $this->HashBPJS();
		$completeUrl = $this->url . '/peserta/nik/' . $query. '/tglSEP'. '/'.date('Y-m-d');
		// return $this->request($completeUrl, $hashsignature, $uid, $timestmp);
		$dat = CJSON::decode($this->request($completeUrl, $hashsignature, $uid, $timestmp));
		if ($dat['metaData']['code'] == 200) {
			$k = KelaspelayananM::model()->findByAttributes(array(
				'kelasbpjs_id' => $dat['response']['peserta']['hakKelas']['kode'],
			));
			$dat['response']['peserta']['hakKelas']['kode'] = $k->kelaspelayanan_id;
		}
		return CJSON::encode($dat); //$this->request($completeUrl, $hashsignature, $uid, $timestmp);
	}

	// endpoint baru pencari rujukan  new
	function search_rujukan_no_rujukan($query)
	{
		list($uid, $timestmp, $hashsignature) = $this->HashBPJS();
		$completeUrl = $this->url . '/Rujukan/' . $query;

		$res = CJSON::decode($this->request($completeUrl, $hashsignature, $uid, $timestmp));
		// $res = CJSON::decode($this->test_json_no_rujukan);
		if ($res['metaData']['code'] == 200) {
			$item = $res['response']['rujukan'];
			$item['tglKunjungan'] = MyFormatter::formatDateTimeForUser($item['tglKunjungan']);
			$item['peserta']['tglLahir'] = MyFormatter::formatDateTimeForUser($item['peserta']['tglLahir']);
			$item['peserta']['tglTAT'] = MyFormatter::formatDateTimeForUser($item['peserta']['tglTAT']);
			$item['peserta']['tglTMT'] = MyFormatter::formatDateTimeForUser($item['peserta']['tglTMT']);
			$res['response']['rujukan'] = $item;
		}

		return CJSON::encode($res);
		// return $this->request($completeUrl, $hashsignature, $uid, $timestmp);	
	}

	// ini endpoint RUjukan new
	function search_rujukan_no_bpjs($query)
	{
		list($uid, $timestmp, $hashsignature) = $this->HashBPJS();
		$completeUrl = $this->url . '/Rujukan/Peserta/' . $query;

		$res = CJSON::decode($this->request($completeUrl, $hashsignature, $uid, $timestmp));
		// $res = CJSON::decode($this->test_json_rujukan);
		if ($res['metaData']['code'] == 200) {
			$item = $res['response']['rujukan'];
			$item['tglKunjungan'] = MyFormatter::formatDateTimeForUser($item['tglKunjungan']);
			$item['peserta']['tglLahir'] = MyFormatter::formatDateTimeForUser($item['peserta']['tglLahir']);
			$item['peserta']['tglTAT'] = MyFormatter::formatDateTimeForUser($item['peserta']['tglTAT']);
			$item['peserta']['tglTMT'] = MyFormatter::formatDateTimeForUser($item['peserta']['tglTMT']);
			$res['response']['rujukan'] = $item;
		}

		return CJSON::encode($res);
	}

	// ini endpoint RUjukan new
	function search_rujukan_rs_no_rujukan($query)
	{
		list($uid, $timestmp, $hashsignature) = $this->HashBPJS();
		$completeUrl = $this->url . '/Rujukan/RS/' . $query;
		$res = CJSON::decode($this->request($completeUrl, $hashsignature, $uid, $timestmp));
		// $res = CJSON::decode($this->test_json_rujukan_rs);
		if ($res['metaData']['code'] == 200) {
			$item = $res['response']['rujukan'];
			$item['tglKunjungan'] = MyFormatter::formatDateTimeForUser($item['tglKunjungan']);
			$item['peserta']['tglLahir'] = MyFormatter::formatDateTimeForUser($item['peserta']['tglLahir']);
			$item['peserta']['tglTAT'] = MyFormatter::formatDateTimeForUser($item['peserta']['tglTAT']);
			$item['peserta']['tglTMT'] = MyFormatter::formatDateTimeForUser($item['peserta']['tglTMT']);
			$res['response']['rujukan'] = $item;
		}

		return CJSON::encode($res);
	}

	// ini endpoint RUjukan new
	function search_rujukan_rs_no_bpjs($query)
	{
		list($uid, $timestmp, $hashsignature) = $this->HashBPJS();
		$completeUrl = $this->url . '/Rujukan/RS/Peserta/' . $query;
		$res = CJSON::decode($this->request($completeUrl, $hashsignature, $uid, $timestmp));
		// $res = CJSON::decode($this->test_json_rujukan_rs);
		if ($res['metaData']['code'] == 200) {
			$item = $res['response']['rujukan'];
			$item['tglKunjungan'] = MyFormatter::formatDateTimeForUser($item['tglKunjungan']);
			$item['peserta']['tglLahir'] = MyFormatter::formatDateTimeForUser($item['peserta']['tglLahir']);
			$item['peserta']['tglTAT'] = MyFormatter::formatDateTimeForUser($item['peserta']['tglTAT']);
			$item['peserta']['tglTMT'] = MyFormatter::formatDateTimeForUser($item['peserta']['tglTMT']);
			$res['response']['rujukan'] = $item;
		}

		return CJSON::encode($res);
	}

	// endpoint rujukan pcar by nomer kartu masa 30 hari
	function list_rujukan_tanggal($noKartu, $start, $limit)
	{
		list($uid, $timestmp, $hashsignature) = $this->HashBPJS();
		$completeUrl = $this->url . '/Rujukan/List/Peserta/' . $noKartu;
		return $this->request($completeUrl, $hashsignature, $uid, $timestmp);
	}

	// endpoint rujukan rs by nomer kartu masa 30 hari
	function list_rujukan_rs_tanggal($noKartu, $start, $limit)
	{
		list($uid, $timestmp, $hashsignature) = $this->HashBPJS();
		$completeUrl = $this->url . '/Rujukan/RS/List/Peserta/' . $noKartu;
		return $this->request($completeUrl, $hashsignature, $uid, $timestmp);
	}

	// Endpint dan maping Pembuatan SEP new 
	function create_sep($nokartu, $tglsep, $tglrujukan, $norujukan, $ppkrujukan, $ppkpelayanan, $jnspelayanan, $catatan, $diagawal, $politujuan, $klsrawat, $user, $nomr, $no_trans, $lakaLantas)
	{
		$query = array(
			'request' => array(
				't_sep' => array(
					'noKartu' => $nokartu,
					'tglSep' => $tglsep,
					"ppkPelayanan" => $ppkpelayanan,
					"jnsPelayanan" => $jnspelayanan,
					"klsRawat" => $klsrawat,
					"noMR" => $nomr,
					"rujukan" => [
						"asalRujukan" =>  'variable', //Isikan asal rujukan
						"tglRujukan" => $tglrujukan,
						"noRujukan" => $norujukan,
						"ppkRujukan" => $ppkrujukan
					],
					"catatan" => $catatan,
					"diagAwal" => $diagawal,
					"poli" => [
						"tujuan" => $politujuan, //ISIKAN KODE POLIKLINIK contoh INT, PAR, GIG
						"eksekutif" => "0"  // isikan Esekutif jika ada
					],
					"cob" => [
						"cob" => "0", // isikan COB jika ada isikan 1 jika tidak 0
					],
					"katarak" => [
						"katarak" => "0", // isikan 1 jika ya 0 jika tidak 
					],
					"jaminan" => [
						"lakaLantas" => $lakaLantas, // 0 tidak 1 jika iya 
						"penjamin" => [
							"penjamin" => "1", // 1=Jasa raharja PT, 2=BPJS Ketenagakerjaan, 3=TASPEN PT, 4=ASABRI PT} jika lebih dari 1 isi -> 1,2 (pakai delimiter koma
							"tglKejadian" => "2019-01-01", // sikan tanggal kejadian kecelakaan
							"keterangan" => "kll", // iisikan keterangan kll
							"suplesi" => [
								"suplesi" => "1", //0 tidak 1 ya
								"noSepSuplesi" => "01231923V283423", //Noo.SEP yang Jika Terdapat Suplesi,
								"lokasiLaka" => [
									"kdPropinsi" => "02",
									"kdKabupaten" => "0050",
									"kdKecamatan" => "0574"
								]
							]
						]
					],
					"skdp" => [
						"noSurat" => "123456", // NO Surat kontrol
						"kodeDPJP" => "654321" // Kd DPJP pemberi surat kontrol
					],
					"noTelp" => "082220801333",
					"user" => "Nama Rumah Sakit"
				),
			),
		);

		foreach ($query['request']['t_sep'] as $attr => $item) {
			$query['request']['t_sep'][$attr] = (string) $item;
		}

		// var_dump($query);
		// die;

		list($uid, $timestmp, $hashsignature) = $this->HashBPJS();

		$completeUrl = $this->url . '/SEP/1.1/insert';

		$result = $this->request($completeUrl, $hashsignature, $uid, $timestmp, 'POST', CJSON::encode($query), 'Application/x‐www‐form‐urlencoded');
		// echo($result); die;
		$result = json_decode($result, true);
		// var_dump($result); die;

		$final_result['response'] = $result['response'];
		$final_result['metadata'] = $result['metaData'];

		$this->mapping_trans($result['response'], $no_trans, $ppkpelayanan);
		return json_encode($final_result);
	}

	// Endpint dan maping Update SEP new 
	function update_sep($nosep, $nokartu, $tglsep, $tglrujukan, $norujukan, $ppkrujukan, $ppkpelayanan, $jnspelayanan, $catatan, $diagawal, $politujuan, $klsrawat, $user, $nomr, $no_trans, $lakaLantas)
	{
		$query = array(
			'request' => array(
				't_sep' => array(
					'noKartu' => $nokartu,
					'tglSep' => $tglsep,
					"ppkPelayanan" => $ppkpelayanan,
					"jnsPelayanan" => $jnspelayanan,
					"klsRawat" => $klsrawat,
					"noMR" => $nomr,
					"rujukan" => [
						"asalRujukan" =>  'variable', //Isikan asal rujukan
						"tglRujukan" => $tglrujukan,
						"noRujukan" => $norujukan,
						"ppkRujukan" => $ppkrujukan
					],
					"catatan" => $catatan,
					"diagAwal" => $diagawal,
					"poli" => [
						"tujuan" => $politujuan, //ISIKAN KODE POLIKLINIK contoh INT, PAR, GIG
						"eksekutif" => "0"  // isikan Esekutif jika ada
					],
					"cob" => [
						"cob" => "0", // isikan COB jika ada isikan 1 jika tidak 0
					],
					"katarak" => [
						"katarak" => "0", // isikan 1 jika ya 0 jika tidak 
					],
					"jaminan" => [
						"lakaLantas" => $lakaLantas, // 0 tidak 1 jika iya 
						"penjamin" => [
							"penjamin" => "1", // 1=Jasa raharja PT, 2=BPJS Ketenagakerjaan, 3=TASPEN PT, 4=ASABRI PT} jika lebih dari 1 isi -> 1,2 (pakai delimiter koma
							"tglKejadian" => "2019-01-01", // sikan tanggal kejadian kecelakaan
							"keterangan" => "kll", // iisikan keterangan kll
							"suplesi" => [
								"suplesi" => "1", //0 tidak 1 ya
								"noSepSuplesi" => "01231923V283423", //Noo.SEP yang Jika Terdapat Suplesi,
								"lokasiLaka" => [
									"kdPropinsi" => "02",
									"kdKabupaten" => "0050",
									"kdKecamatan" => "0574"
								]
							]
						]
					],
					"skdp" => [
						"noSurat" => "123456", // NO Surat kontrol
						"kodeDPJP" => "654321" // Kd DPJP pemberi surat kontrol
					],
					"noTelp" => "082220801333",
					"user" => "Nama Rumah Sakit"
				),
			),
		);

		foreach ($query['request']['t_sep'] as $attr => $item) {
			$query['request']['t_sep'][$attr] = (string) $item;
		}

		// var_dump($query);
		// die;

		// echo "<pre>".CHtml::encode($query)."</pre>"; die;
		//var_dump($this->HashBPJS());
		list($uid, $timestmp, $hashsignature) = $this->HashBPJS();

		$completeUrl = $this->url . '/SEP/1.1/Update';
		// echo $completeUrl; die;

		$result = $this->request($completeUrl, $hashsignature, $uid, $timestmp, 'POST', CJSON::encode($query), 'Application/x‐www‐form‐urlencoded');
		// echo($result); die;
		$result = json_decode($result, true);
		// var_dump($result); die;

		$final_result['response'] = $result['response'];
		$final_result['metadata'] = $result['metaData'];

		$this->mapping_trans($result['response'], $no_trans, $ppkpelayanan);
		return json_encode($final_result);
	}

	// enpoin update tanggal pulang sep new
	function update_tanggal_pulang_sep($nosep, $tglpulang, $ppkpelayanan)
	{

		$query = array(
			'request' => array(
				't_sep' => array(
					'noSep' => $nosep,
					'tglPlg' => $tglpulang,
					'ppkPelayanan' => $ppkpelayanan,
				)
			)
		);

		foreach ($query['request']['t_sep'] as $attr => $item) {
			$query['request']['t_sep'][$attr] = (string) $item;
		}

		list($uid, $timestmp, $hashsignature) = $this->HashBPJS();
		$completeUrl = $this->url . '/Sep/updtglplg';
		return $this->request($completeUrl, $hashsignature, $uid, $timestmp, 'PUT', CJSON::encode($query), 'Application/x‐www‐form‐urlencoded');
	}

	function mapping_trans($nosep, $notrans, $ppkpelayanan)
	{
		$query = array(
			'request' => array(
				't_map_sep' => array(
					'noSep' => $nosep,
					'noTrans' => $notrans,
					'ppkPelayanan' => $ppkpelayanan,
				)
			)
		);

		foreach ($query['request']['t_map_sep'] as $attr => $item) {
			$query['request']['t_map_sep'][$attr] = (string) $item;
		}
		//echo CHtml::encode($query); die;

		list($uid, $timestmp, $hashsignature) = $this->HashBPJS();
		$completeUrl = $this->url . '/SEP/map/trans';
		return $this->request($completeUrl, $hashsignature, $uid, $timestmp, 'POST', CJSON::encode($query), 'Application/x‐www‐form‐urlencoded');
	}

	// endpoint hapus sep new
	function delete_sep($nosep, $ppkpelayanan)
	{
		$query = array(
			'request' => array(
				't_sep' => array(
					'noSep' => $nosep,
					'ppkPelayanan' => $ppkpelayanan,
				)
			)
		);

		foreach ($query['request']['t_sep'] as $attr => $item) {
			$query['request']['t_sep'][$attr] = (string) $item;
		}


		list($uid, $timestmp, $hashsignature) = $this->HashBPJS();
		$completeUrl = $this->url . '/SEP/Delete';
		return $this->request($completeUrl, $hashsignature, $uid, $timestmp, 'DELETE', CJSON::encode($query), 'Application/x‐www‐form‐urlencoded');
	}

	function delete_transaksi($nosep, $ppkpelayanan)
	{
		list($uid, $timestmp, $hashsignature) = $this->HashBPJS();
		$completeUrl = $this->url . '/SEP/sep';
		$query = '<request>
						<data>
							<t_map_sep>
								<noSep>' . $nosep . '</noSep>
								<ppkPelayanan>' . $ppkpelayanan . '</ppkPelayanan>
							</t_map_sep>
						</data>
					</request>';
		return $this->request($completeUrl, $hashsignature, $uid, $timestmp, 'DELETE', $query, 'Application/x‐www‐form‐urlencoded');
	}

	// ini udah tidak ada endpoint nya
	function riwayat_terakhir($query)
	{
		list($uid, $timestmp, $hashsignature) = $this->HashBPJS();
		$completeUrl = $this->url . '/sep/peserta/' . $query;
		return $this->request($completeUrl, $hashsignature, $uid, $timestmp);
	}

	// ini pencarian sep new
	function detail_sep($query)
	{
		list($uid, $timestmp, $hashsignature) = $this->HashBPJS();
		$completeUrl = $this->url . '/SEP' . '/' . $query;
		return $this->request($completeUrl, $hashsignature, $uid, $timestmp);
	}

	function detail_ppk_rujukan($query, $start, $limit)
	{
		list($uid, $timestmp, $hashsignature) = $this->HashBPJS();
		$completeUrl = $this->url . '/provider/ref/provider/query?nama=' . $query . '&start=' . $start . '&limit=' . $limit;
		return $this->request($completeUrl, $hashsignature, $uid, $timestmp);
	}

	function search_diagosa($query)
	{
		$vars = 'icd=' . $query . '&reqdata=diagnosa';
		list($uid, $timestmp, $hashsignature) = $this->HashBPJS();
		$completeUrl = $this->inacbg_url . '/icd.php';
		$res = CJSON::decode($this->request($completeUrl, $hashsignature, $uid, $timestmp, 'POST', $vars, 'Application/x‐www‐form‐urlencoded'));

		if (count($res) != 0) {
			$icd_code = array();
			foreach ($res as $key => $row) {
				$icd_code[$key] = $row['ICD_CODE'];
			}

			array_multisort($icd_code, SORT_ASC, $res);
		}

		return CJSON::encode($res);
	}

	function search_cbg_sep($query)
	{
		list($uid, $timestmp, $hashsignature) = $this->HashBPJS();
		$completeUrl = $this->url . '/sep/cbg/' . $query;
		return $this->request($completeUrl, $hashsignature, $uid, $timestmp);
	}

	function search_cbg($query)
	{
		$vars = 'icd=' . $query . '&reqdata=procedure';
		list($uid, $timestmp, $hashsignature) = $this->HashBPJS();
		$completeUrl = $this->inacbg_url . '/icd.php';
		$res = CJSON::decode($this->request($completeUrl, $hashsignature, $uid, $timestmp, 'POST', $vars, 'Application/x‐www‐form‐urlencoded'));

		if (count($res) != 0) {
			$icd_code = array();
			foreach ($res as $key => $row) {
				$icd_code[$key] = $row['ICD_CODE'];
			}

			array_multisort($icd_code, SORT_ASC, $res);
		}

		return CJSON::encode($res);
	}

	function search_cmg($query)
	{
		$vars = 'proc=' . $query . '&reqdata=cmg';
		list($uid, $timestmp, $hashsignature) = $this->HashBPJS();
		$completeUrl = $this->inacbg_url . '/ws_cmg.php';
		return $this->request($completeUrl, $hashsignature, $uid, $timestmp, 'POST', $vars, 'Application/x‐www‐form‐urlencoded');
	}

	function create_laporan_sep($query)
	{
		list($uid, $timestmp, $hashsignature) = $this->HashBPJS();
		$completeUrl = $this->url . '/sep/integrated/Kunjungan/sep/' . $query;
		return $this->request($completeUrl, $hashsignature, $uid, $timestmp);
	}

	function monitor_verifikasi_klaim($tglMasuk = null, $tglKeluar = null, $klsRawat = null, $kasus = null, $cari = null, $status = null)
	{
		$completeUrl = $this->url . '/sep/integrated/Kunjungan';

		if (!empty($tglMasuk)) $completeUrl .= '/tglMasuk/' . $tglMasuk;
		if (!empty($tglKeluar)) $completeUrl .= '/tglKeluar/' . $tglKeluar;
		if (!empty($klsRawat)) $completeUrl .= '/klsRawat/' . $klsRawat;
		if (!empty($kasus)) $completeUrl .= '/kasus/' . $kasus;
		if (!empty($cari)) $completeUrl .= '/Cari/' . $cari;
		if (!empty($status)) $completeUrl .= '/status/' . $status;

		list($uid, $timestmp, $hashsignature) = $this->HashBPJS();
		return $this->request($completeUrl, $hashsignature, $uid, $timestmp);
	}

	function create_grouper($query)
	{
		list($uid, $timestmp, $hashsignature) = $this->HashBPJS();
		$completeUrl = $this->inacbg_url . '/ca_grouper.php?' . $query;
		return $this->request($completeUrl, $hashsignature, $uid, $timestmp);
	}

	function create_finalisasi_grouper($query)
	{
		list($uid, $timestmp, $hashsignature) = $this->HashBPJS();
		$completeUrl = $this->url . '/gruper/grouper/save' . $query;
		return $this->request($completeUrl, $hashsignature, $uid, $timestmp);
	}
}
